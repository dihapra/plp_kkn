<div class="super-admin-program super-admin-admin-pic">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3 flex-wrap gap-3">
        <div>
            <h3 class="mb-1">Admin PIC</h3>
            <p class="mb-0 text-muted">Kelola akun admin PIC (role disimpan sebagai admin).</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddAdminPic">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Admin PIC
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Daftar Admin PIC</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="adminPicTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Nama</th>
                            <th style="width: 30%;">Email</th>
                            <th style="width: 20%;">Fakultas</th>
                            <th style="width: 15%;">Dibuat</th>
                            <th class="text-end" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adminPicModal" tabindex="-1" aria-labelledby="adminPicModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="adminPicModalTitle">Tambah Admin PIC</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adminPicForm">
                <div class="modal-body">
                    <input type="hidden" id="adminPic_id" name="id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="adminPic_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="adminPic_nama" name="nama" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adminPic_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="adminPic_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="adminPic_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="adminPic_password" name="password" autocomplete="new-password">
                            <small class="text-muted">Kosongkan saat edit jika tidak ingin mengganti password.</small>
                        </div>
                        <div class="col-md-6">
                            <label for="adminPic_fakultas" class="form-label">Fakultas</label>
                            <input type="text" class="form-control" id="adminPic_fakultas" name="fakultas" placeholder="Opsional">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="adminPicSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        const url = baseUrl + 'super-admin/datatable/admin-pic';

        const table = $('#adminPicTable').DataTable({
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
                {
                    data: 'fakultas',
                    orderable: true,
                    render: function (value) {
                        return value || '-';
                    }
                },
                {
                    data: 'created_at',
                    orderable: true,
                    render: function (value) {
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
                                    <li>
                                        <a class="dropdown-item action-edit" href="#" data-id="${id}">Edit</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger action-delete" href="#" data-id="${id}">Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'asc']]
        });

        const modalEl = document.getElementById('adminPicModal');
        const adminPicModal = new bootstrap.Modal(modalEl);

        function resetForm() {
            $('#adminPicForm')[0].reset();
            $('#adminPic_id').val('');
        }

        function openCreateModal() {
            resetForm();
            $('#adminPic_password').prop('required', true);
            $('#adminPicModalTitle').text('Tambah Admin PIC');
            $('#adminPicSubmitBtn').text('Simpan');
            adminPicModal.show();
        }

        function openEditModal(rowData) {
            $('#adminPic_id').val(rowData.id);
            $('#adminPic_nama').val(rowData.nama);
            $('#adminPic_email').val(rowData.email);
            $('#adminPic_fakultas').val(rowData.fakultas || '');
            $('#adminPic_password').val('');
            $('#adminPic_password').prop('required', false);
            $('#adminPicModalTitle').text('Edit Admin PIC');
            $('#adminPicSubmitBtn').text('Simpan Perubahan');
            adminPicModal.show();
        }

        $('#btnAddAdminPic').on('click', function () {
            openCreateModal();
        });

        $('#adminPicTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#adminPicTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Admin PIC?',
                text: `Akun "${rowData.nama}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (!result.isConfirmed) return;
                try {
                    const response = await fetch(`${baseUrl}super-admin/admin-pic/delete/${rowData.id}`, {
                        method: 'POST'
                    });
                    const payload = await response.json();
                    if (!response.ok) {
                        throw new Error(payload?.message || 'Gagal menghapus admin PIC.');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: payload?.message || 'Admin PIC berhasil dihapus.',
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

        $('#adminPicForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#adminPic_id').val();
            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/admin-pic/update/${id}`
                : `${baseUrl}super-admin/admin-pic/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal menyimpan admin PIC.');
                }

                Swal.fire({
                    icon: 'success',
                    title: isUpdate ? 'Diperbarui' : 'Tersimpan',
                    text: result?.message || 'Admin PIC berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    adminPicModal.hide();
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
