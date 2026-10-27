<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	if (@$folder[3] == 'jurnal') {
		$jenis = mysqli_real_escape_string($con, $_POST['jenis']);
		$nama_jurnal = mysqli_real_escape_string($con, $_POST['nama_jurnal']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$link_jurnal = mysqli_real_escape_string($con, $_POST['link_jurnal']);
		$issn = mysqli_real_escape_string($con, $_POST['issn']);
		$tahun_edisi_pertama = mysqli_real_escape_string($con, $_POST['tahun_edisi_pertama']);
		$peringkat_akreditasi = mysqli_real_escape_string($con, $_POST['peringkat_akreditasi']);
		$tgl_berlaku_akred = mysqli_real_escape_string($con, $_POST['tgl_berlaku_akred']);
		$tgl_berakhir_akred = mysqli_real_escape_string($con, $_POST['tgl_berakhir_akred']);
		
		$path_akreditasi = '';
		if (!empty($_FILES['file_akreditasi']['name'])) {
			$file_name = $_FILES['file_akreditasi']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "akreditasi_jurnal_" . time() . "." . $ext;
			
			$destination = 'file/referensi-jurnal/' . $new_name;
			
			$_FILES['file'] = $_FILES['file_akreditasi'];
			if (UploadReferensiJurnal($new_name)) {
				$path_akreditasi = $destination;
			}
			unset($_FILES['file']);
		}

		$path_cover = '';
		if (!empty($_FILES['cover_url']['name'])) {
			$file_name = $_FILES['cover_url']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "cover_jurnal_" . time() . "." . $ext;
			
			$destination = 'file/referensi-jurnal/' . $new_name;
			
			$_FILES['file'] = $_FILES['cover_url'];
			if (UploadReferensiJurnal($new_name)) {
				$path_cover = $destination;
			}
			unset($_FILES['file']);
		}

		$query = "INSERT INTO referensi_jurnal (
			jenis, nama_jurnal, penerbit, link_jurnal, issn, tahun_edisi_pertama, peringkat_akreditasi, file_akreditasi, tgl_berlaku_akred, tgl_berakhir_akred, cover_url
		) VALUES (
			'$jenis', '$nama_jurnal', '$penerbit', '$link_jurnal', '$issn', '$tahun_edisi_pertama', '$peringkat_akreditasi', '$path_akreditasi', '$tgl_berlaku_akred', '$tgl_berakhir_akred', '$path_cover'
		)";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/jurnal/?m=inserted');
		} else {
			header('location:/referensi/jurnal/?m=not_inserted');
		}

	} elseif (@$folder[3] == 'klaster') {
		$jenis = mysqli_real_escape_string($con, $_POST['jenis']);
		$tahun = mysqli_real_escape_string($con, $_POST['tahun']);
		$klaster = mysqli_real_escape_string($con, $_POST['klaster']);
		
		$path_sk = '';
		if (!empty($_FILES['file_sk_klaster']['name'])) {
			$file_name = $_FILES['file_sk_klaster']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "sk_klaster_" . time() . "." . $ext;
			
			$destination = 'file/referensi-klaster/' . $new_name;
			
			$_FILES['file'] = $_FILES['file_sk_klaster'];
			if (UploadReferensiKlaster($new_name)) {
				$path_sk = $destination;
			}
			unset($_FILES['file']);
		}

		$query = "INSERT INTO referensi_klaster (
			jenis, tahun, klaster, file_sk_klaster
		) VALUES (
			'$jenis', '$tahun', '$klaster', '$path_sk'
		)";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/klaster/?m=inserted');
		} else {
			header('location:/referensi/klaster/?m=not_inserted');
		}
	}
}
?>
