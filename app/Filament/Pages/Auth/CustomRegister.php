<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomRegister extends Register
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNpmFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getKategoriFormComponent(),
                $this->getTelegramChatIdFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getNpmFormComponent(): Component
    {
        return TextInput::make('npm')
            ->label('NPM')
            ->helperText('Nomor Pokok Mahasiswa')
            ->required()
            ->maxLength(20)
            ->autofocus();
    }

    protected function getKategoriFormComponent(): Component
    {
        return Select::make('kategori')
            ->label('Kategori Mahasiswa')
            ->options([
                'Magang' => 'Magang',
                'Skripsi' => 'Skripsi',
            ])
            ->required();
    }

    protected function getTelegramChatIdFormComponent(): Component
    {
        return TextInput::make('telegram_chat_id')
            ->label('Telegram Chat ID')
            ->helperText('Dapatkan ID melalui @userinfobot di Telegram')
            ->numeric()
            ->maxLength(50);
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->revealable();
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->revealable();
    }

    protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'npm' => $data['npm'] ?? null,
            'kategori' => $data['kategori'] ?? null,
            'telegram_chat_id' => $data['telegram_chat_id'] ?? null,
        ]);

        $user->assignRole('mahasiswa');

        return $user;
    }
}
