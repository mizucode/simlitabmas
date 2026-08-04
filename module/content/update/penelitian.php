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

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'patenhki') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$tgl_paten = mysqli_real_escape_string($con, $_POST['tgl_paten']);
		$nomor = mysqli_real_escape_string($con, $_POST['nomor']);
		$pemberi = mysqli_real_escape_string($con, @$_POST['pemberi']);
		$id_tkt = mysqli_real_escape_string($con, $_POST['id_tkt']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/patenhki';

		$query = "UPDATE penelitian_patenhki SET 
			judul='$judul',
			id_jenis='$id_jenis',
			tgl_paten='$tgl_paten',
			nomor='$nomor',
			pemberi='$pemberi',
			id_tkt='$id_tkt',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id'";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'prosiding') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$nama_seminar = mysqli_real_escape_string($con, $_POST['nama_seminar']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$isbn = mysqli_real_escape_string($con, $_POST['isbn']);
		$issn = mysqli_real_escape_string($con, @$_POST['issn']);
		$doi = mysqli_real_escape_string($con, @$_POST['doi']);
		$bahasa = mysqli_real_escape_string($con, @$_POST['bahasa']);
		$sitasi = mysqli_real_escape_string($con, $_POST['sitasi']);
		$url_artikel = mysqli_real_escape_string($con, @$_POST['link']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/prosiding';

		$query = "UPDATE penelitian_prosiding SET 
			judul='$judul',
			id_jenis='$id_jenis',
			nama_seminar='$nama_seminar',
			tgl_terbit='$tgl_terbit',
			penerbit='$penerbit',
			isbn='$isbn',
			issn='$issn',
			doi='$doi',
			bahasa='$bahasa',
			sitasi='$sitasi',
			link='$url_artikel',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id'";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'jurnal') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$nama_jurnal = mysqli_real_escape_string($con, $_POST['nama_jurnal']);
		$issn = mysqli_real_escape_string($con, $_POST['issn']);
		$url_jurnal = mysqli_real_escape_string($con, @$_POST['url_jurnal']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$vol = mysqli_real_escape_string($con, $_POST['vol']);
		$no = mysqli_real_escape_string($con, $_POST['no']);
		$halaman = mysqli_real_escape_string($con, @$_POST['halaman']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$doi = mysqli_real_escape_string($con, @$_POST['doi']);
		$sitasi = mysqli_real_escape_string($con, $_POST['sitasi']);
		$url_artikel = mysqli_real_escape_string($con, $_POST['url_artikel']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/jurnal';

		$query = "UPDATE penelitian_jurnal SET 
			judul='$judul',
			id_jenis='$id_jenis',
			nama_jurnal='$nama_jurnal',
			issn='$issn',
			url_jurnal='$url_jurnal',
			tgl_terbit='$tgl_terbit',
			vol='$vol',
			no='$no',
			halaman='$halaman',
			penerbit='$penerbit',
			doi='$doi',
			sitasi='$sitasi',
			url_artikel='$url_artikel',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id'";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'buku') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$jmlh_halaman = mysqli_real_escape_string($con, @$_POST['jmlh_halaman']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$isbn = mysqli_real_escape_string($con, $_POST['isbn']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/buku';

		$query = "UPDATE penelitian_buku SET 
			judul='$judul',
			tgl_terbit='$tgl_terbit',
			jmlh_halaman='$jmlh_halaman',
			penerbit='$penerbit',
			isbn='$isbn',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id'";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'karya') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$link_lit = mysqli_real_escape_string($con, $_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, $_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/karya';

		$query = "UPDATE penelitian_karya SET 
			judul='$judul',
			id_jenis='$id_jenis',
			tgl_terbit='$tgl_terbit',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id'";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'chapter') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$jenis_chapter = mysqli_real_escape_string($con, $_POST['jenis_chapter']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$judul_chapter = mysqli_real_escape_string($con, $_POST['judul_chapter']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$jmlh_halaman = mysqli_real_escape_string($con, @$_POST['jmlh_halaman']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$isbn = mysqli_real_escape_string($con, $_POST['isbn']);
		$bahasa = mysqli_real_escape_string($con, @$_POST['bahasa']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/chapter';

		$q_old = mysqli_query($con, "SELECT judul FROM penelitian_chapter WHERE id='$id'");
		$old_data = mysqli_fetch_array($q_old);
		$old_judul = mysqli_real_escape_string($con, $old_data['judul'] ?? '');

		$query = "UPDATE penelitian_chapter SET 
			jenis_chapter='$jenis_chapter',
			judul='$judul',
			judul_chapter='$judul_chapter',
			tgl_terbit='$tgl_terbit',
			jmlh_halaman='$jmlh_halaman',
			penerbit='$penerbit',
			isbn='$isbn',
			bahasa='$bahasa',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id' OR (nip_dosen IN (SELECT nik FROM regis_dosen_penelitian WHERE id_porto='$id' AND jenis_porto='chapter') AND (judul='$old_judul' OR judul='$judul'))";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_patenhki') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_patenhki_" . $id_porto . "_" . time() . "." . $ext;
			UploadPatenhki($new_name);
			$path = "file/patenhki/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_patenhki'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_patenhki'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_patenhki', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/patenhki/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/patenhki/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_karya') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_karya_" . $id_porto . "_" . time() . "." . $ext;
			UploadKarya($new_name);
			$path = "file/karya/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_karya'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_karya'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_karya', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/karya/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/karya/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_jurnal') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_jurnal_" . $id_porto . "_" . time() . "." . $ext;
			UploadJurnal($new_name);
			$path = "file/jurnal/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_jurnal'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_jurnal'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_jurnal', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/jurnal/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/jurnal/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_prosiding') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_prosiding_" . $id_porto . "_" . time() . "." . $ext;
			UploadProsiding($new_name);
			$path = "file/prosiding/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_prosiding'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_prosiding'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_prosiding', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/prosiding/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/prosiding/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_buku') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_buku_" . $id_porto . "_" . time() . "." . $ext;
			UploadBuku($new_name);
			$path = "file/buku/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_buku'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_buku'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_buku', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/buku/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/buku/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_chapter') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_chapter_" . $id_porto . "_" . time() . "." . $ext;
			UploadChapter($new_name);
			$path = "file/chapter/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_chapter'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_chapter'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_chapter', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/chapter/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/chapter/anggota/$id_porto?m=not_updated");
		}
	} elseif (@$folder[3] == 'tulisan') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$media = mysqli_real_escape_string($con, $_POST['media']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$link = mysqli_real_escape_string($con, @$_POST['link']);
		$id_level = mysqli_real_escape_string($con, $_POST['id_level']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/penelitian/tulisan';

		$q_old = mysqli_query($con, "SELECT judul FROM penelitian_tulisan WHERE id='$id'");
		$old_data = mysqli_fetch_array($q_old);
		$old_judul = mysqli_real_escape_string($con, $old_data['judul'] ?? '');

		$query = "UPDATE penelitian_tulisan SET 
			id_jenis='$id_jenis',
			judul='$judul',
			media='$media',
			tgl_terbit='$tgl_terbit',
			link='$link',
			id_level='$id_level',
			link_lit='$link_lit',
			link_abmas='$link_abmas',
			date_modif=CURDATE(),
			time_modif=CURTIME()
			WHERE id='$id' OR (nip_dosen IN (SELECT nik FROM regis_dosen_penelitian WHERE id_porto='$id' AND jenis_porto='tulisan') AND (judul='$old_judul' OR judul='$judul'))";

		if (mysqli_query($con, $query)) {
			header("location:" . $redirect . "?m=updated");
		} else {
			header("location:" . $redirect . "?m=not_updated");
		}
	} elseif (@$folder[3] == 'anggota_tulisan') {
		$id_regis = mysqli_real_escape_string($con, $_POST['id_regis']);
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_tulisan_" . $id_porto . "_" . time() . "." . $ext;
			UploadTulisan($new_name);
			$path = "file/tulisan/" . $new_name;

			$qnik = mysqli_query($con, "SELECT nik FROM regis_dosen_penelitian WHERE id='$id_regis'");
			$dnik = mysqli_fetch_array($qnik);
			$nik_anggota = $dnik['nik'];
			
			// Hapus file lama jika ada (berdasarkan id_porto dan nip_dosen)
			$q_old_file = mysqli_query($con, "SELECT url FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_tulisan'");
			while ($r_old = mysqli_fetch_array($q_old_file)) {
				if (file_exists($r_old['url'])) @unlink($r_old['url']);
			}
			mysqli_query($con, "DELETE FROM file_dosen WHERE id_cat='$id_porto' AND nip_dosen='$nik_anggota' AND kelompok='file_tulisan'");

			$tgl_sekarang = date('Y-m-d');
			$jam_sekarang = date('H:i:s');
			mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nik_anggota', 'file_tulisan', '$type_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
		}

		$query = "UPDATE regis_dosen_penelitian SET role = '$role' WHERE id = '$id_regis'";

		if (mysqli_query($con, $query)) {
			header("location:/penelitian/tulisan/anggota/$id_porto?m=updated");
		} else {
			header("location:/penelitian/tulisan/anggota/$id_porto?m=not_updated");
		}
	} else {
		header('location:/penelitian/penelitian');
	}
}
?>