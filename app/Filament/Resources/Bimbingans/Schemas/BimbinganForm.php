<?php

namespace App\Filament\Resources\Bimbingans\Schemas;

use App\Models\User;
use App\Models\Laporan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;

class BimbinganForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $schema
            ->columns(1)
            ->components([
                // ==================== SECTION INFORMASI BIMBINGAN ====================
                Section::make('Informasi Bimbingan')
                    ->columns(2)
                    ->schema([

                   
                        
                       

                        // -------------------- JENIS & TANGGAL BIMBINGAN --------------------
                        Select::make('type')
                            ->label('Jenis Bimbingan')
                            ->options(function () use ($user) {
                                $allTypes = [
                                    'proposal' => '📄 Proposal',
                                    'skripsi' => '🎓 Skripsi',
                                    'magang' => '💼 Magang',
                                ];

                                // Untuk mahasiswa, hanya tampilkan tipe yang sudah ada laporan-nya
                                if ($user->hasRole('mahasiswa')) {
                                    $existingTypes = Laporan::where('mahasiswa_id', $user->id)
                                        ->pluck('type')
                                        ->toArray();

                                    return collect($allTypes)->filter(function ($label, $type) use ($existingTypes) {
                                        return in_array($type, $existingTypes);
                                    })->toArray();
                                }

                                return $allTypes;
                            })
                            ->required()
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->placeholder('Pilih jenis bimbingan')
                            ->helperText(function () use ($user) {
                                if ($user->hasRole('mahasiswa')) {
                                    $count = Laporan::where('mahasiswa_id', $user->id)->count();
                                    if ($count == 0) {
                                        return '⚠️ Buat Laporan terlebih dahulu sebelum membuat Bimbingan';
                                    }
                                    return 'ℹ️ Hanya menampilkan tipe yang sudah ada Laporan-nya';
                                }
                                return null;
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($user) {
                                // Jika mahasiswa pilih type, ambil dosen dari laporan
                                if ($user->hasRole('mahasiswa') && $state) {
                                    $laporan = Laporan::where('mahasiswa_id', $user->id)
                                        ->where('type', $state)
                                        ->first();

                                    if ($laporan && $laporan->dosen_id) {
                                        $set('dosen_id', $laporan->dosen_id);
                                    }
                                }
                            })
                            ->columnSpan(1),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Bimbingan')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->validationMessages([
                                'required' => 'Tanggal bimbingan wajib diisi.',
                            ])
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->default(now())
                            ->columnSpan(1),

                        // -------------------- TOPIK BIMBINGAN --------------------
                        TextInput::make('topik')
                            ->label('Topik Bimbingan')
                            ->maxLength(50)
                            ->required()
                            ->validationMessages([
                                'required' => 'Topik bimbingan wajib diisi.',
                            ])
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->placeholder('Topik bimbingan')
                            ->columnSpanFull(),

                        // -------------------- ISI BIMBINGAN --------------------
                        Textarea::make('isi')
                            ->label('Isi/Rincian Bimbingan')
                            ->maxLength(255)
                            ->rows(3)
                            ->required()
                            ->validationMessages([
                                'required' => 'Isi bimbingan wajib diisi.',
                            ])
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->columnSpanFull(),

                        // -------------------- STATUS BIMBINGAN --------------------
                        Select::make('status')
                            ->label('Status Bimbingan')
                            ->options([
                                'review' => 'Review',
                                'disetujui' => 'Disetujui',
                                'revisi' => 'Revisi',
                            ])
                            ->default('review')
                            ->required()
                            ->visible($user->hasRole('super_admin') || $user->hasRole('dosen'))
                            ->columnSpanFull(),

                        Hidden::make('status')
                            ->default('review')
                            ->visible($user->hasRole('mahasiswa')),

                        // -------------------- KOMENTAR --------------------
                        Textarea::make('komentar')
                            ->label('Komentar untuk Mahasiswa')
                            ->maxLength(100)
                            ->rows(2)
                            ->placeholder('Berikan komentar dan saran untuk mahasiswa')
                            ->required(fn ($get) => $get('status') === 'revisi')
                            ->validationMessages([
                                'required' => 'Komentar wajib diisi jika status adalah Revisi.',
                            ])
                            ->visible($user->hasRole('super_admin') || $user->hasRole('dosen'))
                            ->columnSpanFull(),

                        Textarea::make('komentar')
                            ->label('Komentar Dosen')
                            ->maxLength(100)
                            ->rows(2)
                            ->placeholder('Belum ada komentar dari dosen')
                            ->disabled()
                            ->visible(fn($get) => $user->hasRole('mahasiswa') && !empty($get('komentar')))
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
