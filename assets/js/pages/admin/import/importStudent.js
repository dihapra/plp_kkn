(function () {
    const form = document.getElementById('uploadForm');
    const btn = document.getElementById('btnImport');
    const fileEl = document.getElementById('fileImportCsv');
    const modalEl = document.getElementById('importAll');

    function ok(msg) {
        if (window.Swal) Swal.fire('Berhasil', msg || 'Import selesai.', 'success');
        else alert(msg || 'Import selesai.');
    }

    function fail(msg) {
        if (window.Swal) Swal.fire('Gagal', msg || 'Terjadi kesalahan.', 'error');
        else alert(msg || 'Terjadi kesalahan.');
    }

    function validExt(name) {
        const ext = (name.split('.').pop() || '').toLowerCase();
        return ['csv', 'xlsx', 'xls'].includes(ext);
    }

    function setLoading(state) {
        btn.disabled = state;
        btn.textContent = state ? 'Mengimpor...' : 'Import';
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const f = fileEl.files[0];
        if (!f) {
            fail('Pilih file terlebih dahulu.');
            return;
        }
        if (!validExt(f.name)) {
            fail('Format tidak didukung. Gunakan CSV/XLSX/XLS.');
            return;
        }

        const formData = new FormData(form); // termasuk file & CSRF hidden input
        setLoading(true);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                // Jangan set Content-Type manual; biarkan browser set boundary multipart.
                // Jika pakai CSRF via header (opsional), kamu bisa tambahkan:
                // headers: { 'X-Requested-With': 'fetch' }
            });

            // coba parse JSON; kalau gagal, lempar error
            const data = await res.json().catch(() => {
                throw new Error('Response bukan JSON yang valid.');
            });

            if (!res.ok) {
                throw new Error(data.message || data.error || 'Import gagal.');
            }

            ok(data.message || 'Import berhasil.');
            // tutup modal
            if (window.bootstrap) {
                const instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                instance.hide();
            }
            form.reset();

            // kalau pakai DataTables, refresh di sini
            if (window.$ && $('#dataTable').length) $('#dataTable').DataTable().ajax.reload(null, false);

        } catch (err) {
            fail(err.message);
        } finally {
            setLoading(false);
        }
    });

    // reset state saat modal dibuka
    if (modalEl) {
        modalEl.addEventListener('shown.bs.modal', () => {
            setLoading(false);
            form.reset();
        });
    }
})();