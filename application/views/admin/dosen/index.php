<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Dosen</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/dosen/insert') ?>" class="btn btn-primary btn-sm mr-2"><i class="fas fa-plus"></i> Tambah Dosen</a>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#importModal"><i class="fas fa-file-excel"></i> Import Dosen</button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="lecturerTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Prodi</th>
                                <th>Fakultas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileImportDosen">Pilih File Excel (.xlsx, .xls, .csv)</label>
                        <input type="file" class="form-control-file" id="fileImportDosen" name="fileImportDosen" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#lecturerTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/datatable/dosen') ?>",
                "type": "POST"
            },
            "columns": [{
                    "data": null,
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nip"
                },
                {
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "phone"
                },
                {
                    "data": "prodi"
                },
                {
                    "data": "fakultas"
                },
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        let buttons = '<a href="<?= base_url('admin/dosen/edit_page/') ?>' + data + '" class="btn btn-info btn-sm mr-1"><i class="fas fa-edit"></i> Edit</a>';
                        // Only show delete button for super_admin
                        <?php if ($this->session->userdata('role') === 'super_admin') : ?>
                            buttons += '<button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' + data + '"><i class="fas fa-trash"></i> Hapus</button>';
                        <?php endif; ?>
                        return buttons;
                    }
                }
            ]
        });

        // Delete button click handler
        $('#lecturerTable').on('click', '.delete-btn', function() {
            const lecturerId = $(this).data('id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data dosen akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/dosen/hapus/') ?>' + lecturerId,
                        type: 'POST',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    'Dihapus!',
                                    'Data dosen berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    $('#lecturerTable').DataTable().ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message || 'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan server: ' + xhr.responseText,
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Import form submission
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log(true)
            $.ajax({
                url: '<?= base_url('admin/dosen/import') ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        ).then(() => {
                            $('#importModal').modal('hide');
                            $('#lecturerTable').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            response.message || 'Terjadi kesalahan saat import data.',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan server: ' + xhr.responseText,
                        'error'
                    );
                }
            });
        });
    });
</script>