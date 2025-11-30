$(document).ready(function () {
    const url = $('#datatablesSimple').data('url');
    $('#datatablesSimple').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": url,
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
        columns: [
            { data: 'nim', "orderable": true },
            { data: 'name', "orderable": true },
            { data: 'school_name', "orderable": true },
            { data: 'lecturer_name', "orderable": true },
        ]
    });
});