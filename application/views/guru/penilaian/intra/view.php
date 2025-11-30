<?php

/**
 * View: application/views/guru/penilaian/intra/view.php
 * Diperlukan from controller:
 *  - $penilaian_asistensi_intrakurikuler : array indikator (AspekPenilaian->penilaian_asistensi_intrakurikuler)
 *  - $student : object student (->id, ->name)
 *  - $scores  : result dari EvaluationCase->get_score_by_student_id(1, $student_id)
 */

// Map skor: indicator_key => score (dukung array/object)
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

$intra       = $penilaian_asistensi_intrakurikuler ?? [];

// Manually assign aspects based on the structure in AspekPenilaian.php
$aspect_mapping = [
    'pendahuluan_pembuka_dan_doa' => 'Pendahuluan',
    'pendahuluan_periksa_kehadiran' => 'Pendahuluan',
    'pendahuluan_siapkan_fisik_psikis' => 'Pendahuluan',
    'pendahuluan_kaitkan_pengalaman' => 'Pendahuluan',
    'pendahuluan_pertanyaan_relevan' => 'Pendahuluan',
    'pendahuluan_manfaat_materi' => 'Pendahuluan',
    'pendahuluan_beri_info_materi' => 'Pendahuluan',
    'pendahuluan_beri_tujuan_pembelajaran' => 'Pendahuluan',
    'pendahuluan_jelaskan_langkah' => 'Pendahuluan',
    'inti_partisipasi_diskusi' => 'Kegiatan Inti',
    'inti_penguasaan_materi' => 'Kegiatan Inti',
    'inti_keselarasan_tujuan' => 'Kegiatan Inti',
    'inti_menjawab_pertanyaan' => 'Kegiatan Inti',
    'penutup_meringkas_materi' => 'Penutup',
    'penutup_memberi_tugas' => 'Penutup',
    'penutup_kuis_formatif' => 'Penutup',
    'komunikasi_interaktif' => 'Komunikasi',
    'komunikasi_bahasa_tubuh' => 'Komunikasi',
    'disiplin_kehadiran' => 'Kedisiplinan',
    'disiplin_inisiatif' => 'Kedisiplinan',
];

$total_items = count($intra);
$denom       = max(1, $total_items * 4);

// Hitung total & persen di server
$total_skor = 0;
foreach ($intra as $it) {
    $k = $it['key'] ?? null;
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
        <h2 class="mb-0">Penilaian Intrakurikuler — Lihat</h2>
        <a href="<?= base_url('guru/penilaian_intrakurikuler') ?>" class="btn btn-outline-secondary">← Kembali</a>
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
                        <th style="min-width:220px; max-width: 250px;">Aspek</th> <!-- Added Aspek column -->
                        <th style="min-width:280px; max-width: 300px;">Indikator</th>
                        <th colspan="4" class="text-center">Kriteria Penilaian</th>
                        <th class="text-center" style="width:120px">Skor (1–4)</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th> <!-- Added empty th for Aspek -->
                        <th style="min-width:220px">4 (Sangat Baik)</th>
                        <th style="min-width:220px">3 (Baik)</th>
                        <th style="min-width:220px">2 (Cukup)</th>
                        <th style="min-width:220px">1 (Kurang)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($intra as $it):
                        // Asumsi urutan criteria = [4,3,2,1]
                        $key   = $it['key']   ?? ('indikator_' . $no);
                        $label = $it['label'] ?? ('Indikator ' . $no);
                        $c4    = $it['criteria'][0] ?? '';
                        $c3    = $it['criteria'][1] ?? '';
                        $c2    = $it['criteria'][2] ?? '';
                        $c1    = $it['criteria'][3] ?? '';
                        $selected = $score_map[$key] ?? null;
                        $aspek_name = $aspect_mapping[$key] ?? '-'; // Get aspect name
                    ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($aspek_name) ?></td> <!-- Display Aspek -->
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