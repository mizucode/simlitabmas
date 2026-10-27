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
			<div class='row'>
				<div class='col-md-12'>
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Artikel Jurnal</h3>
							<div class="card-tools">
								<?php
								$q_pending = mysqli_query($con, "SELECT COUNT(DISTINCT judul) as jml FROM penelitian_jurnal WHERE (status='1' OR status IS NULL OR status='') AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end'");
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
									<i class="fas fa-plus"></i> Tambah
								</button>
							</div>
						</div>
						<div class="card-body p-3">
							<table id="tabel-jurnal" class="table table-bordered table-hover table-striped text-nowrap w-100" style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th>Tim Jurnal</th>
										<th>Ketua</th>
										<th>Jenis</th>
										<th>Judul Artikel</th>
										<th>Nama Jurnal</th>
										<th>File</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT p1.*, p1.id AS id_jurnal, IFNULL(dosen.name_glr, mhs.name) AS name_glr FROM penelitian_jurnal p1 INNER JOIN (SELECT MIN(id) as min_id FROM penelitian_jurnal WHERE status='2' AND tgl_terbit>='$tgl_start' AND tgl_terbit<='$tgl_end' GROUP BY judul) p2 ON p1.id = p2.min_id LEFT JOIN dosen ON p1.nip_dosen=dosen.nik LEFT JOIN mhs ON p1.nip_dosen=mhs.nim ORDER BY p1.id DESC");
									if (mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {
											$id_jenis = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_jenis']}' AND kelompok='jurnal' LIMIT 1"));

											$q_regis_cnt = mysqli_query($con, "SELECT COUNT(*) as total FROM regis_dosen_penelitian WHERE id_porto='{$r['id_jurnal']}' AND jenis_porto='jurnal'");
											$d_regis_cnt = mysqli_fetch_array($q_regis_cnt);
											$jml_dosen = $d_regis_cnt ? (int) $d_regis_cnt['total'] : 0;
											?>
											<tr>
												<td class="align-middle text-center"><?= $no ?></td>
												<td class="align-middle text-center">
													<a href="/penelitian/jurnal/anggota/<?= $r['id_jurnal'] ?>" class="badge badge-info p-2 px-3"
														style="font-size: 0.9rem;">
														<i class="fas fa-users mr-1"></i> <?= $jml_dosen ?> Anggota
													</a>
												</td>
												<td class="align-middle">
													<span class="text-dark"><?= $r['name_glr'] ?></span>
												</td>
												<td class="align-middle text-center"><?= $id_jenis ? $id_jenis['desc'] : '' ?></td>
												<td class="align-middle" style="min-width: 250px;">
													<div class='text-dark text-wrap' style='display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;' title='<?= htmlspecialchars($r['judul'], ENT_QUOTES) ?>'>
														<?= htmlspecialchars($r['judul']) ?>
													</div>
												</td>
												<td class="align-middle text-center"><?= $r['nama_jurnal'] ?></td>
												<td class="align-middle text-center">
													<?php
													$judul_esc = mysqli_real_escape_string($con, $r['judul']);
													$qfile_pivot = mysqli_query($con, "
										SELECT f.url as url_file, kode.desc as type_file, IFNULL(d.name_glr, m.name) AS name_glr
										FROM file_dosen f
										LEFT JOIN dosen d ON f.nip_dosen = d.nik
										LEFT JOIN mhs m ON f.nip_dosen = m.nim
										LEFT JOIN kode ON f.value_kode = kode.value AND kode.kelompok = 'file_jurnal'
										WHERE f.id_cat IN (SELECT id FROM penelitian_jurnal WHERE judul='$judul_esc') AND f.kelompok = 'file_jurnal'
									");

													$jml_file = mysqli_num_rows($qfile_pivot);

													if ($jml_file > 0) {
														?>
														<button type="button" class="btn btn-xs btn-outline-info shadow-sm" data-toggle="modal"
															data-target="#modalFile<?= $r['id_jurnal'] ?>">
															<i class="fas fa-folder-open mr-1"></i> <?= $jml_file ?> File
														</button>

														<!-- Modal daftar file -->
														<div class="modal fade text-left" id="modalFile<?= $r['id_jurnal'] ?>" tabindex="-1" role="dialog"
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
													<a href="/penelitian/jurnal/anggota/<?= $r['id_jurnal'] ?>" class="btn btn-xs btn-info shadow-sm">Lihat
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

					<?php include "form/jurnal/tambah.php"; ?>

				</div>
			</div>

			<script>
				$(function () {
					$('.select2bs4').select2({
						theme: 'bootstrap4'
					});
					$("#tabel-jurnal").DataTable({
						"responsive": true,
						"lengthChange": true,
						"autoWidth": false,
						"info": true,
						"searching": true,
						"scrollX": true,
						"language": {
							"emptyTable": "<p class='text-center text-danger mb-0'>Tidak ada data jurnal</p>"
						},
						"buttons": [
							{ extend: 'copy', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-copy"></i> Copy' },
							{ extend: 'csv', className: 'btn btn-info btn-sm text-white', text: 'CSV' },
							{ extend: 'excel', className: 'btn btn-success btn-sm', text: 'Excel' },
							{ extend: 'pdf', className: 'btn btn-danger btn-sm', text: 'PDF' },
							{ extend: 'print', className: 'btn btn-secondary btn-sm', text: '<i class="fas fa-print"></i> Cetak' }
						]
					}).buttons().container().appendTo('#tabel-jurnal_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':
			include "form/jurnal/edit.php";
			break;

		case 'anggota':
			include "anggota/jurnal.php";
			break;

		case 'validasi':
			include "validasi/jurnal.php";
			break;

	}
}
?>