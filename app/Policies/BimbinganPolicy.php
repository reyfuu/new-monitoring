<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Bimbingan;
use Illuminate\Auth\Access\HandlesAuthorization;

class BimbinganPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Bimbingan');
    }

    public function view(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('View:Bimbingan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Bimbingan');
    }

    public function update(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('Update:Bimbingan');
    }

    public function delete(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('Delete:Bimbingan');
    }

    public function restore(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('Restore:Bimbingan');
    }

    public function forceDelete(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('ForceDelete:Bimbingan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Bimbingan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Bimbingan');
    }

    public function replicate(AuthUser $authUser, Bimbingan $bimbingan): bool
    {
        return $authUser->can('Replicate:Bimbingan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Bimbingan');
    }

}