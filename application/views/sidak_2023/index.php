<?
$tempUserLoginInfoNama= $this->PEGAWAI_NAMA_LENGKAP;
$tempUserLoginInfoPath= $this->PEGAWAI_PATH;
$reqInfoSipeta= $this->INFO_SIPETA;
// echo $reqInfoSipeta;exit;

if(empty($reqInfoSipeta))
{
  echo '<script language="javascript">';
  echo 'window.document.location.href = "../app/index";';
  echo '</script>';
  exit();
}

if($tempUserLoginInfoPath == ""){
  $tempUserLoginInfoPath= "images/foto-profile.jpg";
}
?>

<style type="text/css">
 .header-mobile-open{
    padding-left: 10px;
  }
</style>

<script type="text/javascript">
  function atas(){
    // console.log('x');
    $("body").animate({ scrollTop: 0 }, "slow");
  }
</script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SIPETA - Sistem Informasi Peta Talenta
    </title>
    <base href="<?=base_url();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="baru/main.css" rel="stylesheet">
    <link href="baru/style.css" rel="stylesheet">
    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- <div class="app-drawer-overlay d-none animated fadeIn"></div><script type="text/javascript" src="baru/main.js"></script> -->
  </head>
  <body>
    <div class="app-container app-theme-white">
      <div class="app-top-bar bg-plum-plate top-bar-text-light">
        <div class="container fiori-container">
          <div class="top-bar-left">
            <ul class="nav">
              <li class="nav-item">
                <!-- a href="codeportal/index" target="_blank" class="nav-link"> SiapAsn.com </a> -->
                <a href="codeportal/index"><img src="images/sipeta75.png"></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="app-header header-shadow">
        <div class="container fiori-container">
          <div class="app-header__mobile-menu">
            <div>
              <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                </span>
              </button>
            </div>
          </div>
          <!-- 
          <div class="app-header__logo" style="background: #11142c;">
            <a href="codeportal/index"><img src="images/logo.png"></a> 
          </div>
          -->
          <ul class="horizontal-nav-menu">
            <!-- <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/rekam_jejak">
                <span>Rekam Jejak</span>
              </a>
            </li>
            <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/kualifikasi">
                <span>Kualifikasi</span>
              </a>
            </li>
            <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/pengembangan_kompetensi">
                <span>Pengembangan Kompetensi</span>
              </a>
            </li>
            <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/perumpunan">
                <span>Perumpunanan</span>
              </a>
            </li> -->
            <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/sidak_rekap">
                <span>Rekap</span>
              </a>
            </li>
            <li class="dropdown">
              <a data-offset="10" data-display="static" aria-expanded="false" class="active" href="sidak_2023/index/peta">
                <span>Peta</span>
              </a>
            </li>
            <li class="dropdown">
              <a href="app/logout"><i class="fa fa-sign-out"></i></a>
            </li>
          </ul>
          <div class="app-header__menu">
            <span>
              <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav active">
              <span class="btn-icon-wrapper">
              <i class="fa fa-ellipsis-v fa-w-6"></i>
              </span>
              </button>
            </span>
          </div>
          <div class="app-header-right">
            <div class="header-btn-lg pr-0">
              <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                  <div class="widget-content-left">
                    <div class="btn-group">
                      <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                        <img class="rounded" src="<?=$tempUserLoginInfoPath?>" alt="" width="42">
                        <!-- <i class="fa fa-angle-down ml-2 opacity-8"></i> -->
                      </a>
                      <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(59px, 44px, 0px);">
                        <div class="dropdown-menu-header">
                          <div class="dropdown-menu-header-inner bg-info">
                            <div class="menu-header-image opacity-2" style="background-image: url('assets/images/dropdown-header/city1.jpg');"></div>
                            <div class="menu-header-content text-left">
                              <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                  <div class="widget-content-left mr-3">
                                    <img class="rounded-circle" src="<?=$tempUserLoginInfoPath?>" alt="" width="42">
                                  </div>
                                  <div class="widget-content-left">
                                    <div class="widget-heading"><?=$tempUserLoginInfoNama?> </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <ul class="nav flex-column" style="background-color:#6770d2">
                              <li class="nav-item-divider nav-item"></li>
                              <li class="nav-item-btn text-center nav-item">
                                <a class="btn-wide btn btn-primary btn-sm" href="app/loadUrl/app/ubah_password" style="background-color: #00a651;z-index: 10;"> Ubah Password </a>
                                <a class="btn-wide btn btn-primary btn-sm" href="app/logout" style="background-color: #ed1c24;z-index: 10;"> Logout </a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
              <div class="app-header__menu" style="padding-left: 20px;width: 100%;color: white;">
                <div class="widget-content-left">
                  <div class="widget-heading"><?=$tempUserLoginInfoNama?> </div>
                </div>
                <ul class="nav flex-column">                              
                  <li class="nav-item-btn text-center nav-item" style="padding:0px; text-align:left !important">
                    <button class="btn-wide btn btn-primary btn-sm" style="background-color: #d92550;"> Logout </button>
                    <button class="btn-wide btn btn-primary btn-sm" style="background-color: #28a745"> Ubah Password </button>
                  </li>
                </ul>
             </div>
          </div>
        </div>
      </div>
      <div class="app-main">
        <div class="app-main__outer">
          <div class="app-main__inner">            
            <?=($content ? $content:'')?>
          </div>
          <div class="app-wrapper-footer">
            <div class="app-footer">
              <div class="container fiori-container">
                <div class="app-footer__inner">
                  <div class="footer-dots">
                  <!--
                    <div class="dropdown">
                      <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                        <img src="images/siap-asn.png" width="50">
                      </a>
                    </div>
                    <div class="dropdown">
                    </div>
                  -->
                  </div>
                  <div class="app-footer-right">
                    <div class="dropdown">
                      <div class="footer-dots">
                        <div class="dropdown">
                          Badan Kepegawaian dan Pengembangan Sumber Daya Manusia
                        </div>
                        <div class="dropdown">
                          <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                            <img src="images/bkpsdm-logo.png" width="50">
                          </a>
                        </div>                       
                        <!--
                        <div class="dropdown">
                          Pemerintah Kabupaten Jombang
                        </div>
                        <div class="dropdown">
                          <a aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dot-btn-wrapper">
                            <img src="images/logo-daerah.png" width="35">
                          </a>
                        </div>
                        -->

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ui-theme-settings">
        <button type="button" onclick="atas()" id="TooltipDemo" class="btn-open-options btn btn-outline-2x btn-outline-focus">
        <i class="fa fa-spinner" aria-hidden="true" style="font-size:20px"></i>
        </button>
        <!-- <div class="theme-settings__inner"></div> -->
      </div>
    </div>
  </body>
</html>

