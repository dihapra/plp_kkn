siap, aku detailkan PRD **Logbook Guru (Grouped View)** dari sisi backend, frontend, sampai UX flow 👇

---

# 📑 PRD: Fitur Logbook Guru (Grouped by Mahasiswa)

## 🎯 Tujuan

- Guru melihat **logbook mahasiswa bimbingannya** dalam bentuk tabel terstruktur.
- **Satu baris = satu mahasiswa**
- **Kolom = pertemuan (P1…Pn)**
- Isi cell = tombol “Detail” (jika logbook ada) atau “-” (jika kosong).
- Klik “Detail” → tampil modal dengan isi logbook (deskripsi, tanggal, feedback).

---

## 1. Data Source & Query

### Tabel yang terlibat

- `logbook (id, student_id, meeting_number, activity_description, lecturer_feedback, teacher_feedback, created_at, …)`
- `student (id, nim, name, teacher_id)`

### Query utama

- Ambil semua logbook untuk guru login (`WHERE student.teacher_id = $teacher_id`).
- Join dengan student.
- Sortir `ORDER BY student.nim, logbook.meeting_number`.

### Transformasi (UseCase: `execute_grouped()`)

- Group hasil query by `student.nim`.
- Struktur array:

```php
[
  '1223111001' => [
    'nama' => 'Reta Uli Sitohang',
    'nim'  => '1223111001',
    'pertemuan' => [
      1 => [ 'id' => 101, 'status' => '✓' ],
      2 => [ 'id' => 102, 'status' => '✓' ],
      3 => [ 'id' => null, 'status' => '-' ],
      …
    ]
  ],
  …
]
```

- `status = "✓"` → logbook ada, `"-"` → tidak ada.
- `id` dipakai untuk tombol detail.

---

## 2. Backend Output ke View

- Controller `Guru::logbook()`:

  - Panggil `LogbookCase::execute_grouped()`.
  - Kirim ke view `guru/logbook/index.php`:

    ```php
    view_with_layout('guru/logbook/index', 'SIMPLP UNIMED', [
        'logbooks' => $grouped,   // array grouped
        'maxMeeting' => 16        // jumlah pertemuan maksimal
    ]);
    ```

- Opsi: kirim juga full logbook detail → encode JSON di `<script>` → frontend bisa langsung lookup tanpa Ajax.

---

## 3. Tampilan Tabel

- Header:

  ```
  Nama Mahasiswa | NIM | P1 | P2 | P3 | … | Pn
  ```

- Body:

  - Loop mahasiswa.
  - Loop pertemuan 1 → n:

    - Jika ada `id`: render tombol:

      ```html
      <button class="btn btn-sm btn-info btn-detail-logbook" data-id="101">
      	Detail
      </button>
      ```

    - Jika tidak ada: render `-`.

Contoh row:

```html
<tr>
	<td>Reta Uli Sitohang</td>
	<td>1223111001</td>
	<td><button data-id="101" …>Detail</button></td>
	<td><button data-id="102" …>Detail</button></td>
	<td>-</td>
	…
</tr>
```

---

## 4. Modal Detail

- Modal kosong ada di view:

  ```html
  <div class="modal fade" id="viewLogbookModal" …>
  	<div class="modal-content">
  		<div class="modal-header">
  			<h5>Detail Logbook</h5>
  		</div>
  		<div class="modal-body">
  			<div id="logbookDetailContent">Loading…</div>
  		</div>
  	</div>
  </div>
  ```

---

## 5. UX Flow (2 opsi)

### **Opsi A (Full Preload)**

- Backend kirim **semua detail logbook** ke view (misalnya array besar di-encode ke JSON).
- Frontend: saat klik tombol, cari data by `id` dari JSON → tampilkan di modal.
- **Kelebihan**: tidak ada request tambahan.
- **Kekurangan**: data awal besar kalau ribuan logbook.

### **Opsi B (Lazy Load per klik)**

- Backend hanya kirim grouped array (nama, nim, id).
- Klik tombol → Ajax GET ke `guru/get_logbook_detail/{id}` → server return JSON detail.
- Isi modal dengan hasil.
- **Kelebihan**: load awal ringan.
- **Kekurangan**: butuh Ajax per klik.

---

## 6. Endpoint `guru/get_logbook_detail/{id}`

- Input: `id` logbook.
- Output JSON:

```json
{
	"status": "success",
	"data": {
		"id": 101,
		"student_name": "Reta Uli Sitohang",
		"student_nim": "1223111001",
		"meeting_number": 1,
		"created_at": "2025-08-27 10:00:00",
		"activity_description": "Membuat RPP",
		"lecturer_feedback": "Baik",
		"teacher_feedback": "Perlu perbaikan"
	}
}
```

- Jika tidak ketemu / unauthorized → `status = error`.

---

## 7. Acceptance Criteria

- Guru login hanya melihat mahasiswa bimbingannya.
- Tabel tampil **per mahasiswa 1 baris, pertemuan sebagai kolom**.
- Jika ada logbook → tombol Detail dengan `data-id`.
- Modal detail bisa ditampilkan lengkap (aktivitas, tanggal, feedback).
- Mode preload atau lazy load bisa dipilih (disarankan lazy load untuk ribuan data).
- Tidak menggunakan DataTables (plain Bootstrap table).

---

👉 mau aku bikinkan juga PRD tambahan khusus **opsi preload (semua detail dikirim ke view dalam JSON)**, atau kita lock ke **opsi lazy load** saja biar aman kalau datanya besar?
