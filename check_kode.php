<?php
define('valid', 1);
require 'config/db.php';
$q = mysqli_query($con, "SELECT DISTINCT kelompok FROM kode");
while ($r = mysqli_fetch_assoc($q)) {
    echo $r['kelompok'] . "\n";
}
$q = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='jenis_porto'");
if (mysqli_num_rows($q) > 0) {
    echo "\n\nJenis Porto:\n";
    while ($r = mysqli_fetch_assoc($q)) {
        echo $r['value'] . ' - ' . $r['desc'] . "\n";
    }
}
