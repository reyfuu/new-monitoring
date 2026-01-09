<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Bimbingan;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class KaprodiDashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard Kaprodi';
    protected static ?string $title = 'Dashboard Kaprodi';
    protected static ?string $slug = 'kaprodi-dashboard';
    protected static ?int $navigationSort = -2;

    protected string $view = 'filament.pages.kaprodi-dashboard';

    public static function getNavigationIcon(): string|Heroicon|null
    {
        return Heroicon::ChartBarSquare;
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('ka_prodi');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getViewData(): array
    {
        // Total Mahasiswa
        $totalMahasiswa = User::role('mahasiswa')->count();

        // Total Dosen
        $totalDosen = User::role('dosen')->count();

        // Bimbingan stats
        $totalBimbingan = Bimbingan::where('status', 'disetujui')->count();
        $bimbinganSelesai = Bimbingan::whereIn('status', ['disetujui'])->count();
        $bimbinganReview = Bimbingan::where('status', 'pending')->orWhereNull('status')->count();

        // Mahasiswa On Track (punya bimbingan dengan status selesai)
        $mahasiswaOnTrack = User::role('mahasiswa')
            ->whereHas('bimbingans', function ($q) {
                $q->whereIn('status', ['disetujui']);
            })->count();

        // Mahasiswa At Risk (punya bimbingan tapi belum ada yang selesai)
        $mahasiswaAtRisk = User::role('mahasiswa')
            ->whereHas('bimbingans')
            ->whereDoesntHave('bimbingans', function ($q) {
                $q->whereIn('status', ['disetujui']);
            })->count();

        // Mahasiswa Overdue (tidak punya bimbingan sama sekali / belum ada bimbingan)
        $mahasiswaOverdue = User::role('mahasiswa')
            ->whereDoesntHave('bimbingans')
            ->count();

        // Dosen workload
        $dosenList = User::role('dosen')
            ->withCount('mahasiswaBimbingan')
            ->orderByDesc('mahasiswa_bimbingan_count')
            ->take(5)
            ->get();

        // Calculate percentages
        $onTrackPercent = $totalMahasiswa > 0 ? round(($mahasiswaOnTrack / $totalMahasiswa) * 100) : 0;
        $atRiskPercent = $totalMahasiswa > 0 ? round(($mahasiswaAtRisk / $totalMahasiswa) * 100) : 0;
        $overduePercent = $totalMahasiswa > 0 ? round(($mahasiswaOverdue / $totalMahasiswa) * 100) : 0;

        return [
            'totalMahasiswa' => $totalMahasiswa,
            'totalDosen' => $totalDosen,
            'totalBimbingan' => $totalBimbingan,
            'bimbinganSelesai' => $bimbinganSelesai,
            'bimbinganReview' => $bimbinganReview,
            'mahasiswaOnTrack' => $mahasiswaOnTrack,
            'mahasiswaAtRisk' => $mahasiswaAtRisk,
            'mahasiswaOverdue' => $mahasiswaOverdue,
            'onTrackPercent' => $onTrackPercent,
            'atRiskPercent' => $atRiskPercent,
            'overduePercent' => $overduePercent,
            'dosenList' => $dosenList,
            'userName' => Auth::user()->name ?? 'Kaprodi',
        ];
    }
}
