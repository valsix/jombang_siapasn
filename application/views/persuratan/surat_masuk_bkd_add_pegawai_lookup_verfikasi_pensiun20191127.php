<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
$this->load->model('persuratan/CetakIjinBelajar');
$this->load->model('persuratan/UsulanSurat');
$this->load->model('persuratan/Pensiun');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawai();
$set->selectByParamsMonitoring(array(), -1, -1, $statement);
$set->firstRow();
// echo $set->query;exit;
// NOMOR
$reqPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqPegawaiJabatan= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPendidikanNamaUsulan= $set->getField('PENDIDIKAN_NAMA_US');
$reqPendidikanJurusanUsulan= $set->getField('JURUSAN_US');
$reqStatusTms= $set->getField('STATUS_TMS');
$reqStatusKembali= $set->getField('STATUS_KEMBALI');
$reqStatusVerifikasi= $set->getField('STATUS_VERIFIKASI');
$reqStatusSuratKeluar= $set->getField('STATUS_SURAT_KELUAR');
//echo "--".$reqStatusVerifikasi;exit;
$reqKeteranganTeknis= $set->getField('KETERANGAN_TEKNIS');
$reqTahunSurat= $set->getField('TAHUN_SURAT');
$reqJenisKarpeg= $set->getField('JENIS_KARPEG');
$reqJenisKarpegNama= $set->getField('JENIS_KARPEG_NAMA');
$reqPangkatRiwayatAkhir= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_USULAN_NAMA');
$reqKeterangan= $set->getField('KETERANGAN');
$reqNoSuratKehilangan= $set->getField('NO_SURAT_KEHILANGAN');
$reqTanggalSuratKehilangan= dateToPageCheck($set->getField('TANGGAL_SURAT_KEHILANGAN'));
$reqUsulanSuratId= $set->getField('USULAN_SURAT_ID');
$reqStatusKirimUsulan= $set->getField('STATUS_KIRIM_USULAN');
$reqKategori= $set->getField('KATEGORI');
$reqKategoriNama= $set->getField('KATEGORI_NAMA');
unset($set);

$statement= " AND A.USULAN_SURAT_ID = ".$reqUsulanSuratId."";
$set= new UsulanSurat();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
$reqNomorSuratKeluar= $set->getField("NOMOR");

$arrInfo="";
$index_data= 0;
//$statement= " AND A.JENIS_PELAYANAN_ID = ".$reqJenis." AND B.PEGAWAI_ID = ".$reqId;

if($reqJenis == 8)
$statement= " AND A.JENIS_PELAYANAN_ID = 7";
else
$statement= " AND A.JENIS_PELAYANAN_ID = ".$reqJenis;

$statement.= " AND A.KATEGORI = '".$reqKategori."'";

//$statementdetil= " AND B.PEGAWAI_ID = ".$reqId;
//$statementCheck= " AND B.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawaiCheck();
$set->selectByParamsMonitoringCheck(array(), -1,-1, $statement, $reqRowId, $statementdetil);
//echo $set->query;exit;
while($set->nextRow())
{
  // if($reqJenisKarpeg == 1 && ($set->getField("NOMOR") == 5 || $set->getField("NOMOR") == 6))
  // continue;
  // elseif($reqJenisKarpeg == 2 && $set->getField("NOMOR") == 5)
  // continue;
  // elseif($reqJenisKarpeg == 3 && $set->getField("NOMOR") == 6)
  // continue;
  
  //one
  $arrInfo[$index_data]["KATEGORI_FILE_ID"] = $set->getField("KATEGORI_FILE_ID");
  $arrInfo[$index_data]["JENIS_ID"] = $set->getField("JENIS_ID");
  $arrInfo[$index_data]["SURAT_MASUK_BKD_ID"] = $set->getField("SURAT_MASUK_BKD_ID");
  $arrInfo[$index_data]["SURAT_MASUK_UPT_ID"] = $set->getField("SURAT_MASUK_UPT_ID");
  $arrInfo[$index_data]["PEGAWAI_ID"] = $set->getField("PEGAWAI_ID");
  
  $arrInfo[$index_data]["NOMOR"] = $set->getField("NOMOR");
  $arrInfo[$index_data]["JENIS_PELAYANAN_ID"] = $set->getField("JENIS_PELAYANAN_ID");
  $arrInfo[$index_data]["TIPE"] = $set->getField("TIPE");
  $arrInfo[$index_data]["NAMA"] = $set->getField("NAMA");
  $arrInfo[$index_data]["INFO_CHECKED"] = $set->getField("INFO_CHECKED");
  $arrInfo[$index_data]["LINK_FILE"] = $set->getField("LINK_FILE");
  $arrInfo[$index_data]["STATUS_INFORMASI"] = $set->getField("STATUS_INFORMASI");
  $arrInfo[$index_data]["INFORMASI_TABLE"] = $set->getField("INFORMASI_TABLE");
  $arrInfo[$index_data]["INFORMASI_FIELD"] = $set->getField("INFORMASI_FIELD");
  
  $arrInfo[$index_data]["JENIS_PELAYANAN_NAMA"] = $set->getField("JENIS_PELAYANAN_NAMA");
  $arrInfo[$index_data]["PANGKAT_RIWAYAT_ID"] = $set->getField("PANGKAT_RIWAYAT_ID");
  $arrInfo[$index_data]["PENDIDIKAN_RIWAYAT_ID"] = $set->getField("PENDIDIKAN_RIWAYAT_ID");
  
  $arrInfo[$index_data]["SURAT_MASUK_PEGAWAI_CHECK_ID"] = $set->getField("SURAT_MASUK_PEGAWAI_CHECK_ID");
  
  //SURAT_MASUK_PEGAWAI_CHECK_ID;INFO_CHECKED;JENIS_ID;SURAT_MASUK_BKD_ID;SURAT_MASUK_UPT_ID
  
  $index_data++;
}
$jumlah_info= $index_data;
//print_r($arrInfo);exit;

if($jumlah_info > 0)
{
  $tempInfoJudul= $arrInfo[0]["JENIS_PELAYANAN_NAMA"];
}
//echo $jumlah_info;exit;

$arrLog="";
$index_data= 0;
$statement= " AND A.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
//$statementCheck= " AND B.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new SuratMasukPegawaiTurunStatus();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
  $arrLog[$index_data]["KETERANGAN"] = $set->getField("KETERANGAN");
  $arrLog[$index_data]["LAST_USER"] = $set->getField("LAST_USER");
  $arrLog[$index_data]["LAST_DATE"] = $set->getField("LAST_DATE");
  $index_data++;
}
$jumlah_log= $index_data;
//print_r($arrLog);exit;

$arrRevisiLog="";
$index_data= 0;
$statement= " AND A.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
//$statementCheck= " AND B.SURAT_MASUK_PEGAWAI_ID = ".$reqRowId;
$set= new CetakIjinBelajar();
$set->selectByParams(array(), -1,-1, $statement);
//echo $set->query;exit;
while($set->nextRow())
{
  $arrRevisiLog[$index_data]["CETAK_IJIN_BELAJAR_ID"] = $set->getField("CETAK_IJIN_BELAJAR_ID");
  $arrRevisiLog[$index_data]["LAST_USER"] = $set->getField("LAST_USER");
  $arrRevisiLog[$index_data]["LAST_DATE"] = $set->getField("LAST_DATE");
  $index_data++;
}
$jumlah_revisi_log= $index_data;

$arrSuamiIstri="";
$index_data= 0;
$sOrder= "ORDER BY A.TANGGAL_KAWIN";
$statement= " AND A.PEGAWAI_ID = ".$arrInfo[0]["PEGAWAI_ID"]." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
$set= new SuratMasukPegawaiCheck();
$set->selectByParamsPensiunSuamiIstri(array(),-1,-1, $statement, $reqKategori, $sOrder);
// echo $set->query;exit();
while($set->nextRow())
{
  $arrSuamiIstri[$index_data]["SUAMI_ISTRI_ID"] = $set->getField("SUAMI_ISTRI_ID");
  $arrSuamiIstri[$index_data]["NAMA"] = $set->getField("NAMA");
  $arrSuamiIstri[$index_data]["TANGGAL_KAWIN"] = dateToPageCheck($set->getField("TANGGAL_KAWIN"));
  $arrSuamiIstri[$index_data]["CERAI_TMT"] = dateToPageCheck($set->getField("CERAI_TMT"));
  $arrSuamiIstri[$index_data]["KEMATIAN_TMT"] = dateToPageCheck($set->getField("KEMATIAN_TMT"));
  $arrSuamiIstri[$index_data]["STATUS_S_I"] = $set->getField("STATUS_S_I");
  $arrSuamiIstri[$index_data]["STATUS_S_I_NAMA"] = $set->getField("STATUS_S_I_NAMA");
  $arrSuamiIstri[$index_data]["STATUS_S_I_RIWAYAT_FIELD"] = $set->getField("STATUS_S_I_RIWAYAT_FIELD");
  $index_data++;
}
$jumlah_suami_istri= $index_data;
// print_r($arrSuamiIstri);exit();
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="msapplication-tap-highlight" content="no">
  <meta name="description" content="Simpeg Jombang">
  <meta name="keywords" content="Simpeg Jombang">
  <title>Simpeg Jombang</title>
  <base href="<?=base_url()?>" />


  <link rel="stylesheet" type="text/css" href="css/gaya.css">

  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
  
  <!-- AUTO KOMPLIT -->
  <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
  <script src="lib/autokomplit/jquery-ui.js"></script>
  <!-- SIMPLE TAB -->
  <script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
  <link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

  <!-- CORE CSS-->    
  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- CSS style Horizontal Nav-->    
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <!-- Custome CSS-->    
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  
  <link href="lib/mbox/mbox.css" rel="stylesheet">
  <script src="lib/mbox/mbox.js"></script>
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">

  <?php /*?><style type="text/css">
    @media 
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {
      table.tabel-responsif thead th{ display:none; }
      <?
      $arrData= array("Persyaratan", "Checklist", "File");

      for($i=0; $i < count($arrData); $i++)
      {
        $index= $i+1;
        ?>
        table.tabel-responsif td:nth-of-type(<?=$index?>):before { content: "<?=$arrData[$i]?>"; }
        <? 
      }  ?>
    }

    .round {
      border-radius: 50%;
      padding: 5px;
    }
  </style><?php */?>
  
  <style>
    td, th {
    padding: 5px 5px;
    display: table-cell;
    text-align: left;
    vertical-align: middle;
    border-radius: 2px;
  }
  
  .carousel .carousel-item{
    width:100%;
  }
  
  
  /* CSS for responsive iframe */
/* ========================= */

/* outer wrapper: set max-width & max-height; max-height greater than padding-bottom % will be ineffective and height will = padding-bottom % of max-width */
#Iframe-Master-CC-and-Rs {
  *max-width: 512px;
  max-height: 100%; 
  overflow: hidden;
}

/* inner wrapper: make responsive */
.responsive-wrapper {
  position: relative;
  height: 0;    /* gets height from padding-bottom */
  
  /* put following styles (necessary for overflow and scrolling handling on mobile devices) inline in .responsive-wrapper around iframe because not stable in CSS:
    -webkit-overflow-scrolling: touch; overflow: auto; */
  
}
 
.responsive-wrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  
  margin: 0;
  padding: 0;
  border: none;
}

/* padding-bottom = h/w as % -- sets aspect ratio */
/* YouTube video aspect ratio */
.responsive-wrapper-wxh-572x612 {
  padding-bottom: 107%;
}

/* general styles */
/* ============== */
.set-border {
  border: 5px inset #4f4f4f;
}
.set-box-shadow { 
  -webkit-box-shadow: 4px 4px 14px #4f4f4f;
  -moz-box-shadow: 4px 4px 14px #4f4f4f;
  box-shadow: 4px 4px 14px #4f4f4f;
}
.set-padding {
  padding: 40px;
}
.set-margin {
  margin: 30px;
}
.center-block-horiz {
  margin-left: auto !important;
  margin-right: auto !important;
}

*html,body{height:100%;}
.carousel{
    height: 100%;
  height: 100vh !important;
    margin-bottom: 60px !important;
}

/*.carousel {
    *height: 150vh !important;
  height: 115% !important;
    width: 100%;
  *overflow:auto;
    overflow:hidden;
}*/
.carousel .carousel-inner {
    height:100% !important;
}

    .responsive-iframe {
    display: block;
      *position: relative;
      *padding-bottom: 56.25%;
    padding-bottom: 86.25%;
      *padding-top: 35px;
      height: 0;
    *height: 150vh !important;
      overflow: hidden;
    }
     
    .responsive-iframe iframe {
      position: absolute;
      top:0;
      left: 0;
      width: 100%;
      height: 100%;
    }
  
  th, td
  {
    padding: 3px 8px !important;
  }
  </style>
</head>

<body>
 <div id="basic-form" class="section">
  <div class="row">
    <div class="col s12 m10 offset-m1 ">
      <ul class="collection card">
        <li class="collection-item ubah-color-warna"><?=$tempInfoJudul?></li>
        <li class="collection-item">
          <div class="row">
          <form id="ff" method="post"  novalidate enctype="multipart/form-data">
          <table class="bordered striped md-text">
            <thead>
               <?
               if($reqNomorSuratKeluar == ""){}
               else
               {
               ?>
               <tr>
                    <th class="green-text material-font" style="width:20%">No Usul Ke KAnreg</th>
                    <th class="green-text material-font" style="width:1%">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqNomorSuratKeluar ?></th>
               </tr>
               <?
               }
               ?>
               <tr>
                    <th class="green-text material-font" style="width:20%">Jenis Pensiun</th>
                    <th class="green-text material-font" style="width:1%">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqKategoriNama?></th>
                </tr>
                <tr>
                  <th class="green-text material-font">Nama</th>
                    <th class="green-text material-font">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqPegawaiNama?></th>
                </tr>
                <tr>
                    <th class="green-text material-font">Gol Terakhir</th>
                    <th class="green-text material-font">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqPangkatRiwayatAkhir?></th>
                </tr>
                <tr>
                    <th class="green-text material-font">Jabatan</th>
                    <th class="green-text material-font">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqPegawaiJabatan?></th>
                </tr>
                <tr>
                  <th class="green-text material-font">Satuan Kerja</th>
                    <th class="green-text material-font">:</th>
                    <th colspan="4" class="green-text material-font"><?=$reqSatuanKerjaNama?></th>
                </tr>
            </thead>
          </table>
          
          <div id="resize">
            <div id="tabs" style="display:none; height:100%;">
            <ul id="tabs-swipe-demo" class="tabs" <?php /*?>style="display:none"<?php */?>>
                <li class="tab col s3"><a class="active" href="#swipe-1">Test 1</a></li>
                <li class="tab col s3"><a href="#swipe-2">Test 2</a></li>
            </ul>
            </div>
            
            <div class="row">
            <div id="swipe-1" class="col s12" style="height:auto !important">
              <table class="bordered striped md-text table_list tabel-responsif" id="link-table" style="margin-bottom:10px;">
                <thead> 
                  <tr class="ubah-color-warna">
                    <th colspan="4" class="white-text material-font">Persyaratan</th>
                    <th class="white-text material-font" style="width:5%; text-align:center">File</th>
                  </tr>
                </thead>
                <tbody>
                <?
        $tempAnakRowNum= 1;
        $tempJumlahChecked= 0;
        $tempTahunSurat= $reqTahunSurat;
        $index_tahun=1;
        $index_suami_istri= 0;
        for($index=0; $index < $jumlah_info; $index++)
        {
          $tempKategoriFileId= $arrInfo[$index]["KATEGORI_FILE_ID"];
          $tempSuratMasukPegawaiCheckId= $arrInfo[$index]["SURAT_MASUK_PEGAWAI_CHECK_ID"];
          $tempJenisId= $arrInfo[$index]["JENIS_ID"];
          $tempSuratMasukBkdId= $arrInfo[$index]["SURAT_MASUK_BKD_ID"];
          $tempSuratMasukUptId= $arrInfo[$index]["SURAT_MASUK_UPT_ID"];
          $tempPegawaiId= $arrInfo[$index]["PEGAWAI_ID"];
          $tempNomor= $arrInfo[$index]["NOMOR"];
          $tempJenisPelayananId= $arrInfo[$index]["JENIS_PELAYANAN_ID"];
          $tempTipe= $arrInfo[$index]["TIPE"];
          $tempNama= $arrInfo[$index]["NAMA"];
          $tempInfoChecked= $arrInfo[$index]["INFO_CHECKED"];
          $tempLinkFile= $arrInfo[$index]["LINK_FILE"];
          $tempStatusInformasi= $arrInfo[$index]["STATUS_INFORMASI"];
          $tempInformasiTable= $arrInfo[$index]["INFORMASI_TABLE"];
          $tempInformasiField= $arrInfo[$index]["INFORMASI_FIELD"];
          
          $tempPangkatRiwayatId= $arrInfo[$index]["PANGKAT_RIWAYAT_ID"];
          $tempPendidikanRiwayatId= $arrInfo[$index]["PENDIDIKAN_RIWAYAT_ID"];
          
          $tempInfoValue= "";
          
          // ambil data
          if($tempInformasiTable == "PANGKAT_RIWAYAT")
          {
            if($tempNama == "Surat Pemberitahuan KGB")
            {
              $statement= " AND GR.JENIS_KENAIKAN = 3 AND GR.PEGAWAI_ID = ".$tempPegawaiId;
              $set_detil= new SuratMasukPegawaiCheck();
              $set_detil->selectByParamsKgb(array(),-1,-1, $statement);
              // echo $set_detil->query;exit();
              $set_detil->firstRow();
              $tempRiwayatId= $set_detil->getField("RIWAYAT_ID");
              $tempInformasiTableAwal= $tempInformasiTable;
              $tempInformasiTable= $set_detil->getField("RIWAYAT_TABLE");
              $tempInformasiField= $set_detil->getField("RIWAYAT_FIELD");
              $tempInfoRiwayatField= "";
            }
            else
            {

                $tempRiwayatId= $tempPangkatRiwayatId;

                if($tempKategoriFileId == "1" || $tempKategoriFileId == "2"){}
                else
                {
                  $statement= " AND A.PANGKAT_RIWAYAT_ID = ".$tempPangkatRiwayatId;

                  // KATEGORI_FILE_ID
                  $set_detil= new SuratMasukPegawaiCheck();
                  $set_detil->selectByParamsPangkat(array(),-1,-1, $statement);
                  $set_detil->firstRow();
                  // echo $set_detil->query;exit;
                  $tempPangkatKode= $set_detil->getField("PANGKAT_KODE");
                  $tempPangkatTahun= $set_detil->getField("MASA_KERJA_TAHUN");
                  $tempPangkatBulan= $set_detil->getField("MASA_KERJA_BULAN");
                  unset($set_detil);
                  
                  if($tempInformasiField == "PANGKAT_INFO")
                  {
                    $tempInfoValue= $tempPangkatKode;
                  }
                  elseif($tempInformasiField == "MASA_KERJA")
                  {
                    $tempInfoValue= $tempPangkatTahun." tahun ".$tempPangkatBulan." bln";
                  }
                }
              
            }
            
          }
          elseif
          (
            $tempInformasiTable == "KGB"
            ||
            (
              ($tempInformasiTable == "GAJI_RIWAYAT" && $tempJenisPelayananId == 7)
            )
          )
          {
            $statement= " AND GR.JENIS_KENAIKAN = 3 AND GR.PEGAWAI_ID = ".$tempPegawaiId;
            $set_detil= new SuratMasukPegawaiCheck();
            $set_detil->selectByParamsKgb(array(),-1,-1, $statement);
            // echo $set_detil->query;exit();
            $set_detil->firstRow();
            $tempRiwayatId= $set_detil->getField("RIWAYAT_ID");
            $tempInformasiTableAwal= $tempInformasiTable;
            $tempInformasiTable= $set_detil->getField("RIWAYAT_TABLE");
            $tempInformasiField= $set_detil->getField("RIWAYAT_FIELD");
            $tempInfoRiwayatField= "";
          }
          elseif($tempInformasiTable == "PENDIDIKAN_RIWAYAT")
          {
            $tempRiwayatId= $tempPendidikanRiwayatId;
            $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$tempPendidikanRiwayatId;
            $set_detil= new SuratMasukPegawaiCheck();
            $set_detil->selectByParamsPendidikan(array(),-1,-1, $statement);
            $set_detil->firstRow();
            //$set_detil->query;exit;
            $tempPendidikanNama= $set_detil->getField("PENDIDIKAN_NAMA");
            $tempPendidikanJurusan= $set_detil->getField("JURUSAN");
            unset($set_detil);
            
            if($tempInformasiField == "PENDIDIKAN_NAMA")
            {
              $tempInfoValue= $tempPendidikanNama." / ".$tempPendidikanJurusan;
            }
            
          }
          elseif($tempInformasiTable == "PENILAIAN_SKP")
          {
            $tempTahunSurat= $reqTahunSurat - $index_tahun;
            $index_tahun--;
  
            $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TAHUN = ".$tempTahunSurat;
            $set_detil= new SuratMasukPegawaiCheck();
            $set_detil->selectByParamsPenilaianSkp(array(),-1,-1, $statement);
            $set_detil->firstRow();
            // echo $set_detil->query;exit;
            $tempSkpTahun= $tempTahunSurat;
            $tempPenilaianSkpId= $set_detil->getField("PENILAIAN_SKP_ID");
            unset($set_detil);
            
            $tempRiwayatId= $tempPenilaianSkpId;
            $tempInfoValue= $tempSkpTahun;
            $tempInfoRiwayatField= "ppk";
          }
          elseif($tempInformasiTable == "SUAMI_ISTRI")
          {
            if(isStrContain($tempTipe, "aktanikah"))
            {
              if($index_suami_istri < $jumlah_suami_istri){}
              else
                continue;

              $tempSuamiIstriId= $arrSuamiIstri[$index_suami_istri]["SUAMI_ISTRI_ID"];
              $tempSuamiIstriNama= $arrSuamiIstri[$index_suami_istri]["NAMA"];
              $tempSuamiIstriTanggalKawin= $arrSuamiIstri[$index_suami_istri]["TANGGAL_KAWIN"];
              $tempSuamiIstriCeraiTmt= $arrSuamiIstri[$index_suami_istri]["CERAI_TMT"];
              $tempSuamiIstriKematianTmt= $arrSuamiIstri[$index_suami_istri]["KEMATIAN_TMT"];
              $tempSuamiIstriStatusSi= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I"];
              $tempSuamiIstriStatusSiNama= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I_NAMA"];
              $tempSuamiIstriStatusSiRiwayatField= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I_RIWAYAT_FIELD"];

              $tempNama= "Nama Suami/Istri";
              $tempInfoValue= $tempSuamiIstriNama;

              $index_suami_istri++;

              $tempInfoRiwayatField= "aktanikah";

              $tempRiwayatId= $tempSuamiIstriId;
            }
            elseif(isStrContain($tempTipe, "kariskarsu"))
            {
              // untuk ambil data kartu keluarga
              $tempInformasiTable= "PEGAWAI";
              $tempRiwayatId= 6;
              $tempInfoRiwayatField= "";
            }
            else
            {
              if($tempTipe == "skkonversinipbaru")
              {
                $sOrder= "ORDER BY A.TANGGAL_KAWIN DESC";
                $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
                $set_detil= new Pensiun();
                $set_detil->selectByParamsSuamiIstri(array(), -1,-1, $statement, $sOrder);
                $set_detil->firstRow();
                //$set_detil->query;exit;
                $tempSuamiIstriId= $set_detil->getField("SUAMI_ISTRI_ID");
                $tempSuamiIstriStatusPns= $set_detil->getField("STATUS_PNS");
                if($tempSuamiIstriStatusPns == "")
                {
                  continue;
                }
                $tempInfoRiwayatField= $tempTipe;
                $tempRiwayatId= $tempSuamiIstriId;
              }
              else
              {
                if($jumlah_suami_istri > 0)
                {
                  $index_terakhir_suami_istri= $jumlah_suami_istri - 1;
                  $tempSuamiIstriId= $arrSuamiIstri[$index_terakhir_suami_istri]["SUAMI_ISTRI_ID"];
                  $tempInfoRiwayatField= $tempTipe;
                  $tempRiwayatId= $tempSuamiIstriId;
                  // echo $tempSuamiIstriId."-asd".$index;exit();
                }
              }

            }
          }
          elseif($tempInformasiTable == "ANAK")
          {
            $statement= " AND A.NOMOR = ".$tempAnakRowNum;
            $set_detil= new Pensiun();
            $set_detil->selectByParamsAnak($tempPegawaiId, $reqKategori, $statement);
            $set_detil->firstRow();
            //$set_detil->query;exit;
            $tempAnakId= $set_detil->getField("ANAK_ID");
            $tempAnakUsia= $set_detil->getField("ANAK_USIA");
            $tempAnakTanggalLahir= dateToPageCheck($set_detil->getField("ANAK_TANGGAL_LAHIR"));
            $tempInfoRiwayatField= "aktakelahiran";
            $tempNama= "Nama Anak";
            $tempInfoValue= $set_detil->getField("ANAK_NAMA");
            unset($set_detil);

            if($tempAnakId == "")
            continue;

            $tempRiwayatId= $tempAnakId;
            $tempAnakRowNum++;
          }
          elseif($tempInformasiTable == "PEGAWAI")
          {
            if(isStrContain($tempTipe, "kariskarsu"))
              $tempRiwayatId= 6;
            else
              $tempRiwayatId= $tempKategoriFileId;
            
            $tempInfoRiwayatField= "";
          }
          
          //get path file
          $tempPath="";
          if($tempLinkFile == ""){}
          else
          {
            //one
            //$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."'";
            if($tempKategoriFileId == "1" && $tempInformasiTable !== "PEGAWAI")
            $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = 'skcpns'";
            elseif($tempKategoriFileId == "2" && $tempInformasiTable !== "PEGAWAI")
            {
              if($tempTipe == "sttplprajabatan")
              {
                  $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = '".$tempTipe."'";
              }
              else
              {
                  $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = 'skpns'";
              }
            }
            elseif($tempInfoRiwayatField !== "")
            {
              $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID = ".$tempRiwayatId." AND A.RIWAYAT_FIELD = '".$tempInfoRiwayatField."'";
            }
            else
            {
              if($tempRiwayatId == 9 || $tempRiwayatId == 19)
              $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID IN (9,19)";
              else
              $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID = ".$tempRiwayatId;
            }
            
            $set_detil= new SuratMasukPegawaiCheck();
            $set_detil->selectByParamsFile(array(),-1,-1, $statement);
            $set_detil->firstRow();
            // if($tempInformasiTable == "PEGAWAI" && $tempRiwayatId == "2")
            // if($tempInformasiTable == "PANGKAT_RIWAYAT" && $tempKategoriFileId == "4")
            if($tempInformasiTableAwal == "KGB")
            // if($tempTipe == "skjandaduda")
            {
            // echo $set_detil->query;exit;
            }
            $tempPath= $set_detil->getField("PATH");

            if($tempInformasiTable == "PEGAWAI" && $tempRiwayatId == "2")
            {
            // echo $tempPath."ASD";exit;
            }

            if(file_exists($tempPath)){}
            else
            $tempPath= "";

          }
          
          $tempArrNomor= explode(".",$tempNomor);
                ?>
                    <tr>
                <?
          if($tempStatusInformasi == "1")
          {
            if($tempNomor == "5")
            $tempInfoValue= "Nomor ".$reqNoSuratKehilangan." Tanggal ".$reqTanggalSuratKehilangan;
            elseif($tempNomor == "6")
            $tempInfoValue= $reqKeterangan;
        ?>
                  <?
          if($tempLinkFile == "" && $tempNomor != "5" && $tempNomor != "6" )
          {
                    ?>
                  <td colspan="2" style="text-align:right; width:18%"><?=$tempNama?></td>
                    <?
          }
          else
          {
                    ?>
                    <td colspan="2" style="text-align:left; width:18%"><?=$tempNama?></td>
                    <?
          }
                    ?>
                    <td style="width:2%">:</td>
                    <td><?=$tempInfoValue?></td>
                <?
          }
          else
          {
                ?>
                      <td colspan="4"><?=$tempNama?></td>
                <?
          }
          
          $tempChecked= "";
          if($tempInfoChecked == "1")
          {
            $tempChecked= "checked";
            $tempJumlahChecked++;
          }
                ?>
                      <td style="text-align:center">
                        <?
            if($tempLinkFile == ""){}
            else
            {
                        ?>
                        <a href="javascript:void(0)" title="Ubah" onClick="setpdf('<?=$tempPath?>')">
                          <img src="images/iconviewpdf.png" style="width:25px" height="25px" />
                        </a>
                        <?
            }
                        ?>
                      </td>
                   </tr>

                  <?
                  if($tempInformasiTable == "SUAMI_ISTRI" && isStrContain($tempTipe, "aktanikah"))
                  {
                  ?>
                  <tr>
                    <td colspan="2" style="text-align:right">Tanggal Nikah</td>
                    <td>:</td>
                    <td colspan="3"><?=$tempSuamiIstriTanggalKawin?></td>
                  </tr>
                  <?
                      $tempValueDetil= "";

                      if($tempSuamiIstriStatusSi == "1"){}
                      else
                      {
                      ?>
                      <?
                        if($tempSuamiIstriStatusSi == "2")
                        {
                          $tempValueDetil= $tempSuamiIstriCeraiTmt;
                          $tempValueInfoDetil= "Tanggal Cerai";
                        }
                        elseif($tempSuamiIstriStatusSi == "3")
                        {
                          $tempValueDetil= $tempSuamiIstriKematianTmt;
                          $tempValueInfoDetil= "Tanggal Wafat";
                        }
                      ?>
                      <tr>
                        <td colspan="2" style="text-align:right"><?=$tempValueInfoDetil?></td>
                        <td>:</td>
                        <td colspan="3"><?=$tempValueDetil?></td>
                      </tr>
                      <?
                      }
                  }
                  elseif($tempInformasiTable == "ANAK")
                  {
                  ?>
                  <tr>
                    <td colspan="2" style="text-align:right">Tanggal Lahir</td>
                    <td>:</td>
                    <td colspan="3"><?=$tempAnakTanggalLahir?></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="text-align:right">Usia</td>
                    <td>:</td>
                    <td colspan="3"><?=$tempAnakUsia?></td>
                  </tr>
                  <?
                  }
                  ?>

                <?
                }
                ?>
               </tbody>
             </table>
             
            <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
            <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            </button>
                        
             
            </div>
            </div>
            
            <div class="row">
            <div id="swipe-2" class="col s12" style="height:auto !important">
                <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="kembalitab()">Persyaratan
                   <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>
                
                <div class="responsive-iframe"><iframe src="<?=$reqUrlFile?>" id="mainframe" <?php /*?>onload="iframeLoaded()"<?php */?>></iframe></div>

      </div>
            </div>
          
          </div>
            <input type="hidden" name="reqKategori" value="<?=$reqKategori?>" />
            <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
            <input type="hidden" name="reqId" value="<?=$reqId?>" />
          </form>
           
           <?php /*?><button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button">Proses selanjutnya
            <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            </button>
            
            <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button">Cetak
            <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            </button>
            
            <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button">Turun Status
            <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            </button>
            
            <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button">Minta review button di samping fungsi nya apa saja
            <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
            </button>
            
           <?php */?>
        </div>    
       </li>
     </ul>
   </div>
 </div>
</div>

<link href="lib/materializetemplate/css/materializeslide.css" rel="stylesheet" />
<?php /*?><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<?php */?>
<script src="lib/materializetemplate/js/materializeslide.min.js"></script>

<?php /*?><script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script><?php */?>

<script>
function kembalitab()
{
  iframeLoaded();
  $('ul.tabs').tabs('select_tab', 'swipe-1');
}

function setpdf(urlfile)
{
  //$("#mainframe").attr('src',"uploads/3/c95bd2d2a2ee5471550aa470e446ce39.pdf");
  $("#mainframe").attr('src',urlfile);
  //$('ul.tabs').tabs('swipeable', true);
  $("#tabs").tabs({
    //swipeable : true,
    swipeable: true
  });
  
  $('ul.tabs').tabs('select_tab', 'swipe-2');
  iframeLoaded();
}

$(window).bind("load", function() {
  //alert('a');
  //parent.iframeLoadeds();
  //alert('a');
   // code here
});

$(document).ready( function () {
  
  $("#reqsimpan").click(function() { 
    if($("#ff").form('validate') == false){
      return false;
    }
    
    mbox.custom({
       message: "Pastikan sudah melengkapi data Turun Status an. <?=$reqPegawaiNama?>. Yakin untuk dikirim ulang ?",
       options: {close_speed: 100},
       buttons: [
         {
           label: 'Ya',
           color: 'green darken-2',
           callback: function() {
             $("#reqSubmit").click();
             mbox.close();
           }
         },
         {
           label: 'Tidak',
           color: 'grey darken-2',
           callback: function() {
             //console.log('do action for no answer');
             mbox.close();
           }
         }
       ]
    });
    
  });
  
  $("#kembali").click(function() { 
    <?
    if($reqJenis == 8)
    {
    ?>
    document.location.href = "app/loadUrl/persuratan/usulan_surat_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
    <?
    }
    else
    {
    ?>
    document.location.href = "app/loadUrl/persuratan/surat_masuk_bkd_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
    <?
    }
    ?>
  });
  
  /*$("#tabs").tabs({
    //swipeable : true,
    swipeable: true,
    init: function(){
      alert('s');
            //$('body:last-child').append(html);
            //return true;
      }
    //,heightStyle: "fill"
    //,fxAutoHeight: true
    //, responsiveThreshold : Infinity
  });*/
  //parent.iframeLoaded();
  /*var height = 0;
  $("main").each(function( index ) {
    if(height<$(this).height()){
    height = $(this).height()
    }
  });
  $("main").height(height);*/
  
  $('#tabs').on('init', function(event, tabs){
    alert('a');
    //console.log('slider was initialized');
  });
  
  /*$('#resize').resizable({
   handles: 's',
   alsoResize: '.ui-tabs-panel'
    });*/

  //$("ul.tabs a").css('height', $("ul.tabs").height());
});

function iframeLoaded() {
  var iFrameID= document.getElementById('mainFrame');
  if(iFrameID) {
      // here you can make the height, I delete it first, then I make it again
      iFrameID.height = "";
      iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
  }
}
</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>