@extends('layout.main')

@section('content')
<div class="pt-20 px-6 pb-10">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Data Pendidikan</h1>
            <p class="text-sm text-gray-500">Kelola riwayat pendidikan karyawan dan kandidat.</p>
        </div>
        <button type="button" onclick="openCrudModal()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
            <i class="fas fa-graduation-cap"></i> Kelola Pendidikan
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-medium">Data belum dapat disimpan:</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div id="crudModal" class="fixed inset-0 z-30 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
        <div class="flex max-h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between bg-emerald-700 px-5 py-4 text-white">
                <div>
                    <h2 class="font-semibold"><i class="fas fa-graduation-cap mr-2"></i>Riwayat Pendidikan</h2>
                    <p class="mt-1 text-xs text-emerald-100">Daftar riwayat pendidikan dan aksi CRUD.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openCreateModal()" class="rounded-lg bg-white px-3 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50"><i class="fas fa-plus mr-1"></i>Tambah</button>
                    <button type="button" onclick="closeCrudModal()" class="h-8 w-8 rounded-full hover:bg-white/20" aria-label="Tutup">&times;</button>
                </div>
            </div>
            <div class="overflow-auto p-4">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Jenjang</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Sekolah / Institusi</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Jurusan</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tahun</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Pemilik Data</th>
                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($pendidikan as $record)
                                    <tr class="cursor-pointer hover:bg-emerald-50" onclick="openDetailModal({{ $record->id }})">
                                        <td class="px-5 py-4"><span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-800">{{ $record->jenjang }}</span></td>
                                        <td class="px-5 py-4 font-medium text-gray-800">{{ $record->nama_sekolah }}</td>
                                        <td class="px-5 py-4 text-sm text-gray-600">{{ $record->jurusan ?: '-' }}</td>
                                        <td class="px-5 py-4 text-sm text-gray-600">{{ $record->tahun_masuk }} - {{ $record->tahun_lulus ?: 'Berjalan' }}</td>
                                        <td class="px-5 py-4 text-sm text-gray-600">{{ $record->karyawan?->nama_lengkap ?? $record->kandidat?->nama_lengkap ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 text-right">
                                            <button type="button" onclick="event.stopPropagation(); openDetailModal({{ $record->id }})" class="text-emerald-600 hover:text-emerald-800" title="Lihat detail"><i class="fas fa-eye"></i></button>
                                            <button type="button" onclick="event.stopPropagation(); openEditModal({{ $record->id }})" class="ml-3 text-blue-600 hover:text-blue-800" title="Edit data"><i class="fas fa-edit"></i></button>
                                            <form action="{{ route('pendidikan.destroy', $record) }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('Hapus riwayat pendidikan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-3 text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500">Belum ada riwayat pendidikan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-2xl overflow-y-auto rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between rounded-t-xl bg-emerald-700 px-5 py-4 text-white">
                <h2 class="font-semibold"><i class="fas fa-plus mr-2"></i>Tambah Riwayat Pendidikan</h2>
                <button type="button" onclick="closeCreateModal()" class="h-8 w-8 rounded-full hover:bg-white/20" aria-label="Tutup">&times;</button>
            </div>
            <form method="POST" action="{{ route('pendidikan.store-page') }}" class="space-y-4 p-5">
                @csrf
                <div>
                    <label for="createPemilik" class="mb-1 block text-sm font-medium text-gray-700">Pemilik Data *</label>
                    <select id="createPemilik" name="pemilik" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                        <option value="">Pilih karyawan atau kandidat</option>
                        @foreach($karyawan as $person)<option value="karyawan:{{ $person->id }}">Karyawan: {{ $person->nama_lengkap }}</option>@endforeach
                        @foreach($kandidat as $person)<option value="kandidat:{{ $person->id }}">Kandidat: {{ $person->nama_lengkap }}</option>@endforeach
                    </select>
                </div>
                @include('pendidikan.form-fields', ['prefix' => 'create'])
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCreateModal()" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700">Batal</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"><i class="fas fa-save mr-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detailModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between rounded-t-xl bg-emerald-700 px-5 py-4 text-white">
                <h2 class="font-semibold"><i class="fas fa-id-card mr-2"></i>Detail Pendidikan</h2>
                <button type="button" onclick="closeDetailModal()" class="h-8 w-8 rounded-full hover:bg-white/20" aria-label="Tutup">&times;</button>
            </div>
            <dl class="grid grid-cols-2 gap-x-4 gap-y-3 p-5 text-sm">
                <dt class="text-gray-500">Jenjang</dt><dd id="detailJenjang" class="font-medium"></dd>
                <dt class="text-gray-500">Sekolah / Institusi</dt><dd id="detailSekolah" class="font-medium"></dd>
                <dt class="text-gray-500">Jurusan</dt><dd id="detailJurusan"></dd>
                <dt class="text-gray-500">Gelar</dt><dd id="detailGelar"></dd>
                <dt class="text-gray-500">Tahun Masuk</dt><dd id="detailMasuk"></dd>
                <dt class="text-gray-500">Tahun Lulus</dt><dd id="detailLulus"></dd>
                <dt class="text-gray-500">Lokasi</dt><dd id="detailLokasi"></dd>
                <dt class="text-gray-500">IPK / Nilai</dt><dd id="detailNilai"></dd>
                <dt class="text-gray-500">Pemilik Data</dt><dd id="detailPemilik"></dd>
            </dl>
            <div class="flex justify-end border-t border-gray-100 px-5 py-4">
                <button type="button" onclick="closeDetailModal()" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">Tutup</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 p-4" role="dialog" aria-modal="true">
        <div class="w-full max-w-2xl overflow-y-auto rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between rounded-t-xl bg-slate-800 px-5 py-4 text-white">
                <h2 class="font-semibold"><i class="fas fa-pen mr-2"></i>Edit Riwayat Pendidikan</h2>
                <button type="button" onclick="closeEditModal()" class="h-8 w-8 rounded-full hover:bg-white/20" aria-label="Tutup">&times;</button>
            </div>
            <form id="editForm" method="POST" class="space-y-4 p-5">
                @csrf
                @method('PUT')
                @include('pendidikan.form-fields', ['prefix' => 'edit'])
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700">Batal</button>
                    <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-900"><i class="fas fa-save mr-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const educationRecords = @json($pendidikan->map(fn ($record) => [
    'id' => $record->id,
    'jenjang' => $record->jenjang,
    'nama_sekolah' => $record->nama_sekolah,
    'jurusan' => $record->jurusan,
    'gelar' => $record->gelar,
    'ipk_atau_nilai' => $record->ipk_atau_nilai,
    'lokasi' => $record->lokasi,
    'tahun_masuk' => $record->tahun_masuk,
    'tahun_lulus' => $record->tahun_lulus,
    'pemilik' => $record->karyawan?->nama_lengkap ?? $record->kandidat?->nama_lengkap ?? '-',
])->values());
let activeEducation = null;

function showModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function hideModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

function openCrudModal() { showModal('crudModal'); }
function closeCrudModal() {
    if (!document.getElementById('detailModal').classList.contains('hidden')) return;
    hideModal('crudModal');
}
function openCreateModal() { showModal('createModal'); }
function closeCreateModal() { hideModal('createModal'); }
function closeDetailModal() { hideModal('detailModal'); }
function closeEditModal() { hideModal('editModal'); }

function openDetailModal(id) {
    activeEducation = educationRecords.find(record => record.id === id);
    if (!activeEducation) return;

    const fields = {
        detailJenjang: activeEducation.jenjang,
        detailSekolah: activeEducation.nama_sekolah,
        detailJurusan: activeEducation.jurusan,
        detailGelar: activeEducation.gelar,
        detailMasuk: activeEducation.tahun_masuk,
        detailLulus: activeEducation.tahun_lulus || 'Belum lulus',
        detailLokasi: activeEducation.lokasi,
        detailNilai: activeEducation.ipk_atau_nilai,
        detailPemilik: activeEducation.pemilik,
    };
    Object.entries(fields).forEach(([field, value]) => {
        document.getElementById(field).textContent = value || '-';
    });
    showModal('detailModal');
}

function openEditModal(id) {
    activeEducation = educationRecords.find(record => record.id === id);
    if (!activeEducation) return;

    const form = document.getElementById('editForm');
    form.action = `/pendidikan/${activeEducation.id}`;
    ['jenjang', 'nama_sekolah', 'jurusan', 'gelar', 'ipk_atau_nilai', 'lokasi', 'tahun_masuk', 'tahun_lulus']
        .forEach(field => {
            const input = document.getElementById(`edit_${field}`);
            input.value = activeEducation[field] ?? '';
        });
    showModal('editModal');
}

document.addEventListener('click', event => {
    if (event.target.id === 'crudModal') closeCrudModal();
    if (event.target.id === 'createModal') closeCreateModal();
    if (event.target.id === 'detailModal') closeDetailModal();
    if (event.target.id === 'editModal') closeEditModal();
});
document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    if (!document.getElementById('editModal').classList.contains('hidden')) closeEditModal();
    else if (!document.getElementById('detailModal').classList.contains('hidden')) closeDetailModal();
    else if (!document.getElementById('createModal').classList.contains('hidden')) closeCreateModal();
    else closeCrudModal();
});
</script>
@endsection
