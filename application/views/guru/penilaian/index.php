<div class="card mt-4">
    <div class="card-header">
        <div class="card-title">Daftar Mahasiswa untuk Penilaian</div>
    </div>

    <div class="card-body">
        <div class="mt-2">
            <table id="dataTable" class="table table-bordered dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student->name) ?></td>
                                <td><?= htmlspecialchars($student->nim) ?></td>
                                <td>
                                    <a href="<?= base_url('guru/penilaian_intrakurikuler/' . $student->id) ?>" class="btn btn-info btn-sm">Intrakurikuler</a>
                                    <a href="<?= base_url('guru/penilaian_ekstrakurikuler/' . $student->id) ?>" class="btn btn-warning btn-sm">Ekstrakurikuler</a>
                                    <a href="<?= base_url('guru/penilaian_sikap/' . $student->id) ?>" class="btn btn-danger btn-sm">Sikap</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada mahasiswa yang perlu dinilai.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        $('#dataTable').DataTable({
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
            }
        });
    });
</script>