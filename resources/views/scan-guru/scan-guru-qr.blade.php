<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Scan QR Absensi Guru</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 16px;
            text-align: center;
        }

        h3 {
            margin-bottom: 12px;
        }

        .scanner-wrapper {
            position: relative;
            max-width: 360px;
            margin: auto;
        }

        #reader {
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            border: 4px solid #ddd;
            background: #000;
            transition: all .3s ease;
        }

        #reader.processing {
            border-color: #16a34a;
            box-shadow: 0 0 18px rgba(22, 163, 74, 0.9);
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            display: none;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            border-radius: 14px;
        }

        .overlay.active {
            display: flex;
        }

        #message {
            display: none;
            max-width: 360px;
            margin: 12px auto 0;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
        }

        #message.success {
            background: #ecfdf5;
            border-left: 6px solid #16a34a;
            color: #065f46;
        }

        #message.error {
            background: #fef2f2;
            border-left: 6px solid #dc2626;
            color: #7f1d1d;
        }

        #log {
            margin-top: 10px;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }

        #log p {
            background: #fff;
            border-left: 6px solid #16a34a;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <h3>📷 Scan QR Jadwal Guru</h3>

    <div class="scanner-wrapper">
        <div id="reader"></div>

        <div id="overlay" class="overlay">
            ⏳ Memproses absensi...
        </div>
    </div>

    <!-- 🔔 PESAN DI BAWAH KAMERA -->
    <div id="message"></div>

    <!-- LOG RIWAYAT -->
    <div id="log"></div>

    <script>
        const scanner = new Html5Qrcode("reader");
        let processing = false;

        const reader = document.getElementById('reader');
        const overlay = document.getElementById('overlay');
        const messageBox = document.getElementById('message');

        function showMessage(text, type = 'error') {
            messageBox.className = '';
            messageBox.classList.add(type);
            messageBox.innerText = text;
            messageBox.style.display = 'block';
        }

        function clearMessage() {
            messageBox.className = '';
            messageBox.innerText = '';
            messageBox.style.display = 'none';
        }

        scanner.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 220
            },
            (qr) => {
                if (processing) return;

                processing = true;
                clearMessage();

                reader.classList.add('processing');
                overlay.classList.add('active');

                navigator.geolocation.getCurrentPosition((pos) => {
                    fetch("{{ route('scan.guru.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify({
                                qr: qr,
                                latitude: pos.coords.latitude,
                                longitude: pos.coords.longitude
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status === 'success') {
                                showMessage(
                                    `✅ ${res.nama} | ${res.status_masuk.replace('_', ' ')}`,
                                    'success'
                                );

                                document.getElementById('log').innerHTML =
                                    `<p>
                                        <b>${res.nama}</b><br>
                                        Status: ${res.status_masuk}<br>
                                        Jarak: ${res.jarak} m
                                    </p>` + document.getElementById('log').innerHTML;

                            } else {
                                showMessage(`❌ ${res.message}`, 'error');
                            }
                        })
                        .catch(() => {
                            showMessage('❌ Gagal mengirim data absensi', 'error');
                        })
                        .finally(() => {
                            setTimeout(() => {
                                processing = false;
                                reader.classList.remove('processing');
                                overlay.classList.remove('active');
                            }, 3000);
                        });
                });
            }
        );
    </script>

</body>

</html>