<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Materi Tidak Dipublikasikan</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/non_pub">
					<div class="form-group">
						<p>
						<label>Dosen<span class="text-danger"> *</span></label>
						<select class="form-control select2-dosen" name="nip_dosen" style="width: 100%;" required>
							<option value="">-- Pilih Dosen --</option>
							<?php
							$qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name");
							while ($d = mysqli_fetch_array($qdosen)) {
								echo "<option value='{$d['nik']}'>{$d['nik']} - {$d['name_glr']}</option>";
							}
							?>
						</select>
						</p>
						<p>
						<label>Judul Materi<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Nama Seminar/Pertemuan<span class="text-danger"> *</span></label>
						<input name="nama_seminar" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Tanggal Pelaksanaan<span class="text-danger"> *</span></label>
						<input name="tgl_pelaksanaan" type="date" class="form-control" required>
						</p>
						<p>
						<label>Penyelenggara<span class="text-danger"> *</span></label>
						<input name="penyelenggara" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
						<label>Bahasa</label>
						<input name="bahasa" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class='form-control' name='link_lit'>
							<option value=''>-- Pilih --</option>
							<?php
							$qlit = mysqli_query($con, "SELECT id, judul_kegiatan FROM penelitian_penelitian ORDER BY id");
							while ($lit = mysqli_fetch_array($qlit)) {
								echo "<option value='{$lit['id']}'>" . htmlspecialchars($lit['judul_kegiatan']) . "</option>";
							}
							?>
						</select>
						</p>
						<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class='form-control' name='link_abmas'>
							<option value=''>-- Pilih --</option>
							<?php
							$qabmas = mysqli_query($con, "SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id");
							while ($abmas = mysqli_fetch_array($qabmas)) {
								echo "<option value='{$abmas['id']}'>" . htmlspecialchars($abmas['judul_kegiatan']) . "</option>";
							}
							?>
						</select>
						</p>
						<p>
						<label>Jenis File<span class="text-danger"> *</span></label>
						<select class='form-control' name='jenis_file' required>
							<option value=''>-- Pilih --</option>
							<?php
							$qkode3 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_non_pub' ORDER BY id");
							while ($kode3 = mysqli_fetch_array($qkode3)) {
								echo "<option value='{$kode3['value']}'>{$kode3['desc']}</option>";
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
  $(function () {
    if ($.fn.select2) {
      $('.select2-dosen').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#tambah')
      });
    }
  });
</script>
