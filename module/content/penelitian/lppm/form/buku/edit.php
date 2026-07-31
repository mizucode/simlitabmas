<?php
// Form Edit Buku Referensi
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_buku WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Buku Referensi</h3>
			<div class="card-tools">
				<a href="/penelitian/buku/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/buku">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/buku/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Judul Buku<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['judul']) ?>" required>
					</p>
					<p>
						<label>Tanggal Terbit<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" value="<?= $edit['tgl_terbit'] ?>" required>
					</p>
					<p>
						<label>Jumlah Halaman</label>
						<input name="jmlh_halaman" type="number" class="form-control" max="3000"
							value="<?= htmlspecialchars($edit['jmlh_halaman'] ?? '') ?>">
					</p>
					<p>
						<label>Penerbit<span class="text-danger"> *</span></label>
						<input name="penerbit" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['penerbit'] ?? '') ?>" required>
					</p>
					<p>
						<label>ISBN<span class="text-danger"> *</span></label>
						<input name="isbn" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['isbn'] ?? '') ?>" required>
					</p>
					<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class="form-control select2-lit-edit-buku" name="link_lit" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							$qlit = mysqli_query($con, "SELECT * FROM penelitian_penelitian ORDER BY id DESC");
							while ($lit = mysqli_fetch_array($qlit)) {
								$sel = ($lit['id'] == ($edit['link_lit'] ?? '')) ? 'selected' : '';
								?>
								<option value="<?= $lit['id'] ?>" <?= $sel ?>><?= htmlspecialchars($lit['judul_kegiatan']) ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class="form-control select2-abmas-edit-buku" name="link_abmas" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							$qabmas = mysqli_query($con, "SELECT * FROM pengabdian_pengabdian ORDER BY id DESC");
							while ($abmas = mysqli_fetch_array($qabmas)) {
								$sel = ($abmas['id'] == ($edit['link_abmas'] ?? '')) ? 'selected' : '';
								?>
								<option value="<?= $abmas['id'] ?>" <?= $sel ?>><?= htmlspecialchars($abmas['judul_kegiatan']) ?></option>
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
		$('.select2-lit-edit-buku, .select2-abmas-edit-buku').select2({
			theme: 'bootstrap4'
		});
	});
</script>
