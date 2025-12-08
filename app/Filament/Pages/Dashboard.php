<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    protected static string $routePath = '/dashboard';

    public static function canAccess(): bool
    {
        // Allow all authenticated users to access (to prevent 403 loop)
        // Redirect is handled in mount()
        return true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Hide Dashboard from navigation for ka_prodi
        if ($user && $user->hasRole('ka_prodi')) {
            return false;
        }
        
        return true;
    }

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Redirect ka_prodi ke dashboard khusus
        if ($user && $user->hasRole('ka_prodi')) {
            $this->redirect('/kaprodi-dashboard');
        }
    }
}