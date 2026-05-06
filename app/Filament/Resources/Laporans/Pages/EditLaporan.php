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
    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;

        \Illuminate\Support\Facades\Log::info("EditLaporan afterSave Triggered", [
            'komentar' => $data['komentar'] ?? 'MISSING',
            'record_id' => $record->id
        ]);

        if (!empty($data['komentar'])) {
            $record->comments()->create([
                'komentar' => $data['komentar'],
                'tanggal' => now(),
                'npm' => $record->mahasiswa?->npm,
                'dosen' => auth()->user()->name,
                'nidn' => auth()->user()->nidn,
                'user_id' => auth()->id(),
                'jenis' => 'Laporan Akademik',
            ]);

            \Illuminate\Support\Facades\Log::info("Comment Created for Laporan ID {$record->id}");

            // Kirim notifikasi dengan komentar
            \App\Jobs\SendLaporanStatusEmail::dispatch($record, $record->status, $data['komentar']);
            \App\Jobs\SendLaporanStatusTelegram::dispatch($record, $record->status, $data['komentar']);
        }
    }
}
