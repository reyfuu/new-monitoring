<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\SendLaporanMingguanBaruEmail;
use App\Jobs\SendLaporanMingguanStatusEmail;
use App\Jobs\SendLaporanMingguanTelegram;
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
            SendLaporanMingguanTelegram::dispatch($laporanMingguan);
        });

        static::updating(function($laporanMingguan){
            $user = Auth::user();

            if($user && $user->hasRole('mahasiswa')){
               $originalStatus = strtolower(trim($laporanMingguan->getOriginal('status') ?? ''));

               if($originalStatus == 'revisi'){
                    $contentFiels = ['tanggal'];
                    $hasContentChanges = false;

                    foreach($contentFiels as $field){
                        if($laporanMingguan->isDirty($field)){
                            $hasContentChanges = true;
                            break;
                        }
                    }

                    if($hasContentChanges){
                        $laporanMingguan->revision_count = ($laporanMingguan->revision_count ?? 0) + 1;

                        $laporanMingguan->status = 'review';
                    }
               }
            }
        });

        static::updated(function ($laporanMingguan) {
            if ($laporanMingguan->isDirty('status')) {
                $newStatus = strtolower(trim($laporanMingguan->status));

                if (in_array($newStatus, ['disetujui', 'revisi'])) {
                    SendLaporanMingguanStatusEmail::dispatch($laporanMingguan, $newStatus);
                    SendLaporanMingguanStatusTelegram::dispatch($laporanMingguan, $newStatus);
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
