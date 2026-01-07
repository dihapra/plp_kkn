<?php
$moduleLabel = $module_label ?? 'Modul';
$pageTitle   = $page_title ?? '';
$description = $description ?? '';
$highlights  = $highlights ?? [];
$checklist   = $checklist ?? [];
$links       = $supporting_links ?? [];
?>

<div class="super-admin-module">
    <div class="d-flex justify-content-between align-items-start mt-3 mb-4 flex-wrap gap-3">
        <div>
            <p class="text-uppercase small text-muted mb-1"><?= htmlspecialchars($moduleLabel) ?></p>
            <h3 class="mb-1"><?= htmlspecialchars($moduleLabel . ' - ' . $pageTitle) ?></h3>
            <p class="text-muted mb-0"><?= htmlspecialchars($description) ?></p>
        </div>
        <span class="badge bg-primary-subtle text-primary px-3 py-2">
            <i class="bi bi-tools me-1"></i> Sedang disiapkan
        </span>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($highlights as $card): ?>
            <div class="col-md-4">
                <div class="card h-100 border-light shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <?php if (!empty($card['icon'])): ?>
                                <span class="text-primary me-2"><i class="bi <?= htmlspecialchars($card['icon']) ?> fs-5"></i></span>
                            <?php endif; ?>
                            <h6 class="mb-0"><?= htmlspecialchars($card['title'] ?? '-') ?></h6>
                        </div>
                        <p class="mb-0 text-muted small"><?= htmlspecialchars($card['body'] ?? '') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-light shadow-sm h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="fw-semibold">Checklist Persiapan</span>
                    <span class="badge bg-light text-muted text-uppercase">Draft</span>
                </div>
                <div class="card-body">
                    <?php if (empty($checklist)): ?>
                        <p class="text-muted mb-0">Belum ada checklist yang terdaftar.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($checklist as $item): ?>
                                <?php
                                $status = $item['status'] ?? 'pending';
                                $badge  = [
                                    'done'     => 'success',
                                    'progress' => 'warning',
                                    'pending'  => 'secondary',
                                ][$status] ?? 'secondary';
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><?= htmlspecialchars($item['label'] ?? '-') ?></span>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-light shadow-sm h-100">
                <div class="card-header">
                    <span class="fw-semibold">Dokumen & Tautan Pendukung</span>
                </div>
                <div class="card-body d-flex flex-column gap-3">
                    <?php if (empty($links)): ?>
                        <p class="text-muted mb-0">Belum ada tautan pendukung tersedia.</p>
                    <?php else: ?>
                        <?php foreach ($links as $link): ?>
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong><?= htmlspecialchars($link['label'] ?? '-') ?></strong>
                                    <?php if (!empty($link['status'])): ?>
                                        <span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($link['status']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= htmlspecialchars($link['url'] ?? '#') ?>" class="text-decoration-none small">
                                    <?= htmlspecialchars($link['url'] ?? '#') ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4 mb-0" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Catatan:</strong>
                Modul ini sudah tersedia di sidebar sehingga tim dapat mempersiapkan alur kerja sambil menunggu integrasi final.
            </div>
        </div>
    </div>
</div>
