<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="icon" href="dist/login/img/fav/simlitabmas.png" type="image/png" />
    <title>SIMLITABMAS | UM Kuningan</title>
    <link rel="stylesheet" href="dist/login/css/bootstrap.css" />
    <link rel="stylesheet" href="dist/login/css/flaticon.css" />
    <link rel="stylesheet" href="dist/login/css/themify-icons.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dist/login/css/style.css" />
    <link rel="stylesheet" href="dist/login/css/info.css" />    
  </head>

  <body>
    <header class="header_area">
      <div class="main_menu">

        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="container">
            <a class="navbar-brand logo_h" href="#"><img logo src="dist/login/img/logo/simlitabmas.svg" alt=""/></a>
        
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="icon-bar"></span> <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse offset" id="navbarSupportedContent" >
              <ul class="nav navbar-nav menu_nav ml-auto">
                <li class="nav-item active">
                  <a class="nav-link" href="https://umkuningan.ac.id" target="_blank">Portal Web</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" target="_blank" href="https://drive.google.com/file/d/1tqMXceLrRebOzK-TI-wR3rUPLmbI3smG/view?usp=drive_link">Panduan</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </header>

       <div class="section_gap registration_area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-7">
              <div class="row clock_sec clockdiv" id="clockdiv">
                <div class="col-lg-12">
                   <span class="mobile-break"></span>
                    <h1 class="mb-3">Selamat Datang Di SIMLITABMAS</h1>
                     <h6 class="si-text si-text__heading-6 si-text--bold m-0">Anda tetap dapat masuk dengan nama pengguna &amp; kata sandi
                      yang sama</h6><br>
                      <p class="spacing">Platform SIMLITABMAS hadir untuk mempermudah pengelolaan penelitian dan pengabdian masyarakat.</p>

                </div>
              </div>
              
            </div>
            <div class="col-lg-4 offset-lg-1">
              <div class="register_form">
                    <h3>Masuk ke Akun</h3>
                    <form class="form_area" id="myForm" action="validation.php" method="post">
                        <div class="row">
                            <div class="col-lg-12 form_group">
                                <input name="username" placeholder="Nama Pengguna" required="" type="text">
                                <input name="password" placeholder="Kata Sandi" required="" type="password">
                                <div class="captcha_container">
                                    <img src="/dist/captcha/captcha.php" alt="Captcha Image" class="captcha_image">
                                    <input type="text" name="captcha" placeholder="Kode Verifikasi" required>
                                </div>

                            </div>
            
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn">Masuk</button>
                            </div>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    

<div id="notification" class="floating-alert alert alert-success " role="alert">
    <div>
        <i class="fa fa-info-circle" aria-hidden="true"></i>
        <strong>Info!</strong> Selamat datang di wajah baru dengan domain @umkuningan.ac.id.
    </div>
    <br>    
    <button class="close-alert" onclick="closeNotification()">&times;</button>
</div>

    <script>
        function closeNotification() {
            document.getElementById("notification").style.display = "none";
        }
    </script>
    
   
    <footer class="footer-area">
        <div class="container">

          <div class="row footer-bottom d-flex justify-content-between mt-0">
            <a href="https://www.umkuningan.ac.id">
              <img src="dist/login/img/logo footer.png" alt="Situs Resmi" class="icon-footer-logo">
            </a>
            <p class="col-lg-8 col-sm-12 footer-text m-0 text-black">
              Copyright ©<script>document.write(new Date().getFullYear());</script> Universitas Muhammadiyah Kuningan | Lembaga Pembangunan, Pengembangan Teknologi dan Sistem Informasi
            </p>
            <div class="col-lg-4 col-sm-12 footer-social">
              <a href="https://www.facebook.com/umkuningan"><i class="ti-facebook"></i></a>
              <a href="https://www.instagram.com/umkuningan"><i class="ti-instagram"></i></a>
              <a href="https://www.x.com/umkuningan/"><img src="dist/login/img/x-twitter.png" alt="Twitter" class="icon-x-twitter"></a>
              <a href="https://www.youtube.com/@umkuningan"><i class="ti-youtube"></i></a>
            </div>
            
          </div>          
        </div>
      </footer>
      
    <script src="dist/login/js/jquery-3.2.1.min.js"></script>
    <script src="dist/login/js/bootstrap.min.js"></script>
    <script src="dist/login/js/theme.js"></script>
    
  </body>
</html>

<?php
if(!empty($_GET['m'])) {
  switch($_GET['m']) {
    case 'injection' :
      echo" <script>alert('Format username dan password tidak benar');</script> ";
    break;
    case 'not_active' :
      echo" <script>alert('Akun anda dinonaktifkan');</script> ";
    break;
    default :
      echo" <script>alert('username atau password salah');</script> ";
    break;
    case 'captcha' :
      echo" <script>alert('kode verifikasi keliru. Perhatikan huruf besar kecil');</script> ";
    break;
    case 'simaster' :
      echo" <script>alert('Sistem diahlihkan ke SIMASTER');</script> ";
    break;
  }
}
?>