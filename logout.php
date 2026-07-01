<?php
  session_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
	}
else {
	define('valid','1');
	include "config/db.php";
	include "config/library.php";
	$date = date("Ymd"); 

	mysqli_query($con,"UPDATE statistik SET online='0' WHERE user='$_SESSION[ses_user]' AND date='$date' ");

	session_destroy();
	echo "<script>alert('Anda telah keluar dari halaman sistem'); window.location = '/login'</script>";
}
?>