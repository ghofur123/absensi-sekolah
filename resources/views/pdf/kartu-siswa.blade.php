<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .card-td {
            width: 33.33%;
            padding: 5px;
            vertical-align: top;
        }

        /* CONTAINER KARTU */
        .student-card {
            width: 90%;
            height: 330px;
            /* Sesuaikan dengan aspek rasio background */
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            border: 1px solid #ccc;
        }

        /* BACKGROUND GAMBAR */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* LAYER CONTENT DI ATAS BACKGROUND */
        .content {
            position: relative;
            z-index: 10;
            width: 100%;
            height: 100%;
        }

        /* POSISI NAMA SISWA */
        .student-name {
            position: absolute;
            top: 80px;
            /* Atur naik turunnya nama */
            width: 100%;
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        /* POSISI NISN (Di bawah nama atau di area info) */
        .student-nisn {
            position: absolute;
            top: 350px;
            /* Menyesuaikan posisi di kolom NISN pada gambar */
            left: 195px;
            /* Menyesuaikan kolom kanan pada desain */
            font-size: 7.5pt;
            font-weight: bold;
            color: #333;
        }

        /* POSISI QR CODE */
        .qr-container {
            position: absolute;
            top: 130px;
            /* Pusatkan di tengah kotak siku-siku background */
            left: 50%;
            margin-left: -48px;
            /* Setengah dari lebar QR untuk center murni */
            width: 96px;
            height: 96px;
            text-align: center;
        }

        .qr-image {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    @php
        $bgUrl = $kelas->lembaga?->getFirstMediaUrl('bg_kartu_siswa');

        // 🔑 ubah URL jadi PATH FILE
        $bgPath = $bgUrl ? public_path(parse_url($bgUrl, PHP_URL_PATH)) : null;
    @endphp
    <table class="main-table">
        @foreach ($siswas->chunk(3) as $chunk)
            <tr>
                @foreach ($chunk as $siswa)
                    <td class="card-td">
                        <div class="student-card">
                            @if ($bgPath && file_exists($bgPath))
                                <img src="{{ $bgPath }}" class="bg-image">
                            @endif

                            <div class="content">
                                <div class="student-name">
                                    {{ $siswa->nama_siswa }}<br>
                                    NISN : {{ $siswa->nisn }}
                                </div>
                                <div class="qr-container">
                                    <img src="data:image/png;base64,{{ $qrs[$siswa->id] }}" class="qr-image">
                                </div>


                            </div>
                        </div>
                    </td>
                @endforeach

            </tr>
        @endforeach
    </table>

</body>

</html>
