<?php
// ---- Helper URL {student_id} ----
if (!function_exists('build_url_sid')) {
    function build_url_sid($tpl, $student_id)
    {
        if (!$tpl || !$student_id) return 'javascript:void(0);';
        $path = str_replace('{student_id}', $student_id, $tpl);
        if (preg_match('~^https?://~i', $path)) return $path;
        return base_url($path);
    }
}

if (!function_exists('render_cell_by_flag')) {
    /**
     * @param int|bool $hasScore    >0 berarti sudah dinilai (TRUE)
     * @param int      $student_id
     * @param string   $label
     * @param string   $type        'intrakurikuler'|'kokurikuler'|'sikap'
     * @param array    $endpointMap ['view'=>..., 'score'=>..., 'edit'=>...]
     */
    function render_cell_by_flag($hasScore, $student_id, $label, $type, array $endpointMap)
    {
        $cfg = $endpointMap[$type] ?? [];
        $viewUrl  = build_url_sid($cfg['view']  ?? null, $student_id);
        $scoreUrl = build_url_sid($cfg['score'] ?? null, $student_id);
        $editUrl  = build_url_sid($cfg['edit']  ?? null, $student_id);

        ob_start(); ?>
        <div class="text-center" title="<?= htmlentities($label) ?>">
            <?php if (!empty($hasScore)): ?>
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
                <span class="badge bg-warning text-dark">Belum Dinilai</span><br>
                <div class="btn-group mt-1">
                    <a class="btn btn-sm btn-primary disabled"><i class="bi bi-eye"></i> Lihat</a>
                    <a href="<?= $scoreUrl ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-pencil-square"></i> Nilai
                    </a>
                </div>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}

// ---- Endpoint pakai {student_id} semuanya ----
$endpointMap = [
    'intrakurikuler' => [
        'view'  => 'dosen/penilaian/intrakurikuler/view/{student_id}',
        'score' => 'dosen/penilaian/intrakurikuler/{student_id}',
        'edit'  => 'dosen/penilaian/intrakurikuler/edit/{student_id}',
    ],
    'kokurikuler' => [
        'view'  => 'dosen/penilaian/ekstrakurikuler/view/{student_id}',
        'score' => 'dosen/penilaian/ekstrakurikuler/{student_id}',
        'edit'  => 'dosen/penilaian/ekstrakurikuler/edit/{student_id}',
    ],
    'sikap' => [
        'view'  => 'dosen/penilaian/sikap/view/{student_id}',
        'score' => 'dosen/penilaian/sikap/{student_id}',
        'edit'  => 'dosen/penilaian/sikap/edit/{student_id}',
    ],
];
?>

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
        <h4 class="mb-3">Penilaian Per Mahasiswa</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="datatable-penilaian">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="min-width:260px">Nama Mahasiswa</th>
                        <th>Asistensi Intrakurikuler</th>
                        <th>Ko/Kurikuler & Ekstrakurikuler</th>
                        <th>Sikap Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $row): ?>
                            <?php
                            $sid   = is_object($row) ? ($row->student_id ?? null) : ($row['student_id'] ?? null);
                            $sname = is_object($row) ? ($row->student_name ?? '-') : ($row['student_name'] ?? '-');

                            $has_intra = is_object($row) ? (int)($row->has_cocurricular ?? 0) : (int)($row['has_cocurricular'] ?? 0); // intrakurikuler = cocullicular
                            $has_extra = is_object($row) ? (int)($row->has_extracurricular ?? 0)
                                : (int)($row['has_extracurricular'] ?? 0);
                            $has_sikap = is_object($row) ? (int)($row->has_attitude ?? 0) : (int)($row['has_attitude'] ?? 0);
                            ?>
                            <tr>
                                <td><?= htmlentities($sname) ?></td>

                                <!-- Intrakurikuler (ambil dari assist_cocullicular) -->
                                <td>
                                    <?= render_cell_by_flag($has_intra, $sid, 'Penilaian Asistensi Intrakurikuler', 'intrakurikuler', $endpointMap) ?>
                                </td>

                                <!-- Ko/Kurikuler & Ekstrakurikuler (gabungan: salah satu ada = sudah dinilai) -->
                                <td>
                                    <?= render_cell_by_flag($has_extra, $sid, 'Penilaian Ko/Kurikuler & Ekstrakurikuler', 'kokurikuler', $endpointMap) ?>
                                </td>

                                <!-- Sikap -->
                                <td>
                                    <?= render_cell_by_flag($has_sikap, $sid, 'Penilaian Sikap Mahasiswa', 'sikap', $endpointMap) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data mahasiswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>