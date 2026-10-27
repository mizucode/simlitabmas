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
							<h3 class="card-title">Kegiatan Pengabdian</h3>
							<div class="card-tools">

								<?php
								$q_pending = mysqli_query($con, "SELECT COUNT(*) as jml FROM pengabdian_pengabdian WHERE (status='1' OR status IS NULL OR status='') AND tgl_mulai_kegiatan>='$tgl_start' AND tgl_mulai_kegiatan<='$tgl_end'");
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
										class="fas fa-plus"></i> Tambah Pengabdian</button>
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-pengabdian"
								class="table table-bordered table-hover table-striped text-nowrap w-100"
								style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th>Tim Pengabdi</th>
										<th>Ketua</th>
										<th>Judul Kegiatan</th>
										<th>Skema</th>
										<th>File</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT *, pengabdian_pengabdian.id AS id_pengabdian FROM pengabdian_pengabdian,dosen WHERE pengabdian_pengabdian.nip_dosen=dosen.nik AND pengabdian_pengabdian.status='2' AND tgl_mulai_kegiatan>='$tgl_start' AND tgl_mulai_kegiatan<='$tgl_end' ORDER BY pengabdian_pengabdian.id_skema,pengabdian_pengabdian.judul_kegiatan ");
									if ($q && mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {
											$skema = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_skema']}' AND kelompok='skema_pengabdian' LIMIT 1"));

											// Auto-heal jika ketua belum terdaftar di pivot regis_dosen_penelitian
											$nip_pem = mysqli_real_escape_string($con, $r['nip_dosen'] ?? '');
											if (!empty($nip_pem)) {
												$cek_main = mysqli_query($con, "SELECT id FROM regis_dosen_penelitian WHERE id_porto='{$r['id_pengabdian']}' AND nik='$nip_pem' AND (jenis_porto='Pengabdian' OR jenis_porto='pengabdian')");
												if (mysqli_num_rows($cek_main) == 0) {
													$tgl_reg = date('Y-m-d');
													$jam_reg = date('H:i:s');
													$ses_user = mysqli_real_escape_string($con, @$_SESSION['ses_user']);
													$role_main = !empty($r['id_peran']) ? $r['id_peran'] : '1';
													mysqli_query($con, "INSERT INTO regis_dosen_penelitian (id_porto, nik, role, jenis_porto, date_regis, time_regis, registered_by) VALUES ('{$r['id_pengabdian']}', '$nip_pem', '$role_main', 'Pengabdian', '$tgl_reg', '$jam_reg', '$ses_user')");
												}
											}

											// Cek jumlah dosen di tabel regis_dosen_penelitian
											$q_regis_cnt = mysqli_query($con, "SELECT COUNT(*) as total FROM regis_dosen_penelitian WHERE id_porto='{$r['id_pengabdian']}' AND (jenis_porto='Pengabdian' OR jenis_porto='pengabdian')");
											$d_regis_cnt = mysqli_fetch_array($q_regis_cnt);
											$jml_dosen = $d_regis_cnt ? (int) $d_regis_cnt['total'] : 0;
											?>
											<tr>
												<td class="align-middle"><?= $no ?></td>

												<td class="align-middle text-center">
													<a href="/pengabdian/pengabdian/anggota/<?= $r['id_pengabdian'] ?>" class="badge badge-info p-2 px-3"
														style="font-size: 0.9rem;">
														<i class="fas fa-users mr-1"></i> <?= $jml_dosen ?> Dosen
													</a>
												</td>
												<td class="align-middle">
													<span class="text-dark">
														<?= htmlspecialchars((string)$r['name_glr']) ?>
													</span>
												</td>
												<td class="align-middle">
													<span class="text-dark d-inline-block text-truncate" style="max-width: 350px; line-height: 1.4;"
														title="<?= htmlspecialchars((string)$r['judul_kegiatan']) ?>">
														<?= htmlspecialchars((string)$r['judul_kegiatan']) ?>
													</span>
												</td>
												<td class="align-middle text-center">
													<span class="text-dark"><?= htmlspecialchars((string)($skema['desc'] ?? '-')) ?></span>
												</td>
												<td class="align-middle text-center">
													<?php
													$qfile_pivot = mysqli_query($con, "
														SELECT DISTINCT f.id, f.url as url_file, f.value_kode, kode.desc as type_file_desc, d.name_glr 
														FROM file_dosen f 
														LEFT JOIN dosen d ON f.nip_dosen = d.nik 
														LEFT JOIN kode ON (f.value_kode = kode.value OR f.value_kode = kode.desc) AND kode.kelompok = 'file_pengabdian'
														WHERE f.id_cat = '{$r['id_pengabdian']}' AND f.kelompok = 'file_pengabdian'
													");

													$jml_file = $qfile_pivot ? mysqli_num_rows($qfile_pivot) : 0;

													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal"
															data-target="#modalFile<?= $r['id_pengabdian'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>

														<!-- Modal -->
														<div class="modal fade text-left" id="modalFile<?= $r['id_pengabdian'] ?>" tabindex="-1" role="dialog"
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
																					$type = !empty($rf['type_file_desc']) ? $rf['type_file_desc'] : (!empty($rf['value_kode']) ? $rf['value_kode'] : 'Dokumen Pendukung');
																					$file_url = (strpos($rf['url_file'], 'http') === 0) ? $rf['url_file'] : $storage_url . $rf['url_file'];
																					?>
																					<tr>
																						<td class="align-middle"><?= htmlspecialchars((string)$type) ?></td>
																						<td class="align-middle"><?= htmlspecialchars((string)$rf['name_glr']) ?></td>
																						<td class="text-center align-middle">
																							<a href="<?= $file_url ?>" target="_blank"
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
													<a href="/pengabdian/pengabdian/anggota/<?= $r['id_pengabdian'] ?>" class="btn btn-xs btn-info shadow-sm">Lihat
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

					<?php include "form/pengabdian/tambah.php"; ?>

				</div>
			</div>
			<script>
				$(function () {
					$("#tabel-pengabdian").DataTable({
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
					}).buttons().container().appendTo('#tabel-pengabdian_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':
			include "form/pengabdian/edit.php";
			break;

		case 'anggota':
			include "anggota/pengabdian.php";
			break;

		case 'validasi':
			include "validasi/pengabdian.php";
			break;

	}
}