$(document).ready(async function () {
    let selectData = [];
    // Fetch data sekolah dari server
    async function fetchTeacher() {
        const schoolId = $('#schoolId').val();
        try {
            const response = await fetch(`${baseUrl}admin/guru/select?schoolId=${schoolId}`);
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
        } catch (error) {
            console.error(error);
        }
    }

    // Fetch data sekolah saat halaman dimuat
    await fetchTeacher();

    // Re-bind dropdownParent setiap kali modal ditampilkan
    // Re-bind dropdownParent setiap kali modal ditampilkan
    $('#createModal, #editModal').on('shown.bs.modal', function () {
        const modalId = $(this).attr('id'); // Ambil ID modal yang sedang aktif
        const selectElement = $(this).find('#teacherSelect'); // Cari elemen select dalam modal aktif

        // Re-inisialisasi Select2
        selectElement.select2({
            dropdownParent: $(`#${modalId}`), // Tetapkan modal aktif sebagai parent
            data: selectData,
            placeholder: 'Pilih Guru',
            allowClear: true,
            width: '100%'
        });

        // Tambahkan event select untuk mengisi schoolName dan schoolId
        selectElement.off('select2:select'); // Hindari bind event ganda
        selectElement.on('select2:select', function (e) {
            const selectedData = e.params.data; // Data sekolah yang dipilih
            $(`#${modalId} #teacherName`).val(selectedData.text); // Isi nama sekolah
            $(`#${modalId} #teacherId`).val(selectedData.id); // Isi ID sekolah
        });

        // Event untuk clear pilihan
        selectElement.off('select2:clear'); // Hindari bind event ganda
        selectElement.on('select2:clear', function () {
            $(`#${modalId} #teacherName`).val(''); // Kosongkan nama sekolah
            $(`#${modalId} #teacherId`).val(''); // Kosongkan ID sekolah
        });
    });

    // Prevent dropdown auto-closing
    $(document).on('mousedown', '.select2-container', function (e) {
        e.stopPropagation(); // Hindari close dropdown
    });
});
