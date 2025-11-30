<style>
    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.4);
        color: white;
        border: none;
        font-size: 2rem;
        padding: 0.5rem 0.8rem;
        border-radius: 50%;
        cursor: pointer;
        z-index: 1056;
        /* lebih tinggi dari modal content */
        transition: background 0.2s;
    }

    .nav-btn:hover {
        background: rgba(0, 0, 0, 0.7);
    }

    .nav-btn-prev {
        left: -3.5rem;
        /* agak keluar dari modal */
    }

    .nav-btn-next {
        right: -3.5rem;
    }
</style>
<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Data Guru Belum Terverifikasi
        </div>
    </div>

    <div class="card-body">
        <!-- <div class="d-flex m-2" style="gap:10px">
            <?php if ($this->session->userdata('role') === 'super_admin'): ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#verif-modal">
                    Tambah
                </button>
            <?php endif; ?>

            <button type="button" class="btn btn-success ekspor-guru">
                Ekspor
            </button>
        </div> -->
        <?php $this->load->view('utils/filter_sekolah') ?>
        <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Phone</th>
                    <th>Nama Sekolah</th>
                    <th>Bank</th>
                    <th>No Rekening</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="verif-modal" tabindex="-1" aria-labelledby="verif-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Tombol prev di kiri -->
            <button type="button" class="nav-btn nav-btn-prev" id="prevBtnSide">
                <i class="bi bi-chevron-left"></i>
            </button>

            <!-- Tombol next di kanan -->
            <button type="button" class="nav-btn nav-btn-next" id="nextBtnSide">
                <i class="bi bi-chevron-right"></i>
            </button>

            <form id="verify-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="verif-modalLabel">Tambah Data Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="container-fluid">

                        <!-- Bagian Informasi Guru -->
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">Nama Guru Pamong</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">Status Pernikahan</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="status">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">E-Mail Aktif</div>
                            <div class="col-8">
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">No WhatsApp</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="phone">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">Nama Sekolah</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="school_name">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">Prodi Mahasiswa</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="prodi">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-4 fw-bold">Nama Mahasiswa yang Dibimbing</div>
                            <div class="col-8">
                                <input type="text" class="form-control" name="mahasiswa">
                            </div>
                        </div>
                        <!-- Tambahkan baris lain sesuai gambar -->

                        <hr>

                        <!-- Bagian Scan KTP & Scan Rekening -->
                        <div class="row text-center mb-3">
                            <div class="col">
                                <h5>Scan KTP</h5>
                                <i class="bi bi-person-badge fs-1"></i>
                                <img id="identication-card"
                                    class="identification-card img-thumbnail mt-2"
                                    style="max-width: 350px; object-fit: contain;">
                            </div>
                            <div class="col">
                                <h5>Scan Rekening</h5>
                                <i class="bi bi-credit-card fs-1"></i>
                                <img id="book"
                                    class="book img-thumbnail mt-2"
                                    style="max-width: 350px; object-fit: contain;">
                            </div>
                        </div>


                        <!-- Nomor KTP dan Rekening -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">NIK</label>
                                <input type="text" class="form-control" name="nik">
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Rekening</label>
                                    <input type="text" class="form-control" name="account_number">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Bank</label>
                                    <input type="text" class="form-control" name="bank">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Pemilik Rekening</label>
                                    <input type="text" class="form-control" name="account_name">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">


                        </div>

                        <!-- Instruksi -->
                        <div class="text-danger small mt-3">
                            1. Cek kesesuaian NIK dengan scan KTP <br>
                            2. Cek kesesuaian data rekening dengan scan buku Tabungan
                        </div>
                        <div class="mt-3">
                            <label for="fail_message" class="form-label fw-bold">Pesan Gagal Verifikasi</label>
                            <textarea class="form-control" id="fail_message" name="fail_message" rows="3"
                                placeholder="Tuliskan alasan jika verifikasi gagal..."></textarea>
                            <span class="form-text text-muted">Wajib diisi jika ingin menekan tombol <b>Gagal Verifikasi</b></span>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" disabled id="btnFail" type="button" class="btn btn-danger">Gagal Verifikasi</button>
                    <button type="submit" class="btn btn-success">Verifikasi</button>
                </div>


            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const failMsg = document.getElementById("fail_message");
        const btnFail = document.getElementById("btnFail");

        failMsg.addEventListener("input", function() {
            if (failMsg.value.trim().length > 0) {
                btnFail.disabled = false;
            } else {
                btnFail.disabled = true;
            }
        });
    });
</script>

<script src="<?= base_url('assets/js/pages/admin/teacher-unverified/page.js') ?>"></script>