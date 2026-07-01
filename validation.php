<?php

define('valid','1');
 include "config/db.php";
 include "config/anti_inj.php";
 include "config/library.php";

$pwd	= mysqli_real_escape_string($con,$_POST['password']);
$ip     = $_SERVER['REMOTE_ADDR']; 

$username = anti_inj($_POST['username']);
$password = anti_inj(md5($_POST['password']));

session_start();

	if($_POST['captcha']==$_SESSION['captcha']){

		if (!ctype_alnum($username) OR !ctype_alnum($password)){
			header('location:login?m=injection');
		}

		else {

			$quser=mysqli_query($con," SELECT username,name,password,level,template,active,bp FROM user WHERE username='$username' AND password='$password' AND level IN('lppm','riset','pkm','luaran') LIMIT 1 ");

			$user=mysqli_fetch_array($quser,MYSQLI_BOTH);
			$num_user=mysqli_num_rows($quser);

			if($num_user>0) {

				if ($user['active']=='1') {

					// session_start(); sudah dipanggil di baris 14
					include "timeout.php";

					$template=mysqli_fetch_array(mysqli_query($con,"SELECT path,layout,skin,screenshoot FROM template WHERE id='{$user['template']}' "),MYSQLI_BOTH);

					$_SESSION['ses_user']     = $user['username'];
					$_SESSION['ses_name']     = $user['name'];
					$_SESSION['ses_pass']     = $user['password'];
					$_SESSION['ses_level']    = $user['level'];
					$_SESSION['template']     = $template['path'];
					$_SESSION['layout']		= $template['layout'];
					$_SESSION['skin']		    = $template['skin'];
					$_SESSION['screenshoot']  = $template['screenshoot'];
					$_SESSION['ses_login']	= 1;
		
					timer();

					$old_id = session_id();
					session_regenerate_id();
					$new_id = session_id();

					mysqli_query($con,"UPDATE user SET session='$new_id' WHERE username='{$user['username']}'");

					$date = date("Ymd"); 
					$time   = time();

					$s = mysqli_query($con,"SELECT online FROM statistik WHERE ip='$ip' AND date='$date' AND user='{$_SESSION['ses_user']}' ");
					if(mysqli_num_rows($s) == 0){
						mysqli_query($con,"INSERT INTO statistik(ip, date, hit, online,user) VALUES('$ip','$date','1','$time', '{$_SESSION['ses_user']}')");
					}
					else {
						mysqli_query($con,"UPDATE statistik SET hit=hit+1 WHERE ip='$ip' AND date='$date' AND user='{$_SESSION['ses_user']}' ");
					}
					if(!empty($user['bp'])) {
						header('location:/dashboard');
					} else {
						header('location:/account/edit/?m=password');					
					}
				}

				else {
					header('location:/login?m=not_active');
				}

			}

			else {
				mysqli_query($con,"INSERT INTO log_error(user,code,date,time,ip) VALUES('$username','$pwd','$tgl_sekarang','$jam_sekarang','$ip') ");
				header('location:/login?m=wrong');
			}
		}
	}

	else {
		header('location:/login?m=captcha');
	}


?>