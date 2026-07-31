<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	
function duplicatePortfolioRecord($con, $jenis_porto, $id_porto, $nik, $role) {
    $table_name = "penelitian_$jenis_porto";
    if (strtolower($jenis_porto) == 'penelitian') $table_name = 'penelitian_penelitian';
    
    $q_orig = mysqli_query($con, "SELECT * FROM $table_name WHERE id = '$id_porto'");
    if ($orig = mysqli_fetch_assoc($q_orig)) {
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
            } elseif ($key == 'id_peran' && strtolower($jenis_porto) == 'penelitian') {
                $val = (is_numeric($role)) ? $role : (($role == 'Ketua') ? '1' : '2');
            }
            
            $cols[] = $key;
            if ($val === null) {
                $vals[] = "NULL";
            } else {
                $vals[] = "'" . mysqli_real_escape_string($con, $val) . "'";
            }
        }
        
        $judul_field = (strtolower($jenis_porto) == 'penelitian') ? 'judul_kegiatan' : 'judul';
        $judul_val = mysqli_real_escape_string($con, $orig[$judul_field]);
        $q_cek = mysqli_query($con, "SELECT id FROM $table_name WHERE $judul_field = '$judul_val' AND nip_dosen = '$nik'");
        
        if (mysqli_num_rows($q_cek) == 0) {
            $query_dup = "INSERT INTO $table_name (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ")";
            mysqli_query($con, $query_dup);
            return mysqli_insert_id($con);
        } else {
            $d_cek = mysqli_fetch_assoc($q_cek);
            return $d_cek['id'];
        }
    }
    return $id_porto; // fallback
}

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
			$new_id_cat = duplicatePortfolioRecord($con, 'penelitian', $id_porto, $nik, $role);
			if (!empty($path_file)) {
				$tgl_sekarang = date('Y-m-d');
				$jam_sekarang = date('H:i:s');
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_penelitian', '$type_file', '$path_file', '$tgl_sekarang', '$jam_sekarang')");
			}

			header("location:/penelitian/penelitian/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/penelitian/anggota/$id_porto?m=not_inserted");
		}
	} elseif (@$folder[3] == 'patenhki') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$tgl_paten = mysqli_real_escape_string($con, $_POST['tgl_paten']);
		$nomor = mysqli_real_escape_string($con, $_POST['nomor']);
		$pemberi = mysqli_real_escape_string($con, $_POST['pemberi']);
		$id_tkt = mysqli_real_escape_string($con, $_POST['id_tkt']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$jenis_file = mysqli_real_escape_string($con, $_POST['jenis_file']);
		
		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		
		$q_check = mysqli_query($con, "SELECT id FROM penelitian_patenhki WHERE judul='$judul'");
		if (mysqli_num_rows($q_check) > 0) {
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];
			
			$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
			// Insert to pivot as role '1' (Ketua) default
			$query_pivot = "INSERT INTO regis_dosen_penelitian (
				id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
			) VALUES (
				'$id_porto', '$nip_dosen', '1', 'patenhki', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
			)";
			
			if (mysqli_query($con, $query_pivot)) {
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "anggota_patenhki_" . $id_porto . "_" . time() . "." . $ext;
					UploadPatenhki($new_name);
					$path = "file/patenhki/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_patenhki', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/penelitian/patenhki/?m=inserted');
			} else {
				header('location:/penelitian/patenhki/?m=not_inserted');
			}
		} else {
			$status_paten = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';
			$query = "INSERT INTO penelitian_patenhki (
				nip_dosen, id_jenis, judul, tgl_paten, nomor, pemberi, id_tkt, link_lit, link_abmas, date_modif, time_modif, status
			) VALUES (
				'$nip_dosen', '$id_jenis', '$judul', '$tgl_paten', '$nomor', '$pemberi', '$id_tkt', '$link_lit', '$link_abmas', CURDATE(), CURTIME(), '$status_paten'
			)";
			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);
				
				$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
				$query_pivot_new = "INSERT INTO regis_dosen_penelitian (
					id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
				) VALUES (
					'$id_cat', '$nip_dosen', '1', 'patenhki', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
				)";
				mysqli_query($con, $query_pivot_new);
				
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "patenhki_" . $id_cat . "_" . time() . "." . $ext;
					UploadPatenhki($new_name);
					$path = "file/patenhki/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_patenhki', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/penelitian/patenhki/?m=inserted');
			} else {
				header('location:/penelitian/patenhki/?m=not_inserted');
			}
		}
	} elseif (@$folder[3] == 'karya') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$jenis_file = mysqli_real_escape_string($con, $_POST['jenis_file']);
		
		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		
		$q_check = mysqli_query($con, "SELECT id FROM penelitian_karya WHERE judul='$judul'");
		if (mysqli_num_rows($q_check) > 0) {
			$d_check = mysqli_fetch_array($q_check);
			$id_porto = $d_check['id'];
			
			$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
			// Insert to pivot as role '1' (Ketua) default
			$query_pivot = "INSERT INTO regis_dosen_penelitian (
				id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
			) VALUES (
				'$id_porto', '$nip_dosen', '1', 'karya', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
			)";
			
			if (mysqli_query($con, $query_pivot)) {
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "anggota_karya_" . $id_porto . "_" . time() . "." . $ext;
					UploadKarya($new_name);
					$path = "file/karya/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_porto', '$nip_dosen', 'file_karya', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/penelitian/karya/?m=inserted');
			} else {
				header('location:/penelitian/karya/?m=not_inserted');
			}
		} else {
			$status_karya = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';
			$query = "INSERT INTO penelitian_karya (
				nip_dosen, id_jenis, judul, tgl_terbit, link_lit, link_abmas, date_modif, time_modif, status
			) VALUES (
				'$nip_dosen', '$id_jenis', '$judul', '$tgl_terbit', '$link_lit', '$link_abmas', CURDATE(), CURTIME(), '$status_karya'
			)";
			if (mysqli_query($con, $query)) {
				$id_cat = mysqli_insert_id($con);
				
				$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
				$query_pivot_new = "INSERT INTO regis_dosen_penelitian (
					id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
				) VALUES (
					'$id_cat', '$nip_dosen', '1', 'karya', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
				)";
				mysqli_query($con, $query_pivot_new);
				
				if (!empty($_FILES['file']['name'])) {
					$file_name = $_FILES['file']['name'];
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$new_name = "karya_" . $id_cat . "_" . time() . "." . $ext;
					UploadKarya($new_name);
					$path = "file/karya/" . $new_name;
					mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_karya', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
				}
				header('location:/penelitian/karya/?m=inserted');
			} else {
				header('location:/penelitian/karya/?m=not_inserted');
			}
		}
	} elseif (@$folder[3] == 'anggota_patenhki') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$jenis_porto = 'patenhki';
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		$path_file = '';
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_patenhki_" . $id_porto . "_" . time() . "." . $ext;
			UploadPatenhki($new_name);
			$path_file = "file/patenhki/" . $new_name;
		}
		
		$registered_by = mysqli_real_escape_string($con, $_SESSION['ses_user']);
		
		$query = "INSERT INTO regis_dosen_penelitian (
			id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
		) VALUES (
			'$id_porto', '$nik', '$role', '$jenis_porto', '$date_regis', '$time_regis', '$type_file', '$registered_by'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_regis = mysqli_insert_id($con);
			$new_id_cat = duplicatePortfolioRecord($con, 'patenhki', $id_porto, $nik, $role);
			if (!empty($path_file)) {
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_patenhki', '$type_file', '$path_file', '$date_regis', '$time_regis')");
			}
			header("location:/penelitian/patenhki/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/patenhki/anggota/$id_porto?m=not_inserted");
		}
	} elseif (@$folder[3] == 'anggota_karya') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$jenis_porto = 'karya';
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		$path_file = '';
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_karya_" . $id_porto . "_" . time() . "." . $ext;
			UploadKarya($new_name);
			$path_file = "file/karya/" . $new_name;
		}
		
		$registered_by = mysqli_real_escape_string($con, $_SESSION['ses_user']);
		
		$query = "INSERT INTO regis_dosen_penelitian (
			id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
		) VALUES (
			'$id_porto', '$nik', '$role', '$jenis_porto', '$date_regis', '$time_regis', '$type_file', '$registered_by'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_regis = mysqli_insert_id($con);
			$new_id_cat = duplicatePortfolioRecord($con, 'karya', $id_porto, $nik, $role);
			if (!empty($path_file)) {
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_karya', '$type_file', '$path_file', '$date_regis', '$time_regis')");
			}
			header("location:/penelitian/karya/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/karya/anggota/$id_porto?m=not_inserted");
		}
	} elseif (@$folder[3] == 'jurnal') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$nama_jurnal = mysqli_real_escape_string($con, $_POST['nama_jurnal']);
		$issn = mysqli_real_escape_string($con, $_POST['issn']);
		$url_jurnal = mysqli_real_escape_string($con, $_POST['url_jurnal']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$vol = mysqli_real_escape_string($con, $_POST['vol']);
		$no_jurnal = mysqli_real_escape_string($con, $_POST['no']);
		$halaman = mysqli_real_escape_string($con, $_POST['halaman']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$doi = mysqli_real_escape_string($con, $_POST['doi']);
		$sitasi = mysqli_real_escape_string($con, $_POST['sitasi']);
		$url_artikel = mysqli_real_escape_string($con, $_POST['url_artikel']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$jenis_file = mysqli_real_escape_string($con, $_POST['jenis_file']);
		
		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		
		$status_jurnal = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';
		
		$query = "INSERT INTO penelitian_jurnal (
			nip_dosen, id_jenis, judul, nama_jurnal, issn, url_jurnal, tgl_terbit, vol, no, halaman, penerbit, doi, sitasi, url_artikel, link_lit, link_abmas, date_modif, time_modif, status
		) VALUES (
			'$nip_dosen', '$id_jenis', '$judul', '$nama_jurnal', '$issn', '$url_jurnal', '$tgl_terbit', '$vol', '$no_jurnal', '$halaman', '$penerbit', '$doi', '$sitasi', '$url_artikel', '$link_lit', '$link_abmas', CURDATE(), CURTIME(), '$status_jurnal'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_cat = mysqli_insert_id($con);
			
			$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
			$query_pivot_new = "INSERT INTO regis_dosen_penelitian (
				id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
			) VALUES (
				'$id_cat', '$nip_dosen', '1', 'jurnal', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
			)";
			mysqli_query($con, $query_pivot_new);
			
			if (!empty($_FILES['file']['name'])) {
				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "jurnal_" . $id_cat . "_" . time() . "." . $ext;
				UploadJurnal($new_name);
				$path = "file/jurnal/" . $new_name;
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_jurnal', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			header('location:/penelitian/jurnal/?m=inserted');
		} else {
			header('location:/penelitian/jurnal/?m=not_inserted');
		}
	} elseif (@$folder[3] == 'anggota_jurnal') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$jenis_porto = 'jurnal';
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		$path_file = '';
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_jurnal_" . $id_porto . "_" . time() . "." . $ext;
			UploadJurnal($new_name);
			$path_file = "file/jurnal/" . $new_name;
		}
		
		$registered_by = mysqli_real_escape_string($con, $_SESSION['ses_user']);
		
		$query = "INSERT INTO regis_dosen_penelitian (
			id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
		) VALUES (
			'$id_porto', '$nik', '$role', '$jenis_porto', '$date_regis', '$time_regis', '$type_file', '$registered_by'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_regis = mysqli_insert_id($con);
			$new_id_cat = duplicatePortfolioRecord($con, 'jurnal', $id_porto, $nik, $role);
			if (!empty($path_file)) {
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_jurnal', '$type_file', '$path_file', '$date_regis', '$time_regis')");
			}
			header("location:/penelitian/jurnal/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/jurnal/anggota/$id_porto?m=not_inserted");
		}
	} elseif (@$folder[3] == 'prosiding') {
		if (isset($_POST['nip_dosen']) && !empty($_POST['nip_dosen'])) {
			$nip_dosen = mysqli_real_escape_string($con, $_POST['nip_dosen']);
		} else {
			$nip_dosen = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
		}
		$id_jenis = mysqli_real_escape_string($con, $_POST['id_jenis']);
		$judul = mysqli_real_escape_string($con, $_POST['judul']);
		$nama_seminar = mysqli_real_escape_string($con, $_POST['nama_seminar']);
		$tgl_terbit = mysqli_real_escape_string($con, $_POST['tgl_terbit']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$isbn = mysqli_real_escape_string($con, $_POST['isbn']);
		$issn = mysqli_real_escape_string($con, $_POST['issn']);
		$doi = mysqli_real_escape_string($con, $_POST['doi']);
		$bahasa = mysqli_real_escape_string($con, $_POST['bahasa']);
		$sitasi = mysqli_real_escape_string($con, $_POST['sitasi']);
		$url_artikel = mysqli_real_escape_string($con, @$_POST['link']);
		$link_lit = mysqli_real_escape_string($con, @$_POST['link_lit']);
		$link_abmas = mysqli_real_escape_string($con, @$_POST['link_abmas']);
		$jenis_file = mysqli_real_escape_string($con, $_POST['jenis_file']);
		
		$tgl_sekarang = date('Y-m-d');
		$jam_sekarang = date('H:i:s');
		
		$status_prosiding = ($_SESSION['ses_level'] == 'lppm' || $_SESSION['ses_level'] == 'admin') ? '2' : '1';
		
		$query = "INSERT INTO penelitian_prosiding (
			nip_dosen, id_jenis, judul, nama_seminar, tgl_terbit, penerbit, isbn, issn, doi, bahasa, sitasi, link, link_lit, link_abmas, date_modif, time_modif, status
		) VALUES (
			'$nip_dosen', '$id_jenis', '$judul', '$nama_seminar', '$tgl_terbit', '$penerbit', '$isbn', '$issn', '$doi', '$bahasa', '$sitasi', '$url_artikel', '$link_lit', '$link_abmas', CURDATE(), CURTIME(), '$status_prosiding'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_cat = mysqli_insert_id($con);
			
			$registered_by = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
			$query_pivot_new = "INSERT INTO regis_dosen_penelitian (
				id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
			) VALUES (
				'$id_cat', '$nip_dosen', '1', 'prosiding', '$tgl_sekarang', '$jam_sekarang', '$jenis_file', '$registered_by'
			)";
			mysqli_query($con, $query_pivot_new);
			
			if (!empty($_FILES['file']['name'])) {
				$file_name = $_FILES['file']['name'];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_name = "prosiding_" . $id_cat . "_" . time() . "." . $ext;
				UploadProsiding($new_name);
				$path = "file/prosiding/" . $new_name;
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$id_cat', '$nip_dosen', 'file_prosiding', '$jenis_file', '$path', '$tgl_sekarang', '$jam_sekarang')");
			}
			header('location:/penelitian/prosiding/?m=inserted');
		} else {
			header('location:/penelitian/prosiding/?m=not_inserted');
		}
	} elseif (@$folder[3] == 'anggota_prosiding') {
		$id_porto = mysqli_real_escape_string($con, $_POST['id_porto']);
		$nik = mysqli_real_escape_string($con, $_POST['nik']);
		$role = mysqli_real_escape_string($con, $_POST['role']);
		$jenis_porto = 'prosiding';
		$date_regis = date('Y-m-d');
		$time_regis = date('H:i:s');
		
		$type_file = mysqli_real_escape_string($con, $_POST['type_file']);
		
		$path_file = '';
		if (!empty($_FILES['file']['name'])) {
			$file_name = $_FILES['file']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "anggota_prosiding_" . $id_porto . "_" . time() . "." . $ext;
			UploadProsiding($new_name);
			$path_file = "file/prosiding/" . $new_name;
		}
		
		$registered_by = mysqli_real_escape_string($con, $_SESSION['ses_user']);
		
		$query = "INSERT INTO regis_dosen_penelitian (
			id_porto, nik, role, jenis_porto, date_regis, time_regis, type_file, registered_by
		) VALUES (
			'$id_porto', '$nik', '$role', '$jenis_porto', '$date_regis', '$time_regis', '$type_file', '$registered_by'
		)";
		
		if (mysqli_query($con, $query)) {
			$id_regis = mysqli_insert_id($con);
			$new_id_cat = duplicatePortfolioRecord($con, 'prosiding', $id_porto, $nik, $role);
			if (!empty($path_file)) {
				mysqli_query($con, "INSERT INTO file_dosen (id_cat, nip_dosen, kelompok, value_kode, url, date_modif, time_modif) VALUES ('$new_id_cat', '$nik', 'file_prosiding', '$type_file', '$path_file', '$date_regis', '$time_regis')");
			}
			header("location:/penelitian/prosiding/anggota/$id_porto?m=inserted");
		} else {
			header("location:/penelitian/prosiding/anggota/$id_porto?m=not_inserted");
		}
	} else {
		header('location:/penelitian/penelitian');
	}
}
?>