<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Admin</div>
                <a class="nav-link" href="<?= base_url('admin') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-speedometer2"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="<?= base_url('admin/program') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-layers"></i></div>
                    Pilih Program
                </a>

                <div class="sb-sidenav-menu-heading">PLP I</div>
                <a class="nav-link" href="<?= base_url('admin/plp1/activities') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-calendar-event"></i></div>
                    Kegiatan
                </a>
                <a class="nav-link" href="<?= base_url('admin/plp1/report') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-clipboard-data"></i></div>
                    Laporan
                </a>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePlpMasterData" aria-expanded="false" aria-controls="collapsePlpMasterData">
                    <div class="sb-nav-link-icon"><i class="bi bi-database"></i></div>
                    Master Data
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapsePlpMasterData" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/sekolah') ?>">Sekolah</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/dosen') ?>">Dosen</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/mahasiswa') ?>">Mahasiswa</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/mahasiswa-true') ?>">Data Mahasiswa Admin</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/guru') ?>">Guru</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/master-data/kepsek') ?>">Kepala Sekolah</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePlpVerification" aria-expanded="false" aria-controls="collapsePlpVerification">
                    <div class="sb-nav-link-icon"><i class="bi bi-shield-check"></i></div>
                    Verifikasi
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapsePlpVerification" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= base_url('admin/plp1/verifikasi/mahasiswa') ?>">Mahasiswa</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/verifikasi/sekolah') ?>">Sekolah</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/verifikasi/guru') ?>">Guru</a>
                        <a class="nav-link" href="<?= base_url('admin/plp1/verifikasi/kepsek') ?>">Kepala Sekolah</a>
                    </nav>
                </div>

                <a class="nav-link" href="<?= base_url('admin/plp1/absensi') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-person-check"></i></div>
                    Absensi
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $this->session->userdata('name') ?: $this->session->userdata('email') ?>
        </div>
    </nav>
</div>

