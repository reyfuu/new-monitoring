<?php

namespace App\Filament\Resources\LaporanMingguans\Pages;

use App\Filament\Resources\LaporanMingguans\LaporanMingguanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLaporanMingguan extends EditRecord
{
    protected static string $resource = LaporanMingguanResource::class;

    protected static ?string $title = 'Perbaharui Laporan Mingguan';

    protected static ?string $breadcrumb = 'Perbaharui';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        if ($user && $user->hasRole('mahasiswa')) {
            $originalStatus = strtolower(trim($this->record->status ?? ''));

            if ($originalStatus === 'revisi') {
                $data['status'] = 'review';
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
