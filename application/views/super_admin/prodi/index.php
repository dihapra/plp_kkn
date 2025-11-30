<div class="super-admin-program super-admin-prodi">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Prodi</h3>
            <p class="mb-0 text-muted">Kelola program studi beserta fakultasnya.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddProdi">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Prodi
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Prodi</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Nama Prodi</th>
                            <th style="width: 40%;">Fakultas</th>
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
        const url = baseUrl + 'super-admin/datatable/prodi';

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
                { data: 'fakultas', orderable: true },
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
                                        <a class="dropdown-item dropdown-item-delete action-delete" href="#" data-id="${id}">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ]
        });

        const modalEl = document.getElementById('prodiModal');
        const prodiModal = new bootstrap.Modal(modalEl);

        function openCreateModal() {
            $('#prodi_id').val('');
            $('#prodi_nama').val('');
            $('#prodi_fakultas').val('');
            $('#prodiModalTitle').text('Tambah Prodi');
            $('#prodiSubmitBtn').text('Simpan');
            prodiModal.show();
        }

        function openEditModal(rowData) {
            $('#prodi_id').val(rowData.id);
            $('#prodi_nama').val(rowData.nama);
            $('#prodi_fakultas').val(rowData.fakultas);
            $('#prodiModalTitle').text('Edit Prodi');
            $('#prodiSubmitBtn').text('Simpan Perubahan');
            prodiModal.show();
        }

        $('#btnAddProdi').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        // create / update prodi
        $('#prodiForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#prodi_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/prodi/update/${id}`
                : `${baseUrl}super-admin/prodi/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan prodi';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Prodi berhasil diperbarui.' : 'Prodi berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    prodiModal.hide();
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

        // Delete (belum terhubung ke endpoint, hanya konfirmasi UI)
        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Prodi?',
                text: `Prodi "${rowData.nama}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Prodi berhasil dihapus (UI saja, endpoint belum dibuat).',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<!-- Modal Prodi (Tambah & Edit) -->
<div class="modal fade" id="prodiModal" tabindex="-1" aria-labelledby="prodiModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="prodiModalTitle">Tambah Prodi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="prodiForm">
                <div class="modal-body">
                    <input type="hidden" id="prodi_id" name="id">
                    <div class="mb-3">
                        <label for="prodi_nama" class="form-label">Nama Prodi</label>
                        <input type="text" class="form-control" id="prodi_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="prodi_fakultas" class="form-label">Fakultas</label>
                        <input type="text" class="form-control" id="prodi_fakultas" name="fakultas" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="prodiSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

