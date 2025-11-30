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
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Edit Penilaian Modul Ajar</h2>
        <a href="<?= base_url('dosen/tugas/modul-ajar') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <!-- Info Modul Ajar -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Submisi</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Mahasiswa:</strong> <?= htmlspecialchars($detail_laporan->student_name ?? '-') ?></li>
            <li class="list-group-item"><strong>Status:</strong> <?= badge_status($detail_laporan->status ?? '') ?></li>
            <li class="list-group-item"><strong>Diunggah:</strong> <?= htmlspecialchars($detail_laporan->created_at ?? '-') ?></li>
        </ul>
    </div>

    <!-- Preview PDF -->
    <div class="mb-4">
        <h5 class="mb-2">Preview Dokumen</h5>
        <iframe src="<?= base_url($detail_laporan->file ?? '') ?>" style="width:100%;height:450px;border:0"></iframe>
    </div>

    <!-- Form Penilaian -->
    <form id="penilaianForm" class="mb-5">
        <input type="hidden" name="submission_id" value="<?= (int)($detail_laporan->submission_id ?? 0) ?>">

        <div class="mb-4">
            <h5 class="mb-2">Skor</h5>
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
                        <?php $no = 1;
                        foreach ($aspek_modul as $aspek):
                            $selected_score = $score_map[$aspek['key']] ?? null;
                        ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($aspek['label']) ?></td>
                                <?php foreach ($aspek['criteria'] as $c): ?>
                                    <td><small><?= htmlspecialchars($c) ?></small></td>
                                <?php endforeach; ?>
                                <td class="text-center">
                                    <select style="width: 100px;" class="form-select nilai-modul"
                                        name="nilai_laporan[<?= htmlspecialchars($aspek['key']) ?>][<?= (int)($detail_laporan->student_id ?? 0) ?>]">
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($selected_score == $i ? 'selected' : '') ?>><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php $no++;
                        endforeach; ?>
                    </tbody>
                    <tfoot>
                        <?php $denom = max(1, count($aspek_modul) * 4); ?>
                        <tr>
                            <th colspan="6" class="text-end">Total Skor</th>
                            <th class="text-center" id="total-modul">0</th>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Nilai (Skor / <?= $denom ?> × 100)</th>
                            <th class="text-center" id="nilai-modul">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Feedback -->
        <div class="mb-3">
            <h5 class="mb-2">Feedback / Komentar</h5>
            <textarea class="form-control" name="feedback" id="feedback" rows="3"><?= htmlspecialchars($detail_laporan->feedback ?? '') ?></textarea>
        </div>

        <div class="sticky-actions">
            <button type="submit" class="btn btn-primary">Update Penilaian</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const denom = <?= (int)max(1, count($aspek_modul) * 4) ?>;

        function recalc() {
            let total = 0;
            document.querySelectorAll('.nilai-modul').forEach(sel => {
                const v = parseInt(sel.value || '0', 10);
                if (!isNaN(v)) total += v;
            });
            const percent = Math.round((total / denom) * 100);
            document.getElementById('total-modul').textContent = total;
            document.getElementById('nilai-modul').textContent = percent;
        }
        document.addEventListener('change', e => {
            if (e.target.matches('.nilai-modul')) recalc();
        });
        recalc();

        // Submit form
        document.getElementById('penilaianForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            try {
                const resp = await fetch("<?= base_url('dosen/ajar/save/3') ?>", {
                    method: 'POST',
                    body: fd
                });
                const json = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: json.message || 'Penilaian diperbarui.'
                    });
                    location.href = "<?= base_url('dosen/tugas/modul-ajar') ?>";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: json?.message || 'Terjadi kesalahan.'
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: e.message
                });
            }
        });
    });
</script>