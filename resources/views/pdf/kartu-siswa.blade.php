<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            padding: 20px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-container {
            margin-bottom: 15px;
        }

        .student-card {
            border: 2px solid #667eea;
            border-radius: 10px;
            height: 380px;
            overflow: hidden;
            background: #f8f9ff;
            page-break-inside: avoid;
        }

        /* Variasi warna */
        .student-card.card-purple {
            border-color: #667eea;
            background: #f8f9ff;
        }

        .student-card.card-pink {
            border-color: #f5576c;
            background: #fff8f9;
        }

        .student-card.card-blue {
            border-color: #4facfe;
            background: #f0fbff;
        }

        .card-header-custom {
            background: #667eea;
            padding: 10px 8px;
            text-align: center;
            border-bottom: 3px solid #ffd700;
        }

        .card-header-custom.header-pink {
            background: #f5576c;
        }

        .card-header-custom.header-blue {
            background: #4facfe;
        }

        .lembaga-name {
            font-weight: bold;
            font-size: 11px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        .card-body-custom {
            padding: 12px 10px;
            text-align: center;
        }

        .photo-section {
            margin-bottom: 8px;
        }

        .photo-placeholder {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            border-radius: 50%;
            background: white;
            border: 3px solid #ffd700;
            display: block;
        }

        .student-name {
            font-weight: bold;
            font-size: 13px;
            margin: 8px 0 6px 0;
            color: #2c3e50;
            min-height: 28px;
            line-height: 14px;
        }

        .info-section {
            margin: 8px 0;
        }

        .info-item {
            background: #e8eaf6;
            padding: 5px 8px;
            margin-bottom: 4px;
            border-radius: 4px;
            font-size: 10px;
            text-align: left;
            border-left: 3px solid #667eea;
        }

        .info-item.info-pink {
            background: #ffe4e8;
            border-left-color: #f5576c;
        }

        .info-item.info-blue {
            background: #e0f7ff;
            border-left-color: #4facfe;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 45px;
        }

        .qr-section {
            margin-top: 10px;
            text-align: center;
        }

        .qr-wrapper {
            padding: 8px;
            background: white;
            display: inline-block;
            border-radius: 6px;
            border: 2px solid #ddd;
        }

        .qr-wrapper img {
            display: block;
            margin: 0 auto;
        }

        /* Grid manual untuk 3 kolom */
        .row-custom {
            width: 100%;
            margin-bottom: 15px;
        }

        .row-custom::after {
            content: "";
            display: table;
            clear: both;
        }

        .col-custom {
            float: left;
            width: 32%;
            margin-right: 2%;
            margin-bottom: 15px;
        }

        .col-custom:nth-child(3n) {
            margin-right: 0;
        }

        @media print {
            body {
                padding: 10px;
            }

            .student-card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <div class="page-title">
        KARTU SISWA – {{ strtoupper($kelas->lembaga->nama_lembaga) }}
    </div>

    <div class="row-custom">
        @foreach ($siswas as $index => $siswa)
        @php
        $cardClass = '';
        $headerClass = '';
        $infoClass = '';

        $remainder = $index % 3;
        if ($remainder == 0) {
        $cardClass = 'card-purple';
        $headerClass = '';
        $infoClass = '';
        } elseif ($remainder == 1) {
        $cardClass = 'card-pink';
        $headerClass = 'header-pink';
        $infoClass = 'info-pink';
        } else {
        $cardClass = 'card-blue';
        $headerClass = 'header-blue';
        $infoClass = 'info-blue';
        }
        @endphp

        <div class="col-custom">
            <div class="student-card {{ $cardClass }}">
                <div class="card-header-custom {{ $headerClass }}">
                    <div class="lembaga-name">
                        {{ $kelas->lembaga->nama_lembaga }}
                    </div>
                </div>

                <div class="card-body-custom">
                    <div class="photo-section">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII="
                            class="photo-placeholder" alt="Photo">
                    </div>

                    <div class="student-name">{{ $siswa->nama_siswa }}</div>

                    <div class="info-section">
                        <div class="info-item {{ $infoClass }}">
                            <span class="info-label">NISN</span>: {{ $siswa->nisn }}
                        </div>

                        <div class="info-item {{ $infoClass }}">
                            <span class="info-label">Kelas</span>: {{ $kelas->nama_kelas }}
                        </div>
                    </div>

                    <div class="qr-section">
                        <div class="qr-wrapper">
                            <img src="data:image/png;base64,{{ $qrs[$siswa->id] }}" width="110" height="110" alt="QR Code">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (($index + 1) % 3 == 0)
    </div>
    <div class="row-custom">
        @endif
        @endforeach
    </div>

</body>

</html>