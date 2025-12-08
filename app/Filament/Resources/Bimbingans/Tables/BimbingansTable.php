<?php

namespace App\Filament\Resources\Bimbingans\Tables;


use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\DeleteAction;

class BimbingansTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $table
            ->recordUrl(null) // Prevent row clicking
            // ==================== GROUPING ====================
            ->groups([
                Group::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function ($record) {
                        // Hitung total bimbingan untuk mahasiswa ini
                        $totalPertemuan = \App\Models\Bimbingan::where('user_id', $record->user_id)
                            ->where('status', 'completed')
                            ->count();

                        return $record->mahasiswa->name . " ({$totalPertemuan} pertemuan)";
                    }),
            ])
            ->defaultGroup('mahasiswa.name')
            ->groupsOnly(false)
            ->groupingSettingsInDropdownOnDesktop() // Hide group buttons from UI
            ->columns([
                // PERTEMUAN KE - FIXED LOGIC
                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn($state) => $state ? "#{$state}" : 'N/A')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        // Hitung urutan pertemuan berdasarkan tanggal
                        return \App\Models\Bimbingan::where('user_id', $record->user_id)
                            ->where('tanggal', '<=', $record->tanggal)
                            ->orderBy('tanggal')
                            ->orderBy('created_at')
                            ->pluck('id')
                            ->search($record->id) + 1;
                    }),

                // MAHASISWA (hidden saat grouping aktif)
                TextColumn::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($user->hasRole('super_admin') || $user->hasRole('dosen')),

                // DOSEN
                TextColumn::make('dosen.name')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum ada dosen')
                    ->toggleable(),

                // TOPIK PERTEMUAN
                TextColumn::make('topik')
                    ->label('Topik Pertemuan')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    })
                    ->weight('medium'),

                // TANGGAL
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),

                // TYPE
                TextColumn::make('type')
                    ->label('Jenis'),

                // STATUS
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'completed',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                        default => $state,
                    }),

                // STATUS PROGRESS (STATUS DOMEN)
                BadgeColumn::make('status_domen')
                    ->label('Status Domen')
                    ->colors([
                        'warning' => 'revisi',
                        'info' => 'review',
                        'success' => 'fix',
                        'primary' => 'acc',
                        'danger' => 'tolak',
                        'gray' => 'selesai',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'revisi' => '🔄 Revisi',
                        'review' => '👀 Review',
                        'fix' => '✅ Fix',
                        'acc' => '🎉 ACC',
                        'tolak' => '❌ Ditolak',
                        'selesai' => '🏁 Selesai',
                        default => $state,
                    }),

                // ISI
                TextColumn::make('isi')
                    ->label('Isi Pertemuan')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                // KOMENTAR DOSEN
                TextColumn::make('komentar')
                    ->label('Komentar Dosen')
                    ->limit(40)
                    ->placeholder('Belum ada komentar')
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    })
                    ->visible(function ($record) use ($user) {
                        if (!$user) return false;

                        return $user->hasRole('super_admin')
                            || $user->hasRole('dosen')
                            || (!empty($record->komentar) && $user->hasRole('mahasiswa'));
                    })
                    ->toggleable(),

                // CREATED AT
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Bimbingan Baru')
                    ->icon('heroicon-o-plus') 
            ])
            ->filters([
                // Filter Status - Admin
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])
                    ->visible($user->hasRole('super_admin')),

                // Filter Mahasiswa - Dosen
                SelectFilter::make('mahasiswa')
                    ->relationship('mahasiswa', 'name')
                    ->searchable()
                    ->preload()
                    ->visible($user->hasRole('dosen')),

                // Filter Progress - Mahasiswa
                SelectFilter::make('status_domen')
                    ->label('Status Domen')
                    ->options([
                        'revisi' => '🔄 Butuh Revisi',
                        'review' => '👀 Dalam Review',
                        'fix' => '✅ Sudah Fix',
                        'acc' => '🎉 Diterima (ACC)',
                        'tolak' => '❌ Ditolak',
                        'selesai' => '🏁 Selesai',
                    ])
                    ->visible($user->hasRole('mahasiswa')),

                // Filter Jenis Bimbingan
                SelectFilter::make('type')
                    ->label('Jenis Bimbingan')
                    ->options([
                        'proposal' => 'Proposal',
                        'skripsi' => 'Skripsi',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                EditAction::make()->visible(false),
                
        ])  
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->toolbarActions([
                ForceDeleteBulkAction::make(),
                DeleteBulkAction::make(),
                
            ])
            ->defaultSort('tanggal', 'desc') 
            ->deferLoading(); 
    }
}
