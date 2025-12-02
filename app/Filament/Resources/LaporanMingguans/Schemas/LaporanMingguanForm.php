<?php

namespace App\Filament\Resources\LaporanMingguans\Schemas;

use App\Models\Laporan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;


class LaporanMingguanForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔍 Ambil query laporan sesuai role
        $laporanQuery = Laporan::query();

        // Jika mahasiswa, hanya ambil laporan miliknya
        if ($user->hasRole('mahasiswa')) {
            $laporanQuery->where('mahasiswa_id', $user->id);
        }

        // 🧱 Komponen form
        $components = [

            Select::make('mahasiswa_id')
                    ->label('Mahasiswa')
                    ->required()
                    ->disabled(fn() => $user->hasRole('mahasiswa'))
                    ->default(fn() => $user->hasRole('mahasiswa') ? $user->id : null)
                    ->dehydrated(true) // ✅ WAJIB, agar tetap disimpan walau disabled
                    ->options(function () use ($user) {
                        if ($user->hasRole('mahasiswa')) {
                            return \App\Models\User::where('id', $user->id)->pluck('name', 'id');
                        }

                        if ($user->hasRole('dosen')) {
                            return \App\Models\User::where('dosen_pembimbing_id', $user->id)
                                ->whereHas('roles', fn($q) => $q->where('name', 'mahasiswa'))
                                ->pluck('name', 'id');
                        }

                        if ($user->hasRole('super_admin')) {
                            return \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'mahasiswa'))
                                ->pluck('name', 'id');
                        }

                        return [];
                    })
                    ->searchable()
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->visible(false) 
                    ->preload(),

                Select::make('dosen_id')
                    ->label('Dosen Pembimbing')
                    ->relationship(
                        name: 'dosen',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) =>
                        $query->whereHas('roles', fn($q) => $q->where('name', 'dosen'))
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn() => $user->hasRole('mahasiswa'))
                    ->default(function () use ($user) {
                        if ($user->hasRole('mahasiswa') && $user->dosen_pembimbing_id) {
                            return $user->dosen_pembimbing_id;
                        }
                        return null;
                    })
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->visible(false)
                    ->dehydrated(true), // ✅ agar tetap disimpan walau disabled
            

            TextInput::make('isi')
                ->label('Link Dokumen Laporan')
                ->placeholder('Tempel link Google Docs / Drive di sini...')
                ->url() // validasi otomatis untuk format URL
                ->required()
                ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                ->suffixIcon('heroicon-o-link')
                ->helperText('Masukkan link dokumen laporan mingguan (contoh: https://docs.google.com/...).'),
        ];

        // Jika user dosen atau super admin → tambahkan kolom status
        if ($user->hasAnyRole(['dosen', 'super admin'])) {
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
