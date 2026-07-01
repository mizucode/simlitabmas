<?php
function time_stamp($waktu_sesi) { 
  $selisih_waktu = time() - $waktu_sesi ; 
  $detik = $selisih_waktu ; 
  $menit = round($selisih_waktu / 60 );
  $jam = round($selisih_waktu / 3600 ); 
  $hari = round($selisih_waktu / 86400 ); 
  $minggu = round($selisih_waktu / 604800 ); 
  $bulan = round($selisih_waktu / 2419200 ); 
  $tahun = round($selisih_waktu / 29030400 ); 

  if($detik <= 60){
	  $time = ' Baru saja'; 
  }
  else if($menit <= 60){
    if($menit==1){
		$time = 'satu menit lalu'; 
    }
    else{
      $time = $menit.' menit lalu'; 
    }
  }
  else if($jam <= 24){
    if($jam==1){
      $time = 'satu jam lalu';
    }
    else{
      $time = $jam.' jam lalu';
    }
  }
  else if($hari <= 7){
    if($hari==1){
      $time = 'satu hari lalu';
    }
    else{
      $time = $hari.' hari lalu';
    }
  }
  else if($minggu <= 4){
    if($minggu==1){
      $time = 'satu minggu lalu';
    }
    else{
      $time = $minggu.' minggu lalu';
    }
  }
  else if($bulan <= 12){
    if($bulan==1){
      $time = 'satu bulan lalu';
    }
    else{
      $time = $bulan.' bulan lalu';
    }   
  }
  else{
    if($tahun==1){
      $time = 'satu tahun lalu';
    }
    else{
      $time = $tahun.' tahun lalu';
    }
  }

return $time;

} 
?>
