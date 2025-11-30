$(document).ready(function () {
    let fakultas = $('#fakultas_filter').val();
    let prodi = $('#prodi_filter').val();
    $('#fakultas_filter').on('change', function () {
        fakultas = $(this).val(); // Mendapatkan nilai dari elemen yang berubah
        fetchDatatable();
    });

    $('#prodi_filter').on('change', function () {
        prodi = $(this).val();
        fetchDatatable();
    });
    // Fungsi untuk inisialisasi DataTable
    async function fetchDatatable() {
        try {
            const url = baseUrl + 'admin/datatable/dosen';
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().clear().destroy();
            }
            $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": url,
                    "type": "POST",
                    "data": function (d) {
                        d.start = d.start;
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
                    { data: 'nip', "orderable": true },
                    { data: 'phone', "orderable": true },
                    { data: 'prodi', "orderable": true },
                    { data: 'fakultas', "orderable": true },
                    {
                        data: null,
                        "defaultContent": "",
                        render: function (data, type, row) {
                            return `
                                <div class="d-flex flex-column" style="gap:5px">
                                    <button class="btn btn-sm btn-primary edit" data-id="${row.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete" data-id="${row.id}" data-name="${row.name}">Delete</button>
                                </div>
                            `;
                        }
                    }
                ]

            });

            // Edit Modal
            $('#dataTable').on('click', '.edit', async function () {
                const id = $(this).data('id');
                const data = await show(id); // Panggil fungsi show dan tunggu hasilnya
                if (!data) {
                    Swal.fire('Error', 'Data tidak ditemukan. Silakan coba lagi.', 'error');
                    return;
                }
                $('#editModal #lectureId').val(data.id);
                $('#editModal #name').val(data.name);
                $('#editModal #nip').val(data.nip);
                $('#editModal #phone').val(data.phone);
                $('#editModal #email').val(data.email);
                $('#editModal #fakultas').val(data.fakultas).trigger('change');

                setTimeout(function () {
                    $('#editModal #prodi').val(data.prodi);
                }, 100);

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

    async function show(id) {
        try {
            const resp = await fetch(`${baseUrl}admin/dosen/edit/${id}`);
            if (!resp.ok) {
                throw new Error('Gagal mengambil data dari server.');
            }
            const result = await resp.json();
            return result.data; // Mengembalikan hasil JSON
        } catch (error) {
            Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
            return null; // Mengembalikan null jika terjadi error
        }
    }

    // Submit Create Form
    $('#createForm').on('submit', async function (e) {
        e.preventDefault();
        try {
            const formData = new FormData(this);
            const response = await fetch(`${baseUrl}admin/dosen/simpan`, {
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
            const id = $('#lectureId').val();
            const formData = new FormData(this);
            const response = await fetch(`${baseUrl}admin/dosen/update/${id}`, {
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
    // Import form submission
    $(document).on('submit', '#importForm', async function (e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        // (Opsional) kalau pakai CSRF CI:
        // formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');

        // Disable tombol submit biar ga dobel klik
        const submitBtn = $(form).find('button[type="submit"]');
        submitBtn.prop('disabled', true);

        // Tampilkan loading
        Swal.fire({
            title: 'Mengimpor...',
            text: 'Mohon tunggu, file sedang diproses.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const res = await fetch(`${baseUrl}admin/dosen/import`, {
                method: 'POST',
                body: formData
                // jangan set content-type; biar browser set multipart boundary
            });

            // Cek HTTP status via response.ok
            // ok = true untuk 2xx
            let payload = null;
            const ct = res.headers.get('content-type') || '';
            if (ct.includes('application/json')) {
                // kalau server balas JSON
                payload = await res.json();
            } else {
                // fallback text/plain atau HTML error
                payload = { message: await res.text() };
            }

            if (res.ok) {
                // Sukses (2xx)
                await Swal.fire('Berhasil!', (payload && payload.message) || 'Import berhasil.', 'success');
                $('#importModal').modal('hide');
                $('#lecturerTable').DataTable().ajax.reload(null, false);
                form.reset();
            } else {
                // Gagal (4xx/5xx)
                await Swal.fire('Gagal!', (payload && payload.message) || `Import gagal (HTTP ${res.status}).`, 'error');
            }
        } catch (err) {
            await Swal.fire('Error!', (err && err.message) || 'Terjadi kesalahan jaringan/skrip.', 'error');
        } finally {
            submitBtn.prop('disabled', false);
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
                    const response = await fetch(`${baseUrl}/admin/dosen/delete/${id}`, {
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

$(document).ready(function () {
    $('form #fakultas').on('change', function () {
        const selectedFakultas = $(this).val(); // Ambil nilai fakultas
        const $prodiSelect = $('form #prodi'); // Dropdown Prodi
        // Kosongkan dropdown Prodi
        $prodiSelect.empty();
        $prodiSelect.append('<option value="">Pilih Prodi</option>');
        // Tambahkan opsi Prodi jika fakultas dipilih
        if (selectedFakultas && prodiData[selectedFakultas]) {
            $.each(prodiData[selectedFakultas], function (index, prodi) {
                $prodiSelect.append(`<option value="${prodi}">${prodi}</option>`);
            });
        }
    });
})