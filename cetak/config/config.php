<?php
	if(valid!='1') {
	header('location:/login');
	}
?>
<?php
	

	$url = no_xss($_SERVER['REQUEST_URI']);
	$folder = explode("/",$url);
	$jumlah_folder = count($folder);
	$http = 'http:/';

	$seo_folder = $jumlah_folder - 1;
	if($jumlah_folder>'1') {
		$module = $folder['2'] ;
		$seo = $folder[$seo_folder];
	}

	if($folder['2']=='') {
		$module = 'dashboard';
	}
	else {
		$module = $folder['2'];
	}

?>