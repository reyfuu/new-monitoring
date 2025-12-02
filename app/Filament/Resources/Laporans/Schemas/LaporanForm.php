<?php

namespace App\Filament\Resources\Laporans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(150)
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->label('Judul Laporan'),

                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->label('Tanggal Mulai'),

                DatePicker::make('tanggal_berakhir')
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->label('Tanggal Berakhir'),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->nullable(),

                Select::make('type')
                    ->label('Tipe Laporan')
                    ->options([
                        'proposal' => 'Proposal',
                        'magang' => 'Magang',
                        'skripsi' => 'Skripsi',
                    ])
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->required(),

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
                    ->dehydrated(true), // ✅ agar tetap disimpan walau disabled

                FileUpload::make('dokumen')
                    ->label('Upload Dokumen')
                    ->directory('laporan-dokumen')
                    ->preserveFilenames()
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->default(fn() => $user->hasRole('mahasiswa') ? 'pending' : 'pending')
                    ->disabled(fn() => $user->hasRole('mahasiswa')) // mahasiswa tidak bisa ubah
                    ->visible(fn() => true),

                Select::make('status_dosen')
                    ->label('Status Proses')
                    ->options([
                        'revisi' => '🔄 Butuh Revisi',
                        'review' => '👀 Dalam Review',
                        'fix' => '✅ Sudah Fix',
                        'acc' => '🎉 Diterima (ACC)',
                        'tolak' => '❌ Ditolak',
                        'selesai' => '🏁 Selesai',
                    ])
                    ->default('review')
                    ->placeholder('Pilih status proses bimbingan')
                    ->visible($user->hasRole('super_admin') || $user->hasRole('dosen'))
                    ->columnSpan(1),
            ]);
    }
}
