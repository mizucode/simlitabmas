<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	if (@$folder[3] == 'penelitian') {
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
				if (strlen($gabungan) > 3)
					$gabungan = $lama_angka . $lama_satuan;
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

		// Cek apakah judul penelitian sudah ada
		$q_check = mysqli_query($con, "SELECT id FROM penelitian_penelitian WHERE judul_kegiatan='$judul_kegiatan'");
		if (mysqli_num_rows($q_check) > 0) {
			// Jika sudah ada, masukkan ke tabel pivot (regis_dosen_penelitian)
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];

			$date_regis = date('Y-m-d');
			$time_regis = date('H:i:s');
			$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);

			$query_pivot = "INSERT INTO regis_dosen_penelitian (
				id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
			) VALUES (
				'$id_porto', '$nip_dosen', '$id_peran', 'Penelitian', '$date_regis', '$time_regis', '$jenis_file', '$registered_by'
			)";

			if (mysqli_query($con, $query_pivot)) {
				$id_regis = mysqli_insert_id($con);
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "anggota_penelitian_" . $id_porto . "_" . time() . "." . $ext;
					UploadPenelitian($new_name);
					$path = "file/penelitian/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_penelitian', '$jenis_file', '$path', '$date_regis', '$time_regis')");
				}
				header('location:/penelitian/penelitian/?m=inserted');
			} else {
				header('location:/penelitian/penelitian/?m=not_inserted');
			}
		} else {
			// Jika belum ada, buat record baru di penelitian_penelitian
			$query = "INSERT INTO penelitian_penelitian (
				nip_dosen, judul_kegiatan, afiliasi, id_skema, thn_usulan, tgl_mulai_kegiatan,
				lama_kegiatan, thn_pelaksanaan_ke, dana_dikti, dana_kampus, dana_mandiri,
				dana_lain, id_peran, no_sk, tgl_sk, mitra, libatkan_mhs, rujukan_ta,
				kelompok_bidang, lokasi, date_modif, time_modif
			) VALUES (
				'$nip_dosen', '$judul_kegiatan', '$afiliasi', '$id_skema', '$thn_usulan', '$tgl_mulai_kegiatan',
				'$lama_kegiatan', '$thn_pelaksanaan_ke', '$dana_dikti', '$dana_kampus', '$dana_mandiri',
				'$dana_lain', '$id_peran', '$no_sk', '$tgl_sk', '$mitra', '$libatkan_mhs', '$rujukan_ta',
				'$kelompok_bidang', '$lokasi', CURDATE(), CURTIME()
			)";

			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);
				
				// Daftarkan pembuat ke tabel pivot anggota
				$date_regis = date('Y-m-d');
				$time_regis = date('H:i:s');
				$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
				$query_pivot_new = "INSERT INTO regis_dosen_penelitian (
					id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
				) VALUES (
					'$id_cat', '$nip_dosen', '$id_peran', 'Penelitian', '$date_regis', '$time_regis', '$jenis_file', '$registered_by'
				)";
				mysqli_query($con, $query_pivot_new);

				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "penelitian_" . $id_cat . "_" . time() . "." . $ext;
					UploadPenelitian($new_name);
					$path = "file/penelitian/" . $new_name;
					$tgl_sekarang = date('Y-m-d');
					$jam_sekarang = date('H:i:s');
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_penelitian', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/penelitian/penelitian/?m=inserted');
			} else {
				header('location:/penelitian/penelitian/?m=not_inserted');
			}
		}
	} elseif (@$folder[3] == 'anggota') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$jenis_porto = 'Penelitian';
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');

		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);

		$path_file = '';
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_penelitian_" . $id_porto . "_" . time() . "." . $ext;
			UploadPenelitian($new_name);
			$path_file = "file/penelitian/" . $new_name;
		}

		$registered_by = mysqli_real_escape_string($con, $_SESSION['ses_user']);

		$query = "INSERT INTO regis_dosen_penelitian (
			id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
		) VALUES (
			'$id_porto', '$nik', '$role', '$jenis_porto', '$date_regis', '$time_regis', '$type_file', '$registered_by'
		)";

		if (mysqli_query($con, $query)) {
			$id_regis = mysqli_insert_id($con);
			if (!empty($path_file)) {
				$tgl_sekarang = date('Y-m-d');
				$jam_sekarang = date('H:i:s');
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik', 'file_penelitian', '$type_file', '$path_file', '$tgl_sekarang', '$jam_sekarang')");
			}

			// Duplikat data ke penelitian_penelitian untuk dosen anggota baru
			$q_orig = mysqli_query($con, "SELECT * FROM penelitian_penelitian WHERE id = '$id_porto'");
			if ($orig = mysqli_fetch_array($q_orig)) {
				// Jika role berisi teks 'Ketua'/'Anggota' (legacy), sesuaikan. Jika berisi angka dari tabel kode, langsung gunakan
				$new_id_peran = (is_numeric($role)) ? $role : (($role == 'Ketua') ? '1' : '2');

				$judul_kegiatan = mysqli_real_escape_string($con, $orig['judul_kegiatan']);
				$afiliasi = mysqli_real_escape_string($con, $orig['afiliasi']);
				$id_skema = mysqli_real_escape_string($con, $orig['id_skema']);
				$thn_usulan = mysqli_real_escape_string($con, $orig['thn_usulan']);
				$tgl_mulai_kegiatan = mysqli_real_escape_string($con, $orig['tgl_mulai_kegiatan']);
				$lama_kegiatan = mysqli_real_escape_string($con, $orig['lama_kegiatan']);
				$thn_pelaksanaan_ke = mysqli_real_escape_string($con, $orig['thn_pelaksanaan_ke']);
				$dana_dikti = mysqli_real_escape_string($con, $orig['dana_dikti']);
				$dana_kampus = mysqli_real_escape_string($con, $orig['dana_kampus']);
				$dana_mandiri = mysqli_real_escape_string($con, $orig['dana_mandiri']);
				$dana_lain = mysqli_real_escape_string($con, $orig['dana_lain']);
				$no_sk = mysqli_real_escape_string($con, $orig['no_sk']);
				$tgl_sk = mysqli_real_escape_string($con, $orig['tgl_sk']);
				$mitra = mysqli_real_escape_string($con, $orig['mitra']);
				$libatkan_mhs = mysqli_real_escape_string($con, $orig['libatkan_mhs']);
				$rujukan_ta = mysqli_real_escape_string($con, $orig['rujukan_ta']);
				$kelompok_bidang = mysqli_real_escape_string($con, $orig['kelompok_bidang']);
				$lokasi = mysqli_real_escape_string($con, $orig['lokasi']);

				// Periksa apakah sudah ada (mencegah duplikat jika form di-resubmit)
				$q_cek_dup = mysqli_query($con, "SELECT id FROM penelitian_penelitian WHERE judul_kegiatan = '$judul_kegiatan' AND nip_dosen = '$nik'");
				if (mysqli_num_rows($q_cek_dup) == 0) {
					$insert_penelitian = "INSERT INTO penelitian_penelitian (
				nip_dosen, judul_kegiatan, afiliasi, id_skema, thn_usulan, tgl_mulai_kegiatan,
				lama_kegiatan, thn_pelaksanaan_ke, dana_dikti, dana_kampus, dana_mandiri,
				dana_lain, id_peran, no_sk, tgl_sk, mitra, libatkan_mhs, rujukan_ta,
				kelompok_bidang, lokasi, date_modif, time_modif
			) VALUES (
				'$nik', '$judul_kegiatan', '$afiliasi', '$id_skema', '$thn_usulan', '$tgl_mulai_kegiatan',
				'$lama_kegiatan', '$thn_pelaksanaan_ke', '$dana_dikti', '$dana_kampus', '$dana_mandiri',
				'$dana_lain', '$new_id_peran', '$no_sk', '$tgl_sk', '$mitra', '$libatkan_mhs', '$rujukan_ta',
				'$kelompok_bidang', '$lokasi', CURDATE(), CURTIME()
			)";
					mysqli_query($con, $insert_penelitian);
				}
			}

			header("location:/penelitian/penelitian/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=not_inserted");
		}
	} elseif (@$folder[3] == 'patenhki') {
		// Logika handler untuk patenhki jika diperlukan
		header('location:/penelitian/penelitian/?m=inserted');
	} else {
		header('location:/penelitian/penelitian');
	}
}
?>