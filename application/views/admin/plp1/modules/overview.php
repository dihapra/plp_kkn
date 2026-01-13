<?php
$moduleLabel = $module_label ?? 'Modul';
$pageTitle   = $page_title ?? '';
$description = $description ?? '';
$program     = $active_program ?? null;
$summary     = $summary ?? [];

$programName = $program['nama'] ?? '';
$tahunAjaran = $program['tahun_ajaran'] ?? '';
$programInfo = trim($programName . ($tahunAjaran !== '' ? ' - ' . $tahunAjaran : ''));
?>

<div class="mt-3 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <p class="text-uppercase small text-muted mb-1"><?= htmlspecialchars($moduleLabel) ?></p>
            <h3 class="mb-1"><?= htmlspecialchars($moduleLabel . ' - ' . $pageTitle) ?></h3>
            <?php if ($description !== ''): ?>
                <p class="text-muted mb-0"><?= htmlspecialchars($description) ?></p>
            <?php endif; ?>
            <?php if ($programInfo !== ''): ?>
                <p class="text-muted small mb-0">Tahun ajaran: <?= htmlspecialchars($programInfo) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-1">Total mahasiswa</p>
                <h4 class="mb-0"><?= (int) ($summary['mahasiswa'] ?? 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-1">Total dosen</p>
                <h4 class="mb-0"><?= (int) ($summary['dosen'] ?? 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-1">Total kepala sekolah</p>
                <h4 class="mb-0"><?= (int) ($summary['kepsek'] ?? 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card h-100 border-light shadow-sm">
            <div class="card-body">
                <p class="text-muted small mb-1">Total sekolah</p>
                <h4 class="mb-0"><?= (int) ($summary['sekolah'] ?? 0) ?></h4>
            </div>
        </div>
    </div>
</div>
