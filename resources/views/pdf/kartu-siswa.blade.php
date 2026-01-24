<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 15px; }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        /* Container Tabel Utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .card-td {
            width: 33.33%;
            padding: 8px;
            vertical-align: top;
        }

        .student-card {
            width: 100%;
            height: 460px; /* Tinggi proporsional */
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Wave Atas menggunakan SVG agar halus di dompdf */
        .header-wave {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background-color: #209378;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.15' d='M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,144C672,139,768,181,864,181.3C960,181,1056,139,1152,122.7C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            z-index: 1;
        }

        /* Wave Bawah */
        .footer-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background-color: #209378;
            border-radius: 80% 80% 0 0 / 20% 20% 0 0;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 5;
            padding: 15px;
            text-align: center;
        }

        .header-info {
            color: white;
            margin-bottom: 15px;
        }

        .school-logo {
            width: 45px;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 8px;
            font-weight: normal;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .school-sub {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
        }

        /* Card Title Section */
        .title-section {
            margin-top: 15px;
            text-align: left;
            padding-left: 10px;
            border-left: 4px solid #209378;
        }

        .title-main {
            font-size: 18px;
            font-weight: 800;
            color: #209378;
            line-height: 1;
        }

        .title-small {
            font-size: 10px;
            color: #333;
        }

        .student-name {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            margin: 15px 0;
            text-transform: uppercase;
            height: 35px;
        }

        /* QR Section */
        .qr-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: white;
            padding: 10px;
        }

        .qr-corner {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid #209378;
        }
        .c-tl { top: 0; left: 0; border-right: none; border-bottom: none; }
        .c-tr { top: 0; right: 0; border-left: none; border-bottom: none; }
        .c-bl { bottom: 0; left: 0; border-right: none; border-top: none; }
        .c-br { bottom: 0; right: 0; border-left: none; border-top: none; }

        .scan-hint {
            font-size: 7px;
            color: #666;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        /* Info Grid */
        .info-table {
            width: 100%;
            font-size: 8px;
            text-align: left;
            margin-top: 10px;
        }

        .info-label {
            color: #209378;
            font-weight: bold;
            display: block;
        }

        .info-val {
            color: #333;
            margin-bottom: 5px;
        }

        .footer-text {
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
            font-size: 7px;
            color: white;
            z-index: 10;
        }
    </style>
</head>
<body>

    <table class="main-table">
        @foreach ($siswas->chunk(3) as $chunk)
        <tr>
            @foreach ($chunk as $siswa)
            <td class="card-td">
                <div class="student-card">
                    <div class="header-wave"></div>
                    
                    <div class="content">
                        <div class="header-info">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Tut_Wuri_Handayani.svg/1200px-Tut_Wuri_Handayani.svg.png" class="school-logo">
                            <div class="school-name">YAYASAN PENDIDIKAN ISLAM NURUL MANNAN</div>
                            <div class="school-sub">{{ $kelas->lembaga->nama_lembaga }}</div>
                        </div>

                        <div class="title-section">
                            <span class="title-main">KARTU</span><br>
                            <span class="title-small">Pelajar Digital</span>
                        </div>

                        <div class="student-name">{{ $siswa->nama_siswa }}</div>

                        <div class="qr-container">
                            <div class="qr-corner c-tl"></div>
                            <div class="qr-corner c-tr"></div>
                            <div class="qr-corner c-bl"></div>
                            <div class="qr-corner c-br"></div>
                            <img src="data:image/png;base64,{{ $qrs[$siswa->id] }}" width="100" height="100">
                        </div>
                        <div class="scan-hint">Scan QR untuk absensi siswa</div>

                        <table class="info-table">
                            <tr>
                                <td width="55%">
                                    <span class="info-label">Alamat:</span>
                                    <div class="info-val">Jl. Pasar Jumat No. 1, Jember</div>
                                </td>
                                <td width="45%">
                                    <span class="info-label">NISN:</span>
                                    <div class="info-val">{{ $siswa->nisn }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="info-label">Kelas:</span>
                                    <div class="info-val">{{ $kelas->nama_kelas }}</div>
                                </td>
                                <td>
                                    <span class="info-label">Kontak:</span>
                                    <div class="info-val">+62 852 0498</div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="footer-wave"></div>
                    <div class="footer-text">Kartu ini milik {{ $kelas->lembaga->nama_lembaga }}</div>
                </div>
            </td>
            @endforeach
            @for ($i = 0; $i < (3 - count($chunk)); $i++)
                <td class="card-td"></td>
            @endfor
        </tr>
        @endforeach
    </table>

</body>
</html>