<?php 

ob_start();

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {
?>

<?php
	switch($folder[2]) {
		default :
				$qp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,mhs.gender,user.hp,k.desa,k.kec,k.kota,k.size FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta WHERE mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='3' AND k.thn='2026' ORDER BY k.desa,kelas.prodi ");
				$qbp = mysqli_query($con,"SELECT mhs.prodi,mhs.nim,mhs.name,prodi.singkatan,kelas.semester,kelas.kelas,user.hp FROM mhs,kelas,prodi,user,hak_akses LEFT JOIN kegiatan on(hak_akses.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa!='') WHERE mhs.nim=hak_akses.nim AND mhs.prodi=prodi.kode AND kelas.kode=mhs.kelas AND kelas.ta=hak_akses.ta AND mhs.nim=user.username AND hak_akses.ta='20252' AND hak_akses.kkn='Y' AND mhs.jalur!='5' AND kegiatan.nim IS NULL ORDER BY mhs.prodi,mhs.kelas ");
				
				$qbk = mysqli_query($con,"SELECT mhs.prodi,mhs.nim,mhs.name,mhs.pindahan,prodi.singkatan,kelas.semester,kelas.kelas,user.hp,hak_akses.kkn FROM mhs,kelas,prodi,user,hak_akses,trakm WHERE mhs.nim=trakm.nim AND trakm.ta='20252' AND mhs.nim=hak_akses.nim AND mhs.prodi=prodi.kode AND kelas.kode=mhs.kelas AND kelas.ta=hak_akses.ta AND mhs.nim=user.username AND hak_akses.ta='20252' AND hak_akses.kkn!='Y' AND trakm.status='A' AND kelas.semester='6' AND mhs.prodi!='48401' AND mhs.prodi!='13462' AND mhs.jalur!='5' ORDER BY mhs.prodi,mhs.kelas ");
				
				$qkelompok = mysqli_query($con,"SELECT * FROM kuota_kel WHERE keterangan='kkn2026' GROUP BY kelompok ");
?>

		<div class='card card-success text-sm'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Kelompok KKN</h3>
			</div>
				<div class="card-body table-responsive">
					<table class='table table-bordered text-nowrap table-striped'>
						<thead>
						<tr>
							<td>Kelompok</td>
							<td>Jumlah Mhs</td>
							<td>Jumlah LK</td>
							<td>Jumlah PR</td>
							<td>PTIK</td>
							<td>PGSD</td>
							<td>PBSD</td>
							<td>PJKR</td>
							<td>PMTK</td>
							<td>PGPAUD</td>
							<td>Farmasi</td>
						</tr>
						</thead>
						<tbody>
				<?php
					while($kelompok = mysqli_fetch_array($qkelompok)) {
						$jp = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM kegiatan WHERE desa='$kelompok[kelompok]' AND thn='2026' "));
						$jplk =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.gender='L' "));
						$jppr =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.gender='P' "));
						$ptik =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='83207' "));
						$pjkr =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='85201' "));
						$pmtk =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='84202' "));
						$pgpaud =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='86207' "));
						$pbsd =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='88202' "));
						$pgsd =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='86206' "));
						$farmasi =  mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM mhs,kegiatan WHERE mhs.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[kelompok]' AND mhs.prodi='48201' "));

						  echo"
							<tr>
							<td>$kelompok[kelompok]</td>
							<td>$jp[j]</td>
							<td>$jplk[j]</td>
							<td>$jppr[j]</td>
							<td>$ptik[j]</td>
							<td>$pgsd[j]</td>
							<td>$pbsd[j]</td>
							<td>$pjkr[j]</td>
							<td>$pmtk[j]</td>
							<td>$pgpaud[j]</td>
							<td>$farmasi[j]</td>
							</tr>
						    ";
					}
				?>
						</tbody>
					</table>
				</div>
		</div>
		
		<div class='card card-success'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Peserta KKN</h3>
			</div>
					
			
			<div class="card-body table-responsive text-xs">
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
					</tr>

					";
					$no++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>
		
		
		<div class='card card-success'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Peserta Sudah Keuangan Tetapi Belum Daftar KKN</h3>
			</div>
					
			
			<div class="card-body table-responsive text-xs">
				<table id='example2' class='table table-bordered text-nowrap'>
				
					<thead>
					<tr>
						<td>No</td>
						<td>Prodi</td>
						<td>Kelas</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>KONTAK</td>
						<td>BTQ</td>
						<td>MAGANG DASAR</td>
						<td>MAGANG LANJUT</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no2=1;
				while($bp=mysqli_fetch_array($qbp)) {
					$name2 = strtoupper($bp[name]);
					$cek_mbkm = mysqli_num_rows(mysqli_query($con,"SELECT * FROM mbkm_kkn WHERE nim='$bp[nim]'"));
					$cek_btq = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM btq WHERE nim='$bp[nim]' "));
					if($bp[prodi]=='83207' AND $bp[kelas]!='KRY') {
						$cek_magang_dasar = '1';
						$cek_magang_lanjutan = '1';
					}
					elseif($bp[prodi]=='48201') {
						$cek_magang_dasar = '1';
						$cek_magang_lanjutan = '1';	
					}				
					else {
						$cek_magang_dasar = mysqli_num_rows(mysqli_query($con,"SELECT mhs.nim FROM mhs,nilai,kurikulum_mk AS k,mutu_nilai AS m WHERE mhs.nim='$bp[nim]' AND mhs.nim=nilai.nim AND nilai.id_mk=k.mk AND mhs.paket_studi=k.kurikulum AND nilai.mutu=m.huruf AND m.status='Y' AND k.kode='1510G01' "));
						$cek_magang_lanjutan = mysqli_num_rows(mysqli_query($con,"SELECT mhs.nim FROM mhs,nilai,kurikulum_mk AS k,mutu_nilai AS m WHERE mhs.nim='$bp[nim]' AND mhs.nim=nilai.nim AND nilai.id_mk=k.mk AND mhs.paket_studi=k.kurikulum AND nilai.mutu=m.huruf AND m.status='Y' AND k.kode='1510G02' "));
					}
					if($cek_btq[nim]!='') {
						$btq = 'Sudah';
					}
					else {
						if($bp[prodi]=='48201') {
							$btq = 'Sudah';						
						}
						else {
							$btq = 'Belum';
						}
					}

					if($cek_magang_dasar>0) { $magang_dasar = 'Ok'; } else { $magang_dasar = ''; }
					if($cek_magang_lanjutan>0) { $magang_lanjutan = 'Ok'; } else { $magang_lanjutan = ''; }

					if($cek_mbkm>0) {
						echo"<tr class='bg-secondary'>";
					
					}
					else {
						if($btq=='Sudah' AND $cek_magang_dasar>0 AND $cek_magang_lanjutan>0) {
							echo"<tr class='bg-success'>";
						}
						else {
							echo"<tr class='bg-danger'>";					
						}
					}
					
					echo"
						<td>$no2</td>
						<td>$bp[singkatan]</td>
						<td>Smt. $bp[semester] $bp[kelas]</td>
						<td>$bp[nim]</td>
						<td>$name2</td>
						<td>$bp[hp]</td>
						<td>$btq</td>
						<td>$magang_dasar</td>
						<td>$magang_lanjutan</td>
					</tr>

					";
					$no2++;
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>

		<div class='card card-success'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Peserta Belum Mendaftar KKN</h3>
			</div>
					
			
			<div class="card-body table-responsive text-xs">
				<table id='example2' class='table table-bordered text-nowrap'>
				
					<thead>
					<tr>
						<td>No</td>
						<td>Prodi</td>
						<td>Kelas</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>KONVERSI?</td>
						<td>KONTAK</td>
						<td>KEUANGAN</td>
						<td>MAGANG DASAR</td>
						<td>MAGANG LANJUT</td>
					</tr>
					</thead>
					<tbody>
<?php
				$no3=1;
				while($bk=mysqli_fetch_array($qbk)) {
					$name3 = strtoupper($bk[name]);
					$cek_mbkm3 = mysqli_num_rows(mysqli_query($con,"SELECT * FROM mbkm_kkn WHERE nim='$bk[nim]'"));
					$cek_btq3 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM btq WHERE nim='$bk[nim]' "));
					if($bk[prodi]=='83207' AND $bk[kelas]!='KRY') {
						$cek_magang_dasar3 = '1';
						$cek_magang_lanjutan3 = '1';
					}
					elseif($bk[prodi]=='48201') {
						$cek_magang_dasar3 = '1';
						$cek_magang_lanjutan3 = '1';	
					}				
					else {
						$cek_magang_dasar3 = mysqli_num_rows(mysqli_query($con,"SELECT mhs.nim FROM mhs,nilai,kurikulum_mk AS k,mutu_nilai AS m WHERE mhs.nim='$bk[nim]' AND mhs.nim=nilai.nim AND nilai.id_mk=k.mk AND mhs.paket_studi=k.kurikulum AND nilai.mutu=m.huruf AND m.status='Y' AND k.kode='1510G01' "));
						$cek_magang_lanjutan3 = mysqli_num_rows(mysqli_query($con,"SELECT mhs.nim FROM mhs,nilai,kurikulum_mk AS k,mutu_nilai AS m WHERE mhs.nim='$bk[nim]' AND mhs.nim=nilai.nim AND nilai.id_mk=k.mk AND mhs.paket_studi=k.kurikulum AND nilai.mutu=m.huruf AND m.status='Y' AND k.kode='1510G02' "));
					}
					if($cek_btq3[nim]!='') {
						$btq3 = 'Sudah';
					}
					else {
						if($bk[prodi]=='48201') {
							$btq3 = 'Sudah';						
						}
						else {
//							$btq3 = 'Belum';
    						$btq3 = 'Sudah';

						}
					}

					if($cek_magang_dasar3>0) { $magang_dasar3 = 'Ok'; } else { $magang_dasar3 = ''; }
					if($cek_magang_lanjutan3>0) { $magang_lanjutan3 = 'Ok'; } else { $magang_lanjutan3 = ''; }
					if($bk[kkn]=='Y') { $keuangan = 'Ok'; } else { $keuangan = ''; }

					if($cek_mbkm3>0) {
					
					}
					else {
						if($btq3=='Sudah' AND $cek_magang_dasar3>0 AND $cek_magang_lanjutan3>0 AND $bk[kkn]=='Y') {
							echo"<tr class='bg-success'>";
						}
						else {
							echo"<tr class='bg-danger'>";					
						}
						echo"
							<td>$no3</td>
							<td>$bk[singkatan]</td>
							<td>Smt. $bk[semester] $bk[kelas]</td>
							<td>$bk[nim]</td>
							<td>$name3</td>
							<td>$bk[pindahan]</td>
							<td>$bk[hp]</td>
							<td>$keuangan</td>
							<td>$magang_dasar3</td>
							<td>$magang_lanjutan3</td>
						</tr>
						";
						$no3++;

					}
	
				}


?>
					</tbody>					
				</table>
					
			</div>
		</div>



<?php		
		break;
		
		
		case 'luaran':
	
			$qkelompok = mysqli_query($con,"SELECT id,value,url_laporan,url_video,keterangan FROM kode WHERE kelompok='kkn2026' ");
?>
		<div class='card card-success text-sm'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Kelompok KKN</h3>
			</div>
				<div class="card-body table-responsive">
					<table class='table table-bordered text-nowrap table-striped'>
						<thead>
						<tr class="bg-info">
							<th>Kelompok</th>
							<th>Pembimbing</th>
							<th>Jumlah Mhs</th>
							<th>Catatan harian</th>
							<th>Pengupload</th>
							<th>Luaran</th>
							<th>Artikel</th>
							<th>Status</th>
						</tr>
						</thead>
						<tbody>
				<?php
					while($kelompok = mysqli_fetch_array($qkelompok)) {
						$jp = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(*) AS j FROM kegiatan WHERE desa='$kelompok[value]' AND thn='2026' "));

						$laporan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM laporan WHERE id_kelompok='$kelompok[id]' ORDER BY id DESC LIMIT 1"));
						$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT name_glr FROM dosen WHERE nik='$kelompok[keterangan]'"));
						$mhs = mysqli_fetch_array(mysqli_query($con,"SELECT mhs.name,user.hp FROM mhs,user WHERE mhs.nim=user.username AND mhs.nim='$laporan[user]'"));

						$ch = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(ch.id) AS j FROM catatanharian AS ch,kegiatan WHERE ch.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[value]' AND ch.valid='1' "));
						$chv = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(ch.id) AS j FROM catatanharian AS ch,kegiatan WHERE ch.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[value]' AND ch.valid='2' "));
						$chtv = mysqli_fetch_array(mysqli_query($con,"SELECT COUNT(ch.id) AS j FROM catatanharian AS ch,kegiatan WHERE ch.nim=kegiatan.nim AND kegiatan.jenis='3' AND kegiatan.thn='2026' AND kegiatan.desa='$kelompok[value]' AND ch.valid='3' "));

						if($laporan[validasi]=='') {
				                      $status = '<span class="btn btn-warning btn-xs">Belum Upload</span>';
						} 
						elseif($laporan[validasi]=='1') {
				                      $status = '<span class="btn btn-warning btn-xs">Sedang proses validasi DPL</span>';
						} 
						elseif($laporan[validasi]=='2') {
				                      $status = '<span class="btn btn-success btn-xs">Dinyatakan Valid oleh DPL</span>';
						} 
						elseif($laporan[validasi]=='3') {
				                      $status = '<span class="btn btn-danger btn-xs">Tidak Valid (Silakan revisi)</span>';
						}

						if($laporan[id]!='') {
						  echo"
							<tr>
							<td>$kelompok[value]</td>
							<td>$dosen[name_glr]</td>
							<td>$jp[j]</td>
							<td>
							<div class='btn btn-warning btn-xs mb-1'>$ch[j] Belum divalidasi</div><br>
							<div class='btn btn-success btn-xs mb-1'>$chv[j] Valid</div><br>
							<div class='btn btn-danger btn-xs' mb-1>$chtv[j] Tidak Valid</div><br>
							</td>
							<td>$mhs[name]<br>$mhs[hp]</td>
							<td>
								<a target='_blank' href='https://universe.umkuningan.ac.id/$laporan[url_laporan]' class='btn btn-xs btn-primary mb-1'><i class='fa fa-file-pdf'></i> Laporan</a><br>
								<a target='_blank' href='$laporan[url_video]' class='btn btn-xs btn-primary mb-1'><i class='fa fa-video'></i> Vidio</a><br>
								<a target='_blank' href='$laporan[url_media]' class='btn btn-xs btn-primary mb-1'><i class='fa fa-newspaper'></i> Berita</a>
							</td>
							<td>
								<p>
								<a target='_blank' href='https://universe.umkuningan.ac.id/$laporan[url_bukti]' class='btn btn-xs btn-primary'><i class='fa fa-file'></i> Bukti Submit</a>
								<a target='_blank' href='https://universe.umkuningan.ac.id/$laporan[url_naskah]' class='btn btn-xs btn-primary'><i class='fa fa-file'></i> Naskah Artikel</a>
								</p>
								<p>$laporan[jurnal] <br> Penerbit $laporan[penerbit] <br> ISSN. $laporan[issn] <br> Akreditasi $laporan[akreditasi] <br> <a href='$laporan[url_jurnal]'>$laporan[url_jurnal]</a></p>
							</td>
							<td>$status</td>
							</tr>
						    ";
					    	}
					    	else {
						  echo"
							<tr>
							<td>$kelompok[value]</td>
							<td>$dosen[name_glr]</td>
							<td>$jp[j]</td>
							<td>
							<div class='btn btn-warning btn-xs mb-1'>$ch[j] Belum divalidasi</div><br>
							<div class='btn btn-success btn-xs mb-1'>$chv[j] Valid</div><br>
							<div class='btn btn-danger btn-xs' mb-1>$chtv[j] Tidak Valid</div><br>
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							</tr>
						    ";
					    	}
					}
				?>
						</tbody>
					</table>
				</div>
		</div>

<?php		
		break;
		
		case 'nilai':
				$qp = mysqli_query($con,"SELECT prodi.singkatan AS prodi,kelas.semester,kelas.kelas,mhs.nim,mhs.name,mhs.gender,user.hp,k.desa,k.kec,k.kota,k.size,kode.url_laporan,kode.url_video FROM mhs,prodi,kegiatan AS k,user,kelas,trakm,ta,kode WHERE kode.kelompok='kkn2026' AND kode.value=k.desa AND mhs.nim=k.nim AND mhs.nim=user.username AND mhs.prodi=prodi.kode AND mhs.nim=trakm.nim AND kelas.kode=trakm.paket AND trakm.ta=kelas.ta AND trakm.ta=ta.kode AND ta.active='Y' AND k.jenis='3' AND k.thn='2026' ORDER BY k.desa,kelas.prodi ");

?>		
		<div class='card card-success'>
			<div class='card-header with-border'>
				<h3 class='card-title'>Peserta KKN</h3>
			</div>
					
			
			<div class="card-body table-responsive">
				<table id='example1' class='table table-bordered text-nowrap'>
				
					<thead>
					<tr>
						<td>No</td>
						<td>Prodi</td>
						<td>Kelas</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>KELOMPOK</td>
						<td>KECAMATAN</td>
						<td>NILAI</td>
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
					$qnilai = mysqli_query($con,"SELECT nilai.mutu FROM mhs,nilai,kurikulum_mk WHERE mhs.nim=nilai.nim AND nilai.id_mk=kurikulum_mk.mk AND mhs.paket_studi=kurikulum_mk.kurikulum AND kurikulum_mk.kode='1510G03' AND mhs.nim='$p[nim]' ORDER BY nilai.ta DESC LIMIT 1 ");
					if(mysqli_num_rows($qnilai)>0) {
						$nilai = mysqli_fetch_array($qnilai);						
					}
					else {
						$nilai[mutu] = '-Tidak KRS-';
					}
					echo"

					<tr>
						<td>$no</td>
						<td>$p[prodi]</td>
						<td>Smt. $p[semester] $p[kelas]</td>
						<td>$p[nim]</td>
						<td>$name</td>
						<td>$desa</td>
						<td>$kec</td>
						<td>$nilai[mutu]</td>
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