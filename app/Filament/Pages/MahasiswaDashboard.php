<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Bimbingan;
use App\Models\Laporan;
use App\Models\LaporanMingguan;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class MahasiswaDashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Mahasiswa';
    protected static ?string $slug = 'mahasiswa-dashboard';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.mahasiswa-dashboard';

    public static function getNavigationIcon(): string|Heroicon|null
    {
        return Heroicon::ChartBarSquare;
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('mahasiswa');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Total Bimbingan milik mahasiswa
        $totalBimbingan = Bimbingan::where('user_id', $user->id)->count();
        
        // Bimbingan terverifikasi (sudah di-review dosen)
        $bimbinganTerverifikasi = Bimbingan::where('user_id', $user->id)
            ->whereIn('status_domen', ['fix', 'acc', 'selesai'])
            ->count();
        
        // Bimbingan menunggu review
        $bimbinganMenunggu = Bimbingan::where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('status_domen')
                  ->orWhere('status_domen', 'review');
            })
            ->count();
        
        // Total Laporan
        $totalLaporan = Laporan::where('mahasiswa_id', $user->id)->count();
        
        // Laporan by type
        $laporanSkripsi = Laporan::where('mahasiswa_id', $user->id)->where('type', 'skripsi')->first();
        $laporanPkl = Laporan::where('mahasiswa_id', $user->id)->where('type', 'pkl')->first();
        $laporanMagang = Laporan::where('mahasiswa_id', $user->id)->where('type', 'magang')->first();
        
        // Dosen pembimbing info
        $dosenPembimbing = $user->dosenPembimbing;
        
        // Bimbingan terakhir (5 terakhir)
        $bimbinganTerakhir = Bimbingan::where('user_id', $user->id)
            ->orderByDesc('tanggal')
            ->take(5)
            ->get();
        
        // Calculate progress percentage
        $progressPercent = $totalBimbingan > 0 
            ? round(($bimbinganTerverifikasi / $totalBimbingan) * 100) 
            : 0;

        return [
            'userName' => $user->name ?? 'Mahasiswa',
            'totalBimbingan' => $totalBimbingan,
            'bimbinganTerverifikasi' => $bimbinganTerverifikasi,
            'bimbinganMenunggu' => $bimbinganMenunggu,
            'totalLaporan' => $totalLaporan,
            'laporanSkripsi' => $laporanSkripsi,
            'laporanPkl' => $laporanPkl,
            'laporanMagang' => $laporanMagang,
            'dosenPembimbing' => $dosenPembimbing,
            'bimbinganTerakhir' => $bimbinganTerakhir,
            'progressPercent' => $progressPercent,
        ];
    }
}
