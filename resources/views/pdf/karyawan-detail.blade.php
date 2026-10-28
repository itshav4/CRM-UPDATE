
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Karyawan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        h2 { font-size: 12px; background: #eaf0f8; padding: 7px; margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f2f5f9; }
        .profile { width: 100%; margin-bottom: 15px; }
        .profile td { border: none; }
        .photo { width: 95px; height: 115px; object-fit: cover; }
        .label { width: 30%; font-weight: bold; }
        .empty { color: #777; font-style: italic; }
    </style>
</head>
<body>
    <h1>DATA DETAIL KARYAWAN</h1>
    <div class="subtitle">Dokumen data karyawan</div>

    <table class="profile">
        <tr>
            <td style="width:120px">
                @if ($foto)
                    <img class="photo" src="{{ $foto }}" alt="Foto karyawan">
                @else
                    <div class="photo">Foto tidak tersedia</div>
                @endif
            </td>
            <td>
                <h2 style="margin-top:0">{{ $karyawan->nama_lengkap ?: '-' }}</h2>
                <p><strong>NIK:</strong> {{ $karyawan->nik ?: '-' }}</p>
                <p><strong>Status:</strong> {{ $karyawan->status_kerja ? ucfirst($karyawan->status_kerja) : '-' }}</p>
            </td>
        </tr>
    </table>

    <h2>Data Utama dan Identitas</h2>
    <table>
        <tr><td class="label">Nama lengkap</td><td>{{ $karyawan->nama_lengkap ?: '-' }}</td></tr>
        <tr><td class="label">NIK</td><td>{{ $karyawan->nik ?: '-' }}</td></tr>
        <tr><td class="label">Nomor HP</td><td>{{ $karyawan->no_telepon ?: '-' }}</td></tr>
        <tr><td class="label">Divisi / Departemen</td><td>{{ $karyawan->kandidat?->divisi ?: ($karyawan->departemen_id ?: '-') }}</td></tr>
        <tr><td class="label">Tanggal masuk kerja</td><td>{{ $karyawan->tanggal_masuk ? \Carbon\Carbon::parse($karyawan->tanggal_masuk)->format('d/m/Y') : '-' }}</td></tr>
        <tr><td class="label">Status karyawan</td><td>{{ $karyawan->status_kerja ? ucfirst($karyawan->status_kerja) : '-' }}</td></tr>
        <tr><td class="label">Email</td><td>-</td></tr>
        <tr><td class="label">Jabatan</td><td>-</td></tr>
        <tr><td class="label">Nomor BPJS</td><td>{{ $karyawan->no_bpjs ?: '-' }}</td></tr>
        <tr><td class="label">NPWP</td><td>{{ $karyawan->npwp ?: '-' }}</td></tr>
    </table>

    @php
        $sections = [
            'Pendidikan' => $karyawan->pendidikan,
            'Pengalaman Kerja' => $karyawan->pengalamanKerja,
            'Sertifikasi' => $karyawan->sertifikasi,
            'Keluarga' => $karyawan->keluarga,
        ];

        $hiddenFields = ['id', 'karyawan_id', 'kandidat_id', 'created_at', 'updated_at'];
    @endphp

    @foreach ($sections as $title => $records)
        <h2>{{ $title }}</h2>

        @if ($records->isNotEmpty())
            <table>
                @foreach ($records as $index => $record)
                    <tr>
                        <th colspan="2">{{ $index + 1 }}.</th>
                    </tr>
                    @foreach ($record->getAttributes() as $field => $value)
                        @if (!in_array($field, $hiddenFields) && $value !== null && $value !== '')
                            <tr>
                                <td class="label">
                                    {{ \Illuminate\Support\Str::headline($field) }}
                                </td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </table>
        @else
            <p class="empty">Belum ada data {{ strtolower($title) }}.</p>
        @endif
    @endforeach

    <p style="margin-top:25px; color:#777; font-size:8px">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </p>
</body>
</html>
