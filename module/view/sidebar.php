<a href="#" class="brand-link bg-navy">
	<img src="/dist/img/logo_univ_med.png" alt="Logo" class="brand-image opacity-75 shadow" />
	<span class="brand-text fw-bold text-light"><b>SIMLITABMAS</b></span>
</a>

<div class="sidebar">

	<div class="user-panel mt-3 pb-3 mb-3 d-flex">
		<div class="pull-left info">
			<a href="#" class="d-block">
				<?php echo $_SESSION['ses_name']; ?>
			</a>
		</div>
	</div>

	<?php
	switch ($_SESSION['ses_level']) {
		case 'lppm':
			?>
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat text-sm" data-widget="treeview"
					role="menu" data-accordion="false">
					<li class="nav-header">LAYANAN</li>
					<li class='nav-item'>
						<a href='/dashboard' class='nav-link'><i class='nav-icon fa fa-home'></i>
							<p>Dashboard</p><small class='label pull-right bg-red'></small>
						</a>
					</li>
					<li class='nav-item'>
						<a href='/account' class='nav-link'><i class='nav-icon fa fa-user-cog'></i>
							<p>Account</p><small class='label pull-right bg-red'></small>
						</a>
					</li>
					<li class='nav-item'>
						<a href='/dosen' class='nav-link'><i class='nav-icon fa fa-users'></i>
							<p>Dosen</p><small class='label pull-right bg-red'></small>
						</a>
					</li>
					<li class='nav-item has-treeview'>
						<a href='/kkn' class='nav-link'><i class='nav-icon fa fa-clipboard'></i>
							<p>KKN-DIK</p><small class='label pull-right bg-red'></small>
							<i class='fas fa-angle-left right'></i>
						</a>
						<ul class='nav nav-treeview'>
							<li class='nav-item'>
								<a href='/kkn/peserta' class='nav-link'><i class='nav-icon fa fa-circle'></i>Peserta</a>
							</li>
							<li class='nav-item'>
								<a href='/kkn/luaran' class='nav-link'><i class='nav-icon fa fa-circle'></i>Luaran</a>
							</li>
							<li class='nav-item'>
								<a href='/kkn/nilai' class='nav-link'><i class='nav-icon fa fa-circle'></i>Nilai</a>
							</li>
						</ul>
					</li>

					<li class='nav-item'>
						<a href='/repository' class='nav-link'><i class='nav-icon fa fa-file'></i>
							<p>Repository</p><small class='label pull-right bg-red'></small>
						</a>
					</li>

					<li class='nav-item'>
						<a href='/kegiatan/4' class='nav-link'><i class='nav-icon fa fa-file-alt'></i>
							<p>Seminar Proposal</p><small class='label pull-right bg-red'></small>
						</a>
					</li>
					<li class='nav-item'>
						<a href='/kegiatan/5' class='nav-link'><i class='nav-icon fa fa-star'></i>
							<p>Sidang Skripsi</p><small class='label pull-right bg-red'></small>
						</a>
					</li>
		<li class='nav-item'>
			<a href='/ppm/penelitian' class='nav-link'><i class='nav-icon fa fa-star'></i> <p>Hibah Penelitian</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item'>
			<a href='/ppm/pengabdian' class='nav-link'><i class='nav-icon fa fa-star'></i> <p>Hibah Pengabdian</p><small class='label pull-right bg-red'></small></a>
		</li>

					<li class="nav-header">DATA MASTER</li>
					<li class='nav-item'>
						<a href='/referensi/jurnal' class='nav-link'><i class='nav-icon fa fa-book'></i>
							<p>Terbitan Jurnal</p>
						</a>
					</li>
					<li class='nav-item'>
						<a href='/referensi/klaster' class='nav-link'><i class='nav-icon fa fa-layer-group'></i>
							<p>Data Klaster</p>
						</a>
					</li>
					<li class="nav-header">LAPORAN KINERJA</li>
					<?php
					$q_pending = mysqli_query($con, "SELECT COUNT(id) AS jml FROM penelitian_penelitian WHERE status='1'");
					$d_pending = mysqli_fetch_assoc($q_pending);
					$jml_pending = $d_pending['jml'];
					
					$q_pending_paten = mysqli_query($con, "SELECT COUNT(DISTINCT judul) AS jml FROM penelitian_patenhki WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_paten = mysqli_fetch_assoc($q_pending_paten);
					$jml_pending_paten = $d_pending_paten['jml'];

					$q_pending_karya = mysqli_query($con, "SELECT COUNT(id) AS jml FROM penelitian_karya WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_karya = mysqli_fetch_assoc($q_pending_karya);
					$jml_pending_karya = $d_pending_karya['jml'];

					$q_pending_jurnal = mysqli_query($con, "SELECT COUNT(DISTINCT judul) AS jml FROM penelitian_jurnal WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_jurnal = mysqli_fetch_assoc($q_pending_jurnal);
					$jml_pending_jurnal = $d_pending_jurnal['jml'];

					$q_pending_prosiding = mysqli_query($con, "SELECT COUNT(DISTINCT judul) AS jml FROM penelitian_prosiding WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_prosiding = mysqli_fetch_assoc($q_pending_prosiding);
					$jml_pending_prosiding = $d_pending_prosiding['jml'];

					$q_pending_buku = mysqli_query($con, "SELECT COUNT(DISTINCT judul) AS jml FROM penelitian_buku WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_buku = mysqli_fetch_assoc($q_pending_buku);
					$jml_pending_buku = $d_pending_buku['jml'];

					$q_pending_chapter = mysqli_query($con, "SELECT COUNT(id) AS jml FROM penelitian_chapter WHERE status='1' OR status IS NULL OR status=''");
					$d_pending_chapter = mysqli_fetch_assoc($q_pending_chapter);
					$jml_pending_chapter = $d_pending_chapter['jml'];

					try {
						$q_pending_tulisan = mysqli_query($con, "SELECT COUNT(DISTINCT judul) AS jml FROM penelitian_tulisan WHERE status='1' OR status IS NULL OR status=''");
						$d_pending_tulisan = mysqli_fetch_assoc($q_pending_tulisan);
						$jml_pending_tulisan = $d_pending_tulisan ? $d_pending_tulisan['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_tulisan = 0;
					}

					try {
						$q_pending_monograf = mysqli_query($con, "SELECT COUNT(DISTINCT pt.id) AS jml FROM penelitian_monograf pt LEFT JOIN dosen d ON (pt.nip_dosen=d.nik OR pt.nip_dosen=d.nip OR pt.nip_dosen=d.nidn) LEFT JOIN regis_dosen_penelitian r ON (pt.id=r.id_porto AND r.jenis_porto='monograf') LEFT JOIN dosen d2 ON (r.nik=d2.nik OR r.nik=d2.nip OR r.nik=d2.nidn) WHERE (pt.status='1' OR pt.status IS NULL OR pt.status='') AND ((d.name_glr IS NOT NULL AND d.name_glr != '') OR (d.name IS NOT NULL AND d.name != '') OR (d2.name_glr IS NOT NULL AND d2.name_glr != '') OR (d2.name IS NOT NULL AND d2.name != ''))");
						$d_pending_monograf = mysqli_fetch_assoc($q_pending_monograf);
						$jml_pending_monograf = $d_pending_monograf ? $d_pending_monograf['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_monograf = 0;
					}

					try {
						$q_pending_penerjemahan = mysqli_query($con, "SELECT COUNT(DISTINCT pt.id) AS jml FROM penelitian_penerjemahan pt LEFT JOIN dosen d ON (pt.nip_dosen=d.nik OR pt.nip_dosen=d.nip OR pt.nip_dosen=d.nidn) LEFT JOIN regis_dosen_penelitian r ON (pt.id=r.id_porto AND r.jenis_porto='penerjemahan') LEFT JOIN dosen d2 ON (r.nik=d2.nik OR r.nik=d2.nip OR r.nik=d2.nidn) WHERE (pt.status='1' OR pt.status IS NULL OR pt.status='') AND ((d.name_glr IS NOT NULL AND d.name_glr != '') OR (d.name IS NOT NULL AND d.name != '') OR (d2.name_glr IS NOT NULL AND d2.name_glr != '') OR (d2.name IS NOT NULL AND d2.name != ''))");
						$d_pending_penerjemahan = mysqli_fetch_assoc($q_pending_penerjemahan);
						$jml_pending_penerjemahan = $d_pending_penerjemahan ? $d_pending_penerjemahan['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_penerjemahan = 0;
					}

					try {
						$q_pending_penyuntingan = mysqli_query($con, "SELECT COUNT(DISTINCT pt.id) AS jml FROM penelitian_penyuntingan pt LEFT JOIN dosen d ON (pt.nip_dosen=d.nik OR pt.nip_dosen=d.nip OR pt.nip_dosen=d.nidn) LEFT JOIN regis_dosen_penelitian r ON (pt.id=r.id_porto AND r.jenis_porto='penyuntingan') LEFT JOIN dosen d2 ON (r.nik=d2.nik OR r.nik=d2.nip OR r.nik=d2.nidn) WHERE (pt.status='1' OR pt.status IS NULL OR pt.status='') AND ((d.name_glr IS NOT NULL AND d.name_glr != '') OR (d.name IS NOT NULL AND d.name != '') OR (d2.name_glr IS NOT NULL AND d2.name_glr != '') OR (d2.name IS NOT NULL AND d2.name != ''))");
						$d_pending_penyuntingan = mysqli_fetch_assoc($q_pending_penyuntingan);
						$jml_pending_penyuntingan = $d_pending_penyuntingan ? $d_pending_penyuntingan['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_penyuntingan = 0;
					}

					try {
						$q_pending_non_pub = mysqli_query($con, "SELECT COUNT(DISTINCT pt.id) AS jml FROM penelitian_non_pub pt LEFT JOIN dosen d ON (pt.nip_dosen=d.nik OR pt.nip_dosen=d.nip OR pt.nip_dosen=d.nidn) LEFT JOIN regis_dosen_penelitian r ON (pt.id=r.id_porto AND r.jenis_porto='non_pub') LEFT JOIN dosen d2 ON (r.nik=d2.nik OR r.nik=d2.nip OR r.nik=d2.nidn) WHERE (pt.status='1' OR pt.status IS NULL OR pt.status='') AND ((d.name_glr IS NOT NULL AND d.name_glr != '') OR (d.name IS NOT NULL AND d.name != '') OR (d2.name_glr IS NOT NULL AND d2.name_glr != '') OR (d2.name IS NOT NULL AND d2.name != ''))");
						$d_pending_non_pub = mysqli_fetch_assoc($q_pending_non_pub);
						$jml_pending_non_pub = $d_pending_non_pub ? $d_pending_non_pub['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_non_pub = 0;
					}

					$total_pending = $jml_pending + $jml_pending_paten + $jml_pending_karya + $jml_pending_jurnal + $jml_pending_prosiding + $jml_pending_buku + $jml_pending_chapter + $jml_pending_tulisan + $jml_pending_monograf + $jml_pending_penerjemahan + $jml_pending_penyuntingan + $jml_pending_non_pub;
					
					$badge_total_pending = ($total_pending > 0) ? "<span class='badge badge-danger right' style='margin-right: 20px;'>{$total_pending}</span>" : "";
					$badge_pending_child = ($jml_pending > 0) ? "<span class='badge badge-danger right'>{$jml_pending}</span>" : "";
					$badge_pending_paten = ($jml_pending_paten > 0) ? "<span class='badge badge-danger right'>{$jml_pending_paten}</span>" : "";
					$badge_pending_karya = ($jml_pending_karya > 0) ? "<span class='badge badge-danger right'>{$jml_pending_karya}</span>" : "";
					$badge_pending_jurnal = ($jml_pending_jurnal > 0) ? "<span class='badge badge-danger right'>{$jml_pending_jurnal}</span>" : "";
					$badge_pending_prosiding = ($jml_pending_prosiding > 0) ? "<span class='badge badge-danger right'>{$jml_pending_prosiding}</span>" : "";
					$badge_pending_buku = ($jml_pending_buku > 0) ? "<span class='badge badge-danger right'>{$jml_pending_buku}</span>" : "";
					$badge_pending_chapter = ($jml_pending_chapter > 0) ? "<span class='badge badge-danger right'>{$jml_pending_chapter}</span>" : "";
					$badge_pending_tulisan = ($jml_pending_tulisan > 0) ? "<span class='badge badge-danger right'>{$jml_pending_tulisan}</span>" : "";
					$badge_pending_monograf = ($jml_pending_monograf > 0) ? "<span class='badge badge-danger right'>{$jml_pending_monograf}</span>" : "";
					$badge_pending_penerjemahan = ($jml_pending_penerjemahan > 0) ? "<span class='badge badge-danger right'>{$jml_pending_penerjemahan}</span>" : "";
					$badge_pending_penyuntingan = ($jml_pending_penyuntingan > 0) ? "<span class='badge badge-danger right'>{$jml_pending_penyuntingan}</span>" : "";
					$badge_pending_non_pub = ($jml_pending_non_pub > 0) ? "<span class='badge badge-danger right'>{$jml_pending_non_pub}</span>" : "";

					// Query Notifikasi Validasi Pengabdian
					try {
						$q_pending_abd_pengabdian = mysqli_query($con, "SELECT COUNT(id) AS jml FROM pengabdian_pengabdian WHERE status='1' OR status IS NULL OR status=''");
						$d_pending_abd_pengabdian = mysqli_fetch_assoc($q_pending_abd_pengabdian);
						$jml_pending_abd_pengabdian = $d_pending_abd_pengabdian ? $d_pending_abd_pengabdian['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_abd_pengabdian = 0;
					}

					try {
						$q_pending_abd_pembicara = mysqli_query($con, "SELECT COUNT(id) AS jml FROM pengabdian_pembicara WHERE status='1' OR status IS NULL OR status=''");
						$d_pending_abd_pembicara = mysqli_fetch_assoc($q_pending_abd_pembicara);
						$jml_pending_abd_pembicara = $d_pending_abd_pembicara ? $d_pending_abd_pembicara['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_abd_pembicara = 0;
					}

					try {
						$q_pending_abd_jabatan = mysqli_query($con, "SELECT COUNT(id) AS jml FROM pengabdian_jabatan WHERE status='1' OR status IS NULL OR status=''");
						$d_pending_abd_jabatan = mysqli_fetch_assoc($q_pending_abd_jabatan);
						$jml_pending_abd_jabatan = $d_pending_abd_jabatan ? $d_pending_abd_jabatan['jml'] : 0;
					} catch (Throwable $e) {
						$jml_pending_abd_jabatan = 0;
					}

					$total_pending_abd = $jml_pending_abd_pengabdian + $jml_pending_abd_pembicara + $jml_pending_abd_jabatan;

					$badge_total_pending_abd = ($total_pending_abd > 0) ? "<span class='badge badge-danger right' style='margin-right: 20px;'>{$total_pending_abd}</span>" : "";
					$badge_pending_abd_pengabdian = ($jml_pending_abd_pengabdian > 0) ? "<span class='badge badge-danger right'>{$jml_pending_abd_pengabdian}</span>" : "";
					$badge_pending_abd_pembicara = ($jml_pending_abd_pembicara > 0) ? "<span class='badge badge-danger right'>{$jml_pending_abd_pembicara}</span>" : "";
					$badge_pending_abd_jabatan = ($jml_pending_abd_jabatan > 0) ? "<span class='badge badge-danger right'>{$jml_pending_abd_jabatan}</span>" : "";
					?>
					<li class='nav-item has-treeview'>
						<a href='#' class='nav-link'>
							<i class='nav-icon fa fa-flask'></i>
							<p>Penelitian <?php echo $badge_total_pending; ?></p>
							<i class='fas fa-angle-left right'></i>
						</a>
						<ul class='nav nav-treeview'>
							<li class='nav-item'><a href='/penelitian/penelitian' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Keg. Penelitian <?php echo $badge_pending_child; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/patenhki' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Paten dan HKI <?php echo $badge_pending_paten; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/karya' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Karya Tanpa HKI <?php echo $badge_pending_karya; ?></p>
								</a></li>
							<li class="nav-header">PUBLIKASI</li>
							<li class='nav-item'><a href='/penelitian/jurnal' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Jurnal <?php echo $badge_pending_jurnal; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/prosiding' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Prosiding <?php echo $badge_pending_prosiding; ?></p>
								</a></li>
														<li class='nav-item'><a href='/penelitian/buku' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Buku Referensi <?php echo $badge_pending_buku; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/chapter' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Book Chapter <?php echo $badge_pending_chapter; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/tulisan' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Tulisan di Media <?php echo $badge_pending_tulisan; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/monograf' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Monograf <?php echo $badge_pending_monograf; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/penerjemahan' class='nav-link'><i
										class='nav-icon far fa-circle'></i>
									<p>Penerjemahan Buku <?php echo $badge_pending_penerjemahan; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/penyuntingan' class='nav-link'><i
										class='nav-icon far fa-circle'></i>
									<p>Penyuntingan <?php echo $badge_pending_penyuntingan; ?></p>
								</a></li>
							<li class='nav-item'><a href='/penelitian/non_pub' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Non Publikasi <?php echo $badge_pending_non_pub; ?></p>
								</a></li>

						</ul>
					</li>

					<li class='nav-item has-treeview'>
						<a href='' class='nav-link'><i class='nav-icon fa fa-hand-holding'></i>
							<p>Pengabdian <?php echo $badge_total_pending_abd; ?></p>
							<i class='fas fa-angle-left right'></i>
						</a>
						<ul class='nav nav-treeview'>
							<li class='nav-item'><a href='/pengabdian/pengabdian' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Keg. Pengabdian <?php echo $badge_pending_abd_pengabdian; ?></p>
								</a></li>
							<li class='nav-item'><a href='/pengabdian/pembicara' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Pembicara/Narasumber <?php echo $badge_pending_abd_pembicara; ?></p>
								</a></li>
							<li class='nav-item'><a href='/pengabdian/jabatan' class='nav-link'><i class='nav-icon far fa-circle'></i>
									<p>Jabatan di Luar PT <?php echo $badge_pending_abd_jabatan; ?></p>
								</a></li>
						</ul>
					</li>



					<li class='nav-item'>
						<a href='/logout' class='nav-link'><i class='nav-icon fa fa-sign-out-alt'></i>
							<p>Logout</p><small class='label pull-right bg-red'></small>
						</a>
					</li>

				</ul>
			</nav>

			<?php
			break;
	}
	?>


</div>