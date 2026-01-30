<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Sekolah QR - Sekolah Prestasi Prima</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #1abc9c;
            --light-color: #f8f9fa;
            --dark-color: #2c3e50;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --success-color: #27ae60;
            --whatsapp-color: #25D366;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
        }

        .navbar-brand i {
            color: var(--accent-color);
        }

        .navbar {
            transition: all 0.3s ease;
            padding: 15px 0;
        }

        .navbar-scrolled {
            padding: 8px 0;
            background-color: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 160px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZGVmcz48cGF0dGVybiBpZD0iZ3JpZCIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIj48cGF0aCBkPSJNIDQwIDAgTCAwIDAgMCA0MCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=');
        }

        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 600px;
        }

        .btn-primary-custom {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            padding: 14px 35px;
            font-weight: 500;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-primary-custom:hover {
            background-color: #16a085;
            border-color: #16a085;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-whatsapp {
            background-color: var(--whatsapp-color);
            border-color: var(--whatsapp-color);
            color: white;
        }

        .btn-whatsapp:hover {
            background-color: #1da851;
            border-color: #1da851;
            color: white;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            position: relative;
            margin-bottom: 3.5rem;
            text-align: center;
        }

        .section-title::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 5px;
            background-color: var(--accent-color);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 3px;
        }

        .section-subtitle {
            text-align: center;
            color: #666;
            max-width: 700px;
            margin: 0 auto 4rem;
            font-size: 1.1rem;
        }

        .qr-system-section {
            background-color: #f8f9fa;
            padding: 100px 0;
        }

        .system-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .system-card:hover {
            transform: scale(1.02);
        }

        .system-card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .system-card-body {
            padding: 30px;
        }

        .system-step {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }

        .system-step:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background-color: var(--accent-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.3rem;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .step-content h5 {
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .analytics-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .analytics-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            height: 100%;
            transition: all 0.3s ease;
        }

        .analytics-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .chart-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(to right, #f0f7ff, #e6f3ff);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-weight: bold;
            margin-top: 20px;
        }

        .whatsapp-integration {
            background-color: #f0f9f6;
            border-left: 5px solid var(--whatsapp-color);
            padding: 25px;
            border-radius: 0 10px 10px 0;
            margin-top: 30px;
        }

        .whatsapp-icon {
            color: var(--whatsapp-color);
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a2530 100%);
            color: white;
            padding: 100px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .stat-label {
            font-size: 1.2rem;
            color: #ddd;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .benefit-list {
            list-style: none;
            padding-left: 0;
        }

        .benefit-list li {
            padding: 10px 0;
            padding-left: 35px;
            position: relative;
        }

        .benefit-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent-color);
            font-weight: bold;
            font-size: 1.2rem;
        }

        .demo-section {
            padding: 100px 0;
            background-color: white;
        }

        .demo-card {
            border: 2px dashed #ddd;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .demo-card:hover {
            border-color: var(--accent-color);
            background-color: #f9f9f9;
        }

        .qr-code-placeholder {
            width: 200px;
            height: 200px;
            margin: 0 auto 30px;
            background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .qr-code-placeholder:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(90deg, transparent 50%, rgba(255, 255, 255, .2) 50%),
                linear-gradient(transparent 50%, rgba(255, 255, 255, .2) 50%);
            background-size: 20px 20px;
        }

        .qr-code-placeholder:after {
            content: 'QR CODE';
            position: absolute;
            font-weight: bold;
            color: #999;
            font-size: 1.2rem;
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 70px 0 30px;
        }

        .footer h5 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }

        .footer a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-color);
        }

        .social-icons a {
            display: inline-block;
            width: 45px;
            height: 45px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 45px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--accent-color);
            transform: translateY(-5px);
        }

        .copyright {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 25px 0;
            margin-top: 50px;
            text-align: center;
            color: #aaa;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1.1rem;
            }

            .stat-number {
                font-size: 2.8rem;
            }

            .system-step {
                flex-direction: column;
                text-align: center;
            }

            .step-number {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-qrcode me-2"></i>
                Absensi<span style="color: var(--accent-color);">QR</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#qr-system">Sistem QR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#analytics">Analitik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#demo">Demo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-primary-custom" href="#demo">Coba Demo</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="animate__animated animate__fadeInDown">Sistem Absensi QR Modern untuk Sekolah</h1>
                    <p class="animate__animated animate__fadeIn animate__delay-1s">Kelola kehadiran siswa dengan teknologi QR Code yang terintegrasi WhatsApp. Notifikasi langsung ke orang tua dan laporan analitik lengkap untuk pengambilan keputusan.</p>
                    <div class="mt-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="#qr-system" class="btn btn-primary-custom me-3">Pelajari Sistem</a>
                        <a href="#demo" class="btn btn-outline-light">Lihat Demo</a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="text-center">
                        <div class="qr-code-placeholder animate__animated animate__pulse animate__infinite animate__slower"></div>
                        <p class="text-white mt-3">Scan QR untuk absensi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- QR System Section -->
    <section id="qr-system" class="qr-system-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Sistem Absensi QR Code Terintegrasi</h2>
                    <p class="section-subtitle">Teknologi absensi modern menggunakan QR Code unik setiap siswa yang terintegrasi dengan WhatsApp untuk notifikasi real-time kepada orang tua dan grup sekolah.</p>
                </div>
            </div>

            <!-- Sistem 1: QR Code ke Orang Tua -->
            <div class="row mb-5">
                <div class="col-12 mb-4">
                    <div class="system-card">
                        <div class="system-card-header">
                            <h3 class="mb-0"><i class="fas fa-user-circle me-2"></i> Sistem 1: QR Code ke Orang Tua via WhatsApp</h3>
                        </div>
                        <div class="system-card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="system-step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h5>QR Code Personal Siswa</h5>
                                            <p>Setiap siswa memiliki QR Code unik yang dicetak pada kartu pelajar.</p>
                                        </div>
                                    </div>
                                    <div class="system-step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h5>Scan QR di Sekolah</h5>
                                            <p>Siswa scan QR Code mereka di terminal absensi sekolah saat jadwal kegiatan.</p>
                                        </div>
                                    </div>
                                    <div class="system-step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h5>Notifikasi Real-time ke WhatsApp</h5>
                                            <p>Sistem langsung mengirim notifikasi ke WhatsApp orang tua dengan detail waktu absen dan status kehadiran.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="whatsapp-integration">
                                        <div class="whatsapp-icon">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <h4>Integrasi WhatsApp</h4>
                                        <p>Notifikasi dikirim secara otomatis melalui WhatsApp API ke nomor orang tua yang terdaftar.</p>
                                        <ul class="benefit-list">
                                            <li>Notifikasi langsung saat anak melaksanakan kegiatan sekolah</li>
                                            <li>Peringatan jika siswa terlambat</li>
                                            <li>Informasi terkait dengan kegiatan sekolah</li>
                                            <li>Laporan kehadiran mingguan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistem 2: QR Code dengan Lokasi ke Grup WA -->
            <div class="row">
                <div class="col-12">
                    <div class="system-card">
                        <div class="system-card-header">
                            <h3 class="mb-0"><i class="fas fa-users me-2"></i> Sistem 2: QR Code dengan Geolokasi ke Grup WhatsApp</h3>
                        </div>
                        <div class="system-card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="system-step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h5>QR Code dengan Verifikasi Lokasi</h5>
                                            <p>QR Code hanya dapat dipindai dalam radius lokasi sekolah, mencegah absensi palsu dari luar area.</p>
                                        </div>
                                    </div>
                                    <div class="system-step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h5>Absensi via Aplikasi Mobile</h5>
                                            <p>Guru dapat memindai QR Code siswa melalui aplikasi mobile dengan verifikasi lokasi GPS.</p>
                                        </div>
                                    </div>
                                    <div class="system-step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h5>Broadcast ke Grup WhatsApp</h5>
                                            <p>Data absensi dikirim ke grup WhatsApp kelas atau sekolah yang sudah terintegrasi dengan sistem.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="whatsapp-integration">
                                        <div class="whatsapp-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <h4>Grup WhatsApp Terintegrasi</h4>
                                        <p>Sistem terhubung dengan grup WhatsApp resmi sekolah untuk distribusi informasi kehadiran.</p>
                                        <ul class="benefit-list">
                                            <li>Notifikasi ke grup WhatsApp kelas</li>
                                            <li>Laporan harian ke grup guru</li>
                                            <li>Pengingat untuk siswa yang belum absen</li>
                                            <li>Koordinasi cepat antara guru dan admin</li>
                                        </ul>
                                        <div class="mt-3">
                                            <span class="badge bg-secondary me-2">Grup Kelas</span>
                                            <span class="badge bg-secondary me-2">Grup Guru</span>
                                            <span class="badge bg-secondary">Grup Admin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Akurasi Absensi</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">&lt; 2 detik</div>
                        <div class="stat-label">Proses Scan QR</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Integrasi WhatsApp</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Monitoring System</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Analytics Section -->
    <section id="analytics" class="analytics-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Analitik Absensi Lengkap</h2>
                    <p class="section-subtitle">Sistem analitik canggih untuk memantau dan menganalisis pola kehadiran siswa dan guru secara real-time.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="analytics-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Analisis Pola Kehadiran Siswa</h4>
                        <p>Pantau pola kehadiran siswa per kelas, per mata pelajaran, dan per periode waktu. Identifikasi tren dan pola ketidakhadiran.</p>
                        <div class="chart-placeholder">
                            Grafik Pola Kehadiran
                        </div>
                        <ul class="benefit-list mt-3">
                            <li>Tingkat kehadiran per kelas</li>
                            <li>Siswa dengan catatan terlambat</li>
                            <li>Pola ketidakhadiran berulang</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="analytics-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4>Monitoring Kehadiran Guru</h4>
                        <p>Pantau kehadiran dan ketepatan waktu guru dalam mengajar. Analisis produktivitas dan konsistensi tenaga pengajar.</p>
                        <div class="chart-placeholder">
                            Grafik Kehadiran Guru
                        </div>
                        <ul class="benefit-list mt-3">
                            <li>Rekap kehadiran guru</li>
                            <li>Keterlambatan mengajar</li>
                            <li>Jam mengajar efektif</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="analytics-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4>Laporan & Ekspor Data</h4>
                        <p>Hasilkan laporan lengkap dengan berbagai format (PDF, Excel) untuk keperluan administrasi, evaluasi, dan pelaporan.</p>
                        <div class="chart-placeholder">
                            Dashboard Laporan
                        </div>
                        <ul class="benefit-list mt-3">
                            <li>Laporan bulanan/tahunan</li>
                            <li>Ekspor data ke Excel</li>
                            <li>Dashboard interaktif</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="analytics-card">
                        <h3 class="text-center mb-4">Fitur Analitik Lanjutan</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <h5><i class="fas fa-bell text-warning me-2"></i> Sistem Peringatan Dini</h5>
                                <p>Notifikasi otomatis ketika siswa mendekati batas ketidakhadiran yang diizinkan.</p>
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fas fa-map-marker-alt text-danger me-2"></i> Heatmap Kehadiran</h5>
                                <p>Visualisasi wilayah dengan tingkat kehadiran terendah untuk intervensi tepat sasaran.</p>
                            </div>
                            <div class="col-md-4">
                                <h5><i class="fas fa-project-diagram text-success me-2"></i> Prediktif Analytics</h5>
                                <p>Prediksi pola kehadiran berdasarkan data historis untuk perencanaan yang lebih baik.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="demo-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">Coba Sistem Absensi QR</h2>
                    <p class="section-subtitle">Lihat bagaimana sistem absensi QR Code bekerja dalam simulasi interaktif berikut.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="demo-card">
                        <h4>Simulasi Scan QR Code</h4>
                        <p>Pindai QR Code siswa untuk melihat proses absensi</p>
                        <div class="qr-code-placeholder mb-4">
                            <!-- QR Code placeholder -->
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary-custom btn-simulate-scan">Simulasikan Scan QR</button>
                        </div>
                        <div class="mt-3 alert alert-success d-none" id="scan-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Absensi berhasil!</strong> Notifikasi telah dikirim ke WhatsApp orang tua.
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="demo-card">
                        <h4>Contoh Notifikasi WhatsApp</h4>
                        <p>Seperti inilah notifikasi yang diterima orang tua</p>
                        <div class="whatsapp-integration mt-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="whatsapp-icon me-3">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Notifikasi Absensi</h5>
                                    <small>Dari: Sistem Absensi Sekolah</small>
                                </div>
                            </div>
                            <div class="alert alert-light">
                                <p class="mb-1"><strong>Ananda Rizki Pratama</strong></p>
                                <p class="mb-1">Kelas: XII IPA 2</p>
                                <p class="mb-1">Absensi: <span class="text-success">Hadir</span></p>
                                <p class="mb-1">Waktu: 07:15 WIB</p>
                                <p class="mb-0">Tanggal: 15 Oktober 2023</p>
                            </div>
                            <p class="text-muted small">Notifikasi ini dikirim otomatis saat siswa melakukan absensi di sekolah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Tentang AbsensiQR</h5>
                    <p>Sistem absensi digital berbasis QR Code terintegrasi WhatsApp untuk sekolah modern. Meningkatkan efisiensi, transparansi, dan komunikasi antara sekolah dan orang tua.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Menu</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#qr-system">Sistem QR</a></li>
                        <li><a href="#analytics">Analitik</a></li>
                        <li><a href="#demo">Demo</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Sistem</h5>
                    <ul class="list-unstyled">
                        <li><a href="#qr-system">QR ke Orang Tua</a></li>
                        <li><a href="#qr-system">QR dengan Lokasi</a></li>
                        <li><a href="#analytics">Analisis Siswa</a></li>
                        <li><a href="#analytics">Monitoring Guru</a></li>
                        <li><a href="#demo">Coba Demo</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5>Kontak & Dukungan</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Jl. Pendidikan No. 123, Jakarta</p>
                    <p><i class="fas fa-phone me-2"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope me-2"></i> info@absensiqr.id</p>
                    <p><i class="fab fa-whatsapp me-2"></i> +62 812-3456-7890</p>
                    <p><i class="fas fa-clock me-2"></i> Senin-Jumat: 08.00-17.00 WIB</p>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Sistem AbsensiQR. Hak cipta dilindungi undang-undang. | Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk pendidikan Indonesia</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth scroll untuk navigasi
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Animasi navbar saat scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Simulasi scan QR Code
        document.querySelector('.btn-simulate-scan').addEventListener('click', function() {
            const successAlert = document.getElementById('scan-success');
            this.textContent = 'Memindai...';
            this.disabled = true;

            // Simulasi proses scanning
            setTimeout(() => {
                successAlert.classList.remove('d-none');
                successAlert.classList.add('animate__animated', 'animate__fadeIn');
                this.textContent = 'Scan QR Lagi';
                this.disabled = false;

                // Hilangkan alert setelah 5 detik
                setTimeout(() => {
                    successAlert.classList.add('d-none');
                }, 5000);
            }, 1500);
        });

        // Animasi saat scroll
        function animateOnScroll() {
            const elements = document.querySelectorAll('.system-card, .analytics-card, .demo-card');

            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.2;

                if (elementPosition < screenPosition) {
                    element.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }

        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);

        // Inisialisasi tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>