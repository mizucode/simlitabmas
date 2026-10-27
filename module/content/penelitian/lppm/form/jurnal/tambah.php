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
									<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/jurnal">
										<div class="form-group">
											<p>
											<label>Dosen Peneliti / Mahasiswa<span class="text-danger"> *</span></label>
											<select class='form-control select2bs4' name='nip_dosen' required style="width: 100%;">
												<option value=''>-- Cari Nama/NIK/NIM --</option>
												<?php
													try {
														$qdosen = mysqli_query($con,"SELECT nik, name_glr, 'Dosen' AS tipe FROM dosen UNION SELECT nim AS nik, name AS name_glr, 'Mahasiswa' AS tipe FROM mhs ORDER BY name_glr");
														if ($qdosen) {
															while($dosen=mysqli_fetch_array($qdosen)) {
																?>
																	<option value='<?= $dosen['nik'] ?>'><?= $dosen['name_glr'] ?> (<?= $dosen['nik'] ?> - <?= $dosen['tipe'] ?>)</option>
																<?php
															}
														}
													} catch (Exception $e) {
														echo "<!-- SQL Error (Dosen): " . $e->getMessage() . " -->";
													}
												?>
											</select>
											</p>
											<p>
											<label>jenis<span class="text-danger"> *</span></label>
											<select class='form-control select2bs4' name='id_jenis' style="width: 100%;" required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='jurnal' ORDER BY id");
													while($kode1=mysqli_fetch_array($qkode1)) {
														echo"
															<option value='$kode1[value]'>$kode1[desc]</option>
														";
													}
												?>
											</select>
											</p>
											<p>
											<label>Judul Artikel<span class="text-danger"> *</span></label>
											<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Nama Jurnal<span class="text-danger"> *</span></label>
											<input name="nama_jurnal" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>ISSN<span class="text-danger"> *</span></label>
											<input name="issn" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>URL Jurnal</label>
											<input name="url_jurnal" type="text" class="form-control" placeholder="Silakan diisi">
											</p>
											<p>
											<label>Tanggal Terbit (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span class="text-danger"> *</span></label>
											<input name="tgl_terbit" type="date" class="form-control" min="<?php echo $tgl_start; ?>" max="<?php echo $tgl_end; ?>" required>
											</p>
											<p>
											<label>Volume<span class="text-danger"> *</span></label>
											<input name="vol" type="number" class="form-control" maxlength="3" required>
											</p>
											<p>
											<label>Nomor<span class="text-danger"> *</span></label>
											<input name="no" type="number" class="form-control" maxlength="3" required>
											</p>
											<p>
											<label>Halaman</label>
											<input name="halaman" type="text" class="form-control" placeholder="Silakan diisi">
											</p>
											<p>
											<label>Penerbit<span class="text-danger"> *</span></label>
											<input name="penerbit" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>DOI</label>
											<input name="doi" type="text" class="form-control" placeholder="Silakan diisi">
											</p>
											<p>
											<label>Jumlah Sitasi<span class="text-danger"> *</span></label>
											<input name="sitasi" type="number" class="form-control" required>
											</p>
											<p>
											<label>URL Artikel<span class="text-danger"> *</span></label>
											<input name="url_artikel" type="text" class="form-control" placeholder="Silakan diisi" required>
											</p>
											<p>
											<label>Penelitian yang Berkaitan Langsung</label>
											<select class='form-control select2bs4' name='link_lit' style='width: 100%;'>
												<option value=''>-- Pilih --</option>
												<?php
													$qlit= mysqli_query($con,"SELECT * FROM penelitian_penelitian ORDER BY id");
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
											<select class='form-control select2bs4' name='link_abmas' style='width: 100%;'>
												<option value=''>-- Pilih --</option>
												<?php
													$qabmas= mysqli_query($con,"SELECT * FROM pengabdian_pengabdian ORDER BY id");
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
											<select class='form-control select2bs4' name='jenis_file' style='width: 100%;' required>
												<option value=''>-- Pilih --</option>
												<?php
													$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_jurnal' ORDER BY id");
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
