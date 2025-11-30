<div class="container">
    <div class="row">
        <div class="col-md-4 col-sm-12 mb-3">
            <label for="sekolah_filter" class="form-label">Sekolah</label>
            <select onchange="" class="form-select" id="sekolah_filter" name="sekolah_filter">
                <option value="">Pilih Sekolah</option>
            </select>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada elemen <select>
        $('#sekolah_filter').select2({
            placeholder: "Pilih Sekolah",
            allowClear: true,
            width: '100%' // Pastikan Select2 menggunakan lebar penuh
        });

        // Fungsi untuk mengambil data sekolah dari API
        async function fetchSchool() {
            try {
                const resp = await fetch(`${baseUrl}sekolah/select`);
                const data = await resp.json();
                return data; // Asumsi `data.data` adalah array dari sekolah
            } catch (error) {
                console.error("Error fetching school data:", error);
                return [];
            }
        }

        // Fungsi untuk mengisi <select> dengan data
        async function initiateSelect() {
            const data = await fetchSchool(); // Ambil data sekolah
            const selectElement = $('#sekolah_filter'); // Ambil elemen <select> menggunakan jQuery

            // Hapus semua opsi kecuali opsi default
            selectElement.empty().append(new Option("Pilih Sekolah", ""));

            // Iterasi data dan tambahkan ke dalam <select>
            data.forEach((school) => {
                const option = new Option(school.name, school.id, false, false); // Opsi Select2
                selectElement.append(option); // Tambahkan opsi ke dalam <select>
            });

            // Perbarui Select2 setelah opsi ditambahkan
            selectElement.trigger('change');
        }

        // Inisialisasi pengisian <select>
        initiateSelect();
    });
</script>
</script>