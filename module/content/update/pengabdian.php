<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	if (@$folder[3] == 'pengabdian') {
		$id = mysqli_real_escape_string($con, @$_POST['id']);
		if (empty($id)) $id = mysqli_real_escape_string($con, @$folder[4]);

		$status = mysqli_real_escape_string($con, @$_POST['status']);
		$keterangan_validasi = mysqli_real_escape_string($con, @$_POST['keterangan_validasi']);

		if (isset($_POST['status'])) {
			$query = "UPDATE pengabdian_pengabdian SET status = '$status', keterangan_validasi = '$keterangan_validasi' WHERE id = '$id'";
			if (mysqli_query($con, $query)) {
				header('location:/pengabdian/pengabdian/?m=updated');
			} else {
				header('location:/pengabdian/pengabdian/?m=not_updated');
			}
		} elseif (isset($_POST['judul_kegiatan'])) {
			$nip_dosen = mysqli_real_escape_string($con, @$_POST['nip_dosen']);
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
			$no_sk = mysqli_real_escape_string($con, @$_POST['no_sk']);
			$tgl_sk = mysqli_real_escape_string($con, @$_POST['tgl_sk']);
			$mitra = mysqli_real_escape_string($con, @$_POST['mitra']);
			$libatkan_mhs = isset($_POST['libatkan_mhs']) ? 'Ya' : 'Tidak';
			$rujukan_ta = isset($_POST['rujukan_ta']) ? 'Ya' : 'Tidak';
			$kelompok_bidang = mysqli_real_escape_string($con, @$_POST['kelompok_bidang']);
			$lokasi = mysqli_real_escape_string($con, @$_POST['lokasi']);

			$tgl_sk_val = empty($tgl_sk) ? "NULL" : "'$tgl_sk'";
			$tgl_mulai_val = empty($tgl_mulai_kegiatan) ? "NULL" : "'$tgl_mulai_kegiatan'";

			$q_old = mysqli_query($con, "SELECT judul_kegiatan FROM pengabdian_pengabdian WHERE id='$id'");
			$old_data = mysqli_fetch_array($q_old);
			$old_judul = mysqli_real_escape_string($con, $old_data['judul_kegiatan'] ?? '');

			$query = "UPDATE pengabdian_pengabdian SET 
				judul_kegiatan='$judul_kegiatan',
				afiliasi='$afiliasi',
				id_skema='$id_skema',
				thn_usulan='$thn_usulan',
				tgl_mulai_kegiatan=$tgl_mulai_val,
				lama_kegiatan='$lama_kegiatan',
				thn_pelaksanaan_ke='$thn_pelaksanaan_ke',
				dana_dikti='$dana_dikti',
				dana_kampus='$dana_kampus',
				dana_mandiri='$dana_mandiri',
				dana_lain='$dana_lain',
				no_sk='$no_sk',
				tgl_sk=$tgl_sk_val,
				mitra='$mitra',
				libatkan_mhs='$libatkan_mhs',
				rujukan_ta='$rujukan_ta',
				kelompok_bidang='$kelompok_bidang',
				lokasi='$lokasi'
				WHERE judul_kegiatan='$old_judul'";

			$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/pengabdian/pengabdian';
			if (mysqli_query($con, $query)) {
				header('location:' . $redirect . '?m=updated');
			} else {
				header('location:' . $redirect . '?m=not_updated');
			}
		} else {
			header('location:/pengabdian/pengabdian');
		}

	} elseif (@$folder[3] == 'pembicara') {
		$id = mysqli_real_escape_string($con, @$_POST['id']);
		if (empty($id)) $id = mysqli_real_escape_string($con, @$folder[4]);

		$status = mysqli_real_escape_string($con, @$_POST['status']);
		$keterangan_validasi = mysqli_real_escape_string($con, @$_POST['keterangan_validasi']);

		if (isset($_POST['status'])) {
			$query = "UPDATE pengabdian_pembicara SET status = '$status', keterangan_validasi = '$keterangan_validasi' WHERE id = '$id'";
			if (mysqli_query($con, $query)) {
				header('location:/pengabdian/pembicara/?m=updated');
			} else {
				header('location:/pengabdian/pembicara/?m=not_updated');
			}
		} elseif (isset($_POST['judul'])) {
			$nip_dosen = mysqli_real_escape_string($con, @$_POST['nip_dosen']);
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

			$tgl_pelaksanaan_val = empty($tgl_pelaksanaan) ? "NULL" : "'$tgl_pelaksanaan'";
			$tgl_sk_val = empty($tgl_sk) ? "NULL" : "'$tgl_sk'";

			$q_old = mysqli_query($con, "SELECT judul FROM pengabdian_pembicara WHERE id='$id'");
			$old_data = mysqli_fetch_array($q_old);
			$old_judul = mysqli_real_escape_string($con, $old_data['judul'] ?? '');

			$query = "UPDATE pengabdian_pembicara SET 
				id_kategori_kegiatan='$id_kategori_kegiatan',
				id_kategori_pembicara='$id_kategori_pembicara',
				judul='$judul',
				nama_pertemuan='$nama_pertemuan',
				id_tingkat='$id_tingkat',
				penyelenggara='$penyelenggara',
				tgl_pelaksanaan=$tgl_pelaksanaan_val,
				bahasa='$bahasa',
				no_sk='$no_sk',
				tgl_sk=$tgl_sk_val,
				link_lit='$link_lit',
				link_abmas='$link_abmas'
				WHERE judul='$old_judul'";

			if (mysqli_query($con, $query)) {
				// Upload file jika ada
				if (!empty($_FILES['file']['name']) && !empty($jenis_file)) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "pembicara_" . $id . "_" . time() . "." . $ext;
					if (function_exists('UploadPembicara')) {
						UploadPembicara($new_name);
					} else {
						move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/pembicara/" . $new_name);
					}
					$path = "file/pembicara/" . $new_name;

					$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pembicara'");
					while ($r_old = mysqli_fetch_array($q_old_file)) {
						if (file_exists($r_old['url'])) @unlink($r_old['url']);
					}
					mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_pembicara'");

					$tgl_sekarang = date('Y-m-d');
					$jam_sekarang = date('H:i:s');
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id', '$nip_dosen', 'file_pembicara', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/pengabdian/pembicara';
				header('location:' . $redirect . '?m=updated');
			} else {
				$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/pengabdian/pembicara';
				header('location:' . $redirect . '?m=not_updated');
			}
		} else {
			header('location:/pengabdian/pembicara');
		}

	} elseif (@$folder[3] == 'jabatan') {
		$id = mysqli_real_escape_string($con, @$_POST['id']);
		if (empty($id)) $id = mysqli_real_escape_string($con, @$folder[4]);

		$status = mysqli_real_escape_string($con, @$_POST['status']);
		$keterangan_validasi = mysqli_real_escape_string($con, @$_POST['keterangan_validasi']);

		if (isset($_POST['status'])) {
			$query = "UPDATE pengabdian_jabatan SET status = '$status', keterangan_validasi = '$keterangan_validasi' WHERE id = '$id'";
			if (mysqli_query($con, $query)) {
				header('location:/pengabdian/jabatan/?m=updated');
			} else {
				header('location:/pengabdian/jabatan/?m=not_updated');
			}
		} elseif (isset($_POST['id_jenis']) || isset($_POST['no_sk'])) {
			$nip_dosen = mysqli_real_escape_string($con, @$_POST['nip_dosen']);
			$id_jenis = mysqli_real_escape_string($con, @$_POST['id_jenis']);
			$no_sk = mysqli_real_escape_string($con, @$_POST['no_sk']);
			$tmt = mysqli_real_escape_string($con, @$_POST['tmt']);
			$lokasi = mysqli_real_escape_string($con, @$_POST['lokasi']);
			$jenis_file = mysqli_real_escape_string($con, @$_POST['jenis_file']);

			$tmt_val = empty($tmt) ? "NULL" : "'$tmt'";

			$q_old = mysqli_query($con, "SELECT no_sk FROM pengabdian_jabatan WHERE id='$id'");
			$old_data = mysqli_fetch_array($q_old);
			$old_no_sk = mysqli_real_escape_string($con, $old_data['no_sk'] ?? '');

			$query = "UPDATE pengabdian_jabatan SET 
				id_jabatan='$id_jenis',
				no_sk='$no_sk',
				tmt=$tmt_val,
				lokasi='$lokasi'
				WHERE no_sk='$old_no_sk'";

			if (mysqli_query($con, $query)) {
				// Upload file jika ada
				if (!empty($_FILES['file']['name']) && !empty($jenis_file)) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "jabatan_" . $id . "_" . time() . "." . $ext;
					if (function_exists('UploadJabatan')) {
						UploadJabatan($new_name);
					} else {
						move_uploaded_file($_FILES['file']['tmp_name'], "../../../file/jabatan/" . $new_name);
					}
					$path = "file/jabatan/" . $new_name;

					$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id' AND kelompok='file_jabatan'");
					while ($r_old = mysqli_fetch_array($q_old_file)) {
						if (file_exists($r_old['url'])) @unlink($r_old['url']);
					}
					mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id' AND kelompok='file_jabatan'");

					$tgl_sekarang = date('Y-m-d');
					$jam_sekarang = date('H:i:s');
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id', '$nip_dosen', 'file_jabatan', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/pengabdian/jabatan';
				header('location:' . $redirect . '?m=updated');
			} else {
				$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/pengabdian/jabatan';
				header('location:' . $redirect . '?m=not_updated');
			}
		} else {
			header('location:/pengabdian/jabatan');
		}
	} elseif (@$folder[3] == 'anggota_pembicara') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, @$_POST['type_file']);
		
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

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_pembicara'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_pembicara'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_pembicara', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/pengabdian/pembicara/anggota/$id_porto?m=updated");
		} else {
			header("location:/pengabdian/pembicara/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_jabatan') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, @$_POST['role']);
		if (empty($role)) $role = '1';
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
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

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_jabatan'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_jabatan'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_jabatan', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/pengabdian/jabatan/anggota/$id_porto?m=updated");
		} else {
			header("location:/pengabdian/jabatan/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
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

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_pengabdian'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_pengabdian'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_pengabdian', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/pengabdian/pengabdian/anggota/$id_porto?m=updated");
		} else {
			header("location:/pengabdian/pengabdian/anggota/$id_porto?m=not_updated");
		}
	} else {
		header('location:/pengabdian/pengabdian');
	}
}
?>
