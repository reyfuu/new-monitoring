<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\SendLaporanBaruEmail;
use App\Jobs\SendLaporanStatusEmail;

class Laporan extends Model
{
    protected $fillable = [
        'judul',
        'tanggal_mulai',
        'tanggal_berakhir',
        'deskripsi',
        'mahasiswa_id',
        'dosen_id',
        'dokumen',
        'status',
        'status_dosen',
        'type',
        'komentar',
        'revision_count',
    ];

    protected static function boot ()
    {
        parent::boot();

        static::creating(function ($laporan) {
            // Auto-assign mahasiswa_id and dosen_id if not set
            $user = Auth::user();

            if ($user && $user->hasRole('mahasiswa')) {
                $laporan->mahasiswa_id = $user->id;

                if (empty($laporan->dosen_id)) {
                    $laporan->dosen_id = $user->dosen_pembimbing_id;
                }
            }
        });

        static::created(function ($laporan) {
            // Dispatch email job for new laporan
            SendLaporanBaruEmail::dispatch($laporan);
        });

        static::updating(function ($laporan) {
            $user = Auth::user();

            if ($user && $user->hasRole('mahasiswa')) {
                $originalStatus = strtolower(trim($laporan->getOriginal('status') ?? ''));

                if ($originalStatus == 'revisi') {
                    $contentFields = ['judul','deskripsi'];
                    $hasContentChanges = false;

                    foreach ($contentFields as $field) {
                        if ($laporan->isDirty($field)) {
                            $hasContentChanges = true;
                            break;
                        }
                    }

        
                }
            }
        });

        static::updated(function ($laporan) {
            if($laporan->isDirty('status')){
                $newStatus = strtolower(trim($laporan->status ?? ''));

                if(in_array($newStatus, ['disetujui', 'revisi'])){
                    SendLaporanStatusEmail::dispatch($laporan, $newStatus, $laporan->komentar);
                }
            }
        });
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id')->whereHas('roles', fn($q) => $q->where('name', 'mahasiswa'));
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id')->whereHas('roles', fn($q) => $q->where('name', 'dosen'));
    }

    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where(function ($q) use ($dosenId) {
            $q->where('dosen_id', $dosenId)
                ->orWhereHas('mahasiswa', function ($subQuery) use ($dosenId) {
                    $subQuery->where('dosen_pembimbing_id', $dosenId);
                });
        });
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
