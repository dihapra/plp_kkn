$(document).ready(function () {
    // ========= STATE =========
    const $schoolFilter = $('#sekolah_filter');
    const $datatable = $('#dataTable');
    const form = $('#verify-form');
    const modal = $('#verif-modal');

    const datatableUrl = 'admin/datatable/guru/unverified';
    let schoolId = $schoolFilter.val();
    let teacherId = null;             // id aktif di modal
    let teacherQueue = [];               // array string id untuk navigasi
    let currentIdx = -1;               // posisi id aktif dalam queue

    // ========= INIT / EVENTS GLOBAL =========
    $schoolFilter.on('change', function () {
        schoolId = $(this).val();
        fetchDatatable();
    });

    // Tombol samping & keyboard
    $('#prevBtnSide').on('click', () => navigate(-1));
    $('#nextBtnSide').on('click', () => navigate(1));
    modal.on('shown.bs.modal', function () {
        $(document).on('keydown.verifyNav', (e) => {
            if (e.key === 'ArrowLeft') { e.preventDefault(); navigate(-1); }
            if (e.key === 'ArrowRight') { e.preventDefault(); navigate(1); }
        });
    }).on('hidden.bs.modal', function () {
        $(document).off('keydown.verifyNav');
    });

    // ========= DATATABLE =========
    async function fetchDatatable() {
        try {
            const url = baseUrl + datatableUrl;
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $datatable.DataTable().clear().destroy();
            }

            $datatable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url,
                    type: 'POST',
                    data: function (d) {
                        d.start = d.start;
                        d.length = d.length;
                        d.school = schoolId;
                    }
                },
                language: {
                    sProcessing: 'Sedang memproses...',
                    sLengthMenu: 'Tampilkan _MENU_ data',
                    sZeroRecords: 'Tidak ditemukan data yang sesuai',
                    sInfo: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    sInfoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    sInfoFiltered: '(disaring dari _MAX_ data keseluruhan)',
                    sSearch: 'Cari:',
                    oPaginate: { sFirst: 'Pertama', sPrevious: 'Sebelumnya', sNext: 'Selanjutnya', sLast: 'Terakhir' }
                },
                columns: [
                    { data: 'name', orderable: true },
                    { data: 'phone', orderable: true },
                    { data: 'school_name', orderable: true },
                    { data: 'bank', orderable: true },
                    { data: 'account_number', orderable: true },
                    {
                        data: null,
                        defaultContent: '',
                        render: function (data, type, row) {
                            let verifButton = '';
                            if (row.user_role === 'super_admin' || row.user_role === 'admin') {
                                verifButton = `<button class="btn btn-sm btn-primary verif" data-id="${row.id}">Verif</button>`;
                            }
                            return `<div class="d-flex flex-column" style="gap:5px">${verifButton}</div>`;
                        }
                    }
                ]
            });

            // Handler klik tombol "Verif" (entry point buka modal)
            $datatable.off('click', '.verif').on('click', '.verif', async function () {
                const id = String($(this).data('id'));

                // Bangun queue dari DataTables (page current + search applied)
                buildQueueFromDT();

                // Tentukan index id yang diklik
                currentIdx = teacherQueue.indexOf(id);
                if (currentIdx === -1) {
                    teacherQueue.push(id);
                    currentIdx = teacherQueue.length - 1;
                }

                await loadTeacher(teacherQueue[currentIdx]);
                // Buka modal hanya jika belum terbuka
                if (!modal.hasClass('show')) modal.modal('show');
            });

        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: `Terjadi kesalahan: ${error.message}`, confirmButtonText: 'OK' });
        }
    }

    // Ambil queue dari DataTables API (lebih stabil daripada dari DOM)
    function buildQueueFromDT() {
        const dt = $datatable.DataTable();
        teacherQueue = dt
            .rows({ page: 'current', search: 'applied' })
            .data()
            .toArray()
            .filter(row => row.user_role === 'super_admin')
            .map(row => String(row.id));

        teacherQueue = [...new Set(teacherQueue)];
        // console.log('queue from DT:', teacherQueue);
        updateNavUI();
    }

    // ========= MODAL LOAD / NAV =========
    function updateNavUI() {
        const total = teacherQueue.length;
        $('#prevBtnSide').prop('disabled', currentIdx <= 0);
        $('#nextBtnSide').prop('disabled', total === 0 || currentIdx >= total - 1);
    }

    async function loadTeacher(id) {
        const data = await getTeacherData(id);
        teacherId = String(data.id);

        // Isi form readonly
        $('#verify-form [name="name"]').val(data.name);
        $('#verify-form [name="email"]').val(data.email);
        $('#verify-form [name="phone"]').val(data.phone);
        $('#verify-form [name="school_name"]').val(data.school_name);
        $('#verify-form [name="prodi"]').val(data.student_prodi ?? '');
        $("#verify-form [name='status']").val(data.status);
        $('#verify-form [name="mahasiswa"]').val(data.student_name ?? '');
        $('#verify-form [name="nik"]').val(data.nik);
        $('#verify-form [name="account_number"]').val(data.account_number);
        $('#verify-form [name="bank"]').val(data.bank);
        $('#verify-form [name="account_name"]').val(data.account_name);
        $('#identication-card').attr('src', '/' + data.identification_card);
        $('#book').attr('src', '/' + data.book);

        const editableFields = new Set(['nik', 'account_number', 'bank', 'account_name']);
        // Kunci semua field
        $('#verify-form')
            .find('input, textarea, select')
            .each(function () {
                const el = this;
                const type = (el.type || '').toLowerCase();
                const tag = el.tagName.toLowerCase();

                // elemen yang tetap boleh diisi (pesan gagal) DIKUNCI belakangan
                if (el.name === 'fail_message') return;
                if (editableFields.has(el.name)) {
                    $(el).prop('disabled', false).prop('readonly', false);
                    return;
                }

                if (tag === 'select' || type === 'file' || type === 'checkbox' || type === 'radio') {
                    $(el).prop('disabled', true);       // elemen non-teks → disabled
                } else {
                    $(el).prop('readonly', true);       // input teks & textarea → readonly
                }
            });

        // Pastikan pesan gagal dapat diisi
        $('#verify-form [name="fail_message"]').prop('disabled', false).prop('readonly', false).val('');

        // (opsional) matikan autocomplete supaya nggak “terkesan bisa diketik”
        $('#verify-form input[type="text"], #verify-form input[type="email"]').attr('autocomplete', 'off');

        updateNavUI();
    }

    async function navigate(delta) {
        const newIdx = currentIdx + delta;
        if (newIdx < 0 || newIdx >= teacherQueue.length) return;
        currentIdx = newIdx;
        await loadTeacher(teacherQueue[currentIdx]);
    }

    async function removeCurrentAndAdvance() {
        if (currentIdx < 0 || currentIdx >= teacherQueue.length) return;

        // Hapus id aktif dari queue
        teacherQueue.splice(currentIdx, 1);

        if (teacherQueue.length === 0) {
            // Queue habis → tutup modal & refresh tabel
            modal.modal('hide');
            fetchDatatable();
            return;
        }

        // Geser index: jika tadi di item terakhir, mundur satu
        if (currentIdx >= teacherQueue.length) currentIdx = teacherQueue.length - 1;

        // Load item berikutnya tanpa tutup modal
        await loadTeacher(teacherQueue[currentIdx]);

        // Opsional: tandai baris sukses di tabel
        $(`#dataTable .verif[data-id="${teacherId}"]`).closest('tr').addClass('table-success');
    }

    // ========= AJAX DETAIL =========
    async function getTeacherData(id) {
        try {
            const resp = await fetch(baseUrl + 'admin/unverified-teacher/' + id);
            if (!resp.ok) throw new Error('Gagal mengambil detail guru.');
            const result = await resp.json();
            return result.data;
        } catch (error) {
            await Swal.fire({ icon: 'error', title: 'Oops...', text: error.message || 'Terjadi kesalahan.', confirmButtonText: 'OK' });
            throw error;
        }
    }

    // ========= SUBMIT VERIFIKASI =========
    form.off('submit').on('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const confirm = await Swal.fire({
            title: 'Konfirmasi Verifikasi',
            text: 'Apakah Anda yakin ingin memverifikasi guru ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Verifikasi',
            cancelButtonText: 'Batal'
        });
        if (!confirm.isConfirmed) return;

        try {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`${baseUrl}admin/validate_teacher/${teacherId}`, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error('Gagal memperbarui data.');

            await Swal.fire('Berhasil!', 'Data berhasil diverifikasi.', 'success');

            // Inti: jangan tutup modal — hapus id dari queue & auto-next
            await removeCurrentAndAdvance();

            // Refresh tabel di belakang (boleh async)
            fetchDatatable();

        } catch (error) {
            Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
        }
    });

    // ========= TOMBOL REJECT =========
    $('#btnFail').on('click', async () => {
        const message = $('#fail_message').val().trim();
        if (message.length === 0) {
            alert("Mohon isi pesan gagal verifikasi.");
            return;
        }
        const formData = new FormData();
        formData.append('fail_message', message);

        const confirm = await Swal.fire({
            title: 'Konfirmasi Verifikasi',
            text: 'Apakah Anda yakin ingin menolak verifikasi guru ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        });
        if (!confirm.isConfirmed) return;

        try {
            // Tampilkan loading
            Swal.fire({
                title: 'Mengirim...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`${baseUrl}admin/reject_teacher/${teacherId}`, {
                method: 'POST',
                body: formData
            });
            if (!response.ok) throw new Error('Gagal memperbarui data.');

            await Swal.fire('Berhasil!', 'Data berhasil ditolak.', 'success');

            // Inti: jangan tutup modal — hapus id dari queue & auto-next
            await removeCurrentAndAdvance();

            // Refresh tabel di belakang (boleh async)
            fetchDatatable();

        } catch (error) {
            Swal.fire('Gagal!', error.message || 'Terjadi kesalahan.', 'error');
        }
    });

    // ========= START =========
    fetchDatatable();
});
