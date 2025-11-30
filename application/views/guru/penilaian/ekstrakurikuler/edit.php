<?php

/**
 * View: application/views/guru/penilaian/ekstra/index.php
 * Diperlukan dari controller:
 *  - $penilaian_asistensi_kokurikuler (atau $penilaian_asistensi_ekstrakurikuler):
 *    [
 *      ['aspek'=>'Nama Aspek', 'indikator'=>[
 *          ['key'=>'...', 'label'=>'...', 'criteria'=>[c4, c3, c2, c1]],
 *          ...
 *      ]],
 *      ...
 *    ]
 *  - $student : object (->id, ->name)
 * Opsional:
 *  - $scores  : boleh map ['key'=>nilai] ATAU result DB (rows {indicator, score})
 */

// --- Ambil grup aspek (dukung 2 nama variabel)
$groups = $penilaian_asistensi_kokurikuler
    ?? $penilaian_asistensi_ekstrakurikuler
    ?? [];

// --- Petakan skor (dukung map atau result DB)
$score_map = [];
if (!empty($scores)) {
    if (is_array($scores) && count($scores) && is_numeric(reset($scores))) {
        // Sudah map ['key'=>score]
        foreach ($scores as $k => $v) $score_map[$k] = (int)$v;
    } else {
        // Hasil query DB (object/assoc) {indicator, score}
        foreach ($scores as $row) {
            if (is_object($row)) {
                $ind = $row->indicator ?? null;
                $val = isset($row->score) ? (int)$row->score : null;
            } else {
                $ind = $row['indicator'] ?? null;
                $val = isset($row['score']) ? (int)$row['score'] : null;
            }
            if ($ind !== null) $score_map[$ind] = $val;
        }
    }
}

// --- Flatten ke baris: tiap indikator = 1 baris (dengan kolom Aspek)
$rows = [];
if (is_array($groups)) {
    foreach ($groups as $g) {
        $aspek = $g['aspek'] ?? '';
        if (!empty($g['indikator']) && is_array($g['indikator'])) {
            foreach ($g['indikator'] as $it) {
                $rows[] = [
                    'aspek'   => (string)$aspek,
                    'key'     => (string)($it['key'] ?? ''),
                    'label'   => (string)($it['label'] ?? ''),
                    'c4'      => (string)($it['criteria'][0] ?? ''),
                    'c3'      => (string)($it['criteria'][1] ?? ''),
                    'c2'      => (string)($it['criteria'][2] ?? ''),
                    'c1'      => (string)($it['criteria'][3] ?? ''),
                ];
            }
        }
    }
}

$total_items = count($rows);
$denom = max(1, $total_items * 4);
?>
<style>
    .table thead th {
        vertical-align: middle;
    }

    .table td small {
        display: block;
        line-height: 1.2;
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 12px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 8px;
        justify-content: flex-end
    }
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Edit Penilaian Ekstrakurikuler</h2>
        <a href="<?= base_url('guru/penilaian_ekstrakurikuler') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <!-- Info Mahasiswa -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Mahasiswa</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Nama Mahasiswa:</strong> <?= htmlspecialchars($student->name ?? '-') ?></li>
            <li class="list-group-item"><strong>NIM / ID:</strong> <?= (int)($student->id ?? 0) ?></li>
        </ul>
    </div>

    <!-- Form Penilaian -->
    <form id="penilaianForm" class="mb-5">
        <input type="hidden" name="student_id" value="<?= (int)($student->id ?? 0) ?>">
        <input type="hidden" name="type" value="2"> <!-- Type 2 for Ekstrakurikuler -->

        <div class="mb-4">
            <h5 class="mb-2">Rubrik Penilaian</h5>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:60px">No</th>
                            <th style="min-width:220px; max-width: 250px;">Aspek</th>
                            <th style="min-width:280px; max-width: 300px;">Indikator</th>
                            <th colspan="4" class="text-center">Kriteria Penilaian</th>
                            <th class="text-center" style="width:120px">Skor (1–4)</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="min-width:220px">4 (Sangat Baik)</th>
                            <th style="min-width:220px">3 (Baik)</th>
                            <th style="min-width:220px">2 (Cukup)</th>
                            <th style="min-width:220px">1 (Kurang)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($rows as $r):
                            $key      = $r['key'] ?: ('indikator_' . $no);
                            $aspek    = $r['aspek'];
                            $label    = $r['label'] ?: ('Indikator ' . $no);
                            $c4 = $r['c4'];
                            $c3 = $r['c3'];
                            $c2 = $r['c2'];
                            $c1 = $r['c1'];
                            $selected = $score_map[$key] ?? null;
                        ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($aspek) ?></td>
                                <td><?= htmlspecialchars($label) ?></td>
                                <td><small><?= htmlspecialchars($c4) ?></small></td>
                                <td><small><?= htmlspecialchars($c3) ?></small></td>
                                <td><small><?= htmlspecialchars($c2) ?></small></td>
                                <td><small><?= htmlspecialchars($c1) ?></small></td>
                                <td class="text-center">
                                    <select class="form-select nilai-item" style="width:100px"
                                        name="nilai[<?= htmlspecialchars($key) ?>]">
                                        <?php for ($i = 1; $i <= 4; $i++): ?>
                                            <option value="<?= $i ?>" <?= ($selected !== null && (int)$selected === $i ? 'selected' : '') ?>>
                                                <?= $i ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php $no++;
                        endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-end">Total Skor</th>
                            <th class="text-center" id="total-skor">0</th>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-end">Nilai (Skor / <?= $denom ?> × 100)</th>
                            <th class="text-center" id="nilai-akhir">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="sticky-actions">
            <button type="submit" class="btn btn-primary">Update Penilaian</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const denom = <?= (int)$denom ?>;
        const selects = document.querySelectorAll('.nilai-item');
        const elTotal = document.getElementById('total-skor');
        const elNilai = document.getElementById('nilai-akhir');

        function recalc() {
            let t = 0;
            selects.forEach(s => {
                const v = parseInt(s.value || '0', 10);
                if (!isNaN(v)) t += v;
            });
            elTotal.textContent = t;
            elNilai.textContent = denom ? Math.round((t / denom) * 100) : 0;
        }
        selects.forEach(s => s.addEventListener('change', recalc));
        recalc();

        // Simpan -> type=2 (ekstrakurikuler/kokurikuler)
        document.getElementById('penilaianForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);
            try {
                const resp = await fetch("<?= base_url('guru/insert_nilai_extra_intra_sikap') ?>", {
                    method: 'POST',
                    body: fd
                });
                const json = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    if (window.Swal) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: json.message || 'Penilaian diperbarui.'
                        });
                        location.href = "<?= base_url('guru/penilaian_ekstrakurikuler') ?>";
                    } else {
                        alert(json.message || 'Penilaian diperbarui.');
                        location.href = "<?= base_url('guru/penilaian_ekstrakurikuler') ?>";
                    }
                } else {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: json?.message || 'Terjadi kesalahan.'
                        });
                    } else {
                        alert(json?.message || 'Terjadi kesalahan.');
                    }
                }
            } catch (err) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops',
                        text: err.message
                    });
                } else {
                    alert(err.message);
                }
            }
        });
    });
</script>