<div class="super-admin-dashboard">
    <div class="row gx-4 gy-4 mt-2">
    <?php foreach ($summary as $label => $value): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <p class="text-uppercase small text-muted mb-1"><?= $label ?></p>
                    <h3 class="fw-bold"><?= number_format($value) ?></h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="row gx-4 gy-4 mt-3">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Pengguna berdasarkan role</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Role</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_by_role as $row): ?>
                                <tr>
                                    <td><?= ucfirst($row['role']) ?></td>
                                    <td class="text-end"><?= number_format($row['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($users_by_role)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted small">Belum ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">User berdasarkan Program</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_by_program as $row): ?>
                                <tr>
                                    <td><?= $row['program'] ?: 'Belum memilih program' ?></td>
                                    <td class="text-end"><?= number_format($row['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($users_by_program)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted small">Belum ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row gx-4 gy-4 mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Kelompok per Program</h5>
                <p class="small text-muted mb-0">Jumlah kelompok berdasarkan program yang ada di basis data.</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Program</th>
                                <th class="text-end">Jumlah Kelompok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($groups_by_program as $row): ?>
                                <tr>
                                    <td><?= $row['program'] ?: 'Belum ditentukan' ?></td>
                                    <td class="text-end"><?= number_format($row['total_groups']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($groups_by_program)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted small">Belum ada data</td>
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
