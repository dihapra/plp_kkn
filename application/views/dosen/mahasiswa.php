<style>
    /* Gaya untuk header tabel */
    #dataTable_length {
        margin-bottom: 20px;
    }

    #dataTable thead th {
        background-color: #343a40;
        color: #fff;
        text-align: center;
        font-weight: bold;
    }

    /* Gaya untuk body tabel */
    #dataTable tbody td {
        vertical-align: middle;
        text-align: center;
    }

    /* Gaya hover untuk baris */
    #dataTable tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    /* Gaya untuk DataTables */
    .dataTables_wrapper .dataTables_filter input {
        margin-left: 5px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.4rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.4rem;
    }
</style>

<div class="card mt-4">
    <div class="card-header">
        <div class="card-title">Data Mahasiswa</div>
    </div>

    <div class="card-body">
        <div class="mt-2">
            <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <!-- <th>No Handphone</th> -->
                        <th>Prodi</th>
                        <th>Guru Pamong</th>
                        <th>Sekolah</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        async function fetchDatatable() {

            try {
                const url = baseUrl + 'dosen/datatable/mahasiswa';
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
                        // { data: 'phone', "orderable": true },
                        { data: 'prodi', "orderable": true },
                        // { data: 'fakultas', "orderable": true },
                        { data: 'teacher_name', "orderable": true },
                        { data: 'school_name', "orderable": true },
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
</script>