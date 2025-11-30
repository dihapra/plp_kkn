<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Manajemen Data Guru
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex m-2" style="gap:10px">
            <?php if ($this->session->userdata('role') === 'super_admin'): ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    Tambah
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-success ekspor-guru">
                Ekspor
            </button>
        </div>
        <?php $this->load->view('utils/filter_sekolah') ?>
        <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Phone</th>
                    <th>Nama Sekolah</th>
                    <th>Bank</th>
                    <th>No Rekening</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<!-- Modal Tambah Guru -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Perbesar modal agar lebih nyaman -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Data Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="teacherTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual"
                            type="button" role="tab">Tambah Manual</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import"
                            type="button" role="tab">Import Excel/CSV</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="teacherTabsContent">
                    <!-- Tab Tambah Manual -->
                    <div class="tab-pane fade show active" id="manual" role="tabpanel">
                        <form id="createForm">
                            <?php $this->load->view('forms/admin/teacher', ['create' => true]); ?>
                        </form>
                    </div>

                    <!-- Tab Import Excel/CSV -->
                    <div class="tab-pane fade" id="import" role="tabpanel">
                        <form id="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="importFile" class="form-label fw-bold">Upload File CSV</label>
                                <input type="file" class="form-control" id="importFile" name="importFile"
                                    accept=".csv, .xls, .xlsx">
                            </div>
                            <div class="mb-3">
                                <a href="<?= base_url('assets/csv/template-guru.csv') ?>"
                                    class="btn btn-outline-primary" download>
                                    <i class="bi bi-download"></i> Download Template
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="createForm" class="btn btn-primary">Simpan</button>
                <button type="submit" form="importForm" class="btn btn-success">Import</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="teacherId" name="id">
                    <?php $this->load->view('forms/admin/teacher', ['create' => false]) ?>
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

<script src="<?= base_url('assets/js/pages/admin/teacher.js') ?>"></script>
<script src="<?= base_url('assets/js/components/select-school.js') ?>"></script>
<script>
    $(document).on('click', '.ekspor-guru', async function(e) {
        let schoolId = $('#sekolah_filter').val() ?? null;
        let url = baseUrl + "admin/export-teachers?schoolId=" + schoolId;

        // console.log(url, schoolId);

        try {
            window.location.href = url;

        } catch (error) {
            Swal.fire({
                title: "Error",
                text: "Terjadi kesalahan saat mengekspor data.",
                icon: "error"
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('importForm');

        importForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Tampilkan loading indicator menggunakan Swal.fire
            Swal.fire({
                title: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Ambil data form dengan FormData
            const formData = new FormData(importForm);

            // Kirim request dengan Fetch API
            fetch("<?= site_url('admin/import-teachers'); ?>", {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close(); // Tutup loading indicator

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Contoh: Reload halaman setelah sukses
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses file.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
        });
    });
</script>