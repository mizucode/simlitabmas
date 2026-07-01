<?php

function UploadFile($fupload_name){
	$vdir_upload = "file/kegiatan/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadRps($fupload_name){
	$vdir_upload = "file/rps/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadUts($fupload_name){
	$vdir_upload = "file/uts/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}

function UploadProfil($fupload_name){
	$vdir_upload = "file/profil/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadKepegawaian($fupload_name){
	$vdir_upload = "file/kepegawaian/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPendidikan($fupload_name){
	$vdir_upload = "file/pendidikan/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPenelitian($fupload_name){
	$vdir_upload = "file/penelitian/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPengabdian($fupload_name){
	$vdir_upload = "file/pengabdian/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}
function UploadPenunjang($fupload_name){
	$vdir_upload = "file/penunjang/";
	$vfile_upload = $vdir_upload . $fupload_name;
	move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
}



?>
