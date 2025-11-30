<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?= isset($lecturer) ? 'Edit Dosen' : 'Tambah Dosen' ?></h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form id="lecturerForm" action="<?= isset($lecturer) ? base_url('admin/dosen/update/' . $lecturer->id) : base_url('admin/dosen/simpan') ?>" method="post">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama" value="<?= isset($lecturer) ? $lecturer->name : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" value="<?= isset($lecturer) ? $lecturer->nip : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan Email" value="<?= isset($lecturer) ? $lecturer->email : '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan Telepon" value="<?= isset($lecturer) ? $lecturer->phone : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="prodi">Prodi</label>
                            <input type="text" class="form-control" id="prodi" name="prodi" placeholder="Masukkan Prodi" value="<?= isset($lecturer) ? $lecturer->prodi : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="fakultas">Fakultas</label>
                            <input type="text" class="form-control" id="fakultas" name="fakultas" placeholder="Masukkan Fakultas" value="<?= isset($lecturer) ? $lecturer->fakultas : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password <?= isset($lecturer) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" <?= isset($lecturer) ? '' : 'required' ?>>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= base_url('admin/dosen') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#lecturerForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.href = '<?= base_url('admin/dosen') ?>';
                        });
                    } else {
                        Swal.fire(
                            'Gagal!',
                            response.message || 'Terjadi kesalahan saat menyimpan data.',
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
