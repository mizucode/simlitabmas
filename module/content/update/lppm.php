<?php
session_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}

else {

	define('valid','1');

/*===================== ACCOUNT ============================= */

	if ($folder[2]=='account') {
	}

/*===================== HAND LABELING ============================= */

	elseif ($folder['2']=='handlabeling') {

		$q = mysqli_query($con,"SELECT e.id,COUNT(*) AS j FROM edom_result AS e,kontrak,dosen WHERE e.question='usulan' AND kontrak.id=e.id_kontrak AND kontrak.dosen=dosen.nik AND dosen.ikatan!='A' AND answer!='' AND sen='' GROUP BY e.answer HAVING j='1' ORDER BY RAND() ");

			while($r=mysqli_fetch_array($q)) {
				$id = $r[id].id;
				$sen_id = mysqli_real_escape_string($con,$_POST[$id]);

				$sen = $r[id].sen;
				$sen_form = mysqli_real_escape_string($con,$_POST[$sen]);
				if($r[id]==$sen_id) {
					mysqli_query($con,"UPDATE edom_result SET sen='$sen_form' WHERE id='$r[id]' ");
				}
			}

			header('location:/edom/handlabeling/');
	}
	elseif ($folder['2']=='edithandlabeling') {

		$q = mysqli_query($con,"SELECT e.id,COUNT(*) AS j FROM edom_result AS e,kontrak,dosen WHERE e.question='usulan' AND kontrak.id=e.id_kontrak AND kontrak.dosen=dosen.nik AND dosen.ikatan!='A' AND answer!='' AND sen!='' GROUP BY e.answer HAVING j='1' ORDER BY e.sen LIMIT 1600,400 ");

 			while($r=mysqli_fetch_array($q)) {
				$id = $r[id].id;
				$sen_id = mysqli_real_escape_string($con,$_POST[$id]);

				$sen = $r[id].sen;
				$sen_form = mysqli_real_escape_string($con,$_POST[$sen]);
				if($r[id]==$sen_id) {
					mysqli_query($con,"UPDATE edom_result SET sen='$sen_form' WHERE id='$r[id]' ");
				}
			}

			header('location:/edom/handlabeling/review');
	}
	
	elseif ($folder[2]=='approve_proposal') {
		$id = mysqli_real_escape_string($con,$folder[3]);
		mysqli_query($con,"UPDATE kegiatan SET approve='2' WHERE id='$id' LIMIT 1 ");
		header('location:/kegiatan/4/?m=updated');
	}
	elseif ($folder[2]=='revision_proposal') {
		$id = mysqli_real_escape_string($con,$folder[3]);
		mysqli_query($con,"UPDATE kegiatan SET approve='3',finish='N' WHERE id='$id' LIMIT 1 ");
		header('location:/kegiatan/4/?m=updated');
	}
	
	elseif ($folder[2]=='approve_skripsi') {
		$id = mysqli_real_escape_string($con,$folder[3]);
		mysqli_query($con,"UPDATE kegiatan SET approve='2' WHERE id='$id' LIMIT 1 ");
		header('location:/kegiatan/5/?m=updated');
	}
	elseif ($folder[2]=='revision_skripsi') {
		$id = mysqli_real_escape_string($con,$folder[3]);
		mysqli_query($con,"UPDATE kegiatan SET approve='3',finish='N' WHERE id='$id' LIMIT 1 ");
		header('location:/kegiatan/5/?m=updated');
	}
	
	elseif ($folder[2]=='ppm') {
		switch($folder['3']) {

			case 'penelitian':
				$id		= mysqli_real_escape_string($con,$_POST[id]);
				$acc_dana	= mysqli_real_escape_string($con,$_POST[acc_dana]);
				$nik_reviewer1	= mysqli_real_escape_string($con,$_POST[nik_reviewer1]);
				$nik_reviewer2	= mysqli_real_escape_string($con,$_POST[nik_reviewer2]);				
				$name_reviewer1 = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$nik_reviewer1' "));
				$name_reviewer2 = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$nik_reviewer2' "));
		
				mysqli_query($con,"UPDATE ajuan_penelitian SET nik_reviewer1='$nik_reviewer1', name_reviewer1='$name_reviewer1[name_glr]', nik_reviewer2='$nik_reviewer2', name_reviewer2='$name_reviewer2[name_glr]', acc_dana='$acc_dana', status='1' WHERE id='$id' LIMIT 1 ");
				
					header('location:/ppm/penelitian/?m=updated');
			break;
			case 'pengabdian':
				$id		= mysqli_real_escape_string($con,$_POST[id]);
				$acc_dana	= mysqli_real_escape_string($con,$_POST[acc_dana]);
				$nik_reviewer1	= mysqli_real_escape_string($con,$_POST[nik_reviewer1]);
				$nik_reviewer2	= mysqli_real_escape_string($con,$_POST[nik_reviewer2]);				
				$name_reviewer1 = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$nik_reviewer1' "));
				$name_reviewer2 = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$nik_reviewer2' "));
		
				mysqli_query($con,"UPDATE ajuan_pengabdian SET nik_reviewer1='$nik_reviewer1', name_reviewer1='$name_reviewer1[name_glr]', nik_reviewer2='$nik_reviewer2', name_reviewer2='$name_reviewer2[name_glr]', acc_dana='$acc_dana', status='1' WHERE id='$id' LIMIT 1 ");
				
					header('location:/ppm/pengabdian/?m=updated');
			break;
			
		}
	}

	elseif ($folder[2]=='ppm_acc') {
		switch($folder['3']) {

			case 'penelitian':
				$id = $folder[4];
				$approve = $folder[5];
		
				mysqli_query($con,"UPDATE ajuan_penelitian SET status='$approve' WHERE id='$id' LIMIT 1 ");

				header('location:/ppm/penelitian/?m=updated');
			break;

			case 'pengabdian':
				$id = $folder[4];
				$approve = $folder[5];
		
				mysqli_query($con,"UPDATE ajuan_pengabdian SET status='$approve' WHERE id='$id' LIMIT 1 ");

				header('location:/ppm/pengabdian/?m=updated');
			break;			
		}
	}

	
}
?>