<?php

namespace App\Filament\Resources\LaporanMingguans;

use App\Filament\Resources\LaporanMingguans\Pages\CreateLaporanMingguan;
use App\Filament\Resources\LaporanMingguans\Pages\EditLaporanMingguan;
use App\Filament\Resources\LaporanMingguans\Pages\ListLaporanMingguans;
use App\Filament\Resources\LaporanMingguans\Schemas\LaporanMingguanForm;
use App\Filament\Resources\LaporanMingguans\Tables\LaporanMingguansTable;
use App\Models\LaporanMingguan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanMingguanResource extends Resource
{
    protected static ?string $model = LaporanMingguan::class;

    // 🧭 Icon di sidebar
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    // 🏷️ Label tunggal & jamak
    protected static ?string $modelLabel = 'Laporan Mingguan';

    protected static ?string $pluralModelLabel = 'Daftar Laporan Mingguan';

    // 📂 Label di sidebar
    protected static ?string $navigationLabel = 'Laporan Mingguan';

    // 🧩 Grup menu di sidebar
   


    public static function form(Schema $schema): Schema
    {
        return LaporanMingguanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanMingguansTable::configure($table);
    }


    public static function getPages(): array
    {
        return [
            'index' => ListLaporanMingguans::route('/'),
            'create' => CreateLaporanMingguan::route('/create'),
            'edit' => EditLaporanMingguan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('mahasiswa')) {
            return $query->whereHas('laporan', function ($q) use ($user) {
                $q->where('mahasiswa_id', $user->id);
            });
        }

        if ($user->hasRole('dosen')) {
            return $query->whereHas('laporan', function ($q) use ($user) {
                $q->where('dosen_id', $user->id);
            });
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['super_admin', 'dosen', 'mahasiswa']);
    }
}
