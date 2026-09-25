<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class AutoCompleteTasks extends Command
{
    protected $signature = 'tasks:auto-complete';

    protected $description = 'Auto confirm and rate tasks that have been Arrived for more than 3 hours';

    public function handle()
    {
        $threshold = now()->subHours(3);

        $pendingTasks = Transaction::where('status', 'Arrived')
            ->where('arrived_at', '<=', $threshold)
            ->get();

        $count = 0;
        foreach ($pendingTasks as $task) {
            $task->update([
                'status' => 'Completed',
                'driver_rating' => 5, 
                'driver_comment' => 'Auto-confirmed by System (Timeout 3 hours)',
                'completed_at' => now(),
            ]);
            $count++;
        }

        $this->info("Successfully auto-completed {$count} tasks.");
    }
}