<style>
    .super-admin-dashboard {
        color: #f8fafc;
    }

    .super-admin-dashboard .card {
        background: rgba(2, 6, 23, 0.9);
        border-radius: 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.45);
    }

    .super-admin-dashboard .card-header {
        background: rgba(15, 23, 42, 0.96) !important;
        border-bottom: 1px solid rgba(148, 163, 184, 0.5);
        color: #f9fafb;
    }

    .super-admin-dashboard .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border: none;
        box-shadow: 0 12px 30px rgba(13, 110, 253, 0.4);
    }

    .super-admin-dashboard table {
        color: #f9fafb;
        background-color: transparent;
    }

    .super-admin-dashboard thead th {
        background-color: rgba(15, 23, 42, 0.96);
        border-color: rgba(148, 163, 184, 0.65);
        color: #f9fafb;
    }

    .super-admin-dashboard tbody tr {
        background-color: rgba(15, 23, 42, 0.9);
        border-color: rgba(148, 163, 184, 0.45);
    }

    .super-admin-dashboard tbody tr:nth-child(odd) {
        background-color: rgba(15, 23, 42, 0.85);
    }

    .super-admin-dashboard .badge {
        font-size: 0.75rem;
        border-radius: 999px;
        padding: 0.3rem 0.75rem;
    }

    .super-admin-dashboard .dropdown-toggle::after {
        display: none;
    }
</style>

<div class="super-admin-dashboard">
    <div class="row gx-4 gy-4 mt-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="mb-1">Daftar Program</h3>
                    <p class="mb-0 text-muted">Kelola program PLP / KKN beserta status aktifnya.</p>
                </div>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Tambah Program
                </button>
            </div>
        </div>
    </div>

    <div class="row gx-4 gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Program</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Nama Program</th>
                                    <th style="width: 25%;">Tahun Ajaran</th>
                                    <th style="width: 20%;">Status</th>
                                    <th class="text-end" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($programs)): ?>
                                    <?php foreach ($programs as $program): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($program['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($program['tahun_ajaran'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <?php if (!empty($program['is_active'])): ?>
                                                    <span class="badge bg-success-subtle text-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="bi bi-pencil-square me-2"></i>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#">
                                                                <i class="bi bi-check-circle me-2"></i>
                                                                Active / Nonactive
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted small py-4">
                                            Belum ada program terdaftar.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

