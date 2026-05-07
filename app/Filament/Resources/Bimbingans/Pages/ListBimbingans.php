<?php

namespace App\Filament\Resources\Bimbingans\Pages;

use App\Filament\Resources\Bimbingans\BimbinganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

class ListBimbingans extends ListRecords
{
    protected static string $resource = BimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Bimbingan Baru')
                ->visible(fn() => Auth::user()->hasRole('mahasiswa')),
        ];
    }
}
