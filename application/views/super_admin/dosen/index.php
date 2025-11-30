<div class="super-admin-program super-admin-dosen">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Dosen</h3>
            <p class="mb-0 text-muted">Kelola akun dosen pembimbing beserta prodi dan datanya.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddDosen">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Dosen
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Dosen</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">No HP</th>
                            <th style="width: 15%;">NIP/NIDN</th>
                            <th style="width: 15%;">Prodi</th>
                            <th class="text-end" style="width: 5%;">Aksi</th>
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
        const url = baseUrl + 'super-admin/datatable/dosen';

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
                { data: 'nidn', orderable: true },
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

        const modalEl = document.getElementById('dosenModal');
        const dosenModal = new bootstrap.Modal(modalEl);

        function resetForm() {
            $('#dosen_id').val('');
            $('#dosen_nama').val('');
            $('#dosen_email').val('');
            $('#dosen_no_hp').val('');
            $('#dosen_nidn').val('');
            $('#dosen_prodi').val('').trigger('change');
            $('#dosen_fakultas').val('').trigger('change');
        }

        function openCreateModal() {
            resetForm();
            $('#dosenModalTitle').text('Tambah Dosen');
            $('#dosenSubmitBtn').text('Simpan');
            dosenModal.show();
        }

        function openEditModal(rowData) {
            $('#dosen_id').val(rowData.id);
            $('#dosen_nama').val(rowData.nama);
            $('#dosen_email').val(rowData.email);
            $('#dosen_no_hp').val(rowData.no_hp || '');
            $('#dosen_nidn').val(rowData.nidn || '');
            const fakultasValue = rowData.fakultas || '';
            const prodiValue = rowData.id_prodi || '';
            $('#dosen_fakultas')
                .val(fakultasValue)
                .trigger('change', { desiredValue: prodiValue });
            $('#dosenModalTitle').text('Edit Dosen');
            $('#dosenSubmitBtn').text('Simpan Perubahan');
            dosenModal.show();
        }

        $('#btnAddDosen').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#dosenForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#dosen_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/dosen/update/${id}`
                : `${baseUrl}super-admin/dosen/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan data dosen';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Data dosen berhasil diperbarui.' : 'Data dosen berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
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

        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Dosen?',
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
                        text: 'Data dosen berhasil dihapus (UI saja, endpoint belum dibuat).',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<div class="modal fade" id="dosenModal" tabindex="-1" aria-labelledby="dosenModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="dosenModalTitle">Tambah Dosen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dosenForm">
                <div class="modal-body">
                    <input type="hidden" id="dosen_id" name="id">

                    <div class="mb-3">
                        <label for="dosen_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="dosen_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_nidn" class="form-label">NIP / NIDN</label>
                        <input type="text" class="form-control" id="dosen_nidn" name="nidn" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="dosen_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="dosen_no_hp" name="no_hp">
                    </div>
                    <div class="mb-3">
                        <label for="dosen_fakultas" class="form-label">Fakultas</label>
                        <select
                            class="form-select sa-fakultas-select"
                            id="dosen_fakultas"
                            name="fakultas"
                            data-placeholder="Pilih Fakultas"
                            data-prodi-target="#dosen_prodi">
                            <option value="">Pilih Fakultas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_prodi" class="form-label">Program Studi</label>
                        <select
                            class="form-select sa-prodi-select"
                            id="dosen_prodi"
                            name="id_prodi"
                            data-placeholder="Pilih Prodi"
                            data-faculty-source="#dosen_fakultas"
                            required>
                            <option value="">Pilih Prodi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="dosenSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
