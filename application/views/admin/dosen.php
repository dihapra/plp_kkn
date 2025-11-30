<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Manajemen Data Dosen
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex m-2" style="gap:10px">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                Tambah
            </button>
            <button type="button" class="btn btn-success">
                Ekspor
            </button>
        </div>
        <?php $this->load->view('utils/filter_fakultas') ?>
        <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIDN/NUPTK</th>
                    <th>No Handphone</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Tambahkan modal-lg agar lebih luas -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Data Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs" id="dosenTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual"
                            type="button" role="tab">Tambah Manual</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="import-tab" data-bs-toggle="tab" data-bs-target="#import"
                            type="button" role="tab">Import Excel</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="dosenTabsContent">
                    <!-- Tab Tambah Manual -->
                    <div class="tab-pane fade show active" id="manual" role="tabpanel">
                        <form id="createForm">
                            <?php $this->load->view('forms/admin/dosen') ?>
                        </form>
                    </div>

                    <!-- Tab Import Excel -->
                    <div class="tab-pane fade" id="import" role="tabpanel">
                        <!-- <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Perhatian:</strong> Pastikan prodi dan fakultas menggunakan huruf besar
                        </div> -->
                        <form id="importForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="importFile" class="form-label fw-bold">Upload File CSV</label>
                                <input type="file" class="form-control" id="importFile" name="importFile"
                                    accept=".xls, .xlsx">


                            </div>
                            <div class="mb-3">
                                <a href="<?= base_url('assets/csv/template-dosen.csv') ?>"
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
                <h5 class="modal-title" id="editModalLabel">Edit Data Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="lectureId" name="id">
                    <?php $this->load->view('forms/admin/dosen') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/pages/admin/lecture.js') ?>"></script>