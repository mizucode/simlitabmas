<?php
if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	if (@$folder[3] == 'jurnal') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$jenis = mysqli_real_escape_string($con, $_POST['jenis']);
		$nama_jurnal = mysqli_real_escape_string($con, $_POST['nama_jurnal']);
		$penerbit = mysqli_real_escape_string($con, $_POST['penerbit']);
		$link_jurnal = mysqli_real_escape_string($con, $_POST['link_jurnal']);
		$issn = mysqli_real_escape_string($con, $_POST['issn']);
		$tahun_edisi_pertama = mysqli_real_escape_string($con, $_POST['tahun_edisi_pertama']);
		$peringkat_akreditasi = mysqli_real_escape_string($con, $_POST['peringkat_akreditasi']);
		$tgl_berlaku_akred = mysqli_real_escape_string($con, $_POST['tgl_berlaku_akred']);
		$tgl_berakhir_akred = mysqli_real_escape_string($con, $_POST['tgl_berakhir_akred']);
		
		$path_akreditasi = mysqli_real_escape_string($con, $_POST['file_akreditasi_lama']);
		if (!empty($_FILES['file_akreditasi']['name'])) {
			$file_name = $_FILES['file_akreditasi']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "akreditasi_jurnal_" . time() . "." . $ext;
			
			$destination = 'file/referensi-jurnal/' . $new_name;
			
			$_FILES['file'] = $_FILES['file_akreditasi'];
			if (UploadReferensiJurnal($new_name)) {
				// Hapus file lama jika ada (jika S3, kita tidak bisa hapus lewat unlink, tapi kita abaikan saja atau bisa bikin API hapus)
				$path_akreditasi = $destination;
			}
			unset($_FILES['file']);
		}

		$path_cover = mysqli_real_escape_string($con, $_POST['cover_url_lama']);
		if (!empty($_FILES['cover_url']['name'])) {
			$file_name = $_FILES['cover_url']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "cover_jurnal_" . time() . "." . $ext;
			
			$destination = 'file/referensi-jurnal/' . $new_name;
			
			$_FILES['file'] = $_FILES['cover_url'];
			if (UploadReferensiJurnal($new_name)) {
				// Hapus file lama jika ada
				$path_cover = $destination;
			}
			unset($_FILES['file']);
		}

		$query = "UPDATE referensi_jurnal SET 
			jenis = '$jenis',
			nama_jurnal = '$nama_jurnal',
			penerbit = '$penerbit',
			link_jurnal = '$link_jurnal',
			issn = '$issn',
			tahun_edisi_pertama = '$tahun_edisi_pertama',
			peringkat_akreditasi = '$peringkat_akreditasi',
			file_akreditasi = '$path_akreditasi',
			tgl_berlaku_akred = '$tgl_berlaku_akred',
			tgl_berakhir_akred = '$tgl_berakhir_akred',
			cover_url = '$path_cover'
			WHERE id = '$id'";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/jurnal/?m=updated');
		} else {
			header('location:/referensi/jurnal/?m=not_updated');
		}

	} elseif (@$folder[3] == 'klaster') {
		$id = mysqli_real_escape_string($con, $_POST['id']);
		$jenis = mysqli_real_escape_string($con, $_POST['jenis']);
		$tahun = mysqli_real_escape_string($con, $_POST['tahun']);
		$klaster = mysqli_real_escape_string($con, $_POST['klaster']);
		
		$path_sk = mysqli_real_escape_string($con, $_POST['file_sk_klaster_lama']);
		if (!empty($_FILES['file_sk_klaster']['name'])) {
			$file_name = $_FILES['file_sk_klaster']['name'];
			$ext = pathinfo($file_name, PATHINFO_EXTENSION);
			$new_name = "sk_klaster_" . time() . "." . $ext;
			
			$destination = 'file/referensi-klaster/' . $new_name;
			
			$_FILES['file'] = $_FILES['file_sk_klaster'];
			if (UploadReferensiKlaster($new_name)) {
				// Hapus file lama jika ada (abaikan untuk S3 sementara ini)
				$path_sk = $destination;
			}
			unset($_FILES['file']);
		}

		$query = "UPDATE referensi_klaster SET 
			jenis = '$jenis',
			tahun = '$tahun',
			klaster = '$klaster',
			file_sk_klaster = '$path_sk'
			WHERE id = '$id'";

		if (mysqli_query($con, $query)) {
			header('location:/referensi/klaster/?m=updated');
		} else {
			header('location:/referensi/klaster/?m=not_updated');
		}
	}
}
?>
