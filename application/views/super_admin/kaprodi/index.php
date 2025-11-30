<div class="super-admin-program super-admin-kaprodi">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Kaprodi</h3>
            <p class="mb-0 text-muted">Kelola akun ketua program studi.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddKaprodi">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Kaprodi
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Kaprodi</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">No HP</th>
                            <th style="width: 25%;">Prodi</th>
                            <th class="text-end" style="width: 10%;">Aksi</th>
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
        const url = baseUrl + 'super-admin/datatable/kaprodi';

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
                { data: 'email', orderable: true },
                { data: 'no_hp', orderable: true },
                { data: 'nama_prodi', orderable: true },
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

        const modalEl = document.getElementById('kaprodiModal');
        const kaprodiModal = new bootstrap.Modal(modalEl);

        function resetForm() {
            $('#kaprodi_id').val('');
            $('#kaprodi_nama').val('');
            $('#kaprodi_email').val('');
            $('#kaprodi_no_hp').val('');
            $('#kaprodi_fakultas').val('').trigger('change');
            $('#kaprodi_prodi').val('').trigger('change');
        }

        function openCreateModal() {
            resetForm();
            $('#kaprodiModalTitle').text('Tambah Kaprodi');
            $('#kaprodiSubmitBtn').text('Simpan');
            kaprodiModal.show();
        }

        function openEditModal(rowData) {
            $('#kaprodi_id').val(rowData.id);
            $('#kaprodi_nama').val(rowData.nama);
            $('#kaprodi_email').val(rowData.email);
            $('#kaprodi_no_hp').val(rowData.no_hp || '');
            const fakultasValue = rowData.fakultas || '';
            const prodiValue = rowData.id_prodi || '';
            $('#kaprodi_fakultas')
                .val(fakultasValue)
                .trigger('change', { desiredValue: prodiValue });
            $('#kaprodiModalTitle').text('Edit Kaprodi');
            $('#kaprodiSubmitBtn').text('Simpan Perubahan');
            kaprodiModal.show();
        }

        $('#btnAddKaprodi').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#kaprodiForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#kaprodi_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/kaprodi/update/${id}`
                : `${baseUrl}super-admin/kaprodi/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan data kaprodi';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Data kaprodi berhasil diperbarui.' : 'Data kaprodi berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    kaprodiModal.hide();
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

        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Kaprodi?',
                text: `Data "${rowData.nama}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Data kaprodi berhasil dihapus (UI saja, endpoint belum dibuat).',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<div class="modal fade" id="kaprodiModal" tabindex="-1" aria-labelledby="kaprodiModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="kaprodiModalTitle">Tambah Kaprodi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kaprodiForm">
                <div class="modal-body">
                    <input type="hidden" id="kaprodi_id" name="id">

                    <div class="mb-3">
                        <label for="kaprodi_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="kaprodi_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="kaprodi_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="kaprodi_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="kaprodi_no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="kaprodi_no_hp" name="no_hp">
                    </div>
                    <div class="mb-3">
                        <label for="kaprodi_fakultas" class="form-label">Fakultas</label>
                        <select
                            class="form-select sa-fakultas-select"
                            id="kaprodi_fakultas"
                            name="fakultas"
                            data-placeholder="Pilih Fakultas"
                            data-prodi-target="#kaprodi_prodi">
                            <option value="">Pilih Fakultas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kaprodi_prodi" class="form-label">Program Studi</label>
                        <select
                            class="form-select sa-prodi-select"
                            id="kaprodi_prodi"
                            name="id_prodi"
                            data-placeholder="Pilih Prodi"
                            data-faculty-source="#kaprodi_fakultas"
                            required>
                            <option value="">Pilih Prodi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kaprodiSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
