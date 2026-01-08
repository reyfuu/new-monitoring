<?php

namespace App\Filament\Resources\LaporanMingguans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanMingguan;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;


class LaporanMingguansTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $table
            ->recordUrl(null)
            ->groups($user->hasRole('mahasiswa') ? [] : [
                Group::make('laporan.mahasiswa.name')
                    ->label('Mahasiswa')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup($user->hasRole('mahasiswa') ? null : 'laporan.mahasiswa.name')
            ->groupingSettingsInDropdownOnDesktop()
            ->groupsOnly(false)
            ->columns([
                TextColumn::make('week')
                    ->label('Minggu Ke')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('isi')
                    ->label('Isi / Link')
                    ->formatStateUsing(function ($state) {
                        if (filter_var($state, FILTER_VALIDATE_URL)) {
                            return "<a href='{$state}' target='_blank' class='text-primary-600 underline'>Buka Dokumen</a>";
                        }
                        return e(\Illuminate\Support\Str::limit($state, 80));
                    })
                    ->html()
                    ->searchable()
                    ->wrap(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'disetujui',
                        'danger' => 'revisi',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                        default => $state ?? 'Pending',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'revisi' => 'Revisi',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Laporan Mingguan')
                    ->icon('heroicon-o-plus')
                    ->visible(fn() => $user->hasRole('mahasiswa')),
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
                                'pending' => 'Pending',
                                'disetujui' => 'Disetujui',
                                'revisi' => 'Revisi',
                            ])
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update($data);
                    })
                    ->visible(fn($record) => $user->hasRole('dosen'))
                    ->modalHeading('Update Status Laporan')
                    ->modalSubmitActionLabel('Simpan'),
            ])
            ->actions([
                EditAction::make(),
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
