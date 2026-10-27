<?php
// Form Edit Non Publikasi
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_non_pub WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data Materi Tidak Dipublikasikan tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fas fa-edit mr-2"></i>Edit Materi Tidak Dipublikasikan</h3>
			<div class="card-tools">
				<a href="/penelitian/non_pub/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary">
					<i class="fas fa-arrow-left mr-1"></i> Kembali
				</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/non_pub">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/non_pub/anggota/<?= $edit['id'] ?>">
				<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
				
				<div class="form-group">
					<p>
						<label>Judul Materi<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul']) ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Nama Seminar/Pertemuan<span class="text-danger"> *</span></label>
						<input name="nama_seminar" type="text" class="form-control" value="<?= htmlspecialchars($edit['nama_seminar'] ?? '') ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Tanggal Pelaksanaan<span class="text-danger"> *</span></label>
						<input name="tgl_pelaksanaan" type="date" class="form-control" value="<?= $edit['tgl_pelaksanaan'] ?>" required>
					</p>
					<p>
						<label>Penyelenggara<span class="text-danger"> *</span></label>
						<input name="penyelenggara" type="text" class="form-control" value="<?= htmlspecialchars($edit['penyelenggara'] ?? '') ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Bahasa</label>
						<input name="bahasa" type="text" class="form-control" value="<?= htmlspecialchars($edit['bahasa'] ?? '') ?>" placeholder="Silakan diisi">
					</p>
					<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class="form-control" name="link_lit">
							<option value="">-- Pilih --</option>
							<?php
							$qlit = mysqli_query($con, "SELECT id, judul_kegiatan FROM penelitian_penelitian ORDER BY id");
							while ($lit = mysqli_fetch_array($qlit)) {
								$sel_lit = ($lit['id'] == $edit['link_lit']) ? 'selected' : '';
								echo "<option value='{$lit['id']}' {$sel_lit}>" . htmlspecialchars($lit['judul_kegiatan']) . "</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class="form-control" name="link_abmas">
							<option value="">-- Pilih --</option>
							<?php
							$qabmas = mysqli_query($con, "SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id");
							while ($abmas = mysqli_fetch_array($qabmas)) {
								$sel_abmas = ($abmas['id'] == $edit['link_abmas']) ? 'selected' : '';
								echo "<option value='{$abmas['id']}' {$sel_abmas}>" . htmlspecialchars($abmas['judul_kegiatan']) . "</option>";
							}
							?>
						</select>
					</p>
				</div>
				
				<div class="text-right mt-4">
					<button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
				</div>
			</form>
		</div>
	</div>
	<?php
}
?>
