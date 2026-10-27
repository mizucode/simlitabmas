<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// generate Code
function RandomCode($max) {
    // Huruf dan angka yang akan di acak
    $char = array("A","B","C","D","E","F","G","H","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y",
                  "Z","a","b","c","d","e","f","g","h","j","k","l","m","n","p","q","r","s","t","u","v","w","x",
                  "y","z","1","2","3","4","5","6","7","8","9");
    
    $keys = array();
    while (count($keys) < $max) {
        $x = mt_rand(0, count($char) - 1);
        if (!in_array($x, $keys)) {
            $keys[] = $x;  
        }      
    }
    $random = '';
    foreach ($keys as $val) {
        $random .= $char[$val]; 
    }
    return $random;
}

$text = RandomCode(5);
$_SESSION['captcha'] = $text;

// Bersihkan output buffer agar tidak ada warning yang merusak stream gambar
while (ob_get_level()) {
    ob_end_clean();
}

$font = __DIR__ . '/font/VeraMoBd.ttf';
$images = __DIR__ . '/images/captcha.jpg';

// 1. Jika ekstensi GD tersedia
if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
    header("Content-type: image/jpeg");
    header("Cache-Control: no-cache, no-store, must-revalidate");
    
    $im = null;
    if (file_exists($images) && function_exists('imagecreatefromjpeg')) {
        $im = @imagecreatefromjpeg($images);
    }
    
    // Fallback jika file background captcha.jpg tidak ditemukan/gagal dimuat
    if (!$im) {
        $im = imagecreatetruecolor(120, 36);
        $bgColor = imagecolorallocate($im, 241, 245, 249);
        imagefill($im, 0, 0, $bgColor);
        
        // Garis noise tipis
        for ($i = 0; $i < 3; $i++) {
            $lineColor = imagecolorallocate($im, rand(180, 215), rand(180, 215), rand(180, 215));
            imageline($im, rand(0, 120), rand(0, 36), rand(0, 120), rand(0, 36), $lineColor);
        }
        $textColor = imagecolorallocate($im, 15, 23, 42);
    } else {
        $textColor = imagecolorallocate($im, 225, 225, 225);
    }
    
    // Tuliskan teks: gunakan TTF jika tersedia, atau fallback ke font bitmap bawaan GD
    if (file_exists($font) && function_exists('imagettftext')) {
        imagettftext($im, 15, 0, 10, 25, $textColor, $font, $text);
    } else {
        imagestring($im, 5, 25, 10, $text, $textColor);
    }
    
    imagejpeg($im);
    imagedestroy($im);
    exit;
}

// 2. Fallback SVG jika server tidak memiliki ekstensi GD sama sekali
header("Content-type: image/svg+xml");
header("Cache-Control: no-cache, no-store, must-revalidate");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<svg xmlns="http://www.w3.org/2000/svg" width="130" height="38" viewBox="0 0 130 38">
  <rect width="100%" height="100%" fill="#f1f5f9" rx="4" />
  <line x1="5" y1="19" x2="125" y2="19" stroke="#cbd5e1" stroke-width="1" />
  <text x="15" y="26" fill="#001f3f" font-family="Courier New, monospace" font-weight="bold" font-size="22" letter-spacing="4"><?php echo htmlspecialchars($text); ?></text>
</svg>