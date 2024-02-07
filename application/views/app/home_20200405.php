<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();


  if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1){
    $reqSatuanKerjaId=0;
  } else {
    $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
  }

$tinggi = 156;
$reqSatuanKerjaNama= "Semua Satuan Kerja";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Dashboard</title>
  <base href="http://siapasn.jombangkab.go.id/">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="lib/AdminLTE-3.0.4/plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="layout-top-nav">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

                <?
                //echo "uhui";
                $data_dashboard_tipe_pegawai = file_get_contents('http://siapasn.jombangkab.go.id/statistik_json/dashboard_tipe_pegawai?reqSatuanKerjaId='.$reqSatuanKerjaId);
                $json_dashboard_tipe_pegawai = json_decode($data_dashboard_tipe_pegawai, TRUE);

                ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?echo $json_dashboard_tipe_pegawai[5]['jumlah'];?></h3>

                <p><?echo $json_dashboard_tipe_pegawai[5]['nama'];?></p>
              </div>
              <div class="icon">
                <i class="ion ion-man"></i>
              </div>
              <a href="app/loadUrl/app/home" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?echo $json_dashboard_tipe_pegawai[0]['jumlah'];?></h3>

                <p>Struktural </p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-personadd"></i>
              </div>
              <a href="app/loadUrl/app/home" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?echo $json_dashboard_tipe_pegawai[1]['jumlah'];?></h3>

                <p>Pelaksana</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="app/loadUrl/app/home" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?echo $totalJFT=$json_dashboard_tipe_pegawai[2]['jumlah']+$json_dashboard_tipe_pegawai[3]['jumlah']+$json_dashboard_tipe_pegawai[4]['jumlah'];?></h3>

                <p>Fungsional Tertentu</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-people"></i>
              </div>
              <a href="app/loadUrl/app/home" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <section class="col-lg-6 connectedSortable">
             <div class="card">
              <div class="card-header">
                <h3 class="card-title">Jenjang Struktural</h3>

                <?
                //echo "uhui";
                $data_dashboard_eselon = file_get_contents('http://siapasn.jombangkab.go.id/statistik_json/dashboard_eselon?reqSatuanKerjaId='.$reqSatuanKerjaId);
                $json_dashboard_eselon = json_decode($data_dashboard_eselon, TRUE);

                ?>


                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <!--<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>-->
                </div>
              </div>
              <!-- /.card-header -->
              <!-- /.card-body -->
              <div class="card-footer bg-white p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      JPT Pratama
                      <span class="float-right text-danger">
                        <?echo $totalJPT=$json_dashboard_eselon[2]['jumlah']+$json_dashboard_eselon[3]['jumlah'];?>
                        <i class="fas fa-arrow-left text-sm"></i>
                        </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      Administrator
                      <span class="float-right text-success">
                        <?echo $totalAdministrator=$json_dashboard_eselon[4]['jumlah']+$json_dashboard_eselon[5]['jumlah'];?>
                        <i class="fas fa-arrow-left text-sm"></i> 
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      Pengawas
                      <span class="float-right text-warning">
                        <?echo $totalPengawas=$json_dashboard_eselon[6]['jumlah']+$json_dashboard_eselon[7]['jumlah'];?>
                        <i class="fas fa-arrow-left text-sm"></i> 
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
            <!-- /.card -->
          </section>
          <section class="col-lg-6 connectedSortable">
             <div class="card">
              <div class="card-header">
                <h3 class="card-title">Fungsional Tertentu Per Bidang</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <!--<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>-->
                </div>
              </div>
              <!-- /.card-header -->
              <!-- /.card-body -->
              <div class="card-footer bg-white p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      Pendidikan
                      <span class="float-right text-danger">
                        <i class="fas fa-graduation-cap text-sm"></i>
                        <?echo $json_dashboard_tipe_pegawai[2]['jumlah'];?>
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      Kesehatan
                      <span class="float-right text-success">
                        <i class="fas fa-medkit text-sm"></i> 
                        <?echo $json_dashboard_tipe_pegawai[3]['jumlah'];?>
                      </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      Lainnya
                      <span class="float-right text-warning">
                        <i class="fas fa-industry text-sm"></i>
                        <?echo $json_dashboard_tipe_pegawai[4]['jumlah'];?>
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.footer -->
            </div>
            <!-- /.card -->
          </section>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="lib/AdminLTE-3.0.4/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="lib/AdminLTE-3.0.4/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="lib/AdminLTE-3.0.4/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="lib/AdminLTE-3.0.4/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="lib/AdminLTE-3.0.4/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="lib/AdminLTE-3.0.4/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="lib/AdminLTE-3.0.4/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="lib/AdminLTE-3.0.4/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="lib/AdminLTE-3.0.4/plugins/moment/moment.min.js"></script>
<script src="lib/AdminLTE-3.0.4/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="lib/AdminLTE-3.0.4/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="lib/AdminLTE-3.0.4/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="lib/AdminLTE-3.0.4/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="lib/AdminLTE-3.0.4/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="lib/AdminLTE-3.0.4/dist/js/pages/dashboard.js"></script>
<script src="lib/AdminLTE-3.0.4/dist/js/pages/dashboard2.js"></script>
 <!-- AdminLTE for demo purposes -->
<script src="lib/AdminLTE-3.0.4/dist/js/demo.js"></script>

</body>
</html>
