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
                        <a class="nav-link" href="<?= base_url('super-admin/admin-pic') ?>">Admin PIC</a>
                        <a class="nav-link" href="<?= base_url('super-admin/kepala-sekolah') ?>">Kepala Sekolah</a>
                        <a class="nav-link" href="<?= base_url('super-admin/guru') ?>">Guru</a>
                        <a class="nav-link" href="<?= base_url('super-admin/desa') ?>">Desa</a>
                        <a class="nav-link" href="<?= base_url('super-admin/program') ?>">Program</a>
                    </nav>
                </div>

                <div class="sb-sidenav-menu-heading">Program</div>
                <?php
                $moduleMenus = [
                    'plp' => [
                        'label' => 'PLP I',
                        'items' => [
                            ['label' => 'Kegiatan', 'path' => 'super-admin/plp/activities'],
                            ['label' => 'Laporan', 'path' => 'super-admin/plp/report'],
                            [
                                'label' => 'Master Data',
                                'children' => [
                                    ['label' => 'Sekolah', 'path' => 'super-admin/plp/master-data/sekolah'],
                                    ['label' => 'Dosen', 'path' => 'super-admin/plp/master-data/dosen'],
                                    ['label' => 'Mahasiswa', 'path' => 'super-admin/plp/master-data/mahasiswa'],
                                    ['label' => 'Data Mahasiswa Admin', 'path' => 'super-admin/plp/master-data/mahasiswa-true'],
                                    ['label' => 'Guru', 'path' => 'super-admin/plp/master-data/guru'],
                                    ['label' => 'Kepala Sekolah', 'path' => 'super-admin/plp/master-data/kepsek'],
                                ],
                            ],
                            [
                                'label' => 'Verifikasi',
                                'children' => [
                                    ['label' => 'Mahasiswa', 'path' => 'super-admin/plp/verifikasi/mahasiswa'],
                                    ['label' => 'Sekolah', 'path' => 'super-admin/plp/verifikasi/sekolah'],
                                    ['label' => 'Guru', 'path' => 'super-admin/plp/verifikasi/guru'],
                                    ['label' => 'Kepala Sekolah', 'path' => 'super-admin/plp/verifikasi/kepsek'],
                                ],
                            ],
                            ['label' => 'Absensi', 'path' => 'super-admin/plp/absensi'],
                        ],
                    ],
                    'plp2' => [
                        'label' => 'PLP II',
                        'items' => [
                            ['label' => 'Kegiatan', 'path' => 'super-admin/plp2/activities'],
                            ['label' => 'Laporan', 'path' => 'super-admin/plp2/report'],
                            [
                                'label' => 'Verifikasi',
                                'children' => [
                                    ['label' => 'Mahasiswa', 'path' => 'super-admin/plp2/verifikasi/mahasiswa'],
                                    ['label' => 'Guru', 'path' => 'super-admin/plp2/verifikasi/guru'],
                                    ['label' => 'Kepala Sekolah', 'path' => 'super-admin/plp2/verifikasi/kepsek'],
                                ],
                            ],
                            ['label' => 'Absensi', 'path' => 'super-admin/plp2/absensi'],
                        ],
                    ],
                    'kkn' => [
                        'label' => 'KKN',
                        'items' => [
                            ['label' => 'Kegiatan', 'path' => 'super-admin/kkn/activities'],
                            ['label' => 'Laporan', 'path' => 'super-admin/kkn/report'],
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
                            <?php foreach ($menu['items'] as $index => $item): ?>
                                <?php if (!empty($item['children'])): ?>
                                    <?php $childCollapseId = sprintf('collapseModule%sItem%s', ucfirst($key), $index); ?>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#<?= $childCollapseId ?>" aria-expanded="false" aria-controls="<?= $childCollapseId ?>">
                                        <?= htmlspecialchars($item['label']) ?>
                                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                                    </a>
                                    <div class="collapse" id="<?= $childCollapseId ?>" data-bs-parent="#collapseModule<?= ucfirst($key) ?>">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <?php foreach ($item['children'] as $child): ?>
                                                <a class="nav-link" href="<?= base_url($child['path']) ?>">
                                                    <?= htmlspecialchars($child['label']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </nav>
                                    </div>
                                <?php else: ?>
                                    <a class="nav-link" href="<?= base_url($item['path']) ?>"><?= htmlspecialchars($item['label']) ?></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                <?php endforeach; ?>

                <div class="sb-sidenav-menu-heading">Payments</div>
                <a class="nav-link" href="<?= base_url('super-admin/pembayaran') ?>">
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
