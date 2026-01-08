<?php

namespace App\Filament\Resources\LaporanMingguans\Pages;

use App\Filament\Resources\LaporanMingguans\LaporanMingguanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanMingguan extends CreateRecord
{
    protected static string $resource = LaporanMingguanResource::class;

    protected static ?string $title = 'Buat Laporan Mingguan';

    protected static ?string $breadcrumb = 'Buat';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user->hasRole('mahasiswa')) {
            $data['mahasiswa_id'] = $user->id;
            $data['dosen_id'] = $user->dosen_pembimbing_id;
            $data['status'] = 'pending';
        }

        return $data;
    }
}
