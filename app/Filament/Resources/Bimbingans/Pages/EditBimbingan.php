<?php

namespace App\Filament\Resources\Bimbingans\Pages;

use App\Filament\Resources\Bimbingans\BimbinganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBimbingan extends EditRecord
{
    protected static string $resource = BimbinganResource::class;

    protected static ?string $title = 'Perbaharui Bimbingan';

    protected static ?string $breadcrumb = 'Perbaharui';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
