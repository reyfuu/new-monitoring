<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\SendBimbinganBaruEmail;
use App\Jobs\SendBimbinganStatusEmail;

class Bimbingan extends Model
{


    protected $fillable = [
        'topik',
        'status',
        'status_domen',
        'user_id',
        'dosen_id',
        'tanggal',
        'isi',
        'type',
        'komentar',
        'revision_count',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bimbingan) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if ($user && $user->hasRole('mahasiswa')) {
                $bimbingan->user_id = $user->id;
                
                // Only set dosen_id if not already provided by form (fallback)
                if (empty($bimbingan->dosen_id)) {
                    $bimbingan->dosen_id = $user->dosen_pembimbing_id;
                }
            }

            // Auto hitung pertemuan ke berapa
            if ($bimbingan->user_id) {
                $bimbingan->pertemuan_ke = static::where('user_id', $bimbingan->user_id)
                    ->count() + 1;
            }
        });

        // Kirim email ke dosen ketika bimbingan baru dibuat
        static::created(function ($bimbingan) {
            SendBimbinganBaruEmail::dispatch($bimbingan);
        });

        // Auto-update status dan revision_count ketika mahasiswa mengedit bimbingan revisi
        static::updating(function ($bimbingan) {
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            
            // Hanya berlaku jika user adalah mahasiswa
            if ($user && $user->hasRole('mahasiswa')) {
                $originalStatus = strtolower(trim($bimbingan->getOriginal('status') ?? ''));
                
                // Jika status sebelumnya 'revisi' dan ada perubahan konten
                if ($originalStatus === 'revisi') {
                    $contentFields = ['topik', 'isi', 'tanggal'];
                    $hasContentChange = false;
                    
                    foreach ($contentFields as $field) {
                        if ($bimbingan->isDirty($field)) {
                            $hasContentChange = true;
                            break;
                        }
                    }
                    
                    if ($hasContentChange) {
                        // Increment revision count
                        $bimbingan->revision_count = ($bimbingan->revision_count ?? 0) + 1;
                        // Ubah status ke review
                        $bimbingan->status = 'review';
                    }
                }
            }
        });

        // Kirim email ke mahasiswa ketika status diubah
        static::updated(function ($bimbingan) {
            // Cek apakah status berubah ke 'disetujui' atau 'ditolak'
            if ($bimbingan->isDirty('status')) {
                $newStatus = strtolower(trim($bimbingan->status));
                
                if (in_array($newStatus, ['disetujui', 'revisi'])) {
                    SendBimbinganStatusEmail::dispatch(
                        $bimbingan,
                        $newStatus,
                        $bimbingan->komentar
                    );
                }
            }
        });
        
    }

    // Relationship: Bimbingan belongs to Mahasiswa
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: Bimbingan belongs to Dosen
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Scope untuk bimbingan mahasiswa tertentu
    public function scopeByMahasiswa($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope untuk bimbingan dosen tertentu
    public function scopeByDosen($query, $dosenId)
    {
        return $query->where(function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId)
                ->orWhereHas('mahasiswa', function ($subQuery) use ($dosenId) {
                    $subQuery->where('dosen_pembimbing_id', $dosenId);
                });
        });
    }

    // Scope untuk bimbingan by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk bimbingan by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function pertemuans()
    {
        return $this->hasMany(Pertemuan::class);
    }

}
