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

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $record = $this->record;

        \Illuminate\Support\Facades\Log::info("EditLaporanMingguan afterSave Triggered", [
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
                'jenis' => 'Laporan Mingguan',
            ]);

            \Illuminate\Support\Facades\Log::info("Comment Created for Laporan Mingguan ID {$record->id}");

            // Kirim notifikasi dengan komentar
            \App\Jobs\SendLaporanMingguanStatusEmail::dispatch($record, $record->status, $data['komentar']);
            \App\Jobs\SendLaporanMingguanStatusTelegram::dispatch($record, $record->status, $data['komentar']);
        }
    }
}
