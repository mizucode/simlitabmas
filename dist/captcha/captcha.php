<?php
session_start();
header("Content-type: image/jpg");
//generate Code
function RandomCode($max){
//Huruf dan angka yang akan di acak
$char = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y",
              "Z","a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x",
              "y","z","1","2","3","4","5","6","7","8","9");
 
$keys = array();
while(count($keys) < $max) {
    $x = mt_rand(0, count($char)-1);
    if(!in_array($x, $keys)) {
       $keys[] = $x;  
    }      
}
$random = '';
foreach($keys as $key => $val){
   $random .= $char[$val]; 
}
return $random;
}
//setting font yang akan digunakan
$font = __DIR__ . '/font/VeraMoBd.ttf';
//gambar yang akan digunakan sebagai background
$images = __DIR__ . '/images/captcha.jpg';
//Buat gambar fungsi GD php
$im = imagecreatefromjpeg("$images")or die("Cannot Initialize new GD image stream");
 
//Generate kode yang akan dituliskan pada gambar sebanyak 6
$text=RandomCode(5);
//Buat sessi untuk pengecekan pada halaman lain
$_SESSION['captcha']=$text;
//menentukan warna text 
$text_color = imagecolorallocate($im, 225, 225, 225);
//Tuliskan text pada gambar
imagettftext($im, 15, 0, 5, 20, $text_color, $font, $text);
imagejpeg($im);
imagedestroy($im);
?>