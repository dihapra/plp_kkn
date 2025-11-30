<!-- ==== CSS ==== -->
<style>
    .task-card {
        margin-bottom: 16px;
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 14px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
    }

    .task-card.revisi {
        border-color: #ffc107;
        background: #fff8e1;
    }

    .task-card h5 {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 0 8px;
    }

    .task-card .hint {
        color: #6b7280;
        font-size: .9rem;
    }

    .preview-wrap {
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        padding: 8px;
        background: #f9fafb;
    }

    .pdf-frame {
        width: 100%;
        height: 460px;
        border: 0;
    }
</style>

<?php
// ====================== DATA TUGAS (type 1..5) ======================
$tugas_kelompok = [
    ['id' => 1, 'judul' => 'Laporan Kemajuan', 'scope' => 'group'],
    ['id' => 2, 'judul' => 'Laporan Akhir', 'scope' => 'group'],
];
$tugas_individu = [
    ['id' => 3, 'judul' => 'Modul Ajar', 'scope' => 'individual'],
    ['id' => 4, 'judul' => 'Bahan Ajar', 'scope' => 'individual'],
    ['id' => 5, 'judul' => 'Modul Projek', 'scope' => 'individual'],
];

$isLeader   = ((int)($student->leader ?? 0) === 1);
$tugas_list = $isLeader ? array_merge($tugas_kelompok, $tugas_individu) : $tugas_individu;

/**
 * Ambil status/feedback/submission_id untuk tiap tugas (individual vs group)
 * $submission: list object submission individu (punya properti: type, status, feedback, revisi_catatan, id)
 * $submission_group: list object submission kelompok (type, status, feedback, revisi_catatan, id)
 */
function getTaskState($task, $submission, $submission_group)
{
    $status = null;
    $feedback = null;
    $revisi_catatan = null;
    $submission_id = null;

    if ($task['scope'] === 'individual') {
        foreach ($submission as $s) {
            if ((int)$s->type === (int)$task['id']) {
                $status = $s->status;
                $feedback = $s->feedback;
                $revisi_catatan = $s->revisi_catatan ?? null;
                $submission_id = $s->id ?? null;
                break;
            }
        }
    } else {
        foreach ($submission_group as $sg) {
            if ((int)$sg->type === (int)$task['id']) {
                $status = $sg->status;
                $feedback = $sg->feedback;
                $revisi_catatan = $sg->revisi_catatan ?? null;
                $submission_id = $sg->id ?? null;
                break;
            }
        }
    }

    // mapping badge bootstrap
    $badgeClass = 'bg-danger';
    $statusText = 'Belum Upload';
    if ($status === 'sedang dinilai') {
        $badgeClass = 'bg-secondary';
        $statusText = 'Sedang Dinilai';
    } elseif ($status === 'sudah dinilai') {
        $badgeClass = 'bg-success';
        $statusText = 'Sudah Dinilai';
    } elseif ($status === 'sudah perbaikan') {
        $badgeClass = 'bg-info';
        $statusText = 'Sudah Perbaikan';
    } elseif ($status === 'revisi') {
        $badgeClass = 'bg-warning text-dark';
        $statusText = 'Revisi';
    }

    return compact('status', 'feedback', 'revisi_catatan', 'submission_id', 'badgeClass', 'statusText');
}
?>

<h1 class="text-center m-4">Tugas Mahasiswa</h1>

<div class="container">
    <?php foreach ($tugas_list as $t):
        $st = getTaskState($t, $submission, $submission_group);
        $cardClass = 'task-card' . (($st['status'] === 'revisi') ? ' revisi' : '');
    ?>
        <div class="<?= $cardClass ?>">
            <h5>
                <span class="task-title">
                    <?= htmlspecialchars($t['judul']) ?>
                    <?php if ($t['scope'] === 'group'): ?>
                        <span class="badge bg-light text-dark ms-2">Kelompok</span>
                    <?php else: ?>
                        <span class="badge bg-light text-dark ms-2">Individu</span>
                    <?php endif; ?>
                </span>
                <span class="badge <?= $st['badgeClass'] ?>"><?= $st['statusText'] ?></span>
            </h5>

            <div class="d-flex align-items-center gap-2 hint">
                <i class="bi bi-file-earmark-pdf"></i>
                <span>Unggah PDF: <strong><?= htmlspecialchars($t['judul']) ?></strong></span>
            </div>

            <div class="mt-2 d-flex align-items-center gap-2">
                <?php if (is_null($st['status']) || $st['status'] === 'revisi'): ?>
                    <button
                        class="btn btn-primary btn-sm btn-upload"
                        data-type="<?= (int)$t['id'] ?>"
                        data-title="<?= htmlspecialchars($t['judul']) ?>"
                        data-submission-id="<?= htmlspecialchars($st['submission_id'] ?? '') ?>">
                        <i class="bi bi-cloud-upload"></i>
                        <?= ($st['status'] === 'revisi' ? 'Upload Ulang' : 'Upload') ?>
                    </button>
                <?php else: ?>
                    <span class="text-muted"><i class="bi bi-lock-fill"></i> Tugas sudah diunggah</span>
                <?php endif; ?>

                <?php if (!empty($st['feedback'])): ?>
                    <button class="btn btn-outline-info btn-sm btn-feedback" data-feedback="<?= htmlspecialchars($st['feedback']) ?>">
                        <i class="bi bi-chat-left-text"></i> Lihat Feedback
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($st['status'] === 'revisi' && !is_null($st['revisi_catatan'])): ?>
                <div class="alert alert-warning mt-2 mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>Catatan Revisi:</strong>
                    <?= nl2br(htmlspecialchars($st['revisi_catatan'])) ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- ============== Modal Upload (preview PDF) ============== -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="uploadModalLabel">
                    <i class="bi bi-cloud-upload"></i> Upload Tugas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="uploadForm">
                    <input type="hidden" id="typeInput" name="type" value="">
                    <input type="hidden" id="submissionIdInput" name="submission_id" value="">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="fileUpload" class="form-label">Pilih Dokumen (.pdf)</label>
                            <input type="file" class="form-control" id="fileUpload" name="file" accept=".pdf,application/pdf">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Maksimal 2 MB. Hanya PDF.
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary" id="submitUploadBtn">
                                    <i class="bi bi-upload"></i> Upload
                                </button>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label d-flex align-items-center gap-2">
                                <i class="bi bi-eye"></i> Preview
                            </label>
                            <div class="preview-wrap">
                                <div id="previewEmpty" class="text-center p-4 text-muted">
                                    <i class="bi bi-file-earmark-pdf" style="font-size:2rem;"></i>
                                    <div class="mt-2">Belum ada file dipilih</div>
                                </div>
                                <iframe id="pdfPreview" class="pdf-frame d-none"></iframe>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============== Modal Feedback ============== -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="feedbackModalLabel">
                    <i class="bi bi-chat-left-text"></i> Feedback Tugas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p id="feedbackContent" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>

<!-- ==== JS ==== -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Asumsi: window.baseUrl sudah didefinisikan global (contoh: const baseUrl = "<?= base_url() ?>";)
        const uploadModal = new bootstrap.Modal(document.getElementById("uploadModal"));
        const uploadForm = document.getElementById("uploadForm");
        const typeInput = document.getElementById("typeInput");
        const submissionInp = document.getElementById("submissionIdInput");
        const fileUpload = document.getElementById("fileUpload");
        const submitBtn = document.getElementById("submitUploadBtn");
        const pdfFrame = document.getElementById("pdfPreview");
        const previewEmpty = document.getElementById("previewEmpty");

        // Buka modal upload
        document.querySelectorAll(".btn-upload").forEach(btn => {
            btn.addEventListener("click", () => {
                const type = btn.dataset.type; // "1".."5"
                const title = btn.dataset.title || 'Upload Tugas';
                const subId = btn.dataset.submissionId || '';

                // simpan type & submission id ke form
                uploadForm.dataset.type = type;
                typeInput.value = type;
                submissionInp.value = subId;

                // set judul modal
                document.getElementById("uploadModalLabel").innerHTML =
                    `<i class="bi bi-cloud-upload"></i> ${title}`;

                // reset file & preview
                fileUpload.value = "";
                previewEmpty.classList.remove('d-none');
                pdfFrame.classList.add('d-none');
                pdfFrame.src = "about:blank";

                uploadModal.show();
            });
        });

        // Preview PDF saat pilih file
        fileUpload.addEventListener("change", () => {
            const file = fileUpload.files[0];
            if (!file) {
                previewEmpty.classList.remove('d-none');
                pdfFrame.classList.add('d-none');
                pdfFrame.src = "about:blank";
                return;
            }
            const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
            if (!isPdf) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format tidak valid',
                    text: 'File harus PDF.'
                });
                fileUpload.value = "";
                previewEmpty.classList.remove('d-none');
                pdfFrame.classList.add('d-none');
                pdfFrame.src = "about:blank";
                return;
            }
            const MAX = 2 * 1024 * 1024; // 2 MB
            if (file.size > MAX) {
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar',
                    text: 'Maksimal 10 MB.'
                });
                fileUpload.value = "";
                previewEmpty.classList.remove('d-none');
                pdfFrame.classList.add('d-none');
                pdfFrame.src = "about:blank";
                return;
            }
            const url = URL.createObjectURL(file);
            pdfFrame.src = url;
            previewEmpty.classList.add('d-none');
            pdfFrame.classList.remove('d-none');
        });

        // Submit upload
        uploadForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const type = uploadForm.dataset.type; // "1".."5"
            const file = fileUpload.files[0];
            if (!file) {
                return Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silakan pilih file PDF!'
                });
            }

            const prevHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Mengunggah...`;

            try {
                const formData = new FormData(uploadForm);
                if (!formData.has('type')) formData.append('type', type); // jaga-jaga

                const resp = await fetch(`${baseUrl}mahasiswa/upload-tugas/${type}`, {
                    method: "POST",
                    body: formData
                });

                let result;
                try {
                    result = await resp.json();
                } catch (_) {
                    result = {};
                }
                if (resp.ok) {
                    await Swal.fire('Berhasil!', result.message || 'File berhasil diunggah.', 'success');
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result?.message || 'Gagal mengunggah file.'
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: `Terjadi kesalahan: ${err.message}`
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = prevHtml;
            }
        });

        // Modal feedback
        document.querySelectorAll(".btn-feedback").forEach(btn => {
            btn.addEventListener("click", () => {
                document.getElementById("feedbackContent").innerText = btn.dataset.feedback || '-';
                new bootstrap.Modal(document.getElementById("feedbackModal")).show();
            });
        });
    });
</script>