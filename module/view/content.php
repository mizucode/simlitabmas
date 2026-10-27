    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>
<?php
	$uppercase_module = strtoupper($module);
	echo "$uppercase_module";

?>
             </h1>
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </section>



        <section class="content">

	<?php

		if(!empty($_GET['m'])) {
			if($_GET['m']=='updated') {
			?>

				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					   <h4><i class="icon fa fa-check"></i> Pesan!</h4>
					   Perubahan berhasil disimpan.
				</div>
		<?php 
			}
			elseif($_GET['m']=='not_updated') {
		?>

				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Pesan!</h4>
					Perubahan gagal disimpan
				</div>
		<?php 
			}
			if($_GET['m']=='inserted') {
			?>

				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					   <h4><i class="icon fa fa-check"></i> Pesan!</h4>
					   Data berhasil disimpan
				</div>
		<?php 
			}
			elseif($_GET['m']=='not_inserted') {
		?>

				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Pesan!</h4>
					Data gagal disimpan
				</div>
		<?php 
			}
			elseif($_GET['m']=='not_inserted_edom') {
		?>

				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Pesan!</h4>
					Harap isi komentar secara serius, tidak asal-asalan!
				</div>
		<?php 
			}
			if($_GET['m']=='deleted') {
			?>

				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					   <h4><i class="icon fa fa-check"></i> Pesan!</h4>
					   Data berhasil dihapus
				</div>
		<?php 
			}
			elseif($_GET['m']=='not_deleted') {
		?>

				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Pesan!</h4>
					Data gagal dihapus
 				</div>
		<?php 
			}
			elseif($_GET['m']=='password') {
		?>

				<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<h4><i class="icon fa fa-ban"></i> Pesan!</h4>
					Mohon maaf, untuk keamanan penggunaan SIAmik, mohon ganti password dengan yang lebih rumit.
				</div>
		<?php 
			}
		}
		?>


			<?php

				switch($_SESSION['ses_level']) {
				
					case 'lppm' :
						if ($module=='dashboard'){
							include "module/content/dashboard/lppm.php";
						}
						elseif ($module=='account'){
							include "module/content/account/default.php";
						}
						elseif ($module=='update'){
							if (@$folder[2] == 'penelitian') {
								include "module/content/update/penelitian.php";
							} elseif (@$folder[2] == 'pengabdian') {
								include "module/content/update/pengabdian.php";
							} elseif (@$folder[2] == 'referensi') {
								include "module/content/update/referensi.php";
							} else {
								include "module/content/update/lppm.php";
							}
						}
						elseif ($module=='insert'){
							if (@$folder[2] == 'penelitian') {
								include "module/content/insert/penelitian.php";
							} elseif (@$folder[2] == 'pengabdian') {
								include "module/content/insert/pengabdian.php";
							} elseif (@$folder[2] == 'referensi') {
								include "module/content/insert/referensi.php";
							} else {
								include "module/content/insert/lppm.php";
							}
						}
						elseif ($module=='delete'){
							if (@$folder[2] == 'penelitian') {
								include "module/content/delete/penelitian.php";
							} elseif (@$folder[2] == 'pengabdian') {
								include "module/content/delete/pengabdian.php";
							} elseif (@$folder[2] == 'referensi') {
								include "module/content/delete/referensi.php";
							} else {
								include "module/content/delete/lppm.php";
							}
						}
						elseif ($module=='kegiatan'){
							include "module/content/kegiatan/lppm.php";
						}
						elseif ($module=='dosen'){
							include "module/content/dosen/lppm.php";
						}
						elseif ($module=='penelitian'){
							include "module/content/penelitian/lppm/dosen.php";
						}
						elseif ($module=='pengabdian'){
							include "module/content/pengabdian/lppm/dosen.php";
						}
						elseif ($module=='ppm'){
							include "module/content/ppm/lppm.php";
						}
						elseif ($module=='kkn'){
							include "module/content/kkn/lppm.php";
						}
						elseif ($module=='repository'){
							include "module/content/repository/lppm.php";
						}
						elseif ($module=='referensi'){
							if (@$folder[2] == 'jurnal') {
								include "module/content/referensi/jurnal.php";
							} elseif (@$folder[2] == 'klaster') {
								include "module/content/referensi/klaster.php";
							}
						}
						else {
							include "module/content/error/404.php";
						}
					
					break;


				}
			?>


        </section>