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
				<h3 class="card-title">Referensi Klaster</h3>
				<div class="card-tools">
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah">
						<i class="fas fa-plus"></i> Tambah Klaster
					</button>
				</div>
			</div>
			
			<div class="card-body p-3">
				<table id="tabel-klaster" class="table table-bordered table-hover table-striped text-nowrap w-100"
					style="font-size: 0.9rem;">
					<thead class="bg-white text-center align-middle">
						<tr>
							<th class="align-middle text-center">No</th>
							<th class="align-middle text-center">Jenis</th>
							<th class="align-middle text-center">Tahun</th>
							<th class="align-middle text-center">Klaster</th>
							<th class="align-middle text-center">File SK Klaster</th>
							<th class="align-middle text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$query = mysqli_query($con, "SELECT * FROM referensi_klaster ORDER BY id DESC");
						while ($r = mysqli_fetch_array($query)) {
							?>
							<tr>
								<td class="align-middle text-center"><?= $no++ ?></td>
								<td class="align-middle text-center"><?= ucfirst($r['jenis']) ?></td>
								<td class="align-middle text-center"><?= $r['tahun'] ?></td>
								<td class="align-middle text-center"><?= $r['klaster'] ?></td>
								<td class="align-middle text-center">
									<?php if (!empty($r['file_sk_klaster'])) { ?>
										<a href="<?= $storage_url . $r['file_sk_klaster'] ?>" target="_blank" class="btn btn-xs btn-outline-info shadow-sm"><i class="fas fa-download"></i> File SK</a>
									<?php } else { ?>
										-
									<?php } ?>
								</td>
								<td class="align-middle text-center">
									<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit<?= $r['id'] ?>"><i class="fas fa-edit"></i></button>
									<a href="/delete/referensi/klaster/<?= $r['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
								</td>
							</tr>

							<!-- Modal Edit -->
							<div class="modal fade" id="edit<?= $r['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">Edit Data Klaster</h4>
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body">
											<form action="/update/referensi/klaster" method="post" enctype="multipart/form-data">
												<div class="modal-body">
													<div class="form-group">
														<input type="hidden" name="id" value="<?= $r['id'] ?>">
														<input type="hidden" name="file_sk_klaster_lama" value="<?= $r['file_sk_klaster'] ?>">
														
														<p>
															<label>Jenis<span class="text-danger"> *</span></label>
															<select name="jenis" class="form-control" required>
																<option value="penelitian" <?= ($r['jenis'] == 'penelitian') ? 'selected' : '' ?>>Penelitian</option>
																<option value="pengabdian" <?= ($r['jenis'] == 'pengabdian') ? 'selected' : '' ?>>Pengabdian</option>
															</select>
														</p>
														<p>
															<label>Tahun<span class="text-danger"> *</span></label>
															<input type="number" class="form-control" name="tahun" value="<?= $r['tahun'] ?>" required>
														</p>
														<p>
															<label>Klaster<span class="text-danger"> *</span></label>
															<input type="text" class="form-control" name="klaster" value="<?= $r['klaster'] ?>" required>
														</p>
														<p>
															<label>File SK Klaster Baru</label>
															<input type="file" class="form-control" name="file_sk_klaster" accept=".pdf">
															<small>Abaikan jika tidak ingin mengganti file</small>
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
				<h4 class="modal-title">Tambah Data Klaster</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<form action="/insert/referensi/klaster" method="post" enctype="multipart/form-data">
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
							<label>Tahun<span class="text-danger"> *</span></label>
							<input type="number" class="form-control" name="tahun" required>
						</p>
						<p>
							<label>Klaster<span class="text-danger"> *</span></label>
							<input type="text" class="form-control" name="klaster" required>
						</p>
						<p>
							<label>File SK Klaster</label>
							<input type="file" class="form-control" name="file_sk_klaster" accept=".pdf">
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
		$("#tabel-klaster").DataTable({
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

