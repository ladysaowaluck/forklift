<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteOldImages extends Command
{
    protected $signature = 'images:delete-old';
    protected $description = 'Delete images older than 3 months.';

    public function handle()
    {
        $cutoffDate = now()->subMonths(3);

        $folders = ['end_images', 'start_images'];

        foreach ($folders as $folder) {
            $files = collect(Storage::disk('public')->listContents($folder, true))
                ->filter(function ($file) use ($cutoffDate) {
                    return $file['type'] === 'file'
                        && isset($file['lastModified'])
                        && $file['lastModified'] < $cutoffDate->getTimestamp();
                });

            foreach ($files as $file) {
                Storage::disk('public')->delete($file['path']);
                $this->info("Deleted: {$file['path']}");
            }
        }

        $this->info('Image cleanup completed!');
    }
}
