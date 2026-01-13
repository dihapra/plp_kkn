<div class="row g-3 mb-4 mt-4">
    <div class="col-md-4">
        <div class="card text-bg-primary h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-white-50">Total Mahasiswa</p>
                        <h3 class="mb-0"><?= number_format($total_mahasiswa ?? 0) ?></h3>
                    </div>
                    <i class="bi bi-people fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-white-50">Dosen Pembimbing</p>
                        <h3 class="mb-0"><?= number_format($total_dosen ?? 0) ?></h3>
                    </div>
                    <i class="bi bi-person-badge fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-warning h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 text-white-50">Program Aktif</p>
                        <h3 class="mb-0"><?= number_format($program_aktif ?? 0) ?></h3>
                    </div>
                    <i class="bi bi-clipboard-check fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="mb-0">Alur Kerja Kaprodi</h5>
        <small class="text-muted">Ikuti tahapan utama untuk menyiapkan penempatan mahasiswa.</small>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary">1</span>
                        <span class="fw-semibold">Tambah Sekolah</span>
                    </div>
                    <p class="mb-0 text-muted small">
                        Pilih sekolah mitra dan unggah surat MOU untuk prodi Anda.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success">2</span>
                        <span class="fw-semibold">Tambah Dosen</span>
                    </div>
                    <p class="mb-0 text-muted small">
                        Input dosen pembimbing agar siap membina mahasiswa.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded-3 p-3 h-100">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark">3</span>
                        <span class="fw-semibold">Plotting Mahasiswa</span>
                    </div>
                    <p class="mb-0 text-muted small">
                        Tentukan dosen, sekolah, dan mahasiswa pada menu plotting.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php /* sementara disembunyikan - Ringkasan Prodi, Mahasiswa Terbaru, Dosen Aktif */ ?>
