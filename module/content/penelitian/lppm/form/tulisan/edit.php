<?php
// Form Edit Tulisan di Media
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_tulisan WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data Tulisan di Media tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title"><i class="fas fa-edit mr-2"></i>Edit Tulisan di Media</h3>
			<div class="card-tools">
				<a href="/penelitian/tulisan/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary">
					<i class="fas fa-arrow-left mr-1"></i> Kembali
				</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/tulisan">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/tulisan/anggota/<?= $edit['id'] ?>">
				<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
				
				<div class="form-group">
					<p>
						<label>Jenis Tulisan<span class="text-danger"> *</span></label>
						<select class="form-control" name="id_jenis" required>
							<option value="">-- Pilih --</option>
							<?php
							$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='tulisan' ORDER BY id");
							while ($kode1 = mysqli_fetch_array($qkode1)) {
								$sel = ($kode1['value'] == $edit['id_jenis']) ? 'selected' : '';
								echo "<option value='{$kode1['value']}' {$sel}>{$kode1['desc']}</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Judul Tulisan<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul']) ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Nama Media<span class="text-danger"> *</span></label>
						<input name="media" type="text" class="form-control" value="<?= htmlspecialchars($edit['media'] ?? '') ?>" placeholder="Silakan diisi" required>
					</p>
					<p>
						<label>Tanggal Terbit<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control" value="<?= $edit['tgl_terbit'] ?>" required>
					</p>
					<p>
						<label>URL / Link Artikel</label>
						<input name="link" type="text" class="form-control" value="<?= htmlspecialchars($edit['link'] ?? '') ?>" placeholder="https://...">
					</p>
					<p>
						<label>Tingkat<span class="text-danger"> *</span></label>
						<select class="form-control" name="id_level" required>
							<option value="">-- Pilih --</option>
							<?php
							$qkode2 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='tingkat' ORDER BY id");
							while ($kode2 = mysqli_fetch_array($qkode2)) {
								$sel2 = ($kode2['value'] == $edit['id_level']) ? 'selected' : '';
								echo "<option value='{$kode2['value']}' {$sel2}>{$kode2['desc']}</option>";
							}
							?>
						</select>
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
