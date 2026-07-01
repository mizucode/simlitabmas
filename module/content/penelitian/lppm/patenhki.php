<?php 

ob_start();	

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}
else {
	switch(@$folder[2]) {
		default :
			if(empty($folder['3'])) {
				$ta = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM ts WHERE active='Y' "));
				$tgl_start = '0000-00-00';
				$tgl_end = '2222-22-22';
			}
			else {
			$sta = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM ts WHERE kode='$folder[3]' "));
				$tgl_start = $sta[tgl_start];
				$tgl_end = $sta[tgl_end];
			}

?>

			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='ta'>
							<option value='/penelitian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>
						<?php
		
						$qk = mysqli_query($con,"SELECT thn,semester,kode,tgl_start,tgl_end FROM ts WHERE active!='u' ORDER BY kode DESC");
						while($k = mysqli_fetch_array($qk)) {
							if($k[kode]==$folder[3]) { $selected = 'selected'; } else {$selected = '';}
							echo "
								<option value='/penelitian/$folder[2]/$k[kode]' $selected>$k[thn] $k[semester] ($k[tgl_start] s.d $k[tgl_end])</option>
							";
						}
						?>
						
							</select>
						</div>
					</div>
				</div>
			</div>
<?php
	if(!empty($folder[3])) {
?>
<!--
			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='prodi'>
							<option value='/penelitian/<?php echo $folder[2]; ?>'>-- Prodi --</option>
						<?php
		
						$qp = mysqli_query($con,"SELECT * FROM prodi ORDER BY kode DESC");
						while($p = mysqli_fetch_array($qp)) {
							if($p[kode]==$folder[4]) { $selected = 'selected'; } else {$selected = '';}
							echo "
								<option value='/penelitian/$folder[2]/$folder[3]/$p[kode]' $selected>$p[nama] ($p[singkatan])</option>
							";
						}
						?>
						
							</select>
						</div>
					</div>
				</div>
			</div>
!-->
<?php
	}
?>
			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Paten dan Hak Kekayaan Intelektual</h3>
					                <div class="card-tools">
					                      <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</button>
					                 </div>														
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-nowrap text-xs">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Dosen</th>
										<th>Jenis</th>
										<th>Nama</th>
										<th>Nomor</th>
										<th>TKT</th>
										<th>Tanggal</th>
										<th>File</th>
									</tr>
								</thead>
								<tbody>
						<?php
							$no=1;
							$q = mysqli_query($con,"SELECT * FROM penelitian_patenhki,dosen WHERE dosen.nik=penelitian_patenhki.nip_dosen AND tgl_paten>='$tgl_start' AND tgl_paten<='$tgl_end' ORDER BY penelitian_patenhki.id_jenis,penelitian_patenhki.judul ");
							if(mysqli_num_rows($q)<1) {
								echo"<tr><td colspan='7'><p class='text-center text-danger'>Tidak ada data pada semester/TA yang dipilih</p></td></tr>";
							}
							else {
								while($r=mysqli_fetch_array($q)) {
									$id_jenis = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_jenis]' AND kelompok='patenhki' LIMIT 1"));
									$id_tkt = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kode WHERE value='$r[id_tkt]' AND kelompok='tkt' LIMIT 1"));
									echo"
										<tr>
											<td>$no</td>
											<td>$r[name_glr]</td>
											<td>$id_jenis[desc]</td>
											<td>$r[judul]</td>
											<td>$r[nomor]</td>
											<td>$id_tkt[desc]</td>
											<td>$r[tgl_paten]</td>
											<td>
									";
									$qfile = mysqli_query($con,"SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.id_cat='$r[id]' AND kode.kelompok='file_patenhki' ");
									while($rfile=mysqli_fetch_array($qfile)) {
											echo"
												<a href='/$rfile[url]' target='_blank'><i class='fa fa-file'></i> $rfile[desc]</a><br>
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
					
					<div class="modal fade" id="tambah">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Tambah</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/patenhki">
										<div class="form-group">
											<p>
											<label>Jenis<span class="text-danger"> *</span></label>
											<select class='form-control' name='id_jenis' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qjenis = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='patenhki' ORDER BY value");
													while($jenis=mysqli_fetch_array($qjenis)) {
														echo"
															<option value='$jenis[value]'>$jenis[desc]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Judul Paten<span class="text-danger"> *</span></label>
											<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Tanggal Paten (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span class="text-danger"> *</span></label>
											<input name="tgl_paten" type="date" class="form-control" min="<?php echo $tgl_start; ?>" max="<?php echo $tgl_end; ?>" required>
											</p>
											<p>
											<label>Nomor Paten<span class="text-danger"> *</span></label>
											<input name="nomor" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Negara/Otoritas Pemberi</label>
											<input name="pemberi" type="text" class="form-control" placeholder="Silakan diisi">
											</p>
											<p>
											<label>Tingkat Kesiapan Teknologi (TKT)</label>
											<select class='form-control' name='id_tkt' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qtkt= mysqli_query($con,"SELECT * FROM kode WHERE kelompok='tkt' ORDER BY value");
													while($tkt=mysqli_fetch_array($qtkt)) {
														echo"
															<option value='$tkt[value]'>$tkt[desc]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Penelitian yang Berkaitan Langsung</label>
											<select class='form-control' name='link_lit'>
												<option value=''>-- Pilih --</option>
												<?php
													$qlit= mysqli_query($con,"SELECT * FROM penelitian_penelitian WHERE nip_dosen='$_SESSION[ses_user]' ORDER BY id");
													while($lit=mysqli_fetch_array($qlit)) {
														echo"
															<option value='$lit[id]'>$lit[judul_kegiatan]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Pengabdian yang Berkaitan Langsung</label>
											<select class='form-control' name='link_abmas'>
												<option value=''>-- Pilih --</option>
												<?php
													$qabmas= mysqli_query($con,"SELECT * FROM pengabdian_pengabdian WHERE nip_dosen='$_SESSION[ses_user]' ORDER BY id");
													while($abmas=mysqli_fetch_array($qabmas)) {
														echo"
															<option value='$abmas[id]'>$abmas[judul_kegiatan]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Jenis File<span class="text-danger"> *</span></label>
											<select class='form-control' name='jenis_file' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_patenhki' ORDER BY id");
													while($kode3=mysqli_fetch_array($qkode3)) {
														echo"
															<option value='$kode3[value]'>$kode3[desc]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>File<span class="text-danger"> *</span></label>
											<input name="file" type="file" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
											</p>

										</div>
								</div>
								<div class="modal-footer justify-content-between">
									<input type="submit" class="btn btn-primary" value="Tambah">
								</div>
								</form>
							</div>
						</div>
			  	        </div>


				</div>
			</div>
<?php		
		break;
	
		case 'edit':

		break;

	}
}