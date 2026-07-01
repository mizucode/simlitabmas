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
							<option value='/penelitian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>
						<?php
		
						$qk = mysqli_query($con,"SELECT thn,semester,kode FROM ta WHERE active!='u' ORDER BY kode DESC");
						while($k = mysqli_fetch_array($qk)) {
							if($k[kode]==$folder[3]) { $selected = 'selected'; } else {$selected = '';}
							echo "
								<option value='/penelitian/$folder[2]/$k[kode]' $selected>$k[thn] $k[semester]</option>
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
							<h3 class="card-title">Penerjemahan Buku</h3>
					                <div class="card-tools">
					                      <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</button>
					                 </div>														
						</div>
						<div class="card-body table-responsive p-0">
							<table class="table table-hover table-striped text-nowrap">
								<thead>
									<tr>
										<th>No</th>
										<th>Judul</th>
										<th>Judul Asli</th>
										<th>Penerbit</th>
										<th>ISBN</th>
										<th>Tanggal Terbit</th>
										<th>File</th>
									</tr>
								</thead
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
											<label>Jenis<span class="text-danger"> *</span></label>
											<select class='form-control' name='id_jenis' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='jenis_buku' ORDER BY id");
													while($kode1=mysqli_fetch_array($qkode1)) {
														echo"
															<option value='$kode1[value]'>$kode1[desc]</option>
														";
													}
												?>
											</select>
											</p>

											<p>
											<label>Judul Buku Hasil Terjemahan<span class="text-danger"> *</span></label>
											<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Judul Buku Asli<span class="text-danger"> *</span></label>
											<input name="judul_asli" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Tanggal Terbit<span class="text-danger"> *</span></label>
											<input name="tgl_terbit" type="date" class="form-control" required>
											</p>
											<p>
											<label>Jumlah Halaman</label>
											<input name="jmlh_halaman" type="number" max="3000" class="form-control">
											</p>
											<p>
											<label>Penerbit<span class="text-danger"> *</span></label>
											<input name="penerbit" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>ISBN<span class="text-danger"> *</span></label>
											<input name="isbn" type="text" class="form-control" placeholder="Silakan diisi" required>
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
													$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_penerjemahan' ORDER BY id");
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