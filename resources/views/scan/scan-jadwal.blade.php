<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR Absensi</title>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #2563eb;
            --success: #16a34a;
            --danger: #dc2626;
            --bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: #1f2937;
        }

        .container {
            max-width: 480px;
            margin: auto;
            padding: 16px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .08);
            padding: 16px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .header h3 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 4px 0;
            font-size: 14px;
            color: #6b7280;
        }

        #reader-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid var(--primary);
            background: #000;
        }

        #reader {
            width: 100%;
            height: 100%;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99;
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
        }

        .log {
            margin-top: 16px;
            max-height: 220px;
            overflow-y: auto;
            background: #f9fafb;
            border-radius: 12px;
            padding: 12px;
            font-size: 13px;
        }

        .log div {
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .success {
            color: var(--success);
            font-weight: 600;
        }

        .error {
            color: var(--danger);
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 520px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">

            <div class="header">
                <h3>📷 Scan Absensi</h3>
                <p>{{ $jadwal->lembaga->nama_lembaga ?? 'Jadwal' }}</p>
                <p>
                    <!-- {{ $jadwal->kelas->nama_kelas ?? 'Kelas' }} -->
                    <!-- – {{ $jadwal->kelas->tingkat ?? '' }} -->
                </p>
            </div>

            <div id="reader-wrapper">
                <div id="reader"></div>
            </div>

            <div class="log" id="log">
                <div>📌 Hasil scan akan muncul di sini...</div>
            </div>

        </div>
    </div>

    <div class="overlay" id="overlay">
        ⏳ Memproses absensi...
    </div>

    <!-- 🔊 AUDIO -->
    <audio id="sound-success" src="/sounds/success.mp3" preload="auto"></audio>
    <audio id="sound-error" src="/sounds/error.mp3" preload="auto"></audio>

    <script>
        const soundSuccess = document.getElementById('sound-success');
        const soundError = document.getElementById('sound-error');

        function playSuccess() {
            soundSuccess.currentTime = 0;
            soundSuccess.play().catch(() => {});
            if (navigator.vibrate) navigator.vibrate(80);
        }

        function playError() {
            soundError.currentTime = 0;
            soundError.play().catch(() => {});
            if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
        }
        const reader = new Html5Qrcode("reader");
        let locked = false;

        reader.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 220,
                    height: 220
                }
            },
            (qr) => {
                if (locked) return;

                locked = true;
                document.getElementById('overlay').style.display = 'flex';

                fetch("{{ route('scan.jadwal.store', $jadwal) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            qr
                        })
                    })
                    .then(res => res.json())
                    .then(res => {
                        const log = document.getElementById('log');

                        if (res.status === 'success') {
                            playSuccess();
                            log.innerHTML =
                                `<div class="success">✅ ${res.nama} (${res.mode})</div>` +
                                log.innerHTML;
                        } else {
                            playError();
                            log.innerHTML =
                                `<div class="error">❌ ${res.message}</div>` +
                                log.innerHTML;
                        }
                    })
                    .catch(() => {
                        alert('Server error');
                    })
                    .finally(() => {
                        document.getElementById('overlay').style.display = 'none';
                        setTimeout(() => locked = false, 1200);
                    });
            }
        );
    </script>

</body>

</html>