<link rel="stylesheet" href="<?= css_url('timeline.css') ?>">
<h1 class="text-center m-4">Aktivitas Mahasiswa</h1>
<ul class="timeline" id="timelineAktivitas"></ul>

<!-- Modal Detail Aktivitas -->
<?php $this->load->view('mahasiswa/aktivitas/detail-aktivitas') ?>

<!-- Modal Tambah Logbook -->
<div class="modal fade" id="logbookModal" tabindex="-1" aria-labelledby="logbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logbookModalLabel">Tambah Logbook - Pertemuan <span
                        id="logbookMeeting"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $this->load->view('forms/student/logbook') ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveLogbookBtn">Simpan Logbook</button>
                </div>

            </div>
        </div>
    </div>
</div>

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
                <?php $this->load->view('forms/student/logbook') ?>
                <div class="mt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted text-uppercase fw-bold small mb-2">Feedback Dosen</h6>
                                    <p class="mb-0" id="lecturerFeedbackDisplay">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="text-muted text-uppercase fw-bold small mb-2">Feedback Guru Pamong</h6>
                                    <p class="mb-0" id="teacherFeedbackDisplay">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit Logbook -->
<div class="modal fade" id="editLogbookModal" tabindex="-1" aria-labelledby="editLogbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLogbookModalLabel">
                    Edit Logbook - Pertemuan <span id="editLogbookMeeting"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $this->load->view('forms/student/logbook') ?>
                <button class="btn btn-success mt-3" id="updateLogbookBtn">Perbarui Logbook</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= js_url("pages/student/logbook/index.js") ?>"></script>
<script src="<?= js_url("pages/student/logbook/save.js") ?>"></script>
<script src="<?= js_url("pages/student/logbook/update.js") ?>"></script>
