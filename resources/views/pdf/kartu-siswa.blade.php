<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        h3 {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-spacing: 10px;
        }

        td {
            width: 33.33%;
            vertical-align: top;
        }

        .card {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            height: 230px;
        }

        .lembaga {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .nama {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .info {
            margin-bottom: 3px;
        }

        .qr {
            margin-top: 8px;
        }
    </style>
</head>

<body>

    <h3>KARTU SISWA – {{ strtoupper($kelas->lembaga->nama_lembaga) }}</h3>

    <table>
        <tr>
            @foreach ($siswas as $index => $siswa)
            <td>
                <div class="card">
                    <div class="lembaga">
                        {{ $kelas->lembaga->nama_lembaga }}
                    </div>

                    <div class="nama">{{ $siswa->nama_siswa }}</div>
                    <div class="info">NISN: {{ $siswa->nisn }}</div>
                    <div class="info">Kelas: {{ $kelas->nama_kelas }}</div>

                    <div class="qr">
                        <img src="data:image/png;base64,{{ $qrs[$siswa->id] }}" width="100">
                    </div>
                </div>
            </td>

            @if (($index + 1) % 3 == 0)
        </tr>
        <tr>
            @endif
            @endforeach
        </tr>
    </table>

</body>

</html>