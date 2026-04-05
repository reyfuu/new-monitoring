<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\PasswordReset\ResetPassword as BaseResetPassword;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;

class CustomResetPassword extends BaseResetPassword
{
    public function mount(?string $email = null, ?string $token = null): void
    {
        if (Filament::auth()->check()) {
            Filament::auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        $this->token = $token ?? request()->query('token');

        $this->form->fill([
            'email' => $email ?? request()->query('email'),
        ]);

        $requestEmail = $email ?? request()->query('email');
        $requestToken = $token ?? request()->query('token');

        if (! $requestEmail || ! $requestToken) {
            $this->abortReset('Link reset password tidak valid atau tidak lengkap.');
            return;
        }

        $broker = Filament::getAuthPasswordBroker();
        $expire = config("auth.passwords.{$broker}.expire", 60);

        $record = DB::table('password_reset_tokens')
            ->where('email', $requestEmail)
            ->first();

        // If no token record found at all for this email
        if (! $record) {
            $this->abortReset('Token reset password tidak ditemukan atau sudah digunakan.');
            return;
        }

        // If the token is too old (expired based on config)
        if (now()->subMinutes($expire)->isAfter($record->created_at)) {
            $this->abortReset("Link reset password sudah kedaluwarsa setelah {$expire} menit. Silakan minta link baru.");
            return;
        }

        // If the token value does not match the hashed one in the database
        if (! Hash::check($requestToken, $record->token)) {
            $this->abortReset('Token reset password tidak sesuai atau tidak valid.');
            return;
        }
    }

    protected function abortReset(string $message): void
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();

        // Redirect user back to the login page
        $this->redirect(Filament::getLoginUrl());
    }
}
