<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                // TextColumn::make('name')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Lengkap')
                    ->icon('heroicon-m-user')
                    ->formatStateUsing(function ($state, $record) {
                        $roles = $record->roles->pluck('name')->toArray();

                        if (in_array('mahasiswa', $roles) && $record->npm) {
                            return "{$record->name} ({$record->npm})";
                        } elseif (in_array('dosen', $roles) && $record->nidn) {
                            return "{$record->name} ({$record->nidn})";
                        }

                        return $record->name;
                    })
                    ->searchable(['name', 'npm', 'nidn'])
                    ->sortable(['name'])
                    ->weight('bold')
                    ->wrap(),


                TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'primary',
                        'dosen' => 'success',
                        'mahasiswa' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'super_admin' => 'heroicon-m-shield-check',
                        'dosen' => 'heroicon-m-academic-cap',
                        'mahasiswa' => 'heroicon-m-user',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'suspended' => 'danger',
                        default => 'secondary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-m-check-circle',
                        'inactive' => 'heroicon-m-x-circle',
                        'suspended' => 'heroicon-m-no-symbol',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->sortable(),

                TextColumn::make('angkatan')
                    ->icon('heroicon-m-hashtag')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->icon('heroicon-m-clock')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat User Baru')
                    ->icon('heroicon-o-plus')
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ]),

                SelectFilter::make('angkatan')
                    ->options(function () {
                        return User::distinct()
                            ->whereNotNull('angkatan')
                            ->pluck('angkatan', 'angkatan')
                            ->toArray();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
