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
            TextInput::make('week')
                ->label('Minggu Ke-')
                ->numeric()
                ->minValue(1)
                ->maxValue(26)
                ->extraInputAttributes(['min' => 1, 'max' => 26])
                ->rules(['integer', 'min:1', 'max:26'])
                ->live()
                ->afterStateUpdated(function ($state, $set) {
                    // Batasi nilai secara real-time saat user mengetik
                    if ($state !== null && $state !== '') {
                        if ($state > 26) {
                            $set('week', 26);
                        } elseif ($state < 1) {
                            $set('week', 1);
                        }
                    }
                })
                ->placeholder('Masukkan nomor minggu (contoh: 1, 2, 3...)')
                ->required()
                ->disabled(fn() => $user->hasRole('dosen'))
                ->helperText('Masukkan angka minggu ke berapa (1-26, maks. 6 bulan)'),

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
