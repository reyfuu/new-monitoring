<?php

namespace App\Filament\Resources\Laporans\Schemas;

use App\Models\Laporan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;


class LaporanForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $laporanQuery = Laporan::query();

        if ($user->hasRole('mahasiswa')) {
            $laporanQuery->where('mahasiswa_id', $user->id);
        } elseif ($user->hasRole('dosen')) {
            $laporanQuery->where('dosen_id', $user->id);
        }


        $componenents =[
            TextInput::make('judul')
                    ->required()
                    ->maxLength(150)
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->label('Judul Laporan')
                    ->validationMessages([
                        'required' => 'Judul laporan wajib diisi.',
                    ]),

                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->disabled(fn() => auth()->user()?->hasRole('dosen') && fn() => auth()->user()?->hasRole('mahasiswa'))
                    ->default(now())
                    ->dehydrated(true)
                    ->visible(fn($operation) => $operation === 'edit' && !auth()->user()?->hasRole('dosen') && !auth()->user()?->hasRole('mahasiswa'))
                    ->label('Tanggal Mulai')
                    ->validationMessages([
                        'required' => 'Tanggal mulai wajib diisi.',
                    ]),

                DatePicker::make('tanggal_berakhir')
                    ->disabled(fn() => auth()->user()?->hasRole('dosen') && fn() => auth()->user()?->hasRole('mahasiswa'))
                    ->visible(fn($operation) => $operation === 'edit' && !auth()->user()?->hasRole('dosen') && !auth()->user()?->hasRole('mahasiswa'))
                    ->label('Tanggal Berakhir'),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->required()
                    ->validationMessages([
                        'required' => 'Deskripsi laporan wajib diisi.',
                    ]),

                Select::make('type')
                    ->label('Tipe Laporan')
                    ->options(function ($record) use ($user) {
                        $allTypes = [
                            'proposal' => 'Proposal',
                            'magang' => 'Magang',
                            'skripsi' => 'Skripsi',
                        ];

                        // Jika edit, return semua type agar value tetap ada
                        if ($record && $record->type) {
                            return $allTypes;
                        }

                        // Filter tipe yang sudah ada laporan-nya (untuk mahasiswa saat create)
                        if ($user->hasRole('mahasiswa')) {
                            $existingTypes = Laporan::where('mahasiswa_id', $user->id)
                                ->pluck('type')
                                ->toArray();

                            return collect($allTypes)->filter(function ($label, $type) use ($existingTypes) {
                                return !in_array($type, $existingTypes);
                            })->toArray();
                        }

                        return $allTypes;
                    })
                    ->disabled(fn($operation, $record) => $operation === 'edit' || auth()->user()?->hasRole('dosen'))
                    ->dehydrated(true)
                    ->required()
                    ->validationMessages([
                        'required' => 'Tipe laporan wajib dipilih.',
                    ])
                    ->helperText(function ($operation) use ($user) {
                        if ($operation === 'edit') {
                            return 'ℹ️ Tipe laporan tidak dapat diubah';
                        }
                        if ($user->hasRole('mahasiswa')) {
                            return 'ℹ️ Setiap tipe hanya bisa dibuat satu kali';
                        }
                        return null;
                    }),

                

                TextInput::make('dokumen')
                ->label('Link Dokumen Laporan')
                ->placeholder('Tempel link Google Docs / Drive di sini...')
                ->url()
                ->required()
                ->validationMessages([
                    'required' => 'Link dokumen wajib diisi.',
                    'url' => 'Format link tidak valid.',
                ])
                ->suffixIcon('heroicon-o-link')
                ->helperText('Masukkan link dokumen laporan mingguan (contoh: https://docs.google.com/...).'),
        


                Textarea::make('komentar')
                    ->label('Komentar')
                    ->rows(4)
                    ->placeholder('Tambahkan komentar untuk laporan ini...')
                    ->disabled(fn() => auth()->user()?->hasRole('mahasiswa'))
                    ->required(fn ($get) => $get('status') === 'revisi')
                    ->validationMessages([
                        'required' => 'Komentar wajib diisi jika status adalah Revisi.',
                    ])
                    ->visible(true)
                    ->nullable(),
                ];

                // Hidden::make('status')
                //         ->default('review')
                //         ->visible($user->hasRole('mahasiswa'));

                     if ($user->hasAnyRole(['dosen', 'super_admin'])) {
                        $componenents[] = Select::make('status')
                            ->label('Status')
                            ->options([
                                'review' => 'Review',
                                'disetujui' => 'Disetujui',
                                'revisi' => 'Revisi',
                            ])
                            ->default('review');
                            
        }

        return $schema->components($componenents);
    }
}
