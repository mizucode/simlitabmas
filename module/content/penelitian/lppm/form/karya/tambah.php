<?php
// Form Tambah Karya Tanpa HKI
?>
<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Karya Tanpa HKI</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/karya">
					<div class="form-group">
						<p>
						<label>Dosen Peneliti<span class="text-danger"> *</span></label>
						<select class='form-control select2bs4' name='nip_dosen' required style="width: 100%;">
							<option value=''>-- Pilih Dosen --</option>
							<?php
								try {
									$qdosen = mysqli_query($con,"SELECT nik, name_glr FROM dosen ORDER BY name");
									if ($qdosen) {
										while($dosen=mysqli_fetch_array($qdosen)) {
											?>
												<option value='<?= $dosen['nik'] ?>'><?= $dosen['nik'] ?> - <?= $dosen['name_glr'] ?></option>
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
						<label>Jenis<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_jenis' required>
							<option value=''>-- Pilih --</option>
							<?php
								$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='karya' ORDER BY id");
								while($kode1=mysqli_fetch_array($qkode1)) {
									?>
										<option value='<?= $kode1['value'] ?>'><?= $kode1['desc'] ?></option>
									<?php
								}
							?>
						</select>
						</p>
						<p>
						<label>Judul Karya<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Tanggal terbit (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" min="<?php echo $tgl_start; ?>" max="<?php echo $tgl_end; ?>" required>
						</p>
						<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class='form-control select2bs4' name='link_lit' style="width: 100%;">
							<option value=''>-- Pilih --</option>
							<?php
								try {
									$qlit = mysqli_query($con, "SELECT id, judul_kegiatan FROM penelitian_penelitian WHERE status='2' ORDER BY id DESC LIMIT 500");
									if ($qlit) {
										while ($lit = mysqli_fetch_array($qlit)) {
											?>
											<option value="<?= $lit['id'] ?>"><?= $lit['judul_kegiatan'] ?></option>
											<?php
										}
									}
								} catch (Exception $e) {
									echo "<!-- SQL Error (Lit): " . $e->getMessage() . " -->";
								}
							?>
						</select>
						</p>

						<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class='form-control select2bs4' name='link_abmas' style="width: 100%;">
							<option value=''>-- Pilih --</option>
							<?php
								try {
									$qabmas = mysqli_query($con, "SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id DESC LIMIT 500");
									if ($qabmas) {
										while ($abmas = mysqli_fetch_array($qabmas)) {
											?>
											<option value="<?= $abmas['id'] ?>"><?= $abmas['judul_kegiatan'] ?></option>
											<?php
										}
									}
								} catch (Exception $e) {
									echo "<!-- SQL Error (Abmas): " . $e->getMessage() . " -->";
								}
							?>
						</select>
						</p>
						<p>
						<label>Jenis File<span class="text-danger"> *</span></label>
						<select class='form-control' name='jenis_file' required>
							<option value=''>-- Pilih --</option>
							<?php
								$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_karya' ORDER BY id");
								while($kode3=mysqli_fetch_array($qkode3)) {
									?>
										<option value='<?= $kode3['value'] ?>'><?= $kode3['desc'] ?></option>
									<?php
								}
							?>
						</select>
						</p>
						<p>
						<label>File<span class="text-danger"> *</span></label>
						<input name="file" type="file" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
						</p>
						
						<label>Pernyataan, <i>Bismillahirrahmanirrahim</i><span class="text-danger"> *</span></label>
						<p>
							<input type='checkbox' name='p1' value='Y' required> Saya mengisi form dengan secara cermat dan jujur<br>
							<input type='checkbox' name='p2' value='Y' required> Saya bertanggungjawab secara hukum dan moral atas isian form<br>
							<input type='checkbox' name='p3' value='Y' required> Saya memilih dan mengunggah berkas yang benar<br>
							<input type='checkbox' name='p4' value='Y' required> Saya mengunggah berkas dengan kualitas yang baik<br>
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
