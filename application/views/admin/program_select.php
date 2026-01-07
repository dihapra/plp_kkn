<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Program | Admin PLP-KKN</title>
    <link rel="icon" href="<?= base_url('assets/images/unimed.ico'); ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-start: #0b1120;
            --bg-mid: #1e293b;
            --bg-end: #22d3ee;
            --card-bg: rgba(255, 255, 255, 0.9);
            --accent: #0ea5e9;
            --accent-dark: #0284c7;
            --text-dark: #0f172a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(14, 165, 233, 0.25), transparent 40%),
                radial-gradient(circle at bottom right, rgba(34, 211, 238, 0.35), transparent 40%),
                linear-gradient(135deg, var(--bg-start), var(--bg-mid) 45%, var(--bg-end));
            color: var(--text-dark);
        }

        .selection-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .selection-card {
            width: 100%;
            max-width: 860px;
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.3);
            overflow: hidden;
        }

        .selection-header {
            padding: 32px 40px;
            background: linear-gradient(120deg, rgba(14, 165, 233, 0.15), rgba(14, 116, 144, 0.08));
        }

        .selection-body {
            padding: 32px 40px 40px;
        }

        .program-option {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 16px;
            padding: 18px 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .program-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 24px rgba(15, 23, 42, 0.1);
        }

        .program-radio:checked + .program-option {
            border-color: rgba(14, 165, 233, 0.5);
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.15);
        }

        .btn-primary {
            border-radius: 12px;
            border: none;
            background: linear-gradient(120deg, var(--accent), var(--accent-dark));
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, var(--accent-dark), var(--accent));
        }

        .btn-outline-light {
            border-radius: 12px;
        }

        .badge-active {
            background: rgba(16, 185, 129, 0.15);
            color: #047857;
        }

        .badge-inactive {
            background: rgba(148, 163, 184, 0.2);
            color: #475569;
        }
    </style>
</head>

<body>
    <div class="selection-shell">
        <div class="selection-card">
            <div class="selection-header d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <p class="text-uppercase small text-muted mb-1">Admin PIC</p>
                    <h2 class="fw-semibold mb-1">Pilih Program Kerja</h2>
                    <p class="mb-0 text-muted">Hai, <?= html_escape($userName ?: 'Admin'); ?>. Pilih program sebelum masuk ke dashboard.</p>
                </div>
                <div>
                    <a href="<?= base_url('logout'); ?>" class="btn btn-outline-light btn-sm text-dark border-0">
                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                    </a>
                </div>
            </div>
            <div class="selection-body">
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
                <?php endif; ?>

                <form action="<?= base_url('admin/program/select'); ?>" method="post">
                    <?php if (empty($programs)): ?>
                        <div class="alert alert-warning mb-0">
                            Program untuk akun ini belum tersedia. Silakan hubungi super admin.
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($programs as $program): ?>
                                <?php
                                $programId = (int) $program['id'];
                                $label = strtoupper($program['kode'] ?? '') ?: ($program['nama'] ?? 'Program');
                                $subtitle = trim(($program['nama'] ?? '') . ' ' . ($program['tahun_ajaran'] ?? ''));
                                $active = !empty($program['active']);
                                ?>
                                <div class="col-12 col-md-6">
                                    <label class="w-100">
                                        <input type="radio"
                                            name="program_id"
                                            value="<?= $programId; ?>"
                                            class="visually-hidden program-radio"
                                            <?= $programId === (int) $selectedProgramId ? 'checked' : ''; ?>
                                            required>
                                        <div class="program-option h-100">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h5 class="mb-0 fw-semibold"><?= html_escape($label); ?></h5>
                                                <span class="badge <?= $active ? 'badge-active' : 'badge-inactive'; ?>">
                                                    <?= $active ? 'Aktif' : 'Nonaktif'; ?>
                                                </span>
                                            </div>
                                            <p class="mb-2 text-muted"><?= html_escape(trim($subtitle) ?: 'Program PLP-KKN'); ?></p>
                                            <?php if (!empty($program['semester'])): ?>
                                                <small class="text-muted">Semester <?= html_escape($program['semester']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4">
                            <p class="text-muted mb-0">
                                Program terpilih akan digunakan untuk filter data admin.
                            </p>
                            <button type="submit" class="btn btn-primary px-4">
                                Lanjut ke Dashboard <i class="bi bi-arrow-right-short ms-1"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
