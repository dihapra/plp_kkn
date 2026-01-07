<?php
$activeProgram = $activeProgram ?? null;
$config = $config ?? [];
$entityKey = $entityKey ?? '';
$columns = $config['columns'] ?? [];
$title = $config['title'] ?? 'Master Data';
$description = $config['description'] ?? '';
$datatablePath = $datatablePath ?? '';
$hasActiveProgram = !empty($activeProgram);
$activeLabel = $hasActiveProgram
    ? sprintf(
        '%s (%s)',
        strtoupper($activeProgram['kode'] ?? 'PLP I'),
        $activeProgram['tahun_ajaran'] ?? 'T/A'
    )
    : null;
?>

<div class="super-admin-program super-admin-masterdata">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1"><?= htmlspecialchars($title) ?> - PLP I</h3>
            <p class="mb-0 text-muted"><?= htmlspecialchars($description) ?></p>
        </div>
        <span class="badge <?= $hasActiveProgram ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' ?> px-3 py-2">
            <?php if ($hasActiveProgram): ?>
                <i class="bi bi-lightning-charge me-1"></i> Program Aktif: <?= htmlspecialchars($activeLabel) ?>
            <?php else: ?>
                <i class="bi bi-exclamation-triangle me-1"></i> Belum ada program aktif
            <?php endif; ?>
        </span>
    </div>

    <?php if (!$hasActiveProgram): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-info-circle"></i>
            <div>
                Aktifkan salah satu program PLP I terlebih dahulu di menu <strong>Master Data &gt; Program</strong> untuk melihat data.
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><?= htmlspecialchars($title) ?></span>
            <small class="text-muted">Data otomatis terfilter untuk program PLP I yang sedang aktif.</small>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="plpMasterDataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <?php foreach ($columns as $column): ?>
                                <th><?= htmlspecialchars($column['label'] ?? '-') ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$hasActiveProgram): ?>
                            <tr>
                                <td colspan="<?= count($columns) ?>" class="text-center text-muted">Belum ada program PLP I aktif.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        const hasProgram = <?= $hasActiveProgram ? 'true' : 'false' ?>;
        if (!hasProgram) {
            return;
        }

        const columnsConfig = <?= json_encode($columns, JSON_UNESCAPED_UNICODE) ?>;
        const datatablePath = <?= json_encode($datatablePath, JSON_UNESCAPED_SLASHES) ?>;

        function renderStatusBadge(status) {
            if (!status) {
                return '-';
            }
            const key = status.toLowerCase();
            const map = {
                verified: { label: 'Verified', variant: 'success' },
                unverified: { label: 'Unverified', variant: 'warning' },
                rejected: { label: 'Rejected', variant: 'danger' },
            };
            const meta = map[key] || { label: status, variant: 'secondary' };
            return `<span class="badge bg-${meta.variant}">${meta.label}</span>`;
        }

        function renderPaymentBadge(status) {
            if (!status) {
                return '-';
            }
            const key = status.toLowerCase();
            const map = {
                dibayar: { label: 'Dibayar', variant: 'success' },
                'belum dibayar': { label: 'Belum Dibayar', variant: 'warning text-dark' },
            };
            const meta = map[key] || { label: status, variant: 'secondary' };
            return `<span class="badge bg-${meta.variant}">${meta.label}</span>`;
        }

        const datatableColumns = columnsConfig.map(function (column) {
            const col = {
                data: column.data || '',
                orderable: column.orderable !== undefined ? column.orderable : true
            };
            if (column.className) {
                col.className = column.className;
            }
            if (column.type === 'status_badge') {
                col.render = function (value) {
                    return renderStatusBadge(value);
                };
            } else if (column.type === 'payment_badge') {
                col.render = function (value) {
                    return renderPaymentBadge(value);
                };
            } else {
                col.render = function (value) {
                    if (value === null || value === undefined || value === '') {
                        return '-';
                    }
                    return value;
                };
            }
            return col;
        });

        $('#plpMasterDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}${datatablePath}`,
                type: 'POST'
            },
            language: {
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ data",
                sZeroRecords: "Tidak ditemukan data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sInfoFiltered: "(disaring dari _MAX_ data keseluruhan)",
                sSearch: "Cari:",
                oPaginate: {
                    sFirst: "Pertama",
                    sPrevious: "Sebelumnya",
                    sNext: "Selanjutnya",
                    sLast: "Terakhir"
                }
            },
            columns: datatableColumns,
            order: [[0, 'asc']]
        });
    });
</script>
