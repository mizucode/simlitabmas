<?php
$file = 'module/content/insert/penelitian.php';
$content = file_get_contents($file);

$function_code = "
function duplicatePortfolioRecord(\$con, \$jenis_porto, \$id_porto, \$nik, \$role) {
    \$table_name = \"penelitian_\$jenis_porto\";
    if (strtolower(\$jenis_porto) == 'penelitian') \$table_name = 'penelitian_penelitian';
    
    \$q_orig = mysqli_query(\$con, \"SELECT * FROM \$table_name WHERE id = '\$id_porto'\");
    if (\$orig = mysqli_fetch_assoc(\$q_orig)) {
        \$cols = [];
        \$vals = [];
        foreach (\$orig as \$key => \$val) {
            if (\$key == 'id') continue;
            
            if (\$key == 'nip_dosen') {
                \$val = \$nik;
            } elseif (\$key == 'date_modif') {
                \$val = date('Y-m-d');
            } elseif (\$key == 'time_modif') {
                \$val = date('H:i:s');
            } elseif (\$key == 'id_peran' && strtolower(\$jenis_porto) == 'penelitian') {
                \$val = (is_numeric(\$role)) ? \$role : ((\$role == 'Ketua') ? '1' : '2');
            }
            
            \$cols[] = \$key;
            if (\$val === null) {
                \$vals[] = \"NULL\";
            } else {
                \$vals[] = \"'\" . mysqli_real_escape_string(\$con, \$val) . \"'\";
            }
        }
        
        \$judul_field = (strtolower(\$jenis_porto) == 'penelitian') ? 'judul_kegiatan' : 'judul';
        \$judul_val = mysqli_real_escape_string(\$con, \$orig[\$judul_field]);
        \$q_cek = mysqli_query(\$con, \"SELECT id FROM \$table_name WHERE \$judul_field = '\$judul_val' AND nip_dosen = '\$nik'\");
        
        if (mysqli_num_rows(\$q_cek) == 0) {
            \$query_dup = \"INSERT INTO \$table_name (\" . implode(', ', \$cols) . \") VALUES (\" . implode(', ', \$vals) . \")\";
            mysqli_query(\$con, \$query_dup);
            return mysqli_insert_id(\$con);
        } else {
            \$d_cek = mysqli_fetch_assoc(\$q_cek);
            return \$d_cek['id'];
        }
    }
    return \$id_porto; // fallback
}

";

if (strpos($content, 'function duplicatePortfolioRecord') === false) {
    // Insert after "} else {" which is at the beginning of the file logic
    $content = preg_replace('/(} else \{\s+)/', "$1" . $function_code, $content, 1);
}

// Array of replacements for each type
$types = [
    'penelitian' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+\$tgl_sekarang = date\(\'Y-m-d\'\);\s+\$jam_sekarang = date\(\'H:i:s\'\);\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_penelitian\', \'\$type_file\', \'\$path_file\', \'\$tgl_sekarang\', \'\$jam_sekarang\'\)"\);\s+\}\s+\/\/ Duplikat data ke penelitian_penelitian untuk dosen anggota baru(.*?)\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'penelitian', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\t\$tgl_sekarang = date('Y-m-d');\n\t\t\t\t\$jam_sekarang = date('H:i:s');\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_penelitian', '\$type_file', '\$path_file', '\$tgl_sekarang', '\$jam_sekarang')\");\n\t\t\t}\n\n\t\t\theader(\"location:/penelitian/penelitian/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ],
    'patenhki' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_patenhki\', \'\$type_file\', \'\$path_file\', \'\$date_regis\', \'\$time_regis\'\)"\);\s+\}\s+header\("location:\/penelitian\/patenhki\/anggota\/\$id_porto\?m=inserted"\);\s+\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'patenhki', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_patenhki', '\$type_file', '\$path_file', '\$date_regis', '\$time_regis')\");\n\t\t\t}\n\t\t\theader(\"location:/penelitian/patenhki/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ],
    'karya' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_karya\', \'\$type_file\', \'\$path_file\', \'\$date_regis\', \'\$time_regis\'\)"\);\s+\}\s+header\("location:\/penelitian\/karya\/anggota\/\$id_porto\?m=inserted"\);\s+\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'karya', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_karya', '\$type_file', '\$path_file', '\$date_regis', '\$time_regis')\");\n\t\t\t}\n\t\t\theader(\"location:/penelitian/karya/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ],
    'jurnal' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_jurnal\', \'\$type_file\', \'\$path_file\', \'\$date_regis\', \'\$time_regis\'\)"\);\s+\}\s+header\("location:\/penelitian\/jurnal\/anggota\/\$id_porto\?m=inserted"\);\s+\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'jurnal', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_jurnal', '\$type_file', '\$path_file', '\$date_regis', '\$time_regis')\");\n\t\t\t}\n\t\t\theader(\"location:/penelitian/jurnal/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ],
    'prosiding' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_prosiding\', \'\$type_file\', \'\$path_file\', \'\$date_regis\', \'\$time_regis\'\)"\);\s+\}\s+header\("location:\/penelitian\/prosiding\/anggota\/\$id_porto\?m=inserted"\);\s+\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'prosiding', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_prosiding', '\$type_file', '\$path_file', '\$date_regis', '\$time_regis')\");\n\t\t\t}\n\t\t\theader(\"location:/penelitian/prosiding/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ],
    'buku' => [
        'match' => '/if \(mysqli_query\(\$con, \$query\)\) \{\s+\$id_regis = mysqli_insert_id\(\$con\);\s+if \(\!empty\(\$path_file\)\) \{\s+mysqli_query\(\$con, "INSERT INTO file_dosen \(id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif\) VALUES \(\'\$id_porto\', \'\$nik\', \'file_buku\', \'\$type_file\', \'\$path_file\', \'\$date_regis\', \'\$time_regis\'\)"\);\s+\}\s+header\("location:\/penelitian\/buku\/anggota\/\$id_porto\?m=inserted"\);\s+\} else \{/s',
        'replace' => "if (mysqli_query(\$con, \$query)) {\n\t\t\t\$id_regis = mysqli_insert_id(\$con);\n\t\t\t\$new_id_cat = duplicatePortfolioRecord(\$con, 'buku', \$id_porto, \$nik, \$role);\n\t\t\tif (!empty(\$path_file)) {\n\t\t\t\tmysqli_query(\$con, \"INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('\$new_id_cat', '\$nik', 'file_buku', '\$type_file', '\$path_file', '\$date_regis', '\$time_regis')\");\n\t\t\t}\n\t\t\theader(\"location:/penelitian/buku/anggota/\$id_porto?m=inserted\");\n\t\t} else {"
    ]
];

foreach ($types as $type => $data) {
    $content = preg_replace($data['match'], $data['replace'], $content);
}

file_put_contents($file, $content);
echo "Done replacing insert blocks\n";
?>
