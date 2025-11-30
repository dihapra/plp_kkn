<h1 class="text-center mb-4"> Dashboard Guru Pamong</h1>

<!-- Alert Mahasiswa yang harus diabsen -->
<div class="alert alert-warning text-center" role="alert">
    <strong>Pemberitahuan:</strong> Ada <span id="mahasiswa-absen-count">10</span> mahasiswa yang harus diabsen!
</div>

<!-- Alert Mahasiswa yang harus dinilai -->
<div class="alert alert-info text-center" role="alert">
    <strong>Pemberitahuan:</strong> Ada <span id="mahasiswa-nilai-count">5</span> mahasiswa yang harus dinilai!
</div>

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