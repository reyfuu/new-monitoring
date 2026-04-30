<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method bool hasRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method bool hasAnyRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method bool hasPermissionTo(string|\Spatie\Permission\Contracts\Permission $permission, string|null $guardName = null)
 */

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'angkatan',
        'kategori',
        'dosen_pembimbing_id',
        'npm',
        'nidn',
        'telegram_chat_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * AKSES FILAMENT
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Relationship: User belongs to Dosen Pembimbing
     */
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    /**
     * Relationship: Dosen has many Mahasiswa
     */
    public function mahasiswaBimbingan()
    {
        return $this->hasMany(User::class, 'dosen_pembimbing_id');
    }

    /**
     * Scope untuk mendapatkan hanya dosen
     */
    public function scopeDosen($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'dosen');
        });
    }

    /**
     * Scope untuk mendapatkan hanya mahasiswa
     */
    public function scopeMahasiswa($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        });
    }

    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class, 'user_id');
    }

    /**
     * Hitung bimbingan yang sudah diverifikasi dosen
     */
    public function getBimbinganTerverifikasiAttribute()
    {
        return $this->bimbingans()
            ->whereNotNull('status_domen')
            ->where('status_domen', '!=', 'review')
            ->count();
    }

    /**
     * Hitung bimbingan yang masih menunggu
     */
    public function getBimbinganMenungguAttribute()
    {
        return $this->bimbingans()
            ->where(function ($query) {
                $query->whereNull('status_domen')
                    ->orWhere('status_domen', 'review');
            })
            ->count();
    }

    /**
     * Status yang dianggap terverifikasi
     */
    public function getStatusDomenTerverifikasiAttribute()
    {
        return ['disetujui'];
    }

    public function laporanSebagaiMahasiswa()
    {
        return $this->hasMany(Laporan::class, 'mahasiswa_id');
    }

    public function laporanSebagaiDosen()
    {
        return $this->hasMany(Laporan::class, 'dosen_id');
    }
}