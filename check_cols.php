<?php
define('valid', 1);
require 'config/db.php';
$tables = ['penelitian_penelitian', 'penelitian_patenhki', 'penelitian_karya', 'penelitian_jurnal', 'penelitian_prosiding', 'penelitian_buku'];
foreach ($tables as $table) {
    $q = mysqli_query($con, "SHOW COLUMNS FROM $table");
    echo $table . ': ';
    $cols = [];
    while ($r = mysqli_fetch_array($q)) { $cols[] = $r['Field']; }
    echo implode(', ', $cols) . "\n";
}
