<?php

namespace App\Filament\Resources\Bimbingans\Pages;

use App\Filament\Resources\Bimbingans\BimbinganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\EditAction;

class ListBimbingans extends ListRecords
{
    protected static string $resource = BimbinganResource::class;

    protected function getHeaderActions(): array
    {
        return [
              EditAction::make()->visible(false),
        ];
    }
}
