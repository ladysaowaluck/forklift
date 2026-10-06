<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * =====================================================================
 * AutoCompleteTasksTest
 * =====================================================================
 * Tests for the "2000 jobs rated in one day" vulnerability fix.
 *
 * VULNERABILITY SUMMARY:
 *   The original code had NO lookback window and used now() as completed_at.
 *   When 2000 old stuck tasks existed, running the command once would:
 *     1. Pull ALL 2000 tasks from history in one shot
 *     2. Stamp completed_at = TODAY on all of them
 *     3. Dashboard shows 2000 "completed today" even though they are old
 *
 * THE FIX:
 *   - Lookback window: only process tasks from last 7 days
 *   - Batch limit: max 200 per run
 *   - Correct timestamp: completed_at = arrived_at + timeout (not now())
 *   - Atomic update: WHERE status='Arrived' check prevents re-processing
 *
 * RUN TESTS:
 *   php artisan test tests/Feature/AutoCompleteTasksTest.php
 * =====================================================================
 *
 * NOTE: TIMEOUT_HOURS is currently 0.02 (72 seconds) for testing.
 *       Change back to 3 for production.
 */
class AutoCompleteTasksTest extends TestCase
{
    use RefreshDatabase;

    // -----------------------------------------------------------------
    // Helper: Create a fake arrived task at a specific time
    // -----------------------------------------------------------------
    private function makeArrivedTask(Carbon $arrivedAt): Transaction
    {
        return Transaction::create([
            'status'         => 'Arrived',
            'arrived_at'     => $arrivedAt,
            'warehouse_from' => 1,
            'warehouse_to'   => 2,
        ]);
    }

    // =================================================================
    // TEST 1 
    // TITLE : Task that waited long enough gets auto-completed
    // PROVES: The basic happy-path — command works correctly
    // =================================================================
    public function test_task_that_exceeded_timeout_gets_auto_completed(): void
    {
        // A task that arrived 5 minutes ago (well past the 72-sec timeout)
        $task = $this->makeArrivedTask(now()->subMinutes(5));

        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        $task->refresh();
        $this->assertEquals('Completed', $task->status,
            ' FAIL: Task past timeout should be auto-completed');
        $this->assertEquals(5, $task->driver_rating,
            ' FAIL: Auto-completed task should receive 5-star rating');
        $this->assertStringContainsString('Auto-confirmed by System', $task->driver_comment,
            ' FAIL: Comment should say auto-confirmed');
    }

    // =================================================================
    // TEST 2 
    // TITLE : Task NOT old enough is left alone
    // PROVES: Command does not touch tasks that haven't timed out yet
    // =================================================================
    public function test_task_not_yet_timed_out_is_not_touched(): void
    {
        // A task that arrived just 10 seconds ago — under 72-sec timeout
        $task = $this->makeArrivedTask(now()->subSeconds(10));

        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        $task->refresh();
        $this->assertEquals('Arrived', $task->status,
            ' FAIL: Task under timeout should remain Arrived');
        $this->assertNull($task->driver_rating,
            ' FAIL: Task should not have been rated yet');
    }

    // =================================================================
    // TEST 3  CORE BUG — The "2000 jobs today" vulnerability
    // TITLE : Old stuck tasks are SKIPPED (lookback window guard)
    // PROVES: The original bug — without lookback, ALL historical tasks
    //         get processed at once, creating a fake spike in the dashboard
    //
    // ORIGINAL (buggy) behavior:
    //   Transaction::where('status','Arrived')
    //     ->where('arrived_at','<=',$threshold)
    //     ->get();  ← returns ALL 2000 stuck tasks from months ago
    //
    // FIXED behavior:
    //   Added ->where('arrived_at','>=',$lookbackThreshold)
    //   Tasks older than 7 days are SKIPPED → no spike
    // =================================================================
    public function test_old_tasks_beyond_lookback_window_are_NOT_processed(): void
    {
        // Simulate the real scenario: 5 tasks stuck for 10 days (beyond 7-day window)
        // In production this could be 2000 tasks from months ago
        $oldTasks = [];
        for ($i = 0; $i < 5; $i++) {
            $oldTasks[] = $this->makeArrivedTask(now()->subDays(10));
        }

        // Also create 1 eligible recent task (to confirm command still works)
        $recentTask = $this->makeArrivedTask(now()->subMinutes(5));

        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        // Recent task SHOULD be completed
        $this->assertEquals('Completed', $recentTask->fresh()->status,
            ' FAIL: Recent eligible task should be completed');

        // Old tasks (10 days) should NOT be touched — lookback window = 7 days
        foreach ($oldTasks as $index => $task) {
            $this->assertEquals('Arrived', $task->fresh()->status,
                " FAIL: Old task #{$index} (10 days) should NOT be auto-completed — lookback guard failed");
            $this->assertNull($task->fresh()->driver_rating,
                " FAIL: Old task #{$index} should not have a rating — would create fake spike in dashboard");
        }
    }

    // =================================================================
    // TEST 4  CORE BUG — Wrong completed_at timestamp
    // TITLE : Old task gets accurate completed_at (not today's date)
    // PROVES: The original bug stamped now() on old tasks, making
    //         a 3-day-old task appear as "completed today" in reports
    //
    // ORIGINAL (buggy):
    //   'completed_at' => now()  ← stamps TODAY on a 3-day-old task!
    //
    // FIXED:
    //   'completed_at' => arrived_at + TIMEOUT_HOURS
    //   → accurate historical timestamp, never pollutes today's stats
    // =================================================================
    public function test_completed_at_is_accurate_arrived_at_plus_timeout_NOT_now(): void
    {
        // A task that arrived 3 days ago and was stuck (within 7-day window)
        $arrivedAt = now()->subDays(3);
        $task = $this->makeArrivedTask($arrivedAt);

        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        $task->refresh();
        $this->assertEquals('Completed', $task->status);

        // Expected: arrived_at + TIMEOUT_HOURS (0.02h = 72 seconds)
        $expectedCompletedAt = $arrivedAt->copy()->addHours(0.02);

        // Allow 5-second tolerance for test execution time
        $this->assertEqualsWithDelta(
            $expectedCompletedAt->timestamp,
            $task->completed_at->timestamp,
            5,
            ' FAIL: completed_at should be arrived_at + timeout, NOT now(). ' .
            'Original bug stamped today\'s date on old tasks, creating 2000 fake completions today.'
        );

        // Extra safety: if task arrived 3 days ago, completed_at must NOT be today
        if (!$arrivedAt->isToday()) {
            $this->assertFalse(
                $task->completed_at->isToday(),
                ' FAIL: A task that arrived 3 days ago must NOT have completed_at = today. ' .
                'This is the root cause of the "2000 jobs today" bug.'
            );
        }
    }

    // =================================================================
    // TEST 5  Race Condition Guard
    // TITLE : Already-completed task is NEVER re-processed or re-rated
    // PROVES: The atomic WHERE status='Arrived' check prevents:
    //         - Overwriting a checker's manual rating
    //         - Double-counting the same task across scheduler runs
    // =================================================================
    public function test_completed_task_is_never_re_processed(): void
    {
        // A checker manually rated this task with 3 stars
        $task = Transaction::create([
            'status'         => 'Completed',
            'arrived_at'     => now()->subMinutes(10),
            'completed_at'   => now()->subMinutes(5),
            'driver_rating'  => 3,
            'driver_comment' => 'Manually rated by checker',
            'warehouse_from' => 1,
            'warehouse_to'   => 2,
        ]);

        // Simulate the scheduler firing 3 times
        $this->artisan('tasks:auto-complete')->assertExitCode(0);
        $this->artisan('tasks:auto-complete')->assertExitCode(0);
        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        $task->refresh();
        // Rating must remain 3 — not overwritten to 5 by auto-complete
        $this->assertEquals(3, $task->driver_rating,
            ' FAIL: Completed task rating should not be overwritten from 3 to 5');
        $this->assertEquals('Manually rated by checker', $task->driver_comment,
            ' FAIL: Completed task comment should not be overwritten');
    }

    // =================================================================
    // TEST 6  Batch Limit Guard
    // TITLE : Never processes more than 200 tasks in a single run
    // PROVES: Prevents memory spikes and runaway processing
    //         Without this, 2000 tasks could be processed at once
    // =================================================================
    public function test_batch_limit_caps_processing_at_200_per_run(): void
    {
        // Create 205 eligible tasks
        for ($i = 0; $i < 205; $i++) {
            $this->makeArrivedTask(
                // Slightly different arrived_at so ORDER BY arrived_at asc is predictable
                now()->subMinutes(5)->subSeconds($i)
            );
        }

        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        $completedCount = Transaction::where('status', 'Completed')->count();
        $arrivedCount   = Transaction::where('status', 'Arrived')->count();

        $this->assertEquals(200, $completedCount,
            ' FAIL: Batch limit should cap at 200 tasks per run — prevents memory/performance spike');
        $this->assertEquals(5, $arrivedCount,
            ' FAIL: Remaining 5 tasks should stay Arrived for the next scheduler run');
    }

    // =================================================================
    // TEST 7 🎯 Full Integration — Reproduces the exact vulnerability
    // TITLE : The "2000 jobs today" scenario — full end-to-end proof
    // PROVES: The original code would have rated 15 tasks (10 old + 5 new),
    //         all stamped today. The fixed code only rates 5 eligible recent tasks.
    //
    // SCENARIO:
    //   - 10 tasks stuck for 10 days (historical backlog — the "2000" in production)
    //   - 5 tasks arrived 5 min ago (legitimately eligible)
    //   - 3 tasks arrived 10 sec ago (not yet timed out)
    //   - 2 tasks already Completed (manually by checker)
    // =================================================================
    public function test_vulnerability_scenario_only_eligible_recent_tasks_are_processed(): void
    {
        // --- Arrange ---

        // 10 OLD backlog tasks (would have been 2000 in production)
        $oldBacklogTasks = [];
        for ($i = 0; $i < 10; $i++) {
            $oldBacklogTasks[] = $this->makeArrivedTask(now()->subDays(10));
        }

        // 5 legitimately eligible tasks (arrived 5 minutes ago)
        $eligibleTasks = [];
        for ($i = 0; $i < 5; $i++) {
            $eligibleTasks[] = $this->makeArrivedTask(now()->subMinutes(5)->subSeconds($i));
        }

        // 3 tasks not yet timed out (arrived 10 seconds ago)
        $notYetTasks = [];
        for ($i = 0; $i < 3; $i++) {
            $notYetTasks[] = $this->makeArrivedTask(now()->subSeconds(10));
        }

        // 2 tasks already completed by a checker
        $checkerTask1 = Transaction::create([
            'status' => 'Completed', 'arrived_at' => now()->subMinutes(20),
            'completed_at' => now()->subMinutes(15), 'driver_rating' => 4,
            'warehouse_from' => 1, 'warehouse_to' => 2,
        ]);
        $checkerTask2 = Transaction::create([
            'status' => 'Completed', 'arrived_at' => now()->subMinutes(30),
            'completed_at' => now()->subMinutes(25), 'driver_rating' => 2,
            'warehouse_from' => 1, 'warehouse_to' => 2,
        ]);

        // --- Act ---
        $this->artisan('tasks:auto-complete')->assertExitCode(0);

        // --- Assert: Old backlog tasks (should be SKIPPED) ---
        foreach ($oldBacklogTasks as $i => $task) {
            $this->assertEquals('Arrived', $task->fresh()->status,
                " FAIL: Old backlog task #{$i} should NOT be processed. " .
                "Without lookback guard, all 10 (or 2000) would be rated today.");
        }

        // --- Assert: Eligible recent tasks (should be COMPLETED) ---
        $todayCompletedCount = 0;
        foreach ($eligibleTasks as $i => $task) {
            $fresh = $task->fresh();
            $this->assertEquals('Completed', $fresh->status,
                " FAIL: Eligible task #{$i} should be auto-completed");
            $this->assertEquals(5, $fresh->driver_rating,
                " FAIL: Eligible task #{$i} should have rating 5");

            // Verify completed_at is NOT stamped with today blindly
            // It should be arrived_at + TIMEOUT (which for tasks 5min old, is still today
            // but WOULD differ for 3-day-old tasks)
            $this->assertNotNull($fresh->completed_at,
                " FAIL: completed_at must be set");

            if ($fresh->completed_at->isToday()) {
                $todayCompletedCount++;
            }
        }

        // --- Assert: Too-new tasks (should stay Arrived) ---
        foreach ($notYetTasks as $i => $task) {
            $this->assertEquals('Arrived', $task->fresh()->status,
                " FAIL: Not-yet-timed-out task #{$i} should remain Arrived");
        }

        // --- Assert: Checker-rated tasks (should be unchanged) ---
        $this->assertEquals(4, $checkerTask1->fresh()->driver_rating,
            ' FAIL: Checker rating 4 should not be overwritten to 5');
        $this->assertEquals(2, $checkerTask2->fresh()->driver_rating,
            ' FAIL: Checker rating 2 should not be overwritten to 5');

        // --- Assert: Total completed today = only 5 eligible (not 10+5=15) ---
        $totalCompleted = Transaction::where('status', 'Completed')->count();
        $this->assertEquals(7, $totalCompleted, // 5 auto + 2 checker
            ' FAIL: Only 7 tasks should be Completed (5 auto + 2 checker). ' .
            'Old backlog tasks must NOT be included. ' .
            "Without the fix, this would be 17 (10 old + 5 new + 2 checker).");
    }
}
