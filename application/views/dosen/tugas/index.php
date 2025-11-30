<?php

/**
 * View: application/views/dosen/tugas/index.php
 * Prasyarat: controller mengirim $tugas_kelompok, $tugas_individu
 * Catatan:
 * - Endpoint per type diatur lewat $endpointMap (view/nilai/edit).
 * - Ganti path di $endpointMap sesuai route di aplikasi kamu.
 */

// ---- Helper: build url dari template "path/{id}" ----
if (!function_exists('build_endpoint_url')) {
    function build_endpoint_url($tpl, $id)
    {
        if (!$tpl || !$id) return 'javascript:void(0);';
        $path = str_replace('{id}', $id, $tpl);
        // Jika sudah absolute (http...), jangan base_url
        if (preg_match('~^https?://~i', $path)) return $path;
        return base_url($path);
    }
}

if (!function_exists('render_submission_cell')) {
    /**
     * Render satu sel status + tombol aksi.
     * @param mixed       $id            submission_id (null jika belum upload)
     * @param int|string  $uploaded      "0"/"1" atau 0/1
     * @param int|string  $scored_by_me  "0"/"1" atau 0/1
     * @param string      $label         label opsional (title)
     * @param int         $type          1..5 (untuk memilih endpoint)
     * @param array       $endpointMap   peta endpoint per type:
     *                                   [type => ['view'=>'path/{id}','score'=>'path/{id}','edit'=>'path/{id}']]
     * @return string HTML
     */
    function render_submission_cell($id, $uploaded, $scored_by_me, $label, $type, array $endpointMap)
    {
        $uploaded = (int)$uploaded === 1;
        $scored   = (int)$scored_by_me === 1;

        // Ambil template endpoint untuk type terkait
        $cfg     = $endpointMap[$type] ?? [];
        $viewUrl = $id ? build_endpoint_url($cfg['view']  ?? null, $id) : 'javascript:void(0);';
        $formUrl = $id ? build_endpoint_url($cfg['score'] ?? null, $id) : 'javascript:void(0);';
        $editUrl = $id ? build_endpoint_url($cfg['edit']  ?? null, $id) : 'javascript:void(0);';

        ob_start(); ?>
        <div class="text-center" title="<?= htmlentities($label) ?>">
            <?php if (!$uploaded): ?>
                <span class="badge bg-danger">Belum Upload</span><br>
                <div class="btn-group mt-1">
                    <a class="btn btn-sm btn-primary disabled"><i class="bi bi-eye"></i> Lihat</a>
                    <a class="btn btn-sm btn-success disabled"><i class="bi bi-pencil-square"></i> Nilai</a>
                </div>
            <?php else: ?>
                <?php if ($scored): ?>
                    <span class="badge bg-success">Sudah Dinilai</span><br>
                    <div class="btn-group mt-1">
                        <a href="<?= $viewUrl ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        <a href="<?= $editUrl ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil-square"></i> Edit Nilai
                        </a>
                    </div>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Perlu Dinilai</span><br>
                    <div class="btn-group mt-1">
                        <a href="<?= $viewUrl ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        <a href="<?= $formUrl ?>" class="btn btn-sm btn-success">
                            <i class="bi bi-pencil-square"></i> Nilai
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}

// ---- Peta endpoint per jenis ----
// 1 = Laporan Kemajuan, 2 = Laporan Akhir, 3 = Modul Ajar, 4 = Bahan Ajar, 5 = Modul Projek
$endpointMap = [
    1 => [
        'view'  => 'dosen/laporan-kemajuan/view/{id}',
        'score' => 'dosen/laporan-kemajuan/nilai/{id}',
        'edit'  => 'dosen/laporan-kemajuan/edit/{id}',
    ],
    2 => [
        'view'  => 'dosen/laporan-akhir/view/{id}',
        'score' => 'dosen/laporan-akhir/nilai/{id}',
        'edit'  => 'dosen/laporan-akhir/edit/{id}',
    ],
    3 => [
        'view'  => 'dosen/modul-ajar/view/{id}',
        'score' => 'dosen/modul-ajar/nilai/{id}',
        'edit'  => 'dosen/modul-ajar/edit/{id}',
    ],
    4 => [
        'view'  => 'dosen/bahan-ajar/view/{id}',
        'score' => 'dosen/bahan-ajar/nilai/{id}',
        'edit'  => 'dosen/bahan-ajar/edit/{id}',
    ],
    5 => [
        'view'  => 'dosen/modul-projek/view/{id}',
        'score' => 'dosen/modul-projek/nilai/{id}',
        'edit'  => 'dosen/modul-projek/edit/{id}',
    ],
];
?>

<!-- Optional mini CSS untuk rapihin tampilan -->
<style>
    .table td,
    .table th {
        vertical-align: middle;
    }

    .badge {
        min-width: 110px;
    }
</style>

<div class="card">
    <div class="card-body">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="submissionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="kelompok-tab" data-bs-toggle="tab" data-bs-target="#kelompok" type="button" role="tab">
                    Tugas Kelompok
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="individu-tab" data-bs-toggle="tab" data-bs-target="#individu" type="button" role="tab">
                    Tugas Individu
                </button>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- TAB KELOMPOK -->
            <div class="tab-pane fade show active" id="kelompok" role="tabpanel" aria-labelledby="kelompok-tab">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nama Kelompok</th>
                            <th>Anggota</th>
                            <th>Laporan Kemajuan</th>
                            <th>Laporan Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tugas_kelompok)): ?>
                            <?php foreach ($tugas_kelompok as $row): ?>
                                <tr>
                                    <td><?= htmlentities($row->group_name ?? '-') ?></td>
                                    <td><?= $row->members ?: '-' ?></td>

                                    <!-- Laporan Kemajuan (type=1) -->
                                    <td>
                                        <?= render_submission_cell(
                                            $row->sub1_id ?? null,
                                            $row->sub1_uploaded ?? '0',
                                            $row->sub1_scored_by_me ?? '0',
                                            'Laporan Kemajuan',
                                            1,
                                            $endpointMap
                                        ) ?>
                                    </td>

                                    <!-- Laporan Akhir (type=2) -->
                                    <td>
                                        <?= render_submission_cell(
                                            $row->sub2_id ?? null,
                                            $row->sub2_uploaded ?? '0',
                                            $row->sub2_scored_by_me ?? '0',
                                            'Laporan Akhir',
                                            2,
                                            $endpointMap
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data kelompok</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- TAB INDIVIDU -->
            <div class="tab-pane fade" id="individu" role="tabpanel" aria-labelledby="individu-tab">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Modul Ajar</th>
                            <th>Bahan Ajar</th>
                            <th>Modul Projek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($tugas_individu)): ?>
                            <?php foreach ($tugas_individu as $row): ?>
                                <tr>
                                    <td><?= htmlentities($row->student_name ?? '-') ?></td>

                                    <!-- Modul Ajar (type=3) -->
                                    <td>
                                        <?= render_submission_cell(
                                            $row->sub3_id ?? null,
                                            $row->sub3_uploaded ?? '0',
                                            $row->sub3_scored_by_me ?? '0',
                                            'Modul Ajar',
                                            3,
                                            $endpointMap
                                        ) ?>
                                    </td>

                                    <!-- Bahan Ajar (type=4) -->
                                    <td>
                                        <?= render_submission_cell(
                                            $row->sub4_id ?? null,
                                            $row->sub4_uploaded ?? '0',
                                            $row->sub4_scored_by_me ?? '0',
                                            'Bahan Ajar',
                                            4,
                                            $endpointMap
                                        ) ?>
                                    </td>

                                    <!-- Modul Projek (type=5) -->
                                    <td>
                                        <?= render_submission_cell(
                                            $row->sub5_id ?? null,
                                            $row->sub5_uploaded ?? '0',
                                            $row->sub5_scored_by_me ?? '0',
                                            'Modul Projek',
                                            5,
                                            $endpointMap
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data individu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>