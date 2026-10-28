<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="{{ $prefix }}_jenjang" class="mb-1 block text-sm font-medium text-gray-700">Jenjang *</label>
        <select id="{{ $prefix }}_jenjang" name="jenjang" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
            <option value="">Pilih jenjang</option>
            @foreach(['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'] as $jenjang)
                <option value="{{ $jenjang }}">{{ $jenjang }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="{{ $prefix }}_nama_sekolah" class="mb-1 block text-sm font-medium text-gray-700">Nama Sekolah / Institusi *</label>
        <input id="{{ $prefix }}_nama_sekolah" name="nama_sekolah" required maxlength="255" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Contoh: Universitas Indonesia">
    </div>
    <div>
        <label for="{{ $prefix }}_jurusan" class="mb-1 block text-sm font-medium text-gray-700">Jurusan</label>
        <input id="{{ $prefix }}_jurusan" name="jurusan" maxlength="255" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Contoh: Teknik Informatika">
    </div>
    <div>
        <label for="{{ $prefix }}_gelar" class="mb-1 block text-sm font-medium text-gray-700">Gelar</label>
        <input id="{{ $prefix }}_gelar" name="gelar" maxlength="255" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Contoh: S.Kom.">
    </div>
    <div>
        <label for="{{ $prefix }}_tahun_masuk" class="mb-1 block text-sm font-medium text-gray-700">Tahun Masuk *</label>
        <input id="{{ $prefix }}_tahun_masuk" name="tahun_masuk" type="number" min="1900" max="{{ date('Y') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="{{ date('Y') - 4 }}">
    </div>
    <div>
        <label for="{{ $prefix }}_tahun_lulus" class="mb-1 block text-sm font-medium text-gray-700">Tahun Lulus</label>
        <input id="{{ $prefix }}_tahun_lulus" name="tahun_lulus" type="number" min="1900" max="{{ date('Y') + 10 }}" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Kosongkan jika masih berjalan">
    </div>
    <div>
        <label for="{{ $prefix }}_lokasi" class="mb-1 block text-sm font-medium text-gray-700">Lokasi / Kota</label>
        <input id="{{ $prefix }}_lokasi" name="lokasi" maxlength="255" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Contoh: Jakarta">
    </div>
    <div>
        <label for="{{ $prefix }}_ipk_atau_nilai" class="mb-1 block text-sm font-medium text-gray-700">IPK / Nilai</label>
        <input id="{{ $prefix }}_ipk_atau_nilai" name="ipk_atau_nilai" maxlength="15" class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="Contoh: 3.75">
    </div>
</div>
