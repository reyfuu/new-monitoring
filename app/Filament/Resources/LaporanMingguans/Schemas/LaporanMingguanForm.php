<?php

namespace App\Filament\Resources\LaporanMingguans\Schemas;

use App\Models\Laporan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;


class LaporanMingguanForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔍 Ambil query laporan sesuai role
        $laporanQuery = Laporan::query();

        if ($user->hasRole('mahasiswa')) {
            $laporanQuery->where('mahasiswa_id', $user->id);
        } elseif ($user->hasRole('dosen')) {
            $laporanQuery->where('dosen_id', $user->id);
        }

        // 🧱 Komponen form sesuai kolom migration
        $components = [
            Select::make('laporan_id')
                ->label('Laporan (Topik)')
                ->options(function () use ($user) {
                    $query = Laporan::query();
                    
                    if ($user->hasRole('mahasiswa')) {
                        $query->where('mahasiswa_id', $user->id);
                    } elseif ($user->hasRole('dosen')) {
                        $query->where('dosen_id', $user->id);
                    }
                    
                    return $query->get()->mapWithKeys(function ($laporan) {
                        $judul = $laporan->judul ?: "Laporan #{$laporan->id}";
                        return [$laporan->id => $judul];
                    });
                })
                ->searchable()
                ->preload()
                ->required()
                ->disabled(fn() => $user->hasRole('dosen')),

            Select::make('week')
                ->label('Minggu Ke')
                ->options([
                    1 => 'Minggu 1',
                    2 => 'Minggu 2',
                    3 => 'Minggu 3',
                    4 => 'Minggu 4',
                ])
                ->required()
                ->disabled(fn() => $user->hasRole('dosen')),

            TextInput::make('isi')
                ->label('Link Dokumen Laporan')
                ->placeholder('Tempel link Google Docs / Drive di sini...')
                ->url()
                ->required()
                ->disabled(fn() => $user->hasRole('dosen'))
                ->suffixIcon('heroicon-o-link')
                ->helperText('Masukkan link dokumen laporan mingguan (contoh: https://docs.google.com/...).'),
        ];

        // Jika user dosen atau super admin → tambahkan kolom status
        if ($user->hasAnyRole(['dosen', 'super_admin'])) {
            $components[] = Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'disetujui' => 'Disetujui',
                    'revisi' => 'Revisi',
                ])
                ->default('pending');
        }

        return $schema->components($components);
    }
}
