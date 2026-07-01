<?php

if (valid!='1'){
	header('location:/login');
}
else {

	date_default_timezone_set('Asia/Jakarta');
	$cu_date=date("Ymd");
	$now_date=date("Y-m-d");
	$cu_time=date("H:i:s");
	$satu_hari = mktime(0,0,0,date("n"),date("j")+1,date("Y"));
	$tom_date = date("Y-m-d", $satu_hari);

	function indo_date($date){
			$tanggal = substr($date,8,2);
			$bulan = konv_bulan(substr($date,5,2));
			$tahun = substr($date,0,4);
			return $tanggal.' '.$bulan.' '.$tahun;		 
	}	

	function simple_date($date){
			$tanggal = substr($date,8,2);
			$bulan = substr(konv_bulan(substr($date,5,2)),0,3);
			$tahun = substr($date,0,4);
			return $tanggal.'/'.$bulan;		 
	}
	function konv_bulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
			} 

	function indo_time($time){
			$jam = substr($time,0,2);
			$menit = substr($time,3,2);
			return $jam.':'.$menit;		 
	}

	function hari($date){
			$day = date('D', strtotime($date));
			$day = konv_hari($day);
			return $day;
	}

	function konv_hari($hari){
				switch ($hari){
					case 'Sun': 
						return "Ahad";
						break;
					case 'Mon':
						return "Senin";
						break;
					case 'Tue':
						return "Selasa";
						break;
					case 'Wed':
						return "Rabu";
						break;
					case 'Thu':
						return "Kamis";
						break;
					case 'Fri':
						return "Jum'at";
						break;
					case 'Sat':
						return "Sabtu";
						break;
				}
			} 

}

?>