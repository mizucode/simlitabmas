<?php 

ob_start();	
session_start();

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {

		include "../timeout.php";

		if($_SESSION['ses_login']==1){
			if(!validation()){
				$_SESSION[ses_login] = 0;
			}
		}

		if($_SESSION['ses_login']==0){
			header('location:/logout');
		}

		else {
	
		define('valid','1');
		include "../config/library.php";
		include "../config/date.php";
		include "../config/anti_inj.php";
		include "../config/db.php";
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

				switch($_SESSION['ses_level']) {

					case 'baak' :
						if ($module=='ujian'){
							include "../module/content/ujian/cetak/baak.php";
						}
						elseif ($module=='nilai'){
							include "../module/content/nilai/cetak/baak.php";
						}
						elseif ($module=='krs'){
							include "../module/content/krs/cetak/baak.php";
						}
						elseif ($module=='edom'){
							include "../module/content/edom/cetak/baak.php";
						}
						elseif ($module=='absensi'){
							include "../module/content/absensi/cetak/baak.php";
						}
						elseif ($module=='wisuda'){
							include "../module/content/wisuda/cetak/baak.php";
						}
						elseif ($module=='mahasiswa'){
							include "../module/content/mahasiswa/cetak/baak.php";
						}

					break;

					case 'prodi' :
						if ($module=='nilai'){
							include "../module/content/nilai/cetak/prodi.php";
						}
						elseif ($module=='krs'){
							include "../module/content/krs/cetak/prodi.php";
						}
						elseif ($module=='absensi'){
							include "../module/content/absensi/cetak/prodi.php";
						}
						elseif ($module=='ujian'){
							include "../module/content/ujian/cetak/prodi.php";
						}
						elseif ($module=='edom'){
							include "../module/content/edom/cetak/prodi.php";
						}

					break;

					case 'dosen' :
						if ($module=='nilai'){
							include "../module/content/nilai/cetak/dosen.php";
						}
						elseif ($module=='krs'){
							include "../module/content/krs/cetak/dosen.php";
						}
						elseif ($module=='edom'){
							include "../module/content/edom/cetak/dosen.php";
						}
						elseif ($module=='absensi'){
							include "../module/content/absensi/cetak/dosen.php";
						}
						elseif ($module=='lcd'){
							include "../module/content/lcd/cetak/dosen.php";
						}

					break;
					
					
					
					case 'mahasiswa' :

						if ($module=='uas'){
							include "../module/content/uas/cetak/mahasiswa.php";
						}
						elseif ($module=='uts'){
							include "../module/content/uts/cetak/mahasiswa.php";
						}
						elseif ($module=='krs'){
							include "../module/content/krs/cetak/mahasiswa.php";
						}
						elseif ($module=='nilai'){
							include "../module/content/nilai/cetak/mahasiswa.php";
						}
						elseif ($module=='restricted'){
							include "../module/content/restricted/mahasiswa.php";
						}
						elseif ($module=='wisuda'){
							include "../module/content/wisuda/cetak/mahasiswa.php";
						}
						elseif ($module=='ijazah'){
							include "../module/content/ijazah/cetak/mahasiswa.php";
						}
						elseif ($module=='skpi'){
							include "../module/content/skpi/cetak/mahasiswa.php";
						}
						elseif ($module=='kegiatan'){
							include "../module/content/kegiatan/cetak/mahasiswa.php";
						}
						elseif ($module=='beasiswa'){
							include "../module/content/beasiswa/cetak/mahasiswa.php";
						}
						else {
							include "../module/content/error/404.php";
						}


					break;
					case 'lpm' :
						if ($module=='edom'){
							include "../module/content/edom/cetak/lpm.php";
						}
						elseif ($module=='absensi'){
							include "../module/content/absensi/cetak/lppm.php";
						}
					break;



				}

		
		
		
		
		}

}
?>