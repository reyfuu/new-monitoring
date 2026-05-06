<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'bimbingan_id',
        'laporan_id',
        'user_id',
        'npm',
        'dosen',
        'nidn',
        'tanggal',
        'komentar',
        'jenis',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function bimbingan()
    {
        return $this->belongsTo(Bimbingan::class);
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
