<?php
// Form Edit Penerjemahan Buku
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_penerjemahan WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data Penerjemahan tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fas fa-edit mr-2"></i>Edit Penerjemahan Buku</h3>
			<div class="card-tools">
				<a href="/penelitian/penerjemahan/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary">
					<i class="fas fa-arrow-left mr-1"></i> Kembali
				</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/penerjemahan">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/penerjemahan/anggota/<?= $edit['id'] ?>">
				<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
				
				<div class="form-group">
					<p>
						<label>Judul Terjemahan<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul']) ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Judul Asli<span class="text-danger"> *</span></label>
						<input name="judul_asli" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul_asli'] ?? '') ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Tanggal Terbit<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" value="<?= $edit['tgl_terbit'] ?>" required>
					</p>
					<p>
						<label>Jumlah Halaman</label>
						<input name="jmlh_halaman" type="number" max="3000" class="form-control" value="<?= htmlspecialchars($edit['jmlh_halaman'] ?? '') ?>">
					</p>
					<p>
						<label>Penerbit<span class="text-danger"> *</span></label>
						<input name="penerbit" type="text" class="form-control" value="<?= htmlspecialchars($edit['penerbit'] ?? '') ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>ISBN</label>
						<input name="isbn" type="text" class="form-control" value="<?= htmlspecialchars($edit['isbn'] ?? '') ?>" placeholder="Silakan diisi">
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
