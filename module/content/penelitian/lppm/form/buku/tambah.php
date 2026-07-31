<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Buku Referensi</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/buku">
					<div class="form-group">
						<p>
						<label>Dosen Peneliti<span class="text-danger"> *</span></label>
						<select class='form-control select2-link' name='nip_dosen' required style="width: 100%;">
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
						<label>Judul Buku<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Tanggal Terbit (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" min="<?php echo $tgl_start; ?>" max="<?php echo $tgl_end; ?>" required>
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
						<select class='form-control select2-link' style="width: 100%;" name='link_lit'>
							<option value=''>-- Pilih --</option>
							<?php
								$qlit= mysqli_query($con,"SELECT * FROM penelitian_penelitian ORDER BY id DESC");
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
						<select class='form-control select2-link' style="width: 100%;" name='link_abmas'>
							<option value=''>-- Pilih --</option>
							<?php
								$qabmas= mysqli_query($con,"SELECT * FROM pengabdian_pengabdian ORDER BY id DESC");
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
						<select class='form-control select2-link' style="width: 100%;" name='jenis_file' required>
							<option value=''>-- Pilih --</option>
							<?php
								$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_buku' ORDER BY id");
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
<script>
    $(document).ready(function() {
        $('.select2-link').select2({
            theme: 'bootstrap4',
            placeholder: "-- Pilih --",
            allowClear: true
        });
    });
</script>
