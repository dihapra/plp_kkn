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

// Hitung total & persen di server
$total_skor = 0;
foreach ($rows as $r) {
    $k = $r['key'] ?? null;
    if ($k && isset($score_map[$k]) && is_numeric($score_map[$k])) {
        $v = (int)$score_map[$k];
        if ($v >= 1 && $v <= 4) $total_skor += $v;
    }
}
$persen = round(($total_skor / $denom) * 100, 2);
?>
<style>
    .table thead th {
        vertical-align: middle;
    }

    .table td small {
        display: block;
        line-height: 1.2;
    }
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Penilaian Ekstrakurikuler — Lihat</h2>
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

    <!-- Tabel Penilaian (read-only, select disabled) -->
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
                                <select class="form-select" style="width:100px" disabled>
                                    <?php if ($selected === null): ?>
                                        <option selected>—</option>
                                    <?php endif; ?>
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
                        <th class="text-center"><?= (int)$total_skor ?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-end">Nilai (Skor / <?= $denom ?> × 100)</th>
                        <th class="text-center"><?= number_format($persen, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>