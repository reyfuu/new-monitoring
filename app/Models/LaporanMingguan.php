<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\SendLaporanMingguanBaruEmail;
use App\Jobs\SendLaporanMingguanStatusEmail;
use App\Jobs\SendLaporanMingguanStatusTelegram;

class LaporanMingguan extends Model
{
    protected $fillable = [
        'laporan_id',
        'mahasiswa_id',
        'dosen_id',
        'week',
        'isi',
        'status',
        'komentar',
    ];

    protected $casts = [
        'week' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($laporanMingguan) {
           
            $user = Auth::user();

            if($user && $user->hasRole('mahasiswa')){
                $laporanMingguan->mahasiswa_id = $user->id;

                if(empty($laporanMingguan->dosen_id)){
                    $laporanMingguan->dosen_id = $user->dosen_pembimbing_id;
                }
            }

        });

        static::created(function ($laporanMingguan) {
            SendLaporanMingguanBaruEmail::dispatch($laporanMingguan);
            SendLaporanMingguanStatusTelegram::dispatch($laporanMingguan, 'review', null, true);
        });

        static::updating(function($laporanMingguan){
            $user = Auth::user();

            if($user && $user->hasRole('mahasiswa')){
               $originalStatus = strtolower(trim($laporanMingguan->getOriginal('status') ?? ''));

               // Jika status awal adalah revisi, ubah ke review
               if($originalStatus == 'revisi'){
                    $laporanMingguan->status = 'review';
               }
            }
        });

        static::updated(function ($laporanMingguan) {
            if ($laporanMingguan->wasChanged(['status', 'komentar'])) {
                $newStatus = strtolower(trim($laporanMingguan->status));

                if (in_array($newStatus, ['disetujui', 'revisi', 'review'])) {
                    SendLaporanMingguanStatusEmail::dispatch($laporanMingguan, $newStatus, $laporanMingguan->komentar);
                    SendLaporanMingguanStatusTelegram::dispatch($laporanMingguan, $newStatus, $laporanMingguan->komentar);
                }
            }
        });
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
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

    public function scopeOfWeek($query, $week)
    {
        return $query->where('week', $week);
    }

    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

 
}
