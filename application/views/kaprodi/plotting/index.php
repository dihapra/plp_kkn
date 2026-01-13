<div class="pt-4">
    <div class="card shadow-sm ">
        <div class="card-header">
            <h5 class="mb-1">Plotting Mahasiswa</h5>
            <p class="mb-0 text-muted">Gunakan menu aksi di kolom terakhir untuk menambah atau mengubah plotting.</p>
        </div>
        <div class="card-body ">
            <div class="row g-2 mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-2">
                            <p class="text-muted small mb-1">Dosen belum diplotting</p>
                            <h6 class="mb-0" id="unassignedDosenCount">0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body py-2">
                            <p class="text-muted small mb-1">Mahasiswa belum diplotting</p>
                            <h6 class="mb-0" id="unassignedStudentCount">0</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-3 pt-4">
                <button class="btn btn-primary btn-sm" id="btnOpenModal">
                    <i class="bi bi-plus-circle me-1"></i>Plotting Baru
                </button>
                <button class="btn btn-outline-secondary btn-sm" id="btnReloadData">
                    <i class="bi bi-arrow-repeat me-1"></i>Muat ulang data
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle" id="kaprodiPlottingTable">
                    <thead>
                        <tr>
                            <th style="width: 26%;">Dosen</th>
                            <th style="width: 26%;">Sekolah</th>
                            <th style="width: 42%;">Mahasiswa</th>
                            <th style="width: 6%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <small class="text-muted d-block mt-3">Data diambil dari program aktif yang sedang berjalan.</small>
        </div>
    </div>
</div>
<div class="modal fade" id="plottingModal" tabindex="-1" aria-labelledby="plottingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="plottingModalLabel">Plotting Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="plottingForm">
                <div class="modal-body">
                    <input type="hidden" id="plottingRowIndex" value="-1">
                    <input type="hidden" id="plottingCurrentDosenId" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Dosen Pembimbing</label>
                            <select class="form-select" id="modalDosen" required>
                                <option value="">Pilih dosen...</option>
                            </select>
                            <small class="text-muted d-block mt-1" id="modalDosenInfo">Kuota dosen akan tampil di
                                sini.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sekolah Mitra</label>
                            <select class="form-select" id="modalSchool" required>
                                <option value="">Pilih sekolah...</option>
                            </select>
                            <small class="text-muted d-block mt-1" id="modalSchoolInfo">Informasi sekolah akan
                                tampil di sini.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Daftar Mahasiswa</label>
                            <select class="form-select" id="modalStudents" multiple size="6" required>
                            </select>
                            <small class="text-muted d-block mt-1">Pilih minimal 5 dan maksimal 13 mahasiswa sesuai prodi.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Plotting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        let dosenList = [];
        let schoolList = [];
        let studentList = [];
        let plottingRows = [];

        const modalEl = document.getElementById('plottingModal');
        const $modalEl = $('#plottingModal');
        const $selectDosen = $('#modalDosen');
        const $selectSchool = $('#modalSchool');
        const $selectStudents = $('#modalStudents');
        let modalInstance = null;

        $selectDosen.select2({
            dropdownParent: $modalEl,
            placeholder: 'Pilih dosen...',
            width: '100%',
            allowClear: true
        });

        $selectSchool.select2({
            dropdownParent: $modalEl,
            placeholder: 'Pilih sekolah...',
            width: '100%',
            allowClear: true
        });

        $selectStudents.select2({
            dropdownParent: $modalEl,
            placeholder: 'Pilih mahasiswa...',
            width: '100%',
            closeOnSelect: false
        });

        function ensureModalInstance() {
            if (!modalInstance && modalEl && window.bootstrap && bootstrap.Modal) {
                modalInstance = new bootstrap.Modal(modalEl, { backdrop: 'static' });
            }
            return modalInstance;
        }

        const table = $('#kaprodiPlottingTable').DataTable({
            data: [],
            ordering: false,
            columns: [
                { data: 'dosen', orderable: false },
                { data: 'sekolah', orderable: false },
                { data: 'mahasiswa', orderable: false },
                { data: 'actions', orderable: false, className: 'text-center' }
            ]
        });

        function formatRows(rows) {
            return rows.map(function (row, index) {
                const dosen = dosenList.find(function (d) { return d.id === row.dosen_id; }) || null;
                const school = schoolList.find(function (s) { return s.id === row.school_id; }) || null;
                const studentNames = (row.students || [])
                    .map(function (student) {
                        return '<li>' + student.nama + '</li>';
                    })
                    .join('');

                const dosenTerisi = dosen && dosen.kuota ? dosen.kuota.terisi : 0;
                const dosenTotal = dosen && dosen.kuota ? dosen.kuota.total : 0;
                const dosenHtml = `
                    <div>
                        <strong>${dosen ? dosen.nama : '-'}</strong>
                    </div>
                `;
                const sekolahHtml = `
                    <div>
                        <strong>${school ? school.nama : '-'}</strong>
                    </div>
                `;
                const mahasiswaHtml = studentNames
                    ? '<ul class="mb-0">' + studentNames + '</ul>'
                    : '<span class="text-muted">Belum ada mahasiswa</span>';

                const actionHtml = `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item action-edit" href="#" data-index="${index}">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i>Plotting
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger action-delete" href="#" data-index="${index}">
                                    <i class="bi bi-trash me-2"></i>Hapus
                                </a>
                            </li>
                        </ul>
                    </div>
                `;

                return {
                    dosen: dosenHtml,
                    sekolah: sekolahHtml,
                    mahasiswa: mahasiswaHtml,
                    actions: actionHtml
                };
            });
        }

        function refreshTable() {
            table.clear().rows.add(formatRows(plottingRows)).draw();
        }

        function updateStatusCards(counts) {
            const hasCounts = counts
                && Object.prototype.hasOwnProperty.call(counts, 'unassigned_dosen')
                && Object.prototype.hasOwnProperty.call(counts, 'unassigned_mahasiswa');

            if (hasCounts) {
                $('#unassignedDosenCount').text(Number(counts.unassigned_dosen) || 0);
                $('#unassignedStudentCount').text(Number(counts.unassigned_mahasiswa) || 0);
                return;
            }

            const usedDosenIds = getUsedDosenIds();
            const usedStudentIds = getUsedStudentIds();
            let unassignedDosen = 0;
            let unassignedStudents = 0;

            dosenList.forEach(function (dosen) {
                if (!usedDosenIds.has(dosen.id)) {
                    unassignedDosen++;
                }
            });

            studentList.forEach(function (student) {
                if (!usedStudentIds.has(student.id)) {
                    unassignedStudents++;
                }
            });

            $('#unassignedDosenCount').text(unassignedDosen);
            $('#unassignedStudentCount').text(unassignedStudents);
        }

        function buildOptions($select, items, placeholder) {
            $select.empty();
            $select.append(new Option(placeholder, ''));
            items.forEach(function (item) {
                const option = new Option(item.text, item.value, false, false);
                if (item.extra) {
                    Object.keys(item.extra).forEach(function (key) {
                        $(option).attr('data-' + key, item.extra[key]);
                    });
                }
                $select.append(option);
            });
        }

        function getUsedDosenIds() {
            return new Set(plottingRows.map(function (row) { return row.dosen_id; }));
        }

        function getUsedStudentIds() {
            const ids = new Set();
            plottingRows.forEach(function (row) {
                (row.student_ids || []).forEach(function (id) { ids.add(id); });
            });
            return ids;
        }

        function updateDosenOptions(currentDosenId) {
            const usedDosenIds = getUsedDosenIds();
            const items = dosenList
                .filter(function (dosen) {
                    return !usedDosenIds.has(dosen.id) || dosen.id === currentDosenId;
                })
                .map(function (dosen) {
                    return {
                        value: String(dosen.id),
                        text: dosen.nama + (dosen.prodi ? ' (' + dosen.prodi + ')' : ''),
                    };
                });
            buildOptions($selectDosen, items, 'Pilih dosen...');
        }

        function updateSchoolOptions() {
            const items = schoolList.map(function (school) {
                return {
                    value: String(school.id),
                    text: school.nama
                };
            });
            buildOptions($selectSchool, items, 'Pilih sekolah...');
        }

        function updateStudentOptions(dosenId, selectedIds) {
            const dosen = dosenList.find(function (d) { return d.id === dosenId; });
            const prodi = dosen ? dosen.prodi : null;
            const usedStudentIds = getUsedStudentIds();
            const selectedSet = new Set(selectedIds || []);

            const items = studentList
                .filter(function (student) {
                    if (usedStudentIds.has(student.id) && !selectedSet.has(student.id)) {
                        return false;
                    }
                    if (prodi && student.prodi !== prodi) {
                        return false;
                    }
                    return true;
                })
                .map(function (student) {
                    return {
                        value: String(student.id),
                        text: student.nama + (student.prodi ? ' (' + student.prodi + ')' : ''),
                        extra: { prodi: student.prodi || '' }
                    };
                });

            $selectStudents.empty();
            items.forEach(function (item) {
                const option = new Option(item.text, item.value, selectedSet.has(Number(item.value)), selectedSet.has(Number(item.value)));
                if (item.extra) {
                    Object.keys(item.extra).forEach(function (key) {
                        $(option).attr('data-' + key, item.extra[key]);
                    });
                }
                $selectStudents.append(option);
            });
            $selectStudents.trigger('change.select2');

            if (dosen) {
                $('#modalDosenInfo').text(dosen.nama + ' -> Kuota ' + dosen.kuota.terisi + '/' + dosen.kuota.total);
            } else {
                $('#modalDosenInfo').text('Kuota dosen akan tampil di sini.');
            }
        }

        function updateSchoolInfo(schoolId) {
            const school = schoolList.find(function (s) { return s.id === schoolId; });
            if (school) {
                $('#modalSchoolInfo').text(school.nama);
            } else {
                $('#modalSchoolInfo').text('Informasi sekolah akan tampil di sini.');
            }
        }

        function openModal(index) {
            $('#plottingRowIndex').val(index);
            $('#plottingCurrentDosenId').val('');
            $('#plottingForm')[0].reset();

            let selectedStudents = [];
            let currentDosenId = null;
            let currentSchoolId = null;

            if (index >= 0 && plottingRows[index]) {
                const row = plottingRows[index];
                currentDosenId = row.dosen_id;
                currentSchoolId = row.school_id;
                selectedStudents = row.student_ids || [];
                $('#plottingCurrentDosenId').val(currentDosenId);
            }

            updateDosenOptions(currentDosenId);
            updateSchoolOptions();
            updateStudentOptions(currentDosenId, selectedStudents);

            $selectDosen.val(currentDosenId ? String(currentDosenId) : '').trigger('change');
            $selectSchool.val(currentSchoolId ? String(currentSchoolId) : '').trigger('change');
            $selectStudents.val(selectedStudents.map(function (id) { return String(id); })).trigger('change');

            if (!currentDosenId) {
                updateStudentOptions(null, []);
                updateSchoolInfo(null);
            }

            const instance = ensureModalInstance();
            if (instance) {
                instance.show();
            } else {
                console.warn('Bootstrap modal belum terinisialisasi.');
            }
        }

        $selectDosen.on('change', function () {
            const value = $(this).val();
            const selectedStudents = ($selectStudents.val() || []).map(function (id) { return Number(id); });
            updateStudentOptions(value ? Number(value) : null, selectedStudents);
        });

        $selectSchool.on('change', function () {
            const value = $(this).val();
            updateSchoolInfo(value ? Number(value) : null);
        });

        $('#plottingForm').on('submit', function (e) {
            e.preventDefault();
            const dosenVal = $selectDosen.val();
            const schoolVal = $selectSchool.val();
            const dosenId = dosenVal ? Number(dosenVal) : 0;
            const schoolId = schoolVal ? Number(schoolVal) : 0;
            const studentIds = ($selectStudents.val() || []).map(function (id) { return Number(id); });
            if (!dosenId || !schoolId || studentIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap',
                    text: 'Pilih dosen, sekolah, dan minimal 5 mahasiswa.'
                });
                return;
            }
            if (studentIds.length < 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mahasiswa kurang',
                    text: 'Plotting membutuhkan minimal 5 mahasiswa.'
                });
                return;
            }
            if (studentIds.length > 13) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mahasiswa terlalu banyak',
                    text: 'Plotting maksimal 13 mahasiswa.'
                });
                return;
            }
            const currentDosenId = $('#plottingCurrentDosenId').val();

            savePlotting({
                dosen_id: dosenId,
                school_id: schoolId,
                student_ids: studentIds,
                current_dosen_id: currentDosenId
            });
        });

        $('#kaprodiPlottingTable').on('click', '.action-edit', function (e) {
            e.preventDefault();
            const index = Number($(this).data('index'));
            openModal(index);
        });

        $('#kaprodiPlottingTable').on('click', '.action-delete', function (e) {
            e.preventDefault();
            const index = Number($(this).data('index'));
            const row = plottingRows[index];
            if (!row) {
                return;
            }
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Plotting?',
                text: `Plotting dosen "${row.dosen_nama}" akan dihapus.`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }
                deletePlotting(row.dosen_id);
            });
        });

        $('#btnOpenModal').on('click', function () {
            openModal(-1);
        });

        $('#btnReloadData').on('click', function () {
            loadPlottingData(true);
        });

        async function loadPlottingData(showToast) {
            try {
                const response = await fetch(`${baseUrl}kaprodi/plotting/data`, {
                    method: 'POST'
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal memuat data plotting.');
                }

                dosenList = result?.data?.dosen || [];
                schoolList = result?.data?.sekolah || [];
                studentList = result?.data?.mahasiswa || [];
                plottingRows = result?.data?.rows || [];

                refreshTable();
                updateStatusCards(result?.data?.counts || null);

                if (showToast) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Dimuat',
                        text: 'Data plotting berhasil dimuat.',
                        timer: 1200,
                        showConfirmButton: false
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        async function savePlotting(payload) {
            try {
                const formData = new FormData();
                formData.append('dosen_id', payload.dosen_id);
                formData.append('school_id', payload.school_id);
                (payload.student_ids || []).forEach(function (id) {
                    formData.append('student_ids[]', id);
                });
                if (payload.current_dosen_id) {
                    formData.append('current_dosen_id', payload.current_dosen_id);
                }

                const response = await fetch(`${baseUrl}kaprodi/plotting/store`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal menyimpan plotting.');
                }

                const instance = ensureModalInstance();
                if (instance) {
                    instance.hide();
                }

                await loadPlottingData();

                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan',
                    text: result?.message || 'Plotting berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        async function deletePlotting(dosenId) {
            try {
                const formData = new FormData();
                formData.append('dosen_id', dosenId);

                const response = await fetch(`${baseUrl}kaprodi/plotting/delete`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (!response.ok) {
                    throw new Error(result?.message || 'Gagal menghapus plotting.');
                }

                await loadPlottingData();

                Swal.fire({
                    icon: 'success',
                    title: 'Terhapus',
                    text: result?.message || 'Plotting berhasil dihapus.',
                    timer: 1500,
                    showConfirmButton: false
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan.'
                });
            }
        }

        loadPlottingData();
    });
</script>
