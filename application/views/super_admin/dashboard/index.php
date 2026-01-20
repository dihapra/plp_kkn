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

    <div class="row gx-4 gy-4 mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-2">
                    <div>
                        <h5 class="card-title mb-0">Pendaftar per Prodi</h5>
                        <p class="small text-muted mb-0">Statistik jumlah pendaftar berdasarkan program studi.</p>
                    </div>
                    <form method="get" action="<?= base_url('super-admin/dashboard') ?>" class="row g-2 align-items-center">
                        <div class="col-12 col-md-auto">
                            <select class="form-select form-select-sm" name="program_code" id="plp1ProgramFilter">
                                <option value="">Semua Program</option>
                                <?php foreach ($program_code_options as $row): ?>
                                    <option value="<?= $row['kode'] ?>" <?= $row['kode'] === $selected_program_code ? 'selected' : '' ?>>
                                        <?= $row['nama'] ?: strtoupper($row['kode']) ?> (<?= $row['kode'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-md-auto">
                            <select class="form-select form-select-sm" name="tahun_ajaran" id="plp1YearFilter">
                                <option value="">Semua Tahun</option>
                                <?php foreach ($program_year_options as $row): ?>
                                    <option value="<?= $row['tahun_ajaran'] ?>" <?= $row['tahun_ajaran'] === $selected_tahun_ajaran ? 'selected' : '' ?>>
                                        <?= $row['tahun_ajaran'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?php
                    $plp1ChartLabels = [];
                    $plp1ChartTotals = [];
                    foreach ($plp1_registrants_by_prodi as $row) {
                        $plp1ChartLabels[] = $row['prodi'] ?: 'Belum ditentukan';
                        $plp1ChartTotals[] = (int) $row['total'];
                    }
                ?>
                <?php if (empty($plp1_registrants_by_prodi)): ?>
                    <p class="text-center text-muted small mb-0">Belum ada data</p>
                <?php else: ?>
                    <canvas id="plp1ProdiChart" height="260" style="min-height: 260px;"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const programFilter = document.getElementById('plp1ProgramFilter');
    const yearFilter = document.getElementById('plp1YearFilter');
    [programFilter, yearFilter].forEach((el) => {
        if (!el) {
            return;
        }
        el.addEventListener('change', () => {
            el.closest('form').submit();
        });
    });

    const plp1Labels = <?= json_encode($plp1ChartLabels) ?>;
    const plp1Totals = <?= json_encode($plp1ChartTotals) ?>;
    const plp1Canvas = document.getElementById('plp1ProdiChart');

    if (plp1Canvas && plp1Labels.length) {
        const shortLabels = plp1Labels.map((label) => {
            return label
                .split(/\s+/)
                .filter(Boolean)
                .map((word) => word.charAt(0))
                .join('')
                .toUpperCase();
        });
        new Chart(plp1Canvas, {
            type: 'bar',
            data: {
                labels: shortLabels,
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: plp1Totals,
                    backgroundColor: 'rgba(54, 162, 235, 0.35)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: (items) => {
                                if (!items.length) {
                                    return '';
                                }
                                return plp1Labels[items[0].dataIndex] || '';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
