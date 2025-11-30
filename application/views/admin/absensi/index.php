<div class="card mt-4 ">
    <div class="card-header">
        <div class="card-title">
            Manajemen Data Absensi
        </div>
    </div>

    <div class="card-body">
        <?php $this->load->view('utils/filter_fakultas') ?>
        <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th class="fit-content">Nama Siswa</th>
                    <th>P1</th>
                    <th>P2</th>
                    <th>P3</th>
                    <th>P4</th>
                    <th>P5</th>
                    <th>P6</th>
                    <th>P7</th>
                    <th>P8</th>
                    <th>P9</th>
                    <th>P10</th>
                    <th>P11</th>
                    <th>P12</th>
                    <th>P13</th>
                    <th>P14</th>
                    <th>P15</th>
                    <th>P16</th>
                </tr>
            </thead>

        </table>
    </div>
</div>


<script>
    const table = "#dataTable"
    $(document).ready(function() {
        initTable();
    })

    function initTable() {
        if ($.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().destroy();
        }
        $(table).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}admin/datatable/absensi`,
                type: "POST",
            },
            language: {
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ data",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sInfoFiltered: "(disaring dari _MAX_ data keseluruhan)",
                sSearch: "Cari:",
                oPaginate: {
                    sFirst: "Pertama",
                    sPrevious: "Sebelumnya",
                    sNext: "Selanjutnya",
                    sLast: "Terakhir",
                },
            },
            columns: [{
                    data: "student_name"
                }, // Nama siswa
                {
                    data: "pertemuan_1",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_2",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_3",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_4",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_5",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_6",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_7",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_8",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_9",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_10",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_11",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_12",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_13",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_14",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_15",
                    defaultContent: "-"
                },
                {
                    data: "pertemuan_16",
                    defaultContent: "-"
                },
            ],
        });
    }
</script>