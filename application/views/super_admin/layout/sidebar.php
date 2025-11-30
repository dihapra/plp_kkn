<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Master Data</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSuperMasterData" aria-expanded="false" aria-controls="collapseSuperMasterData">
                    <div class="sb-nav-link-icon"><i class="bi bi-database"></i></div>
                    Master Data
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                </a>
                <div class="collapse" id="collapseSuperMasterData" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?= base_url('super-admin/sekolah') ?>">Sekolah</a>
                        <a class="nav-link" href="<?= base_url('super-admin/prodi') ?>">Prodi</a>
                        <a class="nav-link" href="<?= base_url('super-admin/user') ?>">User</a>
                        <a class="nav-link" href="<?= base_url('super-admin/dosen') ?>">Dosen</a>
                        <a class="nav-link" href="<?= base_url('super-admin/mahasiswa') ?>">Mahasiswa</a>
                        <a class="nav-link" href="<?= base_url('super-admin/kaprodi') ?>">Kaprodi</a>
                        <a class="nav-link" href="<?= base_url('super-admin/kepala-sekolah') ?>">Kepala Sekolah</a>
                        <a class="nav-link" href="<?= base_url('super-admin/guru') ?>">Guru</a>
                        <a class="nav-link" href="<?= base_url('super-admin/desa') ?>">Desa</a>
                        <a class="nav-link" href="<?= base_url('super-admin/program') ?>">Program</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Modul</div>
                <?php
                $moduleMenus = [
                    'plp' => [
                        'label' => 'PLP I',
                        'items' => [
                            'Kegiatan' => 'admin/plp/activities',
                            'Laporan' => 'admin/plp/report',
                            'Verifikasi' => 'admin/plp/verifikasi',
                            'Absensi' => 'admin/plp/absensi',
                        ],
                    ],
                    'plp2' => [
                        'label' => 'PLP II',
                        'items' => [
                            'Kegiatan' => 'admin/plp2/activities',
                            'Laporan' => 'admin/plp2/report',
                            'Verifikasi' => 'admin/plp2/verifikasi',
                            'Absensi' => 'admin/plp2/absensi',
                        ],
                    ],
                    'kkn' => [
                        'label' => 'KKN',
                        'items' => [
                            'Kegiatan' => 'admin/kkn/activities',
                            'Laporan' => 'admin/kkn/report',
                        ],
                    ],
                ];
                foreach ($moduleMenus as $key => $menu):
                ?>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseModule<?= ucfirst($key) ?>" aria-expanded="false" aria-controls="collapseModule<?= ucfirst($key) ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-grid-fill"></i></div>
                        <?= $menu['label'] ?>
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseModule<?= ucfirst($key) ?>" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <?php foreach ($menu['items'] as $text => $path): ?>
                                <a class="nav-link" href="<?= base_url($path) ?>"><?= $text ?></a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                <?php endforeach; ?>

                <div class="sb-sidenav-menu-heading">Payments</div>
                <a class="nav-link" href="<?= base_url('admin/pembayaran') ?>">
                    <div class="sb-nav-link-icon"><i class="bi bi-credit-card"></i></div>
                    Pembayaran
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $this->session->userdata('name') ?: $this->session->userdata('email') ?>
        </div>
    </nav>
</div>
