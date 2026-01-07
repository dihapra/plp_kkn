<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | PLP-KKN UNIMED</title>
    <link rel="icon" href="<?= base_url('assets/images/unimed.ico'); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #0ea5e9 45%, #67e8f9 100%);
            color: #0f172a;
            min-height: 100vh;
            margin: 0;
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .promo-panel {
            color: #fff;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .promo-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://www.transparenttextures.com/patterns/3px-tile.png');
            opacity: 0.15;
        }

        .promo-panel>* {
            position: relative;
            z-index: 2;
        }

        .floating-card {
            padding: 24px;
            border-radius: 14px;
            background: rgba(15, 23, 42, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(12px);
        }

        .login-panel {
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.12);
        }

        .btn-primary {
            background: linear-gradient(120deg, #0ea5e9, #0f5ca8);
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, #0f5ca8, #0ea5e9);
        }

        .form-control,
        .input-group-text {
            border-radius: 10px;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .btn-disabled {
            color: #94a3b8 !important;
            border-color: #cbd5f5 !important;
            background: #fff !important;
            pointer-events: none;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="auth-page">
        <div class="promo-panel d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="<?= base_url('assets/images/logo-unimed.png'); ?>" alt="UNIMED" height="48">
                    <div>
                        <p class="mb-0 text-uppercase opacity-75 small">Universitas Negeri Medan</p>
                        <h4 class="fw-semibold mb-0">PLP-KKN</h4>
                    </div>
                </div>
                <h1 class="display-5 fw-bold mb-3">Selamat datang kembali.</h1>
                <p class="lead mb-4">Versi baru PLP-KKN menghadirkan antarmuka yang seragam lintas peran, lengkap dengan
                    dashboard, logbook, dan penempatan yang lebih cerdas.</p>
                <div class="floating-card">
                    <p class="text-uppercase small text-white-50 mb-2">Mengapa versi baru?</p>
                    <ul class="mb-0 ps-3">
                        <li class="mb-2">UI seragam untuk dosen, mahasiswa, kaprodi, dan sekolah.</li>
                        <li class="mb-2">Aktivitas lapangan dan logbook langsung sinkron ke dashboard.</li>
                        <li class="mb-0">Laporan & arsip siap unduh tanpa input manual berulang.</li>
                    </ul>
                </div>
            </div>
            <p class="text-white-50 small mb-0">&copy; <?= date('Y'); ?> PLP-KKN UNIMED. Semua hak dilindungi.</p>
        </div>

        <div class="login-panel">
            <?php
            $error_message = $this->session->flashdata('error');
            $success_message = $this->session->flashdata('success');
            ?>
            <div class="form-card">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger mb-3"><?= $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success mb-3"><?= $success_message; ?></div>
                <?php endif; ?>
                <p class="text-uppercase small text-muted mb-1">Masuk ke akun Anda</p>
                <h2 class="fw-semibold mb-4">Portal PLP-KKN UNIMED</h2>
                <form action="<?= base_url('login'); ?>" method="POST" id="loginForm">
                    <div class="mb-3">
                        <label for="identifier" class="form-label">Alamat Email</label>
                        <input type="email"
                            class="form-control <?php echo (form_error('identifier')) ? 'is-invalid' : ''; ?>"
                            id="identifier" name="identifier" placeholder="nama@email.com"
                            value="<?php echo set_value('identifier'); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('identifier'); ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control <?php echo (form_error('password')) ? 'is-invalid' : ''; ?>"
                                id="password" name="password" placeholder="Masukkan kata sandi" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                            <div class="invalid-feedback">
                                <?php echo form_error('password'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
                    </div>
                </form>
                <div class="divider my-4">Sudah punya akun di PLP 2?</div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-disabled disabled" disabled
                        aria-disabled="true">
                        Daftar Ulang Guru Pamong (Segera)
                    </button>
                    <button type="button" class="btn btn-outline-success btn-disabled disabled" disabled
                        aria-disabled="true">
                        Daftar Ulang Kepala Sekolah (Segera)
                    </button>
                </div>
                <div class="divider my-4">Belum punya akun?</div>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-disabled disabled" disabled
                        aria-disabled="true">
                        Daftar sebagai Guru Pamong (Segera)
                    </button>
                    <button type="button" class="btn btn-outline-success btn-disabled disabled" disabled
                        aria-disabled="true">
                        Daftar sebagai Kepala Sekolah (Segera)
                    </button>
                    <a href="<?= base_url('register/mahasiswa'); ?>" class="btn btn-success text-white">
                        Daftar sebagai Mahasiswa PLP
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>