<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gallery;

class CleanInvalidGalleries extends Command
{
    protected $signature = 'gallery:clean-invalid';
    protected $description = 'Remove gallery records with invalid image paths (empty or "0")';

    public function handle()
    {
        $invalidGalleries = Gallery::where('image', '0')
            ->orWhere('image', '')
            ->orWhereNull('image')
            ->get();

        $count = $invalidGalleries->count();

        if ($count === 0) {
            $this->info('No invalid galleries found.');
            return 0;
        }

        $this->info("Found {$count} galleries with invalid image paths:");
        foreach ($invalidGalleries as $gallery) {
            $this->line("  - ID: {$gallery->id}, Title: {$gallery->title}, Image: '{$gallery->image}'");
        }

        if ($this->confirm('Do you want to delete these galleries?', true)) {
            $deleted = Gallery::where('image', '0')
                ->orWhere('image', '')
                ->orWhereNull('image')
                ->delete();

            $this->info("Successfully deleted {$deleted} invalid gallery records.");
            return 0;
        }

        $this->info('Operation cancelled.');
        return 0;
    }
}
