<?php
// Form Edit Prosiding
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_prosiding WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Artikel Prosiding</h3>
			<div class="card-tools">
				<a href="/penelitian/prosiding/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/prosiding">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/prosiding/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					
					<p>
						<label>Judul Naskah<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['judul']) ?>" required>
					</p>
					<p>
						<label>Jenis Prosiding<span class="text-danger"> *</span></label>
						<select class="form-control select2-jenis-edit-prosiding" name="id_jenis" style="width: 100%;" required>
							<option value="">-- Pilih --</option>
							<?php
							$qjenis = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='jenis_prosiding' ORDER BY id");
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
						<label>Nama Seminar<span class="text-danger"> *</span></label>
						<input name="nama_seminar" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['nama_seminar'] ?? '') ?>" required>
					</p>
					<p>
						<label>Tanggal Terbit<span class="text-danger"> *</span></label>
						<input name="tgl_terbit" type="date" class="form-control"
							value="<?= $edit['tgl_terbit'] ?>" required>
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
						<label>ISSN</label>
						<input name="issn" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['issn'] ?? '') ?>">
					</p>
					<p>
						<label>DOI</label>
						<input name="doi" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['doi'] ?? '') ?>">
					</p>
					<p>
						<label>Bahasa</label>
						<input name="bahasa" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['bahasa'] ?? '') ?>">
					</p>
					<p>
						<label>Jumlah Sitasi<span class="text-danger"> *</span></label>
						<input name="sitasi" type="number" class="form-control"
							value="<?= $edit['sitasi'] ?>" required>
					</p>
					<p>
						<label>URL Artikel</label>
						<input name="link" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['link'] ?? '') ?>">
					</p>
					<p>
						<label>Penelitian yang Berkaitan Langsung</label>
						<select class="form-control select2-lit-edit-prosiding" name="link_lit" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							$qlit = mysqli_query($con, "SELECT id, judul_kegiatan FROM penelitian_penelitian ORDER BY id");
							while ($lit = mysqli_fetch_array($qlit)) {
								$sel_lit = ($lit['id'] == $edit['link_lit']) ? 'selected' : '';
								?>
								<option value="<?= $lit['id'] ?>" <?= $sel_lit ?>><?= htmlspecialchars($lit['judul_kegiatan']) ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Pengabdian yang Berkaitan Langsung</label>
						<select class="form-control select2-abmas-edit-prosiding" name="link_abmas" style="width: 100%;">
							<option value="">-- Pilih --</option>
							<?php
							$qabmas = mysqli_query($con, "SELECT id, judul_kegiatan FROM pengabdian_pengabdian ORDER BY id");
							while ($abmas = mysqli_fetch_array($qabmas)) {
								$sel_abmas = ($abmas['id'] == $edit['link_abmas']) ? 'selected' : '';
								?>
								<option value="<?= $abmas['id'] ?>" <?= $sel_abmas ?>><?= htmlspecialchars($abmas['judul_kegiatan']) ?></option>
								<?php
							}
							?>
						</select>
					</p>
				</div>
				
				<div class="form-group mt-4">
					<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		$(function () {
			$('.select2-jenis-edit-prosiding').select2({ theme: 'bootstrap4' });
			$('.select2-lit-edit-prosiding').select2({ theme: 'bootstrap4' });
			$('.select2-abmas-edit-prosiding').select2({ theme: 'bootstrap4' });
		});
	</script>
	<?php
}
?>
