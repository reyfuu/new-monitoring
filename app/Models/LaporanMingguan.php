<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
