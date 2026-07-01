<?php 

ob_start();	

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}
else {
	switch(@$folder[3]) {
		default :
		
			if(empty($folder['3'])) {
				$hibah = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM hibah WHERE tipe='2' AND status='1' ORDER BY id DESC LIMIT 1 "));
			}
			else {
				$hibah = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM hibah WHERE id='$folder[3]' "));
			}
			$sta = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM ts WHERE kode='$folder[3]' "));
			$tgl_start = $sta[tgl_start];
			$tgl_end = $sta[tgl_end];
		
?>
			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='ta'>
							<option value='/ppm/pengabdian/<?php echo $folder[2]; ?>'>-- Pilih Skema --</option>


						<?php
		
						$qp = mysqli_query($con,"SELECT * FROM hibah WHERE tipe='2' ORDER BY tahun");
						while($p = mysqli_fetch_array($qp)) {
							if($p[id]==$folder[3]) { $selected = 'selected'; } else {$selected = '';}
							echo "
								<option value='/ppm/pengabdian/$p[id]/' $selected>$p[skema] ($p[tahun])</option>
							";
						}
						?>
						
							</select>
						
						
						</div>
					</div>
				</div>
			</div>
			
			<div class="callout callout-info">
				<h5><?php echo $hibah[skema]; ?></h5>
				<br>
				<p>
				Tahun <?php echo $hibah[tahun]; ?>
				</p>
				<p>
				Awal pengabdian <?php echo indo_date($hibah[awal]); ?>
				</p>
				<p>
				Lama kegiatan <?php echo $hibah[lama]; ?>
				</p>
				<p>
				Maksimal usulan <?php echo rupiah($hibah[dana]); ?>
				</p>
				<p>
				Periode pendaftaran usulan <?php echo indo_date($hibah[start_apply]); ?> s.d <?php echo indo_date($hibah[end_apply]); ?>
				</p>
			</div>
			
			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Ajuan pengabdian</h3>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-wrap">
								<thead>
									<tr>
										<th>No</th>
										<th>Judul</th>
										<th>Kelompok</th>
										<th>Reviewer</th>
										<th>Aksi</th>
									</tr>
								</thead>
								<tbody>
						<?php
							$no=1;
							$q = mysqli_query($con,"SELECT aj.*,dosen.nik,dosen.name_glr FROM ajuan_pengabdian AS aj,dosen WHERE dosen.nik=aj.nip_dosen ");
							if(mysqli_num_rows($q)<1) {
								echo"<tr><td colspan='6'><p class='text-center text-danger'>Tidak ada ajuan</p></td></tr>";
							}
							else {

								while($r=mysqli_fetch_array($q)) {
									$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$r[nip_dosen]' LIMIT 1"));
									$skema = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_skema]' AND kelompok='skema_pengabdian' LIMIT 1"));
									$peran = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_peran]' AND kelompok='peran_pengabdian' LIMIT 1"));
									$dana_kampus = rupiah($r[dana_kampus]);
									$dana_acc = rupiah($r[acc_dana]);
									if($r[status]=='' OR $r[status]=='1') {
										$aksi = "<p><a href='/update/ppm_acc/pengabdian/$r[id]/2' class='btn btn-sm btn-success'><i class='fas fa-check'></i> Danai</a></p>
										<p><a href='/update/ppm/pengabdian/$r[id]/3' class='btn btn-sm btn-danger'><i class='fas fa-times'></i> Tolak</a></p>
										";
									} elseif($r[status]=='2') {
										$aksi = '<span class="badge badge-success">Status Didanai</span>';
									} elseif($r[status]=='3') {
										$aksi = '<span class="badge badge-danger">Status Ditolak</span>';
									}

									echo"
										<tr>
											<td>$no</td>
											<td>
											<b>$r[judul_kegiatan]</b><br>Lokasi: $r[lokasi]<br>Usulan Dana: $dana_kampus<br>Dana Disetujui: $dana_acc<br>
									";
									$qfile = mysqli_query($con,"SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.nip_dosen='$r[nip_dosen]' AND fd.id_cat='$r[id]' AND kode.kelompok='file_pengabdian' ");
									while($rfile=mysqli_fetch_array($qfile)) {
											echo"
												<a href='https://universe.umkuningan.ac.id/$rfile[url]' target='_blank'><i class='fa fa-file'></i> $rfile[desc]</a><br>
											";
									}
									$qrevfile = mysqli_query($con,"SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.nip_dosen='$r[nip_dosen]' AND fd.id_cat='$r[id]' AND kode.kelompok='file_rev_pengabdian' ");
									while($rrevfile=mysqli_fetch_array($qrevfile)) {
											echo"
												<a href='https://universe.umkuningan.ac.id/$rrevfile[url]' target='_blank'><i class='fa fa-file'></i> $rrevfile[desc]</a><br>
											";
									}
									echo"
											<p><a href='/ppm/pengabdian/luaran/$r[id]' target='_blank'><i class='fa fa-star-of-life'></i> Luaran</a>
											</td>
											<td><span class='text-xs'>
											$dosen[nik] - 
											$dosen[name_glr] <br>
											<hr>
											$r[nik_anggota1] - 
											$r[name_anggota1] <br>
											$r[aff_anggota1]
											<hr>
											$r[nik_anggota2] - 
											$r[name_anggota2]  <br>
											$r[aff_anggota2]
											</span>
											</td>
											<td>
											<form method='post' action='/update/ppm/pengabdian/$r[id]'>
											<input type='hidden' name='id' value='$r[id]'>
											<p><select class='form-control text-xs' name='nik_reviewer1'>
												<option value=''>-- Pilih --</option>
									";
													$qreviewer1 = mysqli_query($con,"SELECT * FROM dosen WHERE status='A' AND reviewer='1' ORDER BY name");
													while($reviewer1=mysqli_fetch_array($qreviewer1)) {
														if($reviewer1[nik]==$r[nik_reviewer1]) {
															$selected = 'selected';
														} else { $selected = ''; }
														echo"
															<option value='$reviewer1[nik]' $selected>$reviewer1[name_glr]</option>
														";
													}
									echo"
											</select></p>
											<p>
											<select class='form-control text-xs' name='nik_reviewer2'>
												<option value=''>-- Pilih --</option>
									";
													$qreviewer2 = mysqli_query($con,"SELECT * FROM dosen WHERE status='A' AND reviewer='1' ORDER BY name");
													while($reviewer2=mysqli_fetch_array($qreviewer2)) {
														if($reviewer2[nik]==$r[nik_reviewer2]) {
															$selected2 = 'selected';
														} else { $selected2 = ''; }
														echo"
															<option value='$reviewer2[nik]' $selected2>$reviewer2[name_glr]</option>
														";
													}
									echo"
											</select></p>
											
											<p><label>Dana yang disetujui</label><input type='number' name='acc_dana' value='$r[acc_dana]' class='form-control text-sm'></p>

											</td>
											<td>
											<p><input type='submit' value='Simpan' class='btn btn-sm btn-primary'></p>
											<p><a href='/ppm/pengabdian/review/$r[id]' class='btn btn-sm btn-secondary'><i class='fa fa-comment'></i> Review</a>
											$aksi
											</form>
											</td>
										</tr>
									";
									$no++;
								}
							}
						?>
								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
<?php		
		break;
	
		case 'review':
?>
			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Hasil Review</h3>
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-wrap">
								<thead>
									<tr>
										<th>Judul</th>
										<th>Kelompok</th>
										<th>Reviewer</th>
									</tr>
								</thead>
								<tbody>
						<?php

							$q = mysqli_query($con,"SELECT * FROM ajuan_pengabdian AS aj,dosen WHERE dosen.nik=aj.nip_dosen AND aj.id='$folder[4]' ");
							if(mysqli_num_rows($q)<1) {
								echo"<tr><td colspan='6'><p class='text-center text-danger'>Tidak ada ajuan</p></td></tr>";
							}
							else {

								while($r=mysqli_fetch_array($q)) {
									$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$r[nip_dosen]' LIMIT 1"));
									$skema = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_skema]' AND kelompok='skema_pengabdian' LIMIT 1"));
									$peran = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_peran]' AND kelompok='peran_pengabdian' LIMIT 1"));
									$dana_kampus = rupiah($r[dana_kampus]);
									echo"
										<tr>
											<td>
											<b>$r[judul_kegiatan]</b><br>Lokasi: $r[lokasi]<br>Usulan Dana: $dana_kampus<br>
									";
									$qfile = mysqli_query($con,"SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.nip_dosen='$r[nip_dosen]' AND fd.id_cat='$r[id]' AND kode.kelompok='file_pengabdian' ");
									while($rfile=mysqli_fetch_array($qfile)) {
											echo"
												<a href='https://universe.umkuningan.ac.id/$rfile[url]' target='_blank'><i class='fa fa-file'></i> $rfile[desc]</a><br>
											";
									}
									echo"
											</td>
											<td><span class='text-xs'>
											$dosen[nik] - 
											$dosen[name_glr] <br>
											<hr>
											$r[nik_anggota1] - 
											$r[name_anggota1] <br>
											$r[aff_anggota1]
											<hr>
											$r[nik_anggota2] - 
											$r[name_anggota2]  <br>
											$r[aff_anggota2]
											</span>
											</td>
											<td>
											<label>$r[name_reviewer1]</label>
											<p><label>Catatan untuk LPPM</label>
											<textarea class='form-control text-xs' cols='100' rows='5'>$r[advice_reviewer1]</textarea></p>
											<p><label>Catatan untuk Peneliti</label><textarea class='form-control text-xs' cols='100' rows='5'>$r[comment_reviewer1]</textarea></p>
											<p></p>
											<label>$r[name_reviewer2]</label>
											<p><label>Catatan untuk LPPM</label>
											<textarea class='form-control text-xs' cols='100' rows='5'>$r[advice_reviewer2]</textarea></p>
											<p><label>Catatan untuk Peneliti</label><textarea class='form-control text-xs' cols='100' rows='5'>$r[comment_reviewer2]</textarea></p>
											</td>
										</tr>
									";
									$no++;
								}
							}
						?>
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
			</div>

<?php
		break;
		
		case 'luaran':
?>	

			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Luaran</h3>
					                <div class="card-tools">
					                      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah Luaran</button>
					                 </div>			
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-wrap">
								<thead>
									<tr>
										<th>Judul</th>
										<th>Kelompok</th>
										<th>Luaran</th>
									</tr>
								</thead>
								<tbody>
						<?php

							$q = mysqli_query($con,"SELECT * FROM ajuan_pengabdian AS aj,dosen WHERE dosen.nik=aj.nip_dosen AND aj.id='$folder[4]' ");
							if(mysqli_num_rows($q)<1) {
								echo"<tr><td colspan='6'><p class='text-center text-danger'>Tidak ada ajuan</p></td></tr>";
							}
							else {

								while($r=mysqli_fetch_array($q)) {
									$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT nik,name_glr FROM dosen WHERE nik='$r[nip_dosen]' LIMIT 1"));
									$skema = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_skema]' AND kelompok='skema_pengabdian' LIMIT 1"));
									$peran = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_peran]' AND kelompok='peran_pengabdian' LIMIT 1"));
									$dana_kampus = rupiah($r[dana_kampus]);
									echo"
										<tr>
											<td>
											<b>$r[judul_kegiatan]</b><br>Lokasi: $r[lokasi]<br>Usulan Dana: $dana_kampus<br>
									";
									$qfile = mysqli_query($con,"SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.nip_dosen='$r[nip_dosen]' AND fd.id_cat='$r[id]' AND kode.kelompok='file_pengabdian' ");
									while($rfile=mysqli_fetch_array($qfile)) {
											echo"
												<a href='/$rfile[url]' target='_blank'><i class='fa fa-file'></i> $rfile[desc]</a><br>
											";
									}
									echo"
											</td>
											<td><span class='text-xs'>
											$dosen[nik] - 
											$dosen[name_glr] <br>
											<hr>
											$r[nik_anggota1] - 
											$r[name_anggota1] <br>
											$r[aff_anggota1]
											<hr>
											$r[nik_anggota2] - 
											$r[name_anggota2]  <br>
											$r[aff_anggota2]
											</span>
											</td>
											<td>
									";
										$ql = mysqli_query($con,"SELECT * FROM luaran WHERE id_ajuan='$folder[4]' AND ppm='2' ");
										while($l=mysqli_fetch_array($ql)) {
											if($l[status]=='1') {
												$status_luaran = 'Wajib';
											} else {
												$status_luaran = 'Tambahan';
											}
											echo"
												<b>[$status_luaran] $l[nama]</b> - $l[keterangan]
												<hr>
											";
										
										}
									echo"
											</td>
										</tr>
									";
									$no++;
								}
							}
						?>
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
			</div>


<?php
		break;

	}
}