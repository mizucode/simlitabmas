<?php

ob_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {
	switch (@$folder[3]) {
		default:
			if (empty($folder['3'])) {
				$ta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE active='Y' "));
				$tgl_start = '1970-01-01';
				$tgl_end = '2099-12-31';
			} else {
				$sta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE kode='$folder[3]' "));
				$tgl_start = $sta ? $sta['tgl_start'] : '1970-01-01';
				$tgl_end = $sta ? $sta['tgl_end'] : '2099-12-31';
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
									$selected = ($k['kode'] == @$folder[3]) ? 'selected' : '';
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
							$selected = ($p['kode'] == @$folder[4]) ? 'selected' : '';
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
							<h3 class="card-title">Karya yang Tidak Memiliki Paten/HKI</h3>
							<div class="card-tools">
								<?php
								$q_pending = mysqli_query($con, "SELECT COUNT(*) as jml FROM penelitian_karya WHERE (status='1' OR status IS NULL OR status='') AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end'");
								$r_pending = mysqli_fetch_assoc($q_pending);
								$jml_pending = $r_pending['jml'];
								?>
								<a href="/penelitian/<?= @$folder[2] ?>/validasi/<?= @$folder[3] ?>" class="btn btn-info">
									Perlu Validasi
									<?php if ($jml_pending > 0) { ?>
										<span class="badge badge-danger"><?= $jml_pending ?></span>
									<?php } ?>
								</a>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah">
									<i class="fas fa-plus"></i> Tambah Karya
								</button>
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-karya" class="table table-bordered table-hover table-striped text-nowrap w-100"
								style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th>Tim Karya</th>
										<th>Ketua</th>
										<th>Jenis</th>
										<th>Nama</th>
										<th>Tanggal Dibuat</th>
										<th>File</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT * FROM penelitian_karya,dosen WHERE penelitian_karya.nip_dosen=dosen.nik AND penelitian_karya.status='2' AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' ORDER BY dosen.nik ");
									if (mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {
											$id_jenis = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_jenis']}' AND kelompok='karya' LIMIT 1"));

											$q_regis_cnt = mysqli_query($con, "SELECT COUNT(*) as total FROM regis_dosen_penelitian WHERE id_porto='{$r['id']}' AND jenis_porto='karya'");
											$d_regis_cnt = mysqli_fetch_array($q_regis_cnt);
											$jml_dosen = $d_regis_cnt ? (int) $d_regis_cnt['total'] : 0;
											?>
											<tr>
												<td class="align-middle text-center"><?= $no ?></td>
												<td class="align-middle text-center">
													<a href="/penelitian/karya/anggota/<?= $r['id'] ?>" class="badge badge-info p-2 px-3"
														style="font-size: 0.9rem;">
														<i class="fas fa-users mr-1"></i> <?= $jml_dosen ?> Dosen
													</a>
												</td>
												<td class="align-middle">
													<span class="text-dark"><?= $r['name_glr'] ?></span>
												</td>
												<td class="align-middle text-center"><?= $id_jenis ? $id_jenis['desc'] : '' ?></td>
												<td class="align-middle">
													<span class='text-dark text-wrap' style='line-height: 1.4;'><?= $r['judul'] ?></span>
												</td>
												<td class="align-middle text-center"><?= $r['tgl_terbit'] ?></td>
												<td class="align-middle text-center">
													<?php
													$qfile_pivot = mysqli_query($con, "
										SELECT f.url as url_file, kode.desc as type_file, d.name_glr
										FROM file_dosen f
										LEFT JOIN dosen d ON f.nip_dosen = d.nik
										LEFT JOIN kode ON f.value_kode = kode.value AND kode.kelompok = 'file_karya'
										WHERE f.id_cat = '{$r['id']}' AND f.kelompok = 'file_karya'
									");

													$jml_file = mysqli_num_rows($qfile_pivot);

													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal"
															data-target="#modalFile<?= $r['id'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>

														<!-- Modal daftar file -->
														<div class="modal fade text-left" id="modalFile<?= $r['id'] ?>" tabindex="-1" role="dialog"
															aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h4 class="modal-title">File Lampiran</h4>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		<div class="table-responsive">
																			<table class="table table-bordered text-sm mb-0">
																				<thead class="bg-light text-center">
																				<tr>
																					<th>Nama Dokumen</th>
																					<th>Keterangan</th>
																					<th class="text-center" style="width: 80px;">Aksi</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				while ($rf = mysqli_fetch_array($qfile_pivot)) {
																					$type = !empty($rf['type_file']) ? $rf['type_file'] : 'Dokumen Pendukung';
																					?>
																					<tr>
																						<td class="align-middle"><?= $type ?></td>
																						<td class="align-middle text-center"><?= $rf['name_glr'] ?></td>
																						<td class="text-center align-middle">
																							<a href="<?= @$storage_url . $rf['url_file'] ?>" target="_blank"
																								class="btn btn-xs btn-primary">
																								<i class="fas fa-download"></i> Unduh
																							</a>
																						</td>
																					</tr>
																					<?php
																				}
																				?>
																			</tbody>
																		</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<?php
													} else {
														echo "<span class='text-muted small'>-</span>";
													}
													?>
												</td>
												<td class="text-center align-middle">
													<a href="/penelitian/karya/anggota/<?= $r['id'] ?>" class="btn btn-xs btn-info shadow-sm">Lihat
														Detail</a>
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

					<?php include "form/karya/tambah.php"; ?>


				</div>
			</div>
			<?php
			break;

		case 'edit':
			include "form/karya/edit.php";
			break;

		case 'anggota':
			include "anggota/karya.php";
			break;

		case 'validasi':
			include "validasi/karya.php";
			break;

	}
}
?>
<script>
	$(function () {
		$('.select2bs4').select2({
			theme: 'bootstrap4'
		});
	});
</script>