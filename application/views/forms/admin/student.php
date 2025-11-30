<div class="mb-3">
    <label for="name" class="form-label">Nama</label>
    <input type="text" class="form-control" id="name" name="name" required>
</div>
<div class="mb-3">
    <label for="nim" class="form-label">NIM</label>
    <input type="text" class="form-control" id="nim" name="nim" required>
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email">
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Nomor Telepon</label>
    <input type="tel" class="form-control" id="phone" name="phone">
</div>
<div class="mb-3">
    <label for="fakultas" class="form-label">Fakultas</label>
    <select class="form-select" id="fakultas" name="fakultas" required>
        <option value="" disabled selected>Pilih Fakultas</option>
        <!-- Fakultas akan diisi melalui JavaScript -->
    </select>
</div>
<div class="mb-3">
    <label for="prodi" class="form-label">Prodi</label>
    <select class="form-select" id="prodi" name="prodi" required>
        <option value="" disabled selected>Pilih Prodi</option>
        <!-- Prodi akan diisi berdasarkan pilihan fakultas -->
    </select>
</div>
<div class="mb-3">
    <label for="schoolSelect" class="form-label">Pilih Sekolah</label>
    <select class="form-control" id="schoolSelect">
        <option value="">Pilih Sekolah</option>
        <!-- Opsi akan diisi oleh Select2 -->
    </select>
</div>
<div class="mb-3">
    <label for="schoolName" class="form-label">Sekolah</label>
    <input type="text" class="form-control" id="schoolName" name="school_name" disabled>
    <input type="hidden" id="schoolId" name="school_id">
</div>
<div class="mb-3">
    <label for="teacherSelect" class="form-label">Pilih Guru</label>
    <select class="form-control" id="teacherSelect">
        <option value="">Pilih Guru</option>
        <!-- Opsi akan diisi oleh Select2 -->
    </select>
</div>
<div class="mb-3">
    <label for="teacher" class="form-label">Guru Pamong</label>
    <input type="text" class="form-control" id="teacherName" name="teacher_name" disabled>
    <input type="hidden" id="teacherId" name="teacher_id">
</div>
<div class="mb-3">
    <label for="lectureSelect" class="form-label">Pilih Dosen</label>
    <select class="form-control" id="lectureSelect">
        <option value="">Pilih Dosen</option>
        <!-- Opsi akan diisi oleh Select2 -->
    </select>
</div>
<div class="mb-3">
    <label for="lectureName" class="form-label">Dosen Pembimbing</label>
    <input type="text" class="form-control" id="lectureName" name="lectureName" disabled>
    <input type="hidden" id="lectureId" name="lecture_id">
</div>
<script>
    let dataProdi = {
        "FAKULTAS BAHASA DAN SENI": [
            "Pendidikan Bahasa Inggris",
            "Sastra Inggris",
            "Pendidikan Bahasa dan Sastra Indonesia",
            "Pendidikan Seni Rupa",
            "Pendidikan Tari",
            "Sastra Indonesia",
            "Pendidikan Bahasa Prancis",
            "Seni Pertunjukan",
            "Bahasa Jerman",
            "Pendidikan Musik"
        ],
        "FAKULTAS EKONOMI": [
            "Manajemen",
            "Bisnis Digital",
            "Ilmu Ekonomi",
            "Pendidikan Administrasi Perkantoran",
            "Akuntansi",
            "Pendidikan Akuntansi",
            "Pendidikan Bisnis",
            "Kewirausahaan",
            "Pendidikan Ekonomi"
        ],
        // Data fakultas lainnya
        "FAKULTAS ILMU KEOLAHRAGAAN": [
            "Pendidikan Jasmani Kesehatan dan Rekreasi",
            "Pendidikan Kepelatihan Olahraga",
            "Ilmu Keolahragaan"
        ],
        "FAKULTAS ILMU PENDIDIKAN": [
            "Pendidikan Masyarakat",
            "Pendidikan Guru Sekolah Dasar",
            "Bimbingan dan Konseling",
            "Pendidikan Guru Pendidikan Anak Usia Dini"
        ],
        "FAKULTAS ILMU SOSIAL": [
            "Pendidikan Antropologi",
            "Pendidikan Geografi",
            "Pendidikan Sejarah",
            "Pendidikan Pancasila dan Kewarganegaraan"
        ],
        "FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM": [
            "Kimia",
            "Pendidikan Matematika",
            "Pendidikan Fisika",
            "Pendidikan Kimia",
            "Pendidikan Biologi",
            "Biologi",
            "Fisika",
            "Statistika",
            "Pendidikan IPA",
            "Matematika",
            "Ilmu Komputer"
        ],
        "FAKULTAS TEKNIK": [
            "Pendidikan Teknik Mesin",
            "Pendidikan Teknik Otomotif",
            "Pendidikan Teknik Elektro",
            "Pendidikan Teknik Bangunan",
            "Pendidikan Tata Busana",
            "Teknik Sipil",
            "Pendidikan Tata Boga",
            "Teknik Mesin D3",
            "Pendidikan Tata Rias",
            "Pendidikan Teknologi Informatika dan Komputer",
            "Teknik Elektro",
            "Insinyur",
            "Gizi",
            "D4 - Manajemen Konstruksi"
        ]
    };

    // Populate Fakultas Select
    const fakultasSelect = document.getElementById('fakultas');
    const prodiSelect = document.getElementById('prodi');

    Object.keys(dataProdi).forEach(fakultas => {
        const option = document.createElement('option');
        option.value = fakultas;
        option.textContent = fakultas;
        fakultasSelect.appendChild(option);
    });

    // Event Listener untuk Fakultas
    fakultasSelect.addEventListener('change', function() {
        const selectedFakultas = this.value;

        // Clear Prodi Options
        prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi</option>';

        if (dataProdi[selectedFakultas]) {
            dataProdi[selectedFakultas].forEach(prodi => {
                const option = document.createElement('option');
                option.value = prodi;
                option.textContent = prodi;
                prodiSelect.appendChild(option);
            });
        }
    });
</script>