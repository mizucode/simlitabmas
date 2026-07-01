<?php

ob_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	switch (@$folder[2]) {
		default:
			if (empty($folder['3'])) {
				$ta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE active='Y' "));
				$tgl_start = '1970-01-01';
				$tgl_end = '2099-12-31';
			} else {
				$sta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE kode='{$folder[3]}' "));
				$tgl_start = $sta['tgl_start'];
				$tgl_end = $sta['tgl_end'];
			}
			?>

			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control'
								name='ta'>
								<option value='/penelitian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>


								<?php

								$qk = mysqli_query($con, "SELECT * FROM ts WHERE active!='u' ORDER BY kode DESC");
								while ($k = mysqli_fetch_array($qk)) {
									$selected = ($k['kode'] == $folder[3]) ? 'selected' : '';
									?>
									<option value='/penelitian/<?= $folder[2] ?>/<?= $k['kode'] ?>' <?= $selected ?>><?= $k['thn'] ?>
										<?= $k['semester'] ?> (<?= $k['tgl_start'] ?> s.d <?= $k['tgl_end'] ?>)</option>
									<?php
								}
								?>

							</select>


						</div>
					</div>
				</div>
			</div>
			<?php
			if (!empty($folder[3])) {
				?>
				<!--
			<div class='row'>
				<div class='col-md-12'>
					<div class='card card-success'>
						<div class='card-body'>
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='prodi'>
							<option value='/penelitian/<?php echo $folder[2]; ?>'>-- Prodi --</option>
						<?php

						$qp = mysqli_query($con, "SELECT * FROM prodi ORDER BY kode DESC");
						while ($p = mysqli_fetch_array($qp)) {
							$selected = ($p['kode'] == $folder[4]) ? 'selected' : '';
							?>
						<option value='/penelitian/<?= $folder[2] ?>/<?= $folder[3] ?>/<?= $p['kode'] ?>' <?= $selected ?>><?= $p['nama'] ?> (<?= $p['singkatan'] ?>)</option>
						<?php
						}
						?>
						
							</select>
						</div>
					</div>
				</div>
			</div>
!-->
				<?php
			}
			?>
			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Kegiatan Penelitian</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i
										class="fas fa-plus"></i> Tambah</button>
								<!--					                      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> File</button>
																<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Peran</button>
!-->
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-penelitian" class="table table-hover table-striped text-nowrap table-responsive w-100">
								<thead>
									<tr>
										<th>No</th>
										<th>Nama Dosen</th>
										<th>Judul</th>
										<th>Skema</th>
										<th>Peran</th>
										<th>File</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT * FROM penelitian_penelitian,dosen WHERE penelitian_penelitian.nip_dosen=dosen.nik AND tgl_mulai_kegiatan>='$tgl_start' AND tgl_mulai_kegiatan<='$tgl_end' ORDER BY penelitian_penelitian.id_skema,penelitian_penelitian.judul_kegiatan ");
									if (mysqli_num_rows($q) < 1) {
										?>
														<tr><td colspan='6'><p class='text-center text-danger'>Tidak ada data pada semester/TA yang dipilih</p></td></tr>
													<?php
									} else {

										while ($r = mysqli_fetch_array($q)) {
											$skema = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_skema']}' AND kelompok='skema_penelitian' LIMIT 1"));
											$peran = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_peran']}' AND kelompok='peran_penelitian' LIMIT 1"));
											?>
															<tr>
																<td><?= $no ?></td>
																<td><?= $r['name_glr'] ?></td>
																<td><?= $r['judul_kegiatan'] ?></td>
																<td><?= $skema['desc'] ?></td>
																<td><?= $peran['desc'] ?></td>
																<td>
																<?php
																$qfile = mysqli_query($con, "SELECT fd.id,fd.url,kode.desc FROM file_dosen AS fd,kode WHERE fd.kelompok=kode.kelompok AND fd.value_kode=kode.value AND fd.id_cat='{$r['id']}' AND kode.kelompok='file_penelitian' ");
																while ($rfile = mysqli_fetch_array($qfile)) {
																	?>
																		<a href='/<?= $rfile['url'] ?>' target='_blank'><i class='fa fa-file'></i> <?= $rfile['desc'] ?></a><br>
																	<?php
																}
																?>
																</td>
															</tr>
														<?php
														$no++;
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="modal fade" id="tambah">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Tambah</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<form role="form" enctype="multipart/form-data" method="post" action="/insert/penelitian/penelitian">
										<div class="form-group">
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
												<select class='form-control' name='id_skema' required>
													<option value=''>-- Pilih --</option>
													<?php
													$qkode1 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='skema_penelitian' ORDER BY id");
													while ($kode1 = mysqli_fetch_array($qkode1)) {
														?>
														<option value='<?= $kode1['value'] ?>'><?= $kode1['desc'] ?></option>
														<?php
													}
													?>
												</select>
											</p>
											<p>
												<label>Tahun Usulan<span class="text-danger"> *</span></label>
												<input name="thn_usulan" type="text" class="form-control" placeholder="Silakan diisi" maxlength="4"
													required>
											</p>
											<p>
												<label>Tanggal Mulai Kegiatan (<?php echo $tgl_start; ?> - <?php echo $tgl_end; ?>)<span
														class="text-danger"> *</span></label>
												<input name="tgl_mulai_kegiatan" type="date" class="form-control" min="<?php echo $tgl_start; ?>"
													max="<?php echo $tgl_end; ?>" required>
											</p>
											<p>
												<label>Lama Kegiatan</label>
												<input name="lama_kegiatan" type="text" class="form-control" placeholder="Silakan diisi">
											</p>
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
												<select class='form-control' name='id_peran' required>
													<option value=''>-- Pilih --</option>
													<?php
													$qkode2 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='peran_penelitian' ORDER BY id");
													while ($kode2 = mysqli_fetch_array($qkode2)) {
														?>
														<option value='<?= $kode2['value'] ?>'><?= $kode2['desc'] ?></option>
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
												<select class='form-control' name='jenis_file' required>
													<option value=''>-- Pilih --</option>
													<?php
													$qkode3 = mysqli_query($con, "SELECT * FROM kode WHERE kelompok='file_penelitian' ORDER BY id");
													while ($kode3 = mysqli_fetch_array($qkode3)) {
														?>
														<option value='<?= $kode3['value'] ?>'><?= $kode3['desc'] ?></option>
														<?php
													}
													?>
												</select>
											</p>
											<p>
												<label>File<span class="text-danger"> *</span></label>
												<input name="file" type="file" class="form-control" accept=".pdf,.png,.jpg,.jpeg" required>
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


				</div>
			</div>
			<script>
				$(function () {
					$("#tabel-penelitian").DataTable({
						"paging": false,
						"lengthChange": false,
						"info": true,
						"searching": true,
						"buttons": ["copy", "csv", "excel", "pdf", "print"]
					}).buttons().container().appendTo('#tabel-penelitian_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':

			break;

	}
}