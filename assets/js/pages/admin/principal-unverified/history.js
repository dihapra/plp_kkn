$(document).ready(function () {
    // ========= STATE =========
    const $datatable = $("#dataTable");
    const form = $("#verify-form");
    const modal = $("#verif-modal");

    const datatableUrl = 'admin/histori/data/kepsek'; // history endpoint
    let principalQueue = [];   // berisi row object dari DataTables
    let currentIdx = -1;       // index aktif di queue

    // Tombol samping & keyboard
    $("#prevBtnSide").on('click', () => navigate(-1));
    $("#nextBtnSide").on('click', () => navigate(1));
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

            const dt = $datatable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url,
                    type: 'POST',
                    data: function (d) {
                        d.start = d.start;
                        d.length = d.length;
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
                    oPaginate: {
                        sFirst: 'Pertama', sPrevious: 'Sebelumnya',
                        sNext: 'Selanjutnya', sLast: 'Terakhir'
                    }
                },
                columns: [
                    { data: 'name', orderable: true },
                    { data: 'phone', orderable: true },
                    { data: 'school_name', orderable: true },
                    { data: 'bank', orderable: true },
                    { data: 'account_number', orderable: true },
                    { data: 'verified_by_name', orderable: true },
                    { data: 'updated_at', orderable: true },
                    {
                        data: 'status_data',
                        render: function (data, type, row) {
                            if (type === 'display') {
                                let badgeClass = '';
                                if (data === 'verified') {
                                    badgeClass = 'badge bg-success'; // hijau
                                } else if (data === 'rejected') {
                                    badgeClass = 'badge bg-danger'; // merah
                                } else {
                                    badgeClass = 'badge bg-secondary'; // default abu
                                }
                                return '<span class="' + badgeClass + '">' + data + '</span>';
                            }
                            return data;
                        }
                    },

                    {
                        data: null,
                        defaultContent: '',
                        orderable: false,
                        render: function (_data, _type, row) {
                            // History hanya butuh detail
                            return `
                <div class="d-flex flex-column" style="gap:5px">
                  <button class="btn btn-sm btn-outline-primary detail" data-id="${row.id}">
                    Detail
                  </button>
                </div>`;
                        }
                    }
                ]
            });

            // Handler klik "Detail" → isi modal dari row DataTables (tanpa fetch)
            $datatable.off('click', '.detail').on('click', '.detail', function () {
                const rowData = dt.row($(this).closest('tr')).data();

                // Bangun queue dari halaman & filter aktif (lebih stabil)
                buildQueueFromDT(dt);

                // Posisi index dari baris yang diklik
                currentIdx = principalQueue.findIndex(r => String(r.id) === String(rowData.id));
                if (currentIdx === -1) {
                    principalQueue.push(rowData);
                    currentIdx = principalQueue.length - 1;
                }

                loadPrincipalFromRow(principalQueue[currentIdx]);

                if (!modal.hasClass('show')) modal.modal('show');
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

    // Ambil queue dari DataTables API (halaman & filter aktif)
    function buildQueueFromDT(dt) {
        principalQueue = dt
            .rows({ page: 'current', search: 'applied' })
            .data()
            .toArray();

        // Hilangkan duplikat berdasarkan id (kalau perlu)
        const seen = new Set();
        principalQueue = principalQueue.filter(r => {
            const key = String(r.id);
            if (seen.has(key)) return false;
            seen.add(key);
            return true;
        });

        updateNavUI();
    }

    // ========= MODAL LOAD / NAV =========
    function updateNavUI() {
        const total = principalQueue.length;
        $("#prevBtnSide").prop('disabled', currentIdx <= 0);
        $("#nextBtnSide").prop('disabled', total === 0 || currentIdx >= total - 1);
    }

    function loadPrincipalFromRow(data) {
        // Isi form readonly dari row object
        $("#verify-form [name='name']").val(data.name ?? '');
        $("#verify-form [name='email']").val(data.email ?? '');
        $("#verify-form [name='phone']").val(data.phone ?? '');
        $("#verify-form [name='status']").val(data.status);
        $("#verify-form [name='school_name']").val(data.school_name ?? '');
        $("#verify-form [name='nik']").val(data.nik ?? '');
        $("#verify-form [name='account_number']").val(data.account_number ?? '');
        $("#verify-form [name='bank']").val(data.bank ?? '');
        $("#verify-form [name='account_name']").val(data.account_name ?? '');

        // Gambar (jika server sudah kirim path-nya di row)
        if (data.identification_card) {
            $("#identication-card").attr('src', '/' + data.identification_card);
        } else {
            $("#identication-card").attr('src', '');
        }
        if (data.book) {
            $("#book").attr('src', '/' + data.book);
        } else {
            $("#book").attr('src', '');
        }

        // Pastikan readonly
        $("#verify-form").find('input, textarea, select').prop('disabled', true);

        updateNavUI();
    }

    function navigate(delta) {
        const newIdx = currentIdx + delta;
        if (newIdx < 0 || newIdx >= principalQueue.length) return;
        currentIdx = newIdx;
        loadPrincipalFromRow(principalQueue[currentIdx]);
    }

    // ========= START =========
    fetchDatatable();
});
