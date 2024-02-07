<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tinggi = 360;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$reqSatuanKerjaId = $this->SATUAN_KERJA_ID;
// echo $reqSatuanKerjaId;exit;

$tanggalhariini= date("Y-m-d");

$this->load->model('main/Dashboard');

$arrData= array(
    array("label"=>"NAMA LENGKAP", "width"=>"", "display"=>"")
    , array("label"=>"NIP Baru", "width"=>"", "display"=>"")
    , array("label"=>"ID Pegawai", "width"=>"", "display"=>"")
    , array("label"=>"Jam Masuk", "width"=>"", "display"=>"")
    , array("label"=>"Lama Terlambat", "width"=>"", "display"=>"")

);

$arrData2= array(
    array("label"=>"NAMA LENGKAP", "width"=>"", "display"=>"")
    , array("label"=>"NIP Baru", "width"=>"", "display"=>"")
    , array("label"=>"ID Pegawai", "width"=>"", "display"=>"")
    , array("label"=>"Jam Pulang", "width"=>"", "display"=>"")
    , array("label"=>"Pulang Cepat", "width"=>"", "display"=>"")

);

$set= new Dashboard();
$reqSatuanKerjaId= $set->getSatuanKerja($reqSatuanKerjaId);
// echo $set->query;exit();
unset($set);
// echo $reqSatuanKerjaId;exit;
$satuankerjakondisi= " 
AND EXISTS
(
    SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
    AND XXX.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
) ";

if (!empty($reqSatuanKerjaId))
{
   $satuankerjakondisi.= " AND XXX.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")"; 
} 

$reqPegawaiId="";
$reqPeriode= date("mY");
// $reqPeriode="022021";

$reqHari= date("d");
$reqHari = (int)$reqHari;

$reqStatementbulan=" AND TO_CHAR(TANGGAL_MULAI, 'MMYYYY') = '".$reqPeriode."' OR  TO_CHAR(TANGGAL_SELESAI, 'MMYYYY') = '".$reqPeriode."'";

$set= new Dashboard();
$jumlahcutibulanini =$set->getCountByParamsCuti(array(), $satuankerjakondisi,"",$reqStatementbulan);
// echo $set->query;exit;
unset($set);

$statement= " AND A.JENIS_KLARIFIKASI= 'klarifikasi_dinas_luar'";
$set= new Dashboard();
$jumlahdinasluarbulanini =$set->getCountByParamsDinasLuar(array(), $satuankerjakondisi,$statement,$reqStatementbulan);
// echo $set->query;exit;
unset($set);

$reqPeriode= date("dmY");
$reqPeriodeRekap= date("mY");
// $reqPeriode="28022021";
// $reqPeriodeRekap="022021";

$statement="AND JAM_MASUK_".$reqHari." IS NULL";
$statementsatker ="";
if (!empty($reqSatuanKerjaId))
{
  $statementsatker= " AND A.S_KERJA_ID IN (".$reqSatuanKerjaId.")";
} 

$set= new Dashboard();
$jumlahabsenbulanini =$set->getCountByParamsPeriodeBerjalanRekap(array(), $reqPeriode,$reqPeriodeRekap,$statement.$statementsatker);
// echo $set->query;exit;
unset($set);

$statement="AND TERLAMBAT_".$reqHari." IS NOT NULL";
$set= new Dashboard();
$jumlahpegawaiterlambathariini =$set->getCountByParamsPeriodeBerjalanRekap(array(), $reqPeriode,$reqPeriodeRekap,$statement.$statementsatker);
// echo $set->query;exit;
// echo $jumlahpegawaiterlambathariini;exit;
unset($set);

$statement="AND JAM_MASUK_".$reqHari." IS NOT NULL";
$set= new Dashboard();
$jumlahhadirmasukhariini =$set->getCountByParamsPeriodeBerjalanRekap(array(), $reqPeriode,$reqPeriodeRekap,$statement.$statementsatker);
// echo $jumlahhadirmasukhariini;exit;
// echo $set->query;exit;
unset($set);

$reqPeriode= date("dmY");
// $reqPeriode="28022021";

$statement ="";
if (!empty($reqSatuanKerjaId))
{
  $statement= " AND A.S_KERJA_ID IN (".$reqSatuanKerjaId.")";
} 

$set= new Dashboard();
$jumlahpegawaihadirhariini =$set->getCountByParamsPeriodeBerjalan(array(), $reqPeriode,$statement);
// echo $set->query;exit;
// echo $jumlahpegawaihadirhariini;exit;
unset($set);

$statement= " 
AND EXISTS
(
    SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
    AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
) ";
if (!empty($reqSatuanKerjaId))
{
   $statement.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";

} 
$set= new Dashboard();
$order = "ORDER BY PA.JAM DESC";
$set->selectByParamsPresensiAbsenHome(array(), 10,-1, $reqPeriode,$statement,$order);
// echo $set->query;exit;

$reqPeriode= date("dmY");
$reqPeriodeRekap= date("mY");
// $reqPeriode="01022021";
// $reqPeriodeRekap="022021";
// $reqHari="1";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>PJB Presensi</title>
        <base href="<?=base_url()?>">
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Bootstrap In this tutorial, I want to show you how to create a beautiful website template that gives a wonderful look and comfort of your Web applicat" />
        <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">
        
        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">


        <!-- CSS code from Bootply.com editor -->
        <style type="text/css">
            /*!
         * Start Bootstrap - Simple Sidebar HTML Template (http://startbootstrap.com)
         * Code licensed under the Apache License v2.0.
         * For details, see http://www.apache.org/licenses/LICENSE-2.0.
         */

        /* Toggle Styles */

        #wrapper {
            padding-left: 0;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        #wrapper.toggled {
            padding-left: 250px;
        }

        #sidebar-wrapper {
            position: fixed;
            left: 250px;
            z-index: 1000;
            overflow-y: auto;
            margin-left: -250px;
            width: 0;
            height: 100%;
            *background: #000;
            *background:rgba(255,255,255,0.5);
            background:url(images/border-sidebar.png) top right no-repeat;
            background-position:249px 60px;
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }

        #wrapper.toggled #sidebar-wrapper {
            width: 250px;
        }

        #page-content-wrapper {
            padding: 15px 15px 15px 15px;
            width: 100%;
            
            margin-top:60px;
        }

        #wrapper.toggled #page-content-wrapper {
            position: absolute;
            margin-right: -250px;
        }

        /* Sidebar Styles */

        .sidebar-nav {
            position: absolute;
            top: 0;
            margin: 0;
            padding: 0;
            width: 250px;
            list-style: none;
        }

        .sidebar-nav li {
            text-indent: 20px;
            line-height: 40px;
        }

        .sidebar-nav li a {
            display: block;
            color: #999999;
            text-decoration: none;
        }

        .sidebar-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            text-decoration: none;
        }

        .sidebar-nav li a:active,
        .sidebar-nav li a:focus {
            text-decoration: none;
        }

        .sidebar-nav > .sidebar-brand {
            height: 65px;
            font-size: 18px;
            line-height: 60px;
        }

        .sidebar-nav > .sidebar-brand a {
            color: #999999;
        }

        .sidebar-nav > .sidebar-brand a:hover {
            background: none;
            color: #fff;
        }

        @media (min-width: 768px) {
            #wrapper {
                padding-left: 250px;
            }

            #wrapper.toggled {
                padding-left: 0;
            }

            #sidebar-wrapper {
                width: 250px;
            }

            #wrapper.toggled #sidebar-wrapper {
                width: 0;
            }

            #page-content-wrapper {
                padding: 20px;
            }

            #wrapper.toggled #page-content-wrapper {
                position: relative;
                margin-right: 0;
            }
        }
        </style>

        

        <style>
            .nav-tabs
            {
             border-color:#1A3E5E;
             width:60%;
         }

         .nav-tabs > li a { 
            border: 1px solid #1A3E5E;
            background-color:#2F71AB; 
            color:#fff;
        }

        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:focus,
        .nav-tabs > li.active > a:hover{
            background-color:#D6E6F3;
            color:#000;
            border: 1px solid #1A3E5E;
            border-bottom-color: transparent;
        }

        .nav-tabs > li > a:hover{
          background-color: #D6E6F3 !important;
          border-radius: 5px;
          color:#000;

      } 

      .tab-pane {
        border:solid 1px #1A3E5E;
        border-top: 0; 
        width:100%;
        background-color:#D6E6F3;
        padding:5px;

    }
</style>
        
<link rel="stylesheet" href="css/gaya.css" type="text/css">
<link rel="stylesheet" href="css/gaya-bootstrap.css" type="text/css">
<link rel="stylesheet" href="css/gaya-navbar-hover.css" type="text/css">

<style>
html{
    height: 100%;
}

/*anggoro style*/
.konten-dashboard{
    background-color:#075d72; 
    width: 80%;
    height: 80px;
    border-radius: 25px;
    margin-top: 10%;
    margin-bottom: 10%;
    color: white;
}
.data-dashboard{
    padding-top: 5%;
    font-size:11px;
}

.icon-notif-dashboard{
    font-size:50px;
    padding-top: 7%;
}

.daftar-hadir{
    background-color:white;
    margin-top: 5%;
    border-radius: 25px;
    padding-top: 10px;
    padding-bottom: 10px;
}

body {
 /* background-image: url('images/bg-dashboard.jpg');*/
/* background-color: #b2d7ff;*/
   background: radial-gradient(circle at top left, #fdbb2d, #22c1c3); 
  /*background-image: linear-gradient(to bottom right, #FF0000, #FFC0CB);*/
/*background-image: linear-gradient(to right, #4682B4, #00FFFF, #00FA9A);
*/   background-size: cover;
   font-size: 10px;

}

table tr th:nth-child(1){
        text-align: center;
    }
    .row_selected td,
    .row_selected td.sorting_1{
        background-color: #005c99 !important;
        color:  #fff;
    }

    .fg-toolbar.ui-toolbar.ui-widget-header.ui-helper-clearfix.ui-corner-tl.ui-corner-tr .dataTables_filter {
        padding-right: 5px !important;
    }

    .fg-toolbar.ui-toolbar.ui-widget-header.ui-helper-clearfix.ui-corner-tl.ui-corner-tr .dataTables_filter label input[type=search] {
        width: 250px !important;
    }
</style>

<!-- FONT AWESOME -->
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

<!-- DOUGHNUT CHART JS -->
<script src="lib/Chart.js-master/Chart.js">
</script>

<!-- SCROLLING TABLE MASTER -->
<link rel="stylesheet" href="lib/ScrollingTable-master/style.css" />

        <style type="text/css">
            table.display th.th_like:nth-child(1),
            table.display tbody tr td:nth-child(1){
                *width: 300px !important;
                *border: 1px solid green !important;
            }
            .row_selected td,
            .row_selected td.sorting_1{
                background-color: #005c99 !important;
                color:  #fff;

            }
        </style>
        
        <style>
        .area-terlambat-pulang-cepat {
            font-size: 14px !important;
        }
        .area-terlambat-pulang-cepat .nav-tabs > li {
            margin-bottom: 0px;
        }
        .area-terlambat-pulang-cepat .nav-tabs > li > a {
            border: none;
            background-color: #075d72;
        }
        .area-terlambat-pulang-cepat .nav-tabs > li > a:hover {
/*            background: red !important;*/
            border: none;
            background: #ebebeb !important;
            margin-bottom: 0px;
            
            -webkit-border-radius: 4px;
            -webkit-border-bottom-right-radius: 0px;
            -webkit-border-bottom-left-radius: 0px;
            -moz-border-radius: 4px;
            -moz-border-radius-bottomright: 0px;
            -moz-border-radius-bottomleft: 0px;
            border-radius: 4px;
            border-bottom-right-radius: 0px;
            border-bottom-left-radius: 0px;
        }
        .area-terlambat-pulang-cepat .nav-tabs > li.active > a {
                background: #ebebeb;
            }
        .area-terlambat-pulang-cepat .nav.nav-tabs {
                border: none;
            }
        .area-terlambat-pulang-cepat .tab-content {
            border: none !important;
        }
        .area-terlambat-pulang-cepat .tab-content .tab-pane {
            border: none;
            background-color: #ebebeb;
        }
            
            
        .area-terlambat-pulang-cepat .dataTables_paginate {
                
        }
            .area-terlambat-pulang-cepat .dataTables_paginate a {
/*                border: 1px solid red;*/
                display: inline-block;
                height: 30px;
                line-height: 30px;
                padding: 0 10px;
                margin: 0 1px;
                background: #FFFFFF;
                
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
        }
        .area-data-pegawai {
/*            border: 2px solid red;*/
            height: calc(100vh);
            padding-right: 30px;
            padding-left: 30px;
/*            background: #dfdfdf;*/
/*            background: #0b4e5f;*/
/*            background: #0b4351;*/
            background: #55a386;
        }
        .area-data-pegawai h2 {
            color: #FFFFFF;
        }
        .row.area-data-angka .konten-dashboard .icon-notif-dashboard {
/*            border: 1px solid red;*/
            
            display: flex;
            justify-content: center; /* align horizontal */
            align-items: center; /* align vertical */

        }
        .row.area-data-angka .konten-dashboard .icon-notif-dashboard i {
            font-size: 30px;
        }
        .row.area-data-angka .konten-dashboard .data-dashboard {
            padding-top: 5%;
            font-size: 11px;
            padding-right: 30px;
        }
        @media screen and (max-width:767px) {
            .body-pjb {
                background: #fdbb2d !important;
                padding-top: 30px;
                
/*                border: 1px solid red !important;*/
                
/*
                background-color: #fdbb2d;
                background: url(images/linear_bg_2.png);
                background-repeat: repeat-x;
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#fdbb2d), to(#22c1c3));
                background: -webkit-linear-gradient(top, #fdbb2d, #22c1c3);
                background: -moz-linear-gradient(top, #fdbb2d, #22c1c3);
                background: -ms-linear-gradient(top, #fdbb2d, #22c1c3);
                background: -o-linear-gradient(top, #fdbb2d, #22c1c3);
*/

            }
            .area-data-angka {
/*                border: 2px solid red !important;*/
                height: auto !important;
                width: calc(100% + 30px) !important;
                
/*                display: none;*/
            }
            .area-data-angka [class*='col-'] {
                height: auto !important;
            }
            .area-data-angka .konten-dashboard {
                height: auto !important;
                margin-top: 0px;
            }
            .area-terlambat-pulang-cepat {
/*                border: 2px solid #FFFF00;*/
                height: auto !important;
                padding-top: 20px;
                
/*                display: none;*/
            }
            .area-terlambat-pulang-cepat .tab-content {
/*                border-width: 1px 0 0 0 !important;*/
            }
            .area-data-pegawai {
/*                border: 2px solid green;*/
                height: auto !important;
                margin-top: 20px;
                padding-bottom: 20px;
                padding-top: 10px;
/*                background: rgba(255,255,255,0.9) !important;*/
            }
        }
           
        
        </style>

</head>
<body class="body-pjb">
    <div class="col-md-8" style="padding-left: 0px;padding-right: 0px;">
       <div class="row area-data-angka" style="width: 100%; height: 90px">
            <div class="col-md-4" style=" height: 50px">
                <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span>JUMLAH PEGAWAI HARI INI</span><br>
                            <span style="font-size: 30px;color:  #2bc71c"><b><?=$jumlahpegawaihadirhariini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
            <div class="col-md-4" style=" height: 50px">
               <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span>PEGAWAI HADIR MASUK HARI INI</span><br>
                            <span style="font-size: 30px;color:  #6efa60"><b><?=$jumlahhadirmasukhariini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
            <div class="col-md-4" style="height: 50px">
                 <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span>PEGAWAI TERLAMBAT HARI INI</span><br>
                            <span style="font-size: 30px;color: #ff86d3"><b><?=$jumlahpegawaiterlambathariini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
       </div>
       <div class="row area-data-angka" style="width: 100%; height: 120px">
            <div class="col-md-4" style=" height: 50px">
               <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-bed" aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span> PEGAWAI CUTI BULAN INI</span><br>
                            <span style="font-size: 30px;color: #1ab9aa "><b><?=$jumlahcutibulanini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
            <div class="col-md-4" style=" height: 50px">
               <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-university " aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span>PEGAWAI DINAS LUAR BULAN INI</span><br>
                            <span style="font-size: 30px;color:  #fbe82c"><b><?=$jumlahdinasluarbulanini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
            <div class="col-md-4" style="height: 50px">
                 <center>
                    <div class="konten-dashboard">
                        <div class="icon-notif-dashboard col-md-4">
                            <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                        </div>
                        <div class="data-dashboard col-md-8">
                            <span>PEGAWAI ABSEN BULAN INI</span><br>
                            <span style="font-size: 30px;color: #e72607 "><b><?=$jumlahabsenbulanini?></b></span>
                        </div>                        
                    </div>
                </center>
            </div>
       </div>
       <div class="row area-terlambat-pulang-cepat" style=" height: 250px; margin-left: 3%;margin-right: 5%;">
            <ul class="nav nav-tabs">               
                <li class="active">
                    <a data-toggle="tab" href="#tab-terlambat" onclick="setcall()">
                        Terlambat
                    </a>
                </li>  
                <li>
                    <a data-toggle="tab" href="#tab-PulangCepat" onclick="setcall()">
                        Pulang Cepat
                    </a>
                </li>  
            </ul>

            <div class="tab-content" style="border: solid black" style="width:100%">
                <div id="tab-terlambat" class="tab-pane fade in active">
                    <div id="wadah">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-responsive table-bordered table-striped table-hover" id="example">
                        <thead>
                            <tr>
                                <?
                                for($i=0; $i < count($arrData); $i++)
                                {
                                    $infolabel= $arrData[$i]["label"];
                                    $infowidth= $arrData[$i]["width"];
                                ?>
                                    <th class="th_like" style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
                                <?
                                }
                                ?>
                            </tr>
                        </thead>
                        </table>
                    </div>
                </div>
                <div id="tab-PulangCepat" class="tab-pane fade" style="width:100%">
                     <div id="wadah">
                        <table cellpadding="0" cellspacing="0" border="0" style="" class="table table-responsive table-bordered table-striped table-hover" id="example2">
                        <thead>
                            <tr>
                                <?
                                for($i=0; $i < count($arrData2); $i++)
                                {
                                    $infolabel= $arrData2[$i]["label"];
                                    $infowidth= $arrData2[$i]["width"];
                                ?>
                                    <th class="th_like" style="<?=$style?>;" width="<?=$infowidth?>px"><?=$infolabel?></th>
                                <?
                                }
                                ?>
                            </tr>
                        </thead>
                        </table>
                    </div>
                </div>
            </div>
       </div>
    </div>
    
    <div class="col-md-4 area-data-pegawai">
    <!--<div class="col-md-4 area-data-pegawai" style="background: linear-gradient(to right,  #05cf55 , #1cb5e0);  height: 518px">-->
        <div class="col-md-12" style="margin-top: 2%;">
           <center><h2>DAFTAR HADIR PEGAWAI</h2></center>
        </div>
        <div class="col-md-12" style="background-color:#075d72;height: 460px;border-radius: 25px;overflow: auto;">
            <?
            while($set->nextRow())
            {
                $reqPegawaiId = $set->getField("PEGAWAI_ID");
                ?>
                <div class="daftar-hadir col-md-12"  style="font-size: 12px;padding-top: 5px;padding-bottom: 5px;">
                    <div class="row" >
                        <div class="col-md-6" style="">
                            <span><?=$set->getField("NAMA_LENGKAP");?></span>
                        </div>
                        <div class="col-md-6" style="text-align: right;">
                            <span><?=$set->getField("TIPE_PRESENSI");?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6" style="">
                            <span><?=$set->getField("MESIN_PRESENSI");?></span>
                        </div>
                        <div class="col-md-6" style="text-align: right;">
                            <span><?=$set->getField("WAKTU");?></span>
                        </div>
                    </div>
                </div>
                <?
            }
            ?>

            <?
            if(empty($reqPegawaiId))
            {
            ?>
            <div class="daftar-hadir col-md-12" style="font-size: 12px;padding-top: 5px;padding-bottom: 5px;">
                <div class="row" >
                    <div class="col-md-6" style="">
                        <span>Tidak Ada Data</span>
                    </div>
                </div>
            </div>
            <?
            }
            ?>


        </div>
    </div>
</body>
        
<div id="wrapper" class="wrapper-corporate">
    
</div>
        
<script type='text/javascript' src="lib/bootstrap/js/jquery.js"></script>

<script type='text/javascript' src="lib/bootstrap/js/bootstrap.js"></script>

    <!-- datatablesss -->
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/media/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/syntax/shCore.css">
    <link rel="stylesheet" type="text/css" href="lib/DataTables-1.10.7/examples/resources/demo.css">
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.7/media/js/jquery.js"></script>

    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/Responsive/js/dataTables.responsive.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/media/js/jquery.dataTables.rowGrouping.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
    <script type="text/javascript" language="javascript" src="lib/DataTables-1.10.6/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<!-- JavaScript jQuery code from Bootply.com editor  -->

<script type='text/javascript'>

$(document).ready(function() {
    // alert("jancookk");
    $("#wrapper").toggleClass("toggled");
    $("#menu-toggle").click(function(e) {
        //alert("hhh");
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
});

</script>

    <script type="text/javascript" charset="utf-8">
        var oTable; 
        var json= "dashboard_json/pegawai_terlambat"; var form= "klarifikasi_masuk_pulang_add";
        var oTable2; 
        var json2= "dashboard_json/pegawai_pulang_cepat"; var form= "klarifikasi_masuk_pulang_add";
        $(document).ready( function () {
                                            
            var id = -1;//simulation of id
            $(window).resize(function() {
              // console.log($(window).height());
              $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
            });
            oTable = $('#example').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
                "aoColumns": [
                <?
                for($col=0; $col<count($arrData); $col++)
                {
                    if($col == 0){}
                    else
                        echo ",";

                    if($arrData[$col]["display"] == "1")
                        echo "{'bVisible': false}";
                    else
                        echo "null";
                }
                ?>
                ],
                // "responsive": true,
                "lengthChange": false,
                "bSort":true,
                "bProcessing": true,
                "bServerSide": true,
                "FixedColumns": true,
                "bFilter": false,
                "sAjaxSource": "main/"+json+"/?reqHari=<?=$reqHari?>&reqPeriode=<?=$reqPeriode?>&reqPeriodeRekap=<?=$reqPeriodeRekap?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
                "sScrollY": ($(window).height() - <?=$tinggi?>),
                "scrollX": true,
                "responsive": false,
                "sScrollX": "100%",                               
                "sScrollXInner": "100%",
                "sPaginationType": "full_numbers",
                "fnDrawCallback": function( oSettings ) {
                    $('#example_filter input').unbind();
                    $('#example_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            setCariInfo();
                        }
                    });
                }
            });
                                            
            var id = -1;//simulation of id
            $(window).resize(function() {
              // console.log($(window).height());
              $('.dataTables_scrollBody').css('height', ($(window).height() - <?=$tinggi?>));
            });
            oTable2 = $('#example2').dataTable({ bJQueryUI: true,"iDisplayLength": 50,
                "aoColumns": [
                <?
                for($col=0; $col<count($arrData2); $col++)
                {
                    if($col == 0){}
                    else
                        echo ",";

                    if($arrData2[$col]["display"] == "1")
                        echo "{'bVisible': false}";
                    else
                        echo "null";
                }
                ?>
                ],
                // "responsive": true,
                "lengthChange": false,
                "bSort":true,
                "bProcessing": true,
                "bServerSide": true,
                "FixedColumns": true,
                "bFilter": false,
                "sAjaxSource": "main/"+json2+"/?reqHari=<?=$reqHari?>&reqPeriode=<?=$reqPeriode?>&reqPeriodeRekap=<?=$reqPeriodeRekap?>&reqSatuanKerjaId=<?=$reqSatuanKerjaId?>",
                "sScrollY": ($(window).height() - <?=$tinggi?>),
                "scrollX": true,
                "responsive": false,
                "sScrollX": "100%",                               
                "sScrollXInner": "100%",
                "sPaginationType": "full_numbers",
                "fnDrawCallback": function( oSettings ) {
                    $('#example_filter input').unbind();
                    $('#example_filter input').bind('keyup', function(e) {
                        if(e.keyCode == 13) {
                            setCariInfo();
                        }
                    });
                }
            });

            // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){

            //     $(".table").resize();
            //     // $($.fn.dataTable.tables(true)).DataTable()
            //     // .columns.adjust()
            //     // .responsive.recalc();

            //   // $($.fn.dataTable.tables(true)).DataTable()
            //   // .columns.adjust();
            // });

            // $('#example2').on('shown.bs.modal', function () {
            //     $(".table").resize()
            // })
            // $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            //   if (e.target.hash == '#tab-PulangCepat') {
            //     oTable2.columns.adjust().draw()
            //     }
            // })

            // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // alert("");
                // $($.fn.dataTable.tables(true)).DataTable()
                // .columns.adjust()
                // .responsive.recalc();
            // });

        });

        // $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        //     alert("")
        //     // $.fn.dataTable.tables({ visible: true, api: true })
        //     // .columns.adjust()
        //     // .responsive.recalc()
        //     // .draw();
        // });

        function setcall()
        {
            // alert("")
            // $.fn.dataTable.tables({ visible: true, api: true })
            // .columns.adjust()
            // .responsive.recalc()
            // .draw();
            $(".table").resize();
        }
    </script>
</body>
</html>