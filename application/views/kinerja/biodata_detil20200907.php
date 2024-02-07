<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$tempUserLoginId= $this->USER_LOGIN_ID;

if($tempUserLoginId == "1" || $tempUserLoginId == "411" || $tempUserLoginId == "359" || $tempUserLoginId == "376"){}
else
{
  redirect('app/index');
  exit;
}

$reqId= $this->input->get("reqId");
$reqTahun= $this->input->get("reqTahun");
$reqKuadran= $this->input->get("reqKuadran");

$this->load->model('talent/Personal');

$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set= new Personal();
$set->selectByParamsPegawai(array(), -1,-1, $statement);
$set->firstRow();
$infopegawainiplama= $set->getField("NIP_LAMA");
if(!empty($infopegawainiplama))
  $infopegawainiplama= $infopegawainiplama." / ";
$infopegawainipbaru= $set->getField("NIP_BARU_FORMAT");
$infopegawainama= $set->getField("NAMA_LENGKAP");
$infopegawaitempatlahir= $set->getField("TEMPAT_LAHIR");
$infopegawaitanggallahir= strtoupper(getFormattedDate($set->getField("TANGGAL_LAHIR")));
$infopegawaistatus= $set->getField("PEGAWAI_STATUS_NAMA");
$infopegawaikedudukan= $set->getField("PEGAWAI_KEDUDUKAN_NAMA");
$infopegawaiagama= $set->getField("AGAMA_NAMA");
$infopegawaipangkatkode= $set->getField("PANGKAT_RIWAYAT_KODE");
$infopegawaipangkattmt= dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
$infopegawaipendidikantingkat= $set->getField("PENDIDIKAN_NAMA");
$infopegawaipendidikanjurusan= $set->getField("PENDIDIKAN_JURUSAN_NAMA");
$infopegawaipendidikansekolah= $set->getField("PENDIDIKAN_SEKOLAH");
$infopegawaipendidikanlulus= $set->getField("PENDIDIKAN_LULUS");
$infopegawaijabatannama= $set->getField("JABATAN_RIWAYAT_NAMA");
$infopegawaijabataneselon= $set->getField("JABATAN_RIWAYAT_ESELON");
$infopegawaijabatantmt= datetimeToPage($set->getField("JABATAN_RIWAYAT_TMT"), "date");
$infopegawaisatuankerja= $set->getField("SATUAN_KERJA_INDUK");
$infopegawaialamat= $set->getField("ALAMAT");
$infopegawaidesa= $set->getField("DESA_NAMA");
$infopegawaikecamatan= $set->getField("KECAMATAN_NAMA");
$infopegawaikabupaten= $set->getField("KABUPATEN_NAMA");
$infopegawaipropinsi= $set->getField("PROPINSI_NAMA");
$infopegawaiimage= $set->getField("PATH");
if(empty($infopegawaiimage))
$infopegawaiimage= "images/foto-profile.jpg";
unset($set);

$arrInfo= array();
$index_data= 0;
$statement= " AND PD.PEGAWAI_ID = ".$reqId;
$set= new Personal();
$set->selectByParamsSpider(array(), -1,-1, $reqTahun, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
  $arrInfo[$index_data]["PENILAIAN_TANGGAL"] = $set->getField("PENILAIAN_TANGGAL");
  $arrInfo[$index_data]["PENILAIAN_JABATAN"] = $set->getField("PENILAIAN_JABATAN");
  $arrInfo[$index_data]["PENILAIAN_UNIT_KERJA"] = $set->getField("PENILAIAN_UNIT_KERJA");
  $arrInfo[$index_data]["TAHUN"] = $set->getField("TAHUN");
  $arrInfo[$index_data]["NILAI_PERILAKU"] = $set->getField("NILAI_PERILAKU");
  $arrInfo[$index_data]["NILAI_PRESTASI"] = $set->getField("NILAI_PRESTASI");
  $arrInfo[$index_data]["KELEBIHAN"] = $set->getField("KELEBIHAN");
  $arrInfo[$index_data]["KEKURANGAN"] = $set->getField("KEKURANGAN");
  $arrInfo[$index_data]["DESKRIPSI"] = $set->getField("DESKRIPSI");
  $arrInfo[$index_data]["ATRIBUT_NAMA"] = $set->getField("ATRIBUT_NAMA");
  $arrInfo[$index_data]["ATRIBUT_NILAI"] = $set->getField("ATRIBUT_NILAI");
  $index_data++;
}
$jumlah_info= $index_data;
// print_r($arrInfo);exit;

if($jumlah_info > 0)
{
  $kinerjatanggal= datetimeToPage($arrInfo[0]["PENILAIAN_TANGGAL"], "date");
  $kinerjajabatan= $arrInfo[0]["PENILAIAN_JABATAN"];
  $kinerjaunitkerja= $arrInfo[0]["PENILAIAN_UNIT_KERJA"];
  $kinerjatahun= $arrInfo[0]["TAHUN"];
  $kinerjaperilaku= $arrInfo[0]["NILAI_PERILAKU"];
  $kinerjaprestasi= $arrInfo[0]["NILAI_PRESTASI"];
  $kinerjakelebihan= $arrInfo[0]["KELEBIHAN"];
  $kinerjakekurangan= $arrInfo[0]["KEKURANGAN"];
  // $kinerjakekurangan= str_replace("   ", "", $kinerjakekurangan);
  $kinerjadeskripsi= $arrInfo[0]["DESKRIPSI"];
}
// echo $kinerjakekurangan;exit;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="lib/bootstrap-3.3.7/docs/favicon.ico">

    <title>Biodata Detail</title>
    <base href="<?=base_url()?>" />

    <!-- Bootstrap core CSS -->
    <link href="lib/bootstrap-3.3.7/docs/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="lib/bootstrap-3.3.7/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="lib/bootstrap-3.3.7/docs/examples/starter-template/starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style type="text/css">
		.navbar-brand{
			padding: 0 0;
		}
		.navbar-inverse {
			background-color: #605ca8;
			border-color: #605ca8;
		}
		.page-header{
			text-transform: uppercase;
			margin-top: 20px;
		}
		.control-label{
			*text-align: left !important;
		}
		.control-label.text-left{
			text-align: left;
			font-weight: normal;
		}
		table.table th{
			vertical-align: middle !important;
			background: #EEEEEE; 
      text-align: center;
		}
		.col-md-5 {
			*border: 1px solid red; 
		}
		@media screen and (max-width:767px) {
			label.control-label{
				width: 100%;
			}
			label.control-label.text-left{
				font-weight: normal;
			}
		}
		
    </style>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="images/logo.png"></a>
        </div>
        <?php /*?><div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse --><?php */?>
      </div>
    </nav>

    <div class="container">
    	<form class="form-horizontal" action="">
    	<div class="row">
			<div class="col-md-12">&nbsp;</div>
          	
            <div class="col-sm-5 col-sm-push-7">
            	<div class="foto"><img src="<?=$infopegawaiimage?>" width="200"></div>
            </div>
		    <div class="col-sm-7 col-sm-pull-5">
            	<div class="page-header">
                  <h4>Biodata</h4>
                </div>
            	<div class="form-group">
                    <!--<label class="control-label col-sm-1">a.</label>
                    <label class="control-label col-sm-3">NIP Baru / NIP Lama:</label>
                    <label class="control-label col-sm-8 text-left">19830502 201101 1 001</label>-->
                    <label class="control-label col-sm-4">NIP Baru / NIP Lama:</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawainiplama?><?=$infopegawainipbaru?></label>
                  <!--</div>
                  <div class="form-group">-->
                    <label class="control-label col-sm-4">Nama      :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawainama?></label>
                  <!--</div>

                  <div class="form-group">-->
                    <label class="control-label col-sm-4"> Tempat Lahir :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaitempatlahir?></label>
				<!--</div>
                
                <div class="form-group">-->
                    <label class="control-label col-sm-4">Tanggal Lahir :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaitanggallahir?></label>
                  <!--</div>

                  <div class="form-group">-->
                    <label class="control-label col-sm-4">Status Pegawai      :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaistatus?></label>
				<!--</div>
                
                <div class="form-group">-->
                    <label class="control-label col-sm-4">Kedudukan      :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaikedudukan?></label>

                  <!--</div>

                  <div class="form-group">-->
                    <label class="control-label col-sm-4">Agama :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaiagama?></label>
                  </div>
            </div>
		</div>
        <div class="page-header">
          <h4>Pangkat</h4>
        </div>
        <div class="row">
            <div class="col-md-7">
            	
                  <div class="form-group">
                    <label class="control-label col-sm-4">Pangkat Terakhir :</label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaipangkatkode?></label>
                     
                  </div>
            </div>
            <div class="col-md-5">
            	<div class="form-group">
                    <label class="control-label col-sm-5">TMT Pangkat Terakhir      :</label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaipangkattmt?></label>
                     
                  </div>
            </div>
		</div>
        <div class="page-header">
          <h4>Pendidikan</h4>
        </div>
        <div class="row">
            <div class="col-md-7">
            	<div class="form-group">

                    <!--<label class="control-label col-sm-4">Pendidikan Terakhir </label>
                    <label class="control-label col-sm-8 text-left">&nbsp;</label>-->

                    <label class="control-label col-sm-4">Tingkat Pendidikan      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaipendidikantingkat?></label>
                    
                    <label class="control-label col-sm-4">Fakultas/Jurusan      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaipendidikanjurusan?></label>
                  </div>
 
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <!--<label class="control-label col-sm-12">&nbsp;</label>-->
                    
                    <label class="control-label col-sm-5">Sekolah/Universitas     : </label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaipendidikansekolah?></label>

                    <label class="control-label col-sm-5">Tahun Lulus     : </label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaipendidikanlulus?></label>
				</div>
            </div>
            
        </div>
        <div class="page-header">
          <h4>Jabatan</h4>
        </div>
        <div class="row">
        	<div class="col-md-7">
            	<div class="form-group">
                    <label class="control-label col-sm-4">Jabatan Terakhir      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaijabatannama?></label>

                    <label class="control-label col-sm-4">Eselon      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaijabataneselon?></label>
                  </div>
            </div>
            <div class="col-md-5">
            	<div class="form-group">
                    <label class="control-label col-sm-5">TMT Jabatan :</label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaijabatantmt?></label>

                    <label class="control-label col-sm-5">Unit Kerja :</label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaisatuankerja?></label>
                </div>
            </div>
        </div>
        <div class="page-header">
          <h4>Alamat</h4>
        </div>
        <div class="row">
        	<div class="col-md-7">
            	<div class="form-group">
                    <label class="control-label col-sm-4">Alamat Rumah      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaialamat?></label>

                    <label class="control-label col-sm-4">Desa      : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaidesa?></label>

                    <label class="control-label col-sm-4">Kecamatan     : </label>
                    <label class="control-label col-sm-8 text-left"><?=$infopegawaikecamatan?></label>
				</div> 
            </div>
            <div class="col-md-5">
            	<div class="form-group">
                    <label class="control-label col-sm-12">&nbsp;</label>

                    <label class="control-label col-sm-5">Kabupaten     : </label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaikabupaten?></label>

                    <label class="control-label col-sm-5">Propinsi      : </label>
                    <label class="control-label col-sm-7 text-left"><?=$infopegawaipropinsi?></label>
				</div> 
            </div>
        </div>
        <hr>
        </form>

        <div class="row">
          <div class="col-md-4">
            <div class="alert alert-success">KUADRAN <?=romanic_number($reqKuadran)?></div>
          </div>
          <div class="col-md-8">
            <div class="alert alert-warning">Pimpinan Tinggi Masa Depan (Berpotensi untuk dipromosikan ke level manajemen puncak)</div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4">
          	<div id="container"></div>
          </div>
          <div class="col-md-3">
          	<div class="table-responsive">
            <table class="table" cellspacing="0" cellpadding="0">
              <?
              for($index_data=0; $index_data < $jumlah_info; $index_data++)
              {
              ?>
              <tr>
                <td><?=$arrInfo[$index_data]["ATRIBUT_NAMA"]?></td>
                <td>:</td>
                <td><?=$arrInfo[$index_data]["ATRIBUT_NILAI"]?></td>
              </tr>
              <?
              }
              ?>
            </table>
            </div>
          </div>
          <div class="col-md-2">
          	<div class="alert alert-info text-center" role="alert">
              <h4 style="margin-bottom: 0px;"><?=$kinerjaperilaku?></h4>
            </div>
            <div class="alert alert-info text-center" role="alert">
              <h4 style="margin-bottom: 0px;"><?=$kinerjaprestasi?></h4>
            </div>
            
          </div>
          <div class="col-md-3">
          	<div class="table-responsive">
            <table class="table" cellspacing="0" cellpadding="0">
              <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td><?=$kinerjatanggal?></td>
              </tr>
              <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?=$kinerjajabatan?></td>
              </tr>
              <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td><?=$kinerjaunitkerja?></td>
              </tr>
              <tr>
                <td>Tahun   Kinerja</td>
                <td>:</td>
                <td><?=$kinerjatahun?></td>
              </tr>
            </table>
            </div>
          </div>
		</div>
        
        <div class="row">
          <div class="col-md-12">
          	<div class="page-header">
            	<h4>Kelebihan</h4>
            </div>
            <div class="alert alert-success">
              <ul>
                <?
                $arrkelebihan= explode("-", $kinerjakelebihan);
                for($i= 0; $i<count($arrkelebihan); $i++)
                {
                  if(empty($arrkelebihan[$i]))
                    continue;
                ?>
                  <li><?=$arrkelebihan[$i]?></li>
                <?
                }
                ?>
              </ul>
            </div>
          </div>

          <div class="col-md-12">
          	<div class="page-header">
            	<h4>Kekurangan</h4>
            </div>
            <div class="alert alert-danger">
              <ul>
                <?
                $arrkekurangan= explode("-", $kinerjakekurangan);
                for($i= 0; $i<count($arrkekurangan); $i++)
                {
                  if(empty($arrkekurangan[$i]))
                    continue;
                ?>
                  <li><?=$arrkekurangan[$i]?></li>
                <?
                }
                ?>
              </ul>
            </div>

          </div>

          <div class="col-md-12">
          	<div class="page-header">
            	<h4>Deskripsi Psikologis</h4>
            </div>
            <div class="alert alert-info"><?=$kinerjadeskripsi?></div>
          </div>
          
          <div class="col-md-12">
          	<div class="page-header">
              <h4>II. Riwayat Kepangkatan</h4>
            </div>
          	<div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Gol. Ruang</th>
                  <th>TMT</th>
                  <th colspan="2">Surat Keputusan</th>
                  <th rowspan="2">Jenis KP</th>
                  <th rowspan="2">Pejabat Yang Menetapkan</th>
                </tr>
                <tr>
                  <th>Pangkat</th>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"PANGKAT_KODE", "align"=>"center", "format"=>"")
                    , array("field"=>"TMT_PANGKAT", "align"=>"center", "format"=>"date")
                    , array("field"=>"NO_SK", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_SK", "align"=>"center", "format"=>"date")
                    , array("field"=>"JENIS_RIWAYAT_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PEJABAT_PENETAP_NAMA", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;
                $set= new Personal();
                $set->selectByParamsPangkat(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);
                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="<?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>III. Riwayat Jabatan Struktural / Fungsional</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Jabatan / TMT / Eselon</th>
                  <th rowspan="2">Unit Kerja</th>
                  <th colspan="2">Surat Keputusan</th>
                  <th rowspan="2">Jenis Jab</th>
                  <th rowspan="2">Pejabat Yang Menetapkan</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"SATUAN_KERJA_INDUK", "align"=>"", "format"=>"")
                    , array("field"=>"NO_SK", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_SK", "align"=>"center", "format"=>"datetime")
                    , array("field"=>"JENIS_JABATAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PEJABAT_PENETAP_NAMA", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;
                $set= new Personal();
                $set->selectByParamsJabatan(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "NAMA")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".datetimeToPage($set->getField("TMT_JABATAN"), "date")."<br/>".$set->getField("ESELON_NAMA");
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="<?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>IV. Riwayat Tugas</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Jabatan / TMT</th>
                  <th rowspan="2">Unit Kerja</th>
                  <th colspan="2">Surat Keputusan</th>
                  <th rowspan="2">Jenis Jab</th>
                  <th rowspan="2">Pejabat Yang Menetapkan</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"SATUAN_KERJA_INDUK", "align"=>"", "format"=>"")
                    , array("field"=>"NO_SK", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_SK", "align"=>"center", "format"=>"datetime")
                    , array("field"=>"STATUS_PLT_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PEJABAT_PENETAP_NAMA", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;
                $set= new Personal();
                $set->selectByParamsTugas(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "NAMA")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".datetimeToPage($set->getField("TMT_JABATAN"), "date");
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="<?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>V. Riwayat Pendidikan</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Tk Pendidikan</th>
                  <th rowspan="2">Jurusan</th>
                  <th rowspan="2">Nama Asal Sekolah</th>
                  <th rowspan="2">Status Pendidikan</th>
                  <th colspan="2">STTB / Ijasah</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"PENDIDIKAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PENDIDIKAN_JURUSAN_NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"STATUS_PENDIDIKAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"NO_STTB", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_STTB", "align"=>"center", "format"=>"datetime")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;
                $set= new Personal();
                $set->selectByParamsPendidikan(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "NAMA")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".datetimeToPage($set->getField("TMT_JABATAN"), "date");
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="<?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>VI. Riwayat Diklat Struktural</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Diklat</th>
                  <th rowspan="2">Tempat / Penyelenggara</th>
                  <th rowspan="2">Angkatan/<br/>Tgl Diklat</th>
                  <th rowspan="2">Jam</th>
                  <th colspan="2">STTPP</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT", "align"=>"", "format"=>"")
                    , array("field"=>"ANGKATAN", "align"=>"center", "format"=>"")
                    , array("field"=>"JUMLAH_JAM", "align"=>"center", "format"=>"")
                    , array("field"=>"NO_STTPP", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_STTPP", "align"=>"center", "format"=>"date")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;
                $set= new Personal();
                $set->selectByParamsStruktural(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT")
                    {
                      $infonama= $set->getField($arrdata[$x]["field"]);
                      if(!empty($set->getField("PENYELENGGARA")))
                      {
                        $infonama.= " / ".$set->getField("PENYELENGGARA");
                      }
                    }
                    elseif($arrdata[$x]["field"] == "ANGKATAN")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_MULAI"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>VII. Riwayat Diklat Fungsional</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Diklat</th>
                  <th rowspan="2">Tempat / Penyelenggara</th>
                  <th rowspan="2">Angkatan/<br/>Tgl Diklat</th>
                  <th rowspan="2">Jam</th>
                  <th colspan="2">STTPP</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT", "align"=>"", "format"=>"")
                    , array("field"=>"ANGKATAN", "align"=>"center", "format"=>"")
                    , array("field"=>"JUMLAH_JAM", "align"=>"center", "format"=>"")
                    , array("field"=>"NO_STTPP", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_STTPP", "align"=>"center", "format"=>"date")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsFungsional(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT")
                    {
                      $infonama= $set->getField($arrdata[$x]["field"]);
                      if(!empty($set->getField("PENYELENGGARA")))
                      {
                        $infonama.= " / ".$set->getField("PENYELENGGARA");
                      }
                    }
                    elseif($arrdata[$x]["field"] == "ANGKATAN")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_MULAI"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>VIII. Riwayat Diklat Teknis</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Diklat</th>
                  <th rowspan="2">Tempat / Penyelenggara</th>
                  <th rowspan="2">Angkatan/<br/>Tgl Diklat</th>
                  <th rowspan="2">Jam</th>
                  <th colspan="2">STTPP</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT", "align"=>"", "format"=>"")
                    , array("field"=>"ANGKATAN", "align"=>"center", "format"=>"")
                    , array("field"=>"JUMLAH_JAM", "align"=>"center", "format"=>"")
                    , array("field"=>"NO_STTPP", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_STTPP", "align"=>"center", "format"=>"date")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsTeknis(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT")
                    {
                      $infonama= $set->getField($arrdata[$x]["field"]);
                      if(!empty($set->getField("PENYELENGGARA")))
                      {
                        $infonama.= " / ".$set->getField("PENYELENGGARA");
                      }
                    }
                    elseif($arrdata[$x]["field"] == "ANGKATAN")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_MULAI"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="7" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>IX. Kursus</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Diklat</th>
                  <th rowspan="2">Tempat / Penyelenggara</th>
                  <th rowspan="2">Tgl Diklat</th>
                  <th colspan="2">STTPP</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_MULAI", "align"=>"center", "format"=>"date")
                    , array("field"=>"NO_PIAGAM", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_PIAGAM", "align"=>"center", "format"=>"date")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsKursus(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT")
                    {
                      $infonama= $set->getField($arrdata[$x]["field"]);
                      if(!empty($set->getField("PENYELENGGARA")))
                      {
                        $infonama.= " / ".$set->getField("PENYELENGGARA");
                      }
                    }
                    elseif($arrdata[$x]["field"] == "ANGKATAN")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_MULAI"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="6" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>X. Seminar/Lokakarya/Simposium</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Diklat</th>
                  <th rowspan="2">Tempat / Penyelenggara</th>
                  <th rowspan="2">Tgl Diklat</th>
                  <th colspan="2">STTPP</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_MULAI", "align"=>"center", "format"=>"date")
                    , array("field"=>"NO_PIAGAM", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_PIAGAM", "align"=>"center", "format"=>"date")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsSeminar(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT")
                    {
                      $infonama= $set->getField($arrdata[$x]["field"]);
                      if(!empty($set->getField("PENYELENGGARA")))
                      {
                        $infonama.= " / ".$set->getField("PENYELENGGARA");
                      }
                    }
                    elseif($arrdata[$x]["field"] == "ANGKATAN")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_MULAI"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="6" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XI. Riwayat Keluarga</h4>
            </div>
            <?
            $statement= " AND JENIS_KELAMIN = 'L' AND A.PEGAWAI_ID = ".$reqId;
            $set_ol= new Personal();
            $set_ol->selectByParamsOrangTua(array(), -1,-1, $statement);
            $set_ol->firstRow();
            $olnama= $set_ol->getField("NAMA");
            $oltempatlahir= $set_ol->getField("TEMPAT_LAHIR");
            $oltanggallahir= dateToPageCheck($set_ol->getField("TANGGAL_LAHIR"));
            $olpekerjaaan= $set_ol->getField("PEKERJAAN");
            $olalamat= $set_ol->getField("ALAMAT");
            $oltelepon= $set_ol->getField("TELEPON");
            $oldesa= $set_ol->getField("DESA_NAMA");
            $olkecamatan= $set_ol->getField("KECAMATAN_NAMA");
            $olkabupaten= $set_ol->getField("KABUPATEN_NAMA");
            $olpropinsi= $set_ol->getField("PROPINSI_NAMA");
            $olkodepos= $set_ol->getField("KODEPOS");
            unset($set_ol);

            $statement= " AND JENIS_KELAMIN = 'P' AND A.PEGAWAI_ID = ".$reqId;
            $set_op= new Personal();
            $set_op->selectByParamsOrangTua(array(), -1,-1, $statement);
            $set_op->firstRow();
            $opnama= $set_op->getField("NAMA");
            $optempatlahir= $set_op->getField("TEMPAT_LAHIR");
            $optanggallahir= dateToPageCheck($set_op->getField("TANGGAL_LAHIR"));
            $oppekerjaaan= $set_op->getField("PEKERJAAN");
            $opalamat= $set_op->getField("ALAMAT");
            $optelepon= $set_op->getField("TELEPON");
            $opdesa= $set_op->getField("DESA_NAMA");
            $opkecamatan= $set_op->getField("KECAMATAN_NAMA");
            $opkabupaten= $set_op->getField("KABUPATEN_NAMA");
            $oppropinsi= $set_op->getField("PROPINSI_NAMA");
            $opkodepos= $set_op->getField("KODEPOS");
            unset($set_op);

            $statement= " AND JENIS_KELAMIN = 'L' AND A.PEGAWAI_ID = ".$reqId;
            $set_ml= new Personal();
            $set_ml->selectByParamsMertua(array(), -1,-1, $statement);
            $set_ml->firstRow();
            $mlnama= $set_ml->getField("NAMA");
            $mltempatlahir= $set_ml->getField("TEMPAT_LAHIR");
            $mltanggallahir= dateToPageCheck($set_ml->getField("TANGGAL_LAHIR"));
            $mlpekerjaaan= $set_ml->getField("PEKERJAAN");
            $mlalamat= $set_ml->getField("ALAMAT");
            $mltelepon= $set_ml->getField("TELEPON");
            $mldesa= $set_ml->getField("DESA_NAMA");
            $mlkecamatan= $set_ml->getField("KECAMATAN_NAMA");
            $mlkabupaten= $set_ml->getField("KABUPATEN_NAMA");
            $mlpropinsi= $set_ml->getField("PROPINSI_NAMA");
            $mlkodepos= $set_ml->getField("KODEPOS");
            unset($set_ml);

            $statement= " AND JENIS_KELAMIN = 'P' AND A.PEGAWAI_ID = ".$reqId;
            $set_mp= new Personal();
            $set_mp->selectByParamsMertua(array(), -1,-1, $statement);
            $set_mp->firstRow();
            $mpnama= $set_mp->getField("NAMA");
            $mptempatlahir= $set_mp->getField("TEMPAT_LAHIR");
            $mptanggallahir= dateToPageCheck($set_mp->getField("TANGGAL_LAHIR"));
            $mppekerjaaan= $set_mp->getField("PEKERJAAN");
            $mpalamat= $set_mp->getField("ALAMAT");
            $mptelepon= $set_mp->getField("TELEPON");
            $mpdesa= $set_mp->getField("DESA_NAMA");
            $mpkecamatan= $set_mp->getField("KECAMATAN_NAMA");
            $mpkabupaten= $set_mp->getField("KABUPATEN_NAMA");
            $mppropinsi= $set_mp->getField("PROPINSI_NAMA");
            $mpkodepos= $set_mp->getField("KODEPOS");
            unset($set_mp);
            ?>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
                <tbody>
                <tr><td colspan="3"></td><td style="text-align: center;">Ayah</td><td style="text-align: center;">Ibu</td></tr>
                <tr><th>1</th><th style="text-align: left;" colspan="2">Nama</th><td><?=$olnama?></td><td><?=$opnama?></td></tr>
                <tr><th>2</th><th style="text-align: left;" colspan="2">Tempat Lahir</th><td><?=$oltempatlahir?></td><td><?=$optempatlahir?></td></tr>
                <tr><th>3</th><th style="text-align: left;" colspan="2">Tanggal Lahir</th><td><?=$oltanggallahir?></td><td><?=$optanggallahir?></td></tr>
                <tr><th>4</th><th style="text-align: left;" colspan="2">Pekerjaan</th><td><?=$olpekerjaaan?></td><td><?=$oppekerjaaan?></td></tr>
                <tr><th>5</th><th style="text-align: left;" colspan="2">Alamat</th><td><?=$olalamat?></td><td><?=$opalamat?></td></tr>
                <tr><th></th><th>a.</th><th style="text-align: left;">Telepon</th><td><?=$oltelepon?></td><td><?=$optelepon?></td></tr>
                <tr><th></th><th>b.</th><th style="text-align: left;">Desa</th><td><?=$oldesa?></td><td><?=$opdesa?></td></tr>
                <tr><th></th><th>c.</th><th style="text-align: left;">Kecamatan</th><td><?=$olkecamatan?></td><td><?=$opkecamatan?></td></tr>
                <tr><th></th><th>d.</th><th style="text-align: left;">Kabupaten</th><td><?=$olkabupaten?></td><td><?=$opkabupaten?></td></tr>
                <tr><th></th><th>e.</th><th style="text-align: left;">Propinsi</th><td><?=$olpropinsi?></td><td><?=$oppropinsi?></td></tr>
                <tr><th></th><th>f.</th><th style="text-align: left;">Kode pos</th><td><?=$olkodepos?></td><td><?=$opkodepos?></td></tr>
                <tr><td colspan="3"></td><td style="text-align: center;">Ayah Mertua</td><td style="text-align: center;">Ibu Mertua</td></tr>
                <tr><th>1</th><th style="text-align: left;" colspan="2">Nama</th><td><?=$mlnama?></td><td><?=$mpnama?></td></tr>
                <tr><th>2</th><th style="text-align: left;" colspan="2">Tempat Lahir</th><td><?=$mltempatlahir?></td><td><?=$mptempatlahir?></td></tr>
                <tr><th>3</th><th style="text-align: left;" colspan="2">Tanggal Lahir</th><td><?=$mltanggallahir?></td><td><?=$mptanggallahir?></td></tr>
                <tr><th>4</th><th style="text-align: left;" colspan="2">Pekerjaan</th><td><?=$mlpekerjaaan?></td><td><?=$mppekerjaaan?></td></tr>
                <tr><th>5</th><th style="text-align: left;" colspan="2">Alamat</th><td><?=$mlalamat?></td><td><?=$mpalamat?></td></tr>
                <tr><th></th><th>a.</th><th style="text-align: left;">Telepon</th><td><?=$mltelepon?></td><td><?=$mptelepon?></td></tr>
                <tr><th></th><th>b.</th><th style="text-align: left;">Desa</th><td><?=$mldesa?></td><td><?=$mpdesa?></td></tr>
                <tr><th></th><th>c.</th><th style="text-align: left;">Kecamatan</th><td><?=$mlkecamatan?></td><td><?=$mpkecamatan?></td></tr>
                <tr><th></th><th>d.</th><th style="text-align: left;">Kabupaten</th><td><?=$mlkabupaten?></td><td><?=$mpkabupaten?></td></tr>
                <tr><th></th><th>e.</th><th style="text-align: left;">Propinsi</th><td><?=$mlpropinsi?></td><td><?=$mppropinsi?></td></tr>
                <tr><th></th><th>f.</th><th style="text-align: left;">Kode pos</th><td><?=$mlkodepos?></td><td><?=$mpkodepos?></td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XII. Data Suami / Istri</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Suami / Istri</th>
                  <th>Tempat dan Tanggal Lahir</th>
                  <th>Pendidikan</th>
                  <th>Tanggal Kawin</th>
                  <th>Status</th>
                  <th>Tunjangan</th>
                  <th>Pekerjaan</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT_LAHIR", "align"=>"", "format"=>"")
                    , array("field"=>"PENDIDIKAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"TANGGAL_KAWIN", "align"=>"center", "format"=>"date")
                    , array("field"=>"STATUS_S_I_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"STATUS_TUNJANGAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PEKERJAAN", "align"=>"", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsSuamiIstri(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT_LAHIR")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_LAHIR"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="8" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XIII. Data Anak</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Anak</th>
                  <th rowspan="2">Tempat / Tanggal Lahir</th>
                  <th rowspan="2">L/P</th>
                  <th colspan="2">Tunjangan</th>
                  <th rowspan="2">Pendidikan</th>
                  <th rowspan="2">Pekerjaan</th>
                </tr>
                <tr>
                  <th>Status Keluarga</th>
                  <th>Tunjangan</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TEMPAT_LAHIR", "align"=>"", "format"=>"")
                    , array("field"=>"JENIS_KELAMIN", "align"=>"center", "format"=>"")
                    , array("field"=>"STATUS_KELUARGA_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"STATUS_TUNJANGAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PENDIDIKAN_NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"PEKERJAAN", "align"=>"", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsAnak(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    if($arrdata[$x]["field"] == "TEMPAT_LAHIR")
                      $infonama= $set->getField($arrdata[$x]["field"])."<br/>".dateToPageCheck($set->getField("TANGGAL_LAHIR"));
                    else
                      $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="8" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XIV. Riwayat Penghargaan</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Nama Penghargaan</th>
                  <th colspan="2">Surat Keputusan</th>
                  <th rowspan="2">Pejabat Yang Menetapkan</th>
                  <th rowspan="2">Tahun</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"NAMA_NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"NO_SK", "align"=>"", "format"=>"")
                    , array("field"=>"TANGGAL_SK", "align"=>"center", "format"=>"date")
                    , array("field"=>"PEJABAT_PENETAP_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"TAHUN", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsPenghargaan(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="6" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XV. Riwayat Daftar Penilaian Pelaksanaan Pekerjaan</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
                <tr>
                  <th>No.</th>
                  <th>Tahun</th>
                  <th>Jumlah SKP</th>
                  <th>N1</th>
                  <th>N2</th>
                  <th>N3</th>
                  <th>N4</th>
                  <th>N5</th>
                  <th>N6</th>
                  <th>Nilai Rata-rata</th>
                  <th>Nilai Prestasi Kerja</th>
                  <th>Rekomendasi / Keterangan</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"TAHUN", "align"=>"center", "format"=>"")
                    , array("field"=>"SKP_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"ORIENTASI_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"INTEGRITAS_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"KOMITMEN_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"DISIPLIN_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"KERJASAMA_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"KEPEMIMPINAN_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"RATA_NILAI", "align"=>"center", "format"=>"")
                    , array("field"=>"PRESTASI_HASIL", "align"=>"center", "format"=>"")
                    , array("field"=>"REKOMENDASI", "align"=>"", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsPenilaianSkp(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);

                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="12" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                <tr><td colspan="12"></td></tr>
                <tr><td colspan="4">Keterangan</td></tr>
                <tr><td>N1 :</td><td colspan="3">Orientasi Pelayanan</td></tr>
                <tr><td>N2 :</td><td colspan="3">Integritas</td></tr>
                <tr><td>N3 :</td><td colspan="3">Komitmen</td></tr>
                <tr><td>N4 :</td><td colspan="3">Disiplin</td></tr>
                <tr><td>N5 :</td><td colspan="3">Kerjasama</td></tr>
                <tr><td>N6 :</td><td colspan="3">Kepemimpinan</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XVI. Riwayat Hukuman Disiplin Pegawai</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Tingkat Hukdis</th>
                  <th rowspan="2">Jenis Hukdis</th>
                  <th rowspan="2">TMT Hukdis</th>
                  <th colspan="2">Surat Keputusan</th>
                  <th rowspan="2">Berlaku</th>
                  <th rowspan="2">Pejabat Yang Menetapkan</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"TINGKAT_HUKUMAN_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"JENIS_HUKUMAN_NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"TMT_SK", "align"=>"center", "format"=>"date")
                    , array("field"=>"NO_SK", "align"=>"center", "format"=>"")
                    , array("field"=>"TANGGAL_SK", "align"=>"center", "format"=>"date")
                    , array("field"=>"BERLAKU_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"PEJABAT_PENETAP_NAMA", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsHukuman(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);
                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="8" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XVII. Riwayat Cuti</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th rowspan="2">No.</th>
                  <th rowspan="2">Tahun</th>
                  <th rowspan="2">Jenis Cuti</th>
                  <th colspan="2">Surat Cuti</th>
                  <th rowspan="2">Tgl Mulai</th>
                  <th rowspan="2">Tgl Selesai</th>
                  <th rowspan="2">Lama Cuti</th>
                  <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                  <th>Nomor</th>
                  <th>Tanggal</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"TAHUN", "align"=>"center", "format"=>"")
                    , array("field"=>"JENIS_CUTI_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"NO_SURAT", "align"=>"center", "format"=>"")
                    , array("field"=>"TANGGAL_SURAT", "align"=>"center", "format"=>"date")
                    , array("field"=>"TANGGAL_MULAI", "align"=>"center", "format"=>"date")
                    , array("field"=>"TANGGAL_SELESAI", "align"=>"center", "format"=>"date")
                    , array("field"=>"LAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"KETERANGAN", "align"=>"", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsCuti(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);
                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="9" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <div class="page-header">
              <h4>XVIII. Riwayat Penguasaan Bahasa</h4>
            </div>
            <div class="table-responsive">
              <table class="table" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Jenis Bahasa</th>
                  <th>Nama Bahasa</th>
                  <th>Kemampuan Bicara</th>
                </tr>
                </thead>
                <tbody>
                <?
                $arrdata= array(
                    array("field"=>"JENIS_NAMA", "align"=>"center", "format"=>"")
                    , array("field"=>"NAMA", "align"=>"", "format"=>"")
                    , array("field"=>"KEMAMPUAN_NAMA", "align"=>"center", "format"=>"")
                );

                $statement= " AND A.PEGAWAI_ID = ".$reqId;

                $set= new Personal();
                $set->selectByParamsBahasa(array(), -1,-1, $statement);
                $nomor= 1;
                while ($set->nextRow())
                {
                ?>
                <tr>
                  <td style="vertical-align: middle;"><?=$nomor?></td>
                  <?
                  for($x=0; $x < count($arrdata); $x++)
                  {
                    $infonama= $set->getField($arrdata[$x]["field"]);
                    $infoalign= $arrdata[$x]["align"];
                    $infoformat= $arrdata[$x]["format"];

                    if($infoformat == "date")
                    {
                      $infonama= dateToPageCheck($infonama);
                    }
                    elseif($infoformat == "datetime")
                    {
                      $infonama= datetimeToPage($infonama, "date");
                    }

                    $stylealign= "";
                    if(!empty($infoalign))
                      $stylealign= "text-align: ".$infoalign.";";
                  ?>
                  <td style="vertical-align: middle; <?=$stylealign?>"><?=$infonama?></td>
                  <?
                  }
                  ?>
                </tr>
                <?
                  $nomor++;
                }
                if($nomor == 1)
                {
                ?>
                <tr>
                  <td colspan="4" style="text-align: center;">Data tidak ada</td>
                </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div>
          </div>
                                          
        </div>

      <!-- <div class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
      </div> -->

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="lib/highcharts/jquery.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="lib/bootstrap-3.3.7/docs/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="lib/bootstrap-3.3.7/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    
    <!-- HIGHCHART -->
    <script src="lib/highcharts/jquery-3.1.1.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> -->
    
    <script src="lib/highcharts/highcharts-spider.js"></script>
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <script src="lib/highcharts/highcharts-more.js"></script>
    <!-- <script src="https://code.highcharts.com/highcharts-more.js"></script> -->
    <script src="lib/highcharts/exporting-spider.js"></script>
    <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
    <script src="lib/highcharts/export-data.js"></script>
    <!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
    <script src="lib/highcharts/accessibility.js"></script>
    <!-- <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->
    
    <script>
	  Highcharts.chart('container', {

		chart: {
			polar: true,
			type: 'line'
		},
	
		// accessibility: {
		// 	description: 'A spiderweb chart compares the allocated budget against actual spending within an organization. The spider chart has six spokes. Each spoke represents one of the 6 departments within the organization: sales, marketing, development, customer support, information technology and administration. The chart is interactive, and each data point is displayed upon hovering. The chart clearly shows that 4 of the 6 departments have overspent their budget with Marketing responsible for the greatest overspend of $20,000. The allocated budget and actual spending data points for each department are as follows: Sales. Budget equals $43,000; spending equals $50,000. Marketing. Budget equals $19,000; spending equals $39,000. Development. Budget equals $60,000; spending equals $42,000. Customer support. Budget equals $35,000; spending equals $31,000. Information technology. Budget equals $17,000; spending equals $26,000. Administration. Budget equals $10,000; spending equals $14,000.'
		// },
	
		title: {
			text: '',
			x: -80
		},
	
		pane: {
			size: '80%'
		},
	
		xAxis: {
			categories: [
      <?
      for($index_data=0; $index_data < $jumlah_info; $index_data++)
      {
        if($index_data > 0)
          echo ",";
      ?>
      '<?=$arrInfo[$index_data]["ATRIBUT_NAMA"]?>'
      <?
      }
      ?>
      ],
			tickmarkPlacement: 'on',
			lineWidth: 0
		},

    credits: {
      enabled: false
    },
	
		yAxis: {
			gridLineInterpolation: 'polygon',
			lineWidth: 0,
			min: 0
		},
	
		tooltip: {
			shared: true,
			pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.0f}</b><br/>'
		},
	
		legend: {
			align: 'right',
			verticalAlign: 'middle',
			layout: 'vertical'
		},
	
		series: [
    {
			name: '',
			data: [
      <?
      for($index_data=0; $index_data < $jumlah_info; $index_data++)
      {
        if($index_data > 0)
          echo ",";
      ?>
      <?=$arrInfo[$index_data]["ATRIBUT_NILAI"]?>
      <?
      }
      ?>
      ],
			pointPlacement: 'on'
		}
    /*, 
    {
			name: 'Actual Spending',
			data: [50000, 39000, 42000, 31000, 26000, 14000],
			pointPlacement: 'on'
		}*/
    ],
	
		responsive: {
			rules: [{
				condition: {
					maxWidth: 500
				},
				chartOptions: {
					legend: {
						align: 'center',
						verticalAlign: 'bottom',
						layout: 'horizontal'
					},
					pane: {
						size: '70%'
					}
				}
			}]
		}
	
	});
	</script>
    
    
  </body>
</html>
