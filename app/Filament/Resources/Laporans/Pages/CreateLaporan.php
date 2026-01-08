<?php

namespace App\Filament\Resources\Laporans\Pages;

use App\Filament\Resources\Laporans\LaporanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporan extends CreateRecord
{
    protected static string $resource = LaporanResource::class;

    protected static ?string $title = 'Buat Laporan';

    protected static ?string $breadcrumb = 'Buat';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set tanggal_mulai ke hari ini jika belum ada
        if (!isset($data['tanggal_mulai'])) {
            $data['tanggal_mulai'] = now()->format('Y-m-d');
        }

        // Set tanggal_berakhir ke null jika belum ada
        if (!isset($data['tanggal_berakhir'])) {
            $data['tanggal_berakhir'] = null;
        }

        return $data;
    }
}
