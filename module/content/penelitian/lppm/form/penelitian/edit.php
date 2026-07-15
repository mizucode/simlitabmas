<?php
// Form Edit Penelitian
$id_edit = mysqli_real_escape_string($con, @$folder[4]);
$edit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM penelitian_penelitian WHERE id='$id_edit'"));
if (!$edit) {
	echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
} else {
	// Parsing nilai lama_kegiatan menjadi angka dan satuan
	$lama_val = trim($edit['lama_kegiatan']);
	$lama_angka = preg_replace('/[^0-9]/', '', $lama_val);
	$lama_sat_str = strtolower(trim(preg_replace('/[0-9]/', '', $lama_val)));
	$sel_t = ($lama_sat_str == 't' || $lama_sat_str == 'tahun' || empty($lama_sat_str)) ? 'selected' : '';
	$sel_b = ($lama_sat_str == 'b' || $lama_sat_str == 'bulan') ? 'selected' : '';
	$sel_s = ($lama_sat_str == 's' || $lama_sat_str == 'semester') ? 'selected' : '';
	$sel_m = ($lama_sat_str == 'm' || $lama_sat_str == 'minggu') ? 'selected' : '';
	$sel_h = ($lama_sat_str == 'h' || $lama_sat_str == 'hari') ? 'selected' : '';
	$sel_ta = ($lama_sat_str == 'ta') ? 'selected' : '';
	?>
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit Kegiatan Penelitian</h3>
			<div class="card-tools">
				<a href="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>" class="btn btn-sm btn-secondary"><i
						class="fas fa-arrow-left"></i> Kembali</a>
			</div>
		</div>
		<div class="card-body">
			<form role="form" enctype="multipart/form-data" method="post" action="/update/penelitian/penelitian">
				<input type="hidden" name="id" value="<?= $edit['id'] ?>">
				<input type="hidden" name="redirect" value="/penelitian/<?= @$folder[2] ?: 'lppm' ?>/anggota/<?= $edit['id'] ?>">
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
							$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='skema_penelitian' ORDER BY id");
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
							maxlength="4" required>
					</p>
					<p>
						<label>Tanggal Mulai Kegiatan<span class="text-danger"> *</span></label>
						<input name="tgl_mulai_kegiatan" type="date" class="form-control" value="<?= $edit['tgl_mulai_kegiatan'] ?>"
							required>
					</p>
					<div class="form-group">
						<label>Lama Kegiatan</label>
						<div class="row">
							<div class="col-md-6 col-6">
								<input name="lama_angka" type="number" class="form-control" value="<?= htmlspecialchars($lama_angka) ?>"
									placeholder="Angka (ex: 1)" min="1" max="99">
							</div>
							<div class="col-md-6 col-6">
								<select name="lama_satuan" class="form-control">
									<option value="T" <?= $sel_t ?>>Tahun (T)</option>
									<option value="B" <?= $sel_b ?>>Bulan (B)</option>
									<option value="S" <?= $sel_s ?>>Semester (S)</option>
									<option value="M" <?= $sel_m ?>>Minggu (M)</option>
									<option value="H" <?= $sel_h ?>>Hari (H)</option>
									<option value="ta" <?= $sel_ta ?>>Tahun Ajaran (ta)</option>
								</select>
							</div>
						</div>
						<small class="text-muted">Pilih angka dan satuan, sistem otomatis menyimpan ke format database singkatan
							standar (cth: 1 T).</small>
					</div>
					
					<p>
						<label>Tahun Pelaksanaan Ke<span class="text-danger"> *</span></label>
						<input name="thn_pelaksanaan_ke" type="number" class="form-control" value="<?= htmlspecialchars($edit['thn_pelaksanaan_ke']) ?>" required>
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
					<input type="hidden" name="id_peran" value="<?= $edit['id_peran'] ?>">
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
						<label>Lokasi Penelitian</label>
						<input name="lokasi" type="text" class="form-control" value="<?= htmlspecialchars($edit['lokasi']) ?>">
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
		$('.select2-dosen-edit, .select2-skema-edit').select2({
			theme: 'bootstrap4'
		});
	});
</script>