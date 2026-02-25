<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\SendLaporanBaruEmail;
use App\Jobs\SendLaporanStatusEmail;
use App\Jobs\SendLaporanStatusTelegram;

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

            if (empty($laporan->status)) {
                $laporan->status = 'review';
            }
        });

        static::created(function ($laporan) {
            SendLaporanBaruEmail::dispatch($laporan);
            SendLaporanStatusTelegram::dispatch($laporan, 'review');
        });

        static::updating(function ($laporan) {
            \Illuminate\Support\Facades\Log::info("Laporan UPDATING Triggered: ID {$laporan->id}");
            $user = Auth::user();

            if ($user && $user->hasRole('mahasiswa')) {
                $originalStatus = strtolower(trim($laporan->getOriginal('status') ?? ''));

                if ($originalStatus == 'revisi') {
                    $contentFields = ['judul', 'deskripsi', 'dokumen'];
                    $hasContentChanges = false;

                    foreach ($contentFields as $field) {
                        if ($laporan->isDirty($field)) {
                            $hasContentChanges = true;
                            break;
                        }
                    }

                    if ($hasContentChanges) {
                        $laporan->status = 'review';
                    }
                }
            }
        });

        static::updated(function ($laporan) {
            \Illuminate\Support\Facades\Log::info("Laporan UPDATED Triggered: ID {$laporan->id}, Status: {$laporan->status}, WasChanged(status): " . ($laporan->wasChanged('status') ? 'YES' : 'NO'));
            
            if ($laporan->wasChanged('status')) {
                $newStatus = strtolower(trim($laporan->status ?? ''));
                \Illuminate\Support\Facades\Log::info("Laporan Status Hook Match: New Status: {$newStatus}");

                if (in_array($newStatus, ['disetujui', 'revisi', 'review'])) {
                    SendLaporanStatusEmail::dispatch($laporan, $newStatus, $laporan->komentar);
                    SendLaporanStatusTelegram::dispatch($laporan, $newStatus, $laporan->komentar);
                }
            }
        });
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
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
