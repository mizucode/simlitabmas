<?php
// Form Edit Pembicara
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pengabdian_pembicara WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Pembicara / Narasumber</h3>
			<div class="card-tools">
				<a href="/pengabdian/<?= @$folder[2] ?: 'pembicara' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/pengabdian/pembicara">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/pengabdian/<?= @$folder[2] ?: 'pembicara' ?>/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Jenis Kegiatan<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_kategori_kegiatan' required>
							<option value=''>-- Pilih --</option>
							<?php
							$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='frek_kegiatan' ORDER BY id");
							while ($kode1 = mysqli_fetch_array($qkode1)) {
								$sel = ($kode1['value'] == $edit['id_kategori_kegiatan']) ? 'selected' : '';
								echo "<option value='$kode1[value]' $sel>$kode1[desc]</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Kategori Pembicara<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_kategori_pembicara' required>
							<option value=''>-- Pilih --</option>
							<?php
							$qkode2 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='pembicara' ORDER BY id");
							while ($kode2 = mysqli_fetch_array($qkode2)) {
								$sel = ($kode2['value'] == $edit['id_kategori_pembicara']) ? 'selected' : '';
								echo "<option value='$kode2[value]' $sel>$kode2[desc]</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Judul Makalah/Materi<span class="text-danger"> *</span></label>
						<input name="judul" type="text" class="form-control" value="<?= htmlspecialchars($edit['judul']) ?>" required>
					</p>
					<p>
						<label>Nama Pertemuan Ilmiah/Kegiatan<span class="text-danger"> *</span></label>
						<input name="nama_pertemuan" type="text" class="form-control" value="<?= htmlspecialchars($edit['nama_pertemuan']) ?>" required>
					</p>
					<p>
						<label>Penyelenggara<span class="text-danger"> *</span></label>
						<input name="penyelenggara" type="text" class="form-control" value="<?= htmlspecialchars($edit['penyelenggara']) ?>" required>
					</p>
					<p>
						<label>Tingkat<span class="text-danger"> *</span></label>
						<select class='form-control' name='id_tingkat' required>
							<option value=''>-- Pilih --</option>
							<?php
							$qkode3 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='tingkat' ORDER BY id");
							while ($kode3 = mysqli_fetch_array($qkode3)) {
								$sel = ($kode3['value'] == $edit['id_tingkat']) ? 'selected' : '';
								echo "<option value='$kode3[value]' $sel>$kode3[desc]</option>";
							}
							?>
						</select>
					</p>
					<p>
						<label>Tanggal Pelaksanaan<span class="text-danger"> *</span></label>
						<input name="tgl_pelaksanaan" type="date" class="form-control" value="<?= $edit['tgl_pelaksanaan'] ?>" required>
					</p>
					<p>
						<label>Bahasa</label>
						<input name="bahasa" type="text" class="form-control" value="<?= htmlspecialchars($edit['bahasa']) ?>">
					</p>
					<p>
						<label>Nomor Surat Tugas/SK</label>
						<input name="no_sk" type="text" class="form-control" value="<?= htmlspecialchars($edit['no_sk']) ?>">
					</p>
					<p>
						<label>Tanggal SK</label>
						<input name="tgl_sk" type="date" class="form-control" value="<?= $edit['tgl_sk'] ?>">
					</p>
					<p>
						<label>Link Penelitian</label>
						<input name="link_lit" type="url" class="form-control" value="<?= htmlspecialchars($edit['link_lit']) ?>">
					</p>
					<p>
						<label>Link Pengabdian</label>
						<input name="link_abmas" type="url" class="form-control" value="<?= htmlspecialchars($edit['link_abmas']) ?>">
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
