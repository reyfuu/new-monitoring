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

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;

        \Illuminate\Support\Facades\Log::info("EditBimbingan afterSave Triggered", [
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
                'jenis' => 'Bimbingan',
            ]);

            \Illuminate\Support\Facades\Log::info("Comment Created for Bimbingan ID {$record->id}");

            // Kirim notifikasi dengan komentar
            \App\Jobs\SendBimbinganStatusEmail::dispatch($record, $record->status, $data['komentar']);
            \App\Jobs\SendBimbinganStatusTelegram::dispatch($record, $record->status, $data['komentar']);
        }
    }
}
