<div class="super-admin-program">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Program</h3>
            <p class="mb-0 text-muted">Kelola program PLP / KKN beserta status aktifnya.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddProgram">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Program
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Program</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Kode</th>
                            <th style="width: 30%;">Nama Program</th>
                            <th style="width: 20%;">Tahun Ajaran</th>
                            <th style="width: 15%;">Status</th>
                            <th class="text-end" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const url = baseUrl + 'super-admin/datatable/program';

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
                {
                    data: null,
                    orderable: true,
                    render: function (data, type, row) {
                        return row?.kode || '-';
                    }
                },
                { data: 'nama', orderable: true },
                { data: 'tahun_ajaran', orderable: true },
                { data: 'status', orderable: true },
                {
                    data: 'id',
                    orderable: false,
                    className: 'text-end',
                    render: function (id, type, row) {
                        return `
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    ...
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item dropdown-item-edit action-edit" href="#" data-id="${id}">Edit</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-toggle action-toggle" href="#" data-id="${id}">Active / Nonactive</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-delete action-delete" href="#" data-id="${id}">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        const modalEl = document.getElementById('programModal');
        const programModal = new bootstrap.Modal(modalEl);

        function openCreateModal() {
            $('#program_id').val('');
            $('#program_kode').val('');
            $('#program_nama').val('');
            $('#program_tahun').val('');
            $('#program_status').val('0');
            $('#programModalTitle').text('Tambah Program');
            $('#programSubmitBtn').text('Simpan');
            programModal.show();
        }

        function openEditModal(rowData) {
            $('#program_id').val(rowData.id);
            $('#program_kode').val(rowData.kode || '');
            $('#program_nama').val(rowData.nama);
            $('#program_tahun').val(rowData.tahun_ajaran);
            $('#program_status').val(rowData.status === 'Aktif' ? '1' : '0');
            $('#programModalTitle').text('Edit Program');
            $('#programSubmitBtn').text('Simpan Perubahan');
            programModal.show();
        }

        $('#btnAddProgram').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#programForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#program_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/program/update/${id}`
                : `${baseUrl}super-admin/program/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan program';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Program berhasil diperbarui.' : 'Program berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    programModal.hide();
                    table.ajax.reload(null, false);
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.',
                });
            }
        });

        // Toggle active / nonactive
        $('#dataTable').on('click', '.action-toggle', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'question',
                title: 'Ubah status program?',
                text: `Ubah status "${rowData.nama}"?`,
                showCancelButton: true,
                confirmButtonText: 'Ya, ubah',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`${baseUrl}super-admin/program/toggle/${rowData.id}`, {
                        method: 'POST'
                    });
                    const resJson = await response.json();

                    if (!response.ok) {
                        const message = resJson?.message || 'Gagal mengubah status program';
                        throw new Error(message);
                    }

                    const newStatus = resJson?.data?.status || 'berubah';
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: `Status program diubah menjadi ${newStatus}.`,
                        timer: 1500,
                        showConfirmButton: false
                    });
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

        // Delete
        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus program?',
                text: `Program "${rowData.nama}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Program berhasil dihapus.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<!-- Modal Program (Tambah & Edit) -->
<div class="modal fade" id="programModal" tabindex="-1" aria-labelledby="programModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="programModalTitle">Tambah Program</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="programForm">
                <div class="modal-body">
                    <input type="hidden" id="program_id" name="id">
                    <div class="mb-3">
                        <label for="program_kode" class="form-label">Kode</label>
                        <select class="form-select" id="program_kode" name="kode" required>
                            <option value="" selected>Pilih Kode</option>
                            <option value="plp1">plp1</option>
                            <option value="kkn">kkn</option>
                            <option value="plp2">plp2</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="program_nama" class="form-label">Nama Program</label>
                        <input type="text" class="form-control" id="program_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="program_tahun" class="form-label">Tahun Ajaran</label>
                        <input type="text" class="form-control" id="program_tahun" name="tahun_ajaran" placeholder="2026" required>
                    </div>
                    <div class="mb-3">
                        <label for="program_status" class="form-label">Status</label>
                        <select class="form-select" id="program_status" name="status">
                            <option value="0" selected>Tidak Aktif</option>
                            <option value="1">Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="programSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
