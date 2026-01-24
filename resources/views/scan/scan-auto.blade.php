<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR Absensi</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { font-family: system-ui, sans-serif; margin: 0; padding: 16px; background: #f8fafc; }
        .container { max-width: 480px; margin: auto; }
        #reader-wrapper { position: relative; width: 100%; border: 2px solid #2563eb; border-radius: 14px; overflow: hidden; aspect-ratio: 1/1; background: #000; display: none; margin-top: 16px; }
        #reader { width: 100%; height: 100%; }
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            color: #fff; display: flex;
            align-items: center; justify-content: center;
            font-size: 18px; font-weight: bold;
            display: none;
            border-radius: 14px;
            z-index: 10;
        }
        .log { margin-top: 16px; max-height: 220px; overflow-y: auto; background: #fff; padding: 12px; border-radius: 12px; font-size: 14px; }
        select { width: 100%; padding: 8px; font-size: 16px; border-radius: 8px; margin-top: 8px; }
        .today-jadwal { margin-top: 16px; background: #fff; padding: 12px; border-radius: 12px; font-size: 14px; }
        .today-jadwal div { margin-bottom: 6px; }
    </style>
</head>
<body>

<div class="container">
    <h3 style="text-align:center;">📷 Scan QR Absensi</h3>

    <label for="jadwal">Pilih Jadwal:</label>
    <select id="jadwal">
        <option value="">-- Pilih Jadwal --</option>
        @foreach($jadwalsToday as $jadwal)
            <option value="{{ $jadwal->id }}">
                {{ $jadwal->lembaga->nama_lembaga ?? '-' }} |
                {{ $jadwal->kelas->nama_kelas ?? '-' }} |
                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
            </option>
        @endforeach
    </select>

    <div id="reader-wrapper">
        <div id="reader"></div>
        <div class="overlay" id="overlay">⏳ Memproses...</div>
    </div>

    <div class="log" id="log">
        <div>📌 Hasil scan akan muncul di sini...</div>
    </div>

    <div class="today-jadwal">
        <h4>📅 Jadwal Hari Ini</h4>
        @forelse($jadwalsToday as $jadwal)
            <div>
                {{ $jadwal->lembaga->nama_lembaga ?? '-' }} |
                {{ $jadwal->kelas->nama_kelas ?? '-' }} |
                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
            </div>
        @empty
            <div>❌ Tidak ada jadwal hari ini</div>
        @endforelse
    </div>
</div>

<script>
let reader;
let isProcessing = false;
const readerWrapper = document.getElementById('reader-wrapper');
const selectJadwal = document.getElementById('jadwal');
const overlay = document.getElementById('overlay');

selectJadwal.addEventListener('change', function() {
    const jadwalId = this.value;

    if (!jadwalId) {
        readerWrapper.style.display = 'none';
        if(reader) reader.stop();
        return;
    }

    readerWrapper.style.display = 'block';

    if(reader) {
        reader.stop().then(() => initScanner(jadwalId));
    } else {
        initScanner(jadwalId);
    }
});

function initScanner(jadwalId) {
    reader = new Html5Qrcode("reader");

    reader.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 220, height: 220 } },
        (qr) => {
            if(isProcessing) return;
            isProcessing = true;
            overlay.style.display = 'flex';

            const log = document.getElementById('log');
            log.innerHTML = `<div>⏳ Memproses QR: ${qr}</div>` + log.innerHTML;

            fetch(`/scan/jadwal/${jadwalId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ qr })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    log.innerHTML = `<div style="color:green;">✅ ${data.nama} (Kelas: ${data.kelas}) berhasil diabsen</div>` + log.innerHTML;
                } else {
                    log.innerHTML = `<div style="color:red;">❌ ${data.message}</div>` + log.innerHTML;
                }
            })
            .catch(err => {
                log.innerHTML = `<div style="color:red;">❌ Terjadi kesalahan</div>` + log.innerHTML;
                console.error(err);
            })
            .finally(() => {
                isProcessing = false;
                overlay.style.display = 'none';
            });
        },
        (error) => console.warn('QR scan error', error)
    );
}
</script>

</body>
</html>
