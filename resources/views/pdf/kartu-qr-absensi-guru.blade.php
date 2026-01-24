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
            font-size: 12px;
            padding: 30px;
            background: #f5f5f5;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .card {
            border: 3px solid #2ecc71;
            border-radius: 15px;
            background: white;
            overflow: hidden;
        }

        .card-header {
            background: #2ecc71;
            padding: 20px 15px;
            text-align: center;
            border-bottom: 4px solid #f39c12;
        }

        .lembaga {
            font-weight: bold;
            font-size: 18px;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            line-height: 1.5;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .judul {
            font-weight: bold;
            font-size: 20px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 10px 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .card-body {
            padding: 25px 20px;
            text-align: center;
        }

        .info-section {
            background: #ecf0f1;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            background: white;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            font-size: 14px;
            text-align: left;
            border-left: 4px solid #2ecc71;
            font-weight: 500;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            width: 150px;
        }

        .info-value {
            color: #34495e;
        }

        .qr-section {
            margin: 20px 0;
        }

        .qr-wrapper {
            background: white;
            padding: 15px;
            display: inline-block;
            border-radius: 10px;
            border: 3px solid #2ecc71;
        }

        .qr-wrapper img {
            display: block;
        }

        .scan-instruction {
            margin-top: 15px;
            padding: 12px;
            background: #fff3cd;
            border: 2px solid #f39c12;
            border-radius: 8px;
            color: #856404;
            font-weight: bold;
            font-size: 13px;
        }

        .icon-scan {
            display: inline-block;
            margin-right: 5px;
        }

        @media print {
            body {
                background: white;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="page-title">
            KARTU QR ABSENSI GURU
        </div>

        <div class="card">
            <div class="card-header">
                <div class="lembaga">
                    {{ $jadwal->lembaga->nama_lembaga }}
                </div>

                <div class="judul">
                    ABSENSI GURU
                </div>
            </div>

            <div class="card-body">
                <div class="info-section">
                    <div class="info-item">
                        <span class="info-label">Mata Pelajaran</span>: 
                        <span class="info-value">{{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Jam</span>: 
                        <span class="info-value">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                    </div>
                </div>

                <div class="qr-section">
                    <div class="qr-wrapper">
                        <img src="data:image/png;base64,{{ $qr }}" width="180" height="180" alt="QR Code">
                    </div>
                </div>

                <div class="scan-instruction">
                    <span class="icon-scan">📱</span>
                    Scan QR Code untuk absensi guru
                </div>
            </div>
        </div>
    </div>

</body>

</html>