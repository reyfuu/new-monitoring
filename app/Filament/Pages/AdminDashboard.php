<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\Laporan;
use App\Models\LaporanMingguan;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class AdminDashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Admin';
    protected static ?string $slug = 'admin-dashboard';
    protected static ?int $navigationSort = -2;

    protected string $view = 'filament.pages.admin-dashboard';

    public static function getNavigationIcon(): string|Heroicon|null
    {
        return Heroicon::ChartBarSquare;
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user && $user->hasRole('admin');
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

        // Total Users (all system users)
        $totalUsers = User::count();

        // Total Kaprodi
        $totalKaprodi = User::role('ka_prodi')->count();

        // Bimbingan stats (dari field 'status')
        $totalBimbingan = Bimbingan::count();
        $bimbinganSelesai = Bimbingan::where('status', 'disetujui')->count();
        $bimbinganReview = Bimbingan::where('status', 'review')->count();

        
        // Mahasiswa On Track (punya bimbingan dengan status disetujui)
        $mahasiswaOnTrack = User::role('mahasiswa')
            ->whereHas('bimbingans', function ($q) {
                $q->where('status', 'disetujui');
            })->count();

        // Mahasiswa At Risk (punya bimbingan tapi belum ada yang disetujui)
        $mahasiswaAtRisk = User::role('mahasiswa')
            ->whereHas('bimbingans')
            ->whereDoesntHave('bimbingans', function ($q) {
                $q->where('status', 'disetujui');
            })->count();

        // Mahasiswa Overdue (tidak punya bimbingan sama sekali)
        $mahasiswaOverdue = User::role('mahasiswa')
            ->whereDoesntHave('bimbingans')
            ->count();


        // Laporan by type (proposal, magang, skripsi)
        $laporanProposal = Laporan::where('type', 'proposal')->count();
        $laporanMagang = Laporan::where('type', 'magang')->count();
        $laporanSkripsi = Laporan::where('type', 'skripsi')->count();

        // Dosen workload list dengan perhitungan mahasiswa dari gabungan dosen_pembimbing_id dan laporan
        $dosenList = User::role('dosen')->get()->map(function ($dosen) {
            // Gabungkan mahasiswa dari dosen_pembimbing_id dan laporan
            $mahasiswaIds = collect();
            $mahasiswaIds = $mahasiswaIds->merge(
                User::role('mahasiswa')->where('dosen_pembimbing_id', $dosen->id)->pluck('id')
            );
            $mahasiswaIds = $mahasiswaIds->merge(
                Laporan::where('dosen_id', $dosen->id)->pluck('mahasiswa_id')->filter()
            );

            // Return as array with computed count
            return (object) [
                'id' => $dosen->id,
                'name' => $dosen->name,
                'email' => $dosen->email,
                'mahasiswa_bimbingan_count' => $mahasiswaIds->unique()->filter()->count()
            ];
        })->sortByDesc('mahasiswa_bimbingan_count')->values()->take(5);

        // Calculate percentages
        $onTrackPercent = $totalMahasiswa > 0 ? round(($mahasiswaOnTrack / $totalMahasiswa) * 100) : 0;
        $atRiskPercent = $totalMahasiswa > 0 ? round(($mahasiswaAtRisk / $totalMahasiswa) * 100) : 0;
        $overduePercent = $totalMahasiswa > 0 ? round(($mahasiswaOverdue / $totalMahasiswa) * 100) : 0;

        return [
            'userName' => Auth::user()->name ?? 'Admin',
            'totalUsers' => $totalUsers,
            'totalMahasiswa' => $totalMahasiswa,
            'totalDosen' => $totalDosen,
            'totalKaprodi' => $totalKaprodi,
            'totalBimbingan' => $totalBimbingan,
            'bimbinganSelesai' => $bimbinganSelesai,
            'bimbinganReview' => $bimbinganReview,
            'mahasiswaOnTrack' => $mahasiswaOnTrack,
            'mahasiswaAtRisk' => $mahasiswaAtRisk,
            'mahasiswaOverdue' => $mahasiswaOverdue,
            'onTrackPercent' => $onTrackPercent,
            'atRiskPercent' => $atRiskPercent,
            'overduePercent' => $overduePercent,
            'laporanProposal' => $laporanProposal,
            'laporanMagang' => $laporanMagang,
            'laporanSkripsi' => $laporanSkripsi,
            'dosenList' => $dosenList,
        ];
    }
}
