$(document).ready(function () {
    let schoolId = $('#sekolah_filter').val();

    // Fungsi untuk inisialisasi DataTable
    $('#sekolah_filter').on('change', function () {
        schoolId = $(this).val();
        fetchDatatable();
    })

    async function fetchDatatable() {
        try {
            const url = baseUrl + 'admin/datatable/guru';
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
                        d.school = schoolId
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
                    { data: 'phone', "orderable": true },
                    { data: 'school_name', "orderable": true },
                    { data: 'bank', "orderable": true },
                    { data: 'account_number', "orderable": true },
                    {
                        data: null,
                        defaultContent: "",
                        render: function (data, type, row) {
                            // Tampilkan tombol delete hanya jika user_role adalah super_admin
                            let deleteButton = "";
                            let editButton = "";
                            if (row.user_role === "super_admin") {
                                deleteButton = `<button class="btn btn-sm btn-danger delete" data-id="${row.id}" data-name="${row.name}">Delete</button>`;
                            }
                            if (row.user_role === "super_admin" || row.user_role === 'admin') {
                                editButton = `<button class="btn btn-sm btn-primary edit" data-row='${JSON.stringify(row)}'">Edit</button>`;
                            }
                            return `
                                <div class="d-flex flex-column" style="gap:5px">
                                    ${editButton}
                                    ${deleteButton}
                                </div>
                            `;
                        }
                    }

                ]

            });

            // Edit Modal
            $('#dataTable').on('click', '.edit', async function () {
                const data = $(this).data('row');
                $('#editModal #teacherId').val(data.id);
                const $schoolSelect = $('#editModal .school-select');
                if ($schoolSelect.length) {
                    if ($schoolSelect.find(`option[value="${data.school_id}"]`).length === 0 && data.school_id) {
                        $schoolSelect.append(new Option(data.school_name, data.school_id, true, true));
                    }
                    $schoolSelect.val(data.school_id).trigger('change');
                }
                $('#editModal #name').val(data.name);
                $('#editModal #email').val(data.email);
                $('#editModal #phone').val(data.phone);
                $('#editModal #status').val(data.status);
                $('#editModal #nik').val(data.nik);
                // $('#editModal #principal').val(data.principal);
                $('#editModal #bank').val(data.bank);
                $('#editModal #accountNumber').val(data.account_number);
                $('#editModal #accountName').val(data.account_name);
                $('#editModal #imgBook').attr('src', baseUrl + data?.book);
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
            const response = await fetch(`${baseUrl}admin/guru/simpan`, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error('Gagal menyimpan data.');
            const result = await response.json();
            Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success');
            $('#createForm')[0].reset();
            $('#createForm .school-select').val(null).trigger('change');
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
            const id = $('#teacherId').val();
            const formData = new FormData(this);
            const response = await fetch(`${baseUrl}admin/guru/update/${id}`, {
                method: 'POST',
                body: formData
            });
            const result = await response.json().catch(() => ({}));
            if (!response.ok) {
                const message = result?.message || 'Gagal memperbarui data.';
                throw new Error(message);
            }
            Swal.fire('Berhasil!', result?.message || 'Data berhasil diperbarui.', 'success');
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
                    const response = await fetch(`${baseUrl}admin/guru/hapus/${id}`, {
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
