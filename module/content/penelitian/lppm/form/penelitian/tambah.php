<?php
// Form Tambah Penelitian
?>
<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Kegiatan Penelitian</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/penelitian">
					<div class="form-group">
						<p>
							<label>Dosen Peneliti<span class="text-danger"> *</span></label>
							<select class="form-control select2-dosen-tambah" name="nip_dosen" style="width: 100%;" required>
								<option value="">-- Pilih Dosen --</option>
								<?php
								$qdosen = mysqli_query($con, "SELECT nik, name_glr FROM dosen ORDER BY name");
								while ($d = mysqli_fetch_array($qdosen)) {
									?>
									<option value="<?= $d['nik'] ?>"><?= $d['nik'] ?> - <?= $d['name_glr'] ?></option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>Judul Kegiatan<span class="text-danger"> *</span></label>
							<input name="judul_kegiatan" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Afiliasi<span class="text-danger"> *</span></label>
							<input name="afiliasi" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Skema<span class="text-danger"> *</span></label>
							<select class="form-control select2-skema-tambah" name="id_skema" style="width: 100%;" required>
								<option value="">-- Pilih --</option>
								<?php
								$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='skema_penelitian' ORDER BY id");
								while ($kode1 = mysqli_fetch_array($qkode1)) {
									?>
									<option value="<?= $kode1['value'] ?>"><?= $kode1['desc'] ?></option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>Tahun Usulan<span class="text-danger"> *</span></label>
							<input name="thn_usulan" type="text" class="form-control" placeholder="Silakan diisi" maxlength="4" required>
						</p>
						<p>
							<label>Tanggal Mulai Kegiatan (<?= $tgl_start ?> - <?= $tgl_end ?>)<span class="text-danger"> *</span></label>
							<input name="tgl_mulai_kegiatan" type="date" class="form-control" min="<?= $tgl_start ?>" max="<?= $tgl_end ?>" required>
						</p>
						<div class="form-group">
							<label>Lama Kegiatan</label>
							<div class="row">
								<div class="col-md-6 col-6">
									<input name="lama_angka" type="number" class="form-control" placeholder="Angka (ex: 1)" min="1" max="99">
								</div>
								<div class="col-md-6 col-6">
									<select name="lama_satuan" class="form-control">
										<option value="T">Tahun (T)</option>
										<option value="B">Bulan (B)</option>
										<option value="S">Semester (S)</option>
										<option value="M">Minggu (M)</option>
										<option value="H">Hari (H)</option>
										<option value="ta">Tahun Ajaran (ta)</option>
									</select>
								</div>
							</div>
							<small class="text-muted">Pilih angka dan satuan, sistem otomatis menyimpan ke format database singkatan standar (cth: 1 T).</small>
						</div>
						<p>
							<label>Tahun Pelaksanaan ke<span class="text-danger"> *</span></label>
							<input name="thn_pelaksanaan_ke" type="number" class="form-control" max="10" required>
						</p>
						<p>
							<label>Dana dari Dikti<span class="text-danger"> *</span></label>
							<input name="dana_dikti" type="number" class="form-control" required>
						</p>
						<p>
							<label>Dana dari Perguruan Tinggi<span class="text-danger"> *</span></label>
							<input name="dana_kampus" type="number" class="form-control" required>
						</p>
						<p>
							<label>Dana dari Mandiri<span class="text-danger"> *</span></label>
							<input name="dana_mandiri" type="number" class="form-control" required>
						</p>
						<p>
							<label>Dana dari Sumber Lainnya<span class="text-danger"> *</span></label>
							<input name="dana_lain" type="number" class="form-control" required>
						</p>
						<p>
							<label>Peran<span class="text-danger"> *</span></label>
							<select class="form-control" name="id_peran" required>
								<option value="">-- Pilih --</option>
								<?php
								$qkode2 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='peran_penelitian' ORDER BY id");
								while ($kode2 = mysqli_fetch_array($qkode2)) {
									?>
									<option value="<?= $kode2['value'] ?>"><?= $kode2['desc'] ?></option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>Nomor SK Tugas</label>
							<input name="no_sk" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Tanggal SK Tugas</label>
							<input name="tgl_sk" type="date" class="form-control">
						</p>
						<p>
							<label>Mitra</label>
							<input name="mitra" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Apakah Melibatkan Mahasiswa?</label>
							<input name="libatkan_mhs" type="checkbox" value="Ya"> Ya
						</p>
						<p>
							<label>Apakah dijadikan Rujukan Tesis/Disertasi?</label>
							<input name="rujukan_ta" type="checkbox" value="Ya"> Ya
						</p>
						<p>
							<label>Kelompok Bidang</label>
							<input name="kelompok_bidang" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Lokasi Penelitian</label>
							<input name="lokasi" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Jenis File<span class="text-danger"> *</span></label>
							<select class="form-control" name="jenis_file" required>
								<option value="">-- Pilih --</option>
								<?php
								$qkode3 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_penelitian' ORDER BY id");
								while ($kode3 = mysqli_fetch_array($qkode3)) {
                                    ?>
									<option value="<?= $kode3['value'] ?>"><?= $kode3['desc'] ?></option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>File<span class="text-danger"> *</span></label>
							<input name="file" type="file" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
						</p>
						<label>Pernyataan, <i>Bismillahirrahmanirrahim</i><span class="text-danger"> *</span></label>
						<p>
							<input type="checkbox" name="p1" value="Y" required> Saya mengisi form dengan secara cermat dan jujur<br>
							<input type="checkbox" name="p2" value="Y" required> Saya bertanggungjawab secara hukum dan moral atas isian form<br>
							<input type="checkbox" name="p3" value="Y" required> Saya memilih dan mengunggah berkas yang benar<br>
							<input type="checkbox" name="p4" value="Y" required> Saya mengunggah berkas dengan kualitas yang baik<br>
						</p>
					</div>
			</div>
			<div class="modal-footer justify-content-between">
				<input type="submit" class="btn btn-primary" value="Tambah">
			</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function () {
	$('.select2-dosen-tambah, .select2-skema-tambah').select2({
		theme: 'bootstrap4',
		dropdownParent: $('#tambah')
	});
});
</script>