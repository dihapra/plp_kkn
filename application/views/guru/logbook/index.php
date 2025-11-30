<div class="card">
    <div class="card-body">
        <h4 class="mb-3">Logbook Mahasiswa</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <?php
                        $displayMeetings = array_values(array_filter(
                            $editableMeetings ?? [],
                            static function ($meeting) {
                                return (int) $meeting !== 8;
                            }
                        ));
                        ?>
                        <?php foreach ($displayMeetings as $meeting_num): ?>
                            <th>P<?= $meeting_num ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logbooks)): ?>
                        <?php foreach ($logbooks as $nim => $student_data): ?>
                            <tr>
                                <td><?= htmlspecialchars($student_data['nama'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($student_data['nim'] ?? '-') ?></td>
                                <?php foreach ($displayMeetings as $meeting_num): ?>
                                    <?php $meeting_data = $student_data['pertemuan'][$meeting_num] ?? null; ?>
                                    <td class="text-center">
                                        <?php if ($meeting_data && isset($meeting_data['id'])): ?>
                                            <button type="button"
                                                class="btn btn-sm btn-info btn-detail-logbook"
                                                data-id="<?= $meeting_data['id'] ?>">
                                                Detail
                                            </button>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= 2 + count($displayMeetings) ?>" class="text-center text-muted">
                                Belum ada data logbook.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal Detail Aktivitas -->
<?php $this->load->view('mahasiswa/aktivitas/detail-aktivitas') ?>

<!-- Modal Detail Logbook -->
<div class="modal fade" id="viewLogbookModal" tabindex="-1" aria-labelledby="viewLogbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLogbookModalLabel">Detail Logbook - Pertemuan <span
                        id="viewLogbookMeeting"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="logbookDetailContent" class="mb-4">
                    <p class="text-muted mb-0">Loading...</p>
                </div>
                <div class="logbook-section mb-4">
                    <h6 class="logbook-section__title">Detail Aktivitas</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle" id="logbookActivitiesTable">
                            <thead>
                                <tr>
                                    <th style="width:5%">No</th>
                                    <th style="width:45%">Kegiatan</th>
                                    <th style="width:50%">Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="feedbackLecturer" class="form-label">Feedback Dosen</label>
                    <textarea class="form-control" id="feedbackLecturer" rows="3" placeholder="Belum ada feedback dari dosen" readonly></textarea>
                </div>
                <div class="mb-3">
                    <label for="feedbackTeacher" class="form-label">Feedback Guru Pamong</label>
                    <textarea class="form-control" id="feedbackTeacher" rows="3" placeholder="Tuliskan feedback Anda untuk mahasiswa..."></textarea>
                    <small class="text-muted">Feedback ini akan terlihat oleh mahasiswa dan dosen pembimbing.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveFeedbackBtn" disabled>Simpan Feedback</button>
            </div>
        </div>
    </div>
</div>

<style>
    .logbook-section {
        border-left: 4px solid #0d6efd;
        padding-left: 1rem;
        margin-bottom: 1.5rem;
    }
    .logbook-section__title {
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }
    .logbook-tree {
        list-style: none;
        margin: 0;
        padding-left: 0;
    }
    .logbook-tree > li {
        margin-bottom: 0.5rem;
    }
    .logbook-tree span {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #95a1b1;
        letter-spacing: 0.04em;
    }
    .logbook-tree div {
        font-weight: 500;
        color: #212529;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentLogbookId = null;

        $(document).on('click', '.btn-detail-logbook', function() {
            const logbookId = $(this).data('id');
            currentLogbookId = logbookId;

            $('#viewLogbookMeeting').text('');
            $('#logbookDetailContent').html('<p class="text-muted mb-0">Loading...</p>');
            $('#logbookActivitiesTable tbody').html('<tr><td colspan="3" class="text-center text-muted">Memuat aktivitas...</td></tr>');
            $('#feedbackLecturer').val('');
            $('#feedbackTeacher').val('');
            $('#saveFeedbackBtn').prop('disabled', true).text('Simpan Feedback');

            $.ajax({
                url: '<?= base_url('guru/get_logbook_detail/') ?>' + logbookId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.data) {
                        const logbook = response.data;
                        $('#viewLogbookMeeting').text(logbook.meeting_number);

                        const content = `
                            <div class="logbook-hierarchy">
                                <div class="logbook-section">
                                    <h6 class="logbook-section__title">Identitas Mahasiswa</h6>
                                    <ul class="logbook-tree">
                                        <li>
                                            <span>Nama</span>
                                            <div>${logbook.student_name || '-'}</div>
                                        </li>
                                        <li>
                                            <span>NIM</span>
                                            <div>${logbook.student_nim || '-'}</div>
                                        </li>
                                        <li>
                                            <span>Pertemuan</span>
                                            <div>${logbook.meeting_number || '-'}</div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="logbook-section">
                                    <h6 class="logbook-section__title">Konten Logbook</h6>
                                    <ul class="logbook-tree">
                                        <li>
                                            <span>Tanggal Pengisian</span>
                                            <div>${logbook.created_at ? new Date(logbook.created_at).toLocaleDateString() : '-'}</div>
                                        </li>
                                        <li>
                                            <span>Permasalahan</span>
                                            <div>${logbook.problem || '-'}</div>
                                        </li>
                                        <li>
                                            <span>Solusi</span>
                                            <div>${logbook.solution || '-'}</div>
                                        </li>
                                        <li>
                                            <span>Kesimpulan</span>
                                            <div>${logbook.summary || '-'}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        `;
                        $('#logbookDetailContent').html(content);

                        const activities = Array.isArray(logbook.activities) ? logbook.activities : [];
                        const tbody = $('#logbookActivitiesTable tbody');
                        if (activities.length === 0) {
                            tbody.html('<tr><td colspan="3" class="text-center text-muted">Belum ada aktivitas.</td></tr>');
                        } else {
                            const rows = activities.map((activity, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${activity.activity || '-'}</td>
                                    <td>${activity.observation || '-'}</td>
                                </tr>
                            `);
                            tbody.html(rows.join(''));
                        }

                        $('#feedbackLecturer').val(logbook.feedback_lecture || '');
                        $('#feedbackTeacher').val(logbook.feedback_teacher || '');
                        $('#saveFeedbackBtn').prop('disabled', false);
                        $('#viewLogbookModal').modal('show');
                    } else {
                        const message = response.message || 'Logbook tidak ditemukan.';
                        if (window.Swal) {
                            Swal.fire('Gagal', message, 'error');
                        } else {
                            alert(message);
                        }
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Terjadi kesalahan saat mengambil detail logbook.';
                    if (window.Swal) {
                        Swal.fire('Gagal', message, 'error');
                    } else {
                        alert(message);
                    }
                }
            });
        });

        $('#saveFeedbackBtn').on('click', function() {
            if (!currentLogbookId) {
                return;
            }
            const feedback = $('#feedbackTeacher').val();
            const $btn = $(this);
            $btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: '<?= base_url('guru/save_logbook_feedback') ?>',
                method: 'POST',
                data: {
                    logbook_id: currentLogbookId,
                    feedback: feedback
                },
                dataType: 'json',
                success: function(response) {
                    const message = response.message || 'Feedback berhasil disimpan.';
                    if (window.Swal) {
                        Swal.fire('Berhasil', message, 'success');
                    } else {
                        alert(message);
                    }
                    $('#viewLogbookModal').modal('hide');
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Gagal menyimpan feedback.';
                    if (window.Swal) {
                        Swal.fire('Gagal', message, 'error');
                    } else {
                        alert(message);
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).text('Simpan Feedback');
                }
            });
        });
    });
</script>
