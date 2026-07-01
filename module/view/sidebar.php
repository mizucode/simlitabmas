	<a href="#" class="brand-link bg-navy">
		<img src="/dist/img/logo_univ_med.png" alt="Logo" class="brand-image opacity-75 shadow"/>
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
  switch($_SESSION['ses_level']) {
    case 'lppm' :
?>
	  <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-flat text-sm" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-header">LAYANAN</li>		
		<li class='nav-item'>
			<a href='/dashboard' class='nav-link'><i class='nav-icon fa fa-home'></i> <p>Dashboard</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item'>
			<a href='/account' class='nav-link'><i class='nav-icon fa fa-user-cog'></i> <p>Account</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item'>
			<a href='/dosen' class='nav-link'><i class='nav-icon fa fa-users'></i> <p>Dosen</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item has-treeview'>
			<a href='/kkn' class='nav-link'><i class='nav-icon fa fa-clipboard'></i> <p>KKN-DIK</p><small class='label pull-right bg-red'></small>
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
			<a href='/repository' class='nav-link'><i class='nav-icon fa fa-file'></i> <p>Repository</p><small class='label pull-right bg-red'></small></a>
		</li>

		<li class='nav-item'>
			<a href='/kegiatan/4' class='nav-link'><i class='nav-icon fa fa-file-alt'></i> <p>Seminar Proposal</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item'>
			<a href='/kegiatan/5' class='nav-link'><i class='nav-icon fa fa-star'></i> <p>Sidang Skripsi</p><small class='label pull-right bg-red'></small></a>
		</li>
<!--
		<li class='nav-item'>
			<a href='/ppm/penelitian' class='nav-link'><i class='nav-icon fa fa-star'></i> <p>Hibah Penelitian</p><small class='label pull-right bg-red'></small></a>
		</li>
		<li class='nav-item'>
			<a href='/ppm/pengabdian' class='nav-link'><i class='nav-icon fa fa-star'></i> <p>Hibah Pengabdian</p><small class='label pull-right bg-red'></small></a>
		</li>
!-->		
            <li class="nav-header">LAPORAN KINERJA</li>
		<li class='nav-item has-treeview'>
			<a href='' class='nav-link'><i class='nav-icon fa fa-flask'></i> <p>Penelitian</p>
			<i class='fas fa-angle-left right'></i>
			</a>
			<ul class='nav nav-treeview'>
				<li class='nav-item'><a href='/penelitian/penelitian' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Keg. Penelitian</p></a></li>
				<li class='nav-item'><a href='/penelitian/patenhki' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Paten dan HKI</p></a></li>
				<li class='nav-item'><a href='/penelitian/karya' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Karya Tanpa HKI</p></a></li>
				<li class="nav-header">PUBLIKASI</li>
				<li class='nav-item'><a href='/penelitian/jurnal' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Jurnal</p></a></li>
				<li class='nav-item'><a href='/penelitian/prosiding' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Prosiding</p></a></li>
				<li class='nav-item'><a href='/penelitian/buku' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Buku Referensi</p></a></li>
				<li class='nav-item'><a href='/penelitian/chapter' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Book Chapter</p></a></li>
				<li class='nav-item'><a href='/penelitian/tulisan' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Tulisan di Media</p></a></li>
				<li class='nav-item'><a href='/penelitian/monograf' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Monograf</p></a></li>
				<li class='nav-item'><a href='/penelitian/penerjemahan' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Penerjemahan Buku</p></a></li>
				<li class='nav-item'><a href='/penelitian/penyuntingan' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Penyuntingan</p></a></li>
				<li class='nav-item'><a href='/penelitian/non_pub' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Non Publikasi</p></a></li>

			</ul>
		</li>

		<li class='nav-item has-treeview'>
			<a href='' class='nav-link'><i class='nav-icon fa fa-hand-holding'></i> <p>Pengabdian</p>
			<i class='fas fa-angle-left right'></i>
			</a>
			<ul class='nav nav-treeview'>
				<li class='nav-item'><a href='/pengabdian/pengabdian' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Keg. Pengabdian</p></a></li>
				<li class='nav-item'><a href='/pengabdian/pembicara' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Pembicara/Narasumber</p></a></li>
				<li class='nav-item'><a href='/pengabdian/jabatan' class='nav-link'><i class='nav-icon far fa-circle'></i><p>Jabatan di Luar PT</p></a></li>
			</ul>
		</li>



		<li class='nav-item'>
			<a href='/logout' class='nav-link'><i class='nav-icon fa fa-sign-out-alt'></i> <p>Logout</p><small class='label pull-right bg-red'></small></a>
		</li>

          </ul>
          </nav>

<?php    
    break;
  }
?>


        </div>