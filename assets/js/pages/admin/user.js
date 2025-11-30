$(document).ready(function () {

    async function resetPassword(id) {
        const confirmResult = await Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Tindakan ini akan mereset password pengguna!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Reset',
            cancelButtonText: 'Batal'
        });

        if (confirmResult.isConfirmed) {
            try {
                const response = await fetch(`${baseUrl}/admin/users/reset-password/${id}`, {
                    method: 'POST'
                });

                if (!response.ok) throw new Error('Gagal mereset password.');

                Swal.fire('Berhasil!', 'Password berhasil direset.', 'success');
                $('#dataTable').DataTable().clear().destroy();
                fetchDatatable();
            } catch (error) {
                Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
            }
        } else {
            Swal.fire('Dibatalkan', 'Tindakan reset password dibatalkan.', 'info');
        }
    }

    // Fungsi untuk inisialisasi DataTable
    async function fetchDatatable() {
        try {
            const url = baseUrl + 'admin/datatable/user';

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
                    { data: 'username', "orderable": true },
                    { data: 'email', "orderable": true },
                    { data: 'role', "orderable": true },
                    { data: 'nim', "orderable": true },
                    { data: 'nip', "orderable": true },
                    { data: 'nik', "orderable": true },
                    { data: 'principal_nik', "orderable": true },
                    {
                        data: null,
                        defaultContent: "",
                        render: function (data, type, row) {
                            return `
                                <div class="d-flex flex-column" style="gap:5px">
                                    <a class="btn btn-sm btn-danger reset" data-id="${data.id}">Reset Password</a>
                                </div>
                            `;
                        }
                    }

                ]

            });

            // Edit Modal
            $('#dataTable').on('click', '.reset', async function () {
                const id = $(this).data('id');
                resetPassword(id)
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
            if (!response.ok) throw new Error('Gagal menyimpan data.');
            const result = await response.json();
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
            console.log(formData)
            const response = await fetch(`${baseUrl}admin/sekolah/update/${id}`, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error('Gagal memperbarui data.');
            Swal.fire('Berhasil!', 'Data berhasil diperbarui.', 'success');
            $('#editModal').modal('hide');
            $('#dataTable').DataTable().clear().destroy();
            fetchDatatable();
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
                    const response = await fetch(`${baseUrl}/admin/sekolah/delete/${id}`, {
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
