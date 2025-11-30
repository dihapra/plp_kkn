async function updateLogbook() {
    const meetingNumber = document.getElementById("editLogbookMeeting").innerText.trim();
    const modalEdit = document.querySelector('#editLogbookModal');
    // Kumpulkan data baris logbook dari tabel
    const logbookData = [];
    modalEdit.querySelectorAll("#logbookTable tbody tr").forEach(row => {
        const kegiatan = row.querySelector(".kegiatan-observasi").value.trim();
        const hasil = row.querySelector(".hasil-observasi").value.trim();
        if (kegiatan && hasil) {
            logbookData.push({
                activity: kegiatan,
                observation: hasil
            });
        }
    });

    // Ambil nilai dari textarea tambahan
    const permasalahan = modalEdit.querySelector("#permasalahan").value.trim();
    const solusi = modalEdit.querySelector("#solusi").value.trim();
    const kesimpulan = modalEdit.querySelector("#kesimpulan").value.trim();

    // Validasi: pastikan setidaknya ada satu baris logbook
    if (logbookData.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Oops...",
            text: "Logbook tidak boleh kosong!"
        });
        return;
    }

    // Buat object data untuk dikirim sebagai JSON
    const requestData = {
        meeting_number: meetingNumber,
        logbook: logbookData,
        problem: permasalahan,
        solution: solusi,
        summary: kesimpulan
    };

    try {
        const response = await fetch(`${baseUrl}mahasiswa/logbook/update`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestData)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP ${response.status}`);
        }

        const result = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: result.message || "Logbook berhasil diperbarui!"
            }).then(() => {
                // Sembunyikan modal edit dan reload halaman
                const modalEl = document.getElementById("editLogbookModal");
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: result.message || "Terjadi kesalahan saat memperbarui logbook."
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message
        });
    }
}