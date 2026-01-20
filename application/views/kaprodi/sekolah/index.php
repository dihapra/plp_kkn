<?php
$programSekolahOptions = $programSekolahOptions ?? [];
$rows = $rows ?? [];
$prodiInfo = $prodiInfo ?? null;
$activeProgram = $activeProgram ?? null;

$prodiLabel = $prodiInfo
    ? trim($prodiInfo['nama'] . (!empty($prodiInfo['fakultas']) ? ' (' . $prodiInfo['fakultas'] . ')' : ''))
    : 'Belum ditentukan';
?>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="mb-0">Sekolah Kerja Sama</h5>
        <small class="text-muted">Upload Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP per prodi untuk sekolah mitra.</small>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1">Program Studi</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($prodiLabel, ENT_QUOTES, 'UTF-8') ?>" readonly>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold mb-1">Program Aktif</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($activeProgram ? ($activeProgram['nama'] . ' (' . $activeProgram['tahun_ajaran'] . ')') : 'Belum ada program aktif', ENT_QUOTES, 'UTF-8') ?>" readonly>
            </div>
            <div class="col-md-4 d-grid d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mouUploadModal">
                    <i class="bi bi-upload me-1"></i>Tambah Sekolah
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mouUploadModal" tabindex="-1" aria-labelledby="mouUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mouUploadModalLabel">Upload Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kaprodi/sekolah/store') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-1">Program Studi</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($prodiLabel, ENT_QUOTES, 'UTF-8') ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-1">Program Aktif</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($activeProgram ? ($activeProgram['nama'] . ' (' . $activeProgram['tahun_ajaran'] . ')') : 'Belum ada program aktif', ENT_QUOTES, 'UTF-8') ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label for="programSekolahSelect" class="form-label fw-semibold mb-1">Sekolah Mitra</label>
                            <select class="form-select" id="programSekolahSelect" name="program_sekolah_id" required>
                                <option value="">Pilih sekolah mitra...</option>
                                <?php foreach ($programSekolahOptions as $option): ?>
                                    <option value="<?= (int) $option['id'] ?>">
                                        <?= htmlspecialchars($option['nama'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($programSekolahOptions)): ?>
                                <small class="text-muted">Belum ada sekolah terdaftar pada program aktif.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label for="suratMouInput" class="form-label fw-semibold mb-1">Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP (PDF)</label>
                            <input type="file" class="form-control" id="suratMouInput" name="surat_mou" accept=".pdf,application/pdf" required>
                            <small class="text-muted">Maksimum 1 MB, format PDF.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Daftar Sekolah</span>
        <small class="text-muted">Status awal otomatis unverified.</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Nama Sekolah</th>
                        <th>Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada Surat Keterangan Kerja Sama / Surat Pernyataan Mitra PLP yang diupload.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <?php
                            $status = strtolower((string) ($row['status'] ?? ''));
                            $badgeClass = 'secondary';
                            if ($status === 'verified') {
                                $badgeClass = 'success';
                            } elseif ($status === 'rejected') {
                                $badgeClass = 'danger';
                            } elseif ($status === 'unverified') {
                                $badgeClass = 'warning';
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_sekolah'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php if (!empty($row['surat_mou'])): ?>
                                        <a href="<?= base_url($row['surat_mou']) ?>" target="_blank" rel="noopener">
                                            Lihat Surat
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $badgeClass ?>">
                                        <?= $status !== '' ? htmlspecialchars($status, ENT_QUOTES, 'UTF-8') : '-' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function () {
        const $select = $('#programSekolahSelect');
        if ($select.length) {
            $select.select2({
                width: '100%',
                dropdownParent: $('#mouUploadModal')
            });
        }
    });
</script>
