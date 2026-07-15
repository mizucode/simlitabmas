<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	if (@$folder[3] == 'penelitian') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		$judul_kegiatan = mysqli_real_escape_string($con, $_POST['judul_kegiatan']);
		$afiliasi = mysqli_real_escape_string($con, $_POST['afiliasi']);
		$id_skema = mysqli_real_escape_string($con, $_POST['id_skema']);
		$thn_usulan = mysqli_real_escape_string($con, $_POST['thn_usulan']);
		$tgl_mulai_kegiatan = mysqli_real_escape_string($con, $_POST['tgl_mulai_kegiatan']);
		if (isset($_POST['lama_angka']) && isset($_POST['lama_satuan'])) {
			$lama_angka = trim($_POST['lama_angka']);
			$lama_satuan = trim($_POST['lama_satuan']);
			if ($lama_angka !== '') {
				$gabungan = $lama_angka . ' ' . $lama_satuan;
				if (strlen($gabungan) > 3) $gabungan = $lama_angka . $lama_satuan;
				$lama_kegiatan = mysqli_real_escape_string($con, substr($gabungan, 0, 3));
			} else {
				$lama_kegiatan = '';
			}
		} else {
			$lama_kegiatan = substr(mysqli_real_escape_string($con, @$_POST['lama_kegiatan']), 0, 3);
		}
		$thn_pelaksanaan_ke = mysqli_real_escape_string($con, $_POST['thn_pelaksanaan_ke']);
		$dana_dikti = mysqli_real_escape_string($con, $_POST['dana_dikti']);
		$dana_kampus = mysqli_real_escape_string($con, $_POST['dana_kampus']);
		$dana_mandiri = mysqli_real_escape_string($con, $_POST['dana_mandiri']);
		$dana_lain = mysqli_real_escape_string($con, $_POST['dana_lain']);
		$id_peran = mysqli_real_escape_string($con, $_POST['id_peran']);
		$no_sk = mysqli_real_escape_string($con, $_POST['no_sk']);
		$tgl_sk = mysqli_real_escape_string($con, $_POST['tgl_sk']);
		$mitra = mysqli_real_escape_string($con, $_POST['mitra']);
		$libatkan_mhs = isset($_POST['libatkan_mhs']) ? 'Ya' : 'Tidak';
		$rujukan_ta = isset($_POST['rujukan_ta']) ? 'Ya' : 'Tidak';
		$kelompok_bidang = mysqli_real_escape_string($con, $_POST['kelompok_bidang']);
		$lokasi = mysqli_real_escape_string($con, $_POST['lokasi']);
		$jenis_file = mysqli_real_escape_string($con, $_POST['jenis_file']);

		// Dapatkan judul asli sebelum diupdate
		$q_old = mysqli_query($con, "SELECT judul_kegiatan FROM penelitian_penelitian WHERE id='$id'");
		$old_data = mysqli_fetch_array($q_old);
		$old_judul = mysqli_real_escape_string($con, $old_data['judul_kegiatan']);

		// Update seluruh record yang memiliki judul asli yang sama
		$query = "UPDATE penelitian_penelitian SET 
			judul_kegiatan='$judul_kegiatan',
			afiliasi='$afiliasi',
			id_skema='$id_skema',
			thn_usulan='$thn_usulan',
			tgl_mulai_kegiatan='$tgl_mulai_kegiatan',
			lama_kegiatan='$lama_kegiatan',
			thn_pelaksanaan_ke='$thn_pelaksanaan_ke',
			dana_dikti='$dana_dikti',
			dana_kampus='$dana_kampus',
			dana_mandiri='$dana_mandiri',
			dana_lain='$dana_lain',
			no_sk='$no_sk',
			tgl_sk='$tgl_sk',
			mitra='$mitra',
			libatkan_mhs='$libatkan_mhs',
			rujukan_ta='$rujukan_ta',
			kelompok_bidang='$kelompok_bidang',
			lokasi='$lokasi',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE judul_kegiatan='$old_judul'";

		if (mysqli_query($con, $query)) {
			if (!empty($_FILES['file']['name']) && !empty($jenis_file)) {
				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "penelitian_" . $id . "_" . time() . "." . $ext;
				UploadPenelitian($new_name);
				$path = "file/penelitian/" . $new_name;

				// Hapus file lama jika ada
				$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_penelitian'");
				while ($r_old = mysqli_fetch_array($q_old_file)) {
					if (file_exists($r_old['url'])) @unlink($r_old['url']);
				}
				mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_penelitian'");

				$tgl_sekarang = date('Y-m-d');
				$jam_sekarang = date('H:i:s');
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id', '$nip_dosen', 'file_penelitian', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/penelitian';
			header("location:" . $redirect);
		} else {
			$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/penelitian';
			header("location:" . $redirect);
		}
	} elseif (@$folder[3] == 'anggota') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_penelitian_" . $id_porto . "_" . time() . "." . $ext;
			UploadPenelitian($new_name);
			$path = "file/penelitian/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_penelitian'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_penelitian'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_penelitian', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role', type_file = '$type_file' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'patenhki') {
		// Logika update untuk patenhki jika diperlukan
		header('location:/penelitian/penelitian/?m=updated');
	} else {
		header('location:/penelitian/penelitian');
	}
}
?>