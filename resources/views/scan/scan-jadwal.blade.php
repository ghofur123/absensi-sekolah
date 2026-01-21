<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR Absensi</title>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            font-family: Arial;
            text-align: center;
        }

        #reader {
            width: 320px;
            margin: auto;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.85);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99;
            font-size: 18px;
            font-weight: bold;
        }

        .log {
            margin-top: 15px;
            max-height: 180px;
            overflow-y: auto;
            text-align: left;
            width: 320px;
            margin-inline: auto;
            font-size: 13px;
        }

        .success { color: green; }
        .error { color: red; }
    </style>
</head>

<body>

<h3>Scan Absensi</h3>
<p>{{ $jadwal->lembaga->nama_lembaga ?? 'Jadwal' }}</p>
<p>{{ $jadwal->kelas->nama_kelas ?? 'Jadwal' }}</p>
<p>{{ $jadwal->kelas->tingkat ?? 'Jadwal' }}</p>

<div id="reader"></div>

<div class="overlay" id="overlay">
    ⏳ Memproses...
</div>

<div class="log" id="log"></div>

<script>
    const reader = new Html5Qrcode("reader");
    let locked = false;

    reader.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: 250 },
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
                body: JSON.stringify({ qr })
            })
            .then(res => res.json())
            .then(res => {
                const log = document.getElementById('log');

                if (res.status === 'success') {
                    log.innerHTML =
                        `<div class="success">✅ ${res.nama} (${res.mode})</div>` +
                        log.innerHTML;
                } else {
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
