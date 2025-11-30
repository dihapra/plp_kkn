<?php
// ====== Persiapan anggota (id <-> nama) ======
$anggota_ids   = array_filter(array_map('trim', explode(',', (string)($detail_laporan->member_ids ?? ''))));
$anggota_names = array_map('trim', explode('<br>', (string)($detail_laporan->members ?? '')));

// sinkronisasi jumlah (jaga-jaga)
$len = min(count($anggota_ids), count($anggota_names));
$anggota_ids   = array_slice($anggota_ids, 0, $len);
$anggota_names = array_slice($anggota_names, 0, $len);

// ====== Map nilai lama: [indicator_key][student_id] => score ======
$scoreLapMap = [];
foreach (($score_laporan ?? []) as $r) {
    if (isset($r['indicator'], $r['student_id'], $r['score'])) {
        $scoreLapMap[$r['indicator']][(string)$r['student_id']] = (int)$r['score'];
    }
}
$scorePresMap = [];
foreach (($score_presentasi ?? []) as $r) {
    if (isset($r['indicator'], $r['student_id'], $r['score'])) {
        $scorePresMap[$r['indicator']][(string)$r['student_id']] = (int)$r['score'];
    }
}

// label status
function badge_status($s)
{
    $s = strtolower(trim((string)$s));
    if ($s === 'sedang dinilai') return '<span class="badge bg-secondary">Sedang Dinilai</span>';
    if ($s === 'revisi') return '<span class="badge bg-warning text-dark">Perlu Revisi</span>';
    if ($s === 'sudah perbaikan') return '<span class="badge bg-primary">Sudah Perbaikan</span>';
    if ($s === 'sudah dinilai') return '<span class="badge bg-success">Sudah Dinilai</span>';
    return '<span class="badge bg-danger">Belum Mengerjakan</span>';
}
?>
<style>
    .table thead th {
        vertical-align: middle
    }

    .badge {
        min-width: 110px
    }

    .meta-list .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center
    }

    .btn-back {
        min-width: 140px
    }
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Lihat Penilaian Laporan Kemajuan</h2>
        <a href="<?= base_url('dosen/tugas/laporan-kemajuan') ?>" class="btn btn-outline-secondary btn-back">
            ← Kembali
        </a>
    </div>

    <!-- Info Laporan -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Laporan</h5>
        <ul class="list-group meta-list">
            <li class="list-group-item">
                <strong>Nama Kelompok</strong>
                <span><?= htmlspecialchars($detail_laporan->group_name ?? '-') ?></span>
            </li>
            <li class="list-group-item">
                <strong>Anggota</strong>
                <span>
                    <?php foreach ($anggota_names as $nm): ?>
                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($nm) ?></span>
                    <?php endforeach; ?>
                </span>
            </li>
            <li class="list-group-item">
                <strong>Status</strong>
                <span><?= badge_status($detail_laporan->status ?? '') ?></span>
            </li>
        </ul>
    </div>

    <!-- Preview PDF -->
    <div class="mb-4">
        <h5 class="mb-2">Dokumen</h5>
        <iframe src="<?= base_url($detail_laporan->file ?? '') ?>" style="width:100%;height:450px;border:0"></iframe>
    </div>

    <?php
    // Inisialisasi total untuk perhitungan server-side (tanpa JS)
    $totalsLap  = array_fill_keys(array_map('intval', $anggota_ids), 0);
    $totalsPres = array_fill_keys(array_map('intval', $anggota_ids), 0);
    ?>

    <!-- A. Penilaian Laporan -->
    <div class="mb-4">
        <h5 class="mb-2">A. Penilaian Laporan Kemajuan</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2" style="min-width:240px; max-width: 300px;">Aspek Penilaian</th>
                        <th colspan="4" class="text-center">Kriteria Penilaian</th>
                        <th colspan="<?= count($anggota_names) ?>" class="text-center">Nilai (1–4)</th>
                    </tr>
                    <tr>
                        <th style="min-width:220px">4 (Sangat Baik)</th>
                        <th style="min-width:220px">3 (Baik)</th>
                        <th style="min-width:220px">2 (Cukup)</th>
                        <th style="min-width:220px">1 (Kurang)</th>
                        <?php foreach ($anggota_names as $nm): ?>
                            <th style="min-width:110px" class="text-center"><?= htmlspecialchars($nm) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($aspek_laporan as $aspek): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($aspek['label']) ?></td>
                            <?php foreach ($aspek['criteria'] as $c): ?>
                                <td><small><?= htmlspecialchars($c) ?></small></td>
                            <?php endforeach; ?>
                            <?php foreach ($anggota_ids as $sid):
                                $sidStr = (string)(int)$sid;
                                $score  = $scoreLapMap[$aspek['key']][$sidStr] ?? null;
                                if (is_numeric($score)) {
                                    $totalsLap[(int)$sid] += (int)$score;
                                }
                            ?>
                                <td class="text-center"><?= is_numeric($score) ? (int)$score : '–' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total Skor</th>
                        <?php foreach ($anggota_ids as $sid): ?>
                            <th class="text-center"><?= (int)$totalsLap[(int)$sid] ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php
                        $denLap = max(1, count($aspek_laporan) * 4);
                        ?>
                        <th colspan="6" class="text-end">Nilai (Skor / <?= $denLap ?> × 100)</th>
                        <?php foreach ($anggota_ids as $sid):
                            $percent = round(($totalsLap[(int)$sid] / $denLap) * 100);
                        ?>
                            <th class="text-center"><?= (int)$percent ?></th>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- B. Penilaian Presentasi -->
    <div class="mb-4">
        <h5 class="mb-2">B. Penilaian Presentasi</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2" style="min-width:240px; max-width: 300px;">Aspek Penilaian</th>
                        <th colspan="4" class="text-center">Kriteria Penilaian</th>
                        <th colspan="<?= count($anggota_names) ?>" class="text-center">Nilai (1–4)</th>
                    </tr>
                    <tr>
                        <th style="min-width:220px">4 (Sangat Baik)</th>
                        <th style="min-width:220px">3 (Baik)</th>
                        <th style="min-width:220px">2 (Cukup)</th>
                        <th style="min-width:220px">1 (Kurang)</th>
                        <?php foreach ($anggota_names as $nm): ?>
                            <th style="min-width:110px" class="text-center"><?= htmlspecialchars($nm) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($aspek_presentasi as $aspek): ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= htmlspecialchars($aspek['label']) ?></td>
                            <?php foreach ($aspek['criteria'] as $c): ?>
                                <td><small><?= htmlspecialchars($c) ?></small></td>
                            <?php endforeach; ?>
                            <?php foreach ($anggota_ids as $sid):
                                $sidStr = (string)(int)$sid;
                                $score  = $scorePresMap[$aspek['key']][$sidStr] ?? null;
                                if (is_numeric($score)) {
                                    $totalsPres[(int)$sid] += (int)$score;
                                }
                            ?>
                                <td class="text-center"><?= is_numeric($score) ? (int)$score : '–' ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php $no++;
                    endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total Skor</th>
                        <?php foreach ($anggota_ids as $sid): ?>
                            <th class="text-center"><?= (int)$totalsPres[(int)$sid] ?></th>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <?php
                        $denPres = max(1, count($aspek_presentasi) * 4);
                        ?>
                        <th colspan="6" class="text-end">Nilai (Skor / <?= $denPres ?> × 100)</th>
                        <?php foreach ($anggota_ids as $sid):
                            $percent = round(($totalsPres[(int)$sid] / $denPres) * 100);
                        ?>
                            <th class="text-center"><?= (int)$percent ?></th>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Feedback -->
    <div class="mb-4">
        <h5 class="mb-2">Feedback / Komentar</h5>
        <div class="card">
            <div class="card-body">
                <?= nl2br(htmlspecialchars($detail_laporan->feedback ?? '-')) ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-5">
        <a href="<?= base_url('dosen/tugas/laporan-kemajuan') ?>" class="btn btn-outline-secondary btn-back">
            ← Kembali
        </a>
    </div>
</div>