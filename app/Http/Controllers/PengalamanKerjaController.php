<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Karyawan;
use App\Models\PengalamanKerja;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PengalamanKerjaController extends Controller
{
    public function store(Request $request, Karyawan $karyawan)
    {
        return $this->storeForOwner($request, $karyawan->pengalamanKerja());
    }

    public function storeForKandidat(Request $request, Kandidat $kandidat)
    {
        return $this->storeForOwner($request, $kandidat->pengalamanKerja());
    }

    private function storeForOwner(Request $request, HasMany $pengalamanKerja)
    {
        $pengalaman = $pengalamanKerja->create($this->validatedData($request));

        return response()->json([
            'message' => 'Pengalaman kerja berhasil ditambahkan.',
            'data' => $this->experienceResponse($pengalaman),
        ], 201);
    }

    public function update(Request $request, PengalamanKerja $pengalaman)
    {
        $pengalaman->update($this->validatedData($request));

        return response()->json([
            'message' => 'Pengalaman kerja berhasil diperbarui.',
            'data' => $this->experienceResponse($pengalaman->fresh()),
        ]);
    }

    public function destroy(PengalamanKerja $pengalaman)
    {
        $id = $pengalaman->id;
        $pengalaman->delete();

        return response()->json([
            'message' => 'Pengalaman kerja berhasil dihapus.',
            'deleted_id' => $id,
        ]);
    }

    private function validatedData(Request $request): array
    {
        $currentYear = (int) date('Y');

        return $request->validate([
            'nama_perusahaan' => ['required', 'string', 'max:255'],
            'jenis_perusahaan' => ['required', 'string', 'max:255'],
            'tahun_masuk' => ['required', 'integer', 'between:1900,'.$currentYear],
            'tahun_keluar' => ['nullable', 'integer', 'between:1900,'.($currentYear + 10), 'gte:tahun_masuk'],
            'jabatan' => ['required', 'string', 'max:255'],
            'pekerjaan' => ['required', 'string', 'max:5000'],
        ]);
    }

    private function experienceResponse(PengalamanKerja $pengalaman): array
    {
        return [
            'id' => $pengalaman->id,
            'nama_perusahaan' => $pengalaman->nama_perusahaan,
            'jenis_perusahaan' => $pengalaman->jenis_perusahaan,
            'tahun_masuk' => $pengalaman->tahun_masuk,
            'tahun_keluar' => $pengalaman->tahun_keluar,
            'jabatan' => $pengalaman->jabatan,
            'pekerjaan' => $pengalaman->pekerjaan,
        ];
    }
}
