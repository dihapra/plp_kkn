<?php
if (!function_exists('build_endpoint_url')) {
    function build_endpoint_url($tpl, $id)
    {
        if (!$tpl || !$id) return 'javascript:void(0);';
        $path = str_replace('{id}', $id, $tpl);
        if (preg_match('~^https?://~i', $path)) return $path;
        return base_url($path);
    }
}

if (!function_exists('render_submission_status')) {
    function render_submission_status($id, $uploaded, $scored_by_me, $label, $type, array $endpointMap)
    {
        $uploaded = (int)$uploaded === 1;
        $scored   = (int)$scored_by_me === 1;

        ob_start(); ?>
        <div class="text-center" title="<?= htmlentities($label) ?>">
            <?php if (!$uploaded): ?>
                <span class="badge bg-danger">Belum Upload</span>
            <?php else: ?>
                <?php if ($scored): ?>
                    <span class="badge bg-success">Sudah Dinilai</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Perlu Dinilai</span>
                <?php endif; ?>
            <?php endif; ?>
        </div>
<?php
        return ob_get_clean();
    }
}

if (!function_exists('render_submission_actions')) {
    function render_submission_actions($id, $uploaded, $scored_by_me, $label, $type, array $endpointMap)
    {
        $uploaded = (int)$uploaded === 1;
        $scored   = (int)$scored_by_me === 1;
        $cfg     = $endpointMap[$type] ?? [];
        $viewUrl = $id ? build_endpoint_url($cfg['view']  ?? null, $id) : 'javascript:void(0);';
        $formUrl = $id ? build_endpoint_url($cfg['score'] ?? null, $id) : 'javascript:void(0);';
        $editUrl = $id ? build_endpoint_url($cfg['edit']  ?? null, $id) : 'javascript:void(0);';

        ob_start(); ?>
        <div class="text-center">
            <?php if (!$uploaded): ?>
                <div class="btn-group mt-1">
                    <a class="btn btn-sm btn-primary disabled"><i class="bi bi-eye"></i> Lihat</a>
                    <a class="btn btn-sm btn-success disabled"><i class="bi bi-pencil-square"></i> Nilai</a>
                </div>
            <?php else: ?>
                <?php if ($scored): ?>
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

$endpointMap = [
    1 => [
        'view'  => 'dosen/laporan-kemajuan/view/{id}',
        'score' => 'dosen/laporan-kemajuan/nilai/{id}',
        'edit'  => 'dosen/laporan-kemajuan/edit/{id}',
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
        <h4 class="card-title">Tugas Laporan Kemajuan</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nama Kelompok</th>
                    <th>Anggota</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tugas_kelompok)):
                    foreach ($tugas_kelompok as $row):
                ?>
                        <tr>
                            <td><?= htmlentities($row->group_name ?? '-') ?></td>
                            <td><?= $row->members ?: '-' ?></td>
                            <td>
                                <?= render_submission_status(
                                    $row->sub_id ?? null,
                                    $row->sub_uploaded ?? '0',
                                    $row->sub_scored_by_me ?? '0',
                                    'Laporan Kemajuan',
                                    1,
                                    $endpointMap
                                ) ?>
                            </td>
                            <td>
                                <?= render_submission_actions(
                                    $row->sub_id ?? null,
                                    $row->sub_uploaded ?? '0',
                                    $row->sub_scored_by_me ?? '0',
                                    'Laporan Kemajuan',
                                    1,
                                    $endpointMap
                                ) ?>
                            </td>
                        </tr>
                <?php 
                    endforeach;
                else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data kelompok</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>