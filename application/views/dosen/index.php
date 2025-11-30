<h1 class="text-center mb-4">Dosen Dashboard</h1>

<div class="row">
    <!-- Card Mahasiswa -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Jumlah Mahasiswa</h5>
                <p class="card-text display-4 fw-bold" id="total-mahasiswa"><?= $total_student ?></p>
                <a href="<?= base_url('/dosen/mahasiswa') ?>" class="btn btn-primary mt-auto">Lihat Mahasiswa</a>
            </div>
        </div>
    </div>

    <!-- Card Buku Pedoman -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Buku Pedoman</h5>
                <p class="card-text">
                    Akses dan unduh buku pedoman sebagai referensi dosen.
                </p>
                <a href="<?= base_url('assets/Buku_Panduan_PLP_2_Tahun_2025.pdf') ?>" class="btn btn-primary mt-auto"
                    target="_blank">Unduh Buku Pedoman</a>
            </div>
        </div>
    </div>
</div>