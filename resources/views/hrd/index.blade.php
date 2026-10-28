@extends('layout.main')

@section('content')

<style>
    .step-button {
        padding: 7px 12px; border-radius: 8px; white-space: nowrap;
        font-size: 13px; background: #f3f4f6; color: #4b5563;
        border: 1px solid transparent; transition: all .15s;
    }
    .step-button.active { background: #2563eb; color: #fff; }
</style>

<div class="pt-20 px-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">HRD - Rekrutmen Karyawan</h1>
            <p class="text-sm text-gray-500">Data calon karyawan</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="openAddModal()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus"></i> Tambah Calon Karyawan
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-medium">Data belum dapat disimpan:</p>
            <ul class="mt-1 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- ==================== Tabel ==================== -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="flex flex-wrap items-end gap-3 border-b border-gray-200 p-4">
            <div class="min-w-[220px] flex-1">
                <label for="hrdSearch" class="mb-1 block text-xs font-medium text-gray-600">Cari kandidat</label>
                <div class="relative">
                    <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input id="hrdSearch" type="search" placeholder="Nama, NIK, atau divisi"
                           class="w-full rounded-lg border border-gray-300 py-2 pl-9 pr-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>
            </div>
            <div class="w-full sm:w-44">
                <label for="hrdStatusFilter" class="mb-1 block text-xs font-medium text-gray-600">Status</label>
                <select id="hrdStatusFilter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua status</option>
                    <option value="pending">Pending</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-44">
                <label for="hrdDivisionFilter" class="mb-1 block text-xs font-medium text-gray-600">Divisi</label>
                <select id="hrdDivisionFilter" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua divisi</option>
                    <option value="Direktur">Direktur</option>
                    <option value="Keuangan">Keuangan</option>
                    <option value="Multimedia">Multimedia</option>
                    <option value="IT">IT</option>
                    <option value="Administrasi">Administrasi</option>
                </select>
            </div>
            <button id="hrdFilterReset" type="button" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                <i class="fas fa-rotate-left mr-1"></i>Reset
            </button>
            <p id="hrdFilterCount" class="w-full text-xs text-gray-500 sm:w-auto sm:pb-2" aria-live="polite"></p>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500" aria-sort="none">
                        <button type="button" data-sort-column="1" class="inline-flex items-center gap-2 hover:text-gray-800" aria-label="Urutkan berdasarkan nama">
                            Nama <i class="fas fa-sort text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500" aria-sort="none">
                        <button type="button" data-sort-column="2" class="inline-flex items-center gap-2 hover:text-gray-800" aria-label="Urutkan berdasarkan NIK">
                            NIK <i class="fas fa-sort text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500" aria-sort="none">
                        <button type="button" data-sort-column="3" class="inline-flex items-center gap-2 hover:text-gray-800" aria-label="Urutkan berdasarkan divisi">
                            Divisi <i class="fas fa-sort text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">KTP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">CV</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500" aria-sort="none">
                        <button type="button" data-sort-column="7" class="inline-flex items-center gap-2 hover:text-gray-800" aria-label="Urutkan berdasarkan status">
                            Status <i class="fas fa-sort text-gray-400" aria-hidden="true"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Keluarga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pendidikan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Pengalaman Kerja</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Sertifikasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($kandidat as $i => $k)
                <tr class="hover:bg-gray-50 hrd-kandidat-row"
                    data-search="{{ strtolower($k->nama_lengkap . ' ' . $k->nik . ' ' . $k->divisi) }}"
                    data-status="{{ strtolower($k->status) }}"
                    data-division="{{ strtolower($k->divisi) }}">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                    <td class="px-6 py-4">{{ $k->nama_lengkap }}</td>
                    <td class="px-6 py-4">{{ $k->nik }}</td>
                    <td class="px-6 py-4">{{ $k->divisi }}</td>

                    {{-- FOTO --}}
                    <td class="px-6 py-4">
                        @if($k->foto)
                            <img src="{{ asset('storage/' . $k->foto) }}"
                                 class="h-12 w-12 rounded-lg object-cover border border-gray-200" alt="Foto">
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>

                    {{-- KTP --}}
                    <td class="px-6 py-4">
                        @if($k->scan_ktp)
                            <a href="{{ asset('storage/' . $k->scan_ktp) }}" target="_blank"
                               class="text-blue-600 hover:underline text-sm">
                                <i class="fas fa-id-card mr-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>

                    {{-- CV --}}
                    <td class="px-6 py-4">
                        @if($k->cv)
                            <a href="{{ asset('storage/' . $k->cv) }}" target="_blank"
                               class="text-indigo-600 hover:underline text-sm">
                                <i class="fas fa-file-pdf mr-1"></i>Lihat
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $k->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($k->status == 'diterima' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($k->status) }}
                        </span>
                    </td>

                    {{-- KELUARGA --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($k->karyawan)
                            @php
                                $karyawanKeluargaJson = $k->karyawan->keluarga->map(function ($keluarga) {
                                    return [
                                        'id' => $keluarga->id,
                                        'nama' => $keluarga->nama,
                                        'tempat_lahir' => $keluarga->tempat_lahir,
                                        'tanggal_lahir' => $keluarga->tanggal_lahir ? \Carbon\Carbon::parse($keluarga->tanggal_lahir)->format('Y-m-d') : null,
                                        'status' => $keluarga->status,
                                        'province_id' => $keluarga->province_id,
                                        'regency_id' => $keluarga->regency_id,
                                        'district_id' => $keluarga->district_id,
                                        'village_id' => $keluarga->village_id,
                                        'address' => $keluarga->address,
                                        'province' => $keluarga->province?->name,
                                        'regency' => $keluarga->regency?->name,
                                        'district' => $keluarga->district?->name,
                                        'village' => $keluarga->village?->name,
                                    ];
                                })->values()->toJson();
                            @endphp
                            <button type="button" onclick="openHrdKeluargaModal(this)"
                                    data-karyawan-id="{{ $k->karyawan->id }}"
                                    data-karyawan-nama="{{ $k->nama_lengkap }}"
                                    data-keluarga="{{ $karyawanKeluargaJson }}"
                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-200"
                                    aria-label="Kelola keluarga {{ $k->nama_lengkap }}"
                                    title="Kelola keluarga {{ $k->nama_lengkap }}">
                                <i class="fas fa-users"></i>
                                <span>{{ $k->karyawan->keluarga->count() }}</span>
                            </button>
                        @else
                            <span class="text-xs text-gray-400">Belum menjadi karyawan</span>
                        @endif
                    </td>

                            {{-- PENDIDIKAN --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($k->karyawan)
                @php
                    $karyawanPendidikanJson = $k->karyawan->pendidikan->map(function ($pendidikan) {
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
                        ];
                    })->values()->toJson();
                @endphp
                <button type="button" onclick="openHrdPendidikanModal(this)"
                        data-karyawan-id="{{ $k->karyawan->id }}"
                        data-karyawan-nama="{{ $k->nama_lengkap }}"
                        data-pendidikan="{{ $karyawanPendidikanJson }}"
                        class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-200"
                        aria-label="Kelola pendidikan {{ $k->nama_lengkap }}"
                        title="Kelola pendidikan {{ $k->nama_lengkap }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ $k->karyawan->pendidikan->count() }}</span>
                </button>
            @else
                <span class="text-xs text-gray-400">Belum menjadi karyawan</span>
            @endif
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $pengalamanKerja = $k->karyawan?->pengalamanKerja ?? $k->pengalamanKerja;
                $pengalamanKerjaJson = $pengalamanKerja->map(function ($pengalaman) {
                    return [
                        'id' => $pengalaman->id,
                        'nama_perusahaan' => $pengalaman->nama_perusahaan,
                        'jenis_perusahaan' => $pengalaman->jenis_perusahaan,
                        'tahun_masuk' => $pengalaman->tahun_masuk,
                        'tahun_keluar' => $pengalaman->tahun_keluar,
                        'jabatan' => $pengalaman->jabatan,
                        'pekerjaan' => $pengalaman->pekerjaan,
                    ];
                })->values()->toJson();
            @endphp
            <button type="button" onclick="openHrdPengalamanModal(this)"
                    data-owner-type="{{ $k->karyawan ? 'karyawan' : 'kandidat' }}"
                    data-owner-id="{{ $k->karyawan?->id ?? $k->id }}"
                    data-karyawan-nama="{{ $k->nama_lengkap }}"
                    data-pengalaman="{{ $pengalamanKerjaJson }}"
                    class="inline-flex items-center gap-1 rounded-lg bg-blue-100 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-200"
                    aria-label="Kelola pengalaman kerja {{ $k->nama_lengkap }}"
                    title="Kelola pengalaman kerja {{ $k->nama_lengkap }}">
                <i class="fas fa-briefcase"></i>
                <span>{{ $pengalamanKerja->count() }}</span>
            </button>
        </td>

        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $sertifikasi = $k->karyawan?->sertifikasi ?? $k->sertifikasi;
                $sertifikasiJson = $sertifikasi->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama_sertifikat' => $item->nama_sertifikat,
                        'bidang_sertifikasi' => $item->bidang_sertifikasi,
                        'badan_pengesahan' => $item->badan_pengesahan,
                        'tahun_lulus' => $item->tahun_lulus,
                        'file_url' => asset('storage/' . $item->file_sertifikat),
                    ];
                })->values()->toJson();
            @endphp
            <button type="button" onclick="openHrdSertifikasiModal(this)"
                    data-owner-type="{{ $k->karyawan ? 'karyawan' : 'kandidat' }}"
                    data-owner-id="{{ $k->karyawan?->id ?? $k->id }}"
                    data-karyawan-nama="{{ $k->nama_lengkap }}"
                    data-sertifikasi="{{ $sertifikasiJson }}"
                    class="inline-flex items-center gap-1 rounded-lg bg-violet-100 px-3 py-1.5 text-xs font-medium text-violet-700 hover:bg-violet-200"
                    aria-label="Kelola sertifikasi {{ $k->nama_lengkap }}"
                    title="Kelola sertifikasi {{ $k->nama_lengkap }}">
                <i class="fas fa-certificate"></i>
                <span>{{ $sertifikasi->count() }}</span>
            </button>
        </td>

                    {{-- AKSI --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- Jadikan karyawan --}}
                        @if($k->status === 'diterima')
                            @if($k->karyawan)
                                <span class="text-xs text-green-600 mr-2" title="Sudah jadi karyawan">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @else
                                <button type="button" onclick="openKaryawanModal(this)"
                                        data-url="{{ route('hrd.jadikan-karyawan', $k->id) }}"
                                        data-nama="{{ $k->nama_lengkap }}"
                                        class="text-green-600 hover:text-green-800 mr-2"
                                        title="Jadikan karyawan">
                                    <i class="fas fa-user-check"></i>
                                </button>
                            @endif
                        @endif

                        {{-- Edit --}}
                        @php
                            $keluargaJson = $k->keluarga->map(function ($keluarga) {
                                return [
                                    'id' => $keluarga->id,
                                    'nama' => $keluarga->nama,
                                    'tempat_lahir' => $keluarga->tempat_lahir,
                                    'tanggal_lahir' => $keluarga->tanggal_lahir ? \Carbon\Carbon::parse($keluarga->tanggal_lahir)->format('Y-m-d') : null,
                                    'status' => $keluarga->status,
                                        'province_id' => $keluarga->province_id,
                                        'regency_id' => $keluarga->regency_id,
                                        'district_id' => $keluarga->district_id,
                                        'village_id' => $keluarga->village_id,
                                        'address' => $keluarga->address,
                                ];
                            })->values()->toJson();
                        @endphp
                        <button type="button"
                                onclick="openEditModal(this)"
                                data-update-url="{{ route('hrd.update', $k->id) }}"
                                data-nama_lengkap="{{ $k->nama_lengkap }}"
                                data-nik="{{ $k->nik }}"
                                data-no_telepon="{{ $k->no_telepon }}"
                                data-divisi="{{ $k->divisi }}"
                                data-tgl_lamar="{{ $k->tgl_lamar ? \Carbon\Carbon::parse($k->tgl_lamar)->format('Y-m-d') : '' }}"
                                data-npwp="{{ $k->npwp }}"
                                data-no_bpjs="{{ $k->no_bpjs }}"
                                data-status="{{ $k->status }}"
                                data-foto="{{ $k->foto ? asset('storage/'.$k->foto) : '' }}"
                                data-ktp="{{ $k->scan_ktp ? asset('storage/'.$k->scan_ktp) : '' }}"
                                data-cv="{{ $k->cv ? asset('storage/'.$k->cv) : '' }}"
                                data-pendidikan='@json($k->pendidikan ?? [])'
                                data-keluarga='{{ $keluargaJson }}'
                                class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Hapus --}}
                        <form action="{{ route('hrd.destroy', $k->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Yakin hapus?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="hrd-no-data-row">
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">Belum ada data</td>
                </tr>
                @endforelse
                <tr id="hrdFilterEmpty" class="hidden">
                    <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada kandidat yang cocok dengan filter.</td>
                </tr>
            </tbody>
        </table>

        <!-- ==================== Pagging dan view n record  ==================== -->
        <div class="flex flex-col gap-3 border-t border-gray-200 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                <label for="hrdPageSize" class="flex items-center gap-2">
                    Tampilkan
                    <select id="hrdPageSize" class="rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm text-gray-700 focus:border-blue-500 focus:outline-none">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    data per halaman
                </label>
                <span id="hrdPaginationInfo" aria-live="polite"></span>
            </div>
            <nav id="hrdPagination" class="flex items-center justify-end gap-1" aria-label="Navigasi halaman"></nav>
        </div>
    </div>
</div>

<!-- ==================== Modal CRUD Keluarga ==================== -->
<div id="hrdKeluargaModal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col shadow-2xl">
        <div class="shrink-0 px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-emerald-700">
            <div>
                <h2 class="font-semibold"><i class="fas fa-people-roof mr-2"></i>Data Keluarga</h2>
                <p id="hrdKeluargaPemilik" class="text-xs text-emerald-100 mt-1"></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openHrdKeluargaForm()" class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
                <button type="button" onclick="closeHrdKeluargaModal()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
            </div>
        </div>
        <div id="hrdKeluargaToast" class="hidden mx-5 mt-4 rounded-lg border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
        <div id="hrdKeluargaList" class="min-h-0 overflow-y-auto p-5 space-y-3"></div>
    </div>
</div>

<div id="hrdKeluargaFormModal" class="hidden fixed inset-0 z-[60] bg-black/60 flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="hrdKeluargaFormCard" class="bg-white rounded-xl w-full max-w-2xl shadow-2xl scale-95 opacity-0 transition-all duration-200">
        <div class="px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-emerald-700">
            <h2 id="hrdKeluargaFormTitle" class="font-semibold"><i class="fas fa-plus-circle mr-2"></i>Tambah Anggota Keluarga</h2>
            <button type="button" onclick="closeHrdKeluargaForm()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
        </div>
        <form id="hrdKeluargaForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="hrdKeluargaMethod" value="POST">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Nama *</label>
                    <input id="hrdKeluargaNama" name="nama" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat lahir *</label>
                    <input id="hrdKeluargaTempat" name="tempat_lahir" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal lahir *</label>
                    <input id="hrdKeluargaTanggal" name="tanggal_lahir" type="date" max="{{ now()->format('Y-m-d') }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Usia</label>
                    <input id="hrdKeluargaUsia" readonly placeholder="Otomatis" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hubungan *</label>
                    <select id="hrdKeluargaStatus" name="status" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Pilih hubungan</option>
                        <option>Ayah</option><option>Ibu</option><option>Suami</option><option>Istri</option><option>Anak</option><option>Orang Tua</option>
                    </select>
                </div>
            </div>
            <section class="space-y-3 border-t border-gray-200 pt-4">
                <h3 class="text-sm font-semibold text-gray-800"><i class="fas fa-map-marker-alt mr-1 text-red-500"></i>Alamat Tempat Lahir</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="hrdFamilyProvince" class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <select id="hrdFamilyProvince" name="province_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hrdFamilyRegency" class="block text-sm font-medium text-gray-700">Kabupaten/Kota</label>
                        <select id="hrdFamilyRegency" name="regency_id" disabled class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:bg-gray-100">
                            <option value="">-- Pilih Kabupaten/Kota --</option>
                        </select>
                    </div>
                    <div>
                        <label for="hrdFamilyDistrict" class="block text-sm font-medium text-gray-700">Kecamatan</label>
                        <select id="hrdFamilyDistrict" name="district_id" disabled class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:bg-gray-100">
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                    <div>
                        <label for="hrdFamilyVillage" class="block text-sm font-medium text-gray-700">Kelurahan/Desa</label>
                        <select id="hrdFamilyVillage" name="village_id" disabled class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm disabled:bg-gray-100">
                            <option value="">-- Pilih Kelurahan/Desa --</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="hrdFamilyAddress" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea id="hrdFamilyAddress" name="address" rows="2" placeholder="Masukkan alamat lengkap..." class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm"></textarea>
                    </div>
                </div>
            </section>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeHrdKeluargaForm()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="hrdKeluargaDeleteModal" class="hidden fixed inset-0 z-[70] bg-black/60 flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="hrdKeluargaDeleteCard" class="w-full max-w-sm rounded-xl bg-white p-5 shadow-2xl scale-95 opacity-0 transition-all duration-200">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
            <i class="fas fa-trash text-lg"></i>
        </div>
        <h2 class="mt-3 text-center text-lg font-semibold text-gray-800">Hapus data keluarga?</h2>
        <p class="mt-1 text-center text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan.</p>
        <div class="mt-5 flex justify-end gap-2">
            <button type="button" onclick="closeHrdKeluargaDelete()" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Batal</button>
            <button type="button" onclick="confirmHrdKeluargaDelete()" class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700"><i class="fas fa-trash mr-1"></i>Hapus</button>
        </div>
    </div>
</div>

<!-- ==================== Modal CRUD Pendidikan ==================== -->
<div id="hrdPendidikanModal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col shadow-2xl">
        <div class="shrink-0 px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-emerald-700">
            <div>
                <h2 class="font-semibold"><i class="fas fa-graduation-cap mr-2"></i>Data Pendidikan</h2>
                <p id="hrdPendidikanPemilik" class="text-xs text-emerald-100 mt-1"></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openHrdPendidikanForm()" class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
                <button type="button" onclick="closeHrdPendidikanModal()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
            </div>
        </div>
        <div id="hrdPendidikanToast" class="hidden mx-5 mt-4 rounded-lg border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
        <div id="hrdPendidikanList" class="min-h-0 overflow-y-auto p-5 space-y-3"></div>
    </div>
</div>

<!-- Form Modal Pendidikan -->
<div id="hrdPendidikanFormModal" class="hidden fixed inset-0 z-[60] bg-black/60 flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="hrdPendidikanFormCard" class="bg-white rounded-xl w-full max-w-2xl shadow-2xl scale-95 opacity-0 transition-all duration-200">
        <div class="px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-emerald-700">
            <h2 id="hrdPendidikanFormTitle" class="font-semibold"><i class="fas fa-plus-circle mr-2"></i>Tambah Riwayat Pendidikan</h2>
            <button type="button" onclick="closeHrdPendidikanForm()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
        </div>
        <form id="hrdPendidikanForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="hrdPendidikanMethod" value="POST">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Pendidikan *</label>
                    <select id="hrdPendidikanJenjang" name="jenjang" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">Pilih Jenjang</option>
                        <option value="TK/PAUD/TPA">TK/PAUD/TPA</option>
                        <option value="SD/MI">SD/MI</option>
                        <option value="SMP/MTS">SMP/MTS</option>
                        <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                        <option value="D3">D3</option>
                        <option value="D3">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Pendidikan *</label>
                    <input id="hrdPendidikanNamaSekolah" name="nama_sekolah" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Universitas Indonesia">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                    <input id="hrdPendidikanJurusan" name="jurusan" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Teknik Informatika">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gelar</label>
                    <input id="hrdPendidikanGelar" name="gelar" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: S.Kom">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Masuk</label>
                    <input id="hrdPendidikanTahunMasuk" name="tahun_masuk" type="number" min="1900" max="{{ date('Y') }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="2018">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Keluar</label>
                    <input id="hrdPendidikanTahunLulus" name="tahun_lulus" type="number" min="1900" max="{{ date('Y') + 10 }}" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Kosongkan jika masih berjalan">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Pendidikan</label>
                    <input id="hrdPendidikanLokasi" name="lokasi" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Alamat sekolah atau institusi">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">IPK / Nilai</label>
                    <input id="hrdPendidikanIpk" name="ipk_atau_nilai" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: 3.75">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" onclick="closeHrdPendidikanForm()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Hapus Pendidikan -->
<div id="hrdPendidikanDeleteModal" class="hidden fixed inset-0 z-[70] bg-black/60 flex items-center justify-center p-4 opacity-0 transition-opacity duration-200">
    <div id="hrdPendidikanDeleteCard" class="w-full max-w-sm rounded-xl bg-white p-5 shadow-2xl scale-95 opacity-0 transition-all duration-200">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
            <i class="fas fa-trash text-lg"></i>
        </div>
        <h2 class="mt-3 text-center text-lg font-semibold text-gray-800">Hapus data pendidikan?</h2>
        <p class="mt-1 text-center text-sm text-gray-500">Data yang dihapus tidak dapat dikembalikan.</p>
        <div class="mt-5 flex justify-end gap-2">
            <button type="button" onclick="closeHrdPendidikanDelete()" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Batal</button>
            <button type="button" onclick="confirmHrdPendidikanDelete()" class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white hover:bg-red-700"><i class="fas fa-trash mr-1"></i>Hapus</button>
        </div>
    </div>
</div>

<div id="hrdPengalamanModal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col shadow-2xl">
        <div class="shrink-0 px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-blue-700">
            <div>
                <h2 class="font-semibold"><i class="fas fa-briefcase mr-2"></i>Pengalaman Kerja</h2>
                <p id="hrdPengalamanPemilik" class="text-xs text-blue-100 mt-1"></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openHrdPengalamanForm()" class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
                <button type="button" onclick="closeHrdPengalamanModal()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
            </div>
        </div>
        <div id="hrdPengalamanToast" class="hidden mx-5 mt-4 rounded-lg border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
        <div id="hrdPengalamanList" class="min-h-0 overflow-y-auto p-5 space-y-3"></div>
    </div>
</div>

<div id="hrdPengalamanFormModal" class="hidden fixed inset-0 z-[60] bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl shadow-2xl">
        <div class="px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-blue-700">
            <h2 id="hrdPengalamanFormTitle" class="font-semibold">Tambah Pengalaman Kerja</h2>
            <button type="button" onclick="closeHrdPengalamanForm()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
        </div>
        <form id="hrdPengalamanForm" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="hrdNamaPerusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan *</label>
                    <input id="hrdNamaPerusahaan" name="nama_perusahaan" required maxlength="255" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="hrdJenisPerusahaan" class="block text-sm font-medium text-gray-700">Jenis Perusahaan *</label>
                    <input id="hrdJenisPerusahaan" name="jenis_perusahaan" required maxlength="255" placeholder="Contoh: manufaktur, teknologi" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="hrdTahunMasukKerja" class="block text-sm font-medium text-gray-700">Tahun Masuk *</label>
                    <input id="hrdTahunMasukKerja" name="tahun_masuk" type="number" min="1900" max="{{ date('Y') }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="hrdTahunKeluarKerja" class="block text-sm font-medium text-gray-700">Tahun Keluar</label>
                    <input id="hrdTahunKeluarKerja" name="tahun_keluar" type="number" min="1900" max="{{ date('Y') + 10 }}" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Kosongkan jika masih bekerja">
                </div>
                <div class="col-span-2">
                    <label for="hrdJabatan" class="block text-sm font-medium text-gray-700">Jabatan *</label>
                    <input id="hrdJabatan" name="jabatan" required maxlength="255" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="col-span-2">
                    <label for="hrdPekerjaan" class="block text-sm font-medium text-gray-700">Deskripsi Pekerjaan *</label>
                    <textarea id="hrdPekerjaan" name="pekerjaan" required maxlength="5000" rows="3" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-2 border-t pt-3">
                <button type="button" onclick="closeHrdPengalamanForm()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="hrdSertifikasiModal" class="hidden fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col shadow-2xl">
        <div class="shrink-0 px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-violet-700">
            <div>
                <h2 class="font-semibold"><i class="fas fa-certificate mr-2"></i>Sertifikasi</h2>
                <p id="hrdSertifikasiPemilik" class="text-xs text-violet-100 mt-1"></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="openHrdSertifikasiForm()" class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-violet-700 hover:bg-violet-50">
                    <i class="fas fa-plus mr-1"></i>Tambah
                </button>
                <button type="button" onclick="closeHrdSertifikasiModal()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
            </div>
        </div>
        <div id="hrdSertifikasiToast" class="hidden mx-5 mt-4 rounded-lg border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
        <div id="hrdSertifikasiList" class="min-h-0 overflow-y-auto p-5 space-y-3"></div>
    </div>
</div>

<div id="hrdSertifikasiFormModal" class="hidden fixed inset-0 z-[60] bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-2xl shadow-2xl">
        <div class="px-5 py-4 rounded-t-xl text-white flex justify-between items-center bg-violet-700">
            <h2 id="hrdSertifikasiFormTitle" class="font-semibold">Tambah Sertifikasi</h2>
            <button type="button" onclick="closeHrdSertifikasiForm()" class="w-8 h-8 rounded-full hover:bg-white/20 text-xl leading-none">&times;</button>
        </div>
        <form id="hrdSertifikasiForm" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <div>
                <label for="hrdNamaSertifikat" class="block text-sm font-medium text-gray-700">Nama Sertifikat *</label>
                <input id="hrdNamaSertifikat" name="nama_sertifikat" required maxlength="255" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="hrdBidangSertifikasi" class="block text-sm font-medium text-gray-700">Bidang Sertifikasi *</label>
                    <input id="hrdBidangSertifikasi" name="bidang_sertifikasi" required maxlength="255" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="hrdBadanPengesahan" class="block text-sm font-medium text-gray-700">Badan Pengesahan Sertifikat *</label>
                    <input id="hrdBadanPengesahan" name="badan_pengesahan" required maxlength="255" class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Contoh: BNSP">
                </div>
                <div>
                    <label for="hrdTahunSertifikasi" class="block text-sm font-medium text-gray-700">Tahun Sertifikat *</label>
                    <input id="hrdTahunSertifikasi" name="tahun_sertifikasi" type="number" min="1900" max="{{ date('Y') + 10 }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label for="hrdFileSertifikat" class="block text-sm font-medium text-gray-700">Upload Sertifikat <span class="text-xs font-normal text-gray-500">(PDF/JPG/PNG, maks. 5 MB)</span></label>
                <input id="hrdFileSertifikat" name="file_sertifikat" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="mt-1 block w-full text-sm text-gray-600">
                <a id="hrdFileSertifikatSaatIni" class="hidden mt-2 inline-block text-sm text-blue-600 hover:underline" target="_blank" rel="noopener">Lihat sertifikat saat ini</a>
            </div>
            <div class="flex justify-end gap-2 border-t pt-3">
                <button type="button" onclick="closeHrdSertifikasiForm()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-violet-600 text-white text-sm hover:bg-violet-700"><i class="fas fa-save mr-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== Modal Tambah ==================== -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col min-h-0">

        {{-- Header --}}
        <div class="shrink-0 text-white px-5 py-3 rounded-t-xl flex justify-between items-center"
             style="background: linear-gradient(to right, #7c3aed, #3b82f6);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-sm"></i>
                </div>
                <h2 class="font-semibold text-sm">Tambah Calon Karyawan</h2>
            </div>
            <button type="button" onclick="closeModal('modal')"
                    class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors text-xl leading-none text-white">
                &times;
            </button>
        </div>

        <form id="addForm" action="{{ route('hrd.store') }}" method="POST" enctype="multipart/form-data"
              class="min-h-0 flex-1 flex flex-col" data-step="1">
            @csrf

            {{-- Menu slide --}}
            <div class="shrink-0 flex gap-2 border-b px-4 py-3 overflow-x-auto">
                <button type="button" class="step-button active" data-step-button="1"
                        onclick="wizardGo('addForm', 1)">1. Data Diri</button>
                <button type="button" class="step-button" data-step-button="2"
                        onclick="wizardGo('addForm', 2)">2. Pendidikan</button>
                <button type="button" class="step-button" data-step-button="3"
                        onclick="wizardGo('addForm', 3)">3. Dokumen</button>
            </div>
            {{-- Area isi --}}
            <div class="min-h-0 flex-1 overflow-y-auto p-4">

                {{-- ============ STEP 1 ============ --}}
                <section data-wizard-step="1" class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-600 mr-1"></i> Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nama lengkap *</label>
                                <input type="text" name="nama_lengkap" placeholder="isi dengan huruf kapital" required
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK *</label>
                                <input type="text" name="nik" required maxlength="16" placeholder="16 digit"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. telepon *</label>
                                <input type="text" name="no_telepon" required placeholder="08xxxxxxxxxx"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase text-blue-600 mr-1"></i> Posisi yang Dilamar
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Divisi *</label>
                                <select name="divisi" required
                                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Pilih Divisi</option>
                                    <option>Direktur</option>
                                    <option>Keuangan</option>
                                    <option>Multimedia</option>
                                    <option>IT</option>
                                    <option>Administrasi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tgl. lamar *</label>
                                <input type="date" name="tgl_lamar" required
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ============ STEP 2 ============ --}}
                <section data-wizard-step="2" class="hidden space-y-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap text-blue-600 mr-1"></i> Riwayat Pendidikan
                        <span class="text-xs text-gray-400 font-normal">(boleh dikosongkan)</span>
                    </h3>

                    <div id="pendidikanRowsAdd" class="space-y-2" data-index="0"></div>

                    <button type="button" onclick="tambahPendidikan('pendidikanRowsAdd')"
                            class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-1"></i> Tambah Pendidikan
                    </button>
                </section>

                {{-- ============ STEP 3 ============ --}}
                <section data-wizard-step="3" class="hidden space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt text-blue-600 mr-1"></i> Dokumen
                        </h3>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto <span class="text-xs font-normal text-gray-400">(JPG, PNG, maksimal 2 MB)</span></label>
                                <input type="file" name="foto" accept="image/*"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scan KTP</label>
                                <input type="file" name="scan_ktp" accept="image/*,.pdf"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CV</label>
                                <input type="file" name="cv" accept=".pdf,.doc,.docx"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cog text-blue-600 mr-1"></i> Opsional
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NPWP</label>
                                <input type="text" name="npwp"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. BPJS</label>
                                <input type="text" name="no_bpjs"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            {{-- Navigasi --}}
            <div class="shrink-0 flex justify-between items-center gap-2 border-t px-4 py-3">
                <button type="button" data-nav-prev class="invisible px-4 py-2 rounded-lg bg-gray-100 text-gray-600 text-sm"
                        onclick="wizardGo('addForm', Number(document.getElementById('addForm').dataset.step) - 1)">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </button>

                <div class="flex gap-2">
                    <button type="button" data-nav-next
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700"
                            onclick="wizardNext('addForm')">
                        Berikutnya <i class="fas fa-arrow-right ml-1"></i>
                    </button>

                    <button type="submit" data-nav-submit
                            class="hidden px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i> Simpan calon karyawan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================== Modal Edit ==================== -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[94vh] flex flex-col min-h-0">

        <div class="shrink-0 text-white px-5 py-3 rounded-t-xl flex justify-between items-center"
             style="background: linear-gradient(to right, #7c3aed, #3b82f6);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-edit text-white text-sm"></i>
                </div>
                <h2 class="font-semibold text-sm">Edit Calon Karyawan</h2>
            </div>
            <button type="button" onclick="closeModal('editModal')"
                    class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors text-xl leading-none text-white">
                &times;
            </button>
        </div>

        <form id="editForm" action="" method="POST" enctype="multipart/form-data"
              class="min-h-0 flex-1 flex flex-col" data-step="1">
            @csrf
            @method('PUT')

            <div class="shrink-0 flex gap-2 border-b px-4 py-3 overflow-x-auto">
                <button type="button" class="step-button active" data-step-button="1"
                        onclick="wizardGo('editForm', 1)">1. Data Diri</button>
                <button type="button" class="step-button" data-step-button="2"
                        onclick="wizardGo('editForm', 2)">2. Pendidikan</button>
                <button type="button" class="step-button" data-step-button="3"
                        onclick="wizardGo('editForm', 3)">3. Dokumen</button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto p-4">

                {{-- STEP 1 --}}
                <section data-wizard-step="1" class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-600 mr-1"></i> Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Nama lengkap *</label>
                                <input type="text" name="nama_lengkap" required
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK *</label>
                                <input type="text" name="nik" required maxlength="16"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. telepon *</label>
                                <input type="text" name="no_telepon" required
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase text-blue-600 mr-1"></i> Posisi yang Dilamar
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Divisi *</label>
                                <select name="divisi" required
                                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="">Pilih Divisi</option>
                                    <option>Direktur</option>
                                    <option>Keuangan</option>
                                    <option>Multimedia</option>
                                    <option>IT</option>
                                    <option>Administrasi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tgl. lamar *</label>
                                <input type="date" name="tgl_lamar" required
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- STEP 2 --}}
                <section data-wizard-step="2" class="hidden space-y-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap text-blue-600 mr-1"></i> Riwayat Pendidikan
                    </h3>

                    <div id="pendidikanRowsEdit" class="space-y-2" data-index="0"></div>

                    <button type="button" onclick="tambahPendidikan('pendidikanRowsEdit')"
                            class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-1"></i> Tambah Pendidikan
                    </button>
                </section>

                {{-- STEP 3 --}}
                <section data-wizard-step="3" class="hidden space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-alt text-blue-600 mr-1"></i> Dokumen
                            <span class="text-xs text-gray-400 font-normal">(kosongkan kalau tidak diganti)</span>
                        </h3>
                        <div class="space-y-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto <span class="text-xs font-normal text-gray-400">(kosongkan kalau tidak diganti, maksimal 2 MB)</span></label>
                                <div id="editFotoWrap" class="hidden mb-1">
                                    <img id="editFotoImg" class="h-14 w-14 rounded-lg object-cover border">
                                </div>
                                <input type="file" name="foto" accept="image/*"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Scan KTP</label>
                                <a id="editKtpLink" href="#" target="_blank"
                                   class="hidden text-blue-600 text-xs hover:underline">Lihat KTP saat ini</a>
                                <input type="file" name="scan_ktp" accept="image/*,.pdf"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CV</label>
                                <a id="editCvLink" href="#" target="_blank"
                                   class="hidden text-indigo-600 text-xs hover:underline">Lihat CV saat ini</a>
                                <input type="file" name="cv" accept=".pdf,.doc,.docx"
                                       class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-cog text-blue-600 mr-1"></i> Pengaturan Lainnya
                        </h3>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NPWP</label>
                                <input type="text" name="npwp"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. BPJS</label>
                                <input type="text" name="no_bpjs"
                                       class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status"
                                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option value="pending">Pending</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

            </div>

            <div class="shrink-0 flex justify-between items-center gap-2 border-t px-4 py-3">
                <button type="button" data-nav-prev class="invisible px-4 py-2 rounded-lg bg-gray-100 text-gray-600 text-sm"
                        onclick="wizardGo('editForm', Number(document.getElementById('editForm').dataset.step) - 1)">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </button>

                <div class="flex gap-2">
                    <button type="button" data-nav-next
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700"
                            onclick="wizardNext('editForm')">
                        Berikutnya <i class="fas fa-arrow-right ml-1"></i>
                    </button>

                    <button type="submit" data-nav-submit
                            class="hidden px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================== Modal Jadikan Karyawan ==================== -->
<div id="karyawanModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md">

        <div class="px-5 py-4 rounded-t-xl text-white flex justify-between items-center"
             style="background: linear-gradient(to right, #16a34a, #2563eb);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-sm"></i>
                </div>
                <h2 class="font-semibold text-sm">Jadikan Karyawan</h2>
            </div>
            <button type="button" onclick="closeKaryawanModal()"
                    class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-white/20 text-xl leading-none">
                &times;
            </button>
        </div>

        <form id="karyawanForm" action="" method="POST" class="p-5 space-y-4">
            @csrf

            <p class="text-sm text-gray-600">
                Kandidat: <strong id="namaKandidat"></strong>
            </p>

            <div>
                <label class="block text-sm font-medium text-gray-700">ID Departemen</label>
                <input type="number" name="departemen_id" placeholder="Kosongkan kalau belum ada"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ now()->format('Y-m-d') }}"
                       class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeKaryawanModal()"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">
                    Jadikan Karyawan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ==================== Script ==================== --}}
<script src="{{ asset('js/address-cascade.js') }}"></script>
    <script>
/* ============ Riwayat Pendidikan ============ */
const INP = 'w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm';
const JENJANG = ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'];

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
    })[char]);
}

function tambahPendidikan(containerId, values) {
    const wrap = document.getElementById(containerId);
    if (!wrap) return;

    const i = parseInt(wrap.dataset.index || '0', 10);
    wrap.dataset.index = i + 1;

    const v = values || {};

    const row = document.createElement('div');
    row.className = 'pendidikan-row border border-gray-200 rounded-lg p-3 bg-gray-50 space-y-2';
    row.innerHTML = `
        <div class="grid grid-cols-2 gap-2">
            <select name="pendidikan[${i}][jenjang]" class="${INP}">
                <option value="">Jenis Pendidikan</option>
                ${JENJANG.map(j => `<option value="${j}">${j}</option>`).join('')}
            </select>
            <input type="text" name="pendidikan[${i}][nama_sekolah]" placeholder="Nama pendidikan" class="${INP}">
        </div>
        <div class="grid grid-cols-3 gap-2">
            <input type="text" name="pendidikan[${i}][jurusan]" placeholder="Jurusan" class="${INP}">
            <input type="text" name="pendidikan[${i}][gelar]" placeholder="Gelar (S.Kom)" class="${INP}">
            <input type="text" name="pendidikan[${i}][lokasi]" placeholder="Alamat pendidikan" class="${INP}">
        </div>
        <div class="grid grid-cols-3 gap-2">
            <input type="number" name="pendidikan[${i}][tahun_masuk]" placeholder="Masuk" class="${INP}">
            <input type="number" name="pendidikan[${i}][tahun_lulus]" placeholder="Tahun keluar" class="${INP}">
            <input type="text" name="pendidikan[${i}][ipk_atau_nilai]" placeholder="IPK / Nilai" class="${INP}">
        </div>
        <button type="button" onclick="this.closest('.pendidikan-row').remove()"
                class="text-xs text-red-500 hover:text-red-700">
            <i class="fas fa-trash mr-1"></i> Hapus baris
        </button>`;
    wrap.appendChild(row);

    const set = (field, val) => {
        const el = row.querySelector(`[name="pendidikan[${i}][${field}]"]`);
        if (el) el.value = (val === null || val === undefined) ? '' : val;
    };
    set('jenjang', v.jenjang);
    set('nama_sekolah', v.nama_sekolah);
    set('jurusan', v.jurusan);
    set('gelar', v.gelar);
    set('lokasi', v.lokasi);
    set('tahun_masuk', v.tahun_masuk);
    set('tahun_lulus', v.tahun_lulus);
    set('ipk_atau_nilai', v.ipk_atau_nilai);
}

function kosongkanPendidikan(containerId, withBlankRow = true) {
    const wrap = document.getElementById(containerId);
    if (!wrap) return;
    wrap.innerHTML = '';
    wrap.dataset.index = 0;
    if (withBlankRow) tambahPendidikan(containerId);
}

function hitungUsia(tanggalLahir) {
    const tanggal = normalisasiTanggal(tanggalLahir);
    if (!tanggal) return '';
    const hariIni = new Date();
    const lahir = new Date(`${tanggal}T00:00:00`);
    let usia = hariIni.getFullYear() - lahir.getFullYear();
    if (hariIni.getMonth() < lahir.getMonth() ||
        (hariIni.getMonth() === lahir.getMonth() && hariIni.getDate() < lahir.getDate())) usia--;
    return usia >= 0 ? `${usia} tahun` : '';
}

function normalisasiTanggal(value) {
    const tanggal = String(value || '').trim();
    if (/^\d{4}-\d{2}-\d{2}$/.test(tanggal)) return tanggal;

    const cocok = tanggal.match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/);
    if (!cocok) return '';

    const [, hari, bulan, tahun] = cocok;
    const hasil = `${tahun}-${bulan.padStart(2, '0')}-${hari.padStart(2, '0')}`;
    const date = new Date(`${hasil}T00:00:00`);
    return date.getFullYear() === Number(tahun) &&
        date.getMonth() + 1 === Number(bulan) && date.getDate() === Number(hari)
        ? hasil : '';
}

function tampilanTanggal(value) {
    const tanggal = normalisasiTanggal(value);
    if (!tanggal) return value || '';
    const [tahun, bulan, hari] = tanggal.split('-');
    return `${hari}/${bulan}/${tahun}`;
}

function normalisasiTanggalKeluarga(form) {
    form.querySelectorAll('.keluarga-tanggal-lahir').forEach(input => {
        const tanggal = normalisasiTanggal(input.value);
        input.setCustomValidity(tanggal ? '' : 'Gunakan format tanggal DD/MM/YYYY yang valid.');
        if (tanggal) input.value = tanggal;
    });
}

function tambahKeluarga(containerId, values) {
    const wrap = document.getElementById(containerId);
    if (!wrap) return;

    const i = parseInt(wrap.dataset.index || '0', 10);
    wrap.dataset.index = i + 1;
    const v = values || {};
    const row = document.createElement('div');
    row.className = 'keluarga-row border border-emerald-200 rounded-lg p-3 bg-emerald-50 space-y-2';
    row.innerHTML = `
        ${v.id ? `<input type="hidden" name="keluarga[${i}][id]" value="${escapeHtml(v.id)}">` : ''}
        <div class="grid grid-cols-2 gap-2">
            <input type="text" name="keluarga[${i}][nama]" value="${escapeHtml(v.nama)}" placeholder="Nama lengkap" required class="${INP}">
            <select name="keluarga[${i}][status]" required class="${INP}">
                <option value="">Status hubungan</option>
                <option value ="Ayah" ${v.status === 'Ayah' ? 'selected' : ''}>Ayah</option>
                <option value="Ibu" ${v.status === 'Ibu' ? 'selected' : ''}>Ibu</option>
                <option value="Suami" ${v.status === 'Suami' ? 'selected' : ''}>Suami</option>
                <option value="Istri" ${v.status === 'Istri' ? 'selected' : ''}>Istri</option>
                <option value="Anak" ${v.status === 'Anak' ? 'selected' : ''}>Anak</option>
            </select>
        </div>
        <div class="grid grid-cols-3 gap-2">
            <input type="text" name="keluarga[${i}][tempat_lahir]" value="${escapeHtml(v.tempat_lahir)}" placeholder="Tempat lahir" required class="${INP}">
            <input type="text" name="keluarga[${i}][tanggal_lahir]" value="${tampilanTanggal(v.tanggal_lahir)}" placeholder="DD/MM/YYYY" inputmode="numeric" maxlength="10" required class="${INP} keluarga-tanggal-lahir">
            <input type="text" value="${hitungUsia(v.tanggal_lahir)}" placeholder="Usia otomatis" readonly class="${INP} bg-gray-100 keluarga-usia">
        </div>
        <button type="button" onclick="this.closest('.keluarga-row').remove()" class="text-xs text-red-500 hover:text-red-700">
            <i class="fas fa-trash mr-1"></i> Hapus anggota
        </button>`;
    wrap.appendChild(row);

    row.querySelector('.keluarga-tanggal-lahir').addEventListener('input', function () {
        this.setCustomValidity(normalisasiTanggal(this.value) || !this.value ? '' : 'Gunakan format tanggal DD/MM/YYYY yang valid.');
        row.querySelector('.keluarga-usia').value = hitungUsia(this.value);
    });
}

function kosongkanKeluarga(containerId) {
    const wrap = document.getElementById(containerId);
    if (!wrap) return;
    wrap.innerHTML = '';
    wrap.dataset.index = 0;
}

/* ============ Wizard ============ */
function wizardGo(formId, step) {
    const form = document.getElementById(formId);
    if (!form) return;

    const sections = form.querySelectorAll('[data-wizard-step]');
    const total = sections.length;
    step = Math.min(Math.max(Number(step) || 1, 1), total);

    sections.forEach(s => s.classList.toggle('hidden', Number(s.dataset.wizardStep) !== step));
    form.querySelectorAll('[data-step-button]').forEach(b =>
        b.classList.toggle('active', Number(b.dataset.stepButton) === step));

    form.querySelectorAll('[data-nav-prev]').forEach(b => b.classList.toggle('invisible', step === 1));
    form.querySelectorAll('[data-nav-next]').forEach(b => b.classList.toggle('hidden', step === total));
    form.querySelectorAll('[data-nav-submit]').forEach(b => b.classList.toggle('hidden', step !== total));

    form.dataset.step = step;

    const body = form.querySelector('.overflow-y-auto');
    if (body) body.scrollTop = 0;
}

function wizardNext(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    const step = Number(form.dataset.step || 1);
    const section = form.querySelector(`[data-wizard-step="${step}"]`);
    if (!section) return;

    // validasi field di slide yang sedang tampil aja
    const invalid = Array.from(section.querySelectorAll('input, select, textarea'))
        .find(el => !el.checkValidity() && el.type !== 'hidden');

    if (invalid) { invalid.reportValidity(); return; }

    wizardGo(formId, step + 1);
}

/* ============ Modal ============ */
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

function openAddModal() {
    const form = document.getElementById('addForm');
    form.reset();
    kosongkanPendidikan('pendidikanRowsAdd', false);
    wizardGo('addForm', 1);
    document.getElementById('modal').classList.remove('hidden');
}

function openEditModal(btn) {
    const d = btn.dataset;
    const form = document.getElementById('editForm');
    if (!form) { console.error('#editForm nggak ketemu'); return; }

    form.action = d.updateUrl;

    const setVal = (name, val) => {
        const el = form.querySelector(`[name="${name}"]`);
        if (el) el.value = (val === undefined || val === null) ? '' : val;
    };

    setVal('nama_lengkap', d.nama_lengkap);
    setVal('nik', d.nik);
    setVal('no_telepon', d.no_telepon);
    setVal('divisi', d.divisi);
    setVal('tgl_lamar', d.tgl_lamar);
    setVal('npwp', d.npwp);
    setVal('no_bpjs', d.no_bpjs);
    setVal('status', d.status);

    // Preview file lama
    const wrap = document.getElementById('editFotoWrap');
    const img  = document.getElementById('editFotoImg');
    if (d.foto) { img.src = d.foto; wrap.classList.remove('hidden'); }
    else { img.src = ''; wrap.classList.add('hidden'); }

    const ktp = document.getElementById('editKtpLink');
    if (d.ktp) { ktp.href = d.ktp; ktp.classList.remove('hidden'); } else { ktp.classList.add('hidden'); }

    const cv = document.getElementById('editCvLink');
    if (d.cv) { cv.href = d.cv; cv.classList.remove('hidden'); } else { cv.classList.add('hidden'); }

    // Riwayat pendidikan
    let list = [];
    try { list = JSON.parse(d.pendidikan || '[]'); } catch (e) { list = []; }

    if (list.length === 0) {
        kosongkanPendidikan('pendidikanRowsEdit', false);
    } else {
        kosongkanPendidikan('pendidikanRowsEdit', false);
        list.forEach(p => tambahPendidikan('pendidikanRowsEdit', p));
    }

    wizardGo('editForm', 1);
    document.getElementById('editModal').classList.remove('hidden');
}

/* ============ Modal Jadikan Karyawan ============ */
function openKaryawanModal(btn) {
    document.getElementById('karyawanForm').action = btn.dataset.url;
    document.getElementById('namaKandidat').textContent = btn.dataset.nama;
    document.getElementById('karyawanModal').classList.remove('hidden');
}

function closeKaryawanModal() {
    document.getElementById('karyawanModal').classList.add('hidden');
}

let hrdSortState = { column: null, direction: 'asc' };
const hrdSearchInput = document.getElementById('hrdSearch');
const hrdStatusFilter = document.getElementById('hrdStatusFilter');
const hrdDivisionFilter = document.getElementById('hrdDivisionFilter');
const hrdFilterEmpty = document.getElementById('hrdFilterEmpty');
let hrdCandidateRows = Array.from(document.querySelectorAll('tr.hrd-kandidat-row'));
const hrdNoDataRow = document.querySelector('.hrd-no-data-row');
const hrdPageSizeSelect = document.getElementById('hrdPageSize');
const hrdPaginationInfo = document.getElementById('hrdPaginationInfo');
const hrdPagination = document.getElementById('hrdPagination');
let hrdCurrentPage = 1;
let hrdPageSize = Number(hrdPageSizeSelect.value) || 10;

function getFilteredHrdRows() {
    const query = hrdSearchInput?.value.trim().toLocaleLowerCase('id') || '';
    const selectedStatus = hrdStatusFilter?.value || '';
    const selectedDivision = hrdDivisionFilter?.value.toLocaleLowerCase('id') || '';

    return hrdCandidateRows.filter(row => {
        const matchesSearch = (row.dataset.search || '').toLocaleLowerCase('id').includes(query);
        const matchesStatus = !selectedStatus || row.dataset.status === selectedStatus;
        const matchesDivision = !selectedDivision || row.dataset.division === selectedDivision;
        return matchesSearch && matchesStatus && matchesDivision;
    });
}

function makeHrdPageButton(label, page, options = {}) {
    const button = document.createElement('button');
    button.type = 'button';
    button.innerHTML = label;
    button.disabled = Boolean(options.disabled);
    button.setAttribute('aria-label', options.ariaLabel || `Halaman ${page}`);

    const baseClasses = 'min-w-9 rounded-md border px-3 py-2 text-sm transition-colors';
    button.className = options.active
        ? `${baseClasses} border-indigo-600 bg-indigo-600 text-white`
        : `${baseClasses} border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40`;

    if (!button.disabled && !options.active) {
        button.addEventListener('click', () => {
            hrdCurrentPage = page;
            renderHrdPagination();
        });
    }

    return button;
}

function renderHrdPagination() {
    const filteredRows = getFilteredHrdRows();
    const totalRows = filteredRows.length;
    const pageCount = Math.max(1, Math.ceil(totalRows / hrdPageSize));
    hrdCurrentPage = Math.min(hrdCurrentPage, pageCount);

    const startIndex = totalRows ? (hrdCurrentPage - 1) * hrdPageSize : 0;
    const endIndex = Math.min(startIndex + hrdPageSize, totalRows);
    const visibleRows = new Set(filteredRows.slice(startIndex, endIndex));

    hrdCandidateRows.forEach(row => row.classList.toggle('hidden', !visibleRows.has(row)));
    if (hrdNoDataRow) hrdNoDataRow.classList.toggle('hidden', hrdCandidateRows.length > 0);
    hrdFilterEmpty.classList.toggle('hidden', totalRows > 0 || hrdCandidateRows.length === 0);

    filteredRows.slice(startIndex, endIndex).forEach((row, index) => {
        row.cells[0].textContent = startIndex + index + 1;
    });

    hrdPaginationInfo.textContent = totalRows
        ? `Menampilkan ${startIndex + 1} sampai ${endIndex} dari ${totalRows} hasil`
        : 'Menampilkan 0 hasil';

    hrdPagination.replaceChildren();
    hrdPagination.appendChild(makeHrdPageButton('<i class="fas fa-angle-left"></i>', Math.max(1, hrdCurrentPage - 1), {
        disabled: hrdCurrentPage === 1,
        ariaLabel: 'Halaman sebelumnya',
    }));

    const pageNumbers = new Set([1, pageCount, hrdCurrentPage - 1, hrdCurrentPage, hrdCurrentPage + 1]);
    const pages = [...pageNumbers].filter(page => page >= 1 && page <= pageCount).sort((a, b) => a - b);
    let previousPage = 0;

    pages.forEach(page => {
        if (previousPage && page - previousPage > 1) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'px-1 text-sm text-gray-400';
            ellipsis.textContent = '...';
            hrdPagination.appendChild(ellipsis);
        }

        hrdPagination.appendChild(makeHrdPageButton(String(page), page, {
            active: page === hrdCurrentPage,
            ariaLabel: `Halaman ${page}${page === hrdCurrentPage ? ', aktif' : ''}`,
        }));
        previousPage = page;
    });

    hrdPagination.appendChild(makeHrdPageButton('<i class="fas fa-angle-right"></i>', Math.min(pageCount, hrdCurrentPage + 1), {
        disabled: hrdCurrentPage === pageCount,
        ariaLabel: 'Halaman berikutnya',
    }));
}

hrdSearchInput?.addEventListener('input', () => {
    hrdCurrentPage = 1;
    renderHrdPagination();
});
hrdStatusFilter?.addEventListener('change', () => {
    hrdCurrentPage = 1;
    renderHrdPagination();
});
hrdDivisionFilter?.addEventListener('change', () => {
    hrdCurrentPage = 1;
    renderHrdPagination();
});
document.getElementById('hrdFilterReset')?.addEventListener('click', () => {
    if (hrdSearchInput) hrdSearchInput.value = '';
    if (hrdStatusFilter) hrdStatusFilter.value = '';
    if (hrdDivisionFilter) hrdDivisionFilter.value = '';
    hrdCurrentPage = 1;
    renderHrdPagination();
});
hrdPageSizeSelect.addEventListener('change', () => {
    hrdPageSize = Number(hrdPageSizeSelect.value) || 10;
    hrdCurrentPage = 1;
    renderHrdPagination();
});

document.querySelectorAll('[data-sort-column]').forEach(button => {
    button.addEventListener('click', () => {
        const column = Number(button.dataset.sortColumn);
        const direction = hrdSortState.column === column && hrdSortState.direction === 'asc' ? 'desc' : 'asc';
        const tbody = button.closest('table').tBodies[0];
        const rows = Array.from(tbody.querySelectorAll('tr.hrd-kandidat-row'));

        rows.sort((first, second) => {
            const firstValue = first.cells[column]?.textContent.trim() || '';
            const secondValue = second.cells[column]?.textContent.trim() || '';
            const comparison = firstValue.localeCompare(secondValue, 'id', { numeric: true, sensitivity: 'base' });
            return direction === 'asc' ? comparison : -comparison;
        });

        rows.forEach(row => tbody.appendChild(row));
        hrdCandidateRows = rows;
        hrdSortState = { column, direction };

        document.querySelectorAll('[data-sort-column]').forEach(sortButton => {
            const header = sortButton.closest('th');
            const icon = sortButton.querySelector('i');
            const active = Number(sortButton.dataset.sortColumn) === column;
            header.setAttribute('aria-sort', active ? (direction === 'asc' ? 'ascending' : 'descending') : 'none');
            icon.className = active
                ? `fas fa-sort-${direction === 'asc' ? 'up' : 'down'} text-blue-600`
                : 'fas fa-sort text-gray-400';
        });

            hrdCurrentPage = 1;
            renderHrdPagination();
    });
});

        renderHrdPagination();

const hrdKeluargaState = { karyawanId: null, records: [] };
let hrdFamilyAddressCascade = null;

const hrdPendidikanState = { karyawanId: null, karyawanNama: '', records: [], deleteId: null, button: null };

function syncHrdManageButton(button, dataKey, records) {
    if (!button) return;
    button.dataset[dataKey] = JSON.stringify(records);
    const count = button.querySelector('span');
    if (count) count.textContent = records.length;
}

function renderHrdPendidikanList() {
    const list = document.getElementById('hrdPendidikanList');
    if (!hrdPendidikanState.records.length) {
        list.innerHTML = '<div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">Belum ada riwayat pendidikan.</div>';
        return;
    }

    list.innerHTML = hrdPendidikanState.records.map(record => `
        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="min-w-0">
                <div class="font-medium text-sm text-gray-800">${escapeHtml(record.nama_sekolah)} &middot; ${escapeHtml(record.jenjang)}</div>
                <div class="mt-1 text-xs text-gray-500">
                    ${escapeHtml(record.jurusan || 'Jurusan tidak dicantumkan')}
                    &middot; ${escapeHtml(record.tahun_masuk)} - ${escapeHtml(record.tahun_lulus || 'Berjalan')}
                    ${record.lokasi ? `&middot; ${escapeHtml(record.lokasi)}` : ''}
                </div>
                ${record.gelar || record.ipk_atau_nilai ? `<div class="mt-1 text-xs text-gray-500">${escapeHtml(record.gelar || '')}${record.gelar && record.ipk_atau_nilai ? ' &middot; ' : ''}${escapeHtml(record.ipk_atau_nilai || '')}</div>` : ''}
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <button type="button" onclick="editHrdPendidikan(${Number(record.id)})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                <button type="button" onclick="openHrdPendidikanDelete(${Number(record.id)})" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `).join('');
}

function openHrdPendidikanModal(button) {
    hrdPendidikanState.button = button;
    hrdPendidikanState.karyawanId = button.dataset.karyawanId;
    hrdPendidikanState.karyawanNama = button.dataset.karyawanNama;
    hrdPendidikanState.records = JSON.parse(button.dataset.pendidikan || '[]');
    document.getElementById('hrdPendidikanPemilik').textContent = `Karyawan: ${hrdPendidikanState.karyawanNama}`;
    document.getElementById('hrdPendidikanToast').classList.add('hidden');
    renderHrdPendidikanList();
    document.getElementById('hrdPendidikanModal').classList.remove('hidden');
}

function openHrdPendidikanForm() {
    const form = document.getElementById('hrdPendidikanForm');
    form.reset();
    form.action = `/karyawan/${hrdPendidikanState.karyawanId}/pendidikan`;
    document.getElementById('hrdPendidikanMethod').value = 'POST';
    document.getElementById('hrdPendidikanFormTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Tambah Riwayat Pendidikan';
    showHrdPendidikanFormModal();
}

function editHrdPendidikan(id) {
    const record = hrdPendidikanState.records.find(item => Number(item.id) === Number(id));
    if (!record) return;

    const form = document.getElementById('hrdPendidikanForm');
    form.reset();
    form.action = `/pendidikan/${record.id}`;
    document.getElementById('hrdPendidikanMethod').value = 'PUT';
    document.getElementById('hrdPendidikanFormTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Riwayat Pendidikan';
    document.getElementById('hrdPendidikanJenjang').value = record.jenjang || '';
    document.getElementById('hrdPendidikanNamaSekolah').value = record.nama_sekolah || '';
    document.getElementById('hrdPendidikanJurusan').value = record.jurusan || '';
    document.getElementById('hrdPendidikanGelar').value = record.gelar || '';
    document.getElementById('hrdPendidikanTahunMasuk').value = record.tahun_masuk || '';
    document.getElementById('hrdPendidikanTahunLulus').value = record.tahun_lulus || '';
    document.getElementById('hrdPendidikanLokasi').value = record.lokasi || '';
    document.getElementById('hrdPendidikanIpk').value = record.ipk_atau_nilai || '';
    showHrdPendidikanFormModal();
}

function showHrdPendidikanFormModal() {
    const modal = document.getElementById('hrdPendidikanFormModal');
    const card = document.getElementById('hrdPendidikanFormCard');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        card.classList.remove('scale-95', 'opacity-0');
    });
}

function closeHrdPendidikanForm() {
    const modal = document.getElementById('hrdPendidikanFormModal');
    const card = document.getElementById('hrdPendidikanFormCard');
    modal.classList.add('opacity-0');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

function closeHrdPendidikanModal() {
    document.getElementById('hrdPendidikanModal').classList.add('hidden');
    closeHrdPendidikanForm();
    closeHrdPendidikanDelete();
}

function showHrdPendidikanToast(message, type = 'success') {
    const toast = document.getElementById('hrdPendidikanToast');
    toast.textContent = message;
    toast.className = type === 'success'
        ? 'mx-5 mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700'
        : 'mx-5 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
}

async function readHrdPendidikanResponse(response, fallback) {
    let result;
    try {
        result = await response.json();
    } catch {
        throw new Error('Respons server tidak valid.');
    }

    if (response.status === 401) throw new Error('Sesi login sudah berakhir. Silakan login kembali.');
    if (!response.ok) {
        const validation = result.errors ? Object.values(result.errors).flat().join(' ') : '';
        throw new Error(result.message || validation || fallback);
    }
    return result;
}

document.getElementById('hrdPendidikanForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    if (!this.reportValidity()) return;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { Accept: 'application/json' },
        });
        const result = await readHrdPendidikanResponse(response, 'Riwayat pendidikan gagal disimpan.');
        const savedRecord = result.data;
        const existingIndex = hrdPendidikanState.records.findIndex(item => Number(item.id) === Number(savedRecord.id));
        if (existingIndex >= 0) hrdPendidikanState.records[existingIndex] = savedRecord;
        else hrdPendidikanState.records.unshift(savedRecord);

        syncHrdManageButton(hrdPendidikanState.button, 'pendidikan', hrdPendidikanState.records);
        renderHrdPendidikanList();
        closeHrdPendidikanForm();
        showHrdPendidikanToast(existingIndex >= 0
            ? 'Riwayat pendidikan berhasil diperbarui.'
            : 'Riwayat pendidikan berhasil ditambahkan.');
    } catch (error) {
        showHrdPendidikanToast(error.message, 'error');
    }
});

function openHrdPendidikanDelete(id) {
    hrdPendidikanState.deleteId = id;
    const modal = document.getElementById('hrdPendidikanDeleteModal');
    const card = document.getElementById('hrdPendidikanDeleteCard');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        card.classList.remove('scale-95', 'opacity-0');
    });
}

function closeHrdPendidikanDelete() {
    const modal = document.getElementById('hrdPendidikanDeleteModal');
    const card = document.getElementById('hrdPendidikanDeleteCard');
    if (!modal || !card) return;
    modal.classList.add('opacity-0');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
    hrdPendidikanState.deleteId = null;
}

async function confirmHrdPendidikanDelete() {
    const id = hrdPendidikanState.deleteId;
    if (!id) return;

    const body = new FormData();
    body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    body.append('_method', 'DELETE');
    closeHrdPendidikanDelete();

    try {
        const response = await fetch(`/pendidikan/${id}`, {
            method: 'POST',
            body,
            headers: { Accept: 'application/json' },
        });
        await readHrdPendidikanResponse(response, 'Riwayat pendidikan gagal dihapus.');
        hrdPendidikanState.records = hrdPendidikanState.records.filter(record => Number(record.id) !== Number(id));
        syncHrdManageButton(hrdPendidikanState.button, 'pendidikan', hrdPendidikanState.records);
        renderHrdPendidikanList();
        showHrdPendidikanToast('Riwayat pendidikan berhasil dihapus.');
    } catch (error) {
        showHrdPendidikanToast(error.message, 'error');
    }
}

document.getElementById('hrdPendidikanFormModal').addEventListener('click', event => {
    if (event.target.id === 'hrdPendidikanFormModal') closeHrdPendidikanForm();
});
document.getElementById('hrdPendidikanDeleteModal').addEventListener('click', event => {
    if (event.target.id === 'hrdPendidikanDeleteModal') closeHrdPendidikanDelete();
});
document.getElementById('hrdPendidikanModal').addEventListener('click', event => {
    if (event.target.id === 'hrdPendidikanModal') closeHrdPendidikanModal();
});

const hrdPengalamanState = { ownerType: null, ownerId: null, karyawanNama: '', records: [], button: null };
const hrdSertifikasiState = { ownerType: null, ownerId: null, karyawanNama: '', records: [], button: null };

function showHrdRecordToast(id, message, type = 'success') {
    const toast = document.getElementById(id);
    toast.textContent = message;
    toast.className = type === 'success'
        ? 'mx-5 mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700'
        : 'mx-5 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700';
}

function renderHrdPengalamanList() {
    const list = document.getElementById('hrdPengalamanList');
    if (!hrdPengalamanState.records.length) {
        list.innerHTML = '<div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">Belum ada pengalaman kerja.</div>';
        return;
    }

    list.innerHTML = hrdPengalamanState.records.map(record => `
        <div class="flex items-start justify-between gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="min-w-0">
                <div class="font-medium text-sm text-gray-800">${escapeHtml(record.nama_perusahaan)} &middot; ${escapeHtml(record.jabatan)}</div>
                <div class="mt-1 text-xs text-gray-500">${escapeHtml(record.jenis_perusahaan)} &middot; ${escapeHtml(record.tahun_masuk)} - ${escapeHtml(record.tahun_keluar || 'Sekarang')}</div>
                <div class="mt-2 whitespace-pre-line text-sm text-gray-600">${escapeHtml(record.pekerjaan)}</div>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <button type="button" onclick="editHrdPengalaman(${Number(record.id)})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                <button type="button" onclick="deleteHrdPengalaman(${Number(record.id)})" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
            </div>
        </div>`).join('');
}

function openHrdPengalamanModal(button) {
    hrdPengalamanState.button = button;
    hrdPengalamanState.ownerType = button.dataset.ownerType;
    hrdPengalamanState.ownerId = button.dataset.ownerId;
    hrdPengalamanState.karyawanNama = button.dataset.karyawanNama;
    hrdPengalamanState.records = JSON.parse(button.dataset.pengalaman || '[]');
    document.getElementById('hrdPengalamanPemilik').textContent = `Karyawan: ${hrdPengalamanState.karyawanNama}`;
    document.getElementById('hrdPengalamanToast').classList.add('hidden');
    renderHrdPengalamanList();
    document.getElementById('hrdPengalamanModal').classList.remove('hidden');
}

function openHrdPengalamanForm(record = null) {
    const form = document.getElementById('hrdPengalamanForm');
    form.reset();
    form.action = record
        ? `/pengalaman-kerja/${record.id}`
        : `/${hrdPengalamanState.ownerType}/${hrdPengalamanState.ownerId}/pengalaman-kerja`;
    form.querySelector('[name="_method"]').value = record ? 'PUT' : 'POST';
    document.getElementById('hrdPengalamanFormTitle').textContent = record ? 'Edit Pengalaman Kerja' : 'Tambah Pengalaman Kerja';
    if (record) {
        for (const field of ['nama_perusahaan', 'jenis_perusahaan', 'tahun_masuk', 'tahun_keluar', 'jabatan', 'pekerjaan']) {
            form.elements[field].value = record[field] ?? '';
        }
    }
    document.getElementById('hrdPengalamanFormModal').classList.remove('hidden');
}

function editHrdPengalaman(id) {
    const record = hrdPengalamanState.records.find(item => Number(item.id) === Number(id));
    if (record) openHrdPengalamanForm(record);
}

function closeHrdPengalamanForm() {
    document.getElementById('hrdPengalamanFormModal').classList.add('hidden');
}

function closeHrdPengalamanModal() {
    document.getElementById('hrdPengalamanModal').classList.add('hidden');
    closeHrdPengalamanForm();
}

async function saveHrdPengalaman(event) {
    event.preventDefault();
    const form = event.currentTarget;
    if (!form.reportValidity()) return;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json' },
        });
        const result = await readHrdPendidikanResponse(response, 'Pengalaman kerja gagal disimpan.');
        const index = hrdPengalamanState.records.findIndex(item => Number(item.id) === Number(result.data.id));
        if (index >= 0) hrdPengalamanState.records[index] = result.data;
        else hrdPengalamanState.records.unshift(result.data);
        syncHrdManageButton(hrdPengalamanState.button, 'pengalaman', hrdPengalamanState.records);
        renderHrdPengalamanList();
        closeHrdPengalamanForm();
        showHrdRecordToast('hrdPengalamanToast', index >= 0 ? 'Pengalaman kerja berhasil diperbarui.' : 'Pengalaman kerja berhasil ditambahkan.');
    } catch (error) {
        showHrdRecordToast('hrdPengalamanToast', error.message, 'error');
    }
}

async function deleteHrdPengalaman(id) {
    if (!window.confirm('Hapus data pengalaman kerja ini?')) return;
    const body = new FormData();
    body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    body.append('_method', 'DELETE');

    try {
        const response = await fetch(`/pengalaman-kerja/${id}`, {
            method: 'POST',
            body,
            headers: { Accept: 'application/json' },
        });
        await readHrdPendidikanResponse(response, 'Pengalaman kerja gagal dihapus.');
        hrdPengalamanState.records = hrdPengalamanState.records.filter(record => Number(record.id) !== Number(id));
        syncHrdManageButton(hrdPengalamanState.button, 'pengalaman', hrdPengalamanState.records);
        renderHrdPengalamanList();
        showHrdRecordToast('hrdPengalamanToast', 'Pengalaman kerja berhasil dihapus.');
    } catch (error) {
        showHrdRecordToast('hrdPengalamanToast', error.message, 'error');
    }
}

document.getElementById('hrdPengalamanForm').addEventListener('submit', saveHrdPengalaman);

function renderHrdSertifikasiList() {
    const list = document.getElementById('hrdSertifikasiList');
    if (!hrdSertifikasiState.records.length) {
        list.innerHTML = '<div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">Belum ada sertifikasi.</div>';
        return;
    }

    list.innerHTML = hrdSertifikasiState.records.map(record => `
        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <div class="min-w-0">
                <div class="font-medium text-sm text-gray-800">${escapeHtml(record.nama_sertifikat)}</div>
                <div class="mt-1 text-xs text-gray-500">${escapeHtml(record.bidang_sertifikasi)} &middot; Badan pengesahan: ${escapeHtml(record.badan_pengesahan)} &middot; Tahun lulus ${escapeHtml(record.tahun_lulus)}</div>
                <a href="${escapeHtml(record.file_url)}" target="_blank" rel="noopener" class="mt-2 inline-block text-sm text-violet-700 hover:underline"><i class="fas fa-paperclip mr-1"></i>Lihat sertifikat</a>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <button type="button" onclick="editHrdSertifikasi(${Number(record.id)})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                <button type="button" onclick="deleteHrdSertifikasi(${Number(record.id)})" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
            </div>
        </div>`).join('');
}

function openHrdSertifikasiModal(button) {
    hrdSertifikasiState.button = button;
    hrdSertifikasiState.ownerType = button.dataset.ownerType;
    hrdSertifikasiState.ownerId = button.dataset.ownerId;
    hrdSertifikasiState.karyawanNama = button.dataset.karyawanNama;
    hrdSertifikasiState.records = JSON.parse(button.dataset.sertifikasi || '[]');
    document.getElementById('hrdSertifikasiPemilik').textContent = `Karyawan: ${hrdSertifikasiState.karyawanNama}`;
    document.getElementById('hrdSertifikasiToast').classList.add('hidden');
    renderHrdSertifikasiList();
    document.getElementById('hrdSertifikasiModal').classList.remove('hidden');
}

function openHrdSertifikasiForm(record = null) {
    const form = document.getElementById('hrdSertifikasiForm');
    form.reset();
    form.action = record
        ? `/sertifikasi/${record.id}`
        : `/${hrdSertifikasiState.ownerType}/${hrdSertifikasiState.ownerId}/sertifikasi`;
    form.querySelector('[name="_method"]').value = record ? 'PUT' : 'POST';
    document.getElementById('hrdSertifikasiFormTitle').textContent = record ? 'Edit Sertifikasi' : 'Tambah Sertifikasi';
    document.getElementById('hrdFileSertifikat').required = !record;
    const currentFile = document.getElementById('hrdFileSertifikatSaatIni');
    currentFile.classList.toggle('hidden', !record);
    currentFile.href = record?.file_url || '#';

    if (record) {
        form.elements.nama_sertifikat.value = record.nama_sertifikat || '';
        form.elements.bidang_sertifikasi.value = record.bidang_sertifikasi || '';
        form.elements.badan_pengesahan.value = record.badan_pengesahan || '';
        form.elements.tahun_lulus.value = record.tahun_lulus || '';
    }
    document.getElementById('hrdSertifikasiFormModal').classList.remove('hidden');
}

function editHrdSertifikasi(id) {
    const record = hrdSertifikasiState.records.find(item => Number(item.id) === Number(id));
    if (record) openHrdSertifikasiForm(record);
}

function closeHrdSertifikasiForm() {
    document.getElementById('hrdSertifikasiFormModal').classList.add('hidden');
}

function closeHrdSertifikasiModal() {
    document.getElementById('hrdSertifikasiModal').classList.add('hidden');
    closeHrdSertifikasiForm();
}

document.getElementById('hrdSertifikasiForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    if (!this.reportValidity()) return;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { Accept: 'application/json' },
        });
        const result = await readHrdPendidikanResponse(response, 'Sertifikasi gagal disimpan.');
        const index = hrdSertifikasiState.records.findIndex(item => Number(item.id) === Number(result.data.id));
        if (index >= 0) hrdSertifikasiState.records[index] = result.data;
        else hrdSertifikasiState.records.unshift(result.data);
        syncHrdManageButton(hrdSertifikasiState.button, 'sertifikasi', hrdSertifikasiState.records);
        renderHrdSertifikasiList();
        closeHrdSertifikasiForm();
        showHrdRecordToast('hrdSertifikasiToast', index >= 0 ? 'Sertifikasi berhasil diperbarui.' : 'Sertifikasi berhasil ditambahkan.');
    } catch (error) {
        showHrdRecordToast('hrdSertifikasiToast', error.message, 'error');
    }
});

async function deleteHrdSertifikasi(id) {
    if (!window.confirm('Hapus data sertifikasi ini?')) return;
    const body = new FormData();
    body.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    body.append('_method', 'DELETE');

    try {
        const response = await fetch(`/sertifikasi/${id}`, {
            method: 'POST',
            body,
            headers: { Accept: 'application/json' },
        });
        await readHrdPendidikanResponse(response, 'Sertifikasi gagal dihapus.');
        hrdSertifikasiState.records = hrdSertifikasiState.records.filter(record => Number(record.id) !== Number(id));
        syncHrdManageButton(hrdSertifikasiState.button, 'sertifikasi', hrdSertifikasiState.records);
        renderHrdSertifikasiList();
        showHrdRecordToast('hrdSertifikasiToast', 'Sertifikasi berhasil dihapus.');
    } catch (error) {
        showHrdRecordToast('hrdSertifikasiToast', error.message, 'error');
    }
}

function hrdKeluargaStatusClass(status) {
    return status === 'Suami' ? 'bg-blue-100 text-blue-800' :
        status === 'Istri' ? 'bg-pink-100 text-pink-800' : 'bg-amber-100 text-amber-800';
}

function hrdFormatTanggal(value) {
    const iso = normalisasiTanggal(value);
    return iso ? iso.split('-').reverse().join('/') : (value || '-');
}

function renderHrdKeluargaList() {
    const list = document.getElementById('hrdKeluargaList');
    if (!hrdKeluargaState.records.length) {
        list.innerHTML = '<div class="rounded-lg border border-dashed border-gray-300 p-6 text-center text-sm text-gray-500">Belum ada data keluarga.</div>';
        return;
    }

    list.innerHTML = hrdKeluargaState.records.map(record => {
        const addressParts = [record.address, record.village, record.district, record.regency, record.province]
            .filter(value => String(value || '').trim());
        const addressText = addressParts.length ? addressParts.join(', ') : '';

        return `
            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div class="min-w-0">
                    <div class="font-medium text-sm text-gray-800">${escapeHtml(record.nama)}</div>
                    <div class="text-xs text-gray-500">${escapeHtml(record.tempat_lahir)}, ${hrdFormatTanggal(record.tanggal_lahir)} &middot; ${hitungUsia(record.tanggal_lahir)} </div>
                    ${addressText ? `<div class="mt-1 text-xs text-gray-500"><i class="fas fa-location-dot mr-1 text-red-500"></i>${escapeHtml(addressText)}</div>` : ''}
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="rounded-full px-2 py-1 text-xs font-medium ${hrdKeluargaStatusClass(record.status)}">${escapeHtml(record.status)}</span>
                    <button type="button" onclick="editHrdKeluarga(${record.id})" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></button>
                    <form action="/keluarga/${record.id}" method="POST" class="inline" onsubmit="openHrdKeluargaDelete(event, this)">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>`;
    }).join('');
}

function openHrdKeluargaModal(button) {
    hrdKeluargaState.karyawanId = button.dataset.karyawanId;
    try { hrdKeluargaState.records = JSON.parse(button.dataset.keluarga || '[]'); }
    catch (error) { hrdKeluargaState.records = []; }
    document.getElementById('hrdKeluargaPemilik').textContent = `Karyawan: ${button.dataset.karyawanNama}`;
    resetHrdKeluargaForm();
    renderHrdKeluargaList();
    document.getElementById('hrdKeluargaModal').classList.remove('hidden');
}

function openHrdKeluargaForm() {
    resetHrdKeluargaForm();
    openHrdKeluargaFormModal();
}

function initHrdFamilyAddressCascade() {
    if (hrdFamilyAddressCascade) hrdFamilyAddressCascade.destroy();

    hrdFamilyAddressCascade = new AddressCascade({
        provinceId: 'hrdFamilyProvince',
        regencyId: 'hrdFamilyRegency',
        districtId: 'hrdFamilyDistrict',
        villageId: 'hrdFamilyVillage',
        baseUrl: '/',
    });
    hrdFamilyAddressCascade.resetAll();
}

async function openHrdKeluargaFormModal(address = null) {
    const modal = document.getElementById('hrdKeluargaFormModal');
    const card = document.getElementById('hrdKeluargaFormCard');
    modal.classList.remove('hidden');
    initHrdFamilyAddressCascade();
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        card.classList.remove('scale-95', 'opacity-0');
    });

    if (address?.province_id) {
        await hrdFamilyAddressCascade.loadWithValues(
            address.province_id,
            address.regency_id,
            address.district_id,
            address.village_id,
        );
    }
}

async function editHrdKeluarga(id) {
    const record = hrdKeluargaState.records.find(item => item.id === id);
    if (!record) return;
    document.getElementById('hrdKeluargaForm').action = `/keluarga/${id}`;
    document.getElementById('hrdKeluargaMethod').value = 'PUT';
    document.getElementById('hrdKeluargaFormTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Anggota Keluarga';
    document.getElementById('hrdKeluargaNama').value = record.nama;
    document.getElementById('hrdKeluargaTempat').value = record.tempat_lahir;
    document.getElementById('hrdKeluargaTanggal').value = normalisasiTanggal(record.tanggal_lahir);
    document.getElementById('hrdKeluargaUsia').value = hitungUsia(record.tanggal_lahir);
    document.getElementById('hrdKeluargaStatus').value = record.status;
    document.getElementById('hrdFamilyAddress').value = record.address || '';
    await openHrdKeluargaFormModal(record);
}

function resetHrdKeluargaForm() {
    const form = document.getElementById('hrdKeluargaForm');
    form.reset();
    form.action = `/karyawan/${hrdKeluargaState.karyawanId}/keluarga`;
    document.getElementById('hrdKeluargaMethod').value = 'POST';
    document.getElementById('hrdKeluargaFormTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Tambah Anggota Keluarga';
    document.getElementById('hrdKeluargaUsia').value = '';
    document.getElementById('hrdFamilyAddress').value = '';
}

function closeHrdKeluargaModal() {
    document.getElementById('hrdKeluargaModal').classList.add('hidden');
    closeHrdKeluargaForm();
}

function closeHrdKeluargaForm() {
    const modal = document.getElementById('hrdKeluargaFormModal');
    const card = document.getElementById('hrdKeluargaFormCard');
    modal.classList.add('opacity-0');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
    if (hrdFamilyAddressCascade) {
        hrdFamilyAddressCascade.destroy();
        hrdFamilyAddressCascade = null;
    }
}

function showHrdKeluargaToast(message, type = 'success') {
    const toast = document.getElementById('hrdKeluargaToast');
    toast.textContent = message;
    toast.className = type === 'success'
        ? 'mx-5 mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 opacity-0 -translate-y-2 transition-all duration-300'
        : 'mx-5 mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 opacity-0 -translate-y-2 transition-all duration-300';
    requestAnimationFrame(() => toast.classList.remove('opacity-0', '-translate-y-2'));
    clearTimeout(window.hrdKeluargaToastTimer);
    window.hrdKeluargaToastTimer = setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-2');
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, 3500);
}

async function readHrdKeluargaResponse(response, fallback) {
    let result = {};
    try { result = await response.json(); } catch (error) { /* response bukan JSON */ }
    if (response.status === 401) throw new Error('Sesi login sudah berakhir. Silakan login kembali.');
    if (!response.ok) {
        const validation = result.errors ? Object.values(result.errors).flat().join(' ') : '';
        throw new Error(result.message || validation || fallback);
    }
    return result;
}

let hrdKeluargaDeleteForm = null;

function openHrdKeluargaDelete(event, form) {
    event.preventDefault();
    hrdKeluargaDeleteForm = form;
    const modal = document.getElementById('hrdKeluargaDeleteModal');
    const card = document.getElementById('hrdKeluargaDeleteCard');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        card.classList.remove('scale-95', 'opacity-0');
    });
}

function closeHrdKeluargaDelete() {
    const modal = document.getElementById('hrdKeluargaDeleteModal');
    const card = document.getElementById('hrdKeluargaDeleteCard');
    modal.classList.add('opacity-0');
    card.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
    hrdKeluargaDeleteForm = null;
}

async function confirmHrdKeluargaDelete() {
    const form = hrdKeluargaDeleteForm;
    if (!form) return;
    closeHrdKeluargaDelete();

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json' },
        });

        await readHrdKeluargaResponse(response, 'Gagal menghapus data keluarga.');
        hrdKeluargaState.records = hrdKeluargaState.records.filter(record => `/keluarga/${record.id}` !== form.action);
        renderHrdKeluargaList();
        showHrdKeluargaToast('Data keluarga berhasil dihapus.');
    } catch (error) {
        showHrdKeluargaToast(error.message, 'error');
    }
}

document.getElementById('hrdKeluargaTanggal').addEventListener('input', function () {
    document.getElementById('hrdKeluargaUsia').value = hitungUsia(this.value);
    this.setCustomValidity(normalisasiTanggal(this.value) ? '' : 'Gunakan format tanggal DD/MM/YYYY yang valid.');
});
document.getElementById('hrdKeluargaForm').addEventListener('submit', async function (event) {
    event.preventDefault();
    const input = document.getElementById('hrdKeluargaTanggal');
    const tanggal = normalisasiTanggal(input.value);
    input.setCustomValidity(tanggal ? '' : 'Gunakan format tanggal DD/MM/YYYY yang valid.');
    if (!tanggal || !this.checkValidity()) {
        this.reportValidity();
        return;
    }

    input.value = tanggal;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { Accept: 'application/json' },
        });
        const result = await readHrdKeluargaResponse(response, 'Data keluarga gagal disimpan.');

        const saved = result.data;
        const savedRecord = {
            id: saved.id,
            nama: saved.nama,
            tempat_lahir: saved.tempat_lahir,
            tanggal_lahir: saved.tanggal_lahir,
            status: saved.status,
            province_id: saved.province_id,
            regency_id: saved.regency_id,
            district_id: saved.district_id,
            village_id: saved.village_id,
            address: saved.address,
            province: saved.province,
            regency: saved.regency,
            district: saved.district,
            village: saved.village,
        };
        const existingIndex = hrdKeluargaState.records.findIndex(record => record.id === savedRecord.id);
        if (existingIndex >= 0) hrdKeluargaState.records[existingIndex] = savedRecord;
        else hrdKeluargaState.records.unshift(savedRecord);

        renderHrdKeluargaList();
        closeHrdKeluargaForm();
        showHrdKeluargaToast(
            existingIndex >= 0 ? 'Data keluarga berhasil diperbarui.' : 'Data keluarga berhasil ditambahkan.'
        );
    } catch (error) {
        showHrdKeluargaToast(error.message, 'error');
    }
});
document.getElementById('hrdKeluargaModal').addEventListener('click', event => {
    if (event.target.id === 'hrdKeluargaModal') closeHrdKeluargaModal();
});
document.getElementById('hrdKeluargaFormModal').addEventListener('click', event => {
    if (event.target.id === 'hrdKeluargaFormModal') closeHrdKeluargaForm();
});
document.getElementById('hrdKeluargaDeleteModal').addEventListener('click', event => {
    if (event.target.id === 'hrdKeluargaDeleteModal') closeHrdKeluargaDelete();
});
document.getElementById('hrdPengalamanModal').addEventListener('click', event => {
    if (event.target.id === 'hrdPengalamanModal') closeHrdPengalamanModal();
});
document.getElementById('hrdPengalamanFormModal').addEventListener('click', event => {
    if (event.target.id === 'hrdPengalamanFormModal') closeHrdPengalamanForm();
});
document.getElementById('hrdSertifikasiModal').addEventListener('click', event => {
    if (event.target.id === 'hrdSertifikasiModal') closeHrdSertifikasiModal();
});
document.getElementById('hrdSertifikasiFormModal').addEventListener('click', event => {
    if (event.target.id === 'hrdSertifikasiFormModal') closeHrdSertifikasiForm();
});

/* ============ Init ============ */
document.addEventListener('DOMContentLoaded', function () {
    kosongkanPendidikan('pendidikanRowsAdd', false);
    wizardGo('addForm', 1);
    wizardGo('editForm', 1);

    document.querySelectorAll('input[type="file"][name="foto"]').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                this.value = '';
                alert('Foto terlalu besar. Ukuran maksimal foto adalah 2 MB.');
            }
        });
    });
});

// Kalau ada field invalid di slide lain, balik ke slide itu sebelum submit
['addForm', 'editForm'].forEach(function (fid) {
    const form = document.getElementById(fid);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        normalisasiTanggalKeluarga(form);
        if (!form.checkValidity()) {
            e.preventDefault();
            const bad = form.querySelector(':invalid');
            if (bad) {
                const section = bad.closest('[data-wizard-step]');
                if (section) wizardGo(fid, Number(section.dataset.wizardStep));
                bad.reportValidity();
            }
        }
    });
});

// Tutup klik area gelap
['modal', 'editModal', 'karyawanModal'].forEach(function (id) {
    const el = document.getElementById(id);
    if (el) el.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden'); });
});

// Tutup pakai Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('hrdPendidikanFormModal').classList.contains('hidden')) closeHrdPendidikanForm();
        else if (!document.getElementById('hrdPendidikanDeleteModal').classList.contains('hidden')) closeHrdPendidikanDelete();
        else if (!document.getElementById('hrdPendidikanModal').classList.contains('hidden')) closeHrdPendidikanModal();
        else if (!document.getElementById('hrdPengalamanFormModal').classList.contains('hidden')) closeHrdPengalamanForm();
        else if (!document.getElementById('hrdPengalamanModal').classList.contains('hidden')) closeHrdPengalamanModal();
        else if (!document.getElementById('hrdSertifikasiFormModal').classList.contains('hidden')) closeHrdSertifikasiForm();
        else if (!document.getElementById('hrdSertifikasiModal').classList.contains('hidden')) closeHrdSertifikasiModal();
        else if (!document.getElementById('hrdKeluargaFormModal').classList.contains('hidden')) closeHrdKeluargaForm();
        else if (!document.getElementById('hrdKeluargaDeleteModal').classList.contains('hidden')) closeHrdKeluargaDelete();
        else if (!document.getElementById('hrdKeluargaModal').classList.contains('hidden')) closeHrdKeluargaModal();
        closeModal('modal');
        closeModal('editModal');
        closeKaryawanModal();
    }
});
</script>
@endsection
