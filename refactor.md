# Log Refactoring & Perbaikan Fitur Penelitian

Dokumen ini berisi rangkuman perubahan yang telah kita lakukan pada fitur **Paten HKI**, **Karya Tanpa HKI**, dan **Artikel Jurnal** untuk modul `simlitabmas` (Admin/LPPM) dan `universe` (Dosen). Rangkuman ini dibuat untuk memudahkan proses dokumentasi sebelum melakukan *push* ke GitHub dan sebagai acuan untuk *refactoring* fitur penelitian lainnya (seperti Prosiding, Buku, dll) ke depannya.

## 1. Perbaikan Kueri Relasi Tabel (Jenis Paten & TKT)
**Masalah:** Data "Jenis Paten" dan "Tingkat Kesiapan Teknologi (TKT)" tidak muncul pada halaman detail anggota paten HKI karena kesalahan pemanggilan parameter `kelompok` di kueri SQL.
**File yang diubah:**
- `simlitabmas/module/content/penelitian/lppm/anggota/patenhki.php`
  - *Refactor:* Mengubah kondisi `JOIN` dari `kj.kelompok = 'jenis_patenhki'` menjadi `kj.kelompok = 'patenhki'` dan dari `kt.kelompok = 'tkt_penelitian'` menjadi `kt.kelompok = 'tkt'`.

## 2. Sinkronisasi API Upload & Object Storage
**Masalah:** Fitur unggah file Paten HKI masih menggunakan *function* milik penelitian biasa (`UploadPenelitian`) dan menyimpannya di path `file/penelitian/` yang menyebabkan tercampurnya file serta ketidaksesuaian struktur direktori.
**File yang diubah:**
- `simlitabmas/config/upload.php`
  - *Refactor:* Menambahkan function baru `UploadPatenhki($fupload_name)` dengan struktur *Object Storage* yang sama, namun menggunakan *API Key* terpisah (saat ini diset dengan *placeholder*, bisa diganti nanti).
- `universe/config/upload.php`
  - *Refactor:* Menambahkan function `UploadPatenhki($fupload_name)` menggunakan *API Key* yang sama dengan Simlitabmas agar tersinkronisasi.
- `simlitabmas/module/content/insert/penelitian.php`
- `simlitabmas/module/content/update/penelitian.php`
- `universe/module/content/insert/dosen.php`
  - *Refactor:* Mengubah pemanggilan `UploadPenelitian()` menjadi `UploadPatenhki()`.
  - *Refactor:* Mengubah penentuan *path* URL dari `file/penelitian/` menjadi `file/patenhki/`.
  - *Refactor:* Mengubah *prefix* nama file dari `penelitian_...` menjadi `patenhki_...` (di bagian dosen).

## 3. Penambahan Notifikasi (Badge) di Sidebar Admin
**Masalah:** Menu *sidebar* admin belum menampilkan indikator/jumlah data Paten HKI yang masih *pending* (menunggu validasi).
**File yang diubah:**
- `simlitabmas/module/view/sidebar.php`
  - *Refactor:* Menambahkan kueri `SELECT COUNT(id)` ke tabel `penelitian_patenhki` dengan filter `status='1' OR status IS NULL OR status=''`.
  - *Refactor:* Menambahkan *badge* notifikasi pada submenu **Paten dan HKI**.
  - *Refactor:* Mengakumulasi total notifikasi dari "Keg. Penelitian" dan "Paten HKI" pada menu utama induk **Penelitian**.

---

## 4. Refactoring Fitur Karya Tanpa HKI
**Masalah & Perbaikan yang dilakukan:**
- **Penambahan Kolom Database:** Menambahkan kolom `status` (ENUM) dan `keterangan_validasi` (TEXT) pada tabel `penelitian_karya` untuk mendukung proses validasi dari pihak LPPM (disetujui/ditolak/pending).
- **Logika Insert Dosen (Perbaikan Pivot):**
  - File: `universe/module/content/insert/dosen.php`
  - *Refactor:* Menghapus logika insert ke tabel *pivot* `regis_dosen_penelitian` saat dosen submit karya. Pivot insert **hanya terjadi saat LPPM memvalidasi** (status berubah ke `'2'`/Disetujui), bukan saat dosen submit.
  - *Refactor:* Menyederhanakan logika insert menjadi: insert ke tabel utama `penelitian_karya` (status=`'1'`) + upload file. Tanpa cek duplikat judul.
  - *Refactor:* Menyesuaikan pemanggilan fungsi upload menjadi `UploadKarya()`.
- **Perbaikan UI & Select2 pada Form Input:**
  - File: `universe/module/content/penelitian/karya.php` dan `simlitabmas/module/content/penelitian/lppm/form/karya/edit.php`
  - *Refactor:* Mengubah `class` pada form *dropdown* tautan penelitian/pengabdian menjadi `select2-link` yang seragam dan bisa di-search.
- **Pencegahan Fatal Error (Blank Page) di Dashboard Dosen:**
  - File: `universe/module/content/penelitian/karya.php`
  - *Refactor:* Menyesuaikan query *Penelitian yang Berkaitan Langsung* menjadi `WHERE nip_dosen='{$_SESSION['ses_user']}'` (hanya menampilkan kegiatan milik dosen yang bersangkutan) tanpa menggunakan pemanggilan kolom `status` pada tabel ekstensi yang berpotensi memicu *Fatal Error* di environment Universe.

---

## 5. Refactoring Fitur Artikel Jurnal

### 5a. Penambahan Handler Delete yang Hilang (Simlitabmas)
**Masalah:** Tombol "Hapus" pada halaman anggota jurnal dan tombol "Hapus" seluruh data jurnal menampilkan error karena handler delete-nya tidak ada di server-side. Route `/delete/penelitian/jurnal/<id>` dan `/delete/penelitian/anggota_jurnal/<id>` jatuh ke blok `else` default yang hanya redirect tanpa menghapus.
**File yang diubah:**
- `simlitabmas/module/content/delete/penelitian.php`
  - *Refactor:* Menambahkan handler `jurnal` — menghapus file fisik (`file_jurnal`), file anggota, record anggota (`regis_dosen_penelitian` dengan `jenis_porto='jurnal'`), dan record utama `penelitian_jurnal` beserta duplikatnya. Redirect ke `/penelitian/jurnal/?m=deleted`.
  - *Refactor:* Menambahkan handler `anggota_jurnal` — menghapus file lampiran anggota (`file_jurnal`), record anggota dari pivot, dan data duplikat. Redirect ke `/penelitian/jurnal/anggota/<id>?m=deleted`.

### 5b. Perbaikan UI Halaman Jurnal Dosen (Universe)
**Masalah:** Halaman jurnal dosen (`universe`) masih menggunakan 1 tabel gabungan untuk semua status (disetujui, pending, ditolak) tanpa DataTables, tidak konsisten dengan halaman penelitian yang sudah menggunakan 3 tabel terpisah.
**File yang diubah:**
- `universe/module/content/penelitian/jurnal.php`
  - *Refactor:* Memecah 1 tabel gabungan menjadi **3 tabel terpisah**: Disetujui (`card-success`, status=`'2'`), Menunggu Validasi (`card-warning`, status=`'1'`/NULL), dan Ditolak (`card-danger`, status=`'3'`).
  - *Refactor:* Menambahkan **DataTables** pada ketiga tabel dengan konfigurasi paging, search, dan scrollX.
  - *Refactor:* Menambahkan **kolom "Keterangan Ditolak"** pada tabel ditolak.
  - *Refactor:* Menambahkan dialog `confirm('Yakin hapus?')` pada tombol hapus di tabel Disetujui.
  - *Refactor:* Menyeragamkan styling tabel (badge file, shadow, text-wrap) agar konsisten dengan `penelitian.php`.

### 5c. Konsistensi Logika Pivot (Keputusan Desain)
**Keputusan:** Insert ke tabel *pivot* `regis_dosen_penelitian` **hanya dilakukan saat LPPM memvalidasi** (status=`'2'`), bukan saat dosen submit dari Universe.
**Dampak:**
- Insert dari Universe (`universe/module/content/insert/dosen.php`) untuk **semua fitur** (penelitian, patenhki, jurnal, karya) **tidak** memasukkan data ke tabel pivot.
- Validasi dari Simlitabmas (`simlitabmas/module/content/penelitian/lppm/validasi/*.php`) yang menangani pivot insert saat status berubah ke `'2'` dengan cek duplikasi.

| Handler Validasi | File | Pivot saat approve? | Cek duplikat? |
|---|---|---|---|
| Penelitian | `validasi/penelitian.php` | ✅ | ✅ |
| Paten HKI | `validasi/patenhki.php` | ✅ | ✅ |
| Jurnal | `validasi/jurnal.php` | ✅ | ✅ |
| Karya | `validasi/karya.php` | ✅ | ✅ |

### 5d. Sinkronisasi API Upload Jurnal
- `universe/config/upload.php`
  - *Refactor:* Mengupdate API Key pada function `UploadJurnal()` dari *placeholder* menjadi key yang valid.

### 5e. Perbaikan Bug Insert Jurnal (Kueri SQL & File Upload)
**Masalah & Perbaikan yang dilakukan:**
- **Kueri SQL Implisit:** Sebelumnya, insert ke tabel `penelitian_jurnal` tidak menuliskan nama kolom secara eksplisit (`INSERT INTO penelitian_jurnal() VALUES(...)`). Akibatnya, saat kita menambahkan kolom baru di tabel (`status`, `keterangan_validasi`), semua data yang diinput bergeser masuk ke kolom yang salah.
  - File: `universe/module/content/insert/dosen.php`
  - *Refactor:* Mengubah format query insert menjadi eksplisit dan menyebutkan seluruh nama kolom yang dituju secara berurutan. (Paten HKI juga sudah disesuaikan sebelumnya, sedangkan Karya dan Penelitian sudah aman dari awal).
- **Gagal Upload File (File Input Name):** Dosen gagal mengunggah lampiran jurnal karena *handler* di sisi server mencari `$_FILES['file1']`, sedangkan pada form input di Universe namanya adalah `name="file"`.
  - File: `universe/module/content/insert/dosen.php`
  - *Refactor:* Mengubah `$_FILES['file1']` menjadi `$_FILES['file']` pada bagian insert jurnal.

### 5f. Perbaikan UI (Tabel Jurnal LPPM)
**Masalah:** Pada halaman data jurnal di sisi Admin/LPPM, judul artikel yang panjang membuat tabel terlihat berantakan atau kolomnya menyusut tidak proporsional saat diakses pada layar yang lebih kecil.
- `simlitabmas/module/content/penelitian/lppm/jurnal.php`
  - *Refactor:* Menerapkan *CSS line-clamping* (`-webkit-line-clamp: 2`) pada kolom Judul Artikel agar maksimal hanya mengambil 2 baris teks (diakhiri dengan elipsis `...`).
  - *Refactor:* Menambahkan atribut `title` agar judul lengkap tetap terlihat saat kursor disorot (*hover*).
  - *Refactor:* Menambahkan *style* `min-width: 250px;` pada sel (td) agar kolom memiliki lebar proporsional dan teks tidak terhimpit.

---

## 6. Refactoring Fitur Artikel Prosiding

### 6a. Penambahan Handler Delete (Simlitabmas)
**Masalah:** Proses hapus data utama prosiding dan anggota prosiding belum ter-handle di server.
**File yang diubah:**
- `simlitabmas/module/content/delete/penelitian.php`
  - *Refactor:* Menambahkan handler `prosiding` untuk menghapus file fisik (`file_prosiding`), file anggota, record anggota (`regis_dosen_penelitian` dengan `jenis_porto='prosiding'`), dan record utama `penelitian_prosiding` beserta duplikatnya. Redirect ke `/penelitian/prosiding/?m=deleted`.
  - *Refactor:* Menambahkan handler `anggota_prosiding` dengan logika penghapusan serupa untuk tingkat keanggotaan.

### 6b. Perbaikan UI Halaman Prosiding Dosen (Universe)
**Masalah:** Halaman prosiding dosen (`universe`) masih menggunakan 1 tabel gabungan untuk semua status, sama seperti kasus artikel jurnal sebelumnya.
**File yang diubah:**
- `universe/module/content/penelitian/prosiding.php`
  - *Refactor:* Memecah 1 tabel gabungan menjadi **3 tabel terpisah**: Disetujui (`card-success`), Menunggu Validasi (`card-warning`), dan Ditolak (`card-danger`).
  - *Refactor:* Menerapkan **DataTables** pada ketiga tabel dengan konfigurasi seragam dan responsif.
  - *Refactor:* Menambahkan **kolom "Keterangan Ditolak"** pada tabel ditolak.
  - *Refactor:* Menambahkan dialog `confirm('Yakin hapus?')` pada tombol hapus.

### 6c. Perbaikan Bug Insert Prosiding & Sinkronisasi Upload
**Masalah:** Query insert masih implisit dan belum ada direktori upload yang spesifik.
**File yang diubah:**
- `universe/module/content/insert/dosen.php`
  - *Refactor:* Mengubah format query insert ke `penelitian_prosiding` menjadi eksplisit dengan menyebutkan nama kolom secara berurutan.
  - *Refactor:* Memperbarui path upload menjadi `file/prosiding/` dan prefix file menjadi `prosiding_...`.
  - *Refactor:* Menggunakan pemanggilan fungsi `UploadProsiding()` untuk memisahkan file dari file penelitian biasa.

### 6d. Perbaikan UI (Tabel Prosiding LPPM) & Notifikasi Sidebar
**Masalah:** Tabel di sisi Admin butuh line-clamping agar rapi, dan notifikasi pending belum mencakup prosiding.
**File yang diubah:**
- `simlitabmas/module/content/penelitian/lppm/prosiding.php`
  - *Refactor:* Menerapkan *CSS line-clamping* pada kolom Judul Naskah dan Nama Seminar, serta memberikan atribut `title`.
- `simlitabmas/module/view/sidebar.php`
  - *Refactor:* Menambahkan query count untuk status pending pada tabel `penelitian_prosiding` dan menampilkannya sebagai *badge* pada sidebar Admin/LPPM.
  - *Refactor:* Menambahkan jumlah *pending* prosiding ke dalam akumulasi badge utama **Penelitian**.

---

### Langkah Berikutnya (*Next Steps*)
Fitur yang belum di-*refactor* dengan pola yang sama:
1. **Buku** — Belum ada 3 tabel terpisah di Universe, perlu handler delete, kueri eksplisit, dan line-clamping.
2. **Tulisan Media Massa** — Perlu penyesuaian yang sama.
3. Pastikan semua *prefix parameter kelompok* untuk relasi ke tabel `kode` sudah benar.
4. Buat fungsi `Upload...()` tersendiri di `config/upload.php` (baik di sisi admin maupun dosen) beserta penyesuaian *API Key* jika diperlukan.
5. Sesuaikan alur `insert` dan `update` agar menggunakan path direktori spesifik.
6. Update kueri indikator *pending* di `sidebar.php` jika diperlukan.
