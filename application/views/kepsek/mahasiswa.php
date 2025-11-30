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
                        <th>Prodi</th>
                        <th>Fakultas</th>
                        <th>Dosen Pembimbing</th>
                        <th>Guru Pamong</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be filled by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        $('#dataTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('kepsek/datatable_student') ?>",
                "type": "POST"
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
            columns: [{
                    data: 'name'
                },
                {
                    data: 'nim'
                },
                {
                    data: 'prodi'
                },
                {
                    data: 'fakultas'
                },
                {
                    data: 'lecturer_name'
                },
                {
                    data: 'teacher_name'
                },
            ]
        });
    });
</script>