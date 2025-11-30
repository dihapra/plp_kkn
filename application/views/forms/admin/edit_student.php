<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNim" class="form-label">NIM</label>
                        <input type="text" class="form-control" id="editNim" name="nim" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="editPhone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSekolah" class="form-label">Sekolah</label>
                        <input type="text" class="form-control" id="editSekolah" name="sekolah" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDosen" class="form-label">Dosen Pembimbing</label>
                        <input type="text" class="form-control" id="editDosen" name="dosen_pembimbing" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGuru" class="form-label">Guru Pamong</label>
                        <input type="text" class="form-control" id="editGuru" name="guru_pamong" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>