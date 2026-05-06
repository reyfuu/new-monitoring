<?php

namespace App\Filament\Resources\Bimbingans\Tables;


use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use App\Jobs\SendBimbinganStatusEmail;
use App\Jobs\SendBimbinganStatusTelegram;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\ForceDeleteBulkAction;

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
                        'warning' => 'review',
                        'success' => 'disetujui',
                        'danger' => 'revisi',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => $state,
                    }),

                // REVISION COUNT
                TextColumn::make('revision_count')
                    ->label('Revisi Ke-')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn($state) => $state > 0 ? "#{$state}" : '-')
                    ->toggleable(),

                // ISI
                TextColumn::make('isi')
                    ->label('Isi Pertemuan')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->visible($user->hasRole('mahasiswa'))
            ])
            ->filters([
                // Filter Status - Admin
                SelectFilter::make('status')
                    ->options([
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                    ])
                    ->visible($user->hasRole('super_admin')),

                // Filter Mahasiswa - Dosen
                SelectFilter::make('mahasiswa')
                    ->relationship('mahasiswa', 'name')
                    ->searchable()
                    ->preload()
                    ->visible($user->hasRole('dosen')),

                // Filter Jenis Bimbingan
                SelectFilter::make('type')
                    ->label('Jenis Bimbingan')
                    ->options([
                        'proposal' => 'Proposal',
                        'skripsi' => 'Skripsi',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->recordActions([
                Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'review' => 'Review',
                                'disetujui' => 'Disetujui',
                                'revisi' => 'Revisi',
                            ])
                            ->required(),
                        Textarea::make('komentar')
                            ->label('Beri Komentar (Opsional)')
                            ->rows(3)
                            ->placeholder('Tulis feedback jika perlu...'),
                    ])
                    ->action(function ($record, array $data) {
                        \Illuminate\Support\Facades\Log::info("BimbingansTable update_status Triggered", [
                            'komentar' => $data['komentar'] ?? 'MISSING',
                            'record_id' => $record->id
                        ]);

                        $record->update([
                            'status' => $data['status'],
                            'komentar' => $data['komentar'],
                        ]);

                        if (!empty($data['komentar'])) {
                            $record->comments()->create([
                                'komentar' => $data['komentar'],
                                'tanggal' => now(),
                                'npm' => $record->mahasiswa?->npm,
                                'dosen' => Auth::user()->name,
                                'nidn' => Auth::user()->nidn,
                                'user_id' => Auth::id(),
                                'jenis' => 'Bimbingan',
                            ]);

                            \Illuminate\Support\Facades\Log::info("Comment Created for Bimbingan ID {$record->id}");
                            
                            // Kirim notifikasi dengan komentar
                            SendBimbinganStatusEmail::dispatch($record, $data['status'], $data['komentar']);
                            SendBimbinganStatusTelegram::dispatch($record, $data['status'], $data['komentar']);
                        } else {
                            // Kirim notifikasi tanpa komentar (status saja)
                            SendBimbinganStatusEmail::dispatch($record, $data['status'], null);
                            SendBimbinganStatusTelegram::dispatch($record, $data['status'], null);
                        }
                    })
                    ->visible(fn() => Auth::user()->hasRole('dosen'))
                    ->modalHeading('Update Status Bimbingan')
                    ->modalSubmitActionLabel('Simpan'),
            ])
            ->actions([
                Action::make('komentar')
                    ->label('Komentar')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->modalHeading('Riwayat Komentar')
                    ->modalSubmitAction(false)
                    ->infolist(fn ($record) => [
                        TextEntry::make('history_log')
                            ->label('')
                            ->html()
                            ->default(' ')
                            ->getStateUsing(function ($record) {
                                \Illuminate\Support\Facades\Log::info("Viewing History for Bimbingan ID: {$record->id}, Class: " . get_class($record));
                                // Get all comments for this student's bimbingans
                                $comments = \App\Models\Comment::whereHas('bimbingan', function ($query) use ($record) {
                                    $query->where('user_id', $record->user_id);
                                })->latest()->get();
                                
                                \Illuminate\Support\Facades\Log::info("Comments count: " . $comments->count());

                                if ($comments->isEmpty()) {
                                    return new HtmlString('<div class="text-gray-500 italic">Belum ada komentar</div>');
                                }

                                $html = '<div style="display: flex; flex-direction: column; gap: 1rem;">';
                                foreach ($comments as $comment) {
                                    $tanggal = $comment->tanggal ? $comment->tanggal->format('d M Y') : '-';
                                    $jenis = $comment->jenis ? "<span style='margin-right: 8px; padding: 2px 6px; border-radius: 4px; background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; font-size: 0.75rem; font-weight: bold;'>{$comment->jenis}</span>" : "";
                                    $html .= "
                                        <div style='padding: 1rem; border: 1px solid rgba(128, 128, 128, 0.3); border-radius: 0.5rem;'>
                                            <div style='display: flex; justify-content: space-between; margin-bottom: 0.5rem;'>
                                                <span style='font-weight: bold;'>Dosen: {$comment->dosen}</span>
                                                <span style='font-size: 0.875rem; opacity: 0.7; display: flex; align-items: center;'>{$jenis}{$tanggal}</span>
                                            </div>
                                            <div style='white-space: pre-wrap;'>{$comment->komentar}</div>
                                        </div>";
                                }
                                $html .= '</div>';
                                
                                return new HtmlString($html);
                            })
                    ])
                    ->visible(true),
                EditAction::make()
                    ->visible(
                        fn($record) => ! in_array(
                            strtolower(trim($record->status ?? '')),
                            ['completed', 'disetujui']
                        )
                    ),
                DeleteAction::make()
                    ->visible(function (\App\Models\Bimbingan $record) use ($user) {
                        if ($user->hasRole('super_admin')) return true;
                        if ($user->hasRole('mahasiswa') && $record->user_id == $user->id && $record->status == 'pending') return true;
                        return false;
                    }),
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
            ->defaultSort('tanggal', 'asc')
            ->deferLoading();
    }
}
