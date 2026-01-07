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

<?php /* sementara disembunyikan - Ringkasan Prodi, Mahasiswa Terbaru, Dosen Aktif */ ?>
