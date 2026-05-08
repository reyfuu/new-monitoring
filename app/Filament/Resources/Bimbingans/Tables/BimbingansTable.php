<?php

namespace App\Filament\Resources\Bimbingans\Tables;

// FIX: Import namespace yang benar
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanMingguan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\SendLaporanMingguanStatusEmail;
use App\Jobs\SendLaporanMingguanStatusTelegram;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;


class BimbingansTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $table
            ->recordUrl(null)
            ->persistColumnSearchesInSession(false)
            ->persistSearchInSession(false)
            ->persistSortInSession(false)
            ->persistFiltersInSession(false)
            ->groups([
                Group::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function ($record) {
                        // FIX: pakai relasi yang sudah di-eager load, tidak query ulang
                        $totalPertemuan = $record->mahasiswa->bimbingans_count
                            ?? \App\Models\Bimbingan::where('user_id', $record->user_id)->count();

                        return $record->mahasiswa->name . " ({$totalPertemuan} pertemuan)";
                    }),
            ])
            ->defaultGroup('mahasiswa.name')
            ->groupsOnly(false)
            ->striped()
            // FIX: hapus ->columnSpanFull (bukan method Table)
            ->groupingSettingsInDropdownOnDesktop()
            ->columns([
                TextColumn::make('pertemuan_ke')
                    ->label('Pertemuan')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-m-hashtag')
                    ->formatStateUsing(fn($state) => $state ? "Ke-{$state}" : 'N/A')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        // faster: count how many records up to this tanggal for the same user
                        return \App\Models\Bimbingan::where('user_id', $record->user_id)
                            ->where('tanggal', '<=', $record->tanggal)
                            ->count();
                    }),

                TextColumn::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible($user->hasRole('super_admin') || $user->hasRole('dosen')),

                TextColumn::make('dosen.name')
                    ->label('Dosen')
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum ada dosen')
                    ->toggleable(),

                TextColumn::make('topik')
                    ->label('Topik Pertemuan')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    })
                    ->weight('medium'),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->icon('heroicon-m-calendar')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'review' => 'warning',
                        'disetujui' => 'success',
                        'revisi' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'review' => 'heroicon-m-clock',
                        'disetujui' => 'heroicon-m-check-circle',
                        'revisi' => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => ucfirst($state),
                    }),

                TextColumn::make('revision_count')
                    ->label('Revisi Ke-')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-arrow-path')
                    ->formatStateUsing(fn($state) => $state > 0 ? "{$state}" : '-')
                    ->toggleable(),

                TextColumn::make('isi')
                    ->label('Isi Pertemuan')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Bimbingan Baru')
                    ->visible($user->hasRole('mahasiswa')),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                    ])
                    ->visible($user->hasRole('super_admin')),

                SelectFilter::make('mahasiswa')
                    ->relationship('mahasiswa', 'name')
                    ->searchable()
                    ->preload()
                    ->visible($user->hasRole('dosen')),

                SelectFilter::make('type')
                    ->label('Jenis Bimbingan')
                    ->options([
                        'proposal' => 'Proposal',
                        'skripsi' => 'Skripsi',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->recordActions([
            ])
            ->actions([
                Action::make('komentar')
                    ->label('Komentar')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->modalHeading('Riwayat Komentar')
                    ->modalSubmitAction(false)
                    ->infolist(fn($record) => [
                        TextEntry::make('history_log')
                            ->label('')
                            ->html()
                            ->default(' ')
                            ->getStateUsing(function ($record) {
                                $comments = \App\Models\Comment::whereHas('bimbingan', function ($query) use ($record) {
                                    $query->where('user_id', $record->user_id);
                                })->orderBy('tanggal', 'desc')->get();

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
                                
                                return new \Illuminate\Support\HtmlString($html);
                            }),
                    ]),

                EditAction::make()
                    ->visible(
                        fn($record) => !in_array(
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
            ->defaultSort('tanggal', 'asc')
            ->deferLoading();
    }
}