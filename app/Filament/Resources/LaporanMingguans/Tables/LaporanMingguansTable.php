<?php

namespace App\Filament\Resources\LaporanMingguans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanMingguan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use App\Jobs\SendLaporanMingguanStatusEmail;
use App\Jobs\SendLaporanMingguanStatusTelegram;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;


class LaporanMingguansTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $table
            ->recordUrl(null)
            ->groups($user->hasRole('mahasiswa') ? [] : [
                Group::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup($user->hasRole('mahasiswa') ? null : 'mahasiswa.name')
            ->groupingSettingsInDropdownOnDesktop()
            ->groupsOnly(false)
            ->columns([
                TextColumn::make('week')
                    ->label('Minggu Ke')
                    ->sortable()
                    ->badge()
                    ->icon('heroicon-m-calendar')
                    ->color('primary')
                    ->formatStateUsing(fn($state) => "Minggu {$state}"),

                TextColumn::make('isi')
                    ->label('Isi / Link')
                    ->icon('heroicon-m-document-text')
                    ->formatStateUsing(function ($state) {
                        if (filter_var($state, FILTER_VALIDATE_URL)) {
                            return "<div class='flex items-center gap-2'><span class='p-1.5 rounded-lg bg-primary-100 text-primary-700'><svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14'></path></svg></span> <a href='{$state}' target='_blank' class='text-primary-600 font-medium hover:underline'>Buka Dokumen</a></div>";
                        }
                        return e(\Illuminate\Support\Str::limit($state, 80));
                    })
                    ->html()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'review' => 'warning',
                        'disetujui' => 'success',
                        'revisi' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (?string $state): string => match ($state) {
                        'review' => 'heroicon-m-clock',
                        'disetujui' => 'heroicon-m-check-circle',
                        'revisi' => 'heroicon-m-exclamation-triangle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => $state ?? 'Review',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->icon('heroicon-m-clock')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'review' => 'Review',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Laporan Mingguan')
                    ->icon('heroicon-o-plus')
                    ->visible(fn() => Auth::user()->hasRole('mahasiswa')),
            ])
            ->actions([
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
                        $record->update([
                            'status' => $data['status'],
                        ]);

                        if (!empty($data['komentar'])) {
                            $record->comments()->create([
                                'komentar' => $data['komentar'],
                                'tanggal' => now(),
                                'npm' => $record->mahasiswa?->npm,
                                'dosen' => Auth::user()->name,
                                'nidn' => Auth::user()->nidn,
                                'user_id' => Auth::id(),
                                'jenis' => 'Laporan Mingguan',
                            ]);
                            
                            // Kirim notifikasi dengan komentar
                            SendLaporanMingguanStatusEmail::dispatch($record, $data['status'], $data['komentar']);
                            SendLaporanMingguanStatusTelegram::dispatch($record, $data['status'], $data['komentar']);
                        } else {
                            // Kirim notifikasi tanpa komentar
                            SendLaporanMingguanStatusEmail::dispatch($record, $data['status'], null);
                            SendLaporanMingguanStatusTelegram::dispatch($record, $data['status'], null);
                        }
                    })
                    ->visible(fn($record) => Auth::user()->hasRole('dosen'))
                    ->modalHeading('Update Status Laporan')
                    ->modalSubmitActionLabel('Simpan'),

                EditAction::make()
                    ->visible(fn() => Auth::user()->hasRole('mahasiswa')),

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
                                // Get all comments for this student's laporans
                                $comments = \App\Models\Comment::whereHas('laporanMingguan', function ($query) use ($record) {
                                    $query->where('mahasiswa_id', $record->mahasiswa_id);
                                })->latest()->get();

                                if ($comments->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString('<div class="text-gray-500 italic">Belum ada komentar</div>');
                                }

                                $html = '<div style="display: flex; flex-direction: column; gap: 1rem;">';
                                foreach ($comments as $comment) {
                                    $html .= sprintf(
                                        '<div style="border-left: 4px solid #3b82f6; padding-left: 1rem;">
                                            <div style="font-weight: bold; font-size: 0.875rem;">%s (%s)</div>
                                            <div style="font-size: 0.875rem; margin-top: 0.25rem;">%s</div>
                                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">%s</div>
                                        </div>',
                                        e($comment->dosen),
                                        e($comment->nidn),
                                        nl2br(e($comment->komentar)),
                                        $comment->tanggal->format('d M Y H:i')
                                    );
                                }
                                $html .= '</div>';

                                return new \Illuminate\Support\HtmlString($html);
                            })
                    ]),

            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->deferLoading();
    }
}
