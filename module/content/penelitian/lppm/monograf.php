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

			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Monograf (Disetujui)</h3>
							<div class="card-tools">
								<?php
								try {
									$q_pending = mysqli_query($con, "SELECT COUNT(DISTINCT pt.id) as jml FROM penelitian_monograf pt LEFT JOIN dosen d ON (pt.nip_dosen=d.nik OR pt.nip_dosen=d.nip OR pt.nip_dosen=d.nidn) LEFT JOIN regis_dosen_penelitian r ON (pt.id=r.id_porto AND r.jenis_porto='monograf') LEFT JOIN dosen d2 ON (r.nik=d2.nik OR r.nik=d2.nip OR r.nik=d2.nidn) WHERE (pt.status='1' OR pt.status IS NULL OR pt.status='') AND pt.tgl_terbit>='$tgl_start' AND pt.tgl_terbit<='$tgl_end' AND ((d.name_glr IS NOT NULL AND d.name_glr != '') OR (d.name IS NOT NULL AND d.name != '') OR (d2.name_glr IS NOT NULL AND d2.name_glr != '') OR (d2.name IS NOT NULL AND d2.name != ''))");
									$r_pending = mysqli_fetch_assoc($q_pending);
									$jml_pending = $r_pending ? $r_pending['jml'] : 0;
								} catch (Throwable $e) {
									$jml_pending = 0;
								}
								?>
								<a href="/penelitian/<?= $folder[2] ?>/validasi/<?= @$folder[3] ?>" class="btn btn-info">
									Perlu Validasi
									<?php if ($jml_pending > 0) { ?>
										<span class="badge badge-danger"><?= $jml_pending ?></span>
									<?php } ?>
								</a>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah">
									<i class="fas fa-plus"></i> Tambah Monograf
								</button>
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-monograf" class="table table-bordered table-hover table-striped text-nowrap w-100"
								style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th>Tim Penulis</th>
										<th>Penulis Utama</th>
										<th>Judul Monograf</th>
										<th>Penerbit</th>
										<th>ISBN</th>
										<th>Tanggal Terbit</th>
										<th>File</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT penelitian_monograf.*, penelitian_monograf.id AS id_monograf, dosen.name_glr FROM penelitian_monograf LEFT JOIN dosen ON (penelitian_monograf.nip_dosen=dosen.nik OR penelitian_monograf.nip_dosen=dosen.nip OR penelitian_monograf.nip_dosen=dosen.nidn) WHERE tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' AND penelitian_monograf.status='2' ORDER BY penelitian_monograf.id DESC");
									if (mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {

											// Dapatkan jumlah tim penulis
											$q_regis_cnt = mysqli_query($con, "SELECT COUNT(*) as total FROM regis_dosen_penelitian WHERE id_porto='{$r['id_monograf']}' AND jenis_porto='monograf'");
											$d_regis_cnt = mysqli_fetch_array($q_regis_cnt);
											$jml_dosen = $d_regis_cnt ? (int) $d_regis_cnt['total'] : 0;

											// Fallback pencarian nama dosen dari tabel dosen (name_glr / name)
											$nama_dosen = $r['name_glr'] ?? '';
											if (empty($nama_dosen)) {
												if (!empty($r['nip_dosen'])) {
													$qd = mysqli_query($con, "SELECT name_glr, name FROM dosen WHERE nik='{$r['nip_dosen']}' OR nip='{$r['nip_dosen']}' OR nidn='{$r['nip_dosen']}' LIMIT 1");
													if ($dd = mysqli_fetch_array($qd)) {
														$nama_dosen = !empty($dd['name_glr']) ? $dd['name_glr'] : $dd['name'];
													}
												}
												if (empty($nama_dosen)) {
													$qp = mysqli_query($con, "SELECT d.name_glr, d.name FROM regis_dosen_penelitian r JOIN dosen d ON (r.nik=d.nik OR r.nik=d.nip OR r.nik=d.nidn) WHERE r.id_porto='{$r['id_monograf']}' AND r.jenis_porto='monograf' ORDER BY r.role ASC LIMIT 1");
													if ($dp = mysqli_fetch_array($qp)) {
														$nama_dosen = !empty($dp['name_glr']) ? $dp['name_glr'] : $dp['name'];
													}
												}
											}

											if (empty($nama_dosen)) {
												continue;
											}
											?>
											<tr>
												<td class="align-middle text-center"><?= $no ?></td>
												<td class="align-middle text-center">
													<a href="/penelitian/monograf/anggota/<?= $r['id_monograf'] ?>" class="badge badge-info p-2 px-3"
														style="font-size: 0.9rem;">
														<i class="fas fa-users mr-1"></i> <?= $jml_dosen ?> Dosen
													</a>
												</td>
												<td class="align-middle"><?= htmlspecialchars($nama_dosen) ?></td>
												<td class="align-middle" style="min-width: 250px;">
													<div class="text-dark text-wrap"
														style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
														title="<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>">
														<?= htmlspecialchars($r['judul']) ?>
													</div>
												</td>
												<td class="align-middle" style="min-width: 150px;">
													<div class="text-dark text-wrap"
														style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
														title="<?= htmlspecialchars($r['penerbit'] ?? '', ENT_QUOTES) ?>">
														<?= htmlspecialchars($r['penerbit'] ?? '-') ?>
													</div>
												</td>
												<td class="align-middle text-center"><?= htmlspecialchars($r['isbn'] ?? '-') ?></td>
												<td class="align-middle text-center"><?= htmlspecialchars($r['tgl_terbit'] ?? '-') ?></td>
												<td class="align-middle text-center">
													<?php
													$qfile_pivot = mysqli_query($con, "
														SELECT DISTINCT f.url as url_file, kode.desc as type_file, d.name_glr
														FROM file_dosen f
														LEFT JOIN dosen d ON f.nip_dosen = d.nik
														LEFT JOIN kode ON f.value_kode = kode.value AND kode.kelompok = 'file_monograf'
														WHERE f.id_cat = '{$r['id_monograf']}' AND f.kelompok = 'file_monograf'
													");

													$jml_file = mysqli_num_rows($qfile_pivot);

													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal"
															data-target="#modalFileMonograf<?= $r['id_monograf'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>

														<!-- Modal daftar file -->
														<div class="modal fade text-left" id="modalFileMonograf<?= $r['id_monograf'] ?>" tabindex="-1"
															role="dialog" aria-hidden="true">
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
																						$file_url = (strpos($rf['url_file'], 'http') === 0) ? $rf['url_file'] : (isset($storage_url) ? $storage_url . ltrim($rf['url_file'], '/') : '/' . ltrim($rf['url_file'], '/'));
																						?>
																						<tr>
																							<td class="align-middle"><?= htmlspecialchars($type) ?></td>
																							<td class="align-middle text-center"><?= htmlspecialchars($rf['name_glr']) ?></td>
																							<td class="text-center align-middle">
																								<a href="<?= htmlspecialchars($file_url) ?>" target="_blank"
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
													<a href="/penelitian/monograf/anggota/<?= $r['id_monograf'] ?>"
														class="btn btn-xs btn-info shadow-sm">Lihat Detail</a>
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

					<?php include "form/monograf/tambah.php"; ?>

				</div>
			</div>
			<script>
				$(function () {
					$("#tabel-monograf").DataTable({
						"responsive": true,
						"lengthChange": true,
						"autoWidth": false,
						"info": true,
						"searching": true,
						"scrollX": true,
						"language": {
							"emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data monograf yang disetujui</p>"
						},
						"buttons": [
							{ extend: 'copy', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-copy"></i> Copy' },
							{ extend: 'csv', className: 'btn btn-info btn-sm text-white', text: 'CSV' },
							{ extend: 'excel', className: 'btn btn-success btn-sm', text: 'Excel' },
							{ extend: 'pdf', className: 'btn btn-danger btn-sm', text: 'PDF' },
							{ extend: 'print', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-print"></i> Cetak' }
						]
					}).buttons().container().appendTo('#tabel-monograf_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':
			include "form/monograf/edit.php";
			break;

		case 'anggota':
			include "anggota/monograf.php";
			break;

		case 'validasi':
			include "validasi/monograf.php";
			break;

	}
}
?>