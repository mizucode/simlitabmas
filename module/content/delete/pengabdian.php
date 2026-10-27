<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	if (@$folder[3] == 'pengabdian') {
		$id = mysqli_real_escape_string($con, @$folder[4]);

		$qfile = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pengabdian'");
		while ($rf = mysqli_fetch_array($qfile)) {
			if (file_exists($rf['url'])) @unlink($rf['url']);
		}
		mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pengabdian'");
		mysqli_query($con, "DELETE FROM pengabdian_pengabdian WHERE id='$id'");

		header('location:/pengabdian/pengabdian/?m=deleted');

	} elseif (@$folder[3] == 'pembicara') {
		$id = mysqli_real_escape_string($con, @$folder[4]);

		$qfile = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pembicara'");
		while ($rf = mysqli_fetch_array($qfile)) {
			if (file_exists($rf['url'])) @unlink($rf['url']);
		}
		mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pembicara'");
		mysqli_query($con, "DELETE FROM pengabdian_pembicara WHERE id='$id'");

		header('location:/pengabdian/pembicara/?m=deleted');

	} elseif (@$folder[3] == 'jabatan') {
		$id = mysqli_real_escape_string($con, @$folder[4]);

		$qfile = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_jabatan'");
		while ($rf = mysqli_fetch_array($qfile)) {
			if (file_exists($rf['url'])) @unlink($rf['url']);
		}
		mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_jabatan'");
		mysqli_query($con, "DELETE FROM pengabdian_jabatan WHERE id='$id'");

		header('location:/pengabdian/jabatan/?m=deleted');

	} elseif (@$folder[3] == 'anggota_pembicara') {
		$id_regis = mysqli_real_escape_string($con, @$folder[4]);
		$q_get = mysqli_query($con, "SELECT id_porto FROM regis_dosen_penelitian WHERE id='$id_regis'");
		$d_get = mysqli_fetch_array($q_get);
		$id_porto = $d_get ? $d_get['id_porto'] : '';

		mysqli_query($con, "DELETE FROM regis_dosen_penelitian WHERE id='$id_regis'");

		if (!empty($id_porto)) {
			header("location:/pengabdian/pembicara/anggota/$id_porto?m=deleted");
		} else {
			header('location:/pengabdian/pembicara/?m=deleted');
		}

	} elseif (@$folder[3] == 'anggota_jabatan') {
		$id_regis = mysqli_real_escape_string($con, @$folder[4]);
		$q_get = mysqli_query($con, "SELECT id_porto FROM regis_dosen_penelitian WHERE id='$id_regis'");
		$d_get = mysqli_fetch_array($q_get);
		$id_porto = $d_get ? $d_get['id_porto'] : '';

		mysqli_query($con, "DELETE FROM regis_dosen_penelitian WHERE id='$id_regis'");

		if (!empty($id_porto)) {
			header("location:/pengabdian/jabatan/anggota/$id_porto?m=deleted");
		} else {
			header('location:/pengabdian/jabatan/?m=deleted');
		}

	} elseif (@$folder[3] == 'anggota') {
		$id_regis = mysqli_real_escape_string($con, @$folder[4]);
		$q_get = mysqli_query($con, "SELECT id_porto FROM regis_dosen_penelitian WHERE id='$id_regis'");
		$d_get = mysqli_fetch_array($q_get);
		$id_porto = $d_get ? $d_get['id_porto'] : '';

		mysqli_query($con, "DELETE FROM regis_dosen_penelitian WHERE id='$id_regis'");

		if (!empty($id_porto)) {
			header("location:/pengabdian/pengabdian/anggota/$id_porto?m=deleted");
		} else {
			header('location:/pengabdian/pengabdian/?m=deleted');
		}

	} else {
		header('location:/pengabdian/pengabdian');
	}
}
?>
