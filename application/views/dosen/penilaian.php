<style>
    /* Gaya untuk header tabel */
    thead th {
        background-color: #343a40 !important;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    /* Gaya untuk body tabel */
    tbody td {
        vertical-align: middle;
        text-align: center;
    }

    /* Gaya hover untuk baris */
    tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
</style>
<?php
?>
<div class="card mt-4">
    <div class="mb-3 p-2">
        <a href="<?= site_url('dosen/penilaian/export') ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export ke Excel
        </a>
    </div>
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="penilaianTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                    id="dpl-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#dpl"
                    type="button"
                    role="tab"
                    aria-controls="dpl"
                    aria-selected="false">
                    Penilaian DPL
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link"
                    id="guru-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#guru"
                    type="button"
                    role="tab"
                    aria-controls="guru"
                    aria-selected="false">
                    Penilaian Guru Pamong
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button class="nav-link active"
                    id="system-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#system"
                    type="button"
                    role="tab"
                    aria-controls="system"
                    aria-selected="true">
                    Nilai DPL & Guru Pamong
                </button>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content" id="penilaianTabsContent">
            <!-- Tab 1: Nilai Sistem -->
            <div class="tab-pane fade " id="system" role="tabpanel" aria-labelledby="system-tab">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">NIM</th>
                                <th scope="col">F1 </th>
                                <th scope="col">F2 </th>
                                <th scope="col">F3 </th>
                                <th scope="col">F4 </th>
                                <th scope="col">Nilai Akhir</th>
                                <th scope="col">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($result)): ?>
                                <?php foreach ($result['result_system'] as $row): ?>
                                    <tr>
                                        <td><?= $row['student_name'] ?></td>
                                        <td><?= $row['student_nim'] ?></td>
                                        <td><?= $row['f1'] ?></td>
                                        <td><?= $row['f2'] ?></td>
                                        <td><?= $row['f3'] ?></td>
                                        <td><?= $row['f4'] ?></td>
                                        <td><?= $row['nilai_akhir'] ?></td>
                                        <td><?= $row['kategori'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">Tidak ada data nilai sistem.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Penilaian Guru -->
            <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="guru-tab">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Kehadiran</th>
                                <th scope="col">Laporan Kemajuan</th>
                                <th scope="col">Laporan Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($result['result_teacher'])): ?>
                                <?php foreach ($result['result_teacher'] as $row): ?>
                                    <tr>
                                        <td><?= $row['student_name'] ?></td>
                                        <td><?= $row['student_nim'] ?></td>
                                        <td><?= $row['total_hadir'] ?></td>
                                        <td><?= $row['avg_kemajuan'] ?></td>
                                        <td><?= $row['avg_akhir'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada data penilaian guru.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Penilaian DPL -->
            <div class="tab-pane fade show active" id="dpl" role="tabpanel" aria-labelledby="dpl-tab">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">NIM</th>
                                <th scope="col">Kehadiran (F1)</th>
                                <th scope="col">Pres. Kemajuan (F2)</th>
                                <th scope="col">Lap. Kemajuan (F2)</th>
                                <th scope="col">Pres. Akhir (F3)</th>
                                <th scope="col">Lap. Akhir (F4)</th>
                                <th scope="col">Analisis (F4)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($result)): ?>
                                <?php foreach ($result['result_dpl'] as $row): ?>
                                    <tr>
                                        <td><?= $row['student_name'] ?></td>
                                        <td><?= $row['student_nim'] ?></td>
                                        <td><?= $row['hadir_percent'] ?></td>
                                        <td><?= $row['pres_kemajuan_pct'] ?></td>
                                        <td><?= $row['lap_kemajuan_pct'] ?></td>
                                        <td><?= $row['pres_akhir_pct'] ?></td>
                                        <td><?= $row['lap_akhir_pct'] ?></td>
                                        <td><?= $row['analisis_pct'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">Tidak ada data penilaian DPL.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>