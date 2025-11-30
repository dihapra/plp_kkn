<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLP-KKN UNIMED</title>
    <link rel="icon" href="<?= base_url('assets/images/unimed.ico'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0f5ca8;
            --accent: #22d3ee;
            --ink: #0f172a;
            --muted: #64748b;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f5f7fb;
            color: var(--ink);
        }

        .glass {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 20px 80px rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
        }

        .hero {
            background: radial-gradient(circle at top, #e0f2fe, #dbeafe 45%, #fff 80%);
            padding: 140px 0 100px;
        }

        .hero-illustration {
            position: relative;
            min-height: 320px;
        }

        .hero-illustration::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.08;
        }

        .feature-card {
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        .timeline {
            border-left: 2px solid rgba(14, 165, 233, 0.2);
        }

        .timeline-step {
            position: relative;
            padding-left: 30px;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 12px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #fff;
            border: 4px solid var(--primary);
        }

        .cta-card {
            border-radius: 18px;
            background: linear-gradient(135deg, #0ea5e9, #0f5ca8);
            color: #fff;
            padding: 60px;
        }

        .navbar {
            padding: 18px 0;
            transition: background 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
        }

        .pill {
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            background: rgba(14, 165, 233, 0.15);
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: linear-gradient(120deg, #0ea5e9, #0f5ca8);
            border: none;
            padding: 12px 28px;
            font-weight: 600;
            border-radius: 12px;
        }

        .btn-login {
            padding: 10px 22px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="<?= base_url('/'); ?>">
                <img src="<?= base_url('assets/images/logo-unimed.png'); ?>" alt="UNIMED" height="40" />
                <span>PLP-KKN</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#alur">Alur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#panduan">Panduan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                <a href="<?= base_url('login'); ?>" class="btn btn-primary btn-login">Masuk Sistem</a>
            </div>
        </div>
    </nav>

    <header class="hero" id="home">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <div class="pill mb-3">
                        <i class="bi bi-stars"></i> Sistem terpadu PLP & KKN UNIMED
                    </div>
                    <h1 class="display-5 fw-bold mb-3">Kolaborasi kampus dan sekolah dalam satu platform.</h1>
                    <p class="lead text-muted mb-4">Pantau penempatan mahasiswa, bimbingan dosen, hingga progres kegiatan lapangan dengan tampilan baru yang fokus pada pengalaman pengguna.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= base_url('login'); ?>" class="btn btn-primary btn-lg">Mulai Sekarang</a>
                        <a href="#layanan" class="btn btn-outline-dark btn-lg">Lihat Fitur</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration glass p-4">
                        <div class="d-flex flex-column gap-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Status platform</p>
                                    <h4 class="fw-bold">Monitoring real-time</h4>
                                </div>
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-shield-check"></i> Stabil
                                </span>
                            </div>
                            <p class="text-muted mb-0">Setiap modul PLP-KKN terkoneksi pada satu dashboard. Administrasi mahasiswa, sekolah, dan pembimbing dapat dipantau tanpa perlu laporan manual.</p>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                    <span>Penempatan & logbook diperbarui otomatis.</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                    <span>Laporan siap unduh dalam format resmi.</span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle-fill text-primary"></i>
                                    <span>Integrasi antar peran dalam satu akun.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="layanan" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="pill mb-2"><i class="bi bi-palette"></i> Apa yang berubah?</span>
                <h2 class="fw-bold">Satu portal, empat pengalaman.</h2>
                <p class="text-muted">Mahasiswa, dosen, kaprodi, dan sekolah kini terhubung dengan antarmuka baru yang intuitif.</p>
            </div>
            <div class="row g-4">
                <?php
                $features = [
                    ['icon' => 'bi-people', 'title' => 'Manajemen Peserta', 'desc' => 'Pantau status mahasiswa dari registrasi hingga penilaian akhir.'],
                    ['icon' => 'bi-mortarboard', 'title' => 'Bimbingan Terpusat', 'desc' => 'Logbook, jadwal, dan supervisi tersinkron untuk dosen pembimbing.'],
                    ['icon' => 'bi-building', 'title' => 'Profil Sekolah', 'desc' => 'Kuota, kebutuhan kompetensi, dan histori kolaborasi selalu terbarui.'],
                    ['icon' => 'bi-graph-up', 'title' => 'Insight Real-time', 'desc' => 'Dashboard ringkas untuk pimpinan mengambil keputusan cepat.'],
                ];
                foreach ($features as $feature): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card h-100 p-4">
                            <div class="rounded-circle bg-light d-inline-flex p-3 mb-3 text-primary fs-4">
                                <i class="bi <?= $feature['icon']; ?>"></i>
                            </div>
                            <h5 class="fw-semibold"><?= $feature['title']; ?></h5>
                            <p class="text-muted mb-0"><?= $feature['desc']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="alur" class="py-5 bg-light">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <span class="pill mb-2"><i class="bi bi-compass"></i> Alur ringkas</span>
                    <h2 class="fw-bold mb-3">Masuk, pilih peran, langsung produktif.</h2>
                    <p class="text-muted">Kami merangkum seluruh langkah ke dalam alur sederhana agar setiap aktor cepat memahami tugas dan tenggatnya.</p>
                </div>
                <div class="col-lg-7">
                    <div class="timeline">
                        <?php
                        $steps = [
                            ['title' => 'Login & Verifikasi', 'desc' => 'Gunakan akun kampus atau daftar sebagai sekolah mitra.'],
                            ['title' => 'Penempatan & Bimbingan', 'desc' => 'Sistem menampilkan rekomendasi sekolah, kuota, dan pembimbing.'],
                            ['title' => 'Pelaksanaan Lapangan', 'desc' => 'Log aktivitas, presensi, dan laporan otomatis tersimpan.'],
                            ['title' => 'Evaluasi & Arsip', 'desc' => 'Rekap nilai, sertifikat, dan berita acara siap unduh.'],
                        ];
                        foreach ($steps as $step): ?>
                            <div class="timeline-step mb-4">
                                <h5 class="fw-semibold mb-1"><?= $step['title']; ?></h5>
                                <p class="text-muted mb-0"><?= $step['desc']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="panduan" class="py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="pill mb-2"><i class="bi bi-download"></i> Dokumen terbaru</span>
                    <h2 class="fw-bold mb-3">Panduan & sumber belajar.</h2>
                    <p class="text-muted">Kami menyertakan dokumen resmi dan video tutorial agar setiap peran dapat menuntaskan kewajiban dengan percaya diri.</p>
                </div>
                <div class="col-lg-6">
                    <div class="glass p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-0 text-muted">Panduan umum</p>
                                <h5 class="fw-semibold mb-0">Surat Pernyataan Mahasiswa</h5>
                            </div>
                            <a href="<?= base_url('file/assets/file/Surat Pernyataan.docx'); ?>" class="btn btn-outline-primary" download>
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                        <div class="border-top pt-3 mt-3 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 text-muted">Video tutorial</p>
                                <h5 class="fw-semibold mb-0">e-PLP Mahasiswa</h5>
                            </div>
                            <a href="https://www.youtube.com/watch?v=0lecQo1xVyQ" target="_blank" class="btn btn-outline-dark">
                                <i class="bi bi-play-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="py-5">
        <div class="container">
            <div class="cta-card">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <p class="text-uppercase mb-2 fw-semibold">Siap memulai?</p>
                        <h2 class="fw-bold">Implementasi PLP-KKN kini jauh lebih ringan.</h2>
                        <p class="lead mb-0">Hubungi LPPM UNIMED untuk aktivasi institusi atau konsultasi integrasi data.</p>
                    </div>
                    <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                        <a href="https://lppm.unimed.ac.id/" target="_blank" class="btn btn-light btn-lg text-primary fw-semibold">Hubungi LPPM</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center text-muted">
        <small>&copy; <?= date('Y'); ?> PLP-KKN UNIMED. Seluruh hak cipta dilindungi.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    </script>
</body>

</html>
