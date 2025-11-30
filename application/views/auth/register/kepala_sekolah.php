<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kepala Sekolah | PLP-KKN UNIMED</title>
    <link rel="icon" href="<?= base_url('assets/images/unimed.ico') ?>" type="image/x-icon">
    <link href="<?= base_url('assets/libs/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const site_url = "<?= base_url() ?>"
    </script>
    <style>
        :root {
            --gradient: linear-gradient(135deg, #10b981, #047857);
            --ink: #0f172a;
            --muted: #64748b;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f4f7fb;
            color: var(--ink);
            min-height: 100vh;
            margin: 0;
        }

        .register-wrapper {
            min-height: 100vh;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .hero-panel {
            background: var(--gradient);
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .hero-panel .floating-note {
            background: rgba(15, 23, 42, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 20px;
            backdrop-filter: blur(10px);
        }

        .form-panel {
            background: #fff;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            width: 100%;
            max-width: 520px;
        }

        .step-indicators {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-tile {
            text-align: center;
        }

        .step-tile .circle {
            width: 42px;
            height: 42px;
            line-height: 42px;
            margin: 0 auto;
            border-radius: 50%;
            font-weight: 600;
            background: #cce7dc;
            color: #065f46;
            transition: 0.2s ease;
        }

        .step-tile.active .circle {
            background: #047857;
            color: #fff;
            transform: scale(1.05);
        }

        .step-tile .label {
            margin-top: 0.35rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
        }

        .step-tile.active .label {
            color: var(--ink);
        }

        .btn-primary,
        .btn-secondary {
            border-radius: 10px;
            padding: 0.65rem 1.5rem;
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="register-wrapper">
        <aside class="hero-panel">
            <div>
                <p class="text-uppercase small opacity-75 mb-2">Kemitraan Sekolah</p>
                <h1 class="display-6 fw-bold mb-3">Dokumentasikan progres PLP-KKN langsung dari sekolah Anda.</h1>
                <p class="lead">Melalui akun kepala sekolah, Anda dapat memvalidasi penempatan, memonitor logbook guru pembimbing, serta menandatangani berita acara secara digital.</p>
            </div>
            <div class="floating-note">
                <p class="text-uppercase small text-white-50 mb-2">Persiapan data</p>
                <ul class="mb-0 ps-3">
                    <li>Nomor rekening khusus pencairan insentif.</li>
                    <li>Scan buku tabungan untuk verifikasi.</li>
                    <li>Sekolah sudah terdaftar sebagai mitra.</li>
                </ul>
            </div>
        </aside>

        <main class="form-panel">
            <div class="form-card">
                <h3 class="fw-semibold mb-1">Formulir Kepala Sekolah</h3>
                <p class="text-muted mb-4">Tiga langkah singkat untuk mendukung proses PLP dan KKN di sekolah Anda.</p>

                <div class="step-indicators" id="step-indicator">
                    <div class="step-tile" data-step="1">
                        <div class="circle">1</div>
                        <div class="label">Akun</div>
                    </div>
                    <div class="step-tile" data-step="2">
                        <div class="circle">2</div>
                        <div class="label">Sekolah</div>
                    </div>
                    <div class="step-tile" data-step="3">
                        <div class="circle">3</div>
                        <div class="label">Bank</div>
                    </div>
                </div>

                <form id="registerForm">
                    <div class="step active" id="step-1">
                        <?php $this->load->view('forms/auth/akun.php') ?>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-2">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sekolah</label>
                            <select class="form-select" id="schoolSelect" name="school_id" required></select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-3">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bank</label>
                            <input type="text" class="form-control" id="bank" name="bank" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nomor Rekening</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Pemilik Rekening</label>
                            <input type="text" class="form-control" id="account_name" name="account_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Upload Buku Rekening</label>
                            <input type="file" accept=".jpg,.jpeg,.png" class="form-control" name="book" required>
                            <div class="form-text text-muted">Harus berupa gambar (JPG/JPEG/PNG) maksimal 2 MB.</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary finish-step">Daftar</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="<?= base_url('login'); ?>" class="text-decoration-none">Sudah punya akun? Masuk di sini</a>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/select2/js/select2.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 3;

        $(document).ready(function() {
            $('.next-step').on('click', function() {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('.prev-step').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            $('.finish-step').on('click', function() {
                submitFinalForm();
            });

            showStep(currentStep);
            fetchSchool();
        });

        function showStep(step) {
            $('.step').removeClass('active');
            $('#step-' + step).addClass('active');

            $('.step-tile').each(function() {
                const s = $(this).data('step');
                if (s == step) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        }
    </script>
    <script>
        async function fetchSchool() {
            try {
                const response = await fetch(site_url + "sekolah/select/kepsek");
                if (!response.ok) throw new Error('Gagal mengambil data sekolah.');
                const data = await response.json();
                const selectSchoolData = data.map(school => ({
                    id: school.id,
                    text: school.name
                }));

                $('#schoolSelect').select2({
                    data: selectSchoolData,
                    width: '100%',
                    placeholder: "Pilih sekolah",
                    allowClear: true,
                }).val(null).trigger('change');

            } catch (error) {
                console.error(error);
                Swal.fire("Error", "Gagal memuat data sekolah.", "error");
            }
        }

        async function submitFinalForm() {
            const form = document.getElementById('registerForm');
            const formData = new FormData(form);

            try {
                Swal.fire({
                    title: 'Mengirim Pendaftaran...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const response = await fetch("<?= base_url('register/kepala_sekolah/store') ?>", {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil',
                        text: result.message || 'Silakan tunggu verifikasi dari admin.',
                    }).then(() => {
                        window.location.href = '<?= base_url("login") ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pendaftaran Gagal',
                        text: result.message || 'Terjadi kesalahan pada server.',
                    });
                }
            } catch (error) {
                let message = 'Gagal mengirim data. Periksa koneksi Anda.';
                if (error.message && error.message.includes('Failed to fetch')) {
                    message = 'Tidak dapat terhubung ke server. Periksa jaringan Anda.';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: message,
                });

        }
    </script>
</body>

</html>
