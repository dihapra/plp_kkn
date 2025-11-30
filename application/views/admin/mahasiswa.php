<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Manajemen data Mahasiswa
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex m-2" style="gap:10px">
            <a href="<?= base_url('admin/mahasiswa/insert') ?>" type="button" class="btn btn-primary">
                Tambah
            </a>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importAll">
                Import dengan sekolah dan dosen
            </button>
            <button type="button" class="btn btn-success">
                Ekspor
            </button>
        </div>
        <?php $this->load->view('utils/filter_fakultas') ?>
        <table id="dataTable"
            class="table table-hover table-striped table-bordered align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>No Handphone</th>
                    <th>Nama Sekolah</th>
                    <th>Dosen</th>
                    <th>Guru Pamong</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables isi otomatis -->
            </tbody>
        </table>

    </div>
</div>
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data" action="<?= base_url('admin/mahasiswa/import') ?>"
                    method="post">
                    <div class="mb-3">
                        <label for="fileCSV" class="form-label fw-bold">Upload File CSV</label>
                        <input type="file" class="form-control" id="fileCSV" name="fileCSV" accept=".csv" required>
                    </div>
                    <div class="mb-3">
                        <a href="<?= base_url('template/mahasiswa_template.csv') ?>" class="btn btn-outline-primary"
                            download>
                            <i class="bi bi-download"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="importForm" class="btn btn-success">Import</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="importAll" tabindex="-1" aria-labelledby="importAllLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="importAllLabel">Import Data (CSV/Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm" enctype="multipart/form-data" action="<?= base_url('admin/import_lecture_student_school') ?>" method="post">
                    <div class="mb-3">
                        <label for="fileImportCsv" class="form-label fw-bold">Upload File CSV/Excel</label>
                        <input type="file" class="form-control" id="fileImportCsv" name="fileImportCsv" accept=".csv,.xlsx" required>
                        <div class="form-text">
                            Dukung: .csv, .xlsx. Pastikan header kolom sesuai template.
                        </div>
                    </div>

                    <!-- Jika pakai CSRF CI3, sisipkan hidden input berikut -->
                    <?php if ($this->security->get_csrf_token_name()) : ?>
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <?php endif; ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="uploadForm" class="btn btn-success" id="btnImport">Import</button>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets/js/components/select-school.js') ?>"></script>
<script src="<?= base_url('assets/js/components/select-teacher.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/admin/mahasiswa.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/admin/import/importStudent.js') ?>"></script>