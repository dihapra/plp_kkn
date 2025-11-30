<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Tampilan Data Utama
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex m-2" style="gap:10px">
            <a href="<?= base_url('admin/export-all-data') ?>" type="button" class="btn btn-success">
                Ekspor
            </a>
        </div>
        <?php $this->load->view('utils/filter_fakultas') ?>
        <table id="dataTable" class="table table-bordered dt-responsive  nowrap w-100">
            <thead>
                <tr>
                    <th>Nama Sekolah</th>
                    <th>Nama Kepala Sekolah</th>
                    <th>No Handphone Kepala Sekolah</th>
                    <th>Nama Guru Pamong</th>
                    <th>Nama DPL</th>
                    <th>Email Mahasiswa</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>No Handphone Mahasiswa</th>
                    <th>Prodi</th>
                    <th>Fakultas</th>
                    <!-- Tambahkan lebih banyak kolom sesuai kebutuhan -->
                </tr>
            </thead>
        </table>
    </div>
</div>



<script src="<?= base_url('assets/js/components/select-school.js') ?>"></script>
<script src="<?= base_url('assets/js/components/select-teacher.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/admin/allData.js') ?>"></script>