<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Penyuntingan</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/penyuntingan">
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
						<label>Jenis Penyuntingan<span class="text-danger"> *</span></label>
						<select class="form-control select2-jenis" name="id_jenis" style="width: 100%;" required>
							<option value="">-- Pilih Jenis Penyuntingan --</option>
							<?php
							$q_jenis = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='jenis_buku' ORDER BY id");
							while ($j = mysqli_fetch_array($q_jenis)) {
								echo "<option value='{$j['value']}'>{$j['desc']}</option>";
							}
							?>
						</select>
						</p>
						<p>
						<label>Judul Penyuntingan<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
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
						<label>ISBN</label>
						<input name="isbn" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class='form-control' name='link_lit'>
							<option value=''>-- Pilih --</option>
							<?php
								$qlit= mysqli_query($con,"SELECT id, judul_kegiatan FROM penelitian_penelitian ORDER BY id");
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
								$qabmas= mysqli_query($con,"SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id");
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
								$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_penyuntingan' ORDER BY id");
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
  $(function () {
    if ($.fn.select2) {
      $('.select2-dosen').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#tambah')
      });
    }
  });
</script>
