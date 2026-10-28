<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Kandidat;
use App\Models\RiwayatPendidikan;
use Illuminate\Http\Request;

class PendidikanController extends Controller
{
    public function index()
    {
        $pendidikan = RiwayatPendidikan::with(['karyawan', 'kandidat'])
            ->latest()
            ->get();
        $karyawan = Karyawan::orderBy('nama_lengkap')->get(['id', 'nama_lengkap']);
        $kandidat = Kandidat::orderBy('nama_lengkap')->get(['id', 'nama_lengkap']);

        return view('pendidikan.pendidikan', compact('pendidikan', 'karyawan', 'kandidat'));
    }

    public function store(Request $request, Karyawan $karyawan)
    {
        $pendidikan = $karyawan->pendidikan()->create($this->validatedData($request));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Riwayat pendidikan berhasil ditambahkan.',
                'data' => $this->educationResponse($pendidikan->load(['karyawan', 'kandidat'])),
            ], 201);
        }

        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function storePage(Request $request)
    {
        $owner = $request->validate([
            'pemilik' => ['required', 'string', 'regex:/^(karyawan|kandidat):\d+$/'],
        ])['pemilik'];
        [$type, $id] = explode(':', $owner, 2);

        $pemilik = $type === 'karyawan'
            ? Karyawan::findOrFail($id)
            : Kandidat::findOrFail($id);

        $pemilik->pendidikan()->create($this->validatedData($request));

        return redirect()->route('pendidikan.index')
            ->with('success', 'Riwayat pendidikan berhasil ditambahkan.');
    }

    public function update(Request $request, RiwayatPendidikan $pendidikan)
    {
        $pendidikan->update($this->validatedData($request));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Riwayat pendidikan berhasil diperbarui.',
                'data' => $this->educationResponse($pendidikan->fresh()->load(['karyawan', 'kandidat'])),
            ]);
        }

        return redirect()->route('pendidikan.index')
            ->with('success', 'Riwayat pendidikan berhasil diperbarui.');
    }

    public function destroy(Request $request, RiwayatPendidikan $pendidikan)
    {
        $id = $pendidikan->id;
        $pendidikan->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Riwayat pendidikan berhasil dihapus.',
                'deleted_id' => $id,
            ]);
        }

        return redirect()->route('pendidikan.index')
            ->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }

    private function validatedData(Request $request): array
    {
        $currentYear = (int) date('Y');

        return $request->validate([
            'jenjang' => ['required', 'string', 'max:20'],
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'jurusan' => ['nullable', 'string', 'max:255'],
            'gelar' => ['nullable', 'string', 'max:255'],
            'ipk_atau_nilai' => ['nullable', 'string', 'max:15'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'tahun_masuk' => ['required', 'integer', 'between:1900,' . $currentYear],
            'tahun_lulus' => [
                'nullable',
                'integer',
                'between:1900,' . ($currentYear + 10),
                'gte:tahun_masuk',
            ],
        ]);
    }

    private function educationResponse(RiwayatPendidikan $pendidikan): array
    {
        return [
            'id' => $pendidikan->id,
            'jenjang' => $pendidikan->jenjang,
            'nama_sekolah' => $pendidikan->nama_sekolah,
            'jurusan' => $pendidikan->jurusan,
            'gelar' => $pendidikan->gelar,
            'ipk_atau_nilai' => $pendidikan->ipk_atau_nilai,
            'lokasi' => $pendidikan->lokasi,
            'tahun_masuk' => $pendidikan->tahun_masuk,
            'tahun_lulus' => $pendidikan->tahun_lulus,
            'karyawan_id' => $pendidikan->karyawan_id,
            'kandidat_id' => $pendidikan->kandidat_id,
            'pemilik' => $pendidikan->karyawan?->nama_lengkap
                ?? $pendidikan->kandidat?->nama_lengkap
                ?? '-',
        ];
    }
}
