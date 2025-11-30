<?php
$prodiOptions = $prodiOptions ?? [];
$activeProdi  = $prodiOptions[0] ?? null;
$activeProdiName = $activeProdi
    ? htmlspecialchars($activeProdi->nama . (!empty($activeProdi->fakultas) ? " ({$activeProdi->fakultas})" : ''), ENT_QUOTES, 'UTF-8')
    : 'Belum ditentukan';
$activeProdiId = $activeProdi->id ?? 0;
?>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="mb-0">Dosen Pembimbing</h5>
        <small class="text-muted">Pantau kuota bimbingan, distribusi mahasiswa, dan kelola data dosen.</small>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                <label class="text-muted small mb-0">Prodi</label>
                <input type="text" class="form-control form-control-sm" value="<?= $activeProdiName ?>" readonly>
                <input type="hidden" id="dosenProdiFilter" value="<?= (int) $activeProdiId ?>">
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-outline-primary btn-sm" id="btnExportDosen">
                    <i class="bi bi-download me-1"></i>
                    Ekspor
                </button>
                <button class="btn btn-primary btn-sm" id="btnAddDosen">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Dosen
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle w-100" id="kaprodiDosenTable">
                <thead>
                    <tr>
                        <th>Nama Dosen</th>
                        <th>Prodi</th>
                        <th>Total Mahasiswa</th>
                        <th>Mahasiswa Aktif</th>
                        <th>Sekolah Binaan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <small class="text-muted d-block mt-3">
            Angka pada tabel ini berasal dari mahasiswa yang sudah ditempatkan ke sekolah binaan.
        </small>
    </div>
</div>

<div class="modal fade" id="kaprodiDosenModal" tabindex="-1" aria-labelledby="kaprodiDosenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kaprodiDosenModalLabel">Tambah Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kaprodiDosenForm">
                <div class="modal-body">
                    <input type="hidden" id="kaprodiDosenId" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kaprodiDosenNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="kaprodiDosenNama" name="nama" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kaprodiDosenNidn" class="form-label">NIP / NIDN</label>
                            <input type="text" class="form-control" id="kaprodiDosenNidn" name="nidn" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kaprodiDosenEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="kaprodiDosenEmail" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="kaprodiDosenHp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="kaprodiDosenHp" name="no_hp">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Program Studi</label>
                            <input type="text" class="form-control" value="<?= $activeProdiName ?>" readonly>
                            <input type="hidden" id="kaprodiDosenProdi" name="id_prodi" value="<?= (int) $activeProdiId ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kaprodiDosenSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        const table = $('#kaprodiDosenTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}kaprodi/datatable/dosen`,
                type: 'POST',
                data: function (d) {
                    d.filter_prodi = $('#dosenProdiFilter').val();
                }
            },
            order: [[0, 'asc']],
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
                {
                    data: 'nama',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            return `
                                <div class="fw-semibold">${data || '-'}</div>
                                <div class="text-muted small">${row.email || '-'}</div>
                                <div class="text-muted small">NIDN: ${row.nidn || '-'}</div>
                            `;
                        }
                        return data;
                    }
                },
                { data: 'nama_prodi', defaultContent: '-' },
                {
                    data: 'total_mahasiswa',
                    className: 'text-center'
                },
                {
                    data: 'mahasiswa_aktif',
                    className: 'text-center'
                },
                {
                    data: 'sekolah_binaan',
                    render: function (data) {
                        return data || '-';
                    }
                },
                {
                    data: 'id',
                    orderable: false,
                    className: 'text-end',
                    render: function (id) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    ...
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item action-edit" href="#" data-id="${id}">Edit</a></li>
                                    <li><a class="dropdown-item action-delete text-danger" href="#" data-id="${id}">Hapus</a></li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        const modalEl = document.getElementById('kaprodiDosenModal');
        const dosenModal = new bootstrap.Modal(modalEl);

        function resetForm() {
            $('#kaprodiDosenForm')[0].reset();
            $('#kaprodiDosenId').val('');
        }

        function openCreateModal() {
            resetForm();
            $('#kaprodiDosenModalLabel').text('Tambah Dosen');
            $('#kaprodiDosenSubmit').text('Simpan');
            dosenModal.show();
        }

        function openEditModal(rowData) {
            if (!rowData) return;
            $('#kaprodiDosenId').val(rowData.id);
            $('#kaprodiDosenNama').val(rowData.nama);
            $('#kaprodiDosenNidn').val(rowData.nidn);
            $('#kaprodiDosenEmail').val(rowData.email);
            $('#kaprodiDosenHp').val(rowData.no_hp || '');
            $('#kaprodiDosenProdi').val(rowData.id_prodi || '');
            $('#kaprodiDosenModalLabel').text('Edit Dosen');
            $('#kaprodiDosenSubmit').text('Simpan Perubahan');
            dosenModal.show();
        }

        $('#btnAddDosen').on('click', function () {
            openCreateModal();
        });

        $('#btnExportDosen').on('click', function () {
            const prodi = $('#dosenProdiFilter').val();
            const query = prodi ? `?filter_prodi=${prodi}` : '';
            window.location.href = `${baseUrl}kaprodi/dosen/export${query}`;
        });

        function getRowData(element) {
            const $row = $(element).closest('tr');
            let data = table.row($row).data();
            if (!data) {
                data = table.row($(element).closest('li')).data();
            }
            return data;
        }

        $('#kaprodiDosenTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = getRowData(this);
            openEditModal(rowData);
        });

        $('#kaprodiDosenTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = getRowData(this);
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Dosen?',
                text: `Data "${rowData.nama}" akan dihapus permanen.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (!result.isConfirmed) return;
                try {
                    const response = await fetch(`${baseUrl}kaprodi/dosen/delete/${rowData.id}`, {
                        method: 'POST'
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        throw new Error(payload?.message || 'Gagal menghapus dosen.');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: payload?.message || 'Data dosen berhasil dihapus.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message || 'Terjadi kesalahan saat menghapus.'
                    });
                }
            });
        });

        $('#kaprodiDosenForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#kaprodiDosenId').val();
            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}kaprodi/dosen/update/${id}`
                : `${baseUrl}kaprodi/dosen/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal menyimpan data dosen.');
                }
                Swal.fire({
                    icon: 'success',
                    title: isUpdate ? 'Diperbarui' : 'Tersimpan',
                    text: result?.message || (isUpdate ? 'Data dosen berhasil diperbarui.' : 'Data dosen berhasil disimpan.'),
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
    });
</script>
