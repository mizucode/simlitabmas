<?php 

ob_start();	

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}
else {
	switch(@$folder[2]) {
		default :
?>

			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='ta'>
							<option value='/pengabdian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>
						<?php
		
						$qk = mysqli_query($con,"SELECT thn,semester,kode FROM ts WHERE active!='u' ORDER BY kode DESC");
						while($k = mysqli_fetch_array($qk)) {
							if($k[kode]==$folder[3]) { $selected = 'selected'; } else {$selected = '';}
							echo "
								<option value='/pengabdian/$folder[2]/$k[kode]' $selected>$k[thn] $k[semester]</option>
							";
						}
						?>
						
							</select>
						<?php		
						if(empty($folder['3'])) {
							$ta = mysqli_fetch_array(mysqli_query($con,"SELECT kode FROM ta WHERE active='Y' "));
							$folder['3'] = $ta['kode'];
						}
		
						?>
						</div>
					</div>
				</div>
			</div>

			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Jabatan di Luar Kampus</h3>
					                <div class="card-tools">
					                      <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</button>
					                 </div>														
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-nowrap">
								<thead>
									<tr>
										<th>No</th>
										<th>Kategori Jabatan</th>
										<th>Instansi</th>
										<th>Terhitung Mulai</th>
										<th>File</th>
									</tr>
								</thead>
								<tbody>
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
									<form role="form">
										<div class="form-group">
											<p>
											<label>Jenis Jabatan<span class="text-danger"> *</span></label>
											<select class='form-control' name='id_jenis' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='jabatan_pub' ORDER BY id");
													while($kode1=mysqli_fetch_array($qkode1)) {
														echo"
															<option value='$kode1[value]'>$kode1[desc]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Nomor Surat Tugas/SK<span class="text-danger"> *</span></label>
											<input name="no_sk" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Terhitung Mulai Tanggal (TMT)<span class="text-danger"> *</span></label>
											<input name="tmt" type="date" class="form-control" required>
											</p>
											<p>
											<label>Lokasi/Instansi<span class="text-danger"> *</span></label>
											<input name="lokasi" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>

											<p>
											<label>Jenis File<span class="text-danger"> *</span></label>
											<select class='form-control' name='jenis_file' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_jabatan' ORDER BY id");
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