<?php

	function anti_inj($data){
		$con = mysqli_connect(host, user, pass) OR DIE (" Mohon Maaf Sedang Gangguan ");
		$filter = mysqli_real_escape_string($con,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
		if($filter=='%') {
			return 'Kata Kunci Salah';
		}
		else {
			return $filter;
		}
	}

	function anti_xss($data){
		$filter = htmlentities(htmlspecialchars($data), ENT_QUOTES);
		return $filter;
	}

	function no_xss($data){
		$con = mysqli_connect(host, user, pass) OR DIE (" Connection Failed with servers ");
		$filter = mysqli_real_escape_string($con,strip_tags($data));
		return $filter;
	}

?>