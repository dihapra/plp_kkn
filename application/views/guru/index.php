<h1 class="text-center mb-4"> Dashboard Guru Pamong</h1>


<div class="row">
    <!-- Card Mahasiswa -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <h5 class="card-title">Jumlah Mahasiswa</h5>
                <p class="card-text display-4 fw-bold" id="total-mahasiswa"><?= $total_student ?></p>
                <a href="<?= base_url('guru/mahasiswa') ?>" class="btn btn-primary mt-auto">Lihat Mahasiswa</a>
            </div>
        </div>
    </div>


</div>