<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	if (@$folder[3] == 'penelitian') {
		$id = mysqli_real_escape_string($con, @$folder[4]);
		
		// Ambil judul kegiatan untuk menghapus duplikat dan relasi anggotanya
		$judul_kegiatan = '';
		$q_orig = mysqli_query($con, "SELECT judul_kegiatan FROM penelitian_penelitian WHERE id = '$id'");
		if ($orig = mysqli_fetch_array($q_orig)) {
			$judul_kegiatan = mysqli_real_escape_string($con, $orig['judul_kegiatan']);
		}
		
		// Hapus file fisik utama jika ada
		$qfile = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_penelitian'");
		while ($rf = mysqli_fetch_array($qfile)) {
			if (file_exists($rf['url'])) {
				@unlink($rf['url']);
			}
		}
		
		// Hapus record file dari database (utama)
		mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_penelitian'");
		
		// Hapus anggota (pivot table) terkait dan filenya
		$q_anggota = mysqli_query($con, "SELECT id, nik FROM regis_dosen_penelitian WHERE id_porto='$id'");
		while ($r_anggota = mysqli_fetch_array($q_anggota)) {
			$nik_anggota = $r_anggota['nik'];
			$qfile_a = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND nip_dosen='$nik_anggota' AND kelompok='file_penelitian'");
			while ($rfa = mysqli_fetch_array($qfile_a)) {
				if (file_exists($rfa['url'])) @unlink($rfa['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND nip_dosen='$nik_anggota' AND kelompok='file_penelitian'");
		}
		mysqli_query($con, "DELETE FROM regis_dosen_penelitian WHERE id_porto='$id'");

		// Hapus record utama dan seluruh duplikatnya di database
		if (mysqli_query($con, "DELETE FROM penelitian_penelitian WHERE id='$id'")) {
			if (!empty($judul_kegiatan)) {
				mysqli_query($con, "DELETE FROM penelitian_penelitian WHERE judul_kegiatan='$judul_kegiatan'");
			}
			header('location:/penelitian/penelitian/?m=deleted');
		} else {
			header('location:/penelitian/penelitian/?m=not_deleted');
		}
	} elseif (@$folder[3] == 'anggota') {
		$id_regis = mysqli_real_escape_string($con, @$folder[4]);
		
		$q = mysqli_query($con, "SELECT id_porto, nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
		if ($row = mysqli_fetch_array($q)) {
			$id_porto = $row['id_porto'];
			$nik = $row['nik'];
			
			// Ambil judul kegiatan untuk menghapus duplikat di penelitian_penelitian
			$judul_kegiatan = '';
			$q_orig = mysqli_query($con, "SELECT judul_kegiatan FROM penelitian_penelitian WHERE id = '$id_porto'");
			if ($orig = mysqli_fetch_array($q_orig)) {
				$judul_kegiatan = mysqli_real_escape_string($con, $orig['judul_kegiatan']);
			}
			
			// Hapus file terkait anggota ini (berdasarkan id_porto dan nik)
			$qfile = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik' AND kelompok='file_penelitian'");
			while ($rf = mysqli_fetch_array($qfile)) {
				if (file_exists($rf['url'])) {
					@unlink($rf['url']);
				}
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik' AND kelompok='file_penelitian'");
			
			if (mysqli_query($con, "DELETE FROM regis_dosen_penelitian WHERE id='$id_regis'")) {
				// Hapus data duplikat di tabel penelitian_penelitian
				if (!empty($judul_kegiatan)) {
					mysqli_query($con, "DELETE FROM penelitian_penelitian WHERE judul_kegiatan = '$judul_kegiatan' AND nip_dosen = '$nik' AND id != '$id_porto'");
				}
				header("location:/penelitian/penelitian/anggota/$id_porto?m=deleted");
			} else {
				header("location:/penelitian/penelitian/anggota/$id_porto?m=not_deleted");
			}
		} else {
			header("location:/penelitian/penelitian");
		}
	} elseif (@$folder[3] == 'patenhki') {
		// Logika delete untuk patenhki jika diperlukan
		$id = mysqli_real_escape_string($con, @$folder[4]);
		// mysqli_query($con, "DELETE FROM tabel_patenhki WHERE id='$id'");
		header('location:/penelitian/penelitian/?m=deleted');
	} else {
		header('location:/penelitian/penelitian');
	}
}
?>