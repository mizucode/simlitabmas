<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	if (@$folder[3] == 'jurnal') {
		$id = mysqli_real_escape_string($con, @$folder[4]);
		
		$q_cek = mysqli_query($con, "SELECT file_akreditasi, cover_url FROM referensi_jurnal WHERE id='$id'");
		if ($d_cek = mysqli_fetch_assoc($q_cek)) {
			if (!empty($d_cek['file_akreditasi']) && file_exists($d_cek['file_akreditasi'])) {
				unlink($d_cek['file_akreditasi']);
			}
			if (!empty($d_cek['cover_url']) && file_exists($d_cek['cover_url'])) {
				unlink($d_cek['cover_url']);
			}
		}

		$query = "DELETE FROM referensi_jurnal WHERE id = '$id'";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/jurnal/?m=deleted');
		} else {
			header('location:/referensi/jurnal/?m=not_deleted');
		}

	} elseif (@$folder[3] == 'klaster') {
		$id = mysqli_real_escape_string($con, @$folder[4]);
		
		$q_cek = mysqli_query($con, "SELECT file_sk_klaster FROM referensi_klaster WHERE id='$id'");
		if ($d_cek = mysqli_fetch_assoc($q_cek)) {
			if (!empty($d_cek['file_sk_klaster']) && file_exists($d_cek['file_sk_klaster'])) {
				unlink($d_cek['file_sk_klaster']);
			}
		}

		$query = "DELETE FROM referensi_klaster WHERE id = '$id'";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/klaster/?m=deleted');
		} else {
			header('location:/referensi/klaster/?m=not_deleted');
		}
	}
}
?>
