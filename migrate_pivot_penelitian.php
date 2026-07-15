<?php
define('valid', '1');
include "config/db.php";

echo "<h3>Memulai Migrasi Duplikat Penelitian ke Pivot (Anggota)</h3>";

// Cari judul penelitian yang memiliki lebih dari 1 record (duplikat)
$q = mysqli_query($con, "SELECT judul_kegiatan, COUNT(*) as jml FROM penelitian_penelitian GROUP BY judul_kegiatan HAVING jml > 1");

$total_migrated = 0;

while ($r = mysqli_fetch_array($q)) {
    $judul = mysqli_real_escape_string($con, $r['judul_kegiatan']);
    
    // Ambil semua record dengan judul tersebut, urutkan dari ID terkecil (yg pertama dibuat)
    $q_all = mysqli_query($con, "SELECT * FROM penelitian_penelitian WHERE judul_kegiatan='$judul' ORDER BY id ASC");
    
    $is_first = true;
    $main_id = 0;
    
    while ($row = mysqli_fetch_array($q_all)) {
        if ($is_first) {
            // Record pertama dijadikan sebagai Data Utama (Master)
            $main_id = $row['id'];
            $is_first = false;
        } else {
            // Record kedua, ketiga, dst dipindahkan ke tabel pivot (regis_dosen_penelitian)
            $duplicate_id = $row['id'];
            $nip_dosen = $row['nip_dosen'];
            $id_peran = $row['id_peran']; // Role 
            
            // 1. Insert dosen ini ke tabel pivot
            $q_insert_pivot = "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$main_id', '$nip_dosen', '$id_peran', 'Penelitian', CURDATE(), CURTIME(), 'Sistem Migrasi')";
            
            if(mysqli_query($con, $q_insert_pivot)) {
                $new_pivot_id = mysqli_insert_id($con);
                
                // 2. Pindahkan file milik dosen ini di file_dosen
                // Ubah id_cat menjadi ID pivot yg baru, dan ubah kelompok menjadi file_anggota_penelitian
                mysqli_query($con, "UPDATE file_dosen SET id_cat='$new_pivot_id', kelompok='file_anggota_penelitian' WHERE id_cat='$duplicate_id' AND kelompok='file_penelitian'");
                
                // 3. Hapus data duplikat dari tabel penelitian utama
                mysqli_query($con, "DELETE FROM penelitian_penelitian WHERE id='$duplicate_id'");
                
                echo "Berhasil memigrasi dosen NIP: $nip_dosen ke dalam penelitian ID: $main_id (Judul: $judul)<br>";
                $total_migrated++;
            }
        }
    }
}

echo "<h4>Selesai! Total record yang dipindahkan ke tabel pivot: $total_migrated</h4>";
?>
