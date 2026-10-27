<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Tulisan di Media</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/tulisan">
					<div class="form-group">
						<p>
						<label>Dosen / Mahasiswa<span class="text-danger"> *</span></label>
						<select class="form-control select2-dosen" name="nip_dosen" style="width: 100%;" required>
							<option value="">-- Cari Nama/NIK/NIM --</option>
							<?php
							$qdosen = mysqli_query($con, "SELECT nik, name_glr, 'Dosen' AS tipe FROM dosen UNION SELECT nim AS nik, name AS name_glr, 'Mahasiswa' AS tipe FROM mhs ORDER BY name_glr");
							while ($d = mysqli_fetch_array($qdosen)) {
								echo "<option value='{$d['nik']}'>{$d['name_glr']} ({$d['nik']} - {$d['tipe']})</option>";
							}
							?>
						</select>
						</p>
						<p>
						<label>Jenis<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_jenis' required>
							<option value=''>-- Pilih --</option>
							<?php
								$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='tulisan' ORDER BY id");
								while($kode1=mysqli_fetch_array($qkode1)) {
									echo "
										<option value='$kode1[value]'>$kode1[desc]</option>
									";
								}
							?>
						</select>
						</p>
						<p>
						<label>Judul<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Nama Media<span class="text-danger"> *</span></label>
						<input name="media" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Tanggal Terbit (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" min="<?php echo $tgl_start; ?>" max="<?php echo $tgl_end; ?>" required>
						</p>
						<p>
						<label>URL/Link Artikel</label>
						<input name="link" type="text" class="form-control" placeholder="https://...">
						</p>
						<p>
						<label>Tingkat<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_level' required>
							<option value=''>-- Pilih --</option>
							<?php
								$qkode2 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='tingkat' ORDER BY id");
								while($kode2=mysqli_fetch_array($qkode2)) {
									echo "
										<option value='$kode2[value]'>$kode2[desc]</option>
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
									echo "
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
									echo "
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
								$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_tulisan' ORDER BY id");
								while($kode3=mysqli_fetch_array($qkode3)) {
									echo "
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
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
				<input type="submit" class="btn btn-primary" value="Tambah">
			</div>
			</form>
		</div>
	</div>
</div>
<script>
  $(function () {
    if ($.fn.select2) {
      $('.select2-dosen').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#tambah')
      });
    }
  });
</script>
