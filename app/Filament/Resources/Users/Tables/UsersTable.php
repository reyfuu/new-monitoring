<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
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
                    ->formatStateUsing(function ($state, $record) {
                        $roles = $record->roles->pluck('name')->toArray();

                        if (in_array('mahasiswa', $roles) && $record->npm) {
                            return "{$record->name} - {$record->npm}";
                        } elseif (in_array('dosen', $roles) && $record->nidn) {
                            return "{$record->name} - {$record->nidn}";
                        }

                        return $record->name;
                    })
                    ->searchable(['name', 'npm', 'nidn'])
                    ->sortable(['name'])
                    ->wrap(),


                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('roles.name')
                    ->label('Role')
                    ->colors([
                        'primary' => 'super_admin',
                        'success' => 'dosen',
                        'warning' => 'mahasiswa',
                    ])
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'gray' => 'inactive',
                        'danger' => 'suspended',
                    ])
                    ->sortable(),

                TextColumn::make('angkatan')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dosenPembimbing.name')
                    ->label('Dosen Pembimbing')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
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
