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
    protected static ?int $navigationSort = -2;

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

        // Bimbingan terverifikasi (status = disetujui)
        $bimbinganTerverifikasi = Bimbingan::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->count();

        // Bimbingan menunggu review (status = pending)
        $bimbinganMenunggu = Bimbingan::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Total Laporan
        $totalLaporan = Laporan::where('mahasiswa_id', $user->id)->count();

        // Laporan by type (proposal, magang, skripsi)
        $laporanProposal = Laporan::where('mahasiswa_id', $user->id)->where('type', 'proposal')->first();
        $laporanMagang = Laporan::where('mahasiswa_id', $user->id)->where('type', 'magang')->first();
        $laporanSkripsi = Laporan::where('mahasiswa_id', $user->id)->where('type', 'skripsi')->first();

        // Dosen pembimbing info - kumpulkan semua dosen dari laporan
        $dosenPembimbingList = [];

        if ($laporanProposal && $laporanProposal->dosen_id) {
            $dosen = \App\Models\User::find($laporanProposal->dosen_id);
            if ($dosen) {
                $dosenPembimbingList[] = [
                    'dosen' => $dosen,
                    'type' => 'Proposal',
                    'icon' => '📄'
                ];
            }
        }

        if ($laporanMagang && $laporanMagang->dosen_id) {
            $dosen = \App\Models\User::find($laporanMagang->dosen_id);
            if ($dosen) {
                $dosenPembimbingList[] = [
                    'dosen' => $dosen,
                    'type' => 'Magang',
                    'icon' => '🏢'
                ];
            }
        }

        if ($laporanSkripsi && $laporanSkripsi->dosen_id) {
            $dosen = \App\Models\User::find($laporanSkripsi->dosen_id);
            if ($dosen) {
                $dosenPembimbingList[] = [
                    'dosen' => $dosen,
                    'type' => 'Skripsi',
                    'icon' => '📚'
                ];
            }
        }

        // Fallback ke dosen_pembimbing_id di user jika tidak ada laporan
        if (empty($dosenPembimbingList) && $user->dosenPembimbing) {
            $dosenPembimbingList[] = [
                'dosen' => $user->dosenPembimbing,
                'type' => 'Pembimbing',
                'icon' => '👨‍🏫'
            ];
        }

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
            'laporanProposal' => $laporanProposal,
            'laporanMagang' => $laporanMagang,
            'laporanSkripsi' => $laporanSkripsi,
            'dosenPembimbingList' => $dosenPembimbingList,
            'bimbinganTerakhir' => $bimbinganTerakhir,
            'progressPercent' => $progressPercent,
        ];
    }
}
