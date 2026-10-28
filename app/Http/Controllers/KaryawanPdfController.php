<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;

class KaryawanPdfController extends Controller
{
    public function rekap()
    {
        $karyawan = Karyawan::with('kandidat')
            ->orderBy('nama_lengkap')
            ->get();

        $pdf = Pdf::loadView('pdf.karyawan-rekap', [
            'karyawan' => $karyawan,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-karyawan.pdf');
    }

    public function detail(Karyawan $karyawan)
    {
        $karyawan->load([
            'kandidat',
            'pendidikan',
            'pengalamanKerja',
            'sertifikasi',
            'keluarga',
        ]);

        $fotoPath = $karyawan->foto
            ? public_path('storage/' . $karyawan->foto)
            : null;

        $foto = $fotoPath && file_exists($fotoPath)
            ? $fotoPath
            : null;

        $pdf = Pdf::loadView('pdf.karyawan-detail', [
            'karyawan' => $karyawan,
            'foto' => $foto,
        ])->setPaper('a4', 'portrait');

        return $pdf->download(
            'detail-karyawan-' . ($karyawan->nik ?: $karyawan->id) . '.pdf'
        );
    }
}
