<?php
$file = 'module/content/delete/penelitian.php';
$content = file_get_contents($file);

$types = [
    'penelitian' => 'penelitian_penelitian',
    'patenhki' => 'penelitian_patenhki',
    'karya' => 'penelitian_karya',
    'jurnal' => 'penelitian_jurnal',
    'prosiding' => 'penelitian_prosiding',
    'buku' => 'penelitian_buku'
];

foreach ($types as $type => $table) {
    $kelompok = "file_" . $type;
    $judul_field = ($type == 'penelitian') ? 'judul_kegiatan' : 'judul';
    
    // Replace file_dosen deletion for anggota
    // Original might have @unlink($rf['url']) with or without braces
    
    $match = '/\$qfile = mysqli_query\(\$con, "SELECT url FROM file_dosen WHERE id_cat=\'\$id_porto\' AND nip_dosen=\'\$nik\' AND kelompok=\'' . $kelompok . '\'"\);\s*while \(\$rf = mysqli_fetch_array\(\$qfile\)\) \{(.*?)\}\s*mysqli_query\(\$con, "DELETE FROM file_dosen WHERE id_cat=\'\$id_porto\' AND nip_dosen=\'\$nik\' AND kelompok=\'' . $kelompok . '\'"\);/s';
    
    $replace = "\$qfile = mysqli_query(\$con, \"SELECT url FROM file_dosen WHERE (id_cat='\$id_porto' OR id_cat IN (SELECT id FROM $table WHERE $judul_field='\$judul_kegiatan')) AND nip_dosen='\$nik' AND kelompok='$kelompok'\");
\t\t\twhile (\$rf = mysqli_fetch_array(\$qfile)) {
\t\t\t\tif (file_exists(\$rf['url'])) @unlink(\$rf['url']);
\t\t\t}
\t\t\tmysqli_query(\$con, \"DELETE FROM file_dosen WHERE (id_cat='\$id_porto' OR id_cat IN (SELECT id FROM $table WHERE $judul_field='\$judul_kegiatan')) AND nip_dosen='\$nik' AND kelompok='$kelompok'\");";

    $content = preg_replace($match, $replace, $content);
}

file_put_contents($file, $content);
echo "Done replacing delete blocks\n";
?>
