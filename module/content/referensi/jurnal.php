<?php

ob_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

?>

<div class="row">
	<div class="col-md-12">
		<div class="card card-outline card-danger">
			<div class="card-header">
				<h3 class="card-title">Referensi Jurnal</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah">
						<i class="fas fa-plus"></i> Tambah Jurnal
					</button>
				</div>
			</div>
			
			<div class="card-body p-3">
				<table id="tabel-jurnal" class="table table-bordered table-hover table-striped text-nowrap w-100"
					style="font-size: 0.9rem;">
					<thead class="bg-white text-center align-middle">
						<tr>
							<th class="align-middle text-center">No</th>
							<th class="align-middle text-center">Cover</th>
							<th class="align-middle text-center">Jenis</th>
							<th class="align-middle text-center">Nama Jurnal</th>
							<th class="align-middle text-center">Penerbit</th>
							<th class="align-middle text-center">Link Jurnal</th>
							<th class="align-middle text-center">ISSN</th>
							<th class="align-middle text-center">Edisi Pertama</th>
							<th class="align-middle text-center">Akreditasi</th>
							<th class="align-middle text-center">File Akreditasi</th>
							<th class="align-middle text-center">Tgl Berlaku Akreditasi</th>
							<th class="align-middle text-center">Tgl Berakhir Akreditasi</th>
							<th class="align-middle text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$query = mysqli_query($con, "SELECT * FROM referensi_jurnal ORDER BY id DESC");
						while ($r = mysqli_fetch_array($query)) {
							?>
							<tr>
								<td class="align-middle text-center"><?= $no++ ?></td>
								<td class="align-middle text-center">
									<?php if (!empty($r['cover_url'])) { ?>
										<a href="<?= $storage_url . $r['cover_url'] ?>" target="_blank">
											<img src="<?= $storage_url . $r['cover_url'] ?>" style="max-height: 50px; max-width:50px;">
										</a>
									<?php } else { ?>
										-
									<?php } ?>
								</td>
								<td class="align-middle text-center"><?= ucfirst($r['jenis']) ?></td>
								<td class="align-middle text-center"><?= $r['nama_jurnal'] ?></td>
								<td class="align-middle text-center"><?= $r['penerbit'] ?></td>
								<td class="align-middle text-center">
									<?php if (!empty($r['link_jurnal'])) { ?>
										<a href="<?= $r['link_jurnal'] ?>" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-external-link-alt"></i> Link</a>
									<?php } ?>
								</td>
								<td class="align-middle text-center"><?= $r['issn'] ?></td>
								<td class="align-middle text-center"><?= $r['tahun_edisi_pertama'] ?></td>
								<td class="align-middle text-center"><?= $r['peringkat_akreditasi'] ?></td>
								<td class="align-middle text-center">
									<?php if (!empty($r['file_akreditasi'])) { ?>
										<a href="<?= $storage_url . $r['file_akreditasi'] ?>" target="_blank" class="btn btn-xs btn-outline-info shadow-sm"><i class="fas fa-download"></i> File</a>
									<?php } ?>
								</td>
								<td class="align-middle text-center"><?= $r['tgl_berlaku_akred'] ?></td>
								<td class="align-middle text-center"><?= $r['tgl_berakhir_akred'] ?></td>
								<td class="align-middle text-center">
									<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit<?= $r['id'] ?>"><i class="fas fa-edit"></i></button>
									<a href="/delete/referensi/jurnal/<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
								</td>
							</tr>

							<!-- Modal Edit -->
							<div class="modal fade" id="edit<?= $r['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Edit Terbitan Jurnal</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body">
											<form action="/update/referensi/jurnal" method="post" enctype="multipart/form-data">
												<div class="modal-body">
													<div class="form-group">
														<input type="hidden" name="id" value="<?= $r['id'] ?>">
														<input type="hidden" name="file_akreditasi_lama" value="<?= $r['file_akreditasi'] ?>">
														<input type="hidden" name="cover_url_lama" value="<?= $r['cover_url'] ?>">
														
														<p>
															<label>Jenis<span class="text-danger"> *</span></label>
															<select name="jenis" class="form-control" required>
																<option value="penelitian" <?= ($r['jenis'] == 'penelitian') ? 'selected' : '' ?>>Penelitian</option>
																<option value="pengabdian" <?= ($r['jenis'] == 'pengabdian') ? 'selected' : '' ?>>Pengabdian</option>
															</select>
														</p>
														<p>
															<label>Nama Jurnal<span class="text-danger"> *</span></label>
															<input type="text" class="form-control" name="nama_jurnal" value="<?= $r['nama_jurnal'] ?>" required>
														</p>
														<p>
															<label>Penerbit</label>
															<input type="text" class="form-control" name="penerbit" value="<?= $r['penerbit'] ?>">
														</p>
														<p>
															<label>Link Jurnal</label>
															<input type="url" class="form-control" name="link_jurnal" value="<?= $r['link_jurnal'] ?>">
														</p>
														<p>
															<label>ISSN</label>
															<input type="text" class="form-control" name="issn" value="<?= $r['issn'] ?>">
														</p>
														<p>
															<label>Tahun Edisi Pertama</label>
															<input type="number" class="form-control" name="tahun_edisi_pertama" value="<?= $r['tahun_edisi_pertama'] ?>">
														</p>
														<p>
															<label>Peringkat Akreditasi</label>
															<input type="text" class="form-control" name="peringkat_akreditasi" value="<?= $r['peringkat_akreditasi'] ?>">
														</p>
														<p>
															<label>Tgl Berlaku Akreditasi</label>
															<input type="date" class="form-control" name="tgl_berlaku_akred" value="<?= $r['tgl_berlaku_akred'] ?>">
														</p>
														<p>
															<label>Tgl Berakhir Akreditasi</label>
															<input type="date" class="form-control" name="tgl_berakhir_akred" value="<?= $r['tgl_berakhir_akred'] ?>">
														</p>
														<p>
															<label>File Akreditasi Baru</label>
															<input type="file" class="form-control" name="file_akreditasi" accept=".pdf">
															<small>Abaikan jika tidak ingin mengganti file</small>
														</p>
														<p>
															<label>Cover Jurnal Baru</label>
															<input type="file" class="form-control" name="cover_url" accept="image/jpeg, image/png, image/jpg">
															<small>Abaikan jika tidak ingin mengganti gambar</small>
														</p>
													</div>
												</div>
												<div class="modal-footer justify-content-between">
													<input type="submit" class="btn btn-primary" value="Simpan Perubahan">
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>

						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Tambah Terbitan Jurnal</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<form action="/insert/referensi/jurnal" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<p>
							<label>Jenis<span class="text-danger"> *</span></label>
							<select name="jenis" class="form-control" required>
								<option value="penelitian">Penelitian</option>
								<option value="pengabdian">Pengabdian</option>
							</select>
						</p>
						<p>
							<label>Nama Jurnal<span class="text-danger"> *</span></label>
							<input type="text" class="form-control" name="nama_jurnal" required>
						</p>
						<p>
							<label>Penerbit</label>
							<input type="text" class="form-control" name="penerbit">
						</p>
						<p>
							<label>Link Jurnal</label>
							<input type="url" class="form-control" name="link_jurnal">
						</p>
						<p>
							<label>ISSN</label>
							<input type="text" class="form-control" name="issn">
						</p>
						<p>
							<label>Tahun Edisi Pertama</label>
							<input type="number" class="form-control" name="tahun_edisi_pertama">
						</p>
						<p>
							<label>Peringkat Akreditasi</label>
							<input type="text" class="form-control" name="peringkat_akreditasi">
						</p>
						<p>
							<label>Tgl Berlaku Akreditasi</label>
							<input type="date" class="form-control" name="tgl_berlaku_akred">
						</p>
						<p>
							<label>Tgl Berakhir Akreditasi</label>
							<input type="date" class="form-control" name="tgl_berakhir_akred">
						</p>
						<p>
							<label>File Akreditasi</label>
							<input type="file" class="form-control" name="file_akreditasi" accept=".pdf">
						</p>
						<p>
							<label>Cover Jurnal</label>
							<input type="file" class="form-control" name="cover_url" accept="image/jpeg, image/png, image/jpg">
						</p>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</form>
		</div>
	</div>
</div>

<?php 
} 
?>

<script>
	$(function () {
		$("#tabel-jurnal").DataTable({
			"paging": true,
			"lengthChange": false,
			"info": true,
			"searching": true,
			"scrollX": true,
			"language": {
				"emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data</p>"
			}
		});
	});
</script>
