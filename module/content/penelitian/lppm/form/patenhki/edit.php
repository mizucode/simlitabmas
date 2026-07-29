<?php
// Form Edit Paten HKI
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_patenhki WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Paten / HKI</h3>
			<div class="card-tools">
				<a href="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/patenhki">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Judul Paten<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['judul']) ?>" required>
					</p>
					<p>
						<label>Jenis<span class="text-danger"> *</span></label>
						<select class="form-control select2-jenis-edit-patenhki" name="id_jenis" style="width: 100%;" required>
							<option value="">-- Pilih --</option>
							<?php
							$qjenis = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='patenhki' ORDER BY value");
							while ($jenis = mysqli_fetch_array($qjenis)) {
								$sel = ($jenis['value'] == $edit['id_jenis']) ? 'selected' : '';
								?>
								<option value="<?= $jenis['value'] ?>" <?= $sel ?>><?= $jenis['desc'] ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Tanggal Paten<span class="text-danger"> *</span></label>
						<input name="tgl_paten" type="date" class="form-control" value="<?= $edit['tgl_paten'] ?>" required>
					</p>
					<p>
						<label>Nomor Paten<span class="text-danger"> *</span></label>
						<input name="nomor" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['nomor']) ?>" required>
					</p>
					<p>
						<label>Negara/Otoritas Pemberi</label>
						<input name="pemberi" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['pemberi'] ?? '') ?>">
					</p>
					<p>
						<label>Tingkat Kesiapan Teknologi (TKT)</label>
						<select class="form-control select2-tkt-edit-patenhki" name="id_tkt" style="width: 100%;" required>
							<option value="">-- Pilih --</option>
							<?php
							$qtkt = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='tkt' ORDER BY value");
							while ($tkt = mysqli_fetch_array($qtkt)) {
								$sel = ($tkt['value'] == $edit['id_tkt']) ? 'selected' : '';
								?>
								<option value="<?= $tkt['value'] ?>" <?= $sel ?>><?= $tkt['desc'] ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class="form-control" name="link_lit">
							<option value="">-- Pilih --</option>
							<?php
							$qlit = mysqli_query($con, "SELECT * FROM penelitian_penelitian WHERE nip_dosen='{$_SESSION['ses_user']}' ORDER BY id");
							while ($lit = mysqli_fetch_array($qlit)) {
								$sel = ($lit['id'] == ($edit['link_lit'] ?? '')) ? 'selected' : '';
								?>
								<option value="<?= $lit['id'] ?>" <?= $sel ?>><?= $lit['judul_kegiatan'] ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class="form-control" name="link_abmas">
							<option value="">-- Pilih --</option>
							<?php
							$qabmas = mysqli_query($con, "SELECT * FROM pengabdian_pengabdian WHERE nip_dosen='{$_SESSION['ses_user']}' ORDER BY id");
							while ($abmas = mysqli_fetch_array($qabmas)) {
								$sel = ($abmas['id'] == ($edit['link_abmas'] ?? '')) ? 'selected' : '';
								?>
								<option value="<?= $abmas['id'] ?>" <?= $sel ?>><?= $abmas['judul_kegiatan'] ?></option>
								<?php
							}
							?>
						</select>
					</p>
				</div>
				<div class="mt-3 text-right">
					<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
				</div>
			</form>
		</div>
	</div>
	<?php
}
?>
<script>
	$(function () {
		$('.select2-jenis-edit-patenhki, .select2-tkt-edit-patenhki').select2({
			theme: 'bootstrap4'
		});
	});
</script>
