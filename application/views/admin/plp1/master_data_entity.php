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
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="fw-semibold"><?= htmlspecialchars($title) ?></span>
                <div class="text-muted small">Data otomatis terfilter untuk program PLP I yang sedang aktif.</div>
            </div>
            <?php if ($entityKey === 'mahasiswa' && $hasActiveProgram): ?>
                <button type="button" class="btn btn-sm btn-primary" id="btnAddPlpMahasiswa">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Mahasiswa
                </button>
            <?php endif; ?>
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
    <style>
        .plp-mahasiswa-modal .modal-content {
            background: #0f172a;
            color: #f8fafc;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .plp-mahasiswa-modal .modal-header,
        .plp-mahasiswa-modal .modal-body,
        .plp-mahasiswa-modal .modal-footer {
            background: transparent;
        }

        .plp-mahasiswa-modal .form-label,
        .plp-mahasiswa-modal .form-text,
        .plp-mahasiswa-modal small,
        .plp-mahasiswa-modal .text-muted {
            color: rgba(248, 250, 252, 0.72) !important;
        }

        .plp-mahasiswa-modal .form-control,
        .plp-mahasiswa-modal .form-select {
            background-color: #111827;
            border-color: rgba(148, 163, 184, 0.25);
            color: #f8fafc;
        }

        .plp-mahasiswa-modal .form-control::placeholder {
            color: rgba(148, 163, 184, 0.65);
        }

        .plp-mahasiswa-modal .btn-close {
            filter: invert(1) grayscale(1);
        }
    </style>
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
    <div class="modal fade plp-mahasiswa-modal" id="plpMahasiswaModal" tabindex="-1" aria-labelledby="plpMahasiswaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="plpMahasiswaModalLabel">Tambah Mahasiswa PLP I</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="plpMahasiswaForm">
                    <div class="modal-body pt-0">
                        <input type="hidden" id="plpMahasiswaId" name="id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="plpMahasiswaNama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="plpMahasiswaNama" name="nama" required>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaNim" class="form-label">NIM</label>
                                <input type="text" class="form-control" id="plpMahasiswaNim" name="nim" required>
                                <small class="text-muted">Jika NIM sudah terdaftar, data akan diperbarui.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="plpMahasiswaEmail" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaHp" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="plpMahasiswaHp" name="no_hp" placeholder="opsional">
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaFakultas" class="form-label">Fakultas</label>
                                <select
                                    class="form-select sa-fakultas-select"
                                    id="plpMahasiswaFakultas"
                                    name="fakultas"
                                    data-placeholder="Pilih Fakultas"
                                    data-prodi-target="#plpMahasiswaProdi">
                                    <option value="">Pilih Fakultas</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaProdi" class="form-label">Program Studi</label>
                                <select
                                    class="form-select sa-prodi-select"
                                    id="plpMahasiswaProdi"
                                    name="id_prodi"
                                    data-placeholder="Pilih Prodi"
                                    data-faculty-source="#plpMahasiswaFakultas"
                                    required>
                                    <option value="">Pilih Prodi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaSekolah" class="form-label">Sekolah</label>
                                <select class="form-select" id="plpMahasiswaSekolah" name="id_sekolah">
                                    <option value="">Pilih Sekolah</option>
                                </select>
                                <small class="text-muted">Opsional</small>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaGuru" class="form-label">Guru Pamong</label>
                                <select class="form-select" id="plpMahasiswaGuru" name="id_guru">
                                    <option value="">Pilih Guru Pamong</option>
                                </select>
                                <small class="text-muted">Opsional</small>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaDosen" class="form-label">DPL</label>
                                <select class="form-select" id="plpMahasiswaDosen" name="id_dosen">
                                    <option value="">Pilih DPL</option>
                                </select>
                                <small class="text-muted">Opsional</small>
                            </div>
                            <div class="col-md-6">
                                <label for="plpMahasiswaStatus" class="form-label">Status</label>
                                <input type="hidden" name="status" value="verified">
                                <select class="form-select" id="plpMahasiswaStatus" disabled>
                                    <option value="verified" selected>Verified</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="plpMahasiswaSubmitBtn">Simpan</button>
                    </div>
                </form>
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
        const isMahasiswa = <?= $entityKey === 'mahasiswa' ? 'true' : 'false' ?>;
        const isDosen = <?= $entityKey === 'dosen' ? 'true' : 'false' ?>;

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
                    const editItem = isMahasiswa ? `
                        <li>
                            <a class="dropdown-item action-edit" href="#" data-id="${value}">
                                <i class="bi bi-pencil-square me-2 text-dark"></i>Edit
                            </a>
                        </li>
                    ` : '';
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
                                ${editItem}
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

        if (isMahasiswa) {
            const detailModalEl = document.getElementById('studentDetailModal');
            const detailModal = detailModalEl ? new bootstrap.Modal(detailModalEl) : null;
            const $detailList = $('#studentDetailList');
            const createModalEl = document.getElementById('plpMahasiswaModal');
            const createModal = createModalEl ? new bootstrap.Modal(createModalEl) : null;
            const optionUrl = `${baseUrl}admin/plp1/master-data/mahasiswa/options`;
            const storeUrl = `${baseUrl}admin/plp1/master-data/mahasiswa/store`;
            let selectOptions = null;

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

            function normalizeOptions(items) {
                return (items || []).map(function (item) {
                    return {
                        id: item.id,
                        name: item.nama || item.name || '-'
                    };
                });
            }

            function setSelectOptions($select, items, placeholder) {
                if (!$select.length) {
                    return;
                }
                $select.empty();
                $select.append(new Option(placeholder, '', false, false));
                items.forEach(function (item) {
                    $select.append(new Option(item.name, item.id, false, false));
                });
            }

            function initSelect2($select, dropdownParent) {
                if (!$select.length || !$select.select2) {
                    return;
                }
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
                $select.select2({
                    placeholder: $select.find('option').first().text() || 'Pilih',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: dropdownParent || undefined
                });
            }

            function resetMahasiswaForm() {
                $('#plpMahasiswaId').val('');
                $('#plpMahasiswaNama').val('');
                $('#plpMahasiswaNim').val('');
                $('#plpMahasiswaEmail').val('');
                $('#plpMahasiswaHp').val('');
                $('#plpMahasiswaFakultas').val('').trigger('change');
                $('#plpMahasiswaProdi').val('').trigger('change');
                $('#plpMahasiswaSekolah').val('');
                $('#plpMahasiswaGuru').val('');
                $('#plpMahasiswaDosen').val('');
                $('#plpMahasiswaStatus').val('verified');
                $('#plpMahasiswaModalLabel').text('Tambah Mahasiswa PLP I');
                $('#plpMahasiswaSubmitBtn').text('Simpan');
            }

            async function loadMahasiswaOptions() {
                if (selectOptions) {
                    return selectOptions;
                }

                const response = await fetch(optionUrl, { method: 'GET' });
                const result = await response.json();
                if (!response.ok) {
                    const message = result?.message || 'Gagal memuat data pendukung.';
                    throw new Error(message);
                }

                selectOptions = {
                    schools: normalizeOptions(result.data?.schools || result.schools),
                    teachers: normalizeOptions(result.data?.teachers || result.teachers),
                    lecturers: normalizeOptions(result.data?.lecturers || result.lecturers)
                };

                return selectOptions;
            }

            function applyMahasiswaOptions(options) {
                const $school = $('#plpMahasiswaSekolah');
                const $teacher = $('#plpMahasiswaGuru');
                const $lecturer = $('#plpMahasiswaDosen');

                setSelectOptions($school, options.schools, 'Pilih Sekolah');
                setSelectOptions($teacher, options.teachers, 'Pilih Guru Pamong');
                setSelectOptions($lecturer, options.lecturers, 'Pilih DPL');

                const dropdownParent = createModalEl ? $(createModalEl) : null;
                initSelect2($school, dropdownParent);
                initSelect2($teacher, dropdownParent);
                initSelect2($lecturer, dropdownParent);
            }

            $('#btnAddPlpMahasiswa').on('click', async function () {
                if (!createModal) {
                    return;
                }
                resetMahasiswaForm();
                try {
                    const options = await loadMahasiswaOptions();
                    applyMahasiswaOptions(options);
                    createModal.show();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Tidak dapat memuat data.'
                    });
                }
            });

            $('#plpMasterDataTable').on('click', '.action-edit', async function (e) {
                e.preventDefault();
                if (!createModal) {
                    return;
                }
                const rowData = table.row($(this).closest('tr')).data();
                if (!rowData) {
                    return;
                }
                resetMahasiswaForm();
                try {
                    const options = await loadMahasiswaOptions();
                    applyMahasiswaOptions(options);
                    $('#plpMahasiswaId').val(rowData.id || '');
                    $('#plpMahasiswaNama').val(rowData.student_name || '');
                    $('#plpMahasiswaNim').val(rowData.nim || '');
                    $('#plpMahasiswaEmail').val(rowData.email || '');
                    $('#plpMahasiswaHp').val(rowData.phone || '');
                    const fakultasValue = rowData.fakultas || '';
                    const prodiValue = rowData.id_prodi || '';
                    $('#plpMahasiswaFakultas')
                        .val(fakultasValue)
                        .trigger('change', { desiredValue: prodiValue });
                    $('#plpMahasiswaSekolah').val(rowData.id_sekolah || '').trigger('change');
                    $('#plpMahasiswaGuru').val(rowData.id_guru || '').trigger('change');
                    $('#plpMahasiswaDosen').val(rowData.id_dosen || '').trigger('change');
                    $('#plpMahasiswaModalLabel').text('Edit Mahasiswa PLP I');
                    $('#plpMahasiswaSubmitBtn').text('Simpan Perubahan');
                    createModal.show();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Tidak dapat memuat data.'
                    });
                }
            });

            $('#plpMahasiswaForm').on('submit', async function (e) {
                e.preventDefault();
                if (!createModal) {
                    return;
                }

                const formData = new FormData(this);

                try {
                    const response = await fetch(storeUrl, {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();

                    if (!response.ok) {
                        const message = result?.message || 'Gagal menyimpan data mahasiswa.';
                        throw new Error(message);
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: result?.message || 'Data mahasiswa berhasil disimpan.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function () {
                        createModal.hide();
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
