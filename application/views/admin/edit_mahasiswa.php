<div class="container mt-5">
    <h2 class="mb-4">Edit Data mahasiswa</h2>

    <!-- Tabs Content -->
    <div class=" m-3">
        <form id="editForm">
            <?php $this->load->view('forms/admin/student') ?>
            <button type="submit" class="btn btn-small btn-success">Submit</button>
        </form>
    </div>


</div>

<script>
    $(document).ready(async function() {
        const student = <?php echo json_encode($student); ?>;
        console.log(student)
        // Mengisi form edit dengan data yang didapat
        if (student) {
            $("#name").val(student.name || "");
            $("#email").val(student.email || "");
            $("#nim").val(student.nim || "");
            $("#phone").val(student.phone || "");
            $("#fakultas").val(student.fakultas || "");

            // Tunggu sampai prodi dimuat, lalu isi nilai prodi
            // Trigger Event Change untuk Memuat Prodi
            fakultasSelect.dispatchEvent(new Event('change'));

            // Tunggu hingga opsi Prodi dimuat
            setTimeout(() => {
                // Isi Prodi
                prodiSelect.value = student.prodi || "";
            }, 300);
            $("#schoolName").val(student.school_name || "");
            $("#schoolId").val(student.school_id || "");
            $("#teacherName").val(student.teacher_name || "");
            $("#teacherId").val(student.teacher_id || "");
            $("#lectureName").val(student.lecture_name || "");
            $("#lectureId").val(student.lecture_id || "");
        }

        function populateEditForm(student) {
            // Isi Fakultas
            fakultasSelect.value = student.fakultas || "";

            // Sesuaikan waktu tunggu dengan kebutuhan
        }
        const editForm = $("#editForm");

        editForm.on("submit", async function(event) {
            event.preventDefault(); // Mencegah submit form secara default
            const formElement = editForm[0];

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
                const id = <?= $id ?>;
                const response = await fetch(`<?= base_url("admin/mahasiswa/update/$id") ?>`, {
                    method: "POST",
                    body: formData,
                });

                if (!response.ok) {
                    const resultError = await response.json();
                    throw new Error(resultError.message);
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

        function setSelect2Value($select, id, text) {
            if (!id) return;
            // jika option belum ada (mis. data tidak memuat id tsb), sisipkan dulu
            if ($select.find(`option[value="${id}"]`).length === 0) {
                const opt = new Option(text || `ID ${id}`, id, true, true);
                $select.append(opt);
            }
            $select.val(id).trigger('change.select2'); // gunakan .select2 biar UI refresh
        }

        async function fetchSchools() {
            const res = await fetch(`${baseUrl}/sekolah/select`);
            if (!res.ok) throw new Error('Gagal mengambil data sekolah.');
            const data = await res.json();
            const select2Data = data.map(s => ({
                id: s.id,
                text: s.name
            }));
            $('#schoolSelect').select2({
                data: select2Data,
                placeholder: 'Pilih Sekolah',
                allowClear: true,
                width: '100%'
            });
            // sinkronkan hidden saat pilih/clear
            $('#schoolSelect')
                .off('select2:select').on('select2:select', (e) => {
                    $('#schoolName').val(e.params.data.text);
                    $('#schoolId').val(e.params.data.id);
                    // setelah sekolah berubah, muat ulang guru
                    fetchTeacher();
                })
                .off('select2:clear').on('select2:clear', () => {
                    $('#schoolName').val('');
                    $('#schoolId').val('');
                    // kosongkan guru juga
                    $('#teacherSelect').val(null).trigger('change.select2');
                    $('#teacherName').val('');
                    $('#teacherId').val('');
                });

            // SET TERPILIH dari student
            if (student?.school_id) {
                // cari nama dari sumber saat ini (fallback ke student.school_name kalau ada)
                const namaSek = (select2Data.find(s => s.id == student.school_id) || {}).text || student.school_name;
                setSelect2Value($('#schoolSelect'), student.school_id, namaSek);
                if (namaSek) $('#schoolName').val(namaSek);
                $('#schoolId').val(student.school_id);
            }
        }

        async function fetchTeacher() {
            const schoolId = $('#schoolId').val();
            if (!schoolId) {
                // init kosong saja jika belum ada sekolah
                if (!$('#teacherSelect').data('select2')) {
                    $('#teacherSelect').select2({
                        placeholder: 'Pilih Guru',
                        allowClear: true,
                        width: '100%'
                    });
                } else {
                    $('#teacherSelect').empty().trigger('change.select2');
                }
                return;
            }
            const res = await fetch(`${baseUrl}/guru/select?schoolId=${schoolId}`);
            if (!res.ok) throw new Error('Gagal mengambil data guru.');
            const data = await res.json();
            const select2Data = data.map(t => ({
                id: t.id,
                text: t.name
            }));

            if (!$('#teacherSelect').data('select2')) {
                $('#teacherSelect').select2({
                    data: select2Data,
                    placeholder: 'Pilih Guru',
                    allowClear: true,
                    width: '100%'
                });
            } else {
                // update data: kosongkan lalu isi ulang
                $('#teacherSelect').empty();
                select2Data.forEach(it => $('#teacherSelect').append(new Option(it.text, it.id)));
                $('#teacherSelect').trigger('change.select2');
            }

            $('#teacherSelect')
                .off('select2:select').on('select2:select', (e) => {
                    $('#teacherName').val(e.params.data.text);
                    $('#teacherId').val(e.params.data.id);
                })
                .off('select2:clear').on('select2:clear', () => {
                    $('#teacherName').val('');
                    $('#teacherId').val('');
                });

            // SET TERPILIH dari student (jika ada)
            if (student?.teacher_id) {
                const namaGuru = (select2Data.find(t => t.id == student.teacher_id) || {}).text || student.teacher_name;
                setSelect2Value($('#teacherSelect'), student.teacher_id, namaGuru);
                if (namaGuru) $('#teacherName').val(namaGuru);
                $('#teacherId').val(student.teacher_id);
            }
        }

        async function fetchDosen() {
            const res = await fetch(`${baseUrl}/dosen/select`);
            if (!res.ok) throw new Error('Gagal mengambil data dosen.');
            const data = await res.json();
            const select2Data = data.map(d => ({
                id: d.id,
                text: d.name
            }));

            $('#lectureSelect').select2({
                data: select2Data,
                placeholder: 'Pilih Dosen',
                allowClear: true,
                width: '100%'
            });

            $('#lectureSelect')
                .off('select2:select').on('select2:select', (e) => {
                    $('#lectureName').val(e.params.data.text);
                    $('#lectureId').val(e.params.data.id);
                })
                .off('select2:clear').on('select2:clear', () => {
                    $('#lectureName').val('');
                    $('#lectureId').val('');
                });

            // SET TERPILIH dari student
            if (student?.lecture_id) {
                const namaDosen = (select2Data.find(d => d.id == student.lecture_id) || {}).text || student.lecture_name;
                setSelect2Value($('#lectureSelect'), student.lecture_id, namaDosen);
                if (namaDosen) $('#lectureName').val(namaDosen);
                $('#lectureId').val(student.lecture_id);
            }
        }

        await fetchSchools();
        await fetchTeacher();
        await fetchDosen();
    });
</script>