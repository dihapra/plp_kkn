<h1 class="text-center m-4">Dashboard Mahasiswa</h1>

<div class="row mt-4">
    <!-- Card Informasi Sekolah -->
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-school fa-2x text-primary me-3"></i>
                    <h5 class="card-title mb-0">Informasi Sekolah</h5>
                </div>
                <hr>
                <p class="card-text">
                    <strong>Nama Sekolah:</strong>
                    <?= !empty($student->school_name) ? $student->school_name : 'Belum tersedia' ?> <br>
                </p>
            </div>
        </div>
    </div>

    <!-- Card Informasi Guru Pamong -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-chalkboard-teacher fa-2x text-success me-3"></i>
                    <h5 class="card-title mb-0">Guru Pamong</h5>
                </div>
                <hr>
                <p class="card-text">
                    <strong>Nama:</strong>
                    <?= !empty($student->teacher_name) ? $student->teacher_name : 'Belum tersedia' ?> <br>
                </p>
            </div>
        </div>
    </div>

    <!-- Card Informasi Dosen Pembimbing -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-user-graduate fa-2x text-warning me-3"></i>
                    <h5 class="card-title mb-0">Dosen Pembimbing</h5>
                </div>
                <hr>
                <p class="card-text">
                    <strong>Nama:</strong>
                    <?= !empty($student->lecture_name) ? $student->lecture_name : 'Belum tersedia' ?> <br>
                </p>
            </div>
        </div>
    </div>

    <!-- Card Link Buku Pedoman -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-book fa-2x text-danger me-3"></i>
                    <h5 class="card-title mb-0">Buku Pedoman</h5>
                </div>
                <hr>
                <p class="card-text">
                    <strong>Unduh:</strong> <a href="<?= base_url('assets/Buku_Panduan_PLP_2_Tahun_2025.pdf') ?>"
                        target="_blank" class="text-decoration-none">Klik di sini</a>
                </p>
            </div>
        </div>
    </div>
    <?php
    $canPrint = !empty($student->teacher_name);
    $printUrl = site_url('mahasiswa/cetak_surat_tugas');
    ?>

    <!-- Card Cetak Surat Tugas -->
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm <?= $canPrint ? '' : 'bg-light' ?>">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-file-word fa-2x text-primary me-3"></i>
                    <h5 class="card-title mb-0">Cetak Surat Tugas</h5>
                </div>
                <hr>
                <p class="card-text">
                    <strong>Status:</strong>
                    <?= $canPrint ? 'Siap dicetak' : 'Belum bisa dicetak' ?><br>
                    <small class="text-muted">Dokumen akan diunduh sebagai .docx</small>
                </p>

                <a href="<?= $canPrint ? $printUrl : 'javascript:void(0)'; ?>"
                    class="btn btn-primary w-100 <?= $canPrint ? '' : 'disabled' ?>"
                    <?= $canPrint ? '' : 'aria-disabled="true" tabindex="-1"'; ?>>
                    <i class="fa-solid fa-print me-1"></i> Cetak Surat
                </a>

                <?php if (!$canPrint): ?>
                    <div class="mt-2 small text-muted">
                        Nama guru pamong belum ada. Silakan lengkapi data guru terlebih dahulu.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Include FontAwesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>