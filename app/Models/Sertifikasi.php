<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $fillable = [
        'kandidat_id',
        'karyawan_id',
        'nama_sertifikat',
        'bidang_sertifikasi',
        'badan_pengesahan',
        'tahun_lulus',
        'file_sertifikat',
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
