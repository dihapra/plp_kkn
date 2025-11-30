<?php

/**
 * View: application/views/guru/penilaian/sikap/view.php
 * Prasyarat dari controller:
 *  - $penilaian_sikap : array indikator (lihat AspekPenilaian->penilaian_sikap)
 *  - $student : object student (minimal: ->id, ->name)
 *  - $scores : array ['indikator_key' => 1|2|3|4]
 *  - $feedback : string (opsional)
 */

// Persiapan
$sikap    = $penilaian_sikap_mahasiswa ?? [];
$scores_from_db = $scores ?? []; // Original scores from DB (array of objects)
$feedback = $feedback ?? '';

// Transform $scores from array of objects to associative array for easy lookup
$scores = [];
if (!empty($scores_from_db) && is_array($scores_from_db)) {
    foreach ($scores_from_db as $s) {
        if (is_object($s) && isset($s->indicator) && isset($s->score)) {
            $scores[$s->indicator] = $s->score;
        }
    }
}

// --- Flatten ke baris: tiap indikator = 1 baris (dengan kolom Aspek)
$rows = [];
if (is_array($sikap)) {
    foreach ($sikap as $g) {
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
// Hitung total skor dan nilai akhir dari data $scores
$total_skor = 0;
if (!empty($scores) && is_array($scores)) {
    foreach ($scores as $skor) {
        $total_skor += (int)$skor;
    }
}
$nilai_akhir = ($denom > 0) ? round(($total_skor / $denom) * 100) : 0;

?>
<style>
    .table thead th {
        vertical-align: middle;
    }

    .table td small {
        display: block;
        line-height: 1.2;
    }

    .score-box {
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Detail Penilaian Sikap Mahasiswa</h2>
        <a href="<?= base_url('guru/penilaian_sikap') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <!-- Info Mahasiswa -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Mahasiswa</h5>
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Nama Mahasiswa:</strong> <?= htmlspecialchars($student->name ?? '-') ?>
            </li>
            <li class="list-group-item">
                <strong>NIM / ID:</strong> <?= (int)($student->id ?? 0) ?>
            </li>
        </ul>
    </div>

    <!-- Tabel Penilaian -->
    <div class="mb-4">
        <h5 class="mb-2">Rubrik dan Hasil Penilaian</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="min-width:220px; max-width: 250px;">Aspek</th>
                        <th style="min-width:280px; max-width: 300px;">Indikator</th>
                        <th colspan="4" class="text-center">Kriteria Penilaian</th>
                        <th class="text-center" style="width:120px">Skor</th>
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
                        $score = $scores[$key] ?? 0;
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
                                <div class="score-box"><?= (int)$score ?></div>
                            </td>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Total Skor</th>
                        <th class="text-center" id="total-skor"><?= $total_skor ?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-end">Nilai (Skor / <?= $denom ?> × 100)</th>
                        <th class="text-center" id="nilai-akhir"><?= $nilai_akhir ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <a href="<?= base_url('guru/sikap_edit/' . ($student->id ?? '')) ?>" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit Nilai
            </a>
        </div>
    </div>
</div>