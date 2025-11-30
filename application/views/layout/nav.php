<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <?php
    $role = (string) $this->session->userdata('role');

    // Map role -> base path
$roleToPath = [
        'super_admin' => 'super-admin',
        'admin'       => 'admin',
        'lecturer'    => 'dosen',
        'dosen'       => 'dosen',
        'teacher'     => 'guru',
        'guru'        => 'guru',
        'principal'   => 'kepala-sekolah',
        'kepsek'      => 'kepala-sekolah',
        'student'     => 'mahasiswa',
        'mahasiswa'   => 'mahasiswa',
        'kaprodi'     => 'kaprodi',
    ];

    $brandPath  = $roleToPath[$role] ?? 'login';
    $brandUrl   = site_url($brandPath);
    $brandLabel = isset($roleToPath[$role]) ? 'Dashboard' : 'Login';
    ?>

    <!-- Brand -->
    <a class="navbar-brand ps-3 d-flex align-items-center" href="<?= $brandUrl ?>">
        <img src="<?= base_url('assets/images/logo-unimed.png') ?>" width="48" height="48" alt="Logo" class="me-2">
        <?= $brandLabel ?>
    </a>

    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0"
        id="sidebarToggle" type="button" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>

    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></form>

    <!-- Navbar Right -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <?php if (!in_array($role, ['admin', 'super_admin'], true)): ?>
                    <li><a class="dropdown-item" href="<?= base_url('user/profil-pengguna') ?>">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>
