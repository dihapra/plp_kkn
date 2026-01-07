<?php
$activeProgramLabel = 'Belum ada program aktif';
if (!empty($activeProgram)) {
    $parts = [];
    if (!empty($activeProgram['nama'])) {
        $parts[] = $activeProgram['nama'];
    }
    if (!empty($activeProgram['tahun_ajaran'])) {
        $parts[] = $activeProgram['tahun_ajaran'];
    }
    $activeProgramLabel = trim(implode(' ', $parts));
    if ($activeProgramLabel === '') {
        $activeProgramLabel = 'Program aktif';
    }
}
?>

<div class="pt-4">
<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="mb-0">Data Mahasiswa Prodi</h5>
            <small class="text-muted">Pantau progres mahasiswa dan penempatannya di sekolah.</small>
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
            <div class="input-group input-group-sm flex-grow-1" style="max-width: 520px;">
                <span class="input-group-text">Program Aktif</span>
                <input type="text" class="form-control" value="<?= htmlspecialchars($activeProgramLabel, ENT_QUOTES, 'UTF-8') ?>" readonly>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm" id="kaprodiMahasiswaTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Sekolah</th>
                        <th>Program Aktif</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <small class="text-muted d-block mt-3">
            Integrasikan tabel ini dengan endpoint mahasiswa untuk menampilkan data real-time berdasarkan prodi aktif.
        </small>
    </div>
</div>
</div>

<script>
    $(function () {
        $('#kaprodiMahasiswaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= base_url('kaprodi/datatable/mahasiswa') ?>",
                type: "POST"
            },
            columns: [
                { data: 'nama' },
                { data: 'nim' },
                { data: 'prodi' },
                { data: 'sekolah' },
                { data: 'program_aktif' },
            ]
        });
    });
</script>
