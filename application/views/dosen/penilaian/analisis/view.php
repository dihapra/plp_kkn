<?php

// --- Ambil grup aspek
$groups = $penilaian_analisis_mahasiswa ?? [];

// --- Petakan skor
$score_map = [];
if (!empty($scores)) {
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

// --- Flatten ke baris
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
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Lihat Penilaian Analisis Mahasiswa</h2>
        <a href="<?= base_url('dosen/penilaian/analisis') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <!-- Info Mahasiswa -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Mahasiswa</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Nama Mahasiswa:</strong> <?= htmlspecialchars($student->name ?? '-') ?></li>
            <li class="list-group-item"><strong>NIM / ID:</strong> <?= (int)($student->id ?? 0) ?></li>
        </ul>
    </div>

    <div class="mb-4">
        <h5 class="mb-2">Hasil Penilaian</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">No</th>
                        <th style="min-width:220px; max-width: 250px;">Aspek</th>
                        <th style="min-width:280px; max-width: 300px;">Indikator</th>
                        <th class="text-center" style="width:120px">Skor (1–4)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    $total_skor = 0;
                    foreach ($rows as $r):
                        $key      = $r['key'] ?: ('indikator_' . $no);
                        $aspek    = $r['aspek'];
                        $label    = $r['label'] ?: ('Indikator ' . $no);
                        $selected = $score_map[$key] ?? null;
                        if (is_numeric($selected)) $total_skor += $selected;
                    ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($aspek) ?></td>
                            <td><?= htmlspecialchars($label) ?></td>
                            <td class="text-center">
                                <?= $selected ?? '-' ?>
                            </td>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total Skor</th>
                        <th class="text-center"><?= $total_skor ?></th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Nilai Akhir</th>
                        <th class="text-center"><?= $denom > 0 ? round(($total_skor / $denom) * 100, 2) : 0 ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>