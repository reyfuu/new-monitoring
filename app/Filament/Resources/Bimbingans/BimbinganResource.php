<?php

namespace App\Filament\Resources\Bimbingans;

use App\Filament\Resources\Bimbingans\Pages\CreateBimbingan;
use App\Filament\Resources\Bimbingans\Pages\EditBimbingan;
use App\Filament\Resources\Bimbingans\Pages\ListBimbingans;
use App\Filament\Resources\Bimbingans\Schemas\BimbinganForm;
use App\Filament\Resources\Bimbingans\Tables\BimbingansTable;
use App\Models\Bimbingan;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class BimbinganResource extends Resource
{
    protected static ?string $model = Bimbingan::class;

    // BimbinganResource.php
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $modelLabel = 'Bimbingan';
    protected static ?string $pluralModelLabel = 'Daftar Bimbingan';
    protected static ?string $navigationLabel = 'Bimbingan';
    // protected static ?string $navigationGroup = 'Akademik';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return BimbinganForm::configure($schema);
    }


    public static function getPages(): array
    {
        return [
            'index' => ListBimbingans::route('/'),
            'create' => CreateBimbingan::route('/create'),
            'edit' => EditBimbingan::route('/{record}/edit'),
        ];
    }

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return BimbingansTable::configure($table)
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if ($user->hasRole('mahasiswa')) {
                    return $query->where('user_id', $user->id);
                }
                if ($user->hasRole('dosen')) {
                    return $query->where('dosen_id', $user->id)
                        ->orWhereHas('mahasiswa', function ($q) use ($user) {
                            $q->where('dosen_pembimbing_id', $user->id);
                        });
                }
                return $query;
            })
            ->actions([
                EditAction::make()
                    ->visible(
                        fn($record) => ! in_array(
                            strtolower(trim($record->status ?? '')),
                            ['completed', 'disetujui']
                        )
                    ),
                DeleteAction::make()
                    ->visible(function (Bimbingan $record) use ($user) {
                        if ($user->hasRole('super_admin')) return true;
                        if ($user->hasRole('mahasiswa') && $record->user_id == $user->id && $record->status == 'pending') return true;
                        return false;
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->visible($user->hasRole('super_admin')),
            ]);
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Hanya mahasiswa dan super_admin yang bisa membuat bimbingan
        return $user->hasRole('mahasiswa') || $user->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Super admin dan dosen selalu lihat menu Bimbingan
        if ($user->hasAnyRole(['super_admin', 'dosen'])) {
            return true;
        }

        // Mahasiswa hanya lihat jika kategorinya 'skripsi'
        if ($user->hasRole('mahasiswa')) {
            return $user->kategori === 'skripsi';
        }

        return false;
    }
}
