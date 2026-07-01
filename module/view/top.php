<?php
	$qfoto=mysqli_query($con,"SELECT file FROM file WHERE active='Y' AND type='foto' AND user='$_SESSION[ses_user]' LIMIT 1");
	$foto=mysqli_fetch_array($qfoto,MYSQLI_BOTH);
	$cek_foto=mysqli_num_rows($qfoto);

	if($cek_foto<1) {
		$show_foto = '<a href=account-foto-add---->[Masukan]</a></center><br>' ;
		$pp = '/file/foto/default.png';
		$pp_small = '/file/foto/default.png';
	}
	else {
		$show_foto = '<a href=account-foto-change---->[Edit]</a></center><br>';
		$pp = '/file/foto/180xheight_'.$foto['file'];
		$pp_small = '/file/foto/50_'.$foto['file'];
	}

?>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-yellow"></i></a>
      </li>
      <li class="nav-item d-sm-inline-block">
        <a href="#" class="nav-link">Sistem Informasi Penelitian dan Pengabdian</a>
      </li>
    </ul>
      