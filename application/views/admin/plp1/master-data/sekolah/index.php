<?php
$activeProgram = $activeProgram ?? null;
$hasActiveProgram = !empty($activeProgram);
$activeLabel = $hasActiveProgram
    ? sprintf(
        '%s (%s)',
        strtoupper($activeProgram['kode'] ?? 'PLP I'),
        $activeProgram['tahun_ajaran'] ?? 'T/A'
    )
    : null;
?>

<div class="super-admin-program super-admin-sekolah">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Master Data Sekolah - PLP I</h3>
            <p class="mb-0 text-muted">Kelola daftar sekolah untuk program PLP I aktif.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge <?= $hasActiveProgram ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' ?> px-3 py-2">
                <?php if ($hasActiveProgram): ?>
                    <i class="bi bi-lightning-charge me-1"></i> Program Aktif: <?= htmlspecialchars($activeLabel) ?>
                <?php else: ?>
                    <i class="bi bi-exclamation-triangle me-1"></i> Belum ada program aktif
                <?php endif; ?>
            </span>
        </div>
    </div>

    <?php if (!$hasActiveProgram): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-info-circle"></i>
            <div>
                Aktifkan salah satu program PLP I terlebih dahulu di menu <strong>Master Data &gt; Program</strong> untuk menambah sekolah.
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" id="btnAddSchool" <?= $hasActiveProgram ? '' : 'disabled' ?>>
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Sekolah
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Sekolah</span>
            <small class="text-muted">Data otomatis terikat pada program PLP I aktif.</small>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Nama Sekolah</th>
                            <th style="width: 40%;">Alamat</th>
                            <th class="text-end" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$hasActiveProgram): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada program PLP I aktif.</td>
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

        const url = baseUrl + 'admin/plp1/master-data/sekolah/datatable';

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                type: 'POST'
            },
            language: {
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ data",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
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
            columns: [
                { data: 'nama', orderable: true },
                { data: 'alamat', orderable: true },
                {
                    data: 'id',
                    orderable: false,
                    className: 'text-end',
                    render: function (id) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    ...
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item dropdown-item-edit action-edit" href="#" data-id="${id}">Edit</a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        const modalEl = document.getElementById('schoolModal');
        const schoolModal = new bootstrap.Modal(modalEl);
        const activeProgramId = <?= json_encode($activeProgram['id'] ?? null) ?>;
        const insertTabEl = document.getElementById('schoolTabInsert');

        function showInsertTab() {
            if (!insertTabEl) {
                return;
            }
            const tab = new bootstrap.Tab(insertTabEl);
            tab.show();
        }

        function openCreateModal() {
            $('#school_id').val('');
            $('#school_nama').val('');
            $('#school_alamat').val('');
            $('#school_program_id').val(activeProgramId || '');
            $('#schoolImportForm')[0]?.reset();
            $('#schoolModalTitle').text('Tambah Sekolah');
            $('#schoolSubmitBtn').text('Simpan');
            showInsertTab();
            schoolModal.show();
        }

        function openEditModal(rowData) {
            $('#school_id').val(rowData.id);
            $('#school_nama').val(rowData.nama);
            $('#school_alamat').val(rowData.alamat);
            $('#school_program_id').val(activeProgramId || rowData.id_program || '');
            $('#schoolImportForm')[0]?.reset();
            $('#schoolModalTitle').text('Edit Sekolah');
            $('#schoolSubmitBtn').text('Simpan Perubahan');
            showInsertTab();
            schoolModal.show();
        }

        $('#btnAddSchool').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#schoolForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#school_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}admin/plp1/master-data/sekolah/update/${id}`
                : `${baseUrl}admin/plp1/master-data/sekolah/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan sekolah';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Sekolah berhasil diperbarui.' : 'Sekolah berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    schoolModal.hide();
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

        $('#schoolImportForm').on('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch(`${baseUrl}admin/plp1/master-data/sekolah/import`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal mengimpor sekolah';
                    throw new Error(message);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result?.message || 'Data sekolah berhasil diimpor.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    schoolModal.hide();
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

    });
</script>

<div class="modal fade" id="schoolModal" tabindex="-1" aria-labelledby="schoolModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="schoolModalTitle">Tambah Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <ul class="nav nav-tabs" id="schoolModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="schoolTabInsert" data-bs-toggle="tab" data-bs-target="#schoolTabPaneInsert" type="button" role="tab" aria-controls="schoolTabPaneInsert" aria-selected="true">Insert</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="schoolTabImport" data-bs-toggle="tab" data-bs-target="#schoolTabPaneImport" type="button" role="tab" aria-controls="schoolTabPaneImport" aria-selected="false">Import</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="schoolTabPaneInsert" role="tabpanel" aria-labelledby="schoolTabInsert">
                        <form id="schoolForm">
                            <input type="hidden" id="school_id" name="id">
                            <input type="hidden" id="school_program_id" name="id_program" value="<?= htmlspecialchars((string) ($activeProgram['id'] ?? '')) ?>">
                            <div class="mb-3">
                                <label for="school_nama" class="form-label">Nama Sekolah</label>
                                <input type="text" class="form-control" id="school_nama" name="nama" required>
                            </div>
                            <div class="mb-3">
                                <label for="school_alamat" class="form-label">Alamat (Opsional)</label>
                                <textarea class="form-control" id="school_alamat" name="alamat" rows="3"></textarea>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Program</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($activeLabel ?? 'Program belum aktif') ?>" readonly>
                            </div>
                            <div class="modal-footer border-0 px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="schoolSubmitBtn">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="schoolTabPaneImport" role="tabpanel" aria-labelledby="schoolTabImport">
                        <form id="schoolImportForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="school_import_file" class="form-label">File XLSX</label>
                                <input type="file" class="form-control" id="school_import_file" name="importFile" accept=".xlsx" required>
                                <small class="text-muted d-block mt-2">Format: kolom A = nama, kolom B = alamat (opsional).</small>
                            </div>
                            <div class="mb-3">
                                <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('storage/templates/import_sekolah_template.xlsx') ?>" download>
                                    <i class="bi bi-download me-1"></i> Contoh template sekolah
                                </a>
                            </div>
                            <div class="modal-footer border-0 px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-upload me-1"></i> Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
