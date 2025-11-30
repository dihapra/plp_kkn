<?php
// ===== Helper badge status =====
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
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="mb-0">Penilaian Modul Projek</h2>
        <a href="<?= base_url('dosen/tugas/modul-projek') ?>" class="btn btn-outline-secondary">← Kembali</a>
    </div>

    <?php if (!empty($detail_laporan->scored_by_me) && (int)$detail_laporan->scored_by_me === 1): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Sudah Dinilai",
                    text: "Anda sudah memberi nilai untuk submisi ini.",
                    icon: "info",
                    confirmButtonText: "Kembali"
                }).then(() => {
                    window.location.href = "<?= base_url('dosen/tugas/modul-projek') ?>";
                });
            });
        </script>
    <?php endif; ?>

    <!-- Info Modul Ajar (individual) -->
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
        <h5>Periksa Modul & Kirim Revisi</h5>
        <p class="text-muted">Jika perlu revisi, tuliskan catatan di bawah. Jika tidak, lanjut ke penilaian.</p>
        <textarea id="catatanRevisi" class="form-control mb-3" rows="3" placeholder="Tulis catatan revisi di sini..."></textarea>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-danger" id="kirimRevisiBtn">Kirim Revisi</button>
            <button type="button" class="btn btn-success" id="lanjutPenilaianBtn">Lanjut ke Penilaian</button>
        </div>
    </div>

    <!-- STEP 2: Penilaian (INDIVIDUAL) -->
    <form id="penilaianForm" class="mb-5" style="display:none">
        <input type="hidden" name="submission_id" value="<?= (int)($detail_laporan->submission_id ?? 0) ?>">

        <!-- A. Penilaian Modul Ajar -->
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
                            <th style="min-width:220px">4 (Sangat Baik)</th>
                            <th style="min-width:220px">3 (Baik)</th>
                            <th style="min-width:220px">2 (Cukup)</th>
                            <th style="min-width:220px">1 (Kurang)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($aspek_modul as $aspek): ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= htmlspecialchars($aspek['label']) ?></td>
                                <?php foreach ($aspek['criteria'] as $c): ?>
                                    <td><small><?= htmlspecialchars($c) ?></small></td>
                                <?php endforeach; ?>
                                <td class="text-center">
                                    <select style="width: 100px;" class="form-select nilai-modul"
                                        name="nilai_laporan[<?= htmlspecialchars($aspek['key']) ?>][<?= (int)($detail_laporan->student_id ?? 0) ?>]">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
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

        const submissionId = <?= (int)($detail_laporan->submission_id ?? 0) ?>;
        const denom = <?= (int)max(1, count($aspek_modul) * 4) ?>;

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
                text: 'Pastikan catatan revisi (jika ada) sudah dikirim.',
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
                text: 'Submisi akan dikembalikan ke mahasiswa.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Kirim Revisi'
            });
            if (!ok.isConfirmed) return;

            try {
                const resp = await fetch("<?= base_url('dosen/submisi/revisi/') ?>" + submissionId, {
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
                    location.href = "<?= base_url('dosen/tugas/modul-projek') ?>";
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

        // ===== Hitung total & nilai (tanpa multi-student) =====
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
                const resp = await fetch("<?= base_url('dosen/ajar/save/5') ?>", {
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
                    location.href = "<?= base_url('dosen/tugas/modul-projek') ?>";
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