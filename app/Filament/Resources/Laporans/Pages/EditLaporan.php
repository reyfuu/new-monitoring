<?php

namespace App\Filament\Resources\Laporans\Pages;

use App\Filament\Resources\Laporans\LaporanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditLaporan extends EditRecord
{
    protected static string $resource = LaporanResource::class;

    protected static ?string $title = 'Perbaharui Laporan';

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Check if a new dokumen is being uploaded
        if (isset($data['dokumen']) && $data['dokumen'] !== $this->record->dokumen) {
            // Delete old dokumen file if it exists
            if ($this->record->dokumen && Storage::disk('public')->exists($this->record->dokumen)) {
                Storage::disk('public')->delete($this->record->dokumen);
            }
        }

        return $data;
    }
}
