<?php

namespace App\Filament\Resources\LaporanMingguans\Pages;

use App\Filament\Resources\LaporanMingguans\LaporanMingguanResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListLaporanMingguans extends ListRecords
{
    protected static string $resource = LaporanMingguanResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
