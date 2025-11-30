<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h5 class="mb-0">Data Mahasiswa Prodi</h5>
            <small class="text-muted">Pantau progres mahasiswa dan penempatannya di sekolah.</small>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="mahasiswaTahunFilter">
                <option value="">Tahun Ajaran (Semua)</option>
                <option value="2026">2026</option>
                <option value="2025">2025</option>
            </select>
            <select class="form-select form-select-sm" id="mahasiswaStatusFilter">
                <option value="">Status (Semua)</option>
                <option value="aktif">Aktif</option>
                <option value="lulus">Lulus</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm" id="kaprodiMahasiswaTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Prodi</th>
                        <th>Sekolah</th>
                        <th>Tahun</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <small class="text-muted d-block mt-3">
            Integrasikan tabel ini dengan endpoint mahasiswa untuk menampilkan data real-time berdasarkan prodi dan tahun ajaran.
        </small>
    </div>
</div>

<script>
    (function () {
        const sampleRows = [
            { nama: 'Aditya Pratama', nim: '2203456', prodi: 'Pendidikan Kimia', sekolah: 'SMA 1 Medan', tahun: '2026', status: 'aktif' },
            { nama: 'Bella Aulia', nim: '2203412', prodi: 'Pendidikan Matematika', sekolah: 'SMA 2 Binjai', tahun: '2026', status: 'aktif' },
            { nama: 'Citra Rahma', nim: '2102789', prodi: 'Pendidikan Bahasa Inggris', sekolah: 'SMA 3 Medan', tahun: '2025', status: 'lulus' },
        ];

        const table = $('#kaprodiMahasiswaTable').DataTable({
            data: sampleRows,
            columns: [
                { data: 'nama' },
                { data: 'nim' },
                { data: 'prodi' },
                { data: 'sekolah' },
                { data: 'tahun' },
                {
                    data: 'status',
                    render: (status) => {
                        const map = {
                            aktif: 'badge bg-success',
                            lulus: 'badge bg-secondary'
                        };
                        const cls = map[status] || 'badge bg-light text-dark';
                        return `<span class="${cls} text-uppercase">${status}</span>`;
                    }
                },
            ]
        });

        $('#mahasiswaTahunFilter, #mahasiswaStatusFilter').on('change', function () {
            const tahun = $('#mahasiswaTahunFilter').val();
            const status = $('#mahasiswaStatusFilter').val();

            table.clear();
            const filtered = sampleRows.filter((row) => {
                const matchTahun = tahun ? row.tahun === tahun : true;
                const matchStatus = status ? row.status === status : true;
                return matchTahun && matchStatus;
            });
            table.rows.add(filtered).draw();
        });
    })();
</script>
