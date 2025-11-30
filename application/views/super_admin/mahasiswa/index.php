<div class="super-admin-program super-admin-mahasiswa">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Mahasiswa</h3>
            <p class="mb-0 text-muted">Kelola akun mahasiswa beserta prodi dan sekolah penempatan.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddMahasiswa">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Mahasiswa
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Mahasiswa</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 15%;">NIM</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 15%;">No HP</th>
                            <th style="width: 15%;">Prodi</th>
                            <th style="width: 10%;">Sekolah</th>
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
        const url = baseUrl + 'super-admin/datatable/mahasiswa';

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
                { data: 'nim', orderable: true },
                { data: 'email', orderable: true },
                { data: 'no_hp', orderable: true },
                { data: 'nama_prodi', orderable: true },
                { data: 'nama_sekolah', orderable: true },
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

        const modalEl = document.getElementById('mahasiswaModal');
        const mahasiswaModal = new bootstrap.Modal(modalEl);

        function resetForm() {
            $('#mahasiswa_id').val('');
            $('#mahasiswa_nama').val('');
            $('#mahasiswa_nim').val('');
            $('#mahasiswa_email').val('');
            $('#mahasiswa_no_hp').val('');
            $('#mahasiswa_fakultas').val('').trigger('change');
            $('#mahasiswa_prodi').val('').trigger('change');
            $('#mahasiswa_sekolah').val('').trigger('change');
        }

        function openCreateModal() {
            resetForm();
            $('#mahasiswaModalTitle').text('Tambah Mahasiswa');
            $('#mahasiswaSubmitBtn').text('Simpan');
            mahasiswaModal.show();
        }

        function openEditModal(rowData) {
            $('#mahasiswa_id').val(rowData.id);
            $('#mahasiswa_nama').val(rowData.nama);
            $('#mahasiswa_nim').val(rowData.nim);
            $('#mahasiswa_email').val(rowData.email);
            $('#mahasiswa_no_hp').val(rowData.no_hp || '');
            const fakultasValue = rowData.fakultas || '';
            const prodiValue = rowData.id_prodi || '';
            $('#mahasiswa_fakultas')
                .val(fakultasValue)
                .trigger('change', { desiredValue: prodiValue });
            $('#mahasiswa_sekolah').val(rowData.id_sekolah || '').trigger('change');
            $('#mahasiswaModalTitle').text('Edit Mahasiswa');
            $('#mahasiswaSubmitBtn').text('Simpan Perubahan');
            mahasiswaModal.show();
        }

        $('#btnAddMahasiswa').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        $('#mahasiswaForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#mahasiswa_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/mahasiswa/update/${id}`
                : `${baseUrl}super-admin/mahasiswa/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan data mahasiswa';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Data mahasiswa berhasil diperbarui.' : 'Data mahasiswa berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    mahasiswaModal.hide();
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
                title: 'Hapus Mahasiswa?',
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
                        text: 'Data mahasiswa berhasil dihapus (UI saja, endpoint belum dibuat).',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-labelledby="mahasiswaModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="mahasiswaModalTitle">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="mahasiswaForm">
                <div class="modal-body">
                    <input type="hidden" id="mahasiswa_id" name="id">

                    <div class="mb-3">
                        <label for="mahasiswa_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="mahasiswa_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="mahasiswa_nim" name="nim" required>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="mahasiswa_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="mahasiswa_no_hp" name="no_hp">
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_fakultas" class="form-label">Fakultas</label>
                        <select
                            class="form-select sa-fakultas-select"
                            id="mahasiswa_fakultas"
                            name="fakultas"
                            data-placeholder="Pilih Fakultas"
                            data-prodi-target="#mahasiswa_prodi">
                            <option value="">Pilih Fakultas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_prodi" class="form-label">Program Studi</label>
                        <select
                            class="form-select sa-prodi-select"
                            id="mahasiswa_prodi"
                            name="id_prodi"
                            data-placeholder="Pilih Prodi"
                            data-faculty-source="#mahasiswa_fakultas"
                            required>
                            <option value="">Pilih Prodi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mahasiswa_sekolah" class="form-label">Sekolah</label>
                        <select
                            class="form-select sa-school-select"
                            id="mahasiswa_sekolah"
                            name="id_sekolah"
                            data-placeholder="Pilih Sekolah"
                            required>
                            <option value="">Pilih Sekolah</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="mahasiswaSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

