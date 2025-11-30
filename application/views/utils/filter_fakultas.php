<div class=" container ">
    <div class="row">
        <?php
        $fakultas_list = [
            "FAKULTAS BAHASA DAN SENI",
            "FAKULTAS EKONOMI",
            "FAKULTAS ILMU KEOLAHRAGAAN",
            "FAKULTAS ILMU PENDIDIKAN",
            "FAKULTAS ILMU SOSIAL",
            "FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM",
            "FAKULTAS TEKNIK"
        ];

        $role = $this->session->userdata('role');
        $fakultas_admin = trim($this->session->userdata('fakultas') ? $this->session->userdata('fakultas') : ''); // Pastikan tidak null
        ?>

        <div class="col-md-4 col-sm-12 mb-3">
            <label for="fakultas" class="form-label">Fakultas</label>
            <select class="form-select" id="fakultas_filter" name="fakultas_filter" <?= ($role === 'admin') ? 'disabled' : '' ?>>
                <option value="">Pilih Fakultas</option>

                <?php
                if ($role === 'admin') {
                    // Hanya tampilkan fakultas milik admin
                    if (!empty($fakultas_admin)) {
                        echo "<option value=\"$fakultas_admin\" selected>$fakultas_admin</option>";
                    }
                } else {
                    // Super Admin bisa melihat semua fakultas
                    foreach ($fakultas_list as $fakultas) {
                        echo "<option value=\"$fakultas\" >$fakultas</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-4 col-sm-12 mb-3">
            <label for="prodi_filter" class="form-label">Program Studi</label>
            <select class="form-select" id="prodi_filter" name="prodi_filter">
                <option value="">Pilih Prodi</option>
            </select>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/components/filter_fakultas.js') ?>"></script>