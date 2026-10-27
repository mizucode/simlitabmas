<?php
// Form Tambah Penelitian
?>
<div class="modal fade" id="tambah">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Paten / HKI</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/patenhki">
				<div class="modal-body">
					<div class="form-group">
						<p>
							<label>Dosen Peneliti / Mahasiswa<span class="text-danger"> *</span></label>
							<select class="form-control select2-dosen-hki" name="nip_dosen" style="width: 100%;" required>
								<option value="">-- Cari Nama/NIK/NIM --</option>
								<?php
								$qdosen = mysqli_query($con, "SELECT nik, name_glr, 'Dosen' AS tipe FROM dosen UNION SELECT nim AS nik, name AS name_glr, 'Mahasiswa' AS tipe FROM mhs ORDER BY name_glr");
								while ($d = mysqli_fetch_array($qdosen)) {
									?>
									<option value="<?= $d['nik'] ?>"><?= $d['name_glr'] ?> (<?= $d['nik'] ?> - <?= $d['tipe'] ?>)</option>
									<?php
								}
								?>
							</select>
						</p>


						<p>
							<label>Jenis<span class="text-danger"> *</span></label>
							<select class="form-control" name="id_jenis" style="width: 100%;" required>
								<option value="">-- Pilih --</option>
								<?php
								$qjenis = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='patenhki' ORDER BY value");
								while ($jenis = mysqli_fetch_array($qjenis)) {
									?>
									<option value="<?= $jenis['value'] ?>">
										<?= $jenis['desc'] ?>
									</option>
									<?php
								}
								?>
							</select>
						</p>
						<p>
							<label>Judul Paten<span class="text-danger"> *</span></label>
							<input name="judul" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Tanggal Paten (
								<?= $tgl_start ?> -
								<?= $tgl_end ?>)<span class="text-danger"> *</span>
							</label>
							<input name="tgl_paten" type="date" class="form-control" min="<?= $tgl_start ?>" max="<?= $tgl_end ?>"
								required>
						</p>
						<p>
							<label>Nomor Paten<span class="text-danger"> *</span></label>
							<input name="nomor" type="text" class="form-control" placeholder="Silakan diisi" required>
						</p>
						<p>
							<label>Negara/Otoritas Pemberi</label>
							<input name="pemberi" type="text" class="form-control" placeholder="Silakan diisi">
						</p>
						<p>
							<label>Tingkat Kesiapan Teknologi (TKT)</label>
							<select class="form-control" name="id_tkt" style="width: 100%;" required>
								<option value="">-- Pilih --</option>
								<?php
								$qtkt = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='tkt' ORDER BY value");
								while ($tkt = mysqli_fetch_array($qtkt)) {
									?>
									<option value="<?= $tkt['value'] ?>">
										<?= $tkt['desc'] ?>
									</option>
									<?php
								}
								?>
							</select>
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
											?>
											<option value="<?= $lit['id'] ?>"><?= $lit['judul_kegiatan'] ?></option>
											<?php
										}
									}
								} catch (Exception $e) {
									echo "<!-- SQL Error (Lit): " . $e->getMessage() . " -->";
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
											?>
											<option value="<?= $abmas['id'] ?>"><?= $abmas['judul_kegiatan'] ?></option>
											<?php
										}
									}
								} catch (Exception $e) {
									echo "<!-- SQL Error (Abmas): " . $e->getMessage() . " -->";
								}
								?>
							</select>
						</p>
						<p>
							<label>Jenis File<span class="text-danger"> *</span></label>
							<select class="form-control" name="jenis_file" required>
								<option value="">-- Pilih --</option>
								<?php
								$qkode3 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_patenhki' ORDER BY id");
								while ($kode3 = mysqli_fetch_array($qkode3)) {
									?>
									<option value="<?= $kode3['value'] ?>">
										<?= $kode3['desc'] ?>
									</option>
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
							<input type="checkbox" name="p2" value="Y" required> Saya bertanggungjawab secara hukum dan moral atas
							isian form<br>
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
		$('.select2-dosen-hki, .select2-link').select2({
			theme: 'bootstrap4',
			dropdownParent: $('#tambah')
		});
	});
</script>