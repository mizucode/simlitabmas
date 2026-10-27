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
function UploadPatenhki($fupload_name)
{
	$api_key = 'YVLAWKunMQVVcjpNeDkqgj9P';
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
function UploadKarya($fupload_name)
{
	$api_key = 'ZrEbwHtb7Nm2FG4DKqvPggAW';
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
function UploadJurnal($fupload_name)
{
	$api_key = 'Lve9MuFvGY6NgRswqoAAASno';
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

function UploadProsiding($fupload_name)
{
	$api_key = 'k62M96PBvDSNQPv627PDsYv8';
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
	$api_key = '2peFfBV5hYLzdGnf7W3koVFG';
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

function UploadPembicara($fupload_name)
{
	$api_key = 'f6orPW34nmr85vxhsXZqDP3u';
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

function UploadJabatan($fupload_name)
{
	$api_key = 'AddWs7VCSXDC9GBFKTptyCwD';
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
function UploadPenunjang($fupload_name)
{
	$vdir_upload = "file/penunjang/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadBuku($fupload_name)
{
	$api_key = '8q2Q5Ere9yzSk8TREnqWxLPZ';
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

function UploadChapter($fupload_name)
{
	$api_key = 'n25V245avLWFJWqFQMwdxvjG';
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

function UploadTulisan($fupload_name)
{
	$api_key = 'Y3qynvAfMExtQcksia854wJP';
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

function UploadMonograf($fupload_name)
{
	$api_key = '2TF5Qr4bUoLdvswqsRY4Z936';
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

function UploadPenerjemahan($fupload_name)
{
	$api_key = 'FrkxvmuwcgCHtkcyEGJuAc6T';
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
function UploadPenyuntingan($fupload_name)
{
	$api_key = 'FrkxvmuwcgCHtkcyEGJuAc6T';
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
function UploadNonPublikasi($fupload_name)
{
	$api_key = 'gCbNm39phNXPqbHTEEcP6NoK';
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
function UploadReferensiJurnal($fupload_name)
{
	$api_key = 'JLHm3ShNL4CVJsn4bTYdGvLy';
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
function UploadReferensiKlaster($fupload_name)
{
	$api_key = 'D8L57dbmKFAdoiVG5TyGLgUG';
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


?>