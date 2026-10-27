<?php

ob_start();

if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level'])) {
	header('location:/login');
} else {

	switch (@$folder[3]) {

		default:

			// Tentukan rentang tanggal (TA) yang aktif
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

			<!-- ============================== -->
			<!-- Filter Tahun Ajaran (TA)        -->
			<!-- ============================== -->
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-body">
							<select onchange="window.document.location.href=this.options[this.selectedIndex].value;" class="form-control"
								name="ta">
								<option value="/penelitian/<?php echo $folder[2]; ?>">-- Pilih TA --</option>
								<?php
								$qk = mysqli_query($con, "SELECT thn,semester,kode,tgl_start,tgl_end FROM ts WHERE active!='u' ORDER BY kode DESC");
								while ($k = mysqli_fetch_array($qk)) {
									$selected = ($k['kode'] == @$folder[3]) ? 'selected' : '';
									echo "
                                        <option value='/penelitian/{$folder[2]}/{$k['kode']}' $selected>{$k['thn']} {$k['semester']} ({$k['tgl_start']} s.d {$k['tgl_end']})</option>
                                    ";
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>

			<?php if (!empty($folder[3])) { ?>
				<!--
								============================== 
								Filter Program Studi (dinonaktifkan)
								============================== 
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
															echo "
                                    <option value='/penelitian/{$folder[2]}/{$folder[3]}/{$p['kode']}' $selected>{$p['nama']} ({$p['singkatan']})</option>
                                ";
														}
														?>
																</select>
														</div>
												</div>
										</div>
								</div>
								-->
			<?php } ?>

			<!-- ============================== -->
			<!-- Tabel Paten dan Hak Kekayaan Intelektual -->
			<!-- ============================== -->
			<div class="row">
				<div class="col-md-12">
					<div class="card card-outline card-danger">
						<div class="card-header">
							<h3 class="card-title">Paten dan Hak Kekayaan Intelektual</h3>
							<div class="card-tools">
								<?php
								// Hitung data yang masih pending validasi
								$q_pending = mysqli_query($con, "SELECT COUNT(DISTINCT judul) as jml FROM penelitian_patenhki WHERE (status='1' OR status IS NULL OR status='') AND tgl_paten>='$tgl_start' AND tgl_paten<='$tgl_end'");
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
									<i class="fas fa-plus"></i> Tambah Paten
								</button>
							</div>
						</div>

						<div class="card-body p-3">
							<table id="tabel-patenhki" class="table table-bordered table-hover table-striped text-nowrap w-100"
								style="font-size: 0.9rem;">
								<thead class="bg-white text-center align-middle">
									<tr>
										<th class="align-middle" style="width: 50px;">No</th>
										<th>Tim HKI</th>
										<th>Ketua</th>
										<th>Jenis</th>
										<th>Nama</th>
										<th>Nomor</th>
										<th>TKT</th>
										<th>Tanggal</th>
										<th>File</th>
										<th class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$no = 1;
									$q = mysqli_query($con, "SELECT p1.*, IFNULL(dosen.name_glr, mhs.name) AS name_glr FROM penelitian_patenhki p1 INNER JOIN (SELECT MIN(id) as min_id FROM penelitian_patenhki WHERE status='2' AND tgl_paten>='$tgl_start' AND tgl_paten<='$tgl_end' GROUP BY judul) p2 ON p1.id = p2.min_id LEFT JOIN dosen ON p1.nip_dosen=dosen.nik LEFT JOIN mhs ON p1.nip_dosen=mhs.nim ORDER BY p1.id_jenis, p1.judul");

									if (mysqli_num_rows($q) > 0) {
										while ($r = mysqli_fetch_array($q)) {

											$id_jenis = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_jenis']}' AND kelompok='patenhki' LIMIT 1"));
											$id_tkt = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM kode WHERE value='{$r['id_tkt']}' AND kelompok='tkt' LIMIT 1"));

											// Cek jumlah dosen di tabel regis_dosen_penelitian
											$q_regis_cnt = mysqli_query($con, "SELECT COUNT(*) as total FROM regis_dosen_penelitian WHERE id_porto='{$r['id']}' AND jenis_porto='patenhki'");
											$d_regis_cnt = mysqli_fetch_array($q_regis_cnt);
											$jml_dosen = $d_regis_cnt ? (int) $d_regis_cnt['total'] : 0;
											?>
											<tr>
												<td class="align-middle"><?= $no ?></td>
												<td class="align-middle text-center">
													<a href="/penelitian/patenhki/anggota/<?= $r['id'] ?>" class="badge badge-info p-2 px-3"
														style="font-size: 0.9rem;">
														<i class="fas fa-users mr-1"></i> <?= $jml_dosen ?> Anggota
													</a>
												</td>
												<td class="align-middle">
													<span class="text-dark"><?= $r['name_glr'] ?></span>
												</td>
												<td class="align-middle text-center"><?= $id_jenis ? $id_jenis['desc'] : '' ?></td>
												<td class="align-middle">
													<span class="text-dark d-inline-block text-truncate" style="max-width: 250px; line-height: 1.4;"
														title="<?= htmlspecialchars($r['judul']) ?>">
														<?= $r['judul'] ?>
													</span>
												</td>
												<td class="align-middle text-center"><?= $r['nomor'] ?></td>
												<td class="align-middle text-center"><?= $id_tkt ? $id_tkt['desc'] : '' ?></td>
												<td class="align-middle text-center"><?= $r['tgl_paten'] ?></td>
												<td class="align-middle text-center">
													<?php
													$judul_esc = mysqli_real_escape_string($con, $r['judul']);
													// Ambil daftar file lampiran untuk paten ini
													$qfile_pivot = mysqli_query($con, "
                                                        SELECT f.url as url_file, f.value_kode as type_file, IFNULL(d.name_glr, m.name) AS name_glr
                                                        FROM file_dosen f
                                                        LEFT JOIN dosen d ON f.nip_dosen = d.nik
                                                        LEFT JOIN mhs m ON f.nip_dosen = m.nim
                                                        WHERE f.id_cat IN (SELECT id FROM penelitian_patenhki WHERE judul='$judul_esc') AND f.kelompok = 'file_patenhki'
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
																				<thead class="bg-light">
																				<tr>
																					<th>Nama Dokumen</th>
																					<th>Keterangan</th>
																					<th class="text-center" style="width: 80px;">Aksi</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				// Daftar file dari tabel pivot
																				while ($rf = mysqli_fetch_array($qfile_pivot)) {
																					$type = !empty($rf['type_file']) ? $rf['type_file'] : 'Dokumen Pendukung';
																					?>
																					<tr>
																						<td class="align-middle"><?= $type ?></td>
																						<td class="align-middle"><?= $rf['name_glr'] ?></td>
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
													<a href="/penelitian/patenhki/anggota/<?= $r['id'] ?>" class="btn btn-xs btn-info shadow-sm">Lihat
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

					<?php include "form/patenhki/tambah.php"; ?>

				</div>
			</div>

			<script>
				$(function () {
					$("#tabel-patenhki").DataTable({
						"responsive": true,
						"lengthChange": true,
						"autoWidth": false,
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
					}).buttons().container().appendTo('#tabel-patenhki_wrapper .col-md-6:eq(0)');
				});
			</script>
			<?php
			break;

		case 'edit':
			include "form/patenhki/edit.php";
			break;

		case 'anggota':
			include "anggota/patenhki.php";
			break;

		case 'validasi':
			include "validasi/patenhki.php";
			break;


	}
}