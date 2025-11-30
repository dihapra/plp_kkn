<?php

if (!function_exists('build_url_sid')) {
    function build_url_sid($tpl, $student_id)
    {
        if (!$tpl || !$student_id) return 'javascript:void(0);';
        $path = str_replace('{student_id}', $student_id, $tpl);
        if (preg_match('~^https?://~i', $path)) return $path;
        return base_url($path);
    }
}

if (!function_exists('render_evaluation_status')) {
    function render_evaluation_status($hasScore, $label)
    {
        ob_start(); ?>
        <div class="text-center" title="<?= htmlentities($label) ?>">
            <?php if (!empty($hasScore)):
                $hasScore = (int)$hasScore > 0; // Convert to boolean
            ?>
                <?php if ($hasScore): ?>
                    <span class="badge bg-success">Sudah Dinilai</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Belum Dinilai</span>
                <?php endif; ?>
            <?php else: ?>
                <span class="badge bg-warning text-dark">Belum Dinilai</span>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}

if (!function_exists('render_evaluation_actions')) {
    function render_evaluation_actions($hasScore, $student_id, $label, $type, array $endpointMap)
    {
        $cfg = $endpointMap[$type] ?? [];
        $viewUrl  = build_url_sid($cfg['view']  ?? null, $student_id);
        $scoreUrl = build_url_sid($cfg['score'] ?? null, $student_id);
        $editUrl  = build_url_sid($cfg['edit']  ?? null, $student_id);

        ob_start(); ?>
        <div class="text-center">
            <?php if (!empty($hasScore)):
                $hasScore = (int)$hasScore > 0; // Convert to boolean
            ?>
                <?php if ($hasScore): ?>
                    <div class="btn-group mt-1">
                        <a href="<?= $viewUrl ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                        <a href="<?= $editUrl ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil-square"></i> Edit Nilai
                        </a>
                    </div>
                <?php else: ?>
                    <div class="btn-group mt-1">
                        <a class="btn btn-sm btn-primary disabled"><i class="bi bi-eye"></i> Lihat</a>
                        <a href="<?= $scoreUrl ?>" class="btn btn-sm btn-success">
                            <i class="bi bi-pencil-square"></i> Nilai
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
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

$endpointMap = [
    'sikap' => [
        'view'  => 'guru/sikap_view/{student_id}',
        'score' => 'guru/sikap/{student_id}',
        'edit'  => 'guru/sikap_edit/{student_id}',
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
        <h4 class="mb-3">Penilaian Sikap Mahasiswa Per Mahasiswa</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="datatable-penilaian">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="min-width:260px">Nama Mahasiswa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)):
                        foreach ($students as $row):
                            $sid   = is_object($row) ? ($row->student_id ?? null) : ($row['student_id'] ?? null);
                            $sname = is_object($row) ? ($row->student_name ?? '-') : ($row['student_name'] ?? '-');

                            $has_sikap = is_object($row) ? (int)($row->has_attitude ?? 0) : (int)($row['has_attitude'] ?? 0);
                    ?>
                            <tr>
                                <td><?= htmlentities($sname) ?></td>

                                <!-- Sikap -->
                                <td>
                                    <?= render_evaluation_status($has_sikap, 'Penilaian Sikap Mahasiswa') ?>
                                </td>
                                <td>
                                    <?= render_evaluation_actions($has_sikap, $sid, 'Penilaian Sikap Mahasiswa', 'sikap', $endpointMap) ?>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data mahasiswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>