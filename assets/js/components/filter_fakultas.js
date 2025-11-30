const prodiData = {
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
$(document).ready(function () {
    // Event listener saat fakultas dipilih
    $('#fakultas_filter').on('change', function () {
        const selectedFakultas = $(this).val(); // Ambil nilai fakultas
        const $prodiSelect = $('#prodi_filter'); // Dropdown Prodi

        // Kosongkan dropdown Prodi
        $prodiSelect.empty();
        $prodiSelect.append('<option value="">Pilih Prodi</option>');
        // Tambahkan opsi Prodi jika fakultas dipilih
        if (selectedFakultas && prodiData[selectedFakultas]) {
            $.each(prodiData[selectedFakultas], function (index, prodi) {
                $prodiSelect.append(`<option value="${prodi}">${prodi}</option>`);
            });
        }
    });

    if ($('#fakultas_filter').val() !== "") {
        $('#fakultas_filter').trigger('change');
    }
});

