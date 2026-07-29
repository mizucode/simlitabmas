<?php
// Form Edit Karya Tanpa HKI
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_karya WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Karya Tanpa HKI</h3>
			<div class="card-tools">
				<a href="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/karya">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Judul Karya<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul']) ?>" required>
					</p>
					<p>
						<label>Jenis<span class="text-danger"> *</span></label>
						<select class="form-control select2bs4" name="id_jenis" style="width: 100%;" required>
							<option value="">-- Pilih --</option>
							<?php
							$qjenis = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='karya' ORDER BY value");
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
						<label>Tanggal Terbit<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" value="<?= $edit['tgl_terbit'] ?>" required>
					</p>
					<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class="form-control select2-link" name="link_lit" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							try {
								$qlit = mysqli_query($con, "SELECT id, judul_kegiatan FROM penelitian_penelitian WHERE status='2' ORDER BY id DESC LIMIT 500");
								if ($qlit) {
									while ($lit = mysqli_fetch_array($qlit)) {
										$link_lit_val = isset($edit['link_lit']) ? $edit['link_lit'] : '';
										$sel = ($lit['id'] == $link_lit_val) ? 'selected' : '';
										?>
										<option value="<?= $lit['id'] ?>" <?= $sel ?>><?= $lit['judul_kegiatan'] ?></option>
										<?php
									}
								}
							} catch (Exception $e) {
								echo "<!-- Error: " . $e->getMessage() . " -->";
							}
							?>
						</select>
					</p>
					<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class="form-control select2-link" name="link_abmas" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							try {
								$qabmas = mysqli_query($con, "SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id DESC LIMIT 500");
								if ($qabmas) {
									while ($abmas = mysqli_fetch_array($qabmas)) {
										$link_abmas_val = isset($edit['link_abmas']) ? $edit['link_abmas'] : '';
										$sel = ($abmas['id'] == $link_abmas_val) ? 'selected' : '';
										?>
										<option value="<?= $abmas['id'] ?>" <?= $sel ?>>
											<?= $abmas['judul_kegiatan'] ?>
										</option>
										<?php
									}
								}
							} catch (Exception $e) {
								echo "<!-- Error: " . $e->getMessage() . " -->";
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
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		});
	});
</script>