<?php

namespace App\Filament\Resources\Bimbingans\Pages;

use App\Filament\Resources\Bimbingans\BimbinganResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBimbingan extends CreateRecord
{
    protected static string $resource = BimbinganResource::class;

    protected static ?string $title = 'Buat Bimbingan';

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

    public static function canAccess(array $parameters = []): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Hanya mahasiswa dan super_admin yang bisa membuat bimbingan
        return $user->hasRole('mahasiswa') || $user->hasRole('super_admin');
    }
}
