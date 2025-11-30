<link rel="stylesheet" href="<?= css_url('timeline.css') ?>">
<h1 class="text-center m-4">Aktivitas</h1>
<ul class="timeline" id="timelineAktivitas"></ul>

<!-- Modal Detail Aktivitas -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Aktifitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="detailTugasContent">Detail Aktivitas akan ditampilkan di sini.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        const timelineAktivitas = document.getElementById("timelineAktivitas");

        async function fetchAktivitasData() {
            try {
                const response = await fetch(`${baseUrl}guru/list-aktivitas`);
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

        // Fungsi untuk merender timeline berdasarkan data aktivitas saja
        function renderTimeline(aktivitasData) {
            timelineAktivitas.innerHTML = ""; // Bersihkan timeline sebelum render ulang
            // Asumsikan jumlah pertemuan misalnya 16
            const pertemuanCount = 16;
            for (let i = 1; i <= pertemuanCount; i++) {
                // Cari data aktivitas berdasarkan meeting_number
                const aktivitas = aktivitasData.find(item => parseInt(item.meeting) === i) || null;

                const li = document.createElement("li");
                li.className = "timeline-item";

                li.innerHTML = `
                <div class="timeline-item-content">
                    <h5>
                        Pertemuan ${i}
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-info detail-btn" 
                                data-aktivitas='${aktivitas && aktivitas.description ? aktivitas.description : ""}'
                                ${aktivitas && aktivitas.description ? "" : "disabled"}>
                            Detail Aktivitas
                        </button>
                    </div>
                </div>
            `;
                timelineAktivitas.appendChild(li);
            }
            attachModalEvents(); // Pasang event untuk tombol detail
        }

        // Fungsi untuk mengubah huruf pertama menjadi kapital (jika diperlukan)
        function capitalize(text) {
            return text.charAt(0).toUpperCase() + text.slice(1);
        }

        // Pasang event untuk tombol detail
        function attachModalEvents() {
            document.querySelectorAll(".detail-btn").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const modal = new bootstrap.Modal(document.getElementById("detailModal"));
                    document.getElementById("detailTugasContent").innerHTML = btn.dataset.aktivitas || "Detail tidak tersedia.";
                    modal.show();
                });
            });
        }

        // Ambil data aktivitas dan render timeline
        const aktivitasData = await fetchAktivitasData();

        renderTimeline(aktivitasData);
    });
</script>