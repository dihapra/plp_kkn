<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <?php
                $role = (string) $this->session->userdata('role');
                ?>

                <?php if ($role === 'admin' || $role === 'super_admin'): ?>
                    <div class="sb-sidenav-menu-heading">Admin</div>

                    <!-- Master Data -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseMasterData" aria-expanded="false" aria-controls="collapseMasterData">
                        <div class="sb-nav-link-icon"><i class="bi bi-columns-gap"></i></div>
                        Master Data
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseMasterData" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('admin/semua-data') ?>">Data Utama</a>
                            <a class="nav-link" href="<?= base_url('admin/sekolah') ?>">Sekolah</a>
                            <a class="nav-link" href="<?= base_url('admin/guru') ?>">Guru</a>
                            <a class="nav-link" href="<?= base_url('admin/dosen') ?>">Dosen</a>
                            <a class="nav-link" href="<?= base_url('admin/mahasiswa') ?>">Mahasiswa</a>
                            <a class="nav-link" href="<?= base_url('admin/user') ?>">User</a>
                        </nav>
                    </div>

                    <!-- Aktivitas (gabung jadi satu collapse, tidak pakai ID duplikat) -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseActivity" aria-expanded="false" aria-controls="collapseActivity">
                        <div class="sb-nav-link-icon"><i class="bi bi-activity"></i></div>
                        Aktivitas
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseActivity" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('admin/aktivitas/mahasiswa') ?>">Mahasiswa</a>
                            <a class="nav-link" href="<?= base_url('admin/aktivitas/dosen') ?>">Dosen</a>
                            <a class="nav-link" href="<?= base_url('admin/aktivitas/guru') ?>">Guru</a>
                        </nav>
                    </div>

                    <!-- Absensi -->
                    <a class="nav-link" href="<?= base_url('admin/absensi') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-calendar-check"></i></div>
                        Absensi
                    </a>

                    <!-- Verifikasi -->
                    <div class="sb-sidenav-menu-heading">Verifikasi</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVerifikasi" aria-expanded="false" aria-controls="collapseVerifikasi">
                        <div class="sb-nav-link-icon"><i class="bi bi-shield-check"></i></div>
                        Verifikasi
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseVerifikasi" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('admin/verifikasi-guru') ?>">Verifikasi Guru</a>
                            <a class="nav-link" href="<?= base_url('admin/verifikasi-kepala-sekolah') ?>">Verifikasi Kepala Sekolah</a>
                            <a class="nav-link" href="<?= base_url('admin/histori-verifikasi-guru') ?>">History Verifikasi Guru</a>
                            <a class="nav-link" href="<?= base_url('admin/histori-verifikasi-kepsek') ?>">History Verifikasi Kepala Sekolah</a>
                        </nav>
                    </div>


                    <!-- Lainnya -->
                    <div class="sb-sidenav-menu-heading">Lainnya</div>
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLainnya" aria-expanded="false" aria-controls="collapseLainnya">
                        <div class="sb-nav-link-icon"><i class="bi bi-gear"></i></div>
                        Lainnya
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLainnya" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('admin/sekolah-tanpa-kepsek') ?>">Sekolah Belum Ada Kepsek</a>
                            <a class="nav-link" href="<?= base_url('admin/mahasiswa-tanpa-guru') ?>">Mahasiswa Belum Ada Guru Pamong</a>
                        </nav>
                    </div>

                    <?php if ($role === 'super_admin'): ?>
                        <div class="sb-sidenav-menu-heading">Master Data</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSuperMasterData" aria-expanded="false" aria-controls="collapseSuperMasterData">
                            <div class="sb-nav-link-icon"><i class="bi bi-database"></i></div>
                            Master Data
                            <div class="sb-sidenav-collapse-arrow"><i class="bi bi-caret-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseSuperMasterData" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="<?= base_url('admin/sekolah') ?>">Sekolah</a>
                                <a class="nav-link" href="<?= base_url('admin/user') ?>">User</a>
                                <a class="nav-link" href="<?= base_url('admin/dosen') ?>">Dosen</a>
                                <a class="nav-link" href="<?= base_url('admin/kepala-sekolah') ?>">Kepala Sekolah</a>
                                <a class="nav-link" href="<?= base_url('admin/desa') ?>">Desa</a>
                                <a class="nav-link" href="<?= base_url('admin/program') ?>">Program</a>
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
                            <div class="collapse" id="collapseModule<?= ucfirst($key) ?>" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
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
                    <?php endif; ?>

                <?php elseif (in_array($role, ['lecturer', 'dosen'], true)): ?>
                    <div class="sb-sidenav-menu-heading">Dosen</div>
                    <a class="nav-link" href="<?= base_url('dosen/aktivitas') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-calendar-event"></i></div>
                        Aktivitas
                    </a>
                    <a class="nav-link" href="<?= base_url('dosen/mahasiswa') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-mortarboard"></i></div>
                        Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('dosen/kelompok') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-person-lines-fill"></i></div>
                        Kelompok
                    </a>
                    <a class="nav-link" href="<?= base_url('dosen/absensi') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-clipboard-check"></i></div>
                        Absensi Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('dosen/logbook') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-book"></i></div>
                        Logbook Mahasiswa
                    </a>

                    <!-- Penilaian dropdown -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseLecturerEvaluation" aria-expanded="false" aria-controls="collapseLecturerEvaluation">
                        <div class="sb-nav-link-icon"><i class="bi bi-journal-bookmark"></i></div>
                        Penilaian
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLecturerEvaluation" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('dosen/tugas/laporan-kemajuan') ?>">Laporan Kemajuan</a>
                            <a class="nav-link" href="<?= base_url('dosen/tugas/laporan-akhir') ?>">Laporan Akhir</a>
                            <a class="nav-link" href="<?= base_url('dosen/tugas/modul-ajar') ?>">Modul Ajar</a>
                            <a class="nav-link" href="<?= base_url('dosen/tugas/modul-proyek') ?>">Modul Proyek</a>
                            <a class="nav-link" href="<?= base_url('dosen/tugas/bahan-ajar') ?>">Bahan Ajar</a>
                            <a class="nav-link" href="<?= base_url('dosen/penilaian/intrakurikuler') ?>">Asistensi Intrakurikuler</a>
                            <a class="nav-link" href="<?= base_url('dosen/penilaian/ekstrakurikuler') ?>">Asistensi Ekstrakurikuler</a>
                            <a class="nav-link" href="<?= base_url('dosen/penilaian/sikap') ?>">Sikap Mahasiswa</a>
                            <a class="nav-link" href="<?= base_url('dosen/penilaian/analisis') ?>">Analisis Mahasiswa</a>
                            <a class="nav-link" href="<?= base_url('dosen/nilai') ?>">Nilai Final</a>
                        </nav>
                    </div>

                <?php elseif (in_array($role, ['teacher', 'guru'], true)): ?>
                    <div class="sb-sidenav-menu-heading">Guru</div>
                    <a class="nav-link" href="<?= base_url('guru/aktivitas') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-calendar-event"></i></div>
                        Aktivitas
                    </a>
                    <a class="nav-link" href="<?= base_url('guru/logbook') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-book"></i></div>
                        Logbook Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('guru/mahasiswa') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-mortarboard"></i></div>
                        Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('guru/absensi') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-person-check"></i></div>
                        Absensi Mahasiswa
                    </a>

                    <!-- Penilaian dropdown -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseGuruEvaluation" aria-expanded="false" aria-controls="collapseGuruEvaluation">
                        <div class="sb-nav-link-icon"><i class="bi bi-journal-bookmark"></i></div>
                        Penilaian
                        <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseGuruEvaluation" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="<?= base_url('guru/penilaian/intrakurikuler') ?>">Intrakurikuler</a>
                            <a class="nav-link" href="<?= base_url('guru/penilaian/ekstrakurikuler') ?>">Ekstrakurikuler</a>
                            <a class="nav-link" href="<?= base_url('guru/penilaian/sikap') ?>">Sikap Mahasiswa</a>
                        </nav>
                    </div>
                    <!-- <a class="nav-link" href="<?= base_url('guru/tugas') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-list-check"></i></div>
                        Tugas
                    </a>
                    <a class="nav-link" href="<?= base_url('guru/sertifikat') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-award"></i></div>
                        Sertifikat
                    </a> -->

                <?php elseif (in_array($role, ['principal', 'kepsek'], true)): ?>
                    <div class="sb-sidenav-menu-heading">Kepala Sekolah</div>
                    <a class="nav-link" href="<?= base_url('kepala-sekolah/mahasiswa') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-mortarboard"></i></div>
                        Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('kepala-sekolah/guru') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                        Guru
                    </a>
                    <!-- <a class="nav-link" href="<?= base_url('kepala-sekolah/sertifikat') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-award"></i></div>
                        Sertifikat
                    </a> -->

                <?php elseif ($role === 'kaprodi'): ?>
                    <div class="sb-sidenav-menu-heading">Kaprodi</div>
                    <a class="nav-link" href="<?= base_url('kaprodi') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-speedometer2"></i></div>
                        Dashboard
                    </a>
                    <a class="nav-link" href="<?= base_url('kaprodi/mahasiswa') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-mortarboard"></i></div>
                        Mahasiswa
                    </a>
                    <a class="nav-link" href="<?= base_url('kaprodi/dosen') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                        Dosen Pembimbing
                    </a>
                    <a class="nav-link" href="<?= base_url('kaprodi/laporan') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-clipboard-data"></i></div>
                        Laporan
                    </a>

                <?php elseif (in_array($role, ['student', 'mahasiswa'], true)): ?>
                    <div class="sb-sidenav-menu-heading">Mahasiswa</div>
                    <a class="nav-link" href="<?= base_url('mahasiswa/aktivitas') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-calendar-event"></i></div>
                        Aktivitas
                    </a>
                    <a class="nav-link" href="<?= base_url('mahasiswa/tugas') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-list-check"></i></div>
                        Tugas
                    </a>

                <?php else: ?>
                    <div class="sb-sidenav-menu-heading">Unauthorized</div>
                    <a class="nav-link" href="<?= base_url('login') ?>">
                        <div class="sb-nav-link-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= $this->session->userdata('name'); ?>
        </div>
    </nav>
</div>
