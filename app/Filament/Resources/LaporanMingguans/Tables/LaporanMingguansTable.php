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
                    ->view('filament.tables.columns.badge-minggu'),

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
                    ->view('filament.tables.columns.status-badge'),

                // komentar_terakhir column removed per request

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
                EditAction::make()
                    ->label('Ubah')
                    ->visible(fn() => Auth::user()->hasAnyRole(['mahasiswa', 'dosen', 'admin'])),

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
