<?php
// Form Tambah Pembicara
?>
<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Pembicara / Narasumber</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/pengabdian/pembicara">
					<div class="form-group">
						<p>
							<label>Dosen Pembicara<span class="text-danger"> *</span></label>
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
							<label>Jenis Kegiatan<span class="text-danger"> *</span></label>
							<select class='form-control' name='id_kategori_kegiatan' required>
								<option value=''>-- Pilih --</option>
								<?php
								$qkode1 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='frek_kegiatan' ORDER BY id");
								while($kode1=mysqli_fetch_array($qkode1)) {
									echo "<option value='$kode1[value]'>$kode1[desc]</option>";
								}
								?>
							</select>
						</p>
						<p>
							<label>Kategori Pembicara<span class="text-danger"> *</span></label>
							<select class='form-control' name='id_kategori_pembicara' required>
								<option value=''>-- Pilih --</option>
								<?php
								$qkode2 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='pembicara' ORDER BY id");
								while($kode2=mysqli_fetch_array($qkode2)) {
									echo "<option value='$kode2[value]'>$kode2[desc]</option>";
								}
								?>
							</select>
						</p>
						<p>
							<label>Judul Makalah/Materi<span class="text-danger"> *</span></label>
							<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Nama Pertemuan Ilmiah/Kegiatan<span class="text-danger"> *</span></label>
							<input name="nama_pertemuan" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Penyelenggara<span class="text-danger"> *</span></label>
							<input name="penyelenggara" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Tingkat<span class="text-danger"> *</span></label>
							<select class='form-control' name='id_tingkat' required>
								<option value=''>-- Pilih --</option>
								<?php
								$qkode3 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='tingkat' ORDER BY id");
								while($kode3=mysqli_fetch_array($qkode3)) {
									echo "<option value='$kode3[value]'>$kode3[desc]</option>";
								}
								?>
							</select>
						</p>
						<p>
							<label>Tanggal Pelaksanaan (<?= $tgl_start ?> - <?= $tgl_end ?>)<span class="text-danger"> *</span></label>
							<input name="tgl_pelaksanaan" type="date" class="form-control" min="<?= $tgl_start ?>" max="<?= $tgl_end ?>" required>
						</p>
						<p>
							<label>Bahasa</label>
							<input name="bahasa" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Nomor Surat Tugas/SK</label>
							<input name="no_sk" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Tanggal SK</label>
							<input name="tgl_sk" type="date" class="form-control">
						</p>
						<p>
							<label>Link Penelitian</label>
							<input name="link_lit" type="url" class="form-control" placeholder="https://...">
						</p>
						<p>
							<label>Link Pengabdian</label>
							<input name="link_abmas" type="url" class="form-control" placeholder="https://...">
						</p>
						<p>
							<label>Jenis File<span class="text-danger"> *</span></label>
							<select class='form-control' name='jenis_file' required>
								<option value=''>-- Pilih --</option>
								<?php
								$qkode4 = mysqli_query($con,"SELECT * FROM kode WHERE kelompok='file_pembicara' ORDER BY id");
								while($kode4=mysqli_fetch_array($qkode4)) {
									echo "<option value='$kode4[value]'>$kode4[desc]</option>";
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
