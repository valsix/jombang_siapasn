<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

  $this->load->model('Versi');

  $mailbox_kategori = new Versi();
  $mailbox_kategori->selectByParams();



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
  <title>Dashboard</title>
  <base href="<?=base_url()?>">
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
  <script src="lib/HC-804/highcharts.js"></script>
  <script src="lib/HC-804/exporting.js"></script>
  <script src="lib/HC-804/export-data.js"></script>
  <script src="lib/HC-804/accessibility.js"></script>



</head>
<body class="layout-top-nav" >
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
          <section class="col-lg-6 connectedSortable">
             <div class="card">
              <div class="card-header">
                <h3 class="card-title">Versi Aplikasi</h3>


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
                      PHP
                      <span class="float-right text-danger">
                        <?php echo phpversion();?>
                        <i class="fas fa-arrow-left text-sm"></i>
                        </span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="app/loadUrl/app/home" class="nav-link">
                      PostgreSQL
                      <span class="float-right text-success">
                      <?
                                    while($mailbox_kategori->nextRow())
                                    {
                                    ?>
                                    <?=$mailbox_kategori->getField("VERSION");?>
                                    <?
                                    }
                                    ?>
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
