<?php
$programOptions = $programOptions ?? [];
$defaultStatus = $defaultStatus ?? 'unverified';
$defaultProgramId = $programOptions[0]['id'] ?? '';
?>

<style>
    .verification-modal .modal-dialog {
        max-width: min(960px, 95vw);
    }

    .verification-modal .modal-content {
        border-radius: 1.25rem;
        background: #ffffff;
        color: #0f172a;
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
    }

    .verification-modal .modal-header,
    .verification-modal .modal-body,
    .verification-modal .modal-footer {
        background: transparent;
    }

    .verification-detail-card {
        border-radius: 1rem;
        border: 1px solid rgba(148, 163, 184, 0.35);
        background: #f8fafc;
        padding: 1.25rem;
        color: #0f172a;
    }

    .verification-detail-list .detail-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.4rem 0;
        border-bottom: 1px dashed rgba(148, 163, 184, 0.25);
        font-size: 0.94rem;
    }

    .verification-detail-list .detail-row:last-child {
        border-bottom: 0;
    }

    .verification-detail-list .detail-label {
        color: #64748b;
        flex: 0 0 46%;
        font-weight: 500;
    }

    .verification-detail-list .detail-value {
        flex: 0 0 54%;
        text-align: right;
        font-weight: 600;
        color: #0f172a;
    }

    .verification-modal small {
        color: #64748b !important;
    }
</style>

<div class="super-admin-program super-admin-verification">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Verifikasi MOU Sekolah PLP I</h3>
            <p class="mb-0 text-muted">Tinjau surat kerja sama yang diunggah kaprodi sebelum disetujui.</p>
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
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Daftar Pengajuan MOU</span>
            <small class="text-muted">Menampilkan data berdasarkan filter di atas.</small>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 22%;">Sekolah</th>
                            <th style="width: 20%;">Prodi</th>
                            <th style="width: 18%;">Program</th>
                            <th style="width: 15%;">Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="verificationModalTitle">Verifikasi MOU</h5>
                    <small class="text-muted" id="verificationModalSubtitle">Periksa detail kerja sama sekolah.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="verification-detail-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1">Detail MOU</h6>
                            <small class="text-muted">Dokumen diunggah oleh kaprodi.</small>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary" id="registrationStatusBadge">-</span>
                    </div>
                    <div id="registrationDetails" class="verification-detail-list"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 flex-wrap gap-3 justify-content-between">
                <div class="text-muted small">Pastikan dokumen sesuai sebelum memutuskan.</div>
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

<script>
    $(function () {
        const $program = $('#filterProgram');
        const $status = $('#filterStatus');
        const $btnApprove = $('#btnApproveVerification');
        const $btnReject = $('#btnRejectVerification');
        let activeMou = {
            id: null,
            name: ''
        };

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}admin/plp1/verifikasi/sekolah/datatable`,
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
                { data: 'nama_sekolah', orderable: true },
                {
                    data: 'nama_prodi',
                    orderable: true,
                    render: function (text, _, row) {
                        if (!text) return '-';
                        return row.fakultas ? `${text}<br><span class="text-muted small">${row.fakultas}</span>` : text;
                    }
                },
                {
                    data: 'program',
                    orderable: true,
                    render: function (_, __, row) {
                        const code = row.kode_program ? row.kode_program.toUpperCase() : '';
                        const name = row.nama_program || '';
                        const year = row.tahun_ajaran ? ` (${row.tahun_ajaran})` : '';
                        if (code) return `${code}${year}`;
                        if (name) return `${name}${year}`;
                        return '-';
                    }
                },
                {
                    data: 'surat_mou',
                    orderable: false,
                    render: function (text) {
                        if (!text) {
                            return '<span class="text-muted">-</span>';
                        }
                        return `<a href="${baseUrl}${text}" target="_blank" rel="noopener">Lihat Surat</a>`;
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
                    render: function (id) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item action-verify" href="#" data-id="${id}">
                                            <i class="bi bi-shield-check me-2 text-success"></i>Detail & Verifikasi
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

        const verificationModalEl = document.getElementById('verificationModal');
        const verificationModal = verificationModalEl ? new bootstrap.Modal(verificationModalEl) : null;
        const $verificationModalTitle = $('#verificationModalTitle');
        const $verificationModalSubtitle = $('#verificationModalSubtitle');
        const $registrationDetails = $('#registrationDetails');
        const $registrationStatusBadge = $('#registrationStatusBadge');

        function setActionButtonsDisabled(state) {
            $btnApprove.prop('disabled', state);
            $btnReject.prop('disabled', state);
        }

        const registrationFields = [
            { label: 'Sekolah', key: 'nama_sekolah' },
            { label: 'Program Studi', key: 'nama_prodi' },
            { label: 'Fakultas', key: 'fakultas' },
            {
                label: 'Program',
                key: 'kode_program',
                formatter: function (_, record) {
                    return formatProgramLabel(record, 'tahun_ajaran');
                }
            },
            {
                label: 'Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP',
                key: 'surat_mou',
                allowHtml: true,
                formatter: function (value) {
                    if (!value) {
                        return '-';
                    }
                    return `<a href="${baseUrl}${value}" target="_blank" rel="noopener">Lihat Surat</a>`;
                }
            },
            { label: 'Dibuat', key: 'created_at', formatter: formatDateTime },
            { label: 'Diperbarui', key: 'updated_at', formatter: formatDateTime }
        ];

        $('#dataTable').on('click', '.action-verify', function (e) {
            e.preventDefault();
            if (!verificationModal) {
                return;
            }
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) {
                return;
            }
            activeMou = {
                id: rowData.id,
                name: rowData.nama_sekolah || 'Sekolah'
            };
            if (verificationModalEl) {
                verificationModalEl.dataset.mouId = rowData.id ? String(rowData.id) : '';
            }
            setVerificationModalLoading(activeMou.name);
            verificationModal.show();
            fetchVerificationDetail(rowData.id);
        });

        $btnApprove.on('click', function () {
            requestStatusChange('verified');
        });

        $btnReject.on('click', function () {
            requestStatusChange('rejected');
        });

        async function fetchVerificationDetail(id) {
            try {
                const response = await fetch(`${baseUrl}admin/plp1/verifikasi/sekolah/detail/${id}`);
                const payload = await response.json();
                const payloadMessage = payload && payload.message ? payload.message : null;
                const payloadData = payload && payload.data ? payload.data : {};
                if (!response.ok) {
                    throw new Error(payloadMessage || 'Gagal memuat detail MOU.');
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
            const schoolName = payload && payload.nama_sekolah ? payload.nama_sekolah : null;
            const mouId = payload && payload.id ? payload.id : null;

            $verificationModalTitle.text(schoolName ? `Verifikasi MOU ${schoolName}` : 'Verifikasi MOU');
            $verificationModalSubtitle.text('Periksa detail kerja sama sebelum disetujui.');

            if (schoolName) {
                activeMou.name = schoolName;
            }
            if (mouId) {
                activeMou.id = mouId;
            }
            if (verificationModalEl && activeMou.id) {
                verificationModalEl.dataset.mouId = String(activeMou.id);
            }

            setStatusBadge(payload ? payload.status : null);
            renderDetailList($registrationDetails, registrationFields, payload, 'Data MOU tidak tersedia.');
            setActionButtonsDisabled(false);
        }

        function setVerificationModalLoading(name) {
            $verificationModalTitle.text(`Verifikasi MOU ${name}`);
            $verificationModalSubtitle.text('Sedang memuat detail...');
            $registrationStatusBadge.attr('class', 'badge bg-secondary-subtle text-secondary').text('Memuat');
            $registrationDetails.html(createPlaceholderBlock());
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
            if (!activeMou.id && verificationModalEl && verificationModalEl.dataset.mouId) {
                activeMou.id = parseInt(verificationModalEl.dataset.mouId, 10);
            }
            if (!activeMou.id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum ada data',
                    text: 'Pilih data MOU terlebih dahulu.'
                });
                return;
            }

            const name = activeMou.name || 'sekolah';
            const promptText = status === 'verified'
                ? `Pastikan dokumen MOU untuk ${name} sudah sesuai sebelum disetujui.`
                : `Pastikan ${name} sudah diberi catatan terkait penolakan.`;

            Swal.fire({
                icon: 'question',
                title: status === 'verified' ? 'Verifikasi MOU?' : 'Tolak MOU?',
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
                const response = await fetch(`${baseUrl}admin/plp1/verifikasi/sekolah/status/${activeMou.id}`, {
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
