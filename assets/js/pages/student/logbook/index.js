document.addEventListener("DOMContentLoaded", async () => {
    // const
    const timelineAktivitas = document.getElementById("timelineAktivitas");
    const logbookTable = document.querySelector("#logbookTable tbody");
    const addRowBtn = document.getElementById("addRowBtn");
    const saveLogbookBtn = document.getElementById("saveLogbookBtn")
    let rowCount = 0;


    // event
    $('#updateLogbookBtn').on('click', updateLogbook);
    saveLogbookBtn.addEventListener("click", saveLogbook);
    async function fetchAbsensiData() {
        try {
            const response = await fetch(`${baseUrl}mahasiswa/kehadiran`);
            const result = await response.json();

            if (response.ok) {
                return result.data || [];
            } else {
                console.error("Gagal memuat data absensi:", result.message);
            }
        } catch (error) {
            console.error("Error saat mengambil data absensi:", error);
        }
    }

    async function fetchAktivitasData() {
        try {
            const response = await fetch(`${baseUrl}mahasiswa/list-aktivitas`);
            const result = await response.json();

            if (response.ok) {
                return result.data || [];
            } else {
                console.error("Gagal memuat data aktivitas:", result.message);
                return [];
            }
        } catch (error) {
            console.error("Error saat mengambil data aktivitas:", error);
            return [];
        }
    }
    async function fetchLogbook() {
        try {
            const response = await fetch(`${baseUrl}mahasiswa/logbooks`);
            const result = await response.json();

            if (response.ok) {
                return result.data || [];
            } else {
                console.error("Gagal memuat data aktivitas:", result.message);
                return [];
            }
        } catch (error) {
            console.error("Error saat mengambil data aktivitas:", error);
            return [];
        }
    }

    function renderTimeline(absensiData, aktivitasData, logbookData) {
        timelineAktivitas.innerHTML = ""; // Bersihkan timeline sebelum render ulang
        const pertemuanCount = 16; // Total jumlah pertemuan
        const hiddenLogbookMeetings = [1, 2, 3, 8, 15, 16]; // Pertemuan yang tombol logbooknya disembunyikan

        for (let i = 1; i <= pertemuanCount; i++) {
            const absensi = absensiData.find((item) => parseInt(item.meeting_number) === i) || null; // Cari absensi berdasarkan pertemuan
            const aktivitas = aktivitasData.find((item) => parseInt(item.meeting) === i) || null; // Cari aktivitas berdasarkan pertemuan
            const logbook = logbookData.find((item) => parseInt(item.logbook.meeting_number) === i) || null; // Cari logbook berdasarkan pertemuan

            const li = document.createElement("li");
            li.className = `timeline-item ${absensi && !absensi.status ? "card-gray" : ""}`;

            li.innerHTML = `
        <div class="timeline-item-content">
            <h5>
                Pertemuan ${i}
                <span class="badge ${absensi && String(absensi.status).toLowerCase() === "hadir" ? "bg-success" : absensi && String(absensi.status).toLowerCase() === "izin" ? "bg-warning" : "bg-secondary"}">
                    ${absensi && String(absensi.status).toLowerCase() ? `${capitalize(absensi.status)} ` : "Belum Absensi"}
                </span>
            </h5>
            <div>
                <button class="btn btn-sm btn-info detail-btn" data-aktivitas='${aktivitas && aktivitas.description || ""}' ${aktivitas && aktivitas.description ? "" : "disabled"}>
                    Detail Aktivitas
                </button>
                ${logbook
                    ? `
                            <button class="btn btn-sm btn-warning edit-logbook-btn" data-meeting="${i}">Edit Logbook</button>
                            <button class="btn btn-sm btn-secondary detail-logbook-btn" data-meeting="${i}">Detail Logbook</button>
                        `
                    : hiddenLogbookMeetings.includes(i)
                        ? "" // Jangan tampilkan tombol Logbook jika pertemuan ada di daftar tersembunyi
                        : `<button class="btn btn-sm btn-warning logbook-btn" data-meeting="${i}">Logbook</button>`
                }
            </div>
        </div>
        `;

            timelineAktivitas.appendChild(li);
        }

        attachModalEvents(); // Pasang ulang event modal setelah render
        attachLogbookEvents(); // Pasang ulang event untuk tombol logbook
    }



    function capitalize(text) {
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    function attachModalEvents() {
        document.querySelectorAll(".detail-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                const modal = new bootstrap.Modal(document.getElementById("detailModal"));
                document.getElementById("detailTugasContent").innerHTML = btn.dataset.aktivitas || "Belum ada tugas.";
                modal.show();
            });
        });
    }

    // Fetch and render data
    const absensiData = await fetchAbsensiData();
    const aktivitasData = await fetchAktivitasData();
    const logbookData = await fetchLogbook();
    renderTimeline(absensiData || [], aktivitasData || [], logbookData || []);




    function updateRowNumbers() {
        document.querySelectorAll("#logbookTable tbody tr").forEach((row, index) => {
            row.querySelector(".row-number").innerText = index + 1;
        });
    }

    function addRow() {
        rowCount++;
        const row = document.createElement("tr");
        row.innerHTML = `
            <td class="row-number">${rowCount}</td>
            <td><textarea class="form-control kegiatan-observasi"></textarea></td>
            <td><textarea class="form-control hasil-observasi"></textarea></td>
            <td><button class="btn btn-danger btn-sm deleteRow">Hapus</button></td>
        `;
        logbookTable.appendChild(row);
        attachDeleteEvent();
        updateRowNumbers();
    }

    function attachDeleteEvent() {
        document.querySelectorAll(".deleteRow").forEach((btn) => {
            btn.addEventListener("click", function () {
                this.closest("tr").remove();
                updateRowNumbers();
            });
        });
    }

    addRowBtn.addEventListener("click", addRow);

    document.querySelectorAll(".logbook-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const meetingNumber = this.dataset.meeting;
            document.getElementById("logbookMeeting").innerText = meetingNumber;
            logbookTable.innerHTML = "";
            rowCount = 0;
            addRow(); // Tambah satu baris default saat modal dibuka
            const logbookModal = new bootstrap.Modal(document.getElementById("logbookModal"));
            logbookModal.show();
        });
    });
});

function attachLogbookEvents() {
    document.querySelectorAll(".detail-logbook-btn").forEach(btn => {
        btn.addEventListener("click", async () => {
            const meetingNumber = btn.dataset.meeting;
            try {
                const response = await fetch(`${baseUrl}mahasiswa/logbooks/meeting/${meetingNumber}`);
                const result = await response.json();

                if (response.ok) {
                    // Set judul modal detail
                    document.getElementById("viewLogbookMeeting").innerText = meetingNumber;

                    // Isi tabel
                    const tbody = document.querySelector("#viewLogbookModal #logbookTable tbody");
                    tbody.innerHTML = result.data.logbook_activity.map((activity, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${activity.activity}</td>
                            <td>${activity.observation}</td>
                            <td></td>
                        </tr>
                    `).join("");

                    // Isi textarea tambahan
                    const modalDetail = document.querySelector("#viewLogbookModal");
                    modalDetail.querySelector("#permasalahan").value = result.data.logbook.problem || "";
                    modalDetail.querySelector("#solusi").value = result.data.logbook.solution || "";
                    modalDetail.querySelector("#kesimpulan").value = result.data.logbook.summary || "";
                    const lecturerFeedback = modalDetail.querySelector("#lecturerFeedbackDisplay");
                    const teacherFeedback = modalDetail.querySelector("#teacherFeedbackDisplay");
                    if (lecturerFeedback) {
                        lecturerFeedback.textContent = result.data.logbook.feedback_lecture || "-";
                    }
                    if (teacherFeedback) {
                        teacherFeedback.textContent = result.data.logbook.feedback_teacher || "-";
                    }

                    // 🔒 Disable semua input & textarea
                    modalDetail.querySelectorAll("input, textarea, select, button").forEach(el => {
                        // Kecuali tombol close
                        if (!el.classList.contains("btn-close")) {
                            el.setAttribute("disabled", true);
                        }
                    });

                    // 🗑️ Hilangkan tombol aksi di tabel dan tombol "Tambah Baris"
                    modalDetail.querySelectorAll(".deleteRow, #addRowBtn, #saveLogbookBtn, #updateLogbookBtn").forEach(el => {
                        if (el) el.style.display = "none";
                    });

                    new bootstrap.Modal(modalDetail).show();
                }
            } catch (error) {
                Swal.fire("Oops...", "Logbook tidak ditemukan.", "error");
            }
        });
    });
}
// console.log(document.querySelectorAll('.edit-logbook-btn'))
document.addEventListener("click", async function (e) {
    if (e.target && e.target.classList.contains("edit-logbook-btn")) {
        const btn = e.target;
        const meetingNumber = btn.dataset.meeting;
        document.getElementById('editLogbookMeeting').innerText = meetingNumber;

        try {
            // Fetch data logbook untuk pertemuan ini
            const response = await fetch(`${baseUrl}mahasiswa/logbooks/meeting/${meetingNumber}`);
            const result = await response.json();
            if (response.ok) {
                const tbody = document.querySelector('#editLogbookModal #logbookTable tbody');
                const modalEdit = document.querySelector('#editLogbookModal');
                tbody.innerHTML = result.data.logbook_activity.map((activity, index) => `
                    <tr>
                      <td class="row-number">${index + 1}</td>
                      <td>
                        <textarea class="form-control kegiatan-observasi" placeholder="Kegiatan Observasi">${activity.activity}</textarea>
                      </td>
                      <td>
                        <textarea class="form-control hasil-observasi" placeholder="Hasil Observasi">${activity.observation}</textarea>
                      </td>
                      <td>
                        <button class="btn btn-danger btn-sm deleteRow">Hapus</button>
                      </td>
                    </tr>
                `).join("");

                attachDeleteEventEdit(); // Pasang event delete untuk baris edit logbook

                // Prepopulate field tambahan dengan data logbook
                modalEdit.querySelector('#permasalahan').value = result.data.logbook.problem || "";
                modalEdit.querySelector('#solusi').value = result.data.logbook.solution || "";
                modalEdit.querySelector('#kesimpulan').value = result.data.logbook.summary || "";

                // Tampilkan modal edit logbook
                new bootstrap.Modal(document.getElementById('editLogbookModal')).show();
            }
        } catch (error) {
            console.log(error)
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: `Terjadi kesalahan: ${error.message}`
            });
        }
    }
});
document.addEventListener("click", function (e) {
    if (e.target && e.target.id === "addEditRowBtn") {
        addEditRow();
    }
});

function addEditRow() {
    const tbody = document.querySelector("#logbookTable tbody");
    // Hitung baris saat ini
    const currentRows = tbody.querySelectorAll("tr").length;
    const newRowNumber = currentRows + 1;

    const row = document.createElement("tr");
    row.innerHTML = `
        <td class="row-number">${newRowNumber}</td>
        <td>
          <textarea class="form-control kegiatan-observasi" placeholder="Kegiatan Observasi"></textarea>
        </td>
        <td>
          <textarea class="form-control hasil-observasi" placeholder="Hasil Observasi"></textarea>
        </td>
        <td>
          <button class="btn btn-danger btn-sm deleteRow">Hapus</button>
        </td>
    `;
    tbody.appendChild(row);
    attachDeleteEventEdit();
    updateRowNumbersEdit();
}

function attachDeleteEventEdit() {
    document.querySelectorAll("#logbookTable .deleteRow").forEach((btn) => {
        btn.addEventListener("click", function () {
            this.closest("tr").remove();
            updateRowNumbersEdit();
        });
    });
}

function updateRowNumbersEdit() {
    document.querySelectorAll("#logbookTable tbody tr").forEach((row, index) => {
        row.querySelector(".row-number").innerText = index + 1;
    });
}


