<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class UpdateStorageSymlink
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $symlinkPath = public_path('storage');

        if (file_exists($symlinkPath) && is_link($symlinkPath)) {
            unlink($symlinkPath);
        }

        // Recreate symlink
        if (!file_exists($symlinkPath)) {
            symlink(storage_path('app/public'), $symlinkPath);
        }
    }
}
