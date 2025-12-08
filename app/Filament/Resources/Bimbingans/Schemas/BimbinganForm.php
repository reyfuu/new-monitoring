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
                    ->columns(3) 
                    ->schema([

                        // -------------------- MAHASISWA --------------------
                        Select::make('user_id')
                            ->label('Mahasiswa')
                            ->options(function ($record) use ($user) {
                                $query = User::query()
                                    ->whereHas('roles', fn($q) => $q->where('name', 'mahasiswa'));

                                if ($user->hasRole('mahasiswa')) {
                                    $query->where('id', $user->id);
                                } elseif ($user->hasRole('dosen')) {
                                    // Ambil mahasiswa yang punya bimbingan dengan dosen ini ATAU dosen_pembimbing_id = dosen ini
                                    $query->where(function ($q) use ($user) {
                                        $q->where('dosen_pembimbing_id', $user->id)
                                          ->orWhereHas('bimbingans', fn($bq) => $bq->where('dosen_id', $user->id));
                                    });
                                }
                                
                                // Jika edit, pastikan current value ada di options
                                if ($record && $record->user_id) {
                                    $query->orWhere('id', $record->user_id);
                                }

                                return $query->distinct()->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default($user->hasRole('mahasiswa') ? $user->id : null)
                            ->disabled(fn() => auth()->user()?->hasRole('mahasiswa') || auth()->user()->hasRole('dosen'))
                            ->dehydrated(true)
                            ->visible(fn() => in_array($user->getRoleNames()->first(), ['super_admin', 'dosen', 'mahasiswa']))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) use ($user) {
                                if ($user->hasRole('super_admin') && $state) {
                                    $mahasiswa = User::find($state);
                                    if ($mahasiswa && $mahasiswa->dosen_pembimbing_id) {
                                        $set('dosen_id', $mahasiswa->dosen_pembimbing_id);
                                    }
                                }
                            })
                            ->columnSpan(1),

                        // -------------------- DOSEN PEMBIMBING --------------------
                        Select::make('dosen_id')
                            ->label('Dosen Pembimbing')
                            ->options(function () use ($user) {
                                $query = User::query()
                                    ->whereHas('roles', fn($q) => $q->where('name', 'dosen'));
                                
                                if ($user->hasRole('dosen')) {
                                    $query->where('id', $user->id);
                                }
                                
                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(function () use ($user) {
                                // Cek dari laporan proposal/skripsi/magang yang ada
                                if ($user->hasRole('mahasiswa')) {
                                    $laporan = Laporan::where('mahasiswa_id', $user->id)
                                        ->whereNotNull('dosen_id')
                                        ->first();
                                    if ($laporan) {
                                        return $laporan->dosen_id;
                                    }
                                    return $user->dosen_pembimbing_id;
                                }
                                if ($user->hasRole('dosen')) {
                                    return $user->id;
                                }
                                return null;
                            })
                            ->disabled(fn() => $user->hasRole('mahasiswa') || $user->hasRole('dosen'))
                            ->helperText(function ($get) use ($user) {
                                if ($user->hasRole('mahasiswa')) {
                                    $type = $get('type');
                                    if ($type) {
                                        $laporan = Laporan::where('mahasiswa_id', $user->id)
                                            ->where('type', $type)
                                            ->first();
                                        if ($laporan && $laporan->dosen_id) {
                                            return '✅ Dosen diambil otomatis dari Laporan ' . ucfirst($type);
                                        }
                                    }
                                    return 'ℹ️ Dosen akan otomatis terisi berdasarkan tipe laporan';
                                }
                                return null;
                            })
                            ->dehydrated(true)
                            ->visible(fn() => in_array($user->getRoleNames()->first(), ['super_admin', 'dosen', 'mahasiswa']))
                            ->columnSpan(1),

                        // -------------------- TOPIK BIMBINGAN --------------------
                        TextInput::make('topik')
                            ->label('Topik Bimbingan')
                            ->maxLength(50)
                            ->required()
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->placeholder('Topik bimbingan')
                            ->columnSpan(2),

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
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->default(now())
                            ->columnSpan(1),

                        // -------------------- ISI BIMBINGAN --------------------
                        Textarea::make('isi')
                            ->label('Isi/Rincian Bimbingan')
                            ->maxLength(255)
                            ->rows(3)
                            ->required()
                            ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                            ->columnSpanFull(),

                        // -------------------- STATUS BIMBINGAN --------------------
                        Select::make('status')
                            ->label('Status Bimbingan')
                            ->options([
                               'menunggu' => 'Menunggu persetujuan',
                               'disetujui' => 'Disetujui',
                               'ditolak' => 'Ditolak',
                               'selesai' => 'Selesai',
                            ])
                            ->default('pending')
                            ->required()
                            ->visible($user->hasRole('super_admin') || $user->hasRole('dosen'))
                            ->columnSpan(1),

                        Hidden::make('status')
                            ->default('pending')
                            ->visible($user->hasRole('mahasiswa')),

                        // -------------------- STATUS PROSES --------------------
                        Select::make('status_domen')
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

                        TextInput::make('status_domen')
                            ->label('Status Proses')
                            ->formatStateUsing(fn($state) => match ($state) {
                                'revisi' => '🔄 Butuh Revisi',
                                'review' => '👀 Dalam Review',
                                'fix' => '✅ Sudah Fix',
                                'acc' => '🎉 Diterima (ACC)',
                                'tolak' => '❌ Ditolak',
                                'selesai' => '🏁 Selesai',
                                default => 'Belum ada status',
                            })
                            ->disabled()
                            ->visible(fn($get) => $user->hasRole('mahasiswa') && !empty($get('status_domen')))
                            ->columnSpan(1),

                        // -------------------- KOMENTAR --------------------
                        Textarea::make('komentar')
                            ->label('Komentar untuk Mahasiswa')
                            ->maxLength(100)
                            ->rows(2)
                            ->placeholder('Berikan komentar dan saran untuk mahasiswa')
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
