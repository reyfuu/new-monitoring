<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Bimbingan;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Icons\Heroicon;

class Dashboard extends Page
{
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';
    protected static string $routePath = '/dashboard';
    protected static ?int $navigationSort = -10;

    protected string $view = 'filament.pages.dashboard';

    public static function getNavigationIcon(): string|Heroicon|null
    {
        return Heroicon::ChartBarSquare;
    }

    public static function canAccess(): bool
    {
        return Auth::check();
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        
        // Only super_admin sees the default Dashboard menu
        // Other roles have their own custom dashboards
        return $user && $user->hasRole('super_admin');
    }

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Redirect non-admin roles to their specific dashboards
        if ($user) {
            if ($user->hasRole('ka_prodi')) {
                $this->redirect('/kaprodi-dashboard');
            } elseif ($user->hasRole('mahasiswa')) {
                $this->redirect('/mahasiswa-dashboard');
            } elseif ($user->hasRole('dosen')) {
                $this->redirect('/dosen-dashboard');
            }
            // Super admin stays on /dashboard
        }
    }

    public function getViewData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If super_admin, show admin dashboard data
        if ($user && ($user->hasRole('super_admin') || $user->hasRole('admin'))) {
            // Total Mahasiswa
            $totalMahasiswa = User::role('mahasiswa')->count();
            
            // Total Dosen
            $totalDosen = User::role('dosen')->count();
            
            // Total Users (all system users)
            $totalUsers = User::count();
            
            // Total Kaprodi
            $totalKaprodi = User::role('ka_prodi')->count();
            
            // Bimbingan stats
            $totalBimbingan = Bimbingan::count();
            $bimbinganSelesai = Bimbingan::whereIn('status_domen', ['fix', 'acc', 'selesai'])->count();
            $bimbinganReview = Bimbingan::where('status_domen', 'review')->orWhereNull('status_domen')->count();
            
            // Mahasiswa On Track (punya bimbingan dengan status selesai)
            $mahasiswaOnTrack = User::role('mahasiswa')
                ->whereHas('bimbingans', function ($q) {
                    $q->whereIn('status_domen', ['fix', 'acc', 'selesai']);
                })->count();
            
            // Mahasiswa At Risk (punya bimbingan tapi belum ada yang selesai)
            $mahasiswaAtRisk = User::role('mahasiswa')
                ->whereHas('bimbingans')
                ->whereDoesntHave('bimbingans', function ($q) {
                    $q->whereIn('status_domen', ['fix', 'acc', 'selesai']);
                })->count();
            
            // Mahasiswa Overdue (tidak punya bimbingan sama sekali)
            $mahasiswaOverdue = User::role('mahasiswa')
                ->whereDoesntHave('bimbingans')
                ->count();
            
            // Total Laporan
            $totalLaporan = Laporan::count();
            
            // Laporan by type
            $laporanSkripsi = Laporan::where('type', 'skripsi')->count();
            $laporanPkl = Laporan::where('type', 'pkl')->count();
            $laporanMagang = Laporan::where('type', 'magang')->count();
            
            // Dosen workload list
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
                'role' => 'admin',
                'userName' => $user->name ?? 'Admin',
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
                'totalLaporan' => $totalLaporan,
                'laporanSkripsi' => $laporanSkripsi,
                'laporanPkl' => $laporanPkl,
                'laporanMagang' => $laporanMagang,
                'dosenList' => $dosenList,
            ];
        }

        // Default fallback for other roles
        return [
            'role' => 'guest',
            'userName' => $user->name ?? 'User',
        ];
    }
}