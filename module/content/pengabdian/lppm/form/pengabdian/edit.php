<?php
// Form Edit Pengabdian
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM pengabdian_pengabdian WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Kegiatan Pengabdian</h3>
			<div class="card-tools">
				<a href="/pengabdian/<?= @$folder[2] ?: 'pengabdian' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/pengabdian/pengabdian">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/pengabdian/<?= @$folder[2] ?: 'pengabdian' ?>/anggota/<?= $edit['id'] ?>">
				<div class="form-group">
					<input type="hidden" name="nip_dosen" value="<?= $edit['nip_dosen'] ?>">
					<p>
						<label>Judul Kegiatan<span class="text-danger"> *</span></label>
						<input name="judul_kegiatan" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['judul_kegiatan']) ?>" required>
					</p>
					<p>
						<label>Afiliasi<span class="text-danger"> *</span></label>
						<input name="afiliasi" type="text" class="form-control" value="<?= htmlspecialchars($edit['afiliasi']) ?>"
							required>
					</p>
					<p>
						<label>Skema<span class="text-danger"> *</span></label>
						<select class="form-control select2-skema-edit" name="id_skema" style="width: 100%;" required>
							<option value="">-- Pilih --</option>
							<?php
							$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='skema_pengabdian' ORDER BY id");
							while ($kode1 = mysqli_fetch_array($qkode1)) {
								$sel = ($kode1['value'] == $edit['id_skema']) ? 'selected' : '';
								?>
								<option value="<?= $kode1['value'] ?>" <?= $sel ?>><?= $kode1['desc'] ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p>
						<label>Tahun Usulan<span class="text-danger"> *</span></label>
						<input name="thn_usulan" type="text" class="form-control" value="<?= htmlspecialchars($edit['thn_usulan']) ?>"
							required>
					</p>
					<p>
						<label>Tanggal Mulai Kegiatan<span class="text-danger"> *</span></label>
						<input name="tgl_mulai_kegiatan" type="date" class="form-control"
							value="<?= $edit['tgl_mulai_kegiatan'] ?>" required>
					</p>
					<p>
						<label>Lama Kegiatan</label>
						<input name="lama_kegiatan" type="text" class="form-control" value="<?= htmlspecialchars($edit['lama_kegiatan']) ?>">
					</p>
					<p>
						<label>Tahun Pelaksanaan ke<span class="text-danger"> *</span></label>
						<input name="thn_pelaksanaan_ke" type="number" class="form-control"
							value="<?= $edit['thn_pelaksanaan_ke'] ?>" max="10" required>
					</p>
					<p>
						<label>Dana dari Dikti<span class="text-danger"> *</span></label>
						<input name="dana_dikti" type="number" class="form-control" value="<?= $edit['dana_dikti'] ?>" required>
					</p>
					<p>
						<label>Dana dari Perguruan Tinggi<span class="text-danger"> *</span></label>
						<input name="dana_kampus" type="number" class="form-control" value="<?= $edit['dana_kampus'] ?>" required>
					</p>
					<p>
						<label>Dana dari Mandiri<span class="text-danger"> *</span></label>
						<input name="dana_mandiri" type="number" class="form-control" value="<?= $edit['dana_mandiri'] ?>" required>
					</p>
					<p>
						<label>Dana dari Sumber Lainnya<span class="text-danger"> *</span></label>
						<input name="dana_lain" type="number" class="form-control" value="<?= $edit['dana_lain'] ?>" required>
					</p>
					<p>
						<label>Nomor SK Tugas</label>
						<input name="no_sk" type="text" class="form-control" value="<?= htmlspecialchars($edit['no_sk']) ?>">
					</p>
					<p>
						<label>Tanggal SK Tugas</label>
						<input name="tgl_sk" type="date" class="form-control" value="<?= $edit['tgl_sk'] ?>">
					</p>
					<p>
						<label>Mitra</label>
						<input name="mitra" type="text" class="form-control" value="<?= htmlspecialchars($edit['mitra']) ?>">
					</p>
					<p>
						<label>Apakah Melibatkan Mahasiswa?</label>
						<input name="libatkan_mhs" type="checkbox" value="Ya" <?= ($edit['libatkan_mhs'] == 'Ya') ? 'checked' : '' ?>> Ya
					</p>
					<p>
						<label>Apakah dijadikan Rujukan Tesis/Disertasi?</label>
						<input name="rujukan_ta" type="checkbox" value="Ya" <?= ($edit['rujukan_ta'] == 'Ya') ? 'checked' : '' ?>> Ya
					</p>
					<p>
						<label>Kelompok Bidang</label>
						<input name="kelompok_bidang" type="text" class="form-control"
							value="<?= htmlspecialchars($edit['kelompok_bidang']) ?>">
					</p>
					<p>
						<label>Lokasi Pengabdian</label>
						<input name="lokasi" type="text" class="form-control" value="<?= htmlspecialchars($edit['lokasi']) ?>">
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
