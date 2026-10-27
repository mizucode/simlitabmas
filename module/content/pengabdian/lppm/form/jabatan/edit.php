<?php
// Form Edit Jabatan
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pengabdian_jabatan WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Jabatan di Luar Kampus</h3>
			<div class="card-tools">
				<a href="/pengabdian/<?= @$folder[2] ?: 'jabatan' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/pengabdian/jabatan">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/pengabdian/<?= @$folder[2] ?: 'jabatan' ?>/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Jenis Jabatan<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_jenis' required>
							<option value=''>-- Pilih --</option>
							<?php
							$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='jabatan_pub' ORDER BY id");
							while ($kode1 = mysqli_fetch_array($qkode1)) {
								$sel = ($kode1['value'] == $edit['id_jabatan']) ? 'selected' : '';
								echo "<option value='$kode1[value]' $sel>$kode1[desc]</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Nomor Surat Tugas/SK<span class="text-danger"> *</span></label>
						<input name="no_sk" type="text" class="form-control" value="<?= htmlspecialchars($edit['no_sk']) ?>" required>
					</p>
					<p>
						<label>Terhitung Mulai Tanggal (TMT)<span class="text-danger"> *</span></label>
						<input name="tmt" type="date" class="form-control" value="<?= $edit['tmt'] ?>" required>
					</p>
					<p>
						<label>Lokasi/Instansi<span class="text-danger"> *</span></label>
						<input name="lokasi" type="text" class="form-control" value="<?= htmlspecialchars($edit['lokasi']) ?>" required>
					</p>

				</div>
				<div class="card-footer justify-content-between">
					<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
				</div>
			</form>
		</div>
	</div>
	<?php
}
?>
