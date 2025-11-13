<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Laporan;
use Illuminate\Auth\Access\HandlesAuthorization;

class LaporanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Laporan');
    }

    public function view(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('View:Laporan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Laporan');
    }

    public function update(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('Update:Laporan');
    }

    public function delete(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('Delete:Laporan');
    }

    public function restore(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('Restore:Laporan');
    }

    public function forceDelete(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('ForceDelete:Laporan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Laporan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Laporan');
    }

    public function replicate(AuthUser $authUser, Laporan $laporan): bool
    {
        return $authUser->can('Replicate:Laporan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Laporan');
    }

}