<?php

namespace App\Filament\Resources\Laporans;

use App\Filament\Resources\Laporans\Pages\CreateLaporan;
use App\Filament\Resources\Laporans\Pages\EditLaporan;
use App\Filament\Resources\Laporans\Pages\ListLaporans;
use App\Filament\Resources\Laporans\Schemas\LaporanForm;
use App\Filament\Resources\Laporans\Tables\LaporansTable;
use App\Models\Laporan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel = 'Laporan Akademik';

    protected static ?string $pluralModelLabel = 'Daftar Laporan Akademik';

    protected static ?string $navigationLabel = 'Laporan';

    // 🧩 Grup menu di sidebar
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return LaporanForm::configure($schema);
    }

    public function afterSave(): void
    {
        // Logic setelah menyimpan record Laporan
        $laporan = $this->record;
        $oldStatus = $laporan->getOriginal('status');
        $newStatus = $laporan->status;

        if ($oldStatus === $newStatus) {
            return;
        }

        if (in_array($newStatus, ['direview', 'revisi'])) {
            $student= $laporan->mahasiswa;
            
            if($student){
                $message = match ($newStatus){
                    'direview' => 'Laporan Anda telah direview oleh dosen pembimbing.',
                    'revisi' => 'Laporan Anda memerlukan revisi sesuai dengan masukan dosen pembimbing.',
                    default => 'Status laporan Anda telah diperbarui.',
                };

            }
        }
    }

    public static function table(Table $table): Table
    {
        return LaporansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $query = \App\Models\Laporan::query();

        if ($user->hasRole('mahasiswa')) {
            // 👨‍🎓 Hanya laporan miliknya sendiri
            $query->where('mahasiswa_id', $user->id);
        }

        if ($user->hasRole('dosen')) {
            // 👨‍🏫 Laporan yang dibimbing oleh dosen ini
            $query->where('dosen_id', $user->id)
                ->orWhereHas('mahasiswa', function ($q) use ($user) {
                    $q->where('dosen_pembimbing_id', $user->id);
                });
        }

        if ($user->hasRole('super_admin')) {
            // 🧑‍💼 Semua laporan
            // (tidak perlu filter)
        }

        return (string) $query->count();
    }


    public static function getPages(): array
    {
        return [
            'index' => ListLaporans::route('/'),
            'create' => CreateLaporan::route('/create'),
            'edit' => EditLaporan::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Menu Laporan ditampilkan untuk semua role (tanpa filter kategori)
        return $user->hasAnyRole(['super_admin', 'dosen', 'mahasiswa']);
    }
}
