# Panduan Command Terminal: Read dan Edit File

Dokumen ini menjelaskan cara saya biasanya membaca dan mengedit file lewat terminal di project ini (PowerShell di Windows). Contoh di bawah bisa kamu ikuti dan modifikasi.

## 1) Lihat struktur project

- Lihat isi folder root:
  ```powershell
  Get-ChildItem -Force
  ```
- Cari semua file dengan ekstensi tertentu (contoh: PHP):
  ```powershell
  rg --files -g "*.php"
  ```
  Jika `rg` belum ada, pakai:
  ```powershell
  Get-ChildItem -Recurse -Filter *.php
  ```

## 2) Cari teks di file

- Cari string di seluruh project:
  ```powershell
  rg "welcome_message" -g "*.php"
  ```
- Cari di folder tertentu saja:
  ```powershell
  rg "base_url" application
  ```

## 3) Baca file (read-only)

- Lihat isi file:
  ```powershell
  Get-Content application/views/welcome_message.php
  ```
- Lihat sebagian file (lebih cepat untuk file besar):
  ```powershell
  Get-Content application/views/welcome_message.php -TotalCount 80
  ```

## 4) Edit file (manual via editor)

- Buka file di editor default (VS Code):
  ```powershell
  code application/views/welcome_message.php
  ```

## 5) Edit file cepat (otomatis di terminal)

Gunakan ini jika ingin ganti teks kecil tanpa membuka editor.

- Ganti kata di satu file:
  ```powershell
  (Get-Content application/views/welcome_message.php) 
    -replace "Hello", "Halo" |
    Set-Content application/views/welcome_message.php
  ```

- Ganti kata di banyak file (hati-hati):
  ```powershell
  Get-ChildItem -Recurse -Filter *.php |
    ForEach-Object {
      (Get-Content $_.FullName) -replace "old", "new" |
      Set-Content $_.FullName
    }
  ```

## 6) Cek perubahan sebelum/sesudah

- Lihat status git:
  ```powershell
  git status -sb
  ```
- Lihat diff perubahan:
  ```powershell
  git diff
  ```

## 7) Pola kerja singkat yang saya pakai

1. Cari file target dengan `rg --files` atau `rg "keyword"`.
2. Baca isi file dengan `Get-Content`.
3. Edit kecil pakai `-replace` atau buka editor untuk perubahan besar.
4. Cek `git diff` untuk memastikan hasilnya benar.

---

Kalau kamu mau contoh otomatisasi khusus (misalnya update teks di seluruh halaman, rename class, atau migrasi HTML), sebutkan saja.
