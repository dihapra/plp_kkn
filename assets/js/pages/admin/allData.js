$(document).ready(function () {
    let fakultas = $('#fakultas_filter').val();
    let prodi = $('#prodi_filter').val();

    $('#fakultas_filter').on('change', function () {
        fakultas = $(this).val(); // Update nilai fakultas
        prodi = '';              // Reset nilai prodi
        $('#prodi_filter').val(''); // Reset dropdown prodi (pilih opsi default)
        fetchDatatable();
    });

    $('#prodi_filter').on('change', function () {
        prodi = $(this).val(); // Update nilai prodi saat user memilih prodi
        fetchDatatable();
    });

    async function fetchDatatable() {

        try {
            const url = baseUrl + 'admin/datatable/semua-data';
            // Initialize DataTable with fetched data
            $('#dataTable').DataTable().destroy();
            $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": function (d) {
                        d.start = d.start; // Pastikan ini tidak di-hardcode dan benar-benar dinamis
                        d.length = d.length;
                        d.fakultas = fakultas;
                        d.prodi = prodi;
                    }
                },
                "language": {
                    "sProcessing": "Sedang memproses...",
                    "sLengthMenu": "Tampilkan _MENU_ data",
                    "sZeroRecords": "Tidak ditemukan data yang sesuai",
                    "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "sInfoFiltered": "(disaring dari _MAX_ data keseluruhan)",
                    "sSearch": "Cari:",
                    "oPaginate": {
                        "sFirst": "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext": "Selanjutnya",
                        "sLast": "Terakhir"
                    }
                },
                columns: [
                    { data: 'school_name', "orderable": true },
                    { data: 'principal', "orderable": true },
                    { data: 'principal_phone', "orderable": true },
                    { data: 'teacher_name', "orderable": true },
                    { data: 'lecturer_name', "orderable": true },
                    { data: 'student_email', "orderable": true },
                    { data: 'student_name', "orderable": true },
                    { data: 'nim', "orderable": true },
                    { data: 'student_phone', "orderable": true },
                    { data: 'student_prodi', "orderable": true },
                    { data: 'student_fakultas', "orderable": true },

                ]
            });

        } catch (error) {
            // Show error using Swal.fire
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
                confirmButtonText: 'OK'
            });
        }
    }

    // Call the function to fetch data and initialize DataTable
    fetchDatatable();

});
