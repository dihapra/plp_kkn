sip, aku tambahkan langsung ke PRD biar lebih praktis buat dev 👇

---

# 📑 PRD Import Data Penempatan PLP II (Admin › Mahasiswa) — **Final dengan Mapping Array**

## 1. Lokasi & Tujuan

- **Menu:** `Admin › Mahasiswa › Import`
- **Tujuan:** Admin unggah Excel → sistem sinkronkan data mahasiswa, dosen, sekolah, dan users via **updateOrCreate** dalam 1 transaction (1 error = rollback).

---

## 2. Struktur Spreadsheet

- **Baris 1**: Header (wajib persis)
- **Baris 2 → n**: Data

### Mapping Kolom A–K

```php
$data = [
    'nim'                => $row['A'], // Kolom A: NIM Mahasiswa
    'nama_mahasiswa'     => $row['B'], // Kolom B: Nama Mahasiswa
    'email_mahasiswa'    => $row['C'], // Kolom C: Email Mahasiswa (opsional)
    'prodi_mahasiswa'    => $row['D'], // Kolom D: Prodi Mahasiswa (nullable)
    'fakultas_mahasiswa' => $row['E'], // Kolom E: Fakultas Mahasiswa (nullable)

    'nip_dosen'          => $row['F'], // Kolom F: NIP Dosen
    'nama_dosen'         => $row['G'], // Kolom G: Nama Dosen (DPL)
    'email_dosen'        => $row['H'], // Kolom H: Email Dosen (opsional)
    'prodi_dosen'        => $row['I'], // Kolom I: Prodi Dosen (nullable)
    'fakultas_dosen'     => $row['J'], // Kolom J: Fakultas Dosen (nullable)

    'nama_sekolah'       => $row['K'], // Kolom K: Nama Sekolah
];
```

> Kolom lain di kanan (L, M, …) → **abaikan**.
> NIM & NIP harus diperlakukan sebagai string (hindari hilang leading zero).

---

## 3. Flow Proses Import (Transaction)

1. Validasi header minimal (`A,B,F,G,K`).
2. **Loop per baris** (baris 2 → n):

   - Sekolah → `updateOrCreate` by `nama_sekolah`.
   - Dosen → `updateOrCreate` by `nip_dosen`.

     - User dosen → `username = nama_dosen`, `nim = nip_dosen`, `role = lecture`, `password = hash(nip_dosen)`, email opsional.

   - Mahasiswa → `updateOrCreate` by `nim`.

     - Relasi: `dosen_id`, `school_id`.
     - User mahasiswa → `username = nama_mahasiswa`, `nim = nim`, `role = student`, `password = hash(nim)`, email opsional.

   - Prodi & Fakultas → jika ada, diisi; kalau tidak ada → `null`.

3. Jika ada error (mis. kolom wajib kosong) → **throw exception**, rollback semua.
4. Jika sukses → commit.

---

## 4. Output

- **Sukses** → ringkasan jumlah record yang di-insert/update.
- **Error** → baris & alasan (mis. “Baris 15: NIM kosong”), rollback semua.

---

## 5. Acceptance Criteria

- Idempotent: `updateOrCreate` memastikan tidak ada duplikasi.
- Semua mahasiswa punya `dosen_id` & `school_id`.
- Semua dosen & mahasiswa otomatis punya user.
- Prodi/fakultas opsional → bisa null.
- Email opsional.
- 1 error = rollback semua.

---

👉 Apakah mau aku detailkan juga **contoh array breakdown** per tabel (`$data_mahasiswa`, `$data_dosen`, `$data_user`, `$data_school`) biar dev tinggal mapping sesuai repository?
