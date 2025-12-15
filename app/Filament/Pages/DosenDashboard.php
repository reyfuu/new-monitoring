<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class DosenDashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Dosen';
    protected static ?string $slug = 'dosen-dashboard';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.dosen-dashboard';

    public static function getNavigationIcon(): string|Heroicon|null
    {
        return Heroicon::ChartBarSquare;
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('dosen');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Total Mahasiswa bimbingan
        $totalMahasiswa = $user->mahasiswaBimbingan()->count();
        
        // Bimbingan stats dari mahasiswa bimbingan
        $bimbinganQuery = Bimbingan::where('dosen_id', $user->id)
            ->orWhereHas('mahasiswa', function ($q) use ($user) {
                $q->where('dosen_pembimbing_id', $user->id);
            });
        
        $totalBimbingan = (clone $bimbinganQuery)->count();
        
        // Bimbingan perlu review (status_domen null atau 'review')
        $bimbinganReview = (clone $bimbinganQuery)
            ->where(function ($q) {
                $q->whereNull('status_domen')
                  ->orWhere('status_domen', 'review');
            })
            ->count();
        
        // Bimbingan selesai
        $bimbinganSelesai = (clone $bimbinganQuery)
            ->whereIn('status_domen', ['fix', 'acc', 'selesai'])
            ->count();
        
        // Laporan stats
        $totalLaporan = Laporan::where('dosen_id', $user->id)->count();
        
        // Pie chart data - Status Bimbingan
        $statusBimbinganData = [
            'review' => Bimbingan::where('dosen_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('status_domen')->orWhere('status_domen', 'review');
                })->count(),
            'fix' => Bimbingan::where('dosen_id', $user->id)->where('status_domen', 'fix')->count(),
            'acc' => Bimbingan::where('dosen_id', $user->id)->where('status_domen', 'acc')->count(),
            'selesai' => Bimbingan::where('dosen_id', $user->id)->where('status_domen', 'selesai')->count(),
        ];
        
        // Pie chart data - Jenis Laporan
        $jenisLaporanData = [
            'skripsi' => Laporan::where('dosen_id', $user->id)->where('type', 'skripsi')->count(),
            'pkl' => Laporan::where('dosen_id', $user->id)->where('type', 'pkl')->count(),
            'magang' => Laporan::where('dosen_id', $user->id)->where('type', 'magang')->count(),
        ];
        
        // Daftar mahasiswa bimbingan
        $mahasiswaList = $user->mahasiswaBimbingan()
            ->withCount('bimbingans')
            ->orderByDesc('bimbingans_count')
            ->take(5)
            ->get();

        return [
            'userName' => $user->name ?? 'Dosen',
            'totalMahasiswa' => $totalMahasiswa,
            'totalBimbingan' => $totalBimbingan,
            'bimbinganReview' => $bimbinganReview,
            'bimbinganSelesai' => $bimbinganSelesai,
            'totalLaporan' => $totalLaporan,
            'statusBimbinganData' => $statusBimbinganData,
            'jenisLaporanData' => $jenisLaporanData,
            'mahasiswaList' => $mahasiswaList,
        ];
    }
}
