<?php

namespace App\Filament\Resources\Bimbingans\Pages;

use App\Filament\Resources\Bimbingans\BimbinganResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\EditAction;
use Illuminate\Support\Facades\Auth;

class ListBimbingans extends ListRecords
{
    protected static string $resource = BimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
