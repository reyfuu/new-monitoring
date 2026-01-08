<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method bool hasRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method bool hasAnyRole(string|array|\Spatie\Permission\Contracts\Role ...$roles)
 * @method bool hasPermissionTo(string|\Spatie\Permission\Contracts\Permission $permission, string|null $guardName = null)
 */

class User extends Authenticatable
{
    use Notifiable, HasRoles;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship: User belongs to Dosen Pembimbing
    public function dosenPembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_pembimbing_id');
    }

    // Relationship: Dosen has many Mahasiswa
    public function mahasiswaBimbingan()
    {
        return $this->hasMany(User::class, 'dosen_pembimbing_id');
    }

    // Scope untuk mendapatkan hanya dosen
    public function scopeDosen($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'dosen');
        });
    }

    // Scope untuk mendapatkan hanya mahasiswa  
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

    // ✅ NEW: Hitung bimbingan yang sudah diverifikasi dosen (status_domen ada isinya)
    public function getBimbinganTerverifikasiAttribute()
    {
        return $this->bimbingans()
            ->whereNotNull('status_domen') // Sudah ada status dari dosen
            ->where('status_domen', '!=', 'review') // Exclude yang masih 'review'
            ->count();
    }

    // ✅ NEW: Hitung bimbingan yang masih menunggu (status_domen null atau masih review)
    public function getBimbinganMenungguAttribute()
    {
        return $this->bimbingans()
            ->where(function ($query) {
                $query->whereNull('status_domen')
                    ->orWhere('status_domen', 'review');
            })
            ->count();
    }

    // ✅ NEW: List status_domen yang dianggap "selesai/terverifikasi"
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
