async function saveLogbook() {
    const meetingNumber = document.getElementById("logbookMeeting").innerText;
    const logbookData = [];

    // Ambil data dari tabel logbook
    document.querySelectorAll("#logbookTable tbody tr").forEach((row) => {
        const kegiatan = row.querySelector(".kegiatan-observasi").value.trim();
        const hasil = row.querySelector(".hasil-observasi").value.trim();
        if (kegiatan && hasil) {
            logbookData.push({
                kegiatan,
                hasil
            });
        }
    });

    // Ambil data dari textarea
    const permasalahan = document.getElementById("permasalahan").value.trim();
    const solusi = document.getElementById("solusi").value.trim();
    const kesimpulan = document.getElementById("kesimpulan").value.trim();

    if (logbookData.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Oops...",
            text: "Logbook tidak boleh kosong!",
        });
        return;
    }

    const requestData = {
        meeting_number: meetingNumber,
        logbook: logbookData,
        permasalahan,
        solusi,
        kesimpulan,
    };

    try {
        const response = await fetch(`${baseUrl}mahasiswa/logbook/simpan`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        const result = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: result.message || "Logbook berhasil disimpan!",
            }).then(() => {
                document.getElementById("logbookModal").querySelector(".btn-close").click();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Gagal",
                text: result.message || "Terjadi kesalahan saat menyimpan logbook.",
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: `Terjadi kesalahan: ${error.message}`,
        });
    }
}