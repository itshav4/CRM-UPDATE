<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengalamanKerja extends Model
{
    protected $fillable = [
        'kandidat_id',
        'karyawan_id',
        'nama_perusahaan',
        'jenis_perusahaan',
        'tahun_masuk',
        'tahun_keluar',
        'jabatan',
        'pekerjaan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }
}
