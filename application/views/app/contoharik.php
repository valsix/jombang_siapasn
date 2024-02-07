<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('DiklatKursus');
$tempLoginLevel= $this->LOGIN_LEVEL;

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010604";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$statementLevel= "";
if($tempLoginLevel == "99"){}
else
$statementLevel= " AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";

// untuk tambahan kode
$this->load->model('base-api/InfoData');
$this->load->model('base-api/DataCombo');

$set= new InfoData();
$infonipbaru= $set->getnip($reqId);
// echo $infonipbaru;exit;

$arrkunci= [];
$arrdatariwayat= [];
$set= new DiklatKursus();
$set->selectByParams(array(), -1, -1, $statementLevel." AND A.PEGAWAI_ID = ".$reqId);
// echo $set->query;exit;
while($set->nextRow())
{
  $infokunci= dateToPageCheck($set->getField("TANGGAL_SELESAI"));
  $arrdata= [];
  // kunci untuk kondisi
  $arrdata["key"]= $infokunci;
  $arrdata["BKN_ID"]= $set->getField("BKN_ID");

  // data sesuai riwayat
  $arrdata["TIPE_KURSUS_ID"]= $set->getField("TIPE_KURSUS_ID");
  $arrdata["NAMA"]= $set->getField("NAMA");
  $arrdata["JENIS_KURSUS_NAMA"]= $set->getField("JENIS_KURSUS_NAMA");
  $arrdata["PENYELENGGARA"]= $set->getField("PENYELENGGARA");
  $arrdata["TAHUN"]= $set->getField("TAHUN");
  $arrdata["NO_SERTIFIKAT"]= $set->getField("NO_SERTIFIKAT");
  $arrdata["TANGGAL_SERTIFIKAT"]= dateToPageCheck($set->getField("TANGGAL_SERTIFIKAT"));
  $arrdata["JUMLAH_JAM"]= $set->getField("JUMLAH_JAM");
  array_push($arrdatariwayat, $arrdata);

  // kalau tidak ada maka masukkan
  if(!in_array($infokunci, $arrkunci))
  {
    array_push($arrkunci, $infokunci);
  }
}
// print_r($arrdatariwayat);exit;

$arrdatabkn= [];
$arrparam= ["nip"=>$infonipbaru, "vurl"=>"Data_rw_kursus_json"];
$set= new DataCombo();
$set->selectdata($arrparam, "");
while($set->nextRow())
{
  $infokunci= $set->getField("tanggalKursus");
  $arrdata= [];
  // kunci untuk kondisi
  $arrdata["key"]= $infokunci;

  // data sesuai api bkn
  $arrdata["id"]= $set->getField("id");
  $arrdata["idPns"]= $set->getField("idPns");
  $arrdata["nipBaru"]= $set->getField("nipBaru");
  $arrdata["nipLama"]= $set->getField("nipLama");
  $arrdata["jenisKursusNama"]= $set->getField("jenisKursusNama");
  $arrdata["jenisKursusSertifikat"]= $set->getField("jenisKursusSertifikat");
  $arrdata["institusiPenyelenggara"]= $set->getField("institusiPenyelenggara");
  $arrdata["jenisKursusId"]= $set->getField("jenisKursusId");
  $arrdata["jumlahJam"]= $set->getField("jumlahJam");
  $arrdata["namaKursus"]= $set->getField("namaKursus");
  $arrdata["noSertipikat"]= $set->getField("noSertipikat");
  $arrdata["tahunKursus"]= $set->getField("tahunKursus");
  $arrdata["tanggalKursus"]= $set->getField("tanggalKursus");
  $arrdata["jenisDiklatId"]= $set->getField("jenisDiklatId");
  $arrdata["id"]= $set->getField("id");
  array_push($arrdatabkn, $arrdata);

  // kalau tidak ada maka masukkan
  if(!in_array($infokunci, $arrkunci))
  {
    array_push($arrkunci, $infokunci);
  }
}
// print_r($arrdatabkn);exit;

// untuk urut tanggal desc
usort($arrkunci, "sortdatefunctiondesc");
// untuk urut tanggal asc
// usort($arrkunci, "sortdatefunctionasc");
// print_r($arrkunci);exit;
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
  
  <link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
  <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
  <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>

  <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/layouts/style-horizontal.css" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

  <link rel="stylesheet" type="text/css" href="css/gaya-baru.css">
</head>
<body>
  <div class="area-konten area-monitoring-riwayat">
    <div class="judul-konten">
      Monitoring Riwayat Diklat Kursus
      <a style="cursor:pointer; float: right; color: white" onClick="parent.setload('pegawai_add_diklat_kursus_monitoring?reqId=<?=$reqId?>')"> <i class="mdi-navigation-arrow-back"> <span class=" material-font">Kembali</span></i></a>
    </div>
    <div class="inner">
      <div class="judul-instansi">
        <div class="tanggal"></div>
        <div class="data">
          <div class="title"></div>
          <div class="data-siapasn">Data SIAPASN</div>
          <div class="data-bkn">Data BKN</div>
          <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
      </div>

      <?
      foreach ($arrkunci as $key => $value)
      {
        $vkeycari= $value;
        // echo $value;exit;

        $infocarikey= $vkeycari;

        // untuk ambil data riwayat
        $vdatariwayat= in_array_column($infocarikey, "key", $arrdatariwayat);
        $infobknid= "";
        $infonamariwayat= $infojenisriwayat= $infonosertifikatriwayat= $infotanggalsertifikatriwayat= $infojumlahjamriwayat= "";
        if(!empty($vdatariwayat))
        {
          $indexdata= $vdatariwayat[0];
          $infobknid= $arrdatariwayat[$indexdata]["BKN_ID"];
          $infonamariwayat= $arrdatariwayat[$indexdata]["NAMA"];
          $infojenisriwayat= $arrdatariwayat[$indexdata]["JENIS_KURSUS_NAMA"];
          $infonosertifikatriwayat= $arrdatariwayat[$indexdata]["NO_SERTIFIKAT"];
          $infotanggalsertifikatriwayat= $arrdatariwayat[$indexdata]["TANGGAL_SERTIFIKAT"];
          $infojumlahjamriwayat= $arrdatariwayat[$indexdata]["JUMLAH_JAM"];
        }

        // untuk ambil data bkn
        $infodatabknid= "";
        $vdatabkn= in_array_column($infocarikey, "key", $arrdatabkn);
        $infonamabkn= $infojenisbkn= $infonosertifikatbkn= $infotanggalsertifikatbkn= $infojumlahjambkn= "";
        if(!empty($vdatabkn))
        {
          $indexdata= $vdatabkn[0];
          $infodatabknid= $arrdatabkn[$indexdata]["id"];
          $infonamabkn= $arrdatabkn[$indexdata]["namaKursus"];
          $infojenisbkn= $arrdatabkn[$indexdata]["jenisKursusSertifikat"];
          $infonosertifikatbkn= $arrdatabkn[$indexdata]["noSertipikat"];
          $infotanggalsertifikatbkn= $arrdatabkn[$indexdata]["tanggalKursus"];
          $infojumlahjambkn= $arrdatabkn[$indexdata]["jumlahJam"];
        }
      ?>
      <div class="item">
        <div class="tanggal"><span><img src="images/icon-tanggal.png"></span> <?=getFormattedDate(dateToPageCheck($value))?></div>
        <div class="data">

          <div class="baris atas">
            <div class="title"></div>
            <div class="data-siapasn"></div>
            <div class="data-bkn"></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Nama Diklat/Kursus</div>
            <div class="data-siapasn">
              <?
              if(empty($infonamariwayat))
              {
              ?>
              <span class="tidak-ada-data"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Belum ada data</span>
              <?
              }
              ?>
              <?=$infonamariwayat?>
            </div>
            <div class="data-bkn">
              <?
              if(empty($infonamabkn))
              {
              ?>
              <span class="tidak-ada-data"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Belum ada data</span>
              <?
              }
              ?>
              <?=$infonamabkn?>
            </div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Tipe Diklat/Kursus</div>
            <div class="data-siapasn"><?=$infojenisriwayat?></div>
            <div class="data-bkn"><?=$infojenisbkn?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">No Sertifikat</div>
            <div class="data-siapasn"><?=$infonosertifikatriwayat?></div>
            <div class="data-bkn"><?=$infonosertifikatbkn?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Tgl Sertifikat</div>
            <div class="data-siapasn"><?=$infotanggalsertifikatriwayat?></div>
            <div class="data-bkn"><?=$infotanggalsertifikatbkn?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris">
            <div class="title">Jumlah Jam</div>
            <div class="data-siapasn"><?=$infojumlahjamriwayat?></div>
            <div class="data-bkn"><?=$infojumlahjambkn?></div>
            <div class="clearfix"></div>
          </div>

          <div class="baris bawah">
            <div class="title"></div>
            <div class="data-siapasn"></div>
            <div class="data-bkn"></div>
            <div class="clearfix"></div>
          </div>

        </div>

        <div class="aksi">
          <?
          if(empty($infobknid))
          {
          ?>
          <div class="info-sinkron belum">
            <span class="ikon"><img src="images/icon-belum-sinkron.png"></span>
            <span class="teks">Belum Sinkron</span>
          </div>
          <?
          }
          else
          {
          ?>
          <div class="info-sinkron sudah">
            <span class="ikon"><img src="images/icon-sudah-sinkron.png"></span>
            <span class="teks">Sudah Sinkron</span>
          </div>
          <?
          }
          ?>
          <div class="aksi-tombol">
          <?
          $infoidsinkronsiapasnbkndisabled= $infoidsinkronsiapasnbkn= "";
          if(empty($infobknid))
          {
            $infoidsinkronsiapasnbkndisabled= "disabled";
            $infoidsinkronsiapasnbkn= "infoidsinkronsiapasnbkn";
          }
          ?>
          <a class="<?=$infoidsinkronsiapasnbkndisabled?>" href="javascript:void(0)" title="update data SIAPASN ke BKN"><img src="images/icon-right.png"></a>
          <?
          $infoidsinkronbknsiapasn= "";
          if(empty($infodatabknid))
          {
            $infoidsinkronbknsiapasn= "infoidsinkronbknsiapasn";
          }
          ?>
          <a href="javascript:void(0)" id="<?=$infoidsinkronbknsiapasn?>" title="update data BKN ke SIAPASN"><img src="images/icon-left.png"></a>
          <a href="javascript:void(0)" title="hapus sinkron data" ><img src="images/icon-del.png"></a>
          <a href="javascript:void(0)" title="ubah data"><img src="images/icon-pen.png"></a>
          </div>
        </div>
        <div class="clearfix"></div>

      </div>
      <?
      }
      ?>

    </div>
  </div>

  <script type="text/javascript" src="lib/materializetemplate/js/materialize.min.js"></script>
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
  <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>