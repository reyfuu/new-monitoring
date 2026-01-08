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
    protected static ?int $navigationSort = -2;

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

        // Bimbingan perlu review (status = pending)
        $bimbinganReview = (clone $bimbinganQuery)
            ->where('status', 'pending')
            ->count();

        // Bimbingan selesai (status = disetujui)
        $bimbinganSelesai = (clone $bimbinganQuery)
            ->where('status', 'disetujui')
            ->count();

        // Laporan stats
        $totalLaporan = Laporan::where('dosen_id', $user->id)->count();

        // Pie chart data - Status Bimbingan (dari field 'status')
        $statusBimbinganData = [
            'review' => Bimbingan::where('dosen_id', $user->id)->where('status', 'pending')->count(),
            'ditolak' => Bimbingan::where('dosen_id', $user->id)->where('status', 'ditolak')->count(),
            'disetujui' => Bimbingan::where('dosen_id', $user->id)->where('status', 'disetujui')->count(),
        ];

        // Pie chart data - Jenis Laporan (proposal, magang, skripsi)
        $jenisLaporanData = [
            'proposal' => Laporan::where('dosen_id', $user->id)->where('type', 'proposal')->count(),
            'magang' => Laporan::where('dosen_id', $user->id)->where('type', 'magang')->count(),
            'skripsi' => Laporan::where('dosen_id', $user->id)->where('type', 'skripsi')->count(),
        ];

        // Daftar mahasiswa bimbingan (gabungan dari dosen_pembimbing_id dan laporan)
        $mahasiswaIds = collect();

        // 1. Dari relasi dosen_pembimbing_id
        $mahasiswaFromRelation = User::role('mahasiswa')
            ->where('dosen_pembimbing_id', $user->id)
            ->pluck('id');
        $mahasiswaIds = $mahasiswaIds->merge($mahasiswaFromRelation);

        // 2. Dari laporan yang dosen_id = user ini
        $mahasiswaFromLaporan = Laporan::where('dosen_id', $user->id)
            ->pluck('mahasiswa_id');
        $mahasiswaIds = $mahasiswaIds->merge($mahasiswaFromLaporan);

        // Ambil unique mahasiswa dengan count bimbingan
        $mahasiswaList = User::role('mahasiswa')
            ->whereIn('id', $mahasiswaIds->unique())
            ->withCount(['bimbingans' => function ($q) use ($user) {
                $q->where('dosen_id', $user->id);
            }])
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
