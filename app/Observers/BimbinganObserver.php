<?php

namespace App\Observers;

use App\Models\Bimbingan;

class BimbinganObserver
{
    /**
     * Handle the Bimbingan "created" event.
     */
    public function created(Bimbingan $bimbingan): void
    {
        //
    }

    /**
     * Handle the Bimbingan "updated" event.
     */
    public function updated(Bimbingan $bimbingan): void
    {
        //
    }

    /**
     * Handle the Bimbingan "deleted" event.
     */
    public function deleted(Bimbingan $bimbingan): void
    {
        //
    }

    /**
     * Handle the Bimbingan "restored" event.
     */
    public function restored(Bimbingan $bimbingan): void
    {
        //
    }

    /**
     * Handle the Bimbingan "force deleted" event.
     */
    public function forceDeleted(Bimbingan $bimbingan): void
    {
        //
    }
}
