<?php

namespace App\Providers;

use App\Models\Bimbingan;
use App\Models\Laporan;
use App\Models\LaporanMingguan;
use App\Observers\StatusChangeObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\BimbinganObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Laporan::observe(StatusChangeObserver::class);
        // Bimbingan::observe(StatusChangeObserver::class);
        // LaporanMingguan::observe(StatusChangeObserver::class);
    }
}
