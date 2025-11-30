<style>
    .text-small {
        font-size: 12px;
    }
</style>
<div class="container mt-4">
    <h2>Absensi Mahasiswa</h2>


    <!-- Tabel Absensi -->

    <table id="dataTable" class="table table-bordered table-striped w-100">
        <thead class="table-dark text-center align-middle">
            <tr>
                <th rowspan="2" class="text-small">Nama</th>
                <th colspan="16" class="text-small">Pertemuan</th>
            </tr>
            <tr>
                <!-- Generate angka 1 - 16 -->
                <th class="text-small">1</th>
                <th class="text-small">2</th>
                <th class="text-small">3</th>
                <th class="text-small">4</th>
                <th class="text-small">5</th>
                <th class="text-small">6</th>
                <th class="text-small">7</th>
                <th class="text-small">8</th>
                <th class="text-small">9</th>
                <th class="text-small">10</th>
                <th class="text-small">11</th>
                <th class="text-small">12</th>
                <th class="text-small">13</th>
                <th class="text-small">14</th>
                <th class="text-small">15</th>
                <th class="text-small">16</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

</div>


<script>
    $(document).ready(function() {
        // Kolom pertemuan yang bisa diubah oleh guru
        const editableMeetings = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
        const statusOptions = ["", "Hadir", "Tidak Hadir", "Sakit", "Izin"];

        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            paginate: false,
            // pageLength: 25,
            scrollX: true,
            ajax: {
                url: baseUrl + 'guru/absensi/datatable', // endpoint pivot 16 pertemuan
                type: "POST"
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
                    sLast: "Terakhir"
                }
            },
            columns: [{
                    data: "student_name",
                    orderable: true,
                    className: "text-small",
                },
                ...Array.from({
                    length: 16
                }, (_, i) => {
                    const meeting = i + 1;
                    return {
                        data: `pertemuan_${meeting}`,
                        className: "text-small",
                        orderable: false,
                        render: function(data, type, row) {
                            const disabled = editableMeetings.includes(meeting) ? "" : "disabled";
                            const currentValue = data ?? "";
                            let select = `<select style="width:120px;" class="form-select text-small form-select-sm absensi-select" 
                 data-student="${row.student_id}" 
                 data-meeting="${meeting}" ${disabled}>`;

                            statusOptions.forEach(opt => {
                                const selected = (opt === currentValue) ? "selected" : "";
                                select += `<option value="${opt}" ${selected}>${opt || '-'}</option>`;
                            });
                            select += `</select>`;
                            return select;

                        }
                    }
                })
            ]
        });

        // Event: Auto-save ketika select diganti
        $('#dataTable').on('change', '.absensi-select', async function() {
            const studentId = $(this).data('student');
            const meetingNumber = $(this).data('meeting');
            const status = $(this).val();
            const url = baseUrl + 'guru/absensi/simpan';

            // Gunakan FormData
            const formData = new FormData();
            formData.append("student_id", studentId);
            formData.append("meeting_number", meetingNumber);
            formData.append("status", status);

            try {
                const resp = await fetch(url, {
                    method: "POST",
                    body: formData
                });

                if (resp.ok) {


                    const data = await resp.json();

                    // ✅ Tampilkan toast agar tidak mengganggu alur absensi
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Status absensi diperbarui!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menyimpan status absensi.'
                });
            }
        });


    });
</script>