<?php
// Helper status
function badge_status($s)
{
    $s = strtolower(trim((string)$s));
    if ($s === 'sedang dinilai') return '<span class="badge bg-secondary">Sedang Dinilai</span>';
    if ($s === 'revisi') return '<span class="badge bg-warning text-dark">Perlu Revisi</span>';
    if ($s === 'sudah perbaikan') return '<span class="badge bg-primary">Sudah Perbaikan</span>';
    if ($s === 'sudah dinilai') return '<span class="badge bg-success">Sudah Dinilai</span>';
    return '<span class="badge bg-danger">Belum Mengerjakan</span>';
}

// Map score by indicator
// Map score by indicator
$score_map = [];
if (!empty($scores)) {
    foreach ($scores as $row) {
        $score_map[$row['indicator']] = (int) $row['score'];
    }
}
?>
<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Detail Penilaian Modul Ajar</h2>
        <a href="<?= base_url('dosen/tugas/modul-ajar') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <!-- Info -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Submisi</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Mahasiswa:</strong> <?= htmlspecialchars($detail_laporan->student_name ?? '-') ?></li>
            <li class="list-group-item"><strong>Status:</strong> <?= badge_status($detail_laporan->status ?? '') ?></li>
            <li class="list-group-item"><strong>Diunggah:</strong> <?= htmlspecialchars($detail_laporan->created_at ?? '-') ?></li>
        </ul>
    </div>

    <!-- Preview -->
    <div class="mb-4">
        <h5 class="mb-2">Preview Dokumen</h5>
        <iframe src="<?= base_url($detail_laporan->file ?? '') ?>" style="width:100%;height:450px;border:0"></iframe>
    </div>

    <!-- Rubrik -->
    <div class="mb-4">
        <h5 class="mb-2">Rubrik Penilaian</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="min-width:240px; max-width: 300px;">Aspek Penilaian</th>
                        <th colspan="4" class="text-center">Kriteria Penilaian</th>
                        <th class="text-center">Skor (1–4)</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>4 (Sangat Baik)</th>
                        <th>3 (Baik)</th>
                        <th>2 (Cukup)</th>
                        <th>1 (Kurang)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_skor = 0;
                    foreach ($aspek_modul as $aspek):
                        $selected_score = $score_map[$aspek['key']] ?? null;
                        $total_skor += (int)$selected_score;
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($aspek['label']) ?></td>
                            <?php foreach ($aspek['criteria'] as $c): ?>
                                <td><small><?= htmlspecialchars($c) ?></small></td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <select class="form-select" style="width: 100px;" disabled>
                                    <option value="">-</option>
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($selected_score == $i ? 'selected' : '') ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php
                    $denom = max(1, count($aspek_modul) * 4);
                    $nilai = round(($total_skor / $denom) * 100, 2);
                    ?>
                    <tr>
                        <th colspan="6" class="text-end">Total Skor</th>
                        <th class="text-center"><?= $total_skor ?></th>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-end">Nilai (Skor / <?= $denom ?> × 100)</th>
                        <th class="text-center"><?= $nilai ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Tombol Back -->
        <div class="mt-3">
            <a href="<?= base_url('dosen/tugas/modul-ajar') ?>" class="btn btn-outline-secondary">← Kembali</a>
        </div>

    </div>

    <!-- Feedback -->
    <div class="mb-3">
        <h5 class="mb-2">Feedback / Komentar</h5>
        <div class="border rounded p-3 bg-light">
            <?= !empty($detail_laporan->feedback) ? nl2br(htmlspecialchars($detail_laporan->feedback)) : '<em>Tidak ada feedback.</em>' ?>
        </div>
    </div>
</div>