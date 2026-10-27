<?php
// Form Tambah Jabatan
?>
<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Jabatan di Luar Kampus</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/pengabdian/jabatan">
					<div class="form-group">
						<p>
							<label>Dosen<span class="text-danger"> *</span></label>
							<select class="form-control select2-dosen-tambah" name="nip_dosen" style="width: 100%;" required>
								<option value="">-- Pilih Dosen --</option>
								<?php
								$qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name");
								while ($d = mysqli_fetch_array($qdosen)) {
									?>
									<option value="<?= $d['nik'] ?>"><?= $d['nik'] ?> - <?= $d['name_glr'] ?></option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>Jenis Jabatan<span class="text-danger"> *</span></label>
							<select class='form-control' name='id_jenis' required>
								<option value=''>-- Pilih --</option>
								<?php
								$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='jabatan_pub' ORDER BY id");
								while($kode1=mysqli_fetch_array($qkode1)) {
									echo "<option value='$kode1[value]'>$kode1[desc]</option>";
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
									echo "<option value='$kode3[value]'>$kode3[desc]</option>";
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
	$('.select2-dosen-tambah').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#tambah')
	});
});
</script>
