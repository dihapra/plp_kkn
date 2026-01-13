<?php
$programOptions   = $programOptions ?? [];
$defaultProgramId = $defaultProgramId ?? ($programOptions[0]['id'] ?? null);
?>

<div class="super-admin-program super-admin-mahasiswa-true">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Data Mahasiswa Admin</h3>
            <p class="mb-0 text-muted">
                Daftar acuan resmi mahasiswa yang dipakai saat proses verifikasi di modul PLP/KKN.
            </p>
        </div>
    </div>

    <div class="alert alert-info mb-3">
        <div class="d-flex gap-2 align-items-start">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <span>
                Pastikan NIM dan prodi sesuai data akademik terbaru. Data di halaman ini akan dibandingkan dengan
                pendaftaran mahasiswa sebelum disetujui oleh admin.
            </span>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary" id="btnAddReferenceStudent">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Data
        </button>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="filterProgram" class="form-label fw-semibold">Filter Program</label>
                    <select class="form-select" id="filterProgram" <?= empty($programOptions) ? 'disabled' : '' ?>>
                        <option value=""><?= empty($programOptions) ? 'Tidak ada program aktif' : 'Semua Program Aktif' ?></option>
                        <?php foreach ($programOptions as $program): ?>
                            <?php
                            $labelParts = [];
                            if (!empty($program['kode'])) {
                                $labelParts[] = strtoupper($program['kode']);
                            } elseif (!empty($program['nama'])) {
                                $labelParts[] = $program['nama'];
                            }
                            if (!empty($program['tahun_ajaran'])) {
                                $labelParts[] = '(' . $program['tahun_ajaran'] . ')';
                            }
                            $label = trim(implode(' ', $labelParts));
                            $id = (int) $program['id'];
                            ?>
                            <option value="<?= $id ?>" <?= $defaultProgramId === $id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label ?: 'Program') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($programOptions)): ?>
                        <small class="text-warning">Aktifkan minimal satu program untuk menampilkan data.</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Referensi Mahasiswa</span>
            <small class="text-muted">Gunakan tombol di atas untuk menambah atau perbarui data.</small>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="verifiedStudentTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 24%;">Nama</th>
                            <th style="width: 15%;">NIM</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 13%;">No HP</th>
                            <th style="width: 18%;">Prodi</th>
                            <th style="width: 18%;">Program</th>
                            <th style="width: 10%;">Diperbarui</th>
                            <th class="text-end" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mahasiswaTrueModal" tabindex="-1" aria-labelledby="mahasiswaTrueModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="mahasiswaTrueModalTitle">Tambah Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0">
                <ul class="nav nav-tabs" id="mahasiswaTrueModalTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mahasiswaTrueTabInsert" data-bs-toggle="tab" data-bs-target="#mahasiswaTrueTabPaneInsert" type="button" role="tab" aria-controls="mahasiswaTrueTabPaneInsert" aria-selected="true">Insert</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mahasiswaTrueTabImport" data-bs-toggle="tab" data-bs-target="#mahasiswaTrueTabPaneImport" type="button" role="tab" aria-controls="mahasiswaTrueTabPaneImport" aria-selected="false">Import</button>
                    </li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="mahasiswaTrueTabPaneInsert" role="tabpanel" aria-labelledby="mahasiswaTrueTabInsert">
                        <form id="mahasiswaTrueForm">
                            <input type="hidden" id="mahasiswaTrue_id" name="id">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="mahasiswaTrue_nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="mahasiswaTrue_nama" name="nama" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="mahasiswaTrue_nim" class="form-label">NIM</label>
                                    <input type="text" class="form-control" id="mahasiswaTrue_nim" name="nim" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="mahasiswaTrue_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="mahasiswaTrue_email" name="email" placeholder="opsional">
                                </div>
                                <div class="col-md-6">
                                    <label for="mahasiswaTrue_no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="mahasiswaTrue_no_hp" name="no_hp" placeholder="opsional">
                                </div>
                                <div class="col-md-4">
                                    <label for="mahasiswaTrue_fakultas" class="form-label">Fakultas</label>
                                    <select
                                        class="form-select sa-fakultas-select"
                                        id="mahasiswaTrue_fakultas"
                                        name="fakultas"
                                        data-placeholder="Pilih Fakultas"
                                        data-prodi-target="#mahasiswaTrue_prodi">
                                        <option value="">Pilih Fakultas</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="mahasiswaTrue_prodi" class="form-label">Program Studi</label>
                                    <select
                                        class="form-select sa-prodi-select"
                                        id="mahasiswaTrue_prodi"
                                        name="id_prodi"
                                        data-placeholder="Pilih Prodi"
                                        data-faculty-source="#mahasiswaTrue_fakultas">
                                        <option value="">Pilih Prodi</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="mahasiswaTrue_program" class="form-label">Program</label>
                                    <select class="form-select" id="mahasiswaTrue_program" name="id_program" required <?= empty($programOptions) ? 'disabled' : '' ?>>
                                        <option value=""><?= empty($programOptions) ? 'Tidak ada program aktif' : 'Pilih Program Aktif' ?></option>
                                        <?php foreach ($programOptions as $program): ?>
                                            <?php
                                            $labelParts = [];
                                            if (!empty($program['kode'])) {
                                                $labelParts[] = strtoupper($program['kode']);
                                            } elseif (!empty($program['nama'])) {
                                                $labelParts[] = $program['nama'];
                                            }
                                            if (!empty($program['tahun_ajaran'])) {
                                                $labelParts[] = '(' . $program['tahun_ajaran'] . ')';
                                            }
                                            $label = trim(implode(' ', $labelParts));
                                            ?>
                                            <option value="<?= (int) $program['id'] ?>"><?= htmlspecialchars($label ?: 'Program') ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (empty($programOptions)): ?>
                                        <small class="text-warning">Belum ada program aktif. Aktifkan program terlebih dahulu.</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer border-0 px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="mahasiswaTrueSubmitBtn">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="mahasiswaTrueTabPaneImport" role="tabpanel" aria-labelledby="mahasiswaTrueTabImport">
                        <form id="mahasiswaTrueImportForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="mahasiswaTrue_import_file" class="form-label">File XLSX</label>
                                <input type="file" class="form-control" id="mahasiswaTrue_import_file" name="importFile" accept=".xlsx" required>
                                <small class="text-muted d-block mt-2">Format: A = nama, B = nim, C = email, D = no hp, E = prodi, F = fakultas.</small>
                                <small class="text-muted d-block mt-1">Program otomatis memakai kode PLP I (plp1).</small>
                            </div>
                            <input type="hidden" name="program_code" value="plp1">
                            <div class="mb-3">
                                <a class="btn btn-outline-secondary btn-sm" href="<?= base_url('storage/templates/import_mahasiswa_true_template.xlsx') ?>" download>
                                    <i class="bi bi-download me-1"></i> Contoh template mahasiswa
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

<script>
    $(function () {
        const programOptions = <?= json_encode($programOptions, JSON_UNESCAPED_UNICODE) ?>;
        const defaultProgramId = <?= json_encode($defaultProgramId) ?>;
        const $programFilter = $('#filterProgram');

        const table = $('#verifiedStudentTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}admin/plp1/master-data/mahasiswa-true/datatable`,
                type: 'POST',
                data: function (d) {
                    d.program_id = $programFilter.val();
                }
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
            columns: [
                { data: 'nama', orderable: true },
                { data: 'nim', orderable: true },
                {
                    data: 'email',
                    orderable: true,
                    render: function (value) {
                        return value || '-';
                    }
                },
                {
                    data: 'no_hp',
                    orderable: true,
                    render: function (value) {
                        return value || '-';
                    }
                },
                {
                    data: 'nama_prodi',
                    orderable: true,
                    render: function (value) {
                        return value || '-';
                    }
                },
                {
                    data: 'nama_program',
                    orderable: true,
                    render: function (_, __, row) {
                        return formatProgram(row);
                    }
                },
                {
                    data: 'updated_at',
                    orderable: true,
                    render: function (value, _, row) {
                        const fallback = row.created_at || '';
                        return formatDate(value || fallback);
                    }
                },
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
                                    <li><a class="dropdown-item action-edit" href="#" data-id="${id}">Edit</a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        const modalEl = document.getElementById('mahasiswaTrueModal');
        const mahasiswaTrueModal = new bootstrap.Modal(modalEl);
        const insertTabEl = document.getElementById('mahasiswaTrueTabInsert');

        function showInsertTab() {
            if (!insertTabEl) {
                return;
            }
            const tab = new bootstrap.Tab(insertTabEl);
            tab.show();
        }

        function formatDate(value) {
            if (!value) {
                return '-';
            }
            const date = new Date(value);
            if (isNaN(date.getTime())) {
                return value;
            }
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function resetForm() {
            $('#mahasiswaTrue_id').val('');
            $('#mahasiswaTrue_nama').val('');
            $('#mahasiswaTrue_nim').val('');
            $('#mahasiswaTrue_email').val('');
            $('#mahasiswaTrue_no_hp').val('');
            $('#mahasiswaTrue_fakultas').val('').trigger('change');
            $('#mahasiswaTrue_prodi').val('').trigger('change');
            if (programOptions.length > 0) {
                const fallbackProgramId = defaultProgramId || programOptions[0].id;
                $('#mahasiswaTrue_program').val(fallbackProgramId).trigger('change');
            } else {
                $('#mahasiswaTrue_program').val('');
            }
        }

        function openCreateModal() {
            resetForm();
            $('#mahasiswaTrueImportForm')[0]?.reset();
            $('#mahasiswaTrueModalTitle').text('Tambah Data Mahasiswa');
            $('#mahasiswaTrueSubmitBtn').text('Simpan');
            showInsertTab();
            mahasiswaTrueModal.show();
        }

        function openEditModal(rowData) {
            $('#mahasiswaTrue_id').val(rowData.id);
            $('#mahasiswaTrue_nama').val(rowData.nama);
            $('#mahasiswaTrue_nim').val(rowData.nim);
            $('#mahasiswaTrue_email').val(rowData.email || '');
            $('#mahasiswaTrue_no_hp').val(rowData.no_hp || '');
            const fakultasValue = rowData.fakultas || '';
            const prodiValue = rowData.id_prodi || '';
            $('#mahasiswaTrue_fakultas')
                .val(fakultasValue)
                .trigger('change', { desiredValue: prodiValue });
            $('#mahasiswaTrue_program').val(rowData.id_program || '').trigger('change');

            $('#mahasiswaTrueModalTitle').text('Edit Data Mahasiswa');
            $('#mahasiswaTrueSubmitBtn').text('Simpan Perubahan');
            $('#mahasiswaTrueImportForm')[0]?.reset();
            showInsertTab();
            mahasiswaTrueModal.show();
        }

        $('#btnAddReferenceStudent').on('click', function () {
            openCreateModal();
        });

        $programFilter.on('change', function () {
            table.ajax.reload();
        });

        $('#verifiedStudentTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) {
                return;
            }
            openEditModal(rowData);
        });

        function formatProgram(row) {
            if (!row) {
                return '-';
            }
            const code = row.kode_program ? row.kode_program.toUpperCase() : '';
            const name = row.nama_program || '';
            const year = row.tahun_ajaran_program ? ` (${row.tahun_ajaran_program})` : '';
            if (code) {
                return `${code}${year}`;
            }
            if (name) {
                return `${name}${year}`;
            }
            return '-';
        }

        $('#mahasiswaTrueForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#mahasiswaTrue_id').val();
            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}admin/plp1/master-data/mahasiswa-true/update/${id}`
                : `${baseUrl}admin/plp1/master-data/mahasiswa-true/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan data.';
                    throw new Error(message);
                }

                Swal.fire({
                    icon: 'success',
                    title: isUpdate ? 'Diperbarui' : 'Tersimpan',
                    text: result?.message || 'Data berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    mahasiswaTrueModal.hide();
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

        $('#mahasiswaTrueImportForm').on('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch(`${baseUrl}admin/plp1/master-data/mahasiswa-true/import`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal mengimpor data.';
                    throw new Error(message);
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result?.message || 'Data berhasil diimpor.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    mahasiswaTrueModal.hide();
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
