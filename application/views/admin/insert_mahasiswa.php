<div class="container mt-5">
    <h2 class="mb-4">Tambah Data mahasiswa</h2>


    <!-- Tabs Content -->
    <div class=" m-3">
        <form id="createForm">
            <?php $this->load->view('forms/admin/student') ?>
            <button type="submit" class="btn btn-small btn-success">Submit</button>
        </form>
    </div>


</div>


<script>
    $(document).ready(async function() {
        const createForm = $("#createForm");

        createForm.on("submit", async function(event) {
            event.preventDefault(); // Mencegah submit form secara default
            const formElement = createForm[0];

            // Ambil data dari form
            const formData = new FormData(formElement);
            formData.delete('schoolSelect');
            formData.delete('schoolName');
            formData.delete('teacherSelect');
            formData.delete('teacherName');
            formData.delete('lectureSelect');
            formData.delete('lectureName');
            try {
                // Kirim data menggunakan fetch
                const response = await fetch("<?= base_url('admin/mahasiswa/simpan') ?>", {
                    method: "POST",
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error("Terjadi kesalahan saat menyimpan data.");
                }

                const result = await response.json();

                // Tampilkan notifikasi sukses
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: result.message || "Data berhasil disimpan!",
                }).then(() => {
                    // Redirect ke halaman utama setelah notifikasi
                    window.location.href = "<?= base_url('admin/mahasiswa') ?>";
                });
            } catch (error) {
                console.error(error);

                // Tampilkan notifikasi error
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: error.message || "Data gagal disimpan. Silakan coba lagi.",
                });
            }
        });

        // Prevent dropdown auto-closing
        $(document).on('mousedown', '.select2-container', function(e) {
            e.stopPropagation(); // Hindari close dropdown
        });

        async function fetchTeacher() {
            const schoolId = $('#schoolId').val();
            try {
                const response = await fetch(`${baseUrl}/guru/select?schoolId=${schoolId}`);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data .');
                }
                const data = await response.json();

                const select2Data = data.map(teacher => ({
                    id: teacher.id,
                    text: teacher.name
                }));
                selectData = select2Data
                // Inisialisasi Select2 tanpa menentukan dropdownParent dulu
                $('#teacherSelect').select2({
                    data: select2Data,
                    placeholder: 'Pilih Guru',
                    allowClear: true,
                    width: '100%'
                });
                // Tambahkan event select untuk mengisi teacherName dan teacherId
                $('#teacherSelect').off('select2:select').on('select2:select', function(e) {
                    const selectedData = e.params.data;
                    $('#teacherName').val(selectedData.text); // Isi nama guru
                    $('#teacherId').val(selectedData.id); // Isi ID guru
                });

                // Event untuk clear pilihan
                $('#teacherSelect').off('select2:clear').on('select2:clear', function() {
                    $('#teacherName').val(''); // Kosongkan nama guru
                    $('#teacherId').val(''); // Kosongkan ID guru
                });
            } catch (error) {
                console.error(error);
            }
        }






        // Fetch data sekolah dari server
        async function fetchSchools() {
            try {
                const response = await fetch(`${baseUrl}/sekolah/select`);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data sekolah.');
                }
                const data = await response.json();

                const select2Data = data.map(school => ({
                    id: school.id,
                    text: school.name
                }));
                selectData = select2Data
                // Inisialisasi Select2 tanpa menentukan dropdownParent dulu
                $('#schoolSelect').select2({
                    data: select2Data,
                    placeholder: 'Pilih Sekolah',
                    allowClear: true,
                    width: '100%'
                });
                // Tambahkan event select untuk mengisi schoolName dan schoolId
                $('#schoolSelect').off('select2:select').on('select2:select', function(e) {
                    const selectedData = e.params.data;
                    $('#schoolName').val(selectedData.text); // Isi nama sekolah
                    $('#schoolId').val(selectedData.id); // Isi ID sekolah
                });

                // Event untuk clear pilihan
                $('#schoolSelect').off('select2:clear').on('select2:clear', function() {
                    $('#schoolName').val(''); // Kosongkan nama sekolah
                    $('#schoolId').val(''); // Kosongkan ID sekolah
                });
            } catch (error) {
                console.error(error);
            }
        }
        async function fetchDosen() {
            try {
                const response = await fetch(`${baseUrl}/dosen/select`);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data ');
                }
                const data = await response.json();

                const select2Data = data.map(school => ({
                    id: school.id,
                    text: school.name
                }));
                selectData = select2Data
                // Inisialisasi Select2 tanpa menentukan dropdownParent dulu
                $('#lectureSelect').select2({
                    data: select2Data,
                    placeholder: 'Pilih Dosen',
                    allowClear: true,
                    width: '100%'
                });
                // Tambahkan event select untuk mengisi schoolName dan schoolId
                $('#lectureSelect').off('select2:select').on('select2:select', function(e) {
                    const selectedData = e.params.data;
                    $('#lectureName').val(selectedData.text); // Isi nama sekolah
                    $('#lectureId').val(selectedData.id); // Isi ID sekolah
                });

                // Event untuk clear pilihan
                $('#lectureSelect').off('select2:clear').on('select2:clear', function() {
                    $('#lectureName').val(''); // Kosongkan nama sekolah
                    $('#lectureId').val(''); // Kosongkan ID sekolah
                });
            } catch (error) {
                console.error(error);
            }
        }

        await fetchSchools();
        await fetchTeacher();
        await fetchDosen();
    });
</script>