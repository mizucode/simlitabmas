<?php 

ob_start();	

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {
?>

<?php
	switch($folder[2]) {
	
		case '3' :
				$qp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,mhs.gender,user.hp,k.desa,k.kec,k.kota,k.size FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='3' AND k.thn='2021' ORDER BY k.desa,kelas.prodi ");
				$qcp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,mhs.gender,user.hp FROM mhs,prodi,user,kelas,trakm,ta,hak_akses AS h LEFT JOIN kegiatan AS k on(h.nim=k.nim AND k.jenis='3') WHERE mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND h.ta=trakm.ta AND h.nim=mhs.nim AND h.kkn='Y' AND k.id IS NULL ORDER BY mhs.prodi,kelas.semester,kelas.kelas,mhs.nim");
?>
				<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Peserta KKN</h3>
			</div>
			<div class="card-body table-responsive p-0">
				<table id='example1' class='table table-bordered text-nowrap'>
				
					<thead>
					<tr>
						<td>No</td>
						<td>Prodi</td>
						<td>Kelas</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>JK</td>
						<td>KONTAK</td>
						<td>KELOMPOK</td>
						<td>KECAMATAN</td>
						<td>SIZE</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no=1;
				while($p=mysqli_fetch_array($qp)) {
					$name = strtoupper($p[name]);
					$desa = ucwords(strtolower($p[desa]));
					$kec = ucwords(strtolower($p[kec]));
					$kota = ucwords(strtolower($p[kota]));
					echo"

					<tr>
						<td>$no</td>
						<td>$p[prodi]</td>
						<td>Smt. $p[semester] $p[kelas]</td>
						<td>$p[nim]</td>
						<td>$name</td>
						<td>$p[gender]</td>
						<td>$p[hp]</td>
						<td>$desa</td>
						<td>$kec</td>
						<td>$p[size]</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>
		
		<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Calon Peserta KKN (sudah pembayaran namun belum daftar)</h3>
			</div>
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered'>
					<thead>
					<tr>
						<td>No</td>
						<td>Prodi</td>
						<td>Kelas</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>JK</td>
						<td>KONTAK</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no=1;
				while($cp=mysqli_fetch_array($qcp)) {
					$cname = strtoupper($cp[name]);
					echo"

					<tr>
						<td>$no</td>
						<td>$cp[prodi]</td>
						<td>Smt. $cp[semester] $cp[kelas]</td>
						<td>$cp[nim]</td>
						<td>$cname</td>
						<td>$cp[gender]</td>
						<td>$cp[hp]</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>

<?php		
		break;


		case '4' :
				$no=1;
				$qp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,user.hp,k.* FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='4' AND k.finish!='Y' ORDER BY k.approve,k.id DESC ");
				
?>
				<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Pendaftar Seminar Proposal</h3>
			</div>
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered text-xs'>
					<thead>
					<tr>
						<td>NO.</td>
						<td>PRODI</td>
						<td>KELAS</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>SKRIPSI</td>
						<td>PEMB. I</td>
						<td>TGL DAFTAR</td>
						<td>STATUS</td>
						<td>AKSI</td>
					</tr>
					</thead>
					<tbody>
<?php
				while($p=mysqli_fetch_array($qp)) {
					$name = strtoupper($p[name]);
					$dosen1 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$p[dosen1]' "));
					$dosen2 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$p[dosen2]' "));
					
					switch($p[approve]) {
						case '1':
							$status = "<small class='label bg-yellow'>Proses Persetujuan LPPM</small>";
						break;
						case '2':
							$status = "<small class='label bg-green'>Disetujui LPPM</small>";
						break;
						case '3':
							$status = "<small class='label bg-red'>Perlu Revisi</small>";
						break;
					}

					echo"

					<tr>
						<td>$no</td>
						<td>$p[prodi]</td>
						<td>Smt. $p[semester] $p[kelas]</td>
						<td>$p[nim]</td>
						<td>$name</td>
						<td>$p[skripsi]</td>
						<td>$dosen1[name_glr]</td>
						<td>$p[stamp]</td>
						<td>$status</td>
						<td>
							<a href='https://universe.umkuningan.ac.id/$p[file1]' target='_blank' class='m-1'>[Naskah]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file2]' target='_blank' class='m-1'>[Kartu Bimbingan]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file3]' target='_blank' class='m-1'>[Kartu Partisipan]</a>
							<a href='/update/approve_proposal/$p[id]' class='btn btn-success btn-xs m-1'><i class='fa fa-check'></i> Setuji</a>
							<a href='/update/revision_proposal/$p[id]' class='btn btn-warning btn-xs m-1'><i class='fa fa-edit'></i> Perbaiki</a>
						</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>
<?php
				$qps = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,user.hp,k.skripsi,k.dosen1,k.dosen2,k.date,k.file1,k.file2,k.file3 FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='4' AND k.finish='Y' ORDER BY k.id ");
				
?>
				<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Pendaftar yang Telah Selesai Seminar Proposal</h3>
			</div>
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered text-sm'>
					<thead>
					<tr>
						<td>No</td>
						<td>PRODI</td>
						<td>KELAS</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>SKRIPSI</td>
						<td>PEMB. I</td>
						<td>PEMB. II</td>
						<td>TGL DAFTAR</td>
						<td>AKSI</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no=1;
				while($ps=mysqli_fetch_array($qps)) {
					$names = strtoupper($ps[name]);
					$dosens1 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$ps[dosen1]' "));
					$dosens2 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$ps[dosen2]' "));

					echo"

					<tr>
						<td>$no</td>
						<td>$ps[prodi]</td>
						<td>Smt. $ps[semester] $ps[kelas]</td>
						<td>$ps[nim]</td>
						<td>$names</td>
						<td>$ps[skripsi]</td>
						<td>$dosens1[name_glr]</td>
						<td>$dosens2[name_glr]</td>
						<td>$ps[date]</td>
						<td>
							<a href='https://universe.umkuningan.ac.id/$ps[file1]' target='_blank' class='m-1'>[Naskah]</a><br>
							<a href='https://universe.umkuningan.ac.id/$ps[file2]' target='_blank' class='m-1'>[Kartu Bimbingan]</a><br>
							<a href='https://universe.umkuningan.ac.id/$ps[file3]' target='_blank' class='m-1'>[Kartu Partisipan]</a>
						</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>


<?php		
		break;

		case '5' :
				$no=1;
				$qp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,user.hp,k.* FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='5' AND k.finish!='Y' ORDER BY k.approve,k.id DESC ");
				
?>
				<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Pendaftar Sidang Skripsi</h3>
			</div>
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered text-xs'>
					<thead>
					<tr>
						<td>NO.</td>
						<td>PRODI</td>
						<td>KELAS</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>SKRIPSI</td>
						<td>PEMB. I</td>
						<td>TGL DAFTAR</td>
						<td>STATUS</td>
						<td>AKSI</td>
					</tr>
					</thead>
					<tbody>
<?php
				while($p=mysqli_fetch_array($qp)) {
					$name = strtoupper($p[name]);
					$dosen1 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$p[dosen1]' "));
					$dosen2 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$p[dosen2]' "));
					
					switch($p[approve]) {
						case '1':
							$status = "<small class='label bg-yellow'>Proses Persetujuan LPPM</small>";
						break;
						case '2':
							$status = "<small class='label bg-green'>Disetujui LPPM</small>";
						break;
						case '3':
							$status = "<small class='label bg-red'>Perlu Revisi</small>";
						break;
					}

					echo"

					<tr>
						<td>$no</td>
						<td>$p[prodi]</td>
						<td>Smt. $p[semester] $p[kelas]</td>
						<td>$p[nim]</td>
						<td>$name</td>
						<td>$p[skripsi]</td>
						<td>$dosen1[name_glr]</td>
						<td>$p[stamp]</td>
						<td>$status</td>
						<td>
							<a href='https://universe.umkuningan.ac.id/$p[file1]' target='_blank' class='m-1'>[Naskah]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file2]' target='_blank' class='m-1'>[Kartu Bimbingan]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file3]' target='_blank' class='m-1'>[Kartu Partisipan]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file4]' target='_blank' class='m-1'>[LoA/Submitted]</a><br>
							<a href='https://universe.umkuningan.ac.id/$p[file5]' target='_blank' class='m-1'>[Artikel]</a><br>
							<a href='/update/approve_skripsi/$p[id]' class='btn btn-success btn-xs m-1'><i class='fa fa-check'></i> Setuji</a>
							<a href='/update/revision_skripsi/$p[id]' class='btn btn-warning btn-xs m-1'><i class='fa fa-edit'></i> Perbaiki</a>
						</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>
<?php
				$qps = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,user.hp,k.skripsi,k.dosen1,k.dosen2,k.date,k.file1 FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='5' AND k.finish='Y' ORDER BY k.id ");
				
?>
				<div class='card'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Daftar Pendaftar yang Telah Selesai Sidang Skripsi</h3>
			</div>
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered text-sm'>
					<thead>
					<tr>
						<td>No</td>
						<td>PRODI</td>
						<td>KELAS</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>SKRIPSI</td>
						<td>PEMB. I</td>
						<td>PEMB. II</td>
						<td>TGL DAFTAR</td>
						<td>AKSI</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no=1;
				while($ps=mysqli_fetch_array($qps)) {
					$names = strtoupper($ps[name]);
					$dosens1 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$ps[dosen1]' "));
					$dosens2 = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$ps[dosen2]' "));

					echo"

					<tr>
						<td>$no</td>
						<td>$ps[prodi]</td>
						<td>Smt. $ps[semester] $ps[kelas]</td>
						<td>$ps[nim]</td>
						<td>$names</td>
						<td>$ps[skripsi]</td>
						<td>$dosens1[name_glr]</td>
						<td>$dosens2[name_glr]</td>
						<td>$ps[date]</td>
						<td>
							<a href='https://universe.umkuningan.ac.id/$ps[file1]' class='btn btn-success'><i class='fa fa-file-pdf-o'></i> File</a><p></p>
						</td>
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>


<?php		
		break;

	}
?>		

<?php


}

?>