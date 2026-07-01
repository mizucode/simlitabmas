<?php 

ob_start();	

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}
else {
	switch(@$folder[2]) {
		default :
		
		break;
		
		case 'penelitian':
			include "penelitian.php";
		break;

		case 'patenhki':
			include "patenhki.php";
		break;

		case 'karya':
			include "karya.php";
		break;

		case 'jurnal':
			include "jurnal.php";
		break;

		case 'prosiding':
			include "prosiding.php";
		break;

		case 'buku':
			include "buku.php";
		break;

		case 'chapter':
			include "chapter.php";
		break;

		case 'tulisan':
			include "tulisan.php";
		break;

		case 'monograf':
			include "monograf.php";
		break;

		case 'penerjemahan':
			include "penerjemahan.php";
		break;

		case 'penyuntingan':
			include "penyuntingan.php";
		break;

		case 'non_pub':
			include "non_pub.php";
		break;

	}
}