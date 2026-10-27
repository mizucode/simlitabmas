<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

function duplicatePortfolioPengabdian($con, $id_porto, $nik, $role) {
	$q_orig = mysqli_query($con, "SELECT * FROM pengabdian_pengabdian WHERE id = '$id_porto'");
	if ($orig = mysqli_fetch_assoc($q_orig)) {
		// Cek apakah anggota sudah punya row dengan judul yang sama
		$judul_val = mysqli_real_escape_string($con, $orig['judul_kegiatan']);
		$q_cek = mysqli_query($con, "SELECT id FROM pengabdian_pengabdian WHERE judul_kegiatan = '$judul_val' AND nip_dosen = '$nik'");

		if (mysqli_num_rows($q_cek) == 0) {
			$cols = [];
			$vals = [];
			foreach ($orig as $key => $val) {
				if ($key == 'id') continue;

				if ($key == 'nip_dosen') {
					$val = $nik;
				} elseif ($key == 'date_modif') {
					$val = date('Y-m-d');
				} elseif ($key == 'time_modif') {
					$val = date('H:i:s');
				} elseif ($key == 'id_peran') {
					$val = (is_numeric($role)) ? $role : (($role == 'Ketua') ? '1' : '2');
				}

				$cols[] = $key;
				if ($val === null) {
					$vals[] = "NULL";
				} else {
					$vals[] = "'" . mysqli_real_escape_string($con, $val) . "'";
				}
			}

			$query_dup = "INSERT INTO pengabdian_pengabdian (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ")";
			mysqli_query($con, $query_dup);
			return mysqli_insert_id($con);
		} else {
			$d_cek = mysqli_fetch_assoc($q_cek);
			return $d_cek['id'];
		}
	}
	return $id_porto; // fallback
}

	if (@$folder[3] == 'pengabdian') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}

		$judul_kegiatan = mysqli_real_escape_string($con, @$_POST['judul_kegiatan']);
		$afiliasi = mysqli_real_escape_string($con, @$_POST['afiliasi']);
		$id_skema = mysqli_real_escape_string($con, @$_POST['id_skema']);
		$thn_usulan = mysqli_real_escape_string($con, @$_POST['thn_usulan']);
		$tgl_mulai_kegiatan = mysqli_real_escape_string($con, @$_POST['tgl_mulai_kegiatan']);
		$lama_kegiatan = mysqli_real_escape_string($con, @$_POST['lama_kegiatan']);
		$thn_pelaksanaan_ke = mysqli_real_escape_string($con, @$_POST['thn_pelaksanaan_ke']);
		$dana_dikti = mysqli_real_escape_string($con, @$_POST['dana_dikti']);
		$dana_kampus = mysqli_real_escape_string($con, @$_POST['dana_kampus']);
		$dana_mandiri = mysqli_real_escape_string($con, @$_POST['dana_mandiri']);
		$dana_lain = mysqli_real_escape_string($con, @$_POST['dana_lain']);
		$id_peran = mysqli_real_escape_string($con, @$_POST['id_peran']);
		$no_sk = mysqli_real_escape_string($con, @$_POST['no_sk']);
		$tgl_sk = mysqli_real_escape_string($con, @$_POST['tgl_sk']);
		$mitra = mysqli_real_escape_string($con, @$_POST['mitra']);
		$libatkan_mhs = isset($_POST['libatkan_mhs']) ? 'Ya' : 'Tidak';
		$rujukan_ta = isset($_POST['rujukan_ta']) ? 'Ya' : 'Tidak';
		$kelompok_bidang = mysqli_real_escape_string($con, @$_POST['kelompok_bidang']);
		$lokasi = mysqli_real_escape_string($con, @$_POST['lokasi']);
		$jenis_file = mysqli_real_escape_string($con, @$_POST['jenis_file']);

		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		$tgl_sk_val = empty($tgl_sk) ? "'1970-01-01'" : "'$tgl_sk'";
		$tgl_mulai_val = empty($tgl_mulai_kegiatan) ? "'1970-01-01'" : "'$tgl_mulai_kegiatan'";
		$status_val = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';

		$q_check = mysqli_query($con, "SELECT id FROM pengabdian_pengabdian WHERE judul_kegiatan='$judul_kegiatan'");
		if (mysqli_num_rows($q_check) > 0) {
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];

			if (!empty($_FILES['file']['name'])) {
				$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_pengabdian'");
				while ($r_old = mysqli_fetch_array($q_old_file)) {
					if (file_exists($r_old['url'])) @unlink($r_old['url']);
				}
				mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_pengabdian'");

				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "pengabdian_" . $id_porto . "_" . time() . "." . $ext;
				if (function_exists('UploadPengabdian')) {
					UploadPengabdian($new_name);
				} else {
					move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pengabdian/" . $new_name);
				}
				$path = "file/pengabdian/" . $new_name;
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_pengabdian', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			header('location:/pengabdian/pengabdian/?m=inserted');
		} else {
			$query = "INSERT INTO pengabdian_pengabdian (
				nip_dosen, date_modif, time_modif, judul_kegiatan, afiliasi, id_skema, thn_usulan, tgl_mulai_kegiatan,
				lama_kegiatan, thn_pelaksanaan_ke, dana_dikti, dana_kampus, dana_lain, dana_mandiri, id_peran,
				no_sk, tgl_sk, mitra, libatkan_mhs, rujukan_ta, kelompok_bidang, lokasi, status
			) VALUES (
				'$nip_dosen', '$tgl_sekarang', '$jam_sekarang', '$judul_kegiatan', '$afiliasi', '$id_skema', '$thn_usulan', $tgl_mulai_val,
				'$lama_kegiatan', '$thn_pelaksanaan_ke', '$dana_dikti', '$dana_kampus', '$dana_lain', '$dana_mandiri', '$id_peran',
				'$no_sk', $tgl_sk_val, '$mitra', '$libatkan_mhs', '$rujukan_ta', '$kelompok_bidang', '$lokasi', '$status_val'
			)";

			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);

				// Registrasi pengusul utama ke pivot regis_dosen_penelitian
				$date_regis = date('Y-m-d');
				$time_regis = date('H:i:s');
				$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
				$q_pivot_cek = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_cat' AND nik='$nip_dosen' AND (jenis_porto='Pengabdian' OR jenis_porto='pengabdian')");
				if (mysqli_num_rows($q_pivot_cek) == 0) {
					mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_cat', '$nip_dosen', '$id_peran', 'Pengabdian', '$date_regis', '$time_regis', '$registered_by')");
				}

				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "pengabdian_" . $id_cat . "_" . time() . "." . $ext;
					if (function_exists('UploadPengabdian')) {
						UploadPengabdian($new_name);
					} else {
						move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pengabdian/" . $new_name);
					}
					$path = "file/pengabdian/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_pengabdian', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/pengabdian/pengabdian/?m=inserted');
			} else {
				header('location:/pengabdian/pengabdian/?m=not_inserted');
			}
		}

	} elseif (@$folder[3] == 'pembicara') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}

		$id_kategori_kegiatan = mysqli_real_escape_string($con, @$_POST['id_kategori_kegiatan']);
		$id_kategori_pembicara = mysqli_real_escape_string($con, @$_POST['id_kategori_pembicara']);
		$judul = mysqli_real_escape_string($con, @$_POST['judul']);
		$nama_pertemuan = mysqli_real_escape_string($con, @$_POST['nama_pertemuan']);
		$id_tingkat = mysqli_real_escape_string($con, @$_POST['id_tingkat']);
		$penyelenggara = mysqli_real_escape_string($con, @$_POST['penyelenggara']);
		$tgl_pelaksanaan = mysqli_real_escape_string($con, @$_POST['tgl_pelaksanaan']);
		$bahasa = mysqli_real_escape_string($con, @$_POST['bahasa']);
		$no_sk = mysqli_real_escape_string($con, @$_POST['no_sk']);
		$tgl_sk = mysqli_real_escape_string($con, @$_POST['tgl_sk']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$jenis_file = mysqli_real_escape_string($con, @$_POST['jenis_file']);

		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		$tgl_pelaksanaan_val = empty($tgl_pelaksanaan) ? "'1970-01-01'" : "'$tgl_pelaksanaan'";
		$tgl_sk_val = empty($tgl_sk) ? "'1970-01-01'" : "'$tgl_sk'";
		$status_val = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';

		$q_check = mysqli_query($con, "SELECT id FROM pengabdian_pembicara WHERE judul='$judul' AND nip_dosen='$nip_dosen'");
		if (mysqli_num_rows($q_check) > 0) {
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];

			if (!empty($_FILES['file']['name'])) {
				$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_pembicara'");
				while ($r_old = mysqli_fetch_array($q_old_file)) {
					if (file_exists($r_old['url'])) @unlink($r_old['url']);
				}
				mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_pembicara'");

				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "pembicara_" . $id_porto . "_" . time() . "." . $ext;
				if (function_exists('UploadPembicara')) {
					UploadPembicara($new_name);
				} else {
					move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pembicara/" . $new_name);
				}
				$path = "file/pembicara/" . $new_name;
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_pembicara', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			header('location:/pengabdian/pembicara/?m=inserted');
		} else {
			$query = "INSERT INTO pengabdian_pembicara (
				nip_dosen, date_modif, time_modif, id_kategori_kegiatan, id_kategori_pembicara,
				judul, nama_pertemuan, id_tingkat, penyelenggara, tgl_pelaksanaan, bahasa,
				no_sk, tgl_sk, link_lit, link_abmas, status
			) VALUES (
				'$nip_dosen', '$tgl_sekarang', '$jam_sekarang', '$id_kategori_kegiatan', '$id_kategori_pembicara',
				'$judul', '$nama_pertemuan', '$id_tingkat', '$penyelenggara', $tgl_pelaksanaan_val, '$bahasa',
				'$no_sk', $tgl_sk_val, '$link_lit', '$link_abmas', '$status_val'
			)";

			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);

				// Registrasi pembicara utama ke pivot regis_dosen_penelitian
				$date_regis = date('Y-m-d');
				$time_regis = date('H:i:s');
				$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
				$q_pivot_cek = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_cat' AND nik='$nip_dosen' AND (jenis_porto='pembicara' OR jenis_porto='Pembicara')");
				if (mysqli_num_rows($q_pivot_cek) == 0) {
					mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_cat', '$nip_dosen', '1', 'pembicara', '$date_regis', '$time_regis', '$registered_by')");
				}

				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "pembicara_" . $id_cat . "_" . time() . "." . $ext;
					if (function_exists('UploadPembicara')) {
						UploadPembicara($new_name);
					} else {
						move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pembicara/" . $new_name);
					}
					$path = "file/pembicara/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_pembicara', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/pengabdian/pembicara/?m=inserted');
			} else {
				header('location:/pengabdian/pembicara/?m=not_inserted');
			}
		}

	} elseif (@$folder[3] == 'jabatan') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}

		$id_jenis = mysqli_real_escape_string($con, @$_POST['id_jenis']);
		if (empty($id_jenis)) $id_jenis = mysqli_real_escape_string($con, @$_POST['id_jabatan']);
		$no_sk = mysqli_real_escape_string($con, @$_POST['no_sk']);
		$tmt = mysqli_real_escape_string($con, @$_POST['tmt']);
		$lokasi = mysqli_real_escape_string($con, @$_POST['lokasi']);
		$jenis_file = mysqli_real_escape_string($con, @$_POST['jenis_file']);

		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		$tmt_val = empty($tmt) ? "'1970-01-01'" : "'$tmt'";
		$status_val = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';

		$q_check = mysqli_query($con, "SELECT id FROM pengabdian_jabatan WHERE no_sk='$no_sk' AND nip_dosen='$nip_dosen'");
		if (mysqli_num_rows($q_check) > 0) {
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];

			if (!empty($_FILES['file']['name'])) {
				$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_jabatan'");
				while ($r_old = mysqli_fetch_array($q_old_file)) {
					if (file_exists($r_old['url'])) @unlink($r_old['url']);
				}
				mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nip_dosen' AND kelompok='file_jabatan'");

				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "jabatan_" . $id_porto . "_" . time() . "." . $ext;
				if (function_exists('UploadJabatan')) {
					UploadJabatan($new_name);
				} else {
					move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/jabatan/" . $new_name);
				}
				$path = "file/jabatan/" . $new_name;
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_jabatan', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			header('location:/pengabdian/jabatan/?m=inserted');
		} else {
			$query = "INSERT INTO pengabdian_jabatan (
				nip_dosen, date_modif, time_modif, id_jabatan, no_sk, tmt, lokasi, status, file
			) VALUES (
				'$nip_dosen', '$tgl_sekarang', '$jam_sekarang', '$id_jenis', '$no_sk', $tmt_val, '$lokasi', '$status_val', ''
			)";

			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "jabatan_" . $id_cat . "_" . time() . "." . $ext;
					if (function_exists('UploadJabatan')) {
						UploadJabatan($new_name);
					} else {
						move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/jabatan/" . $new_name);
					}
					$path = "file/jabatan/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_jabatan', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/pengabdian/jabatan/?m=inserted');
			} else {
				header('location:/pengabdian/jabatan/?m=not_inserted');
			}
		}
	} elseif (@$folder[3] == 'anggota_pembicara') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, @$_POST['role']);
		if (empty($role)) $role = '2';
		$type_file = mysqli_real_escape_string($con, @$_POST['type_file']);
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		$registered_by = $_SESSION['ses_user'];

		$q_cek = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_porto' AND nik='$nik' AND (jenis_porto='pembicara' OR jenis_porto='Pembicara')");
		if (mysqli_num_rows($q_cek) == 0) {
			mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_porto', '$nik', '$role', 'pembicara', '$date_regis', '$time_regis', '$registered_by')");
		}

		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "pembicara_" . $id_porto . "_" . time() . "." . $ext;
			if (function_exists('UploadPembicara')) {
				UploadPembicara($new_name);
			} else {
				move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pembicara/" . $new_name);
			}
			$path = "file/pembicara/" . $new_name;

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik', 'file_pembicara', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

	} elseif (@$folder[3] == 'anggota_jabatan') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, @$_POST['role']);
		if (empty($role)) $role = '1';
		$type_file = mysqli_real_escape_string($con, @$_POST['type_file']);
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		$registered_by = $_SESSION['ses_user'];

		$q_cek = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_porto' AND nik='$nik' AND (jenis_porto='jabatan' OR jenis_porto='Jabatan')");
		if (mysqli_num_rows($q_cek) == 0) {
			mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_porto', '$nik', '$role', 'jabatan', '$date_regis', '$time_regis', '$registered_by')");
		}

		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "jabatan_" . $id_porto . "_" . time() . "." . $ext;
			if (function_exists('UploadJabatan')) {
				UploadJabatan($new_name);
			} else {
				move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/jabatan/" . $new_name);
			}
			$path = "file/jabatan/" . $new_name;

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik', 'file_jabatan', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		header("location:/pengabdian/jabatan/anggota/$id_porto?m=inserted");
	} elseif (@$folder[3] == 'anggota_pengabdian' || @$folder[3] == 'anggota') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, @$_POST['type_file']);
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		$registered_by = $_SESSION['ses_user'];

		$q_cek = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='$id_porto' AND nik='$nik' AND jenis_porto='Pengabdian'");
		if (mysqli_num_rows($q_cek) == 0) {
			mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('$id_porto', '$nik', '$role', 'Pengabdian', '$date_regis', '$time_regis', '$registered_by')");
		}

		// Duplikat row di pengabdian_pengabdian untuk anggota baru
		$new_id_cat = duplicatePortfolioPengabdian($con, $id_porto, $nik, $role);

		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_pengabdian_" . $id_porto . "_" . time() . "." . $ext;
			if (function_exists('UploadPengabdian')) {
				UploadPengabdian($new_name);
			} else {
				move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pengabdian/" . $new_name);
			}
			$path = "file/pengabdian/" . $new_name;

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_pengabdian', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		header("location:/pengabdian/pengabdian/anggota/$id_porto?m=inserted");
	} else {
		header('location:/pengabdian/pengabdian');
	}
}
?>
