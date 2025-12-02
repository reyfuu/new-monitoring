<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon as IconsHeroicon;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\Laporan;
use App\Models\LaporanMingguan;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $stats = [];

        // Jika user bukan mahasiswa/dosen, tampilkan stat Mahasiswa & Dosen
        if (! $user->hasRole('mahasiswa') && ! $user->hasRole('dosen')) {
            $stats[] = Stat::make('Mahasiswa', User::role('mahasiswa')->count())
                ->icon(IconsHeroicon::UserGroup);

            $stats[] = Stat::make('Dosen', User::role('dosen')->count())
                ->icon(IconsHeroicon::AcademicCap);
        }

        // Stat Bimbingan selalu tampil
        $stats[] = Stat::make('Bimbingan', Bimbingan::count())
            ->icon(IconsHeroicon::ClipboardDocumentList);

        // Hitung Laporan & Laporan Mingguan sesuai role
        if ($user->hasRole('mahasiswa')) {
            $totalLaporan = Laporan::where('mahasiswa_id', $user->id)->count();
            
        } elseif ($user->hasRole('dosen')) {
            $totalLaporan = Laporan::where('dosen_id', $user->id)->count();
            
        } else {
            $totalLaporan = Laporan::count();
        
        }

        $stats[] = Stat::make('Laporan', $totalLaporan)
            ->icon(IconsHeroicon::DocumentText);


        return $stats;
    }
}
