<?php 

ob_start();	

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {

		switch(@$folder['2']) {
			default :
	?>
					<div class='card card-success'>
						<div class='card-header with-border table-striped'>
							<h3 class='card-title'>Dosen</h3>
						</div>

						<div class="card-body">
	<?php

			$no=1;
			$qdosen=mysqli_query($con,"SELECT kode.desc AS ikatan,dosen.nik,dosen.nidn,dosen.name_glr,user.hp,dosen.status FROM dosen,user,kode WHERE dosen.ikatan=kode.value AND kode.kelompok='ikatan' AND dosen.nik=user.username AND dosen.status!='N' AND dosen.status!='K' AND dosen.status!='C' AND dosen.status!='' ORDER BY dosen.ikatan,dosen.status,dosen.nik ");

			if(mysqli_num_rows($qdosen)>0) {

				echo"
					<table class='table table-bordered table-striped table-hover'>
					<tr class='success'>
					<td><b>No</td>
					<td><b>NIK</td>
					<td><b>NIDN</td>
					<td><b>Nama</td>
					<td><b>Kontak</td>
					<td><b>Ikatan</td>
					<td><b>Status</td>
					</tr>
				";
	
				while($dosen=mysqli_fetch_array($qdosen)) {
					echo"
						<tr>
						<td>$no</td>
						<td><a href='/dosen/detail/$dosen[nik]'>$dosen[nik]</a></td>
						<td>$dosen[nidn]</td>
						<td>$dosen[name_glr]</td>
						<td>$dosen[hp]</td>
						<td>$dosen[ikatan]</td>
						<td>$dosen[status]</td>
						<tr>
					";
					$no++;
				}

			echo"
	
				</table>
			";

			}
			
			else {
				echo" <h3>Tidak terdapat dosen</h3> ";
			}



	?>
						</div>

					</div>

		<?php

			break;

			case 'not_active' :
	?>
					<div class='card card-success'>
						<div class='card-header with-border table-striped'>
							<h3 class='card-title'>Dosen Tidak Aktif</h3>
						</div>

						<div class="card-body">
	<?php

			$no=1;
			$qdosen=mysqli_query($con,"SELECT kode.desc AS ikatan,dosen.nik,dosen.nidn,dosen.name_glr,user.hp,dosen.status FROM dosen,user,kode WHERE dosen.ikatan=kode.value AND kode.kelompok='ikatan' AND dosen.nik=user.username AND dosen.status!='A' AND dosen.status!='S' ORDER BY dosen.ikatan,dosen.status,dosen.nik ");

			if(mysqli_num_rows($qdosen)>0) {

				echo"
					<table class='table table-bordered table-striped table-hover'>
					<tr class='success'>
					<td><b>No</td>
					<td><b>NIK</td>
					<td><b>NIDN</td>
					<td><b>Nama</td>
					<td><b>Kontak</td>
					<td><b>Ikatan</td>
					<td><b>Status</td>
					</tr>
				";
	
				while($dosen=mysqli_fetch_array($qdosen)) {
					echo"
						<tr>
						<td>$no</td>
						<td><a href='/dosen/detail/$dosen[nik]'>$dosen[nik]</a></td>
						<td>$dosen[nidn]</td>
						<td>$dosen[name_glr]</td>
						<td>$dosen[hp]</td>
						<td>$dosen[ikatan]</td>
						<td>$dosen[status]</td>
						<tr>
					";
					$no++;
				}

			echo"
	
				</table>
			";

			}
			
			else {
				echo" <h3>Tidak terdapat dosen</h3> ";
			}



	?>
						</div>

					</div>

		<?php

			break;

			case 'on_going' :
	?>

			<div class='row'>

				<div class='col-md-3'>

					<div class='list-group'>
						<a href='#' class='list-group-item active'>Pilih TA</a>
<?php
						$qta = mysqli_query($con,"SELECT kode FROM ta ORDER BY kode DESC ");
						while($ta = mysqli_fetch_array($qta)) {
							echo"<a href='/dosen/on_going/$ta[kode]' class='list-group-item'>$ta[kode]</a>";
						}
?>
					</div>
				</div>

				<div class='col-md-9'>
					<div class='card card-success'>
						<div class='card-header with-border table-striped'>
							<h3 class='card-title'>Dosen</h3>
						</div>

						<div class="card-body">
	<?php

			if(!empty($folder[3])) {
				$ta = $folder[3];
			}
			else {
				$qta = mysqli_fetch_array(mysqli_query($con,"SELECT kode FROM ta WHERE active='Y' "));
				$ta = $qta[kode];
			}


			$no=1;
			$qdosen=mysqli_query($con,"SELECT dosen.nidn,kode.desc AS ikatan,dosen.nik,dosen.name_glr,user.hp,dosen.status FROM dosen,user,kode,kontrak WHERE dosen.ikatan=kode.value AND kode.kelompok='ikatan' AND dosen.nik=user.username AND dosen.nik=kontrak.dosen AND kontrak.ta='$ta' GROUP BY dosen.nik ORDER BY dosen.ikatan,dosen.status,dosen.nik ");

			if(mysqli_num_rows($qdosen)>0) {

				echo"
					<table class='table table-bordered table-striped table-hover'>
					<tr class='success'>
					<td><b>No</td>
					<td><b>NIK</td>
					<td><b>NIDN</td>
					<td><b>Nama</td>
					<td><b>Kontak</td>
					<td><b>Ikatan</td>
					<td><b>Status</td>
					<td><b>Edom</td>
					<td><b>Usulan</td>
					</tr>
				";
	
				while($dosen=mysqli_fetch_array($qdosen)) {
					echo"
						<tr>
						<td>$no</td>
						<td><a href='/dosen/detail/$dosen[nik]'>$dosen[nik]</a></td>
						<td>$dosen[nidn]</td>
						<td>$dosen[name_glr]</td>
						<td>$dosen[hp]</td>
						<td>$dosen[ikatan]</td>
						<td>$dosen[status]</td>
						<td>
					"; ?>
						<a href="#" class="pull-right" onclick=window.open('/cetak/edom/dosen/<?php echo $dosen['nik']; ?>/<?php echo $folder[3]; ?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=600,directories=no,location=no'); return false;><i class="fa fa-print"></i></a>
						</td>
						<td>
						<a href="#" class="pull-right" onclick=window.open('/cetak/edom/usulan/<?php echo $dosen['nik']; ?>/<?php echo $folder[3]; ?>','win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=850,height=600,directories=no,location=no'); return false;><i class="fa fa-print"></i></a>
						</td>
					<?php echo"
						<tr>
					";
					$no++;
				}

			echo"
	
				</table>
			";

			}
			
			else {
				echo" <h3>Tidak terdapat dosen</h3> ";
			}



	?>
						</div>

					</div>

				</div>

			</div>
		<?php

			break;

			case 'homebase' :
	?>

			<div class='row'>

				<div class='col-md-3'>

					<div class='list-group'>
						<a href='#' class='list-group-item active'>Pilih Program Studi</a>
<?php
						$qprodi = mysqli_query($con,"SELECT singkatan,kode FROM prodi ORDER BY kode DESC");
						while($prodi = mysqli_fetch_array($qprodi)) {
							echo"<a href='/dosen/homebase/$prodi[kode]' class='list-group-item'>$prodi[singkatan]</a>";
						}
?>
					</div>
				</div>

				<div class='col-md-9'>
					<div class='card card-success'>
						<div class='card-header with-border table-striped'>
							<h3 class='card-title'>Dosen Homebase</h3>
						</div>

						<div class="card-body">
	<?php

			if(!empty($folder[3])) {
				$prodi = $folder[3];
			}
			else {
				$prodi = '';
			}


			$no=1;
			$qdosen=mysqli_query($con,"SELECT kode.desc AS ikatan,dosen.nik,dosen.nidn,dosen.name_glr,user.hp,dosen.status FROM dosen,user,kode WHERE dosen.ikatan=kode.value AND kode.kelompok='ikatan' AND dosen.nik=user.username AND dosen.status!='N' AND dosen.status!='K' AND dosen.status!='C' AND dosen.status!='' AND dosen.homebase='$prodi' ORDER BY dosen.ikatan,dosen.status,dosen.nik ");

			if(mysqli_num_rows($qdosen)>0) {

				echo"
					<table class='table table-bordered table-striped table-hover'>
					<tr class='success'>
					<td><b>No</td>
					<td><b>NIK</td>
					<td><b>NIDN</td>
					<td><b>Nama</td>
					<td><b>Kontak</td>
					<td><b>Ikatan</td>
					<td><b>Status</td>
					</tr>
				";
	
				while($dosen=mysqli_fetch_array($qdosen)) {
					echo"
						<tr>
						<td>$no</td>
						<td><a href='/dosen/detail/$dosen[nik]'>$dosen[nik]</a></td>
						<td>$dosen[nidn]</td>
						<td>$dosen[name_glr]</td>
						<td>$dosen[hp]</td>
						<td>$dosen[ikatan]</td>
						<td>$dosen[status]</td>
						<tr>
					";
					$no++;
				}

			echo"
	
				</table>
			";

			}
			
			else {
				echo" <h3>Tidak terdapat dosen</h3> ";
			}



	?>
						</div>

					</div>

				</div>

			</div>
		<?php

			break;


			case 'detail' :

				$qdsn = mysqli_query($con,"SELECT d.name_glr,d.nik FROM dosen AS d WHERE d.nik='$folder[3]' ");
				if(mysqli_num_rows($qdsn)>0) {
					$dsn = mysqli_fetch_array($qdsn);
					$qf=mysqli_query($con,"SELECT file FROM file WHERE active='Y' AND type='foto' AND user='$dsn[nik]' LIMIT 1");
					$f=mysqli_fetch_array($qf);
					$cek_f=mysqli_num_rows($qf);

					if($cek_f<1) {
						$pp2 = '/file/foto/default.png';
						$pp_small2 = '/file/foto/default.png';
					}
					else {
						$pp2 = '/file/foto/180xheight_'.$f['file'];
						$pp_small2 = '/file/foto/50_'.$f['file'];
					}

?>					
					<div class='row'>
						<div class='col-md-12'>
							<div class='callout callout-info'>
								<h4><?php echo $dsn['name_glr']; ?></h4>
							</div>
						</div>
						<div class='col-md-3'>
							<div class='card card-success'>
								<div class="card-header">
									Foto
								</div>
								<div class="card-body">
				                    <img src="<?php echo $pp2; ?>" alt="User Image" />
								</div>
							</div>
						</div>

						<div class='col-md-12'>
							<div class='card card-success'>
								<div class="card-header">
									Data Kontrak Mengajar
								</div>
								<div class="card-body">

			<div class='row'>

				<div class='col-md-3'>
					<div class='list-group'>
						<a href='#' class='list-group-item active'>Tahun Akademik</a>
			<?php

				$qk = mysqli_query($con,"SELECT ta FROM kontrak WHERE dosen='$folder[3]' GROUP BY ta ORDER BY ta DESC");
				while($k = mysqli_fetch_array($qk)) {
					echo "
						<a class='list-group-item' href='/dosen/detail/$folder[3]/$k[ta]'>$k[ta]</a>
					";
				}

			if(empty($folder['4'])) {
				$ta = mysqli_fetch_array(mysqli_query($con,"SELECT kode FROM ta WHERE active='Y' "));
				$folder['4'] = $ta['kode'];
			}

			?>
					</div>
				</div>

				<div class='col-md-9'>
					<div class='card card-success'>
						<div class='card-header with-border'>
							<h3 class='card-title'><?php echo @$folder['4']; ?></h3>
						</div>
						
						<div class='card-body'>
<?php

					$no=1;
					$dkontrak=mysqli_query($con,"SELECT day.name,time.mulai,time.sort,room.nama,time.sort AS id_time,kontrak.id,kurikulum_mk.kode,kurikulum_mk.sks,kurikulum_mk.name_mk AS mk,prodi.singkatan,kelas.semester,kelas.kelas,kontrak.ta,kelas.angkatan,day,time,room FROM kontrak,mk,kelas,ta,prodi,kurikulum_mk,day,time,room WHERE kelas.kurikulum=kurikulum_mk.kurikulum AND kontrak.day=day.id AND kontrak.time=time.id AND kontrak.room=room.id AND kurikulum_mk.mk=kontrak.id_mk AND kontrak.id_mk=mk.id AND kontrak.paket=kelas.kode AND kelas.ta=ta.kode AND kontrak.prodi=prodi.kode AND kontrak.ta=ta.kode AND ta.kode='$folder[4]' AND kontrak.dosen='$folder[3]' ");

					echo"
						<table class='table table-bordered table-hover table-striped'>
						<tr class='success'>
							<td><b>No</td>
							<td><b>Kode</td>
							<td><b>Mata Kuliah</td>
							<td><b>SKS</td>
							<td><b>Prodi</td>
							<td><b>Kelas</td>
							<td>Hari</td>
							<td>Waktu</td>
							<td>Ruang</td>
						</tr>
					";
				
					while($kontrak=mysqli_fetch_array($dkontrak)) {

						$sks = $kontrak['sks'] - 1;
						$id_time = $sks + $kontrak['id_time'];

						$akhir = mysqli_fetch_array(mysqli_query($con,"SELECT akhir FROM `time` WHERE `sort`='$id_time' "));

						echo"
							<tr>
							<td>$no</td>
							<td><a href='/matakuliah/$kontrak[kode]'>$kontrak[kode]</td>
							<td>$kontrak[mk]</td>
							<td>$kontrak[sks]</td>
							<td>$kontrak[singkatan]</td>
							<td>$kontrak[angkatan] Smt $kontrak[semester] $kontrak[kelas]</td>
							<td>$kontrak[name]</a></td>
							<td>$kontrak[mulai] - $akhir[akhir]</td>
							<td>$kontrak[nama]</td>

							</tr>
						";
						$no++;
					}
				
					$dsks=mysqli_query($con,"SELECT SUM(kurikulum_mk.sks) FROM kontrak,kurikulum_mk,kelas,ta WHERE kelas.kode=kontrak.paket AND kelas.kurikulum=kurikulum_mk.kurikulum AND kelas.ta=ta.kode AND kontrak.id_mk=kurikulum_mk.mk AND kontrak.ta=ta.kode AND kontrak.dosen='$folder[3]' AND ta.kode='$folder[4]' ");
					$sks=mysql_result($dsks, 0, 0);
					echo"
						<tr>
						<td colspan=3>Total Sks</td>
						<td>$sks</td>
						</tr>
						</table><br>
					";


?>
						</div>
					</div>
				</div>



				
				
			</div>

								</div>
							</div>
						</div>

						<div class='col-md-12'>
							<div class='card card-success'>
								<div class="card-header">
									Evaluasi Dosen
								</div>
								<div class="card-body">
								
								</div>
							</div>
						</div>
						


					</div>
						
<?php
				}
				else {

				}
?>



<?php


			break;
		}

	}



?>