<?php
$programOptions = $programOptions ?? [];
$defaultStatus = $defaultStatus ?? 'unverified';
$defaultProgramId = $programOptions[0]['id'] ?? '';
?>

<style>
    .verification-modal .modal-dialog {
        max-width: min(1200px, 95vw);
    }

    .verification-modal .modal-content {
        border-radius: 1.25rem;
        background: #0f172a;
        color: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.2);
        box-shadow: 0 30px 60px rgba(15, 23, 42, 0.65);
    }

    .verification-modal .modal-header,
    .verification-modal .modal-body,
    .verification-modal .modal-footer {
        background: transparent;
    }

    .verification-detail-card {
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: rgba(15, 23, 42, 0.65);
        padding: 1.25rem;
        min-height: 360px;
        color: #e2e8f0;
    }

    .verification-detail-list .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.35rem 0;
        border-bottom: 1px dashed rgba(148, 163, 184, 0.25);
        font-size: 0.94rem;
    }

    .verification-detail-list .detail-row:last-child {
        border-bottom: 0;
    }

    .verification-detail-list .detail-label {
        color: #cbd5f5;
        flex: 0 0 48%;
        font-weight: 500;
    }

    .verification-detail-list .detail-value {
        flex: 0 0 52%;
        text-align: right;
        font-weight: 600;
        color: #f8fafc;
    }

    .verification-requirement {
        background: rgba(15, 23, 42, 0.85);
        border: 1px dashed rgba(148, 163, 184, 0.35);
        border-radius: 0.85rem;
        color: #e2e8f0;
    }

    .verification-requirement .badge {
        font-size: 0.85rem;
    }

    .verification-placeholder .placeholder {
        min-height: 16px;
    }

    .verification-modal small,
    #referenceEmptyState,
    #syaratEmptyState {
        color: rgba(226, 232, 240, 0.75) !important;
    }

    .edit-student-modal .modal-content {
        background: #0f172a;
        color: #f8fafc;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .edit-student-modal .modal-header,
    .edit-student-modal .modal-body,
    .edit-student-modal .modal-footer {
        background: transparent;
    }
</style>

<div class="super-admin-program super-admin-verification">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Verifikasi Mahasiswa PLP I</h3>
            <p class="mb-0 text-muted">
                Tinjau pendaftaran mahasiswa PLP I sebelum memberikan akses penuh ke modul lapangan.
            </p>
        </div>
        <span class="badge bg-primary-subtle text-primary px-3 py-2">
            <i class="bi bi-shield-check me-1"></i> Panel verifikasi
        </span>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="filterProgram" class="form-label fw-semibold mb-1">Program PLP I</label>
                    <select class="form-select" id="filterProgram">
                        <option value="">Semua Program PLP I</option>
                        <?php foreach ($programOptions as $option): ?>
                            <option value="<?= $option['id'] ?>" <?= $option['id'] === $defaultProgramId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($option['label']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($programOptions)): ?>
                        <small class="text-muted">Belum ada program PLP I terdaftar. Tambahkan lewat menu Program.</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label fw-semibold mb-1">Status Verifikasi</label>
                    <select class="form-select" id="filterStatus">
                        <option value="unverified" <?= $defaultStatus === 'unverified' ? 'selected' : '' ?>>Menunggu (Unverified)</option>
                        <option value="verified" <?= $defaultStatus === 'verified' ? 'selected' : '' ?>>Terverifikasi</option>
                        <option value="rejected" <?= $defaultStatus === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="all" <?= $defaultStatus === 'all' ? 'selected' : '' ?>>Semua Status</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end justify-content-md-end">
                    <button type="button" class="btn btn-success btn-sm" id="btnExport">
                        <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Daftar Pengajuan Mahasiswa</span>
            <small class="text-muted">Menampilkan data berdasarkan filter di atas.</small>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 12%;">NIM</th>
                            <th style="width: 18%;">Program</th>
                            <th style="width: 20%;">Prodi</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 10%;">Status</th>
                            <th class="text-end" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade verification-modal" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="verificationModalTitle">Verifikasi Mahasiswa</h5>
                    <small class="text-muted" id="verificationModalSubtitle">Periksa detail pendaftaran sebelum disetujui.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="row g-4 flex-lg-nowrap">
                    <div class="col-lg-6">
                        <div class="verification-detail-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">Data Pendaftaran</h6>
                                    <small class="text-muted">Data dikirim langsung oleh mahasiswa.</small>
                                </div>
                                <span class="badge bg-secondary-subtle text-secondary" id="registrationStatusBadge">-</span>
                            </div>
                            <div id="registrationDetails" class="verification-detail-list"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="verification-detail-card shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">Data Mahasiswa Referensi</h6>
                                    <small class="text-muted">Sumber: Data Mahasiswa Admin</small>
                                </div>
                                <span class="badge bg-primary-subtle text-primary d-none" id="referenceProgramBadge"></span>
                            </div>
                            <div id="referenceDetails" class="verification-detail-list"></div>
                            <div id="referenceEmptyState" class="text-muted small fst-italic">Data referensi tidak ditemukan berdasarkan NIM ini.</div>
                        </div>
                    </div>
                </div>
                <div class="mt-4" id="syaratSection">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <h6 class="mb-0">Syarat Perkuliahan</h6>
                        <small class="text-muted" id="syaratUpdatedAt"></small>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 g-3" id="syaratRequirementList"></div>
                    <div id="syaratEmptyState" class="text-muted small fst-italic">Belum ada data syarat perkuliahan.</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 flex-wrap gap-3 justify-content-between">
                <div class="text-muted small">Pastikan seluruh data sesuai sebelum memutuskan.</div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-outline-danger" id="btnRejectVerification">
                        <i class="bi bi-x-circle me-1"></i>Tolak
                    </button>
                    <button type="button" class="btn btn-success" id="btnApproveVerification">
                        <i class="bi bi-check2-circle me-1"></i>Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade edit-student-modal" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStudentForm">
                <div class="modal-body">
                    <input type="hidden" id="editStudentId" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editStudentName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM</label>
                            <input type="text" class="form-control" id="editStudentNim" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editStudentEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" id="editStudentPhone">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Program Studi</label>
                            <select class="form-select" id="editStudentProdi" required>
                                <option value="">Pilih prodi...</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        const $program = $('#filterProgram');
        const $status = $('#filterStatus');
        const $btnExport = $('#btnExport');
        const $btnApprove = $('#btnApproveVerification');
        const $btnReject = $('#btnRejectVerification');
        let activeStudent = {
            id: null,
            name: '',
            nim: ''
        };

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}super-admin/plp/verifikasi/mahasiswa/datatable`,
                type: 'POST',
                data: function (d) {
                    d.program_id = $program.val();
                    d.verification_status = $status.val();
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
            order: [[0, 'asc']],
            columns: [
                { data: 'nama', orderable: true },
                { data: 'nim', orderable: true },
                {
                    data: null,
                    orderable: true,
                    render: function (_, __, row) {
                        const code = row.kode_program ? row.kode_program.toUpperCase() : '';
                        const name = row.nama_program || '';
                        const year = row.tahun_ajaran ? ` (${row.tahun_ajaran})` : '';
                        if (code && year) return `${code}${year}`;
                        if (code && !year) return code;
                        if (name) return `${name}${year}`;
                        return '-';
                    }
                },
                {
                    data: 'nama_prodi',
                    orderable: true,
                    render: function (text) {
                        return text || '-';
                    }
                },
                {
                    data: 'email',
                    orderable: true,
                    render: function (text) {
                        return text || '-';
                    }
                },
                {
                    data: 'status',
                    orderable: true,
                    render: function (status) {
                        const map = {
                            verified: { label: 'Verified', class: 'success' },
                            unverified: { label: 'Unverified', class: 'warning' },
                            rejected: { label: 'Rejected', class: 'danger' },
                        };
                        const meta = map[status] || { label: status || '-', class: 'secondary' };
                        return `<span class="badge bg-${meta.class}">${meta.label}</span>`;
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    className: 'text-end',
                    render: function (id, __, row) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item action-edit" href="#" data-id="${id}">
                                            <i class="bi bi-pencil-square me-2 text-primary"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item action-verify" href="#" data-id="${id}">
                                            <i class="bi bi-shield-check me-2 text-success"></i>Verifikasi
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger action-delete" href="#" data-id="${id}">
                                            <i class="bi bi-trash3 me-2"></i>Hapus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        function reloadTable() {
            table.ajax.reload();
        }

        $program.on('change', reloadTable);
        $status.on('change', reloadTable);
        $btnExport.on('click', function () {
            const params = new URLSearchParams();
            if ($program.val()) {
                params.append('program_id', $program.val());
            }
            if ($status.val()) {
                params.append('verification_status', $status.val());
            }
            const searchValue = table.search();
            if (searchValue) {
                params.append('search', searchValue);
            }
            const query = params.toString();
            const url = `${baseUrl}super-admin/plp/verifikasi/mahasiswa/export${query ? '?' + query : ''}`;
            window.location.href = url;
        });

        const verificationModalEl = document.getElementById('verificationModal');
        const verificationModal = verificationModalEl ? new bootstrap.Modal(verificationModalEl) : null;
        const $verificationModalTitle = $('#verificationModalTitle');
        const $verificationModalSubtitle = $('#verificationModalSubtitle');
        const $registrationDetails = $('#registrationDetails');
        const $referenceDetails = $('#referenceDetails');
        const $referenceEmptyState = $('#referenceEmptyState');
        const $registrationStatusBadge = $('#registrationStatusBadge');
        const $referenceProgramBadge = $('#referenceProgramBadge');
        const $syaratList = $('#syaratRequirementList');
        const $syaratEmptyState = $('#syaratEmptyState');
        const $syaratUpdatedAt = $('#syaratUpdatedAt');
        const editModalEl = document.getElementById('editStudentModal');
        const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
        const $editStudentForm = $('#editStudentForm');
        const $editStudentId = $('#editStudentId');
        const $editStudentName = $('#editStudentName');
        const $editStudentNim = $('#editStudentNim');
        const $editStudentEmail = $('#editStudentEmail');
        const $editStudentPhone = $('#editStudentPhone');
        const $editStudentProdi = $('#editStudentProdi');
        let prodiOptionsLoaded = false;

        function setActionButtonsDisabled(state) {
            $btnApprove.prop('disabled', state);
            $btnReject.prop('disabled', state);
        }

        const registrationFields = [
            { label: 'Nama', key: 'nama' },
            { label: 'NIM', key: 'nim' },
            { label: 'Email', key: 'email' },
            { label: 'No HP', key: 'no_hp' },
            { label: 'Program Studi', key: 'nama_prodi' },
            { label: 'Fakultas', key: 'fakultas' },
            {
                label: 'Program',
                key: 'kode_program',
                formatter: function (_, record) {
                    return formatProgramLabel(record, 'tahun_ajaran');
                }
            },
            { label: 'Agama', key: 'agama' },
            { label: 'Dibuat', key: 'created_at', formatter: formatDateTime },
            { label: 'Diperbarui', key: 'updated_at', formatter: formatDateTime }
        ];

        const referenceFields = [
            { label: 'Nama', key: 'nama' },
            { label: 'NIM', key: 'nim' },
            { label: 'Email', key: 'email' },
            { label: 'No HP', key: 'no_hp' },
            { label: 'Program Studi', key: 'nama_prodi' },
            { label: 'Fakultas', key: 'fakultas' },
            {
                label: 'Program',
                key: 'kode_program',
                formatter: function (_, record) {
                    return formatProgramLabel(record, 'tahun_ajaran_program');
                }
            },
            { label: 'Dibuat', key: 'created_at', formatter: formatDateTime },
            { label: 'Diperbarui', key: 'updated_at', formatter: formatDateTime }
        ];

        const requirementStatusMap = {
            lulus: { label: 'Lulus', class: 'bg-success-subtle text-success' },
            proses: { label: 'Proses', class: 'bg-warning-subtle text-warning' },
            'belum lulus': { label: 'Belum Lulus', class: 'bg-danger-subtle text-danger' },
            default: { label: 'Tidak Ada', class: 'bg-secondary-subtle text-secondary' }
        };

        $('#dataTable').on('click', '.action-verify', function (e) {
            e.preventDefault();
            if (!verificationModal) {
                return;
            }
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) {
                return;
            }
            activeStudent = {
                id: rowData.id,
                name: rowData.nama || 'Mahasiswa',
                nim: rowData.nim || '-'
            };
            setVerificationModalLoading(activeStudent.name);
            verificationModal.show();
            fetchVerificationDetail(rowData.id);
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            if (!editModal) {
                return;
            }
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) {
                return;
            }
            openEditModal(rowData.id);
        });

        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) {
                return;
            }

            const name = rowData.nama || 'mahasiswa';
            const nimLabel = rowData.nim ? ` (NIM ${rowData.nim})` : '';

            Swal.fire({
                icon: 'warning',
                title: 'Hapus pendaftaran?',
                text: `Data ${name}${nimLabel} akan dihapus permanen.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (!result.isConfirmed) {
                    return;
                }
                try {
                    const response = await fetch(`${baseUrl}super-admin/plp/verifikasi/mahasiswa/delete/${rowData.id}`, {
                        method: 'POST'
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        throw new Error(payload?.message || 'Gagal menghapus data mahasiswa.');
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: payload?.message || 'Data mahasiswa berhasil dihapus.',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    if (verificationModal && activeStudent.id === rowData.id) {
                        verificationModal.hide();
                        activeStudent = { id: null, name: '', nim: '' };
                    }

                    table.ajax.reload(null, false);
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Terjadi kesalahan.'
                    });
                }
            });
        });

        $btnApprove.on('click', function () {
            requestStatusChange('verified');
        });

        $btnReject.on('click', function () {
            requestStatusChange('rejected');
        });

        $editStudentForm.on('submit', function (e) {
            e.preventDefault();
            submitStudentEdit();
        });

        async function loadProdiOptions(selectedId) {
            if (prodiOptionsLoaded) {
                if (selectedId) {
                    $editStudentProdi.val(String(selectedId));
                }
                return;
            }
            try {
                const response = await fetch(`${baseUrl}super-admin/filter/prodi`);
                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload?.message || 'Gagal memuat daftar prodi.');
                }
                const options = payload?.data || [];
                $editStudentProdi.empty();
                $editStudentProdi.append(new Option('Pilih prodi...', ''));
                options.forEach(function (item) {
                    const label = item.fakultas ? `${item.nama} (${item.fakultas})` : item.nama;
                    $editStudentProdi.append(new Option(label, item.id));
                });
                prodiOptionsLoaded = true;
                if (selectedId) {
                    $editStudentProdi.val(String(selectedId));
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memuat prodi',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        async function openEditModal(id) {
            try {
                const response = await fetch(`${baseUrl}super-admin/plp/verifikasi/mahasiswa/detail/${id}`);
                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload?.message || 'Gagal memuat data mahasiswa.');
                }
                const registration = payload?.data?.pendaftaran;
                if (!registration) {
                    throw new Error('Data pendaftaran tidak ditemukan.');
                }
                $editStudentId.val(registration.id || '');
                $editStudentName.val(registration.nama || '');
                $editStudentNim.val(registration.nim || '');
                $editStudentEmail.val(registration.email || '');
                $editStudentPhone.val(registration.no_hp || '');
                await loadProdiOptions(registration.id_prodi || '');
                if (editModal) {
                    editModal.show();
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        async function submitStudentEdit() {
            const id = Number($editStudentId.val());
            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data tidak lengkap',
                    text: 'ID mahasiswa tidak ditemukan.'
                });
                return;
            }
            try {
                const payload = new URLSearchParams({
                    nama: $editStudentName.val().trim(),
                    nim: $editStudentNim.val().trim(),
                    email: $editStudentEmail.val().trim(),
                    no_hp: $editStudentPhone.val().trim(),
                    id_prodi: $editStudentProdi.val()
                });
                const response = await fetch(`${baseUrl}super-admin/plp/verifikasi/mahasiswa/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: payload
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal memperbarui data mahasiswa.');
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: result?.message || 'Data mahasiswa berhasil diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                });
                if (editModal) {
                    editModal.hide();
                }
                table.ajax.reload(null, false);
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        async function fetchVerificationDetail(id) {
            try {
                const response = await fetch(`${baseUrl}super-admin/plp/verifikasi/mahasiswa/detail/${id}`);
                const payload = await response.json();
                const payloadMessage = payload && payload.message ? payload.message : null;
                const payloadData = payload && payload.data ? payload.data : {};
                if (!response.ok) {
                    throw new Error(payloadMessage || 'Gagal memuat detail mahasiswa.');
                }
                renderVerificationDetail(payloadData);
            } catch (error) {
                verificationModal.hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memuat detail',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        function renderVerificationDetail(payload) {
            const registration = payload && payload.pendaftaran ? payload.pendaftaran : null;
            const reference = payload && payload.referensi ? payload.referensi : null;
            const syarat = payload && payload.syarat ? payload.syarat : null;

            const registrationName = registration && registration.nama ? registration.nama : null;
            const registrationNim = registration && registration.nim ? registration.nim : null;

            $verificationModalTitle.text(registrationName ? `Verifikasi ${registrationName}` : 'Verifikasi Mahasiswa');
            $verificationModalSubtitle.text(registrationNim ? `NIM ${registrationNim}` : 'Periksa detail pendaftaran sebelum disetujui.');

            if (registrationName) {
                activeStudent.name = registrationName;
            }
            if (registrationNim) {
                activeStudent.nim = registrationNim;
            }

            setStatusBadge(registration ? registration.status : null);

            renderDetailList($registrationDetails, registrationFields, registration, 'Data pendaftaran tidak tersedia.');

            if (reference) {
                renderDetailList($referenceDetails, referenceFields, reference, '');
                $referenceEmptyState.addClass('d-none');
            } else {
                $referenceDetails.html('<p class="text-muted small mb-0">Tidak ada data pembanding.</p>');
                $referenceEmptyState.removeClass('d-none');
            }
            setReferenceBadge(reference);
            renderSyaratList(syarat);
            setActionButtonsDisabled(false);
        }

        function setVerificationModalLoading(name) {
            $verificationModalTitle.text(`Verifikasi ${name}`);
            $verificationModalSubtitle.text('Sedang memuat detail mahasiswa...');
            $registrationStatusBadge.attr('class', 'badge bg-secondary-subtle text-secondary').text('Memuat');
            $referenceProgramBadge.addClass('d-none').text('');
            const skeleton = createPlaceholderBlock();
            $registrationDetails.html(skeleton);
            $referenceDetails.html(skeleton);
            $referenceEmptyState.addClass('d-none');
            $syaratList.empty();
            $syaratEmptyState.addClass('d-none');
            $syaratUpdatedAt.text('');
            setActionButtonsDisabled(true);
        }

        function renderDetailList($container, fields, data, emptyText) {
            if (!data) {
                $container.html(`<p class="text-muted small mb-0">${emptyText}</p>`);
                return;
            }

            const rows = fields.map(function (field) {
                const rawValue = field.key ? data[field.key] : null;
                const computed = field.formatter ? field.formatter(rawValue, data) : rawValue;
                const value = computed === undefined || computed === null || computed === '' ? '-' : computed;
                const label = escapeHtml(field.label);
                const renderedValue = field.allowHtml ? value : escapeHtml(value);
                return `
                    <div class="detail-row">
                        <span class="detail-label">${label}</span>
                        <span class="detail-value">${renderedValue}</span>
                    </div>
                `;
            }).join('');

            $container.html(rows);
        }

        function renderSyaratList(data) {
            if (!data) {
                $syaratList.empty();
                $syaratEmptyState.removeClass('d-none');
                $syaratUpdatedAt.text('');
                return;
            }

            const totalSksValue = data.total_sks !== undefined && data.total_sks !== null && data.total_sks !== '' ? data.total_sks : '-';
            const cards = [
                renderRequirementCard('Total SKS', totalSksValue, false),
                renderRequirementCard('Filsafat Pendidikan', data.filsafat_pendidikan),
                renderRequirementCard('Profesi Kependidikan', data.profesi_kependidikan),
                renderRequirementCard('Perkembangan Peserta Didik', data.perkembangan_peserta_didik),
                renderRequirementCard('Psikologi Pendidikan', data.psikologi_pendidikan)
            ];

            $syaratList.html(cards.join(''));
            $syaratEmptyState.addClass('d-none');
            $syaratUpdatedAt.text(data.updated_at ? `Diperbarui ${formatDateTime(data.updated_at)}` : '');
        }

        function renderRequirementCard(label, rawValue, treatAsStatus = true) {
            let content = '-';
            if (treatAsStatus) {
                const key = typeof rawValue === 'string' ? rawValue.toLowerCase() : '';
                const meta = requirementStatusMap[key] || requirementStatusMap.default;
                content = `<span class="badge ${meta.class}">${meta.label}</span>`;
            } else {
                content = rawValue !== null && rawValue !== undefined && rawValue !== '' ? escapeHtml(rawValue) : '-';
            }

            return `
                <div class="col">
                    <div class="verification-requirement h-100 p-3">
                        <span class="text-muted small">${escapeHtml(label)}</span>
                        <div class="mt-2 fs-5 fw-semibold">${content}</div>
                    </div>
                </div>
            `;
        }

        function setStatusBadge(status) {
            const statusMap = {
                verified: { label: 'Verified', class: 'bg-success' },
                unverified: { label: 'Unverified', class: 'bg-warning text-dark' },
                rejected: { label: 'Rejected', class: 'bg-danger' }
            };
            const meta = statusMap[(status || '').toLowerCase()] || { label: status || '-', class: 'bg-secondary' };
            $registrationStatusBadge
                .attr('class', `badge ${meta.class}`)
                .text(meta.label);
        }

        function setReferenceBadge(reference) {
            if (!reference) {
                $referenceProgramBadge.addClass('d-none').text('');
                return;
            }
            $referenceProgramBadge
                .removeClass('d-none')
                .text(formatProgramLabel(reference, 'tahun_ajaran_program'));
        }

        function formatProgramLabel(record, yearKey) {
            if (!record) {
                return '-';
            }
            const code = record.kode_program ? record.kode_program.toUpperCase() : '';
            const name = record.nama_program || '';
            const yearValue = yearKey && record[yearKey] ? ` (${record[yearKey]})` : '';
            if (code) {
                return `${code}${yearValue}`;
            }
            if (name) {
                return `${name}${yearValue}`;
            }
            return '-';
        }

        function formatDateTime(value) {
            if (!value) {
                return '-';
            }
            const date = new Date(value);
            if (Number.isNaN(date.getTime())) {
                return value;
            }
            return date.toLocaleString('id-ID', {
                dateStyle: 'medium',
                timeStyle: 'short'
            });
        }

        function createPlaceholderBlock() {
            const placeholder = `
                <div class="verification-placeholder w-100">
                    <div class="placeholder-glow mb-2">
                        <span class="placeholder col-12"></span>
                        <span class="placeholder col-10"></span>
                        <span class="placeholder col-8"></span>
                        <span class="placeholder col-6"></span>
                    </div>
                </div>
            `;
            return placeholder.repeat(2);
        }

        function escapeHtml(value) {
            if (value === undefined || value === null) {
                return '';
            }
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function requestStatusChange(status) {
            if (!activeStudent.id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum ada data',
                    text: 'Pilih mahasiswa terlebih dahulu.'
                });
                return;
            }

            const name = activeStudent.name || 'mahasiswa';
            const promptText = status === 'verified'
                ? `Pastikan ${name} telah memenuhi persyaratan sebelum disetujui.`
                : `Pastikan ${name} sudah diberi catatan terkait penolakan.`;

            Swal.fire({
                icon: 'question',
                title: status === 'verified' ? 'Verifikasi mahasiswa?' : 'Tolak pendaftaran?',
                text: promptText,
                showCancelButton: true,
                confirmButtonText: status === 'verified' ? 'Ya, verifikasi' : 'Ya, tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                submitStatusChange(status);
            });
        }

        async function submitStatusChange(status) {
            setActionButtonsDisabled(true);
            try {
                const response = await fetch(`${baseUrl}super-admin/plp/verifikasi/mahasiswa/status/${activeStudent.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: new URLSearchParams({ status })
                });
                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload && payload.message ? payload.message : 'Gagal memperbarui status.');
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: payload && payload.message ? payload.message : 'Status berhasil diperbarui.'
                }).then(() => {
                    verificationModal.hide();
                    table.ajax.reload(null, false);
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            } finally {
                setActionButtonsDisabled(false);
            }
        }
    });
</script>
