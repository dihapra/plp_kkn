<div class="container mt-4">
    <h3 class="mb-4">Aktivitas guru</h3>
    <div class="d-flex m-2" style="gap:10px">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            Tambah
        </button>
    </div>
    <table id="activityTable" class="table table-bordered table-striped table-hover w-100">
        <thead class="table-dark">
            <tr>
                <th>Pertemuan</th>
                <th>Tanggal Dibuka</th>
                <th>Tanggal Ditutup</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTable akan mengisi tbody ini -->
        </tbody>
    </table>
</div>

<style>
    #activityTable .fit-content-column {
        white-space: nowrap;
        width: 1%;
    }

    #activityTable .description-column {
        width: 50%;
        white-space: normal;
        word-break: break-word;
    }
</style>

<!-- Modal Deskripsi -->
<div class="modal fade" id="deskripsiModal" tabindex="-1" aria-labelledby="deskripsiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deskripsiModalLabel">Deskripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="deskripsiContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="activityId" name="activityId">
                    <?php $this->load->view('forms/admin/activity', ['mode' => 'edit']) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm">
                <div class="modal-body">
                    <?php $this->load->view('forms/admin/activity', ['mode' => 'create']) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quill CSS -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<!-- Quill JS -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>


<script>
    // DataTables Initialization
    $(document).ready(function() {
        initActivityTable();

    })

    let activityTable;

    function initActivityTable() {
        if ($.fn.DataTable.isDataTable("#activityTable")) {
            $("#activityTable").DataTable().destroy();
        }
        activityTable = $("#activityTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: `${baseUrl}admin/datatable/teacher_activity`,
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
                    data: "meeting",
                    render: (data) => `Pertemuan ${data}`,
                    className: "fit-content-column"
                },
                {
                    data: "start_date",
                    defaultContent: "-",
                    className: "fit-content-column"
                },
                {
                    data: "end_date",
                    defaultContent: "-",
                    className: "fit-content-column"
                },
                {
                    data: "description",
                    defaultContent: "-",
                    className: "description-column"
                },
                {
                    data: null,
                    title: "Aksi",
                    orderable: false,
                    className: "fit-content-column",
                    render: (data, row) => `
                <button class="btn btn-info btn-sm lihat-deskripsi" data-row='${data.description}'>Lihat Deskripsi</button>
                <button class="btn btn-primary btn-sm edit-tugas" data-id="${data.id}">Edit</button>
            `,
                },
            ],
        });
    }

    // Modal Instances
    const deskripsiModal = $("#deskripsiModal");
    const editModal = $("#editModal");
    const editQuill = new Quill("#editDescription", {
        theme: "snow"
    });
    const createQuill = new Quill("#createDescription", {
        theme: "snow"
    });
    $('#editModal').on('shown.bs.modal', function() {
        if (!editQuill) {
            editQuill = new Quill("#editDescription", {
                theme: "snow"
            });
        }
    });

    $('#createModal').on('shown.bs.modal', function() {
        if (!createQuill) {
            createQuill = new Quill("#createDescription", {
                theme: "snow"
            });
        }
    });

    // Lihat Deskripsi
    $("#activityTable tbody").on("click", ".lihat-deskripsi", async function() {
        const description = $(this).data("row");
        console.log(description)
        try {
            document.getElementById("deskripsiContent").innerHTML = description || "Deskripsi tidak tersedia.";
            deskripsiModal.modal('show');
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
            });
        }
    });

    // Edit
    $("#activityTable tbody").on("click", ".edit-tugas", async function() {
        const id = $(this).data("id");
        try {
            const response = await fetch(`${baseUrl}admin/aktifitas/guru/edit/${id}`);
            const responsedata = await response.json();
            const data = responsedata.data
            document.getElementById("activityId").value = id;
            document.getElementById("meeting").value = data.meeting;
            document.getElementById("startDate").value = data.start_date;
            document.getElementById("endDate").value = data.end_date;
            editQuill.root.innerHTML = data.description || "";
            editModal.modal('show');
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
            });
        }
    });

    // Submit Edit Form
    $("#editForm").on("submit", async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = formData.get("activityId");
        formData.append("description", editQuill.root.innerHTML);
        try {
            const response = await fetch(`${baseUrl}admin/aktifitas/guru/update/${id}`, {
                method: "POST",
                body: formData,
            });
            if (!response.ok) throw new Error("Gagal memperbarui data");
            Swal.fire('Berhasil!', 'Data berhasil diperbarui.', 'success');
            editModal.modal('hide');
            activityTable.ajax.reload(null, false);
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
            });
        }
    });

    // Submit Create Form
    $("#createForm").on("submit", async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("description", createQuill.root.innerHTML);
        try {
            const response = await fetch(`${baseUrl}admin/aktifitas/guru/insert`, {
                method: "POST",
                body: formData,
            });
            if (!response.ok) throw new Error("Gagal menyimpan data");
            Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success');
            this.reset();
            $("#createModal").modal('hide');
            createQuill.root.innerHTML = ''
            activityTable.ajax.reload(null, false);
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: `Terjadi kesalahan: ${error.message}`,
            });
        }
    });
</script>
