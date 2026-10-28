
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Karyawan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #bbb; padding: 6px; text-align: left; }
        th { background: #eaf0f8; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h1>REKAP DATA KARYAWAN</h1>
    <div class="subtitle">
        Dicetak pada {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Nomor HP</th>
                <th>Email</th>
                <th>Tanggal Masuk Kerja</th>
                <th>Status Karyawan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($karyawan as $i => $k)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $k->nik ?: '-' }}</td>
                    <td>{{ $k->nama_lengkap ?: '-' }}</td>
                    <td>-</td>
                    <td>{{ $k->kandidat?->divisi ?: ($k->departemen_id ?: '-') }}</td>
                    <td>{{ $k->no_telepon ?: '-' }}</td>
                    <td>-</td>
                    <td>
                        {{ $k->tanggal_masuk
                            ? \Carbon\Carbon::parse($k->tanggal_masuk)->format('d/m/Y')
                            : '-' }}
                    </td>
                    <td>{{ $k->status_kerja ? ucfirst($k->status_kerja) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center">
                        Belum ada data karyawan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
