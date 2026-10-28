<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Karyawan;
use App\Models\Sertifikasi;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SertifikasiController extends Controller
{
    public function store(Request $request, Karyawan $karyawan)
    {
        return $this->storeForOwner($request, $karyawan->sertifikasi());
    }

    public function storeForKandidat(Request $request, Kandidat $kandidat)
    {
        return $this->storeForOwner($request, $kandidat->sertifikasi());
    }

    private function storeForOwner(Request $request, HasMany $sertifikasiRelation)
    {
        $data = $this->validatedData($request);
        $path = $request->file('file_sertifikat')->store('hrd/sertifikat', 'public');

        try {
            $sertifikasi = $sertifikasiRelation->create([
                ...$data,
                'file_sertifikat' => $path,
            ]);
        } catch (Throwable $exception) {
            Storage::disk('public')->delete($path);
            report($exception);
            throw $exception;
        }

        return response()->json([
            'message' => 'Sertifikasi berhasil ditambahkan.',
            'data' => $this->certificationResponse($sertifikasi),
        ], 201);
    }

    public function update(Request $request, Sertifikasi $sertifikasi)
    {
        $data = $this->validatedData($request, false);
        $previousFile = $sertifikasi->file_sertifikat;
        $newFile = null;

        if ($request->hasFile('file_sertifikat')) {
            $newFile = $request->file('file_sertifikat')->store('hrd/sertifikat', 'public');
            $data['file_sertifikat'] = $newFile;
        } else {
            unset($data['file_sertifikat']);
        }

        try {
            $sertifikasi->update($data);
        } catch (Throwable $exception) {
            if ($newFile) {
                Storage::disk('public')->delete($newFile);
            }
            report($exception);
            throw $exception;
        }

        if ($newFile) {
            Storage::disk('public')->delete($previousFile);
        }

        return response()->json([
            'message' => 'Sertifikasi berhasil diperbarui.',
            'data' => $this->certificationResponse($sertifikasi->fresh()),
        ]);
    }

    public function destroy(Sertifikasi $sertifikasi)
    {
        $id = $sertifikasi->id;
        $path = $sertifikasi->file_sertifikat;
        $sertifikasi->delete();
        Storage::disk('public')->delete($path);

        return response()->json([
            'message' => 'Sertifikasi berhasil dihapus.',
            'deleted_id' => $id,
        ]);
    }

    private function validatedData(Request $request, bool $fileRequired = true): array
    {
        $currentYear = (int) date('Y');

        return $request->validate([
            'nama_sertifikat' => ['required', 'string', 'max:255'],
            'bidang_sertifikasi' => ['required', 'string', 'max:255'],
            'badan_pengesahan' => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'integer', 'between:1900,'.($currentYear + 10)],
            'file_sertifikat' => [
                $fileRequired ? 'required' : 'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
        ]);
    }

    private function certificationResponse(Sertifikasi $sertifikasi): array
    {
        return [
            'id' => $sertifikasi->id,
            'nama_sertifikat' => $sertifikasi->nama_sertifikat,
            'bidang_sertifikasi' => $sertifikasi->bidang_sertifikasi,
            'badan_pengesahan' => $sertifikasi->badan_pengesahan,
            'tahun_lulus' => $sertifikasi->tahun_lulus,
            'file_sertifikat' => $sertifikasi->file_sertifikat,
            'file_url' => Storage::disk('public')->url($sertifikasi->file_sertifikat),
        ];
    }
}
