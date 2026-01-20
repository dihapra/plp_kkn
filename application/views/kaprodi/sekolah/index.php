<?php
$programSekolahOptions = $programSekolahOptions ?? [];
$rows = $rows ?? [];
$prodiInfo = $prodiInfo ?? null;
$activeProgram = $activeProgram ?? null;

$prodiLabel = $prodiInfo
    ? trim($prodiInfo['nama'] . (!empty($prodiInfo['fakultas']) ? ' (' . $prodiInfo['fakultas'] . ')' : ''))
    : 'Belum ditentukan';

$assignedProgramSekolahIds = array_map('intval', array_column($rows, 'program_sekolah_id'));
$blockedProgramSekolahIds = $blockedProgramSekolahIds ?? [];
$excludedProgramSekolahIds = array_values(array_unique(array_merge($assignedProgramSekolahIds, $blockedProgramSekolahIds)));
$availableProgramSekolahOptions = array_values(array_filter($programSekolahOptions, function ($option) use ($excludedProgramSekolahIds) {
    return !in_array((int) $option['id'], $excludedProgramSekolahIds, true);
}));
?>

<div class="card shadow-sm mt-4">
    <div class="card-header">
        <h5 class="mb-0">Sekolah Kerja Sama</h5>
        <small class="text-muted">Tambahkan sekolah mitra yang akan menjadi lokasi pelaksanaan PLP I.</small>
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
                    <i class="bi bi-plus-circle me-1"></i>Tambah Sekolah
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mouUploadModal" tabindex="-1" aria-labelledby="mouUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mouUploadModalLabel">Tambah Sekolah Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kaprodi/sekolah/store') ?>" method="post">
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
                            <select class="form-select" id="programSekolahSelect" name="program_sekolah_id[]" multiple required>
                                <option value="">Pilih sekolah mitra...</option>
                                <?php foreach ($availableProgramSekolahOptions as $option): ?>
                                    <option value="<?= (int) $option['id'] ?>">
                                        <?= htmlspecialchars($option['nama'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (empty($availableProgramSekolahOptions)): ?>
                                <small class="text-muted">Semua sekolah mitra untuk prodi ini sudah terdaftar.</small>
                            <?php endif; ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-2">Pernyataan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="agreement" id="agreementConfirm" value="yes" required>
                                <label class="form-check-label" for="agreementConfirm">
                                    Saya, Ketua Program Studi telah melakukan koordinasi dengan pihak sekolah mitra terkait pelaksanaan Pengenalan Lapangan Persekolahan (PLP) I Tahun 2026, termasuk penambahan sekolah mitra sebagai lokasi pelaksanaan kegiatan.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Daftar Sekolah</span>
        <small class="text-muted">Status otomatis verified.</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead>
                    <tr>
                        <th>Nama Sekolah</th>
                        <th>Surat Pernyataan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada sekolah mitra yang ditambahkan.</td>
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
                                    <span class="text-muted">Pernyataan disetujui</span>
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
                dropdownParent: $('#mouUploadModal'),
                placeholder: 'Pilih sekolah mitra...',
                closeOnSelect: false
            });
        }
    });
</script>
