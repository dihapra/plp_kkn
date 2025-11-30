$(document).ready(function () {
    // Fungsi untuk inisialisasi DataTable
    async function fetchDatatable() {
        try {
            const url = baseUrl + 'admin/datatable/sekolah';
            $('#dataTable').DataTable().clear().destroy();
            const table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": function (d) {
                        d.start = d.start;
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
                    { data: 'principal_name', "orderable": true },
                    { data: 'principal_email', "orderable": true },
                    { data: 'principal_phone', "orderable": true },
                    { data: 'principal_bank', "orderable": true },
                    { data: 'principal_account_name', "orderable": true },
                    { data: 'principal_account_number', "orderable": true },
                    { data: 'principal_nik', "orderable": true },
                    { data: 'principal_status', "orderable": true },
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
                                    <button class="btn btn-sm btn-primary edit" data-row='${JSON.stringify(row)}'">Edit</button>
                                    ${deleteButton}
                                </div>
                            `;
                        }
                    }
                ]

            });

            // Edit Modal
            $('#dataTable').on('click', '.edit', async function () {
                const row = $(this).data('row');
                console.log(row)
                // Isi form dengan data yang diterima
                $('#editModal #schoolId').val(row.id);
                $('#editModal #name').val(row.name);
                $('#editModal #principal').val(row.principal_name);
                $('#editModal #email').val(row.principal_email);
                $('#editModal #bank').val(row.principal_bank);
                $('#editModal #phone').val(row.principal_phone);
                $('#editModal #accountNumber').val(row.principal_account_number);
                $('#editModal #accountName').val(row.principal_account_name);
                $('#editModal #nik').val(row.principal_nik);
                $('#editModal #status').val(row.principal_status);
                $('#editModal #imgBook').attr('src', baseUrl + row?.principal_book);
                $('#editModal #book').attr('required', false);
                $('#editModal').modal('show');
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
                confirmButtonText: 'OK'
            });
        }
    }

    // Panggil DataTable
    fetchDatatable();


    // Submit Create Form
    $('#createForm').on('submit', async function (e) {
        e.preventDefault();
        try {
            const formData = new FormData(this);
            const response = await fetch(`${baseUrl}admin/sekolah/simpan`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (!response.ok) {
                const message = result.message ?? "gagal menyimpan data sekolah"
                throw new Error(message);

            }
            Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success');
            $('#createForm')[0].reset();
            $('#createModal').modal('hide');
            $('#dataTable').DataTable().clear().destroy();
            fetchDatatable();
        } catch (error) {
            Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
        }
    });

    // Submit Edit Form
    $('#editForm').on('submit', async function (e) {
        e.preventDefault();
        try {
            const id = $('#schoolId').val();
            const formData = new FormData(this);
            console.log(formData);
            const response = await fetch(`${baseUrl}admin/sekolah/update/${id}`, {
                method: 'POST',
                body: formData
            });
            // Jika response error, aktifkan baris berikut:
            if (!response.ok) {
                const message = result.message ?? "gagal memperbarui data sekolah"
                throw new Error(message);

            }
            Swal.fire('Berhasil!', 'Data berhasil diperbarui.', 'success')
                .then(() => {
                    // Reset form setelah update berhasil
                    $('#editForm')[0].reset();
                    // Sembunyikan modal edit
                    $('#editModal').modal('hide');
                    // Refresh data pada datatable
                    fetchDatatable();
                });
        } catch (error) {
            Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
        }
    });


    // Delete Data
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
                    const response = await fetch(`${baseUrl}admin/sekolah/hapus/${id}`, {
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
