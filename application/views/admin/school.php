<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Manajemen Data Sekolah
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex m-2" style="gap:10px">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                Tambah
            </button>
            <a href="<?= base_url('admin/export-kepsek') ?>" class="btn btn-success">
                Ekspor Kepala Sekolah
            </a>
        </div>
        <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nama Kepsek</th>
                    <th>Email Kepsek</th>
                    <th>Telepon Kepsek</th>
                    <th>Bank Kepsek</th>
                    <th>No Rekening Kepsek</th>
                    <th>Nama di Rekening Kepsek</th>
                    <th>NIK Kepsek</th>
                    <th>Status Kepsek</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah -->

<!-- Modal Tambah Sekolah -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog "> <!-- Tambahkan modal-lg agar lebih luas -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Data Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Tab Content -->
                <!-- Tab Tambah Manual -->
                <form id="createForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Sekolah</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </form>

                <!-- Tab Import Excel -->


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="createForm" class="btn btn-primary">Simpan</button>
                <!-- <button type="submit" form="importForm" class="btn btn-success">Import</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Sekolah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="schoolId" name="id">
                    <?php $this->load->view('forms/admin/school') ?>
                    <img id="imgBook" src="" width="200" alt="buku rekening">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/js/pages/admin/school.js') ?>"></script>
<script>
    // Pastikan SweetAlert2 sudah disertakan, misalnya:
    document.addEventListener('DOMContentLoaded', () => {
        const importForm = document.getElementById('importForm');

        importForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // Mencegah submit form default

            const formData = new FormData(importForm);

            try {
                const response = await fetch(importForm.action, {
                    method: 'POST',
                    body: formData
                });

                // Cek status HTTP menggunakan response.ok
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                }

                // Jika response.ok, parse JSON
                const data = await response.json();

                // Tampilkan notifikasi sukses
                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message || 'Data berhasil diimport!',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Setelah notifikasi, reload halaman atau lakukan tindakan lain
                location.reload();
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan, coba lagi nanti.'
                });
            }
        });
    });
</script>
