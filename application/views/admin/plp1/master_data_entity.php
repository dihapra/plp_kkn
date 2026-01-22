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
                Aktifkan salah satu program PLP I terlebih dahulu di menu <strong>Program</strong> untuk melihat data.
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

<?php if ($entityKey === 'mahasiswa'): ?>
    <div class="modal fade" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentDetailModalLabel">Detail Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="studentDetailList" class="d-grid gap-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

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
            } else if (column.type === 'detail_action') {
                col.orderable = false;
                col.render = function (value) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item action-detail" href="#" data-id="${value}">
                                        <i class="bi bi-eye me-2 text-primary"></i>Detail
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `;
                };
            } else if (column.type === 'edit_action') {
                col.orderable = false;
                col.render = function (value) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item action-edit" href="#" data-id="${value}">
                                        <i class="bi bi-pencil-square me-2 text-dark"></i>Edit
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `;
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

        const table = $('#plpMasterDataTable').DataTable({
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

        function syncSortIcons() {
            const headers = table.columns().header().toArray();
            headers.forEach(function (header, index) {
                const $header = $(header);
                const config = columnsConfig[index] || {};
                const orderable = config.orderable !== false && config.type !== 'detail_action' && config.type !== 'edit_action';
                if (!orderable) {
                    return;
                }
                let $icon = $header.find('.sort-indicator');
                if ($icon.length === 0) {
                    $icon = $('<i class="bi bi-arrow-down-up sort-indicator ms-1 text-muted"></i>');
                    $header.append($icon);
                }
                $icon.removeClass('bi-arrow-up bi-arrow-down bi-arrow-down-up');
                if ($header.hasClass('sorting_asc')) {
                    $icon.addClass('bi-arrow-up');
                } else if ($header.hasClass('sorting_desc')) {
                    $icon.addClass('bi-arrow-down');
                } else {
                    $icon.addClass('bi-arrow-down-up');
                }
            });
        }

        syncSortIcons();
        table.on('order.dt', syncSortIcons);

        const isMahasiswa = <?= $entityKey === 'mahasiswa' ? 'true' : 'false' ?>;
        const isDosen = <?= $entityKey === 'dosen' ? 'true' : 'false' ?>;
        if (isMahasiswa) {
            const detailModalEl = document.getElementById('studentDetailModal');
            const detailModal = detailModalEl ? new bootstrap.Modal(detailModalEl) : null;
            const $detailList = $('#studentDetailList');

            function renderDetailRow(label, value) {
                const safeValue = value !== null && value !== undefined && value !== '' ? value : '-';
                return `
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">${label}</span>
                        <span class="fw-semibold text-end">${safeValue}</span>
                    </div>
                `;
            }

            $('#plpMasterDataTable').on('click', '.action-detail', function (e) {
                e.preventDefault();
                if (!detailModal) {
                    return;
                }
                const rowData = table.row($(this).closest('tr')).data();
                if (!rowData) {
                    return;
                }
                const rows = [
                    renderDetailRow('Nama', rowData.student_name),
                    renderDetailRow('NIM', rowData.nim),
                    renderDetailRow('Email', rowData.email),
                    renderDetailRow('No HP', rowData.phone),
                    renderDetailRow('Program Studi', rowData.program_studi),
                    renderDetailRow('Fakultas', rowData.fakultas),
                    renderDetailRow('Sekolah', rowData.school_name),
                    renderDetailRow('Guru Pamong', rowData.teacher_name),
                    renderDetailRow('DPL', rowData.lecturer_name),
                    renderDetailRow('Status', rowData.status)
                ];
                $detailList.html(rows.join(''));
                detailModal.show();
            });
        }

        if (isDosen) {
            const dosenModalEl = document.getElementById('plpDosenModal');
            const dosenModal = dosenModalEl ? new bootstrap.Modal(dosenModalEl) : null;

            function openEditModal(rowData) {
                if (!dosenModal || !rowData) {
                    return;
                }
                $('#plpDosenId').val(rowData.id || '');
                $('#plpDosenNama').val(rowData.lecturer_name || rowData.nama || '');
                $('#plpDosenNidn').val(rowData.nidn || '');
                $('#plpDosenEmail').val(rowData.email || '');
                $('#plpDosenHp').val(rowData.phone || '');
                const fakultasValue = rowData.fakultas || '';
                const prodiValue = rowData.id_prodi || '';
                $('#plpDosenFakultas')
                    .val(fakultasValue)
                    .trigger('change', { desiredValue: prodiValue });
                dosenModal.show();
            }

            $('#plpMasterDataTable').on('click', '.action-edit', function (e) {
                e.preventDefault();
                const rowData = table.row($(this).closest('tr')).data();
                if (!rowData) {
                    return;
                }
                openEditModal(rowData);
            });

            $('#plpDosenForm').on('submit', async function (e) {
                e.preventDefault();
                const id = $('#plpDosenId').val();
                if (!id) {
                    return;
                }

                const formData = new FormData(this);

                try {
                    const response = await fetch(`${baseUrl}admin/dosen/update/${id}`, {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (!response.ok) {
                        const message = result?.message || 'Gagal menyimpan data dosen';
                        throw new Error(message);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Diperbarui',
                        text: result?.message || 'Data dosen berhasil diperbarui.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        dosenModal.hide();
                        table.ajax.reload(null, false);
                    });
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Terjadi kesalahan.'
                    });
                }
            });
        }
    });
</script>

<?php if ($entityKey === 'dosen'): ?>
    <div class="modal fade" id="plpDosenModal" tabindex="-1" aria-labelledby="plpDosenModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="plpDosenModalTitle">Edit Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="plpDosenForm">
                    <div class="modal-body">
                        <input type="hidden" id="plpDosenId" name="id">
                        <div class="mb-3">
                            <label for="plpDosenNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="plpDosenNama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="plpDosenNidn" class="form-label">NIP / NIDN</label>
                            <input type="text" class="form-control" id="plpDosenNidn" name="nidn" required>
                        </div>
                        <div class="mb-3">
                            <label for="plpDosenEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="plpDosenEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="plpDosenHp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="plpDosenHp" name="no_hp">
                        </div>
                        <div class="mb-3">
                            <label for="plpDosenFakultas" class="form-label">Fakultas</label>
                            <select
                                class="form-select sa-fakultas-select"
                                id="plpDosenFakultas"
                                name="fakultas"
                                data-placeholder="Pilih Fakultas"
                                data-prodi-target="#plpDosenProdi">
                                <option value="">Pilih Fakultas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="plpDosenProdi" class="form-label">Program Studi</label>
                            <select
                                class="form-select sa-prodi-select"
                                id="plpDosenProdi"
                                name="id_prodi"
                                data-placeholder="Pilih Prodi"
                                data-faculty-source="#plpDosenFakultas"
                                required>
                                <option value="">Pilih Prodi</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
