<?php
define('valid', '1');
include "config/db.php";

echo "Memulai migrasi file penelitian dari file_dosen ke regis_dosen_penelitian...\n";

// Ambil semua file penelitian dari tabel file_dosen
$q_file = mysqli_query($con, "SELECT fd.id_cat, fd.url, k.desc as type_file 
                              FROM file_dosen fd 
                              JOIN kode k ON fd.kelompok = k.kelompok AND fd.value_kode = k.value 
                              WHERE fd.kelompok = 'file_penelitian'");

$count = 0;
while ($row = mysqli_fetch_array($q_file)) {
    $id_porto = $row['id_cat'];
    $url = mysqli_real_escape_string($con, $row['url']);
    $type = mysqli_real_escape_string($con, $row['type_file']);
    
    // Update ke regis_dosen_penelitian, prioritas pada Ketua
    $update = mysqli_query($con, "UPDATE regis_dosen_penelitian 
                                  SET url_file = '$url', type_file = '$type' 
                                  WHERE id_porto = '$id_porto' AND jenis_porto = 'Penelitian' AND role = 'Ketua' 
                                  LIMIT 1");
    if ($update && mysqli_affected_rows($con) > 0) {
        $count++;
    }
}

echo "Berhasil memigrasikan $count file ke data Ketua di regis_dosen_penelitian.\n";
?>
