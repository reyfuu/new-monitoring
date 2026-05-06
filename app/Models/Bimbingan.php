<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendBimbinganStatusTelegram;
use App\Jobs\SendBimbinganBaruEmail;
use App\Jobs\SendBimbinganStatusEmail;

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
            SendBimbinganBaruEmail::dispatch($bimbingan);
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

        // Kirim email + Telegram ke mahasiswa ketika status atau komentar diubah
        static::updated(function ($bimbingan) {
            if ($bimbingan->wasChanged(['status'])) {
                $newStatus = strtolower(trim($bimbingan->status));
                if (in_array($newStatus, ['disetujui', 'revisi', 'review'])) {
                    SendBimbinganStatusEmail::dispatch($bimbingan, $newStatus, null);
                    SendBimbinganStatusTelegram::dispatch($bimbingan, $newStatus, null);
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
    public function comments()
    {
        return $this->hasMany(Comment::class, 'bimbingan_id');
    }

    public function latestComment()
    {
        return $this->hasOne(Comment::class, 'bimbingan_id')->latestOfMany();
    }
}
