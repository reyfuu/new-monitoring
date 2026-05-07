<?php

namespace App\Filament\Resources\Laporans\Tables;

use App\Models\Laporan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use App\Jobs\SendLaporanStatusEmail;
use App\Jobs\SendLaporanStatusTelegram;

class LaporansTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $table
            ->query(function () use ($user) {
                $query = Laporan::query();

                if ($user->hasRole('mahasiswa')) {
                    // 👨‍🎓 hanya laporan miliknya
                    $query->where('mahasiswa_id', $user->id);
                }

                if ($user->hasRole('dosen')) {
                    // 👨‍🏫 laporan yang dibimbing langsung + mahasiswa bimbingannya
                    $query->where('dosen_id', $user->id)
                        ->orWhereHas('mahasiswa', function ($q) use ($user) {
                            $q->where('dosen_pembimbing_id', $user->id);
                        });
                }

                // 🧑‍💼 super_admin lihat semua laporan (tanpa filter)

                return $query;
            })
            ->recordUrl(null)
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->icon('heroicon-m-document-text')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->judul)
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('mahasiswa.name')
                    ->label('Mahasiswa')
                    ->icon('heroicon-m-user')
                    ->disabled(fn() => auth()->user()?->hasRole('dosen'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('dosen.name')
                    ->label('Dosen Pembimbing')
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('dokumen')
                    ->label('Dokumen')
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

                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->icon('heroicon-m-calendar')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Laporan Baru')
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
                        \Illuminate\Support\Facades\Log::info("LaporansTable update_status Triggered", [
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
                                'jenis' => 'Laporan Akademik',
                            ]);

                            \Illuminate\Support\Facades\Log::info("Comment Created for Laporan ID {$record->id}");

                            // Kirim notifikasi dengan komentar
                            SendLaporanStatusEmail::dispatch($record, $data['status'], $data['komentar']);
                            SendLaporanStatusTelegram::dispatch($record, $data['status'], $data['komentar']);
                        } else {
                            // Kirim notifikasi tanpa komentar (status saja)
                            SendLaporanStatusEmail::dispatch($record, $data['status'], null);
                            SendLaporanStatusTelegram::dispatch($record, $data['status'], null);
                        }
                    })
                    ->visible(fn($record) => Auth::user()->hasRole('dosen'))
                    ->modalHeading('Update Status Laporan')
                    ->modalSubmitActionLabel('Simpan'),

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
                                \Illuminate\Support\Facades\Log::info("Viewing History for Laporan ID: {$record->id}");
                                
                                // Get all comments for this student's laporans
                                $comments = \App\Models\Comment::whereHas('laporan', function ($query) use ($record) {
                                    $query->where('mahasiswa_id', $record->mahasiswa_id);
                                })->latest()->get();

                                if ($comments->isEmpty()) {
                                    return new \Illuminate\Support\HtmlString('<div class="text-gray-500 italic">Belum ada komentar</div>');
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
                            })
                    ])
                    ->visible(true),
                EditAction::make()
                    ->visible(fn($record) => $record->status !== 'disetujui'),
                DeleteAction::make()
                    ->visible(fn($record) => ($user->hasRole('mahasiswa') || $user->hasRole('super_admin')) && $record->status !== 'disetujui'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => $user->hasRole('mahasiswa') || $user->hasRole('super_admin')),
                ]),
            ]);
    }
}
