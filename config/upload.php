<?php

function UploadFile($fupload_name)
{
	$vdir_upload = "file/kegiatan/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadRps($fupload_name)
{
	$vdir_upload = "file/rps/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadUts($fupload_name)
{
	$vdir_upload = "file/uts/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadProfil($fupload_name)
{
	$vdir_upload = "file/profil/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadKepegawaian($fupload_name)
{
	$vdir_upload = "file/kepegawaian/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPendidikan($fupload_name)
{
	$vdir_upload = "file/pendidikan/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPenelitian($fupload_name)
{
	$api_key = 'MzGAoSFtTXnPXt8w39Mhqcw9';
	$gateway = 'https://file.umkuningan.ac.id/upload_file';

	$lokasi_tmp = $_FILES['file']['tmp_name'];
	$mime_type = $_FILES['file']['type'];

	$s3_url = $gateway . '?key=' . urlencode($api_key);

	$curl = curl_init();
	$cfile = curl_file_create($lokasi_tmp, $mime_type, $fupload_name);

	$postFields = [
		'file' => $cfile,
		'key' => $api_key,
	];

	curl_setopt_array($curl, [
		CURLOPT_URL => $s3_url,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postFields,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => ['Accept: application/json'],
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_SAFE_UPLOAD => true,
	]);

	$response = curl_exec($curl);
	$curl_err = curl_error($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	if ($curl_err) {
		error_log("Gateway cURL Error: " . $curl_err);
		return false;
	}

	$result = json_decode($response, true);
	if ($http_code === 200 && !empty($result['success'])) {
		return true;
	} else {
		$pesan = $result['message'] ?? 'Unknown error';
		error_log("Gateway Upload Error (HTTP {$http_code}): {$pesan}");
		return false;
	}
}
function UploadPengabdian($fupload_name)
{
	$vdir_upload = "file/pengabdian/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPenunjang($fupload_name)
{
	$vdir_upload = "file/penunjang/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}



?>