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
            const url = baseUrl + 'admin/datatable/mahasiswa';
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
                    { data: 'name', "orderable": true },
                    { data: 'nim', "orderable": true },
                    { data: 'phone', "orderable": true },
                    { data: 'school_name', "orderable": true },
                    { data: 'lecturer_name', "orderable": true },
                    { data: 'teacher_name', "orderable": true },
                    {
                        data: null,
                        defaultContent: "",
                        render: function (data, type, row) {
                            // Tampilkan tombol delete hanya jika user_role adalah super_admin
                            let deleteButton = "";
                            if (row.user_role === "super_admin") {
                                deleteButton = `<button class="btn btn-sm btn-danger delete" data-id="${row.id}" data-name="${row.name}">Delete</button>`;
                            }
                            return `
                                <div class="d-flex flex-column" style="gap:5px">
                                    <a href='${baseUrl}admin/mahasiswa/edit_page/${row.id}' class="btn btn-sm btn-primary edit" data-id="${row.id}">Edit</a>
                                    ${deleteButton}
                                </div>
                            `;
                        }
                    }
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
    $(document).on('click', '.delete', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${baseUrl}admin/mahasiswa/hapus/${id}`, {
                        method: 'DELETE'
                    });
                    if (!response.ok) throw new Error('Gagal menghapus data.');
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                    fetchDatatable();
                } catch (error) {
                    Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
                }
            }
        });
    });
});
