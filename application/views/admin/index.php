<h1 class="text-center mb-4">Admin Dashboard</h1>

<!-- Alert Section -->
<!-- <div class="mb-3">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> Ada <span class="fw-bold"><?= $dosen_belum_aktivitas; ?></span> dosen yang belum mengerjakan aktivitasnya.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Perhatian!</strong> Ada <span class="fw-bold"><?= $mahasiswa_tidak_absen; ?></span> mahasiswa yang tidak mengabsensi hari ini.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div> -->

<!-- Chart Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Jumlah Mahasiswa per Fakultas</h5>
                <canvas id="chartMahasiswa" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-center">Jumlah Dosen per Fakultas</h5>
                <canvas id="chartDosen" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Cards Section -->
<div class="row">
    <!-- Card Mahasiswa -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Mahasiswa</h5>
                <p class="card-text display-4 fw-bold"><?= $total_mahasiswa; ?></p>
                <a href="<?= base_url('/admin/mahasiswa') ?>" class="btn btn-primary">Kelola Mahasiswa</a>
            </div>
        </div>
    </div>
    <!-- Card Dosen -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Dosen</h5>
                <p class="card-text display-4 fw-bold"><?= $total_dosen; ?></p>
                <a href="<?= base_url('/admin/dosen') ?>" class="btn btn-primary">Kelola Dosen</a>
            </div>
        </div>
    </div>
    <!-- Card Guru -->
    <div class="col-md-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Guru</h5>
                <p class="card-text display-4 fw-bold"><?= $total_guru; ?></p>
                <a href="<?= base_url('/admin/guru') ?>" class="btn btn-primary">Kelola Guru</a>
            </div>
        </div>
    </div>
    <!-- Card Sekolah -->
    <div class="col-md-4 mt-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Sekolah</h5>
                <p class="card-text display-4 fw-bold"><?= $total_sekolah; ?></p>
                <a href="<?= base_url('/admin/sekolah') ?>" class="btn btn-primary">Kelola Sekolah</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Kepala Sekolah</h5>
                <p class="card-text display-4 fw-bold"><?= $total_kepsek; ?></p>
                <a href="<?= base_url('/admin/sekolah') ?>" class="btn btn-primary">Kelola Sekolah</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-4">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Jumlah Sekolah Kosong</h5>
                <p class="card-text display-4 fw-bold"><?= $total_sekolah_kosong; ?></p>
                <a href="<?= base_url('/admin/sekolah') ?>" class="btn btn-primary">Kelola Sekolah</a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const singkatanFakultas = {
        "FAKULTAS BAHASA DAN SENI": "FBS",
        "FAKULTAS EKONOMI": "FE",
        "FAKULTAS ILMU KEOLAHRAGAAN": "FIK",
        "FAKULTAS ILMU PENDIDIKAN": "FIP",
        "FAKULTAS ILMU SOSIAL": "FIS",
        "FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM": "FMIPA",
        "FAKULTAS TEKNIK": "FT"
    };

    // Mengubah nama fakultas menjadi singkatan
    const labelsMahasiswa = <?= json_encode(array_column($chart_mahasiswa, 'fakultas')) ?>.map(fak => singkatanFakultas[fak] || fak);
    const labelsDosen = <?= json_encode(array_column($chart_dosen, 'fakultas')) ?>.map(fak => singkatanFakultas[fak] || fak);

    // Data untuk Chart Mahasiswa
    const dataMahasiswa = {
        labels: labelsMahasiswa,
        datasets: [{
            label: 'Jumlah Mahasiswa',
            data: <?= json_encode(array_column($chart_mahasiswa, 'total')) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Data untuk Chart Dosen
    const dataDosen = {
        labels: labelsDosen,
        datasets: [{
            label: 'Jumlah Dosen',
            data: <?= json_encode(array_column($chart_dosen, 'total')) ?>,
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };

    // Konfigurasi Chart Mahasiswa
    const configMahasiswa = {
        type: 'bar',
        data: dataMahasiswa,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Konfigurasi Chart Dosen
    const configDosen = {
        type: 'bar',
        data: dataDosen,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Render Chart Mahasiswa
    new Chart(document.getElementById('chartMahasiswa'), configMahasiswa);

    // Render Chart Dosen
    new Chart(document.getElementById('chartDosen'), configDosen);
</script>