<div class="container mt-4">
    <h3 class="mb-4">Manajemen Honor</h3>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filterStatus" class="form-label">Filter Status Pembayaran</label>
            <select class="form-select" id="filterStatus">
                <option value="">Semua</option>
                <option value="Sudah Dibayar">Sudah Dibayar</option>
                <option value="Belum Dibayar">Belum Dibayar</option>
            </select>
        </div>
    </div>
    <table id="paymentTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Jenis Honor</th>
                <th>Status</th>
                <th>Tanggal Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows akan diisi oleh DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal Tambah Pembayaran -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel">Tambah Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="paymentName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="paymentName" name="nama"
                            placeholder="Masukkan nama penerima" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentType" class="form-label">Jenis Honor</label>
                        <select class="form-select" id="paymentType" name="jenis_honor" required>
                            <option value="">Pilih jenis honor</option>
                            <option value="Sekolah">Sekolah</option>
                            <option value="Guru">Guru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentStatus" class="form-label">Status</label>
                        <select class="form-select" id="paymentStatus" name="status" required>
                            <option value="">Pilih status</option>
                            <option value="Sudah Dibayar">Sudah Dibayar</option>
                            <option value="Belum Dibayar">Belum Dibayar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentDate" class="form-label">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="paymentDate" name="tanggal_pembayaran">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Data pembayaran (sementara dari JavaScript)
        const paymentData = [
            {
                nama: "SMAN 1 Jakarta",
                jenis_honor: "Sekolah",
                status: "Sudah Dibayar",
                tanggal_pembayaran: "2025-01-01"
            },
            {
                nama: "John Doe",
                jenis_honor: "Guru",
                status: "Belum Dibayar",
                tanggal_pembayaran: "-"
            }
        ];

        // Inisialisasi DataTables
        const table = $('#paymentTable').DataTable({
            data: paymentData,
            columns: [
                { data: 'nama', title: 'Nama' },
                { data: 'jenis_honor', title: 'Jenis Honor' },
                { data: 'status', title: 'Status' },
                { data: 'tanggal_pembayaran', title: 'Tanggal Pembayaran' },
                {
                    data: null,
                    title: 'Aksi',
                    orderable: false,
                    render: (data, type, row) => `
                    <button class="btn btn-warning btn-sm verify-payment" data-name="${row.nama}">Verifikasi</button>
                `
                }
            ]
        });

        // Filter status pembayaran
        $('#filterStatus').on('change', function () {
            const selectedStatus = $(this).val();
            if (selectedStatus) {
                table.column(3).search(selectedStatus).draw();
            } else {
                table.column(3).search('').draw();
            }
        });

        // Tambah pembayaran baru
        $('#addPaymentForm').on('submit', function (e) {
            e.preventDefault();

            // Ambil data dari form
            const nama = $('#paymentName').val();
            const jenis_honor = $('#paymentType').val();
            const nominal = parseInt($('#paymentAmount').val(), 10);
            const status = $('#paymentStatus').val();
            const tanggal_pembayaran = $('#paymentDate').val() || "-";

            // Tambahkan ke array data
            const newPayment = {
                nama,
                jenis_honor,
                nominal,
                status,
                tanggal_pembayaran
            };
            paymentData.push(newPayment);

            // Reload DataTables dengan data terbaru
            table.clear().rows.add(paymentData).draw();

            // Reset form dan tutup modal
            $('#addPaymentForm')[0].reset();
            $('#addPaymentModal').modal('hide');
        });

        $('#paymentTable').on('click', '.verify-payment', function () {
            const nama = $(this).data('name'); // Ambil data nama dari atribut data-name

            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: `Apakah Anda yakin ingin memverifikasi pembayaran untuk ${nama}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan aksi verifikasi di sini
                    Swal.fire({
                        title: 'Berhasil!',
                        text: `Pembayaran untuk ${nama} telah diverifikasi.`,
                        icon: 'success',
                    });

                    // Contoh: Panggil fungsi untuk memproses verifikasi di server
                    verifyPayment(nama);
                }
            });
        });

        // Submit form verifikasi
        $('#verifyPaymentForm').on('submit', function (e) {
            e.preventDefault();

            const buktiPembayaran = $('#verifyFile').val();
            if (!buktiPembayaran) {
                alert('Bukti pembayaran harus diunggah!');
                return;
            }

            alert('Pembayaran berhasil diverifikasi!');
            $('#verifyPaymentModal').modal('hide');
            $('#verifyPaymentForm')[0].reset();
        });
    });

</script>