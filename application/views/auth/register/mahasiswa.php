<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa PLP | PLP-KKN UNIMED</title>
    <link rel="icon" href="<?= base_url('assets/images/unimed.ico') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const site_url = "<?= base_url() ?>";
    </script>
    <style>
        :root {
            --gradient: linear-gradient(135deg, #2563eb, #7c3aed, #9333ea);
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
            max-width: 580px;
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
            background: #dbe8fe;
            color: #1e40af;
            transition: 0.2s ease;
        }

        .step-tile.active .circle {
            background: #312e81;
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

        .statement-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            background: #f8fafc;
            margin-bottom: 1rem;
        }

        .summary-pill {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            background: #eef2ff;
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .summary-pill span:last-child {
            font-weight: 600;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
            animation: fadeIn 0.25s ease;
        }

        .form-check.statement-check {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.75rem;
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
                <p class="text-uppercase small opacity-75 mb-2">Mahasiswa PLP-KKN</p>
                <h1 class="display-6 fw-bold mb-3">Registrasi satu pintu untuk peserta PLP Unimed.</h1>
                <p class="lead">Cukup sekali isi formulir untuk memetakan fakultas, status MKDK, dan komitmen mengikuti
                    kegiatan PLP. Data akan diteruskan ke Divisi PLP secara otomatis.</p>
            </div>
            <div class="floating-note">
                <p class="text-uppercase small text-white-50 mb-2">Siapkan sebelum mulai</p>
                <ul class="mb-0 ps-3">
                    <li>Pastikan NIM dan email aktif.</li>
                    <li>Status mata kuliah dasar kependidikan (MKDK).</li>
                    <li>Siapkan perangkat untuk menandatangani pernyataan.</li>
                </ul>
            </div>
        </aside>

        <main class="form-panel">
            <div class="form-card">
                <h3 class="fw-semibold mb-1">Formulir Mahasiswa PLP</h3>
                <p class="text-muted mb-4">Tiga langkah singkat: data diri, status MKDK, dan pernyataan kesiapan.</p>

                <div class="step-indicators" id="step-indicator">
                    <div class="step-tile" data-step="1">
                        <div class="circle">1</div>
                        <div class="label">Data Diri</div>
                    </div>
                    <div class="step-tile" data-step="2">
                        <div class="circle">2</div>
                        <div class="label">Status MKDK</div>
                    </div>
                    <div class="step-tile" data-step="3">
                        <div class="circle">3</div>
                        <div class="label">Pernyataan</div>
                    </div>
                </div>

                <form id="studentRegisterForm" class="needs-validation" novalidate>
                    <div class="step active" id="step-1">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="studentName">Nama Mahasiswa</label>
                                <input type="text" class="form-control" id="studentName" name="name" placeholder="Nama lengkap"
                                    required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="studentEmail">Email</label>
                                <input type="email" class="form-control" id="studentEmail" name="email" placeholder="email@domain.com"
                                    required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="studentNim">NIM</label>
                                <input type="text" class="form-control" id="studentNim" name="nim" maxlength="10"
                                    pattern="[0-9]{10}" placeholder="10 digit angka" required>
                                <div class="form-text">Gunakan hanya angka.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="studentPhone">Nomor WhatsApp</label>
                                <input type="tel" class="form-control" id="studentPhone" name="phone" placeholder="08xxxxxxxxxx"
                                    required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="facultySelect">Fakultas</label>
                                <select class="form-select" id="facultySelect" name="faculty" required>
                                    <option value="">-- Pilih Fakultas --</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" for="prodiSelect">Program Studi</label>
                                <select class="form-select" id="prodiSelect" name="program_studi" disabled required>
                                    <option value="">-- Pilih Prodi --</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-2">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Filsafat Pendidikan</label>
                                <select class="form-select" name="mkdk[filsafat_pendidikan]" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Belum Lulus">Belum Lulus</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Profesi Kependidikan</label>
                                <select class="form-select" name="mkdk[profesi_kependidikan]" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Belum Lulus">Belum Lulus</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Perkembangan Peserta Didik</label>
                                <select class="form-select" name="mkdk[perkembangan_peserta_didik]" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Belum Lulus">Belum Lulus</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Psikologi Pendidikan</label>
                                <select class="form-select" name="mkdk[psikologi_pendidikan]" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Belum Lulus">Belum Lulus</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-secondary prev-step">Kembali</button>
                            <button type="button" class="btn btn-primary next-step">Lanjut</button>
                        </div>
                    </div>

                    <div class="step" id="step-3">
                        <div class="statement-card mb-3">
                            <h5 class="fw-semibold mb-3 text-center text-uppercase small text-primary">Ringkasan Data</h5>
                            <div class="summary-pill">
                                <span>Nama</span>
                                <span id="summaryName">-</span>
                            </div>
                            <div class="summary-pill">
                                <span>NIM</span>
                                <span id="summaryNim">-</span>
                            </div>
                            <div class="summary-pill">
                                <span>Program Studi</span>
                                <span id="summaryProdi">-</span>
                            </div>
                            <div class="summary-pill mb-0">
                                <span>Fakultas</span>
                                <span id="summaryFaculty">-</span>
                            </div>
                        </div>
                        <p class="mb-3">Dengan ini saya menyatakan:</p>
                        <div class="form-check statement-check">
                            <input class="form-check-input" type="checkbox" value="setuju_plp" id="statementOne" name="agreement_plp"
                                required>
                            <label class="form-check-label" for="statementOne">
                                Bersedia mengikuti Program Pengenalan Lapangan Persekolahan (PLP) I Tahun 2026 sesuai ketentuan dan pedoman Universitas Negeri Medan.
                            </label>
                        </div>
                        <div class="form-check statement-check">
                            <input class="form-check-input" type="checkbox" value="setuju_tugas" id="statementTwo" name="agreement_tugas"
                                required>
                            <label class="form-check-label" for="statementTwo">
                                Akan melaksanakan seluruh tugas PLP I dengan penuh tanggung jawab sesuai arahan dosen pembimbing dan sekolah mitra.
                            </label>
                        </div>
                        <div class="form-check statement-check">
                            <input class="form-check-input" type="checkbox" value="setuju_profesional" id="statementThree"
                                name="agreement_profesional" required>
                            <label class="form-check-label" for="statementThree">
                                Menyadari bahwa kegiatan PLP merupakan bagian penting dari pembentukan kompetensi sebagai calon pendidik profesional.
                            </label>
                        </div>
                        <div class="form-check statement-check">
                            <input class="form-check-input" type="checkbox" value="setuju_etika" id="statementFour"
                                name="agreement_etika" required>
                            <label class="form-check-label" for="statementFour">
                                Berkomitmen menjaga nama baik universitas dan institusi tempat pelaksanaan PLP serta menaati seluruh tata tertib.
                            </label>
                        </div>
                        <div class="form-check statement-check">
                            <input class="form-check-input" type="checkbox" value="setuju_lapor" id="statementFive" name="agreement_lapor"
                                required>
                            <label class="form-check-label" for="statementFive">
                                Akan melaporkan kendala yang berpotensi menghambat keikutsertaan PLP kepada Divisi PLP sesuai prosedur yang berlaku.
                            </label>
                        </div>
                        <p class="rounded p-2 " style="background-color:#F9FAFB;">
                            Demikian pernyataan ini saya buat dengan sebenar-benarnya tanpa ada paksaan dari pihak manapun.
                        </p>
                        <div class="mb-3">
                            <label for="statementRewrite" class="form-label fw-semibold">Penegasan Pernyataan</label>
                            <textarea id="statementRewrite" name="statement_rewrite" class="form-control" placeholder="Ketik ulang pernyataan komitmen sebagai tanda persetujuan..."
                                rows="4" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const stepTiles = document.querySelectorAll('.step-tile');
        const steps = document.querySelectorAll('.step');
        const form = document.getElementById('studentRegisterForm');
        const facultySelect = document.getElementById('facultySelect');
        const prodiSelect = document.getElementById('prodiSelect');
        const nameField = document.getElementById('studentName');
        const nimField = document.getElementById('studentNim');

        const summaryElements = {
            name: document.getElementById('summaryName'),
            nim: document.getElementById('summaryNim'),
            prodi: document.getElementById('summaryProdi'),
            faculty: document.getElementById('summaryFaculty'),
        };

        let currentStep = 1;
        const totalSteps = 3;

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.next-step').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (!validateStep(currentStep)) return;
                    if (currentStep < totalSteps) {
                        currentStep += 1;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll('.prev-step').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 1) {
                        currentStep -= 1;
                        showStep(currentStep);
                    }
                });
            });

            document.querySelectorAll('.finish-step').forEach(btn => {
                btn.addEventListener('click', handleSubmit);
            });

            facultySelect.addEventListener('change', handleFacultyChange);
            prodiSelect.addEventListener('change', updateSummary);
            nameField.addEventListener('input', handleNameChange);
            nimField.addEventListener('input', handleNimChange);
            document.getElementById('studentPhone').addEventListener('input', handlePhoneChange);

            showStep(currentStep);
            fetchFacultiesForSelect();
        });

        async function fetchFacultiesForSelect() {
            try {
                facultySelect.innerHTML = '<option value="">Memuat fakultas...</option>';
                const response = await fetch(`${site_url}register/mahasiswa/faculties`);
                if (!response.ok) {
                    throw new Error('Gagal memuat daftar fakultas.');
                }
                const data = await response.json();
                populateFacultyOptions(data);
            } catch (error) {
                console.error(error);
                facultySelect.innerHTML = '<option value="">-- Pilih Fakultas --</option>';
                Swal.fire('Error', error.message, 'error');
            }
        }

        function populateFacultyOptions(list = []) {
            facultySelect.innerHTML = '<option value="">-- Pilih Fakultas --</option>';
            list.forEach(item => {
                const code = item.fakultas || item.code;
                if (!code) return;
                const option = document.createElement('option');
                option.value = code;
                option.textContent = code;
                facultySelect.appendChild(option);
            });
        }

        async function fetchProgramsByFaculty(facultyCode) {
            prodiSelect.disabled = true;
            prodiSelect.innerHTML = '<option value="">Memuat program studi...</option>';
            if (!facultyCode) {
                prodiSelect.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                return;
            }
            try {
                const response = await fetch(`${site_url}register/mahasiswa/study-programs?faculty=${encodeURIComponent(facultyCode)}`);
                if (!response.ok) {
                    throw new Error('Gagal memuat program studi.');
                }
                const data = await response.json();
                populateProgramOptions(data);
            } catch (error) {
                console.error(error);
                prodiSelect.innerHTML = '<option value="">-- Pilih Prodi --</option>';
                Swal.fire('Error', error.message, 'error');
            } finally {
                updateSummary();
            }
        }

        function populateProgramOptions(programs = []) {
            prodiSelect.innerHTML = '<option value="">-- Pilih Prodi --</option>';
            programs.forEach(program => {
                const namaProdi = program.nama || program.name;
                if (!namaProdi) return;
                const option = document.createElement('option');
                option.value = namaProdi;
                option.textContent = namaProdi;
                prodiSelect.appendChild(option);
            });
            prodiSelect.disabled = programs.length === 0;
        }

        function showStep(step) {
            steps.forEach(el => el.classList.remove('active'));
            document.getElementById(`step-${step}`).classList.add('active');

            stepTiles.forEach(tile => {
                const tileStep = Number(tile.dataset.step);
                tile.classList.toggle('active', tileStep === step);
            });
        }

        function validateStep(step) {
            const stepElement = document.getElementById(`step-${step}`);
            if (!stepElement) return true;

            const stageFields = stepElement.querySelectorAll('input, select, textarea');
            for (const field of stageFields) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }
            return true;
        }

        function handleFacultyChange(event) {
            const selectedFaculty = event.target.value;
            prodiSelect.value = '';
            fetchProgramsByFaculty(selectedFaculty);
            updateSummary();
        }

        function handleNameChange(event) {
            const formatted = event.target.value.replace(/\b\w/g, c => c.toUpperCase());
            event.target.value = formatted;
            updateSummary();
        }

        function handleNimChange(event) {
            const digitsOnly = event.target.value.replace(/\D/g, '').slice(0, 10);
            event.target.value = digitsOnly;
            updateSummary();
        }

        function handlePhoneChange(event) {
            event.target.value = event.target.value.replace(/[^\d+]/g, '');
        }

        function updateSummary() {
            summaryElements.name.textContent = nameField.value || '-';
            summaryElements.nim.textContent = nimField.value || '-';
            summaryElements.prodi.textContent = prodiSelect.value || '-';
            summaryElements.faculty.textContent = facultySelect.value || '-';
        }

        async function handleSubmit() {
            if (!validateStep(currentStep)) return;

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const confirmation = await Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: 'Pastikan seluruh data sudah benar sebelum dikirim.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Kirim Sekarang',
                cancelButtonText: 'Periksa Lagi'
            });

            if (!confirmation.isConfirmed) {
                return;
            }

            try {
                Swal.fire({
                    title: 'Mengirim data...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const payload = new FormData(form);
                const response = await fetch("<?= base_url('register/mahasiswa/store') ?>", {
                    method: 'POST',
                    body: payload
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi berhasil',
                        text: result?.message || 'Data dikirim ke Divisi PLP.'
                    }).then(() => {
                        window.location.href = '<?= base_url('login'); ?>';
                    });
                } else {
                    throw new Error(result?.message || 'Gagal mengirim data.');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: error.message || 'Tidak dapat terhubung ke server.'
                });
            }
        }
    </script>
</body>

</html>
