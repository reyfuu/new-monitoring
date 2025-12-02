<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id')->whereHas('roles', fn ($q) => $q->where('name', 'mahasiswa'));
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id')->whereHas('roles', fn ($q) => $q->where('name', 'dosen'));
    }
}
