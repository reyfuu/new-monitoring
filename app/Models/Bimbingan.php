<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendBimbinganStatusTelegram;

class Bimbingan extends Model
{
    protected $table = 'bimbingans';

    protected $fillable = [
        'topik',
        'status',
        'user_id',
        'dosen_id',
        'tanggal',
        'pertemuan_ke',
        'isi',
        'type',
        'komentar',
        'revision_count',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'pertemuan_ke' => 'integer',
        'revision_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bimbingan) {
            $user = Auth::user();
            if ($user && $user->hasRole('mahasiswa')) {
                $bimbingan->user_id = $user->id;
                if (empty($bimbingan->dosen_id)) {
                    $bimbingan->dosen_id = $user->dosen_pembimbing_id;
                }
            }
            if (empty($bimbingan->status)) {
                $bimbingan->status = 'review';
            }
        });

        static::created(function ($bimbingan) {
            SendBimbinganStatusTelegram::dispatch($bimbingan, 'review', null, true);
        });

        static::updating(function ($bimbingan) {
            $user = Auth::user();
            if ($user && $user->hasRole('mahasiswa')) {
                $originalStatus = strtolower(trim($bimbingan->getOriginal('status') ?? ''));
                if ($originalStatus == 'revisi') {
                    $bimbingan->status = 'review';
                }
            }
        });

        static::updated(function ($bimbingan) {
            if ($bimbingan->wasChanged(['status', 'komentar'])) {
                $newStatus = strtolower(trim($bimbingan->status));
                if (in_array($newStatus, ['disetujui', 'revisi', 'review'])) {
                    SendBimbinganStatusTelegram::dispatch($bimbingan, $newStatus, $bimbingan->komentar);
                }
            }
        });
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
