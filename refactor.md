# Log Refactoring & Perbaikan Fitur Paten HKI

Dokumen ini berisi rangkuman perubahan yang telah kita lakukan pada fitur **Paten HKI** untuk modul `simlitabmas` (Admin) dan `universe` (Dosen). Rangkuman ini dibuat untuk memudahkan proses dokumentasi sebelum melakukan *push* ke GitHub dan sebagai acuan untuk *refactoring* fitur penelitian lainnya (seperti Jurnal, Buku, dll) ke depannya.

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
- **Logika Insert Dosen:** 
  - File: `universe/module/content/insert/dosen.php`
  - *Refactor:* Mengubah logika `insert` karya agar mengecek duplikat judul. Jika sudah ada, dosen akan dimasukkan ke tabel *pivot* `regis_dosen_penelitian` sebagai anggota. Selain itu, menyesuaikan pemanggilan fungsi upload menjadi `UploadKarya()`.
- **Perbaikan UI & Select2 pada Form Input:**
  - File: `universe/module/content/penelitian/karya.php` dan `simlitabmas/module/content/penelitian/lppm/form/karya/edit.php`
  - *Refactor:* Mengubah `class` pada form *dropdown* tautan penelitian/pengabdian menjadi `select2-link` yang seragam dan bisa di-search.
- **Pencegahan Fatal Error (Blank Page) di Dashboard Dosen:**
  - File: `universe/module/content/penelitian/karya.php`
  - *Refactor:* Menyesuaikan query *Penelitian yang Berkaitan Langsung* menjadi `WHERE nip_dosen='{$_SESSION['ses_user']}'` (hanya menampilkan kegiatan milik dosen yang bersangkutan) tanpa menggunakan pemanggilan kolom `status` pada tabel ekstensi yang berpotensi memicu *Fatal Error* di environment Universe.

---

### Langkah Berikutnya (*Next Steps*)
Jika nanti kita akan melakukan *refactoring* pada fitur penelitian lain seperti **Jurnal** atau **Prosiding**, kita dapat mengacu pada pola perbaikan yang sama:
1. Pastikan *prefix parameter kelompok* untuk relasi ke tabel `kode` sudah benar.
2. Buat fungsi `Upload...()` tersendiri di `config/upload.php` (baik di sisi admin maupun dosen) beserta penyesuaian *API Key* jika diperlukan.
3. Sesuaikan alur `insert` dan `update` agar menggunakan path direktori spesifik (misal: `file/jurnal/`).
4. Update kueri indikator *pending* di `sidebar.php` jika diperlukan.
