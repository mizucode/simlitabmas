<?php 

ob_start();	

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
	header('location:/login');
}
else {
	switch(@$folder[3]) {
		default :
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
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class='form-control' name='ta'>
								<option value='/pengabdian/<?php echo $folder[2]; ?>'>-- Pilih TA --</option>
								<?php
								$qk = mysqli_query($con, "SELECT * FROM ts WHERE active!='u' ORDER BY kode DESC");
								while ($k = mysqli_fetch_array($qk)) {
									$selected = ($k['kode'] == $folder[3]) ? 'selected' : '';
									?>
									<option value='/pengabdian/<?= $folder[2] ?>/<?= $k['kode'] ?>' <?= $selected ?>><?= $k['thn'] ?> <?= $k['semester'] ?> (<?= $k['tgl_start'] ?> s.d <?= $k['tgl_end'] ?>)</option>
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
							<h3 class="card-title">Jabatan di Luar Kampus</h3>
							<div class="card-tools">
								<?php
								$q_pending = mysqli_query($con, "SELECT COUNT(*) as jml FROM pengabdian_jabatan WHERE (status='1' OR status IS NULL OR status='') AND (tmt>='$tgl_start' AND tmt<='$tgl_end' OR tmt IS NULL OR CAST(tmt AS CHAR)='0000-00-00')");
								$r_pending = mysqli_fetch_assoc($q_pending);
								$jml_pending = $r_pending ? $r_pending['jml'] : 0;
								?>
								<a href="/pengabdian/<?= $folder[2] ?>/validasi/<?= @$folder[3] ?>" class="btn btn-info mr-1">
									Perlu Validasi
									<?php if ($jml_pending > 0) { ?>
										<span class="badge badge-danger"><?= $jml_pending ?></span>
									<?php } ?>
								</a>
								<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#tambah"><i class="fas fa-plus"></i> Tambah</button>
							</div>														
						</div>
						<div class="card-body p-3">
							<table id="tabel-jabatan" class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th class="align-middle">Nama Dosen</th>
										<th class="align-middle">Kategori Jabatan & No. SK</th>
										<th class="align-middle">Instansi / Lokasi</th>
										<th class="align-middle">Terhitung Mulai (TMT)</th>
										<th class="align-middle">File</th>
										<th class="text-center align-middle">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT *, pengabdian_jabatan.id AS main_id FROM pengabdian_jabatan LEFT JOIN dosen ON pengabdian_jabatan.nip_dosen=dosen.nik WHERE pengabdian_jabatan.status='2' AND (tmt>='$tgl_start' AND tmt<='$tgl_end' OR tmt IS NULL OR CAST(tmt AS CHAR)='0000-00-00') ORDER BY pengabdian_jabatan.id DESC");
									if ($q && mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {
											$kategori_jabatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_jabatan']}' AND kelompok='jabatan_pub' LIMIT 1"));
											$kat_jab_desc = $kategori_jabatan ? $kategori_jabatan['desc'] : '-';
											$nama_dosen = !empty($r['name_glr']) ? $r['name_glr'] : ($r['nip_dosen'] ?? '-');
											?>
											<tr>
												<td class="align-middle text-center"><?= $no ?></td>
												<td class="align-middle">
													<span class="text-dark font-weight-bold"><?= htmlspecialchars((string) $nama_dosen) ?></span>
												</td>
												<td class="align-middle">
													<span class="text-dark d-inline-block text-truncate" style="max-width: 300px; line-height: 1.4;" title="<?= htmlspecialchars((string) $kat_jab_desc) ?>">
														<?= htmlspecialchars((string) $kat_jab_desc) ?>
													</span>
													<?php if (!empty($r['no_sk'])) { ?>
														<br><small class="text-muted d-inline-block text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars((string) $r['no_sk']) ?>">
															SK: <?= htmlspecialchars((string) $r['no_sk']) ?>
														</small>
													<?php } ?>
												</td>
												<td class="align-middle">
													<span class="text-dark"><?= htmlspecialchars((string) $r['lokasi']) ?></span>
												</td>
												<td class="align-middle text-center">
													<span class="text-dark"><?= htmlspecialchars((string) $r['tmt']) ?></span>
												</td>
												<td class="align-middle text-center">
													<?php
													$qfile_pivot = mysqli_query($con, "
														SELECT DISTINCT f.id, f.url as url_file, kode.desc as type_file, d.name_glr
														FROM file_dosen f
														LEFT JOIN dosen d ON f.nip_dosen = d.nik
														LEFT JOIN kode ON f.value_kode = kode.value AND kode.kelompok = 'file_jabatan'
														WHERE f.id_cat = '{$r['main_id']}' AND f.kelompok = 'file_jabatan'
													");
													$jml_file = ($qfile_pivot) ? mysqli_num_rows($qfile_pivot) : 0;
													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal" data-target="#modalFile<?= $r['main_id'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>
														<div class="modal fade text-left" id="modalFile<?= $r['main_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
																						$file_url = (strpos($rf['url_file'], 'http') === 0) ? $rf['url_file'] : $storage_url . $rf['url_file'];
																						?>
																						<tr>
																							<td class="align-middle"><?= htmlspecialchars((string) $type) ?></td>
																							<td class="align-middle text-center"><?= htmlspecialchars((string) $rf['name_glr']) ?></td>
																							<td class="text-center align-middle">
																								<a href="<?= $file_url ?>" target="_blank" class="btn btn-xs btn-primary">
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
													<a href="/pengabdian/jabatan/anggota/<?= $r['main_id'] ?>" class="btn btn-xs btn-info shadow-sm"><i class="fas fa-eye mr-1"></i> Detail</a>
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
					
					<?php include "form/jabatan/tambah.php"; ?>

				</div>
			</div>

			<script>
				$(function () {
					$("#tabel-jabatan").DataTable({
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
					}).buttons().container().appendTo('#tabel-jabatan_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php		
			break;
		
		case 'edit':
			include "form/jabatan/edit.php";
			break;

		case 'anggota':
			include "anggota/jabatan.php";
			break;

		case 'validasi':
			include "validasi/jabatan.php";
			break;

	}
}