<div
    x-data="qrScanner({{ $jadwal->id }})"
    x-init="init()"
    class="space-y-3"
>
    <video id="preview" class="w-full rounded-lg border"></video>

    <p class="text-sm text-gray-600">
        Arahkan kamera ke QR Code siswa
    </p>

    <p x-text="message" class="text-green-600 font-semibold"></p>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
function qrScanner(jadwalId) {
    return {
        html5QrCode: null,
        message: '',

        init() {
            this.html5QrCode = new Html5Qrcode("preview");

            Html5Qrcode.getCameras().then(cameras => {
                if (cameras.length > 0) {
                    this.html5QrCode.start(
                        cameras[0].id,
                        { fps: 10, qrbox: 250 },
                        (decodedText) => this.onScan(decodedText)
                    );
                }
            });
        },

        onScan(code) {
            this.html5QrCode.stop();
            this.message = 'QR terdeteksi, menyimpan absensi...';

            Livewire.emit('absensi-scan', {
                jadwal_id: jadwalId,
                kode: code
            });
        }
    }
}
</script>
