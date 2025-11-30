<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru Pamong | PLP-KKN UNIMED</title>
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
            --gradient: linear-gradient(135deg, #0ea5e9, #2563eb);
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
            background: rgba(15, 23, 42, 0.25);
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
            max-width: 560px;
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
            background: #cbd5f5;
            color: #1d3b8b;
            transition: 0.2s ease;
        }

        .step-tile.active .circle {
            background: #1d4ed8;
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

        .form-floating > label {
            color: #94a3b8;
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
                <p class="text-uppercase small opacity-75 mb-2">Guru Pamong • PLP-KKN</p>
                <h1 class="display-6 fw-bold mb-3">Kolaborasi kampus dan sekolah kini lebih mudah.</h1>
                <p class="lead">Melalui akun guru pamong, Anda dapat memantau logbook mahasiswa, memberi nilai, dan mengunggah laporan dalam satu alur terpadu.</p>
            </div>
            <div class="floating-note">
                <p class="text-uppercase small text-white-50 mb-2">Sebelum mendaftar</p>
                <ul class="mb-0 ps-3">
                    <li>Siapkan scan KTP dan buku rekening.</li>
                    <li>Pastikan sekolah sudah terdaftar sebagai mitra PLP-KKN.</li>
                    <li>Gunakan email aktif untuk menerima kredensial.</li>
                </ul>
            </div>
        </aside>

        <main class="form-panel">
            <div class="form-card">
                <h3 class="fw-semibold mb-1">Formulir Guru Pamong</h3>
                <p class="text-muted mb-4">Isi data di bawah ini secara bertahap. Anda dapat kembali ke langkah sebelumnya kapan pun.</p>

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
                        <div class="label">Mahasiswa</div>
                    </div>
                    <div class="step-tile" data-step="4">
                        <div class="circle">4</div>
                        <div class="label">Bank</div>
                    </div>
                </div>

                <form id="registerForm" class="needs-validation" novalidate>
                    <div class="step active" id="step-1">
                        <?php $this->load->view('forms/auth/akun.php') ?>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-2">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sekolah Mitra</label>
                            <select class="form-select" id="schoolSelect"></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" id="schoolName" disabled>
                            <input type="hidden" id="schoolId" name="school_id">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Program Studi Mahasiswa</label>
                            <select class="form-select" id="prodiSelect"></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Program Studi</label>
                            <input type="text" class="form-control" id="prodiName" disabled>
                            <input type="hidden" id="prodiId" name="prodi_id">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-3">
                        <div class="mb-3">
                            <label for="mahasiswaSelect" class="form-label fw-semibold">Pilih Anggota Kelompok</label>
                            <select id="mahasiswaSelect" class="form-select" multiple style="width: 100%;"></select>
                            <div class="form-text text-muted">Jika tersisa kurang dari 5 mahasiswa, sistem akan otomatis memilih semuanya.</div>
                        </div>
                        <div class="mb-3">
                            <label for="ketuaSelect" class="form-label fw-semibold">Ketua Kelompok</label>
                            <select id="ketuaSelect" class="form-select" name="ketua" required>
                                <option value="">Pilih Ketua Kelompok</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Bank</label>
                                <input type="text" class="form-control" name="bank">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Rekening</label>
                                <input type="text" class="form-control" name="account_number">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nama Pemilik Rekening</label>
                                <input type="text" class="form-control" name="account_name">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Upload Buku Rekening</label>
                                <input type="file" id="book" name="book" class="form-control" accept=".jpg,.jpeg,.png" required>
                                <div class="form-text text-muted">Format JPG/PNG, maksimal 2MB.</div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary finish-step">Daftar</button>
                        </div>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <a href="<?= base_url('login'); ?>" class="text-decoration-none">Sudah memiliki akun? Masuk di sini</a>
                </div>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/libs/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/libs/select2/js/select2.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const $selectSchool = $("#schoolSelect");
        const $selectProdi = $("#prodiSelect");
        const $mahasiswaSelect = $("#mahasiswaSelect");
        const $form = $("#registerForm");
        let currentStep = 1;
        const totalSteps = 4;

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

            $('.finish-step').on('click', async function() {
                if (currentStep === totalSteps) {
                    const isValid = validateKelompokBeforeSubmit();
                    if (!isValid) return;

                    const result = await Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Pastikan seluruh data sudah benar sebelum mengirim formulir.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, kirim!',
                        cancelButtonText: 'Cek lagi'
                    });

                    if (result.isConfirmed) {
                        submitFinalForm();
                    }
                }
            });

            showStep(currentStep);
            fetchSchool();
            $mahasiswaSelect.on('change', updateMahasiswaList);
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
        async function submitFinalForm() {
            const data = new FormData($form[0]);
            const selectedOptions = $mahasiswaSelect.select2('data');
            const anggotaId = selectedOptions.map(option => option.id);
            anggotaId.forEach(id => data.append('anggotaId[]', id));

            try {
                Swal.fire({
                    title: 'Mengirim data...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const resp = await fetch("<?= base_url('register/guru/store') ?>", {
                    method: "POST",
                    body: data,
                });

                const result = await resp.json();

                if (resp.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran berhasil',
                        text: 'Kami akan memverifikasi berkas Anda dalam waktu dekat.'
                    }).then(() => {
                        window.location.href = '<?= base_url('login'); ?>';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal mendaftar',
                        text: result?.message || 'Terjadi kesalahan pada server.'
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
                    text: message
                });
            }
        }

        async function fetchSchool() {
            try {
                const url = site_url + "sekolah-regist/select";
                const response = await fetch(url);
                if (!response.ok) throw new Error('Gagal mengambil data sekolah.');
                const data = await response.json();
                const selectSchoolData = data.map(school => ({
                    id: school.id,
                    text: school.name,
                    name: school.name
                }));
                populateSchool(selectSchoolData);
            } catch (error) {
                console.log(error);
                Swal.fire("Error", "Gagal memuat data sekolah.", "error");
            }
        }

        function populateSchool(schoolData) {
            if ($selectSchool.hasClass('select2-hidden-accessible')) {
                $selectSchool.select2('destroy');
            }

            $selectSchool.select2({
                data: schoolData,
                width: '100%',
                placeholder: "Pilih sekolah",
                allowClear: true,
            });
            $selectSchool.val(null).trigger('change');
            $selectSchool.off('select2:select');

            $selectSchool.on('select2:select', function(e) {
                const selectedData = e.params.data;
                $form.find('#schoolName').val(selectedData.name);
                $form.find('#schoolId').val(selectedData.id);
                fetchProdi(selectedData.id);
            });

            $selectSchool.on('select2:clear', function() {
                $form.find('#schoolName, #schoolId').val('');
            });
        }

        async function fetchProdi(school_id) {
            try {
                const url = site_url + `prodi-regist/select?school_id=${school_id}&register=false`;
                const response = await fetch(url);
                if (!response.ok) throw new Error('Gagal mengambil data prodi.');
                const data = await response.json();
                const selectProdiData = data.map(prodi => ({
                    id: prodi.id,
                    text: prodi.name,
                    name: prodi.name
                }));
                populateProdi(selectProdiData, school_id);
            } catch (error) {
                console.log(error);
                Swal.fire("Error", "Gagal memuat data prodi.", "error");
            }
        }

        function populateProdi(prodiData, school_id) {
            if ($selectProdi.hasClass('select2-hidden-accessible')) {
                $selectProdi.select2('destroy');
            }
            $selectProdi.empty();

            $selectProdi.select2({
                data: prodiData,
                width: '100%',
                placeholder: "Pilih program studi",
                allowClear: true,
            });

            $selectProdi.off('select2:select');
            $selectProdi.val(null).trigger('change');

            $selectProdi.on('select2:select', function(e) {
                const selectedData = e.params.data;
                $form.find('#prodiName').val(selectedData.name);
                $form.find('#prodiId').val(selectedData.id);
                fetchMahasiswa(school_id, selectedData.id);
            });

            $selectProdi.on('select2:clear', function() {
                $form.find('#prodiName, #prodiId').val('');
            });
        }
    </script>
    <script>
        let _allMahasiswaOptions = [];

        function updateMahasiswaList() {
            const selectedOptions = $mahasiswaSelect.select2('data');
            const ketuaSelect = $('#ketuaSelect');
            ketuaSelect.empty();
            ketuaSelect.append(`<option value="">Pilih Ketua Kelompok</option>`);
            selectedOptions.forEach(option => {
                ketuaSelect.append(`<option value="${option.id}">${option.text}</option>`);
            });
        }

        async function fetchMahasiswa(school_id, prodi_id) {
            try {
                const url = site_url + `mahasiswa-regist/select?school_id=${school_id}&prodi_id=${prodi_id}`;
                const response = await fetch(url);
                if (!response.ok) throw new Error('Gagal mengambil data mahasiswa.');
                const data = await response.json();

                const selectMahasiswaData = data.map(student => ({
                    id: String(student.id),
                    text: student.name,
                    name: student.name
                }));
                _allMahasiswaOptions = selectMahasiswaData;

                $mahasiswaSelect.off().empty().select2({
                    data: selectMahasiswaData,
                    placeholder: "Pilih mahasiswa...",
                    allowClear: true,
                    width: '100%'
                });

                enforceRulesAfterChange(true);

                let justUnselected = false;

                $mahasiswaSelect
                    .on('select2:select', function() {
                        const total = _allMahasiswaOptions.length;
                        const selected = ($mahasiswaSelect.val() || []).length;
                        const remaining = total - selected;

                        if (remaining > 0 && remaining < 5) {
                            const allIds = _allMahasiswaOptions.map(o => String(o.id));
                            $mahasiswaSelect.val(allIds).trigger('change.select2');
                            Swal.fire('Info', `Sisa mahasiswa tinggal ${remaining}. Semua sisa otomatis dipilih.`, 'info');
                        }
                        updateMahasiswaList();
                    })
                    .on('select2:unselect', function(e) {
                        const total = _allMahasiswaOptions.length;
                        const selected = ($mahasiswaSelect.val() || []).length;

                        if (total >= 5 && selected < 5) {
                            const reverted = ($mahasiswaSelect.val() || []).concat([String(e.params.data.id)]);
                            $mahasiswaSelect.val(reverted).trigger('change.select2');
                            Swal.fire('Peringatan', 'Minimal 5 anggota per kelompok.', 'warning');
                        }
                        updateMahasiswaList();
                    })
                    .on('select2:unselect', function() {
                        justUnselected = true;
                    })
                    .on('select2:close', function() {
                        if (justUnselected) {
                            justUnselected = false;
                            return;
                        }

                        const total = _allMahasiswaOptions.length;
                        const selected = ($mahasiswaSelect.val() || []).length;
                        const remaining = total - selected;

                        if (remaining > 0 && remaining < 5) {
                            Swal.fire(
                                'Peringatan',
                                `Sisa mahasiswa tinggal ${remaining}. Harus diambil semua atau disisakan tepat 5.`,
                                'warning'
                            );
                        }
                    });

            } catch (error) {
                console.log(error);
                Swal.fire("Error", "Gagal mengambil data mahasiswa.", "error");
            }
        }

        function enforceRulesAfterChange(onInit = false) {
            const total = _allMahasiswaOptions.length;
            let selectedIds = $mahasiswaSelect.val() || [];
            selectedIds = selectedIds.map(String);

            if (total === 0) return;

            if (total < 5) {
                const allIds = _allMahasiswaOptions.map(o => String(o.id));
                $mahasiswaSelect.val(allIds).trigger('change.select2');
                $mahasiswaSelect.prop('disabled', true);
                updateMahasiswaList();
                if (!onInit) {
                    Swal.fire('Info', 'Total mahasiswa < 5, seluruhnya otomatis dipilih.', 'info');
                }
                return;
            } else {
                $mahasiswaSelect.prop('disabled', false);
            }
        }

        function validateKelompokBeforeSubmit() {
            const total = _allMahasiswaOptions.length;
            const selected = ($mahasiswaSelect.val() || []).length;
            const remaining = total - selected;

            if (total >= 5 && selected < 5) {
                Swal.fire(
                    'Peringatan',
                    `Minimal pilih 5 mahasiswa dari total ${total} mahasiswa untuk satu kelompok.`,
                    'warning'
                );
                return false;
            }

            if (remaining > 0 && remaining < 5) {
                Swal.fire(
                    'Peringatan',
                    `Sisa mahasiswa tinggal ${remaining}. Harus diambil semua atau disisakan tepat 5.`,
                    'warning'
                );
                return false;
            }

            return true;
        }

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
