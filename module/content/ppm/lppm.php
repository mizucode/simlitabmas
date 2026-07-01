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

		case 'pengabdian':
			include "pengabdian.php";
		break;

	}
}