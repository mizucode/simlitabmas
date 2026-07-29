<?php
require 'config/koneksi.php';
$q = mysqli_query($con, 'SELECT id, judul_kegiatan FROM pengabdian_pengabdian LIMIT 1');
if (!$q) {
    echo "ERROR: " . mysqli_error($con);
} else {
    echo "OK";
}
