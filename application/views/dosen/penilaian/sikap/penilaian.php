<?php

/**
 * View: application/views/dosen/penilaian/intra/index.php (adapted for sikap)
 * Prasyarat dari controller:
 *  - $penilaian_sikap : array indikator (lihat AspekPenilaian->penilaian_sikap)
 *  - $student : object student (minimal: ->id, ->name)
 * Opsional:
 *  - $scores : array ['indikator_key' => 1|2|3|4] untuk mode edit (prefill)
 *  - $feedback : string
 */

if (!function_exists('badge_status')) {
    function badge_status($s)
    {
        $s = strtolower(trim((string)$s));

        switch ($s) {
            case 'sedang dinilai':
                return '<span class="badge bg-secondary">Sedang Dinilai</span>';
            case 'revisi':
                return '<span class="badge bg-warning text-dark">Perlu Revisi</span>';
            case 'sudah perbaikan':
                return '<span class="badge bg-primary">Sudah Perbaikan</span>';
            case 'sudah dinilai':
                return '<span class="badge bg-success">Sudah Dinilai</span>';
            default:
                return '<span class="badge bg-light text-muted">-</span>';
        }
    }
}


// Persiapan
$sikap    = $penilaian_sikap ?? [];
$scores   = $scores ?? [];          // opsional untuk prefill
$feedback = $feedback ?? '';        // opsional

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
        <h2 class="mb-0">Penilaian Sikap Mahasiswa</h2>
        <a href="<?= base_url('dosen/penilaian/sikap') ?>" class="btn btn-outline-secondary">← Kembali</a>
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

    <!-- Form Penilaian -->
    <form id="penilaianForm" class="mb-5">
        <input type="hidden" name="student_id" value="<?= (int)($student->id ?? 0) ?>">

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
                            // Prefill jika ada (mode edit)
                            $selected = $scores[$key] ?? null;
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
            <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
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

        // Submit
        document.getElementById('penilaianForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);

            try {
                const resp = await fetch("<?= base_url('dosen/nilai-intra-ekstra-sikap/save/3') ?>", {
                    method: 'POST',
                    body: fd
                });
                const json = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    if (window.Swal) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: json.message || 'Penilaian disimpan.'
                        });
                        location.href = "<?= base_url('dosen/penilaian/sikap') ?>";
                    } else {
                        alert(json.message || 'Penilaian disimpan.');
                        location.href = "<?= base_url('dosen/penilaian/sikap') ?>";
                    }
                } else {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: json?.message || 'Terjadi kesalahan saat menyimpan.'
                        });
                    } else {
                        alert(json?.message || 'Terjadi kesalahan saat menyimpan.');
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