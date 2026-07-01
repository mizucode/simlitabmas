<?php
	function timer(){
		$time = 200000;
		$_SESSION['ses_timeout'] = time()+$time;
	}


	function validation(){
		$timeout = $_SESSION['ses_timeout'];
		if(time()<$timeout){
			timer();
			return true;
		}
		else {
			unset($_SESSION[ses_timeout]);
			return false;
		}
	}

?>