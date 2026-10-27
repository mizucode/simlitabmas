<?php

ob_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	switch (@$folder[3]) {
		default:
			if (empty($folder[3])) {
				$ta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE active='Y' "));
				$tgl_start = '1970-01-01';
				$tgl_end = '2099-12-31';
			} else {
				$sta = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM ts WHERE kode='{$folder[3]}' "));
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
								<option value='/pengabdian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>

								<?php
								$qk = mysqli_query($con, "SELECT * FROM ts WHERE active!='u' ORDER BY kode DESC");
								while ($k = mysqli_fetch_array($qk)) {
									$selected = ($k['kode'] == $folder[3]) ? 'selected' : '';
									?>
									<option value='/pengabdian/<?= $folder[2] ?>/<?= $k['kode'] ?>' <?= $selected ?>><?= $k['thn'] ?>
										<?= $k['semester'] ?> (<?= $k['tgl_start'] ?> s.d <?= $k['tgl_end'] ?>)
									</option>
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
							<option value='/pengabdian/<?php echo $folder[2]; ?>'>-- Prodi --</option>
						<?php

						$qp = mysqli_query($con, "SELECT * FROM prodi ORDER BY kode DESC");
						while ($p = mysqli_fetch_array($qp)) {
							$selected = ($p['kode'] == $folder[4]) ? 'selected' : '';
							?>
						<option value='/pengabdian/<?= $folder[2] ?>/<?= $folder[3] ?>/<?= $p['kode'] ?>' <?= $selected ?>><?= $p['nama'] ?> (<?= $p['singkatan'] ?>)</option>
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
							<h3 class="card-title">Pembicara / Narasumber</h3>
							<div class="card-tools">

								<?php
								$q_pending = mysqli_query($con, "SELECT COUNT(*) as jml FROM pengabdian_pembicara WHERE (status='1' OR status IS NULL OR status='') AND (tgl_pelaksanaan>='$tgl_start' AND tgl_pelaksanaan<='$tgl_end' OR tgl_pelaksanaan IS NULL OR CAST(tgl_pelaksanaan AS CHAR)='0000-00-00')");
								$r_pending = mysqli_fetch_assoc($q_pending);
								$jml_pending = $r_pending ? $r_pending['jml'] : 0;
								?>
								<a href="/pengabdian/<?= $folder[2] ?>/validasi/<?= @$folder[3] ?>" class="btn btn-info">
									Perlu Validasi
									<?php if ($jml_pending > 0) { ?>
										<span class="badge badge-danger"><?= $jml_pending ?></span>
									<?php } ?>
								</a>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i
										class="fas fa-plus"></i> Tambah</button>
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-pembicara" class="table table-bordered table-hover table-striped text-nowrap w-100"
								style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th class="align-middle">Nama Dosen</th>
										<th class="align-middle">Judul Materi & Pertemuan</th>
										<th class="align-middle">Penyelenggara</th>
										<th class="align-middle">Tgl Pelaksanaan</th>
										<th class="align-middle">File</th>
										<th class="text-center align-middle">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT *, pengabdian_pembicara.id AS id_pembicara FROM pengabdian_pembicara LEFT JOIN dosen ON pengabdian_pembicara.nip_dosen=dosen.nik WHERE pengabdian_pembicara.status='2' AND (tgl_pelaksanaan>='$tgl_start' AND tgl_pelaksanaan<='$tgl_end' OR tgl_pelaksanaan IS NULL OR CAST(tgl_pelaksanaan AS CHAR)='0000-00-00') ORDER BY pengabdian_pembicara.id DESC");
									if ($q && mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {
											$nama_dosen = !empty($r['name_glr']) ? $r['name_glr'] : ($r['nip_dosen'] ?? '-');
											?>
											<tr>
												<td class="align-middle text-center"><?= $no ?></td>
												<td class="align-middle">
													<span class="text-dark font-weight-bold"><?= htmlspecialchars((string) $nama_dosen) ?></span>
												</td>
												<td class="align-middle">
													<span class="text-dark d-inline-block text-truncate" style="max-width: 350px; line-height: 1.4;"
														title="<?= htmlspecialchars((string) $r['judul']) ?>">
														<?= htmlspecialchars((string) $r['judul']) ?>
													</span>
													<?php if (!empty($r['nama_pertemuan'])) { ?>
														<br><small class="text-muted d-inline-block text-truncate" style="max-width: 350px;"
															title="<?= htmlspecialchars((string) $r['nama_pertemuan']) ?>">
															<?= htmlspecialchars((string) $r['nama_pertemuan']) ?>
														</small>
													<?php } ?>
												</td>
												<td class="align-middle">
													<span class="text-dark"><?= htmlspecialchars((string) $r['penyelenggara']) ?></span>
												</td>
												<td class="align-middle text-center">
													<span class="text-dark"><?= htmlspecialchars((string) $r['tgl_pelaksanaan']) ?></span>
												</td>
												<td class="align-middle text-center">
													<?php
													$qfile_pivot = mysqli_query($con, "
														SELECT DISTINCT f.id, f.url as url_file, kode.desc as type_file, d.name_glr
														FROM file_dosen f
														LEFT JOIN dosen d ON f.nip_dosen = d.nik
														LEFT JOIN kode ON f.value_kode = kode.value AND kode.kelompok = 'file_pembicara'
														WHERE f.id_cat = '{$r['id_pembicara']}' AND f.kelompok = 'file_pembicara'
													");
													$jml_file = ($qfile_pivot) ? mysqli_num_rows($qfile_pivot) : 0;
													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal"
															data-target="#modalFile<?= $r['id_pembicara'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>

														<!-- Modal -->
														<div class="modal fade text-left" id="modalFile<?= $r['id_pembicara'] ?>" tabindex="-1" role="dialog"
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
																		<table class="table table-bordered text-sm mb-0">
																			<thead class="bg-light">
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
																						<td class="align-middle"><?= htmlspecialchars((string) $type) ?></td>
																						<td class="align-middle"><?= htmlspecialchars((string) $rf['name_glr']) ?></td>
																						<td class="text-center align-middle">
																							<a href="<?= $storage_url . $rf['url_file'] ?>" target="_blank"
																								class="btn btn-xs btn-primary"><i class="fas fa-download"></i> Unduh</a>
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
														<?php
													} else {
														echo "<span class='text-muted small'>-</span>";
													}
													?>
												</td>
												<td class="text-center align-middle">
													<a href="/pengabdian/pembicara/anggota/<?= $r['id_pembicara'] ?>"
														class="btn btn-xs btn-info shadow-sm">Lihat
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

					<?php include "form/pembicara/tambah.php"; ?>

				</div>
			</div>
			<script>
				$(function () {
					$("#tabel-pembicara").DataTable({
						"paging": false,
						"lengthChange": false,
						"info": true,
						"searching": true,
						"scrollX": true,
						"language": {
							"emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data pada semester/TA yang dipilih</p>"
						},
						"buttons": [
							{ extend: 'copy', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-copy"></i> Copy' },
							{ extend: 'csv', className: 'btn btn-info btn-sm text-white', text: 'CSV' },
							{ extend: 'excel', className: 'btn btn-success btn-sm', text: 'Excel' },
							{ extend: 'pdf', className: 'btn btn-danger btn-sm', text: 'PDF' },
							{ extend: 'print', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-print"></i> Cetak' }
						]
					}).buttons().container().appendTo('#tabel-pembicara_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':
			include "form/pembicara/edit.php";
			break;

		case 'anggota':
			include "anggota/pembicara.php";
			break;

		case 'validasi':
			include "validasi/pembicara.php";
			break;

	}
}