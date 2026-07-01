<?php 

ob_start();	

	if (empty($_SESSION['ses_user']) AND empty($_SESSION['ses_pass']) AND empty($_SESSION['ses_level']) ){
		header('location:/login');
	}
	else {

		switch(@$folder[2]) {
			default:

	?>
	        <div class='card card-outline card-danger'>
	            <div class='card-header with-border'>

					<h3 class='card-title'>Data Account</h3>
			                <div class="card-tools">
			                      <a href='/account/edit' class="btn btn-block btn-warning"><i class="fas fa-edit"></i> Edit</a>
			                 </div>														

				</div>
				<div class='card-body table-responsive p-0'>
					<table class='table table-hover text-nowrap'>
		<?php

					$user=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM user AS u WHERE username='$_SESSION[ses_user]' LIMIT 1 "));
					if ($user['active']=='1') { $active='YES'; }	else { $active='NO'; }
	
		?>
						<tr><td><i class="fa fa-user"></i> Username		</td><td><?php echo $user['username']; ?>	</td></tr>
						<tr><td><i class="fa fa-font"></i> Nama Panggilan	</td><td><?php echo $user['name']; ?>	</td></tr>
						<tr><td><i class="fa fa-file-text-o"></i> Deskripsi Diri	</td><td><?php echo $user['about']; ?>	</td></tr>
						<tr><td><i class="fa fa-mobile-phone"></i> Handphone		</td><td><?php echo $user['hp']; ?>		</td></tr>
						<tr><td><i class="fa fa-envelope-square"></i> Email			</td><td><?php echo $user['email']; ?>	</td></tr>
						<tr><td><i class="fa  fa-lock"></i> Password		</td><td>*********						</td></tr>
						<tr><td><i class="fa fa-key"></i> Level			</td><td><?php echo $user['level']; ?>	</td></tr>
					
					</table>
				</div>
			</div>

		<?php

		break;

		case 'edit' :
?>
<?php
			$user=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM user WHERE username='$_SESSION[ses_user]' LIMIT 1 "));
	
			echo"
	        <div class='card card-outline card-danger'>
	            <div class='card-header with-border'>
					<h3 class='card-title'>Account untuk $_SESSION[ses_user]</h3>
				</div>
				<div class='card-body'>
					<form method='post' action='/update/account'>
	                    <div class='form-group'>
							<label>Username	</label>
							<div class='input-group'>
								<input type='text' class='form-control' value='$user[username]' disabled/>
							</div>
						</div>

	                    <div class='form-group'>
							<label>Handphone</label>
							<div class='input-group'>
									<input name='hp' type='text' class='form-control' value='$user[hp]' required/>
							</div>
						</div>

	                    <div class='form-group'>
							<label>Nama Panggilan</label>
							<div class='input-group'>
									<input name='name' type='text' class='form-control' value='$user[name]' required/>
							</div>
						</div>

	                    <div class='form-group'>
							<label>Deskripsi Diri</label>
							<div class='input-group'>
									<textarea name='about' class='form-control'>$user[about]</textarea>
							</div>
						</div>

	                    <div class='form-group'>
							<label>Email</label>
							<div class='input-group'>
									<input name='email' type='text' class='form-control' value='$user[email]' required/>

							</div>
						</div>

	                    <div class='form-group'>
							<label>Ganti Password (biarkan kosong jika tidak ingin diganti)</label>
							<div class='input-group'>
									<input name='password' type='password' class='form-control' value=''/>
							</div>
						</div>

	                    <div class='form-group'>
							<label>Ulangi Password</label>
							<div class='input-group'>
								<input name='password_confirm' type='password' class='form-control' value='' />
							</div>
						</div>





				</div>
                <div class='card-footer'>
                    <button type='submit' class='btn btn-primary'>Ubah</button>
                    <button type='reset' class='btn btn-danger'>Ulangi</button>
                    <input type='button' class='btn btn-success' onclick='self.history.back()' value='Batal'>
				</div>

				</form>

				</div>

				";

		break;
		}


	}



?>