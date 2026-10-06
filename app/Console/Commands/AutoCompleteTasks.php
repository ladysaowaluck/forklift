<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class AutoCompleteTasks extends Command
{
    protected $signature = 'tasks:auto-complete';

    protected $description = 'Auto confirm and rate tasks that have been Arrived for more than 3 hours';

    private const TIMEOUT_HOURS    = 0.02;  // 3 hour
    private const MAX_LOOKBACK_DAYS = 7; //prevents auto-rating ancient backlog tasks
    private const BATCH_LIMIT       = 200; //prevents memory spikes

    public function handle(): int
    {
        $timeoutThreshold  = now()->subHours(self::TIMEOUT_HOURS);
        $lookbackThreshold = now()->subDays(self::MAX_LOOKBACK_DAYS);

        // $pendingTasks = Transaction::where('status', 'Arrived')
        //     ->where('arrived_at', '<=', $threshold)
        //     ->get();

        $pendingTasks = Transaction::where('status', 'Arrived')
            ->whereNotNull('arrived_at')
            ->where('arrived_at', '<=', $timeoutThreshold)  // older than 3 hours
            ->where('arrived_at', '>=', $lookbackThreshold) // not older than 7 days
            ->orderBy('arrived_at', 'asc')
            ->limit(self::BATCH_LIMIT)
            ->get();

        $this->line('=================================================');
        $this->info('[DEBUG] tasks:auto-complete running at: ' . now()->format('Y-m-d H:i:s'));
        $this->line('[DEBUG] Looking for tasks arrived before: ' . $timeoutThreshold->format('Y-m-d H:i:s'));
        $this->line('[DEBUG] Safe lookback window start:       ' . $lookbackThreshold->format('Y-m-d H:i:s'));

        if ($pendingTasks->isEmpty()) {
            $totalArrived  = Transaction::where('status', 'Arrived')->count();
            $latestArrived = Transaction::where('status', 'Arrived')->latest('arrived_at')->first();

            $this->warn('[DEBUG] No eligible tasks found.');
            $this->line("[DEBUG] Total 'Arrived' tasks in DB: {$totalArrived}");

            if ($latestArrived?->arrived_at) {
                $this->line('[DEBUG] Most recent arrived_at: ' . $latestArrived->arrived_at->format('Y-m-d H:i:s')
                    . ' → eligible after: ' . $latestArrived->arrived_at->addHours(self::TIMEOUT_HOURS)->format('Y-m-d H:i:s'));
            }

            $this->line('=================================================');
            return Command::SUCCESS;
        }

        $this->info('[DEBUG] Found ' . $pendingTasks->count() . ' task(s) ready to auto-complete.');
        $this->line('=================================================');

        $count = 0;
        foreach ($pendingTasks as $task) {

            $completedAt = Carbon::parse($task->arrived_at)->addHours(self::TIMEOUT_HOURS);
            if ($completedAt->isFuture()) {
                $completedAt = now(); // safety cap — never set completed_at in the future
            }

            $this->line("[DEBUG] Task #{$task->transaction_id} | Driver: {$task->driver_id} | arrived_at: {$task->arrived_at} | completed_at → {$completedAt}");

            // $task->update([
            //     'status' => 'Completed',
            //     'driver_rating' => 5,
            //     'driver_comment' => 'Auto-confirmed by System (Timeout 3 hours)',
            //     'completed_at' => now(),
            // ]);
            // $count++;

            // Atomic update — only updates if status is STILL 'Arrived'.
            // Prevents overwriting a checker who manually rated the task at the same time.
            $updated = Transaction::where('transaction_id', $task->transaction_id)
                ->where('status', 'Arrived') // double-check status hasn't changed since we fetched
                ->update([
                    'status'         => 'Completed',
                    'driver_rating'  => 5,
                    'driver_comment' => 'Auto-confirmed by System (Timeout ' . self::TIMEOUT_HOURS . ' hours)',
                    'completed_at'   => $completedAt, // accurate historical timestamp, not now()
                    'updated_at'     => now(),
                ]);

            if ($updated) {
                $count++;
                $this->info(" → SUCCESS: Task #{$task->transaction_id} completed.");
                Log::info("AutoCompleteTasks: completed Task #{$task->transaction_id} at {$completedAt}");
            } else {
                $this->warn(" → SKIPPED: Task #{$task->transaction_id} already handled by checker.");
            }
        }

        $this->line('=================================================');
        $this->info("Successfully auto-completed {$count} tasks.");
        Log::info("AutoCompleteTasks: total auto-completed = {$count}");

        return Command::SUCCESS; // return int exit code — required for scheduled commands
    }
}