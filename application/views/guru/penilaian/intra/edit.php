<?php

/**
 * View: application/views/guru/penilaian/intra/edit.php
 * Diperlukan:
 *  - $penilaian_asistensi_intrakurikuler : array indikator (AspekPenilaian->penilaian_asistensi_intrakurikuler)
 *  - $student : object student (->id, ->name)
 *  - $scores  : result dari EvaluationCase->get_score_by_student_id(1, $student_id)
 *      dengan kolom minimal: indicator, score
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
        justify-content: flex-end;
    }
</style>

<div class="container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Edit Penilaian Intrakurikuler</h2>
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

    <!-- Form Edit Penilaian -->
    <form id="penilaianForm" class="mb-5"
        action="<?= base_url('guru/insert_nilai_extra_intra_sikap') ?>"
        method="post">
        <input type="hidden" name="student_id" value="<?= (int)($student->id ?? 0) ?>">
        <input type="hidden" name="type" value="1"> <!-- Type 1 for Intrakurikuler -->

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

        // Submit (upsert di endpoint yang sama)
        const form = document.getElementById('penilaianForm');
        if (!form) return;
        const submitBtn = form.querySelector('[type="submit"]');
        const actionUrl = form.getAttribute('action') || "<?= base_url('guru/insert_nilai_extra_intra_sikap') ?>";

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            const fd = new FormData(form);
            if (submitBtn) {
                submitBtn.dataset.originalText = submitBtn.dataset.originalText || submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            }
            try {
                const resp = await fetch(actionUrl, {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const json = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    if (window.Swal) {
                        await Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan',
                            text: json.message || 'Penilaian diperbarui.'
                        });
                        location.href = "<?= base_url('guru/penilaian_intrakurikuler') ?>";
                    } else {
                        alert(json.message || 'Penilaian diperbarui.');
                        location.href = "<?= base_url('guru/penilaian_intrakurikuler') ?>";
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
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = submitBtn.dataset.originalText || 'Update Penilaian';
                }
            }
        });
    });
</script>
