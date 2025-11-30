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
    // pastikan formatnya benar
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
    .step-circle {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 14px;
        color: #6c757d
    }

    .step-circle .circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #dee2e6;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        margin-bottom: 5px
    }

    .step-circle.active .circle {
        background: #0d6efd;
        color: #fff
    }

    .step-circle.active .label {
        font-weight: 700;
        color: #0d6efd
    }

    .table thead th {
        vertical-align: middle
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
    <h2 class="mb-3">Penilaian Laporan Kemajuan</h2>

    <?php if (!empty($detail_laporan->scored_by_me) && (int)$detail_laporan->scored_by_me === 1): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Sudah Dinilai",
                    text: "Anda sudah memberi nilai untuk submisi ini.",
                    icon: "info",
                    confirmButtonText: "Kembali"
                }).then(() => {
                    window.location.href = "<?= base_url('dosen/tugas/laporan-kemajuan') ?>";
                });
            });
        </script>
    <?php endif; ?>

    <!-- Info Laporan -->
    <div class="mb-3">
        <h5 class="mb-2">Informasi Laporan</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Nama Kelompok:</strong> <?= htmlspecialchars($detail_laporan->group_name ?? '-') ?></li>
            <li class="list-group-item"><strong>Anggota:</strong>
                <?php foreach ($anggota_names as $nm): ?>
                    <span class="badge bg-secondary me-1"><?= htmlspecialchars($nm) ?></span>
                <?php endforeach; ?>
            </li>
            <li class="list-group-item">
                <strong>Status:</strong> <?= badge_status($detail_laporan->status ?? '') ?>
            </li>
        </ul>
    </div>

    <!-- Preview PDF -->
    <div class="mb-4">
        <h5 class="mb-2">Preview Dokumen</h5>
        <iframe src="<?= base_url($detail_laporan->file ?? '') ?>" style="width:100%;height:450px;border:0"></iframe>
    </div>

    <!-- Step Indicator -->
    <div class="d-flex justify-content-center mb-3 gap-4">
        <div id="circleStep1" class="step-circle active">
            <div class="circle">1</div>
            <div class="label">Revisi</div>
        </div>
        <div id="circleStep2" class="step-circle">
            <div class="circle">2</div>
            <div class="label">Penilaian</div>
        </div>
    </div>

    <!-- STEP 1: Revisi -->
    <div id="stepRevisi" class="mb-4">
        <h5>Periksa Laporan & Kirim Revisi</h5>
        <p class="text-muted">Jika laporan perlu direvisi, tuliskan catatan di bawah ini. Jika tidak, lanjut ke penilaian.</p>
        <textarea id="catatanRevisi" class="form-control mb-3" rows="3" placeholder="Tulis catatan revisi di sini..."></textarea>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-danger" id="kirimRevisiBtn">Kirim Revisi</button>
            <button type="button" class="btn btn-success" id="lanjutPenilaianBtn">Lanjut ke Penilaian</button>
        </div>
    </div>

    <!-- STEP 2: Penilaian -->
    <form id="penilaianForm" class="mb-5" style="display:none">
        <input type="hidden" name="submission_id" value="<?= (int)($detail_laporan->submission_id ?? 0) ?>">

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
                                <?php foreach ($anggota_ids as $sid): ?>
                                    <td>
                                        <select class="form-select nilai-laporan" name="nilai_laporan[<?= htmlspecialchars($aspek['key']) ?>][<?= (int)$sid ?>]" data-student-id="<?= (int)$sid ?>">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php $no++;
                        endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Total Skor</th>
                            <?php foreach ($anggota_ids as $sid): ?>
                                <th class="text-center" id="total-laporan-<?= (int)$sid ?>">0</th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Nilai (Skor / <?= count($aspek_laporan) * 4 ?> × 100)</th>
                            <?php foreach ($anggota_ids as $sid): ?>
                                <th class="text-center" id="nilai-laporan-<?= (int)$sid ?>">0</th>
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
                                <?php foreach ($anggota_ids as $sid): ?>
                                    <td>
                                        <select class="form-select nilai-presentasi" name="nilai_presentasi[<?= htmlspecialchars($aspek['key']) ?>][<?= (int)$sid ?>]" data-student-id="<?= (int)$sid ?>">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php $no++;
                        endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Total Skor</th>
                            <?php foreach ($anggota_ids as $sid): ?>
                                <th class="text-center" id="total-presentasi-<?= (int)$sid ?>">0</th>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-end">Nilai (Skor / <?= count($aspek_presentasi) * 4 ?> × 100)</th>
                            <?php foreach ($anggota_ids as $sid): ?>
                                <th class="text-center" id="nilai-presentasi-<?= (int)$sid ?>">0</th>
                            <?php endforeach; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Feedback -->
        <div class="mb-3">
            <h5 class="mb-2">Feedback / Komentar</h5>
            <textarea class="form-control" name="feedback" id="feedback" rows="3" placeholder="Masukkan komentar atau feedback…"></textarea>
        </div>

        <div class="sticky-actions">
            <button type="submit" class="btn btn-primary" id="btnSimpanPenilaian">Simpan Penilaian</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const stepRevisi = document.getElementById('stepRevisi');
        const formNilai = document.getElementById('penilaianForm');
        const circle1 = document.getElementById('circleStep1');
        const circle2 = document.getElementById('circleStep2');
        const btnNext = document.getElementById('lanjutPenilaianBtn');
        const btnRevisi = document.getElementById('kirimRevisiBtn');

        function setStep(n) {
            if (n === 1) {
                circle1.classList.add('active');
                circle2.classList.remove('active');
            } else {
                circle1.classList.remove('active');
                circle2.classList.add('active');
            }
        }

        btnNext?.addEventListener('click', async () => {
            const q = await Swal.fire({
                title: 'Lanjut ke Penilaian?',
                text: 'Pastikan tidak ada revisi terlebih dahulu.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjut'
            });
            if (!q.isConfirmed) return;
            stepRevisi.style.display = 'none';
            formNilai.style.display = 'block';
            setStep(2);
            window.scrollTo({
                top: formNilai.offsetTop - 60,
                behavior: 'smooth'
            });
        });

        btnRevisi?.addEventListener('click', async () => {
            const catatan = (document.getElementById('catatanRevisi').value || '').trim();
            if (!catatan) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Catatan kosong',
                    text: 'Isi catatan revisi terlebih dahulu.'
                });
            }
            const ok = await Swal.fire({
                title: 'Kirim Revisi?',
                text: 'Laporan akan dikembalikan ke mahasiswa.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Kirim Revisi'
            });
            if (!ok.isConfirmed) return;

            try {
                const resp = await fetch("<?= base_url('dosen/submisi/revisi/' . $detail_laporan->submission_id) ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        content_revision: catatan
                    })
                });
                const json = await resp.json();
                if (resp.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Revisi terkirim',
                        text: json.message || 'Berhasil.'
                    });
                    location.href = "<?= base_url('dosen/tugas/laporan-kemajuan') ?>";
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

        // ===== Perhitungan Skor Otomatis =====
        const selectsLaporan = document.querySelectorAll('.nilai-laporan');
        const selectsPresentasi = document.querySelectorAll('.nilai-presentasi');

        function hitung(prefixSelectClass, totalPrefixId, nilaiPrefixId, denom) {
            const totals = {};
            document.querySelectorAll('.' + prefixSelectClass).forEach(sel => {
                const sid = sel.dataset.studentId;
                const v = parseInt(sel.value || '0', 10);
                totals[sid] = (totals[sid] || 0) + (isNaN(v) ? 0 : v);
            });
            Object.keys(totals).forEach(sid => {
                const total = totals[sid] || 0;
                const nilai = denom > 0 ? ((total / denom) * 100).toFixed(2) : '0.00';
                const totalEl = document.getElementById(`${totalPrefixId}-${sid}`);
                const nilaiEl = document.getElementById(`${nilaiPrefixId}-${sid}`);
                if (totalEl) totalEl.textContent = total;
                if (nilaiEl) nilaiEl.textContent = nilai;
            });
        }

        function updateSemua() {
            const denomLaporan = <?= count($aspek_laporan) * 4 ?>;
            const denomPresentasi = <?= count($aspek_presentasi) * 4 ?>;
            hitung('nilai-laporan', 'total-laporan', 'nilai-laporan', denomLaporan);
            hitung('nilai-presentasi', 'total-presentasi', 'nilai-presentasi', denomPresentasi);
        }

        selectsLaporan.forEach(s => s.addEventListener('change', updateSemua));
        selectsPresentasi.forEach(s => s.addEventListener('change', updateSemua));
        updateSemua();

        // ===== Submit Penilaian =====
        document.getElementById('penilaianForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const fd = new FormData(e.target);

            const ok = await Swal.fire({
                title: 'Konfirmasi Penilaian',
                text: 'Pastikan penilaian sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Simpan'
            });
            if (!ok.isConfirmed) return;

            try {
                const resp = await fetch("<?= base_url('dosen/laporan/save/' . $detail_laporan->submission_id) ?>", {
                    method: 'POST',
                    body: fd
                });
                const json = await resp.json().catch(() => ({}));
                if (resp.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: json.message || 'Penilaian disimpan.'
                    });
                    location.href = "<?= base_url('dosen/tugas/laporan-kemajuan') ?>";
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: json?.message || 'Terjadi kesalahan saat menyimpan.'
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