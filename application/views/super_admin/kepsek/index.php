<div class="super-admin-program super-admin-kepsek">
    <div class="d-flex justify-content-between align-items-center mt-3 mb-3">
        <div>
            <h3 class="mb-1">Daftar Kepala Sekolah</h3>
            <p class="mb-0 text-muted">Kelola akun kepala sekolah beserta sekolah dan status pembayarannya.</p>
        </div>
        <button type="button" class="btn btn-primary" id="btnAddKepsek">
            <i class="bi bi-plus-lg me-1"></i>
            Tambah Kepala Sekolah
        </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Kepala Sekolah</span>
        </div>
        <div class="card-body px-3 pb-3 pt-3">
            <div class="table-responsive">
                <table id="dataTable" class="table table-sm mb-0 align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Nama</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">No HP</th>
                            <th style="width: 20%;">Sekolah</th>
                            <th style="width: 10%;">Status Bayar</th>
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
        const url = baseUrl + 'super-admin/datatable/kepsek';

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
                { data: 'nama_sekolah', orderable: true },
                { data: 'status_pembayaran', orderable: true },
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

        const modalEl = document.getElementById('kepsekModal');
        const kepsekModal = new bootstrap.Modal(modalEl);

        function openCreateModal() {
            $('#kepsek_id').val('');
            $('#kepsek_nama').val('');
            $('#kepsek_status_perkawinan').val('');
            $('#kepsek_email').val('');
            $('#kepsek_no_hp').val('');
            $('#kepsek_sekolah').val('').trigger('change');
            $('#kepsek_program').val('').trigger('change');
            $('#kepsek_nik').val('');
            $('#kepsek_no_rekening').val('');
            $('#kepsek_bank').val('');
            $('#kepsek_nama_rekening').val('');
            $('#kepsek_status_bayar').val('belum dibayar');
            $('#kepsek_foto_ktp_preview').attr('src', '').addClass('d-none');
            $('#kepsek_buku_preview').attr('src', '').addClass('d-none');
            $('#kepsekModalTitle').text('Tambah Kepala Sekolah');
            $('#kepsekSubmitBtn').text('Simpan');
            kepsekModal.show();
        }

        function openEditModal(rowData) {
            $('#kepsek_id').val(rowData.id);
            $('#kepsek_nama').val(rowData.nama);
            $('#kepsek_status_perkawinan').val(rowData.status_perkawinan || '');
            $('#kepsek_email').val(rowData.email);
            $('#kepsek_no_hp').val(rowData.no_hp);
            $('#kepsek_sekolah').val(rowData.id_sekolah || '').trigger('change');
            $('#kepsek_program').val(rowData.id_program || '').trigger('change');
            $('#kepsek_nik').val(rowData.nik || '');
            $('#kepsek_no_rekening').val(rowData.nomor_rekening || '');
            $('#kepsek_bank').val(rowData.bank || '');
            $('#kepsek_nama_rekening').val(rowData.nama_rekening || '');
            $('#kepsek_status_bayar').val(rowData.status_pembayaran || 'belum dibayar');

            if (rowData.foto_ktp) {
                $('#kepsek_foto_ktp_preview').attr('src', baseUrl + rowData.foto_ktp).removeClass('d-none');
            } else {
                $('#kepsek_foto_ktp_preview').attr('src', '').addClass('d-none');
            }

            if (rowData.buku) {
                $('#kepsek_buku_preview').attr('src', baseUrl + rowData.buku).removeClass('d-none');
            } else {
                $('#kepsek_buku_preview').attr('src', '').addClass('d-none');
            }
            $('#kepsekModalTitle').text('Edit Kepala Sekolah');
            $('#kepsekSubmitBtn').text('Simpan Perubahan');
            kepsekModal.show();
        }

        $('#btnAddKepsek').on('click', function () {
            openCreateModal();
        });

        $('#dataTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;
            openEditModal(rowData);
        });

        // preview gambar KTP
        $('#kepsek_foto_ktp').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                $('#kepsek_foto_ktp_preview').attr('src', ev.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        });

        // preview gambar buku rekening
        $('#kepsek_buku').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (ev) {
                $('#kepsek_buku_preview').attr('src', ev.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        });

        // Submit create/update kepsek (dengan upload KTP & buku)
        $('#kepsekForm').on('submit', async function (e) {
            e.preventDefault();
            const id = $('#kepsek_id').val();

            const isUpdate = !!id;
            const endpoint = isUpdate
                ? `${baseUrl}super-admin/kepsek/update/${id}`
                : `${baseUrl}super-admin/kepsek/store`;

            const formData = new FormData(this);

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (!response.ok) {
                    const message = result?.message || 'Gagal menyimpan data kepala sekolah';
                    throw new Error(message);
                }

                const title = isUpdate ? 'Diperbarui' : 'Tersimpan';
                const text = result?.message || (isUpdate ? 'Data kepala sekolah berhasil diperbarui.' : 'Data kepala sekolah berhasil disimpan.');

                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    kepsekModal.hide();
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

        // Delete hanya konfirmasi UI sementara
        $('#dataTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const rowData = table.row($(this).closest('tr')).data();
            if (!rowData) return;

            Swal.fire({
                icon: 'warning',
                title: 'Hapus Kepala Sekolah?',
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
                        text: 'Data kepala sekolah berhasil dihapus (UI saja, endpoint belum dibuat).',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>

<!-- Modal Kepala Sekolah (Tambah & Edit) -->
<div class="modal fade" id="kepsekModal" tabindex="-1" aria-labelledby="kepsekModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="kepsekModalTitle">Tambah Kepala Sekolah</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kepsekForm">
                <div class="modal-body">
                    <input type="hidden" id="kepsek_id" name="id">

                    <div class="container-fluid">
                        <!-- Kolom kiri: data pribadi & sekolah -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kepsek_nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="kepsek_nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_status_perkawinan" class="form-label">Status Pernikahan</label>
                                    <select class="form-select" id="kepsek_status_perkawinan" name="status_perkawinan">
                                        <option value="">Pilih status</option>
                                        <option value="Kawin Nol Anak">Kawin Nol Anak</option>
                                        <option value="Kawin 1 Anak">Kawin 1 Anak</option>
                                        <option value="Kawin 2 Anak">Kawin 2 Anak</option>
                                        <option value="Kawin 3 Anak">Kawin 3 Anak</option>
                                        <option value="Tidak Kawin Nol Anak">Tidak Kawin Nol Anak</option>
                                        <option value="Tidak Kawin 1 Anak">Tidak Kawin 1 Anak</option>
                                        <option value="Tidak Kawin 2 Anak">Tidak Kawin 2 Anak</option>
                                        <option value="Tidak Kawin 3 Anak">Tidak Kawin 3 Anak</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="kepsek_email" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="kepsek_no_hp" name="no_hp">
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_sekolah" class="form-label">Sekolah</label>
                                    <select class="form-select sa-school-select" id="kepsek_sekolah" name="id_sekolah" data-placeholder="Pilih Sekolah">
                                        <option value="">Pilih Sekolah</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_program" class="form-label">Program</label>
                                    <select class="form-select sa-program-select" id="kepsek_program" name="id_program" data-placeholder="Pilih Program">
                                        <option value="">Pilih Program</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="kepsek_status_bayar" class="form-label">Status Pembayaran</label>
                                    <select class="form-select" id="kepsek_status_bayar" name="status_pembayaran">
                                        <option value="belum dibayar">Belum dibayar</option>
                                        <option value="dibayar">Dibayar</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom kanan: KTP & rekening -->
                            <div class="col-md-6">
                                <div class="row text-center mb-3">
                                    <div class="col">
                                        <h5>Scan KTP</h5>
                                        <i class="bi bi-person-badge fs-1"></i>
                                        <img id="kepsek_foto_ktp_preview"
                                            class="img-thumbnail mt-2 d-none"
                                            style="max-width: 260px; object-fit: contain; background: #020617;">
                                        <input type="file" class="form-control mt-2" id="kepsek_foto_ktp" name="foto_ktp" accept="image/*">
                                    </div>
                                    <div class="col">
                                        <h5>Scan Rekening</h5>
                                        <i class="bi bi-credit-card fs-1"></i>
                                        <img id="kepsek_buku_preview"
                                            class="img-thumbnail mt-2 d-none"
                                            style="max-width: 260px; object-fit: contain; background: #020617;">
                                        <input type="file" class="form-control mt-2" id="kepsek_buku" name="buku" accept="image/*">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label for="kepsek_nik" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="kepsek_nik" name="nik">
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="kepsek_no_rekening" class="form-label">Nomor Rekening</label>
                                            <input type="text" class="form-control" id="kepsek_no_rekening" name="nomor_rekening">
                                        </div>
                                        <div class="mb-3">
                                            <label for="kepsek_bank" class="form-label">Nama Bank</label>
                                            <input type="text" class="form-control" id="kepsek_bank" name="bank">
                                        </div>
                                        <div class="mb-3">
                                            <label for="kepsek_nama_rekening" class="form-label">Nama Pemilik Rekening</label>
                                            <input type="text" class="form-control" id="kepsek_nama_rekening" name="nama_rekening">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kepsekSubmitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
