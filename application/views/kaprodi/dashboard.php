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

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bar-chart me-2"></i>Ringkasan Prodi</span>
        <button class="btn btn-sm btn-outline-light" disabled>Data belum tersedia</button>
    </div>
    <div class="card-body">
        <p class="text-muted mb-0">Gunakan area ini untuk menampilkan grafik distribusi mahasiswa per angkatan, capaian nilai, atau data penting lainnya.</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-mortarboard me-2"></i>Mahasiswa Terbaru
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Belum ada data mahasiswa yang ditampilkan. Integrasikan dengan modul mahasiswa untuk menampilkan daftar terbaru.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-people me-2"></i>Dosen Aktif
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Belum ada data dosen yang ditampilkan. Integrasikan dengan modul dosen untuk menampilkan aktivitas terbaru.</p>
            </div>
        </div>
    </div>
</div>
