<?php
// Helper function to display score or a dash
function show_score($score)
{
    return is_numeric($score) ? round($score, 2) : '-';
}
function grade_nilai_akhir($score)
{
    if (!is_numeric($score)) return '-';
    $score = round($score);

    // Tabel 2: 85-100 A, 75-84 B, 65-74 C, 0-64 E
    switch (true) {
        case ($score >= 85 && $score <= 100):
            return 'A';
        case ($score >= 75 && $score <= 84):
            return 'B';
        case ($score >= 65 && $score <= 74):
            return 'C';
        default:
            return 'E';
    }
}
function grade_sikap($score, $forceScale100 = null)
{
    if (!is_numeric($score)) return '-';
    $val = floatval($score);

    // Deteksi otomatis: kalau > 4 dianggap skala 0–100 (atau pakai $forceScale100=true untuk memaksa)
    if ($forceScale100 === true || ($forceScale100 === null && $val > 4.0)) {
        $val = $val / 25.0; // 0–100 -> 0–4
    }

    // Tabel 4 (0–4): 3.51–4.00 SB, 2.51–3.50 B, 1.51–2.50 KB, 0.00–1.50 SKB
    switch (true) {
        case ($val >= 3.51 && $val <= 4.00):
            return 'SB';
        case ($val >= 2.51 && $val <= 3.50):
            return 'B';
        case ($val >= 1.51 && $val <= 2.50):
            return 'KB';
        default:
            return 'SKB';
    }
}
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Rekapitulasi Nilai Akhir Mahasiswa</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="penilaianTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dosen-tab" data-bs-toggle="tab" data-bs-target="#dosen" role="tab">Penilaian Dosen (DPL)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="guru-tab" data-bs-toggle="tab" data-bs-target="#guru" role="tab">Penilaian Guru Pamong</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="total-tab" data-bs-toggle="tab" data-bs-target="#total" role="tab">Penilaian Total</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="penilaianTabsContent">

                <!-- Tab 1: Penilaian Dosen -->
                <div class="tab-pane fade show active" id="dosen" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Kemampuan Analisis</th>
                                    <th>Intrakurikuler </th>
                                    <th>Laporan Kemajuan</th>
                                    <th>Presentasi Laporan Kemajuan</th>
                                    <th>Laporan Akhir</th>
                                    <th>Presentasi Laporan Akhir</th>
                                    <th>Modul Ajar</th>
                                    <th>Bahan Ajar</th>
                                    <th>Modul Proyek</th>
                                    <th class="bg-success text-white">Sikap Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penilaian_dosen as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                        <td><?php echo show_score($row['analisis_mahasiswa']); ?></td>
                                        <td><?php echo show_score($row['intrakurikuler_dpl']); ?></td>
                                        <td><?php echo show_score($row['laporan_kemajuan']); ?></td>
                                        <td><?php echo show_score($row['presentasi_kemajuan']); ?></td>
                                        <td><?php echo show_score($row['laporan_akhir']); ?></td>
                                        <td><?php echo show_score($row['presentasi_akhir']); ?></td>
                                        <td><?php echo show_score($row['modul_ajar']); ?></td>
                                        <td><?php echo show_score($row['bahan_ajar']); ?></td>
                                        <td><?php echo show_score($row['modul_proyek']); ?></td>
                                        <td class="bg-light font-weight-bold"><?php echo show_score($row['penilaian_sikap']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 2: Penilaian Guru Pamong -->
                <div class="tab-pane fade" id="guru" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Intrakurikuler</th>
                                    <th>Kokurikuler dan / atau Ekstrakurikuler</th>
                                    <th class="bg-primary text-white">Sikap Mahasiswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penilaian_guru as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                        <td><?php echo show_score($row['intrakurikuler_pamong']); ?></td>
                                        <td><?php echo show_score($row['ekstrakurikuler']); ?></td>
                                        <td><?php echo show_score($row['penilaian_sikap']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Penilaian Total -->
                <div class="tab-pane fade" id="total" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                    <th>Kehadiran</th>
                                    <th>Kemampuan Analisis</th>
                                    <th>Intrakurikuler (Pamong)</th>
                                    <th>Intrakurikuler (DPL)</th>
                                    <th>Kokurikuler dan / atau Ekstrakurikuler</th>
                                    <th>Laporan Kemajuan</th>
                                    <th>Presentasi Laporan Kemajuan</th>
                                    <th>Laporan Akhir</th>
                                    <th>Presentasi Laporan Akhir</th>
                                    <th>Modul Ajar</th>
                                    <th>Bahan Ajar</th>
                                    <th>Modul Proyek</th>
                                    <th class="bg-primary text-white">Nilai Akhir Pengetahuan dan Keterampilan Mahasiswa</th>
                                    <th class="bg-primary text-white">Grade</th>
                                    <th class="bg-success text-white">Sikap Mahasiswa</th>
                                    <th class="bg-success text-white">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penilaian_total as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nim']); ?></td>
                                        <td><?php echo show_score($row['kehadiran']); ?></td>
                                        <td><?php echo show_score($row['analisis_mahasiswa']); ?></td>
                                        <td><?php echo show_score($row['intrakurikuler_pamong']); ?></td>
                                        <td><?php echo show_score($row['intrakurikuler_dpl']); ?></td>
                                        <td><?php echo show_score($row['ekstrakurikuler']); ?></td>
                                        <td><?php echo show_score($row['laporan_kemajuan']); ?></td>
                                        <td><?php echo show_score($row['presentasi_kemajuan']); ?></td>
                                        <td><?php echo show_score($row['laporan_akhir']); ?></td>
                                        <td><?php echo show_score($row['presentasi_akhir']); ?></td>
                                        <td><?php echo show_score($row['modul_ajar']); ?></td>
                                        <td><?php echo show_score($row['bahan_ajar']); ?></td>
                                        <td><?php echo show_score($row['modul_proyek']); ?></td>
                                        <td class="bg-light font-weight-bold"><?php echo show_score($row['total_nilai_akhir']); ?></td>
                                        <td class="bg-light font-weight-bold"><?php echo grade_nilai_akhir($row['total_nilai_akhir']); ?></td>
                                        <td class="bg-light font-weight-bold"><?php echo show_score($row['penilaian_sikap']); ?></td>
                                        <td class="bg-light font-weight-bold"><?php echo grade_sikap($row['penilaian_sikap']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>