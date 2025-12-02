<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMingguan extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'isi',
        'status',
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
