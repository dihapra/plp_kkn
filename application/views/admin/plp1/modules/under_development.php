<?php
$moduleLabel = $module_label ?? 'Modul';
$pageTitle   = $page_title ?? '';
$description = $description ?? '';
$program     = $active_program ?? null;

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
        <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
            <i class="bi bi-tools me-1"></i> Under development
        </span>
    </div>
</div>

<div class="card border-light shadow-sm">
    <div class="card-body">
        <p class="mb-0 text-muted">Fitur ini sedang dikembangkan. Silakan cek kembali setelah rilis berikutnya.</p>
    </div>
</div>
