<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal-new.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Rumpun');
$this->load->model('validasi/DiklatKursus');
$this->load->model('KualitasFile');
$this->load->library('globalvalidasifilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$cekquery= $this->input->get("c");

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010604";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$arrstatusvalidasi= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Valid")
  , array("id"=>"2", "text"=>"Ditolak")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusvalidasi, $arrdata);
}

$set= new Rumpun();
$set->selectbidangterkait(array());
// echo $set->query;exit;
$arrbidangterkait=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("BIDANG_TERKAIT_ID");
  $arrdata["nama"]= $set->getField("NAMA");
  $arrdata["rumpunid"]= $set->getField("RUMPUN_ID");
  $arrdata["rumpun"]= $set->getField("RUMPUN_NAMA");
  array_push($arrbidangterkait, $arrdata);
}
// print_r($arrbidangterkait);exit;

$arrrumpun= [];
$rumpun= new Rumpun();
$rumpun->selectByParams(array());
while($rumpun->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $rumpun->getField("RUMPUN_ID");
  $arrdata["text"]= $rumpun->getField("KETERANGAN");
  array_push($arrrumpun, $arrdata);
}

$arrtipekursus= [];
$statement= " AND A.TIPE_KURSUS_ID NOT IN (1)";
$tipekursus= new DiklatKursus();
$tipekursus->selecttipekursus(array(), -1,-1, $statement);
while($tipekursus->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $tipekursus->getField("TIPE_KURSUS_ID");
  $arrdata["text"]= $tipekursus->getField("NAMA");
  array_push($arrtipekursus, $arrdata);
}

$infodetilmode= "tipe_kursus";
$reqRumpunId= -1;
$reqPermenId= 1;

$statement= " AND INFOMODE = '".$infodetilmode."' AND A.PERMEN_ID = ".$reqPermenId." AND A.RUMPUN_ID = ".$reqRumpunId;
$set= new Rumpun();
$set->selectrumpunnilai(array(), -1, -1, $statement);
// echo $set->query;exit;
$arrinfonilairumpun=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("INFOID");
  $arrdata["NILAI"]= $set->getField("NILAI");
  array_push($arrinfonilairumpun, $arrdata);
}

$statement= "";
$set= new DiklatKursus();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');
$reqTanggalValidasi= $set->getField('TANGGAL_VALIDASI');

$reqValRowId= $set->getField('DIKLAT_KURSUS_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqTipeKursus= $set->getField('TIPE_KURSUS_ID'); $vTipeKursus= checkwarna($reqPerubahanData, 'TIPE_KURSUS_ID', $arrtipekursus, array("id", "text"), $reqTempValidasiHapusId);
$reqJenisKursus= $set->getField('JENIS_KURSUS_NAMA');
$reqJenisKursusId= $set->getField('REF_JENIS_KURSUS_ID');
$reqJenisKursusData= $set->getField('REF_JENIS_KURSUS_DATA');
$reqTahun= $set->getField('TAHUN'); $vTahun= checkwarna($reqPerubahanData, 'TAHUN', [$reqTahun]);
$reqRefInstansiId = $set->getField('REF_INSTANSI_ID');
$reqRefInstansi= $set->getField('REF_INSTANSI_INFO'); $vRefInstansi= checkwarna($reqPerubahanData, 'REF_INSTANSI_NAMA');  
$reqPenyelenggara = $set->getField('PENYELENGGARA'); $vPenyelenggara= checkwarna($reqPerubahanData, 'PENYELENGGARA');
$reqNamaKursus= $set->getField('NAMA'); $vNamaKursus= checkwarna($reqPerubahanData, 'NAMA');
$reqBidangTerkaitId = $set->getField('BIDANG_TERKAIT_ID'); $vBidangTerkaitId= checkwarna($reqPerubahanData, 'BIDANG_TERKAIT_ID');
$reqTglSertifikat= dateToPageCheck($set->getField('TANGGAL_SERTIFIKAT')); $vTglSertifikat= checkwarna($reqPerubahanData, 'TANGGAL_SERTIFIKAT', ["date"]);
$reqNoSertifikat= $set->getField('NO_SERTIFIKAT'); $vNoSertifikat= checkwarna($reqPerubahanData, 'NO_SERTIFIKAT');
$reqAngkatan= $set->getField('ANGKATAN'); $vAngkatan= checkwarna($reqPerubahanData, 'ANGKATAN');
$reqRumpunJabatan= $set->getField('RUMPUN_ID'); $vRumpunJabatan= checkwarna($reqPerubahanData, 'RUMPUN_ID', $arrrumpun, array("id", "text"), $reqTempValidasiHapusId);
$reqRumpunJabatanNama= $set->getField('RUMPUN_NAMA');
$reqRumpunNamaKompetensi= $set->getField('RUMPUN_NAMA');
$reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI')); $vTglMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', ["date"]);
$reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI')); $vTglSelesai= checkwarna($reqPerubahanData, 'TANGGAL_SELESAI', ["date"]);
$reqJumlahJam= $set->getField('JUMLAH_JAM'); $vJumlahJam= checkwarna($reqPerubahanData, 'JUMLAH_JAM');
$reqNilaiKompentensi= $set->getField('NILAI_REKAM_JEJAK'); $vNilaiKompentensi= checkwarna($reqPerubahanData, 'NILAI_REKAM_JEJAK');
$reqTempat= $set->getField('TEMPAT'); $vTempat= checkwarna($reqPerubahanData, 'TEMPAT');
$reqStatusLulus= $set->getField('STATUS_LULUS'); $vStatusLulus= checkwarna($reqPerubahanData, 'STATUS_LULUS', $arrstatuslulus, array("id", "text"), $reqTempValidasiHapusId);

// untuk kondisi file
$vfpeg= new globalvalidasifilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "DIKLAT_KURSUS";
$reqDokumenKategoriFileId= "25"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

$fileRowId= $reqValRowId;
if(empty($reqValRowId))
  $fileRowId= "baru";

$infopushparam= "";
$arrdata= $infodetilparam= [];
if(!empty($reqTempValidasiId))
{
    $arrdata["reqTempValidasiId"]= $reqTempValidasiId;
    $infopushparam= "1";
}

if($infopushparam == "1")
{
    array_push($infodetilparam, $arrdata);
    // print_r($infodetilparam);exit;
}

$vfpeg= new globalvalidasifilepegawai();
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
$arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $fileRowId, "", "", $infodetilparam);

$arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
// print_r($arrlistpilihfile);exit;
$arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

// $keymode= $riwayattable.";".$reqRowId.";foto";

$arrlistpilihfilefield= [];
$reqDokumenPilih= [];
foreach ($arrsetriwayatfield as $key => $value)
{
  $keymode= $value["riwayatfield"];
  $arrlistpilihfilefield[$keymode]= [];

  if(!empty($arrlistpilihfile))
  {
    $arrlistpilihfilefield[$keymode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode);

    $reqDokumenPilih[$keymode]= "";
    $infocari= "selected";
    $arraycari= in_array_column($infocari, "selected", $arrlistpilihfilefield[$keymode]);
    // print_r($arraycari);exit;
    if(!empty($arraycari))
    {
      // print_r($arraycari);exit;
      $reqDokumenPilih[$keymode]= 2;
    }
  }
}
// print_r($reqDokumenPilih);exit;
// print_r($arrlistpilihfilefield);exit;

$set= new KualitasFile();
$set->selectByParams(array());
// echo $set->query;exit;
$arrkualitasfile=[];
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["ID"]= $set->getField("KUALITAS_FILE_ID");
  $arrdata["TEXT"]= $set->getField("NAMA");
  array_push($arrkualitasfile, $arrdata);
}
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
  <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
  <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

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

  <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">


</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
      <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> DIKLAT KURSUS</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <?
                  $warnadata= $vTipeKursus['data'];
                  $warnaclass= $vTipeKursus['warna'];
                  ?>
                  <select name="reqTipeKursus" id="reqTipeKursus">
                    <option value=""></option>
                    <?
                    foreach ($arrtipekursus as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["text"];
                      $optionselected= "";
                      if($reqTipeKursus == $optionid)
                        $optionselected= "selected";
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqTipeKursus" class="<?=$warnaclass?>">
                    Tipe Kursus
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="input-field col s12 m12">
                  <label for="reqJenisKursus">Jenis Diklat/Kursus/Seminar/Workshop</label>
                  <input placeholder="" type="text" class="easyui-validatebox" name="reqJenisKursus" id="reqJenisKursusCari" <?=$read?> value="<?=$reqJenisKursus?>" />
                  <input type="hidden" name="reqJenisKursusId" id="reqJenisKursusId" value="<?=$reqJenisKursusId?>" />
                  <input type="hidden" name="reqJenisKursusData" id="reqJenisKursusData" value="<?=$reqJenisKursusData?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <?
                  $warnadata= $vNamaKursus['data'];
                  $warnaclass= $vNamaKursus['warna'];
                  ?>
                  <label for="reqNamaKursus" class="<?=$warnaclass?>">
                    Nama Diklat/Kursus/Seminar/Workshop
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input  placeholder="" required="" type="text" class="easyui-validatebox" name ='reqNamaKursus' value="<?=$reqNamaKursus?>" id="reqNamaKursus"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vNoSertifikat['data'];
                  $warnaclass= $vNoSertifikat['warna'];
                  ?>
                  <label for="reqNoSertifikat" class="<?=$warnaclass?>">
                    No Sertifikat / Piagam
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" required type="text" class="easyui-validatebox" name="reqNoSertifikat" <?=$read?> value="<?=$reqNoSertifikat?>" id="reqNoSertifikat" />
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vTglSertifikat['data'];
                  $warnaclass= $vTglSertifikat['warna'];
                  ?>
                  <label for="reqTglSertifikat" class="<?=$warnaclass?>">
                    Tgl Sertifikat / Piagam
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSertifikat" id="reqTglSertifikat"  value="<?=$reqTglSertifikat?>" required maxlength="10" onKeyDown="return format_date(event,'reqTglSertifikat');"/>
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vTglMulai['data'];
                  $warnaclass= $vTglMulai['warna'];
                  ?>
                  <label for="reqTglMulai" class="<?=$warnaclass?>">
                    Tgl Mulai
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vTglSelesai['data'];
                  $warnaclass= $vTglSelesai['warna'];
                  ?>
                  <label for="reqTglSelesai" class="<?=$warnaclass?>">
                    Tgl Selesai
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vJumlahJam['data'];
                  $warnaclass= $vJumlahJam['warna'];
                  ?>
                  <label for="reqJumlahJam" class="<?=$warnaclass?>">
                    Jumlah Jam
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" class="easyui-validatebox" required type="text" name="reqJumlahJam" id="reqJumlahJam" <?=$read?> value="<?=$reqJumlahJam?>" />
                </div>
                 <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vTahun['data'];
                  $warnaclass= $vTahun['warna'];
                  ?>
                  <label for="reqTahun" class="<?=$warnaclass?>">
                    Tahun
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
                  <input placeholder="" type="text" id="reqTahunText" disabled value="<?=$reqTahun?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m1">
                  <?
                  $warnadata= $vAngkatan['data'];
                  $warnaclass= $vAngkatan['warna'];
                  ?>
                  <label for="reqAngkatan" class="<?=$warnaclass?>">
                    Angkatan
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" type="text" name="reqAngkatan" id="reqAngkatan"  <?=$read?> value="<?=$reqAngkatan?>" />
                </div>
                <div class="input-field col s12 m1">
                  <?
                  $warnadata= $vTempat['data'];
                  $warnaclass= $vTempat['warna'];
                  ?>
                  <label for="reqTempat" class="<?=$warnaclass?>">
                    Tempat
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" type="text" name="reqTempat" id="reqAngkatan"  <?=$read?> value="<?=$reqTempat?>" />
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vStatusLulus['data'];
                  $warnaclass= $vStatusLulus['warna'];
                  ?>
                  <select id="reqStatusLulus" name="reqStatusLulus">
                    <option value="">Belum</option>
                    <option value="1" <? if($reqStatusLulus == "1") echo "selected";?>>Ya</option>
                  </select>
                  <label for="reqStatusLulus" class="<?=$warnaclass?>">
                    Lulus
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
                <div class="input-field col s12 m2">
                  <?
                  $warnadata= $vNilaiKompentensi['data'];
                  $warnaclass= $vNilaiKompentensi['warna'];
                  ?>
                  <label for="reqNilaiKompentensi" class="<?=$warnaclass?>">
                    Nilai Kompetensi
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqNilaiKompentensi" id="reqNilaiKompentensi" value="<?=$reqNilaiKompentensi?>" />
                  <input placeholder="" disabled type="text" id="reqNilaiKompentensiText" value="<?=$reqNilaiKompentensi?>" />
                </div>
                <div class="input-field col s3 m1" id="labelrumpunset">
                  <label for="reqRumpunJabatanNama">Rumpun Jabatan</label>
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunJabatanNama" value="<?=$reqRumpunJabatanNama?>" />
                </div>

                <div class="input-field col s3 m3">
                  <?
                  $warnadata= $vBidangTerkaitId['data'];
                  $warnaclass= $vBidangTerkaitId['warna'];
                  ?>
                  <select id="reqBidangTerkaitId" name="reqBidangTerkaitId">
                    <option value=""></option>
                    <?
                    // area untuk upload file
                    foreach ($arrbidangterkait as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["nama"];
                    ?>
                      <option value="<?=$optionid?>" <? if($reqBidangTerkaitId == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqBidangTerkaitId" class="<?=$warnaclass?>">
                    Bidang Terkait
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                </div>

                <div class="input-field col s3 m2">
                  <label for="reqRumpunNamaKompetensi">Rumpun Kompetensi</label>
                  <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                  <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunNamaKompetensi" value="<?=$reqRumpunNamaKompetensi?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <?
                  $warnadata= $vRefInstansi['data'];
                  $warnaclass= $vRefInstansi['warna'];
                  ?>
                  <label for="reqRefInstansi" class="<?=$warnaclass?>">
                    Instansi Penyelenggara
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqRefInstansi" id="reqRefInstansiCari" <?=$read?> value="<?=$reqRefInstansi?>" title="Penyelenggara harus diisi" />
                  <input type="hidden" name="reqRefInstansiId" id="reqRefInstansiId" value="<?=$reqRefInstansiId?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <?
                  $warnadata= $vPenyelenggara['data'];
                  $warnaclass= $vPenyelenggara['warna'];
                  ?>
                  <label for="reqPenyelenggara" class="<?=$warnaclass?>">
                    OPD/Unit Kerja Penyelenggara
                    <?
                    if(!empty($warnadata))
                    {
                    ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$warnadata?></span>
                      </a>
                    <?
                    }
                    ?>
                  </label>
                  <input placeholder="" type="text" name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" />
                </div>
              </div>

              <input type="hidden" name="reqStatusValidasi" id="reqStatusValidasi" value="<?=$reqStatusValidasi?>" />
              <!-- <div class="row">
                <div class="input-field col s12 m12">
                  <select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
                    <option value="" <? if($reqStatusValidasi == "") echo 'selected';?>></option>
                    <?
                    foreach($arrstatusvalidasi as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqStatusValidasi == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                    }
                    ?>
                  </select>
                  <label for="reqStatusValidasi">Status Klarifikasi</label>
                </div>
              </div> -->

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/verifikasi/validasi_verifikator";
                    });
                  </script>

                  <input type="hidden" name="cekquery" value="<?=$cekquery?>" />
                  <input type="hidden" name="reqValRowId" value="<?=$reqValRowId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
                  <input type="hidden" name="reqTempValidasiHapusId" value="<?=$reqTempValidasiHapusId?>" />

                  <?
                  if($tempAksesMenu == "A" && empty($reqValidasi))
                  {
                  ?>
                  <button type="submit" style="display:none" id="reqSubmit"></button>
                  <button class="btn waves-effect waves-light green reqsimpan" style="font-size:9pt" type="button">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <button class="btn red waves-effect waves-light reqkirim" style="font-size:9pt" type="button">Valid
                      <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <button class="btn black waves-effect waves-light reqbatal" style="font-size:9pt" type="button">Tolak
                    <i class="mdi-content-forward left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>


                </div>
              </div>


              <div class="row">
                <div class="input-field col s12 m12">
                <?
                // area untuk upload file
                foreach ($arrsetriwayatfield as $key => $value)
                {
                  $riwayatfield= $value["riwayatfield"];
                  $riwayatfieldtipe= $value["riwayatfieldtipe"];
                  $riwayatfieldinfo= $value["riwayatfieldinfo"];
                  $riwayatfieldstyle= $value["riwayatfieldstyle"];
                  // echo $riwayatfieldstyle;exit;
                ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt;<?=$riwayatfieldstyle?>" type="button" id='buttonframepdf<?=$riwayatfield?>'>
                    <input type="hidden" id="labelvpdf<?=$riwayatfield?>" value="<?=$riwayatfieldinfo?>" />
                    <span id="labelframepdf<?=$riwayatfield?>"><?=$riwayatfieldinfo?></span>
                  </button>
                <?
                }
                ?>
                </div>
              </div>

              <div class="row"><div class="col s12 m12"><br/></div></div>

              <?
              // area untuk upload file
              foreach ($arrsetriwayatfield as $key => $value)
              {
                $riwayatfield= $value["riwayatfield"];
                $riwayatfieldtipe= $value["riwayatfieldtipe"];
                $vriwayatfieldinfo= $value["riwayatfieldinfo"];
                $riwayatfieldinfo= " - ".$vriwayatfieldinfo;
                $riwayatfieldrequired= $value["riwayatfieldrequired"];
                $riwayatfieldrequiredinfo= $value["riwayatfieldrequiredinfo"];
                $vriwayattable= $value["vriwayattable"];
                $vriwayatid= "";
                $vpegawairowfile= $reqDokumenKategoriFileId."-".$vriwayattable."-".$riwayatfield."-".$vriwayatid;
              ?>
              <div class="row">
                <div class="input-field col s12 m4">
                  <input type="hidden" id="reqDokumenRequired<?=$riwayatfield?>" name="reqDokumenRequired[]" value="<?=$riwayatfieldrequired?>" />
                  <input type="hidden" id="reqDokumenRequiredNama<?=$riwayatfield?>" name="reqDokumenRequiredNama[]" value="<?=$vriwayatfieldinfo?>" />
                  <input type="hidden" id="reqDokumenRequiredTable<?=$riwayatfield?>" name="reqDokumenRequiredTable[]" value="<?=$vriwayattable?>" />
                  <input type="hidden" id="reqDokumenRequiredTableRow<?=$riwayatfield?>" name="reqDokumenRequiredTableRow[]" value="<?=$vpegawairowfile?>" />
                  <input type="hidden" id="reqDokumenFileId<?=$riwayatfield?>" name="reqDokumenFileId[]" />
                  <input type="hidden" id="reqDokumenKategoriFileId<?=$riwayatfield?>" name="reqDokumenKategoriFileId[]" value="<?=$reqDokumenKategoriFileId?>" />
                  <input type="hidden" id="reqDokumenKategoriField<?=$riwayatfield?>" name="reqDokumenKategoriField[]" value="<?=$riwayatfield?>" />
                  <input type="hidden" id="reqDokumenPath<?=$riwayatfield?>" name="reqDokumenPath[]" value="" />
                  <input type="hidden" id="reqDokumenTipe<?=$riwayatfield?>" name="reqDokumenTipe[]" value="<?=$riwayatfieldtipe?>" />

                  <select id="reqDokumenPilih<?=$riwayatfield?>" name="reqDokumenPilih[]">
                    <?
                    foreach ($arrpilihfiledokumen as $key => $value)
                    {
                      $optionid= $value["id"];
                      $optiontext= $value["nama"];
                    ?>
                      <option value="<?=$optionid?>" <? if($reqDokumenPilih[$riwayatfield] == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenPilih<?=$riwayatfield?>">
                    File Dokumen<?=$riwayatfieldinfo?>
                    <span id="riwayatfieldrequiredinfo<?=$riwayatfield?>" style="color: red;"><?=$riwayatfieldrequiredinfo?></span>
                  </label>
                </div>

                <div class="input-field col s12 m4">
                  <select <?=$disabled?> name="reqDokumenFileKualitasId[]" id="reqDokumenFileKualitasId<?=$riwayatfield?>">
                    <option value=""></option>
                    <?
                    foreach ($arrkualitasfile as $key => $value)
                    {
                      $optionid= $value["ID"];
                      $optiontext= $value["TEXT"];
                      $optionselected= "";
                      if($reqDokumenFileKualitasId == $optionid)
                        $optionselected= "selected";

                      $arrkecualitipe= [];
                      $arrkecualitipe= $vfpeg->kondisikategori($riwayatfieldtipe);
                      if(!in_array($optionid, $arrkecualitipe))
                        continue;
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenFileKualitasId<?=$riwayatfield?>">Kualitas Dokumen<?=$riwayatfieldinfo?></label>
                </div>

                <div id="labeldokumenfileupload<?=$riwayatfield?>" class="input-field col s12 m4" style="margin-top: -25px; margin-bottom: 10px;">
                  <div class="file_input_div">
                    <div class="file_input input-field col s12 m4">
                      <label class="labelupload">
                        <i class="mdi-file-file-upload" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Upload</i>
                        <input id="file_input_file" name="reqLinkFile[]" class="none" type="file" />
                      </label>
                    </div>
                    <div id="file_input_text_div" class=" input-field col s12 m8">
                      <input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                      <label for="file_input_text"></label>
                    </div>
                  </div>
                </div>

                <div id="labeldokumendarifileupload<?=$riwayatfield?>" class="input-field col s12 m4">
                  <select id="reqDokumenIndexId<?=$riwayatfield?>" name="reqDokumenIndexId[]">
                    <option value="" selected></option>
                    <?
                    $arrlistpilihfilepegawai= $arrlistpilihfilefield[$riwayatfield];
                    foreach ($arrlistpilihfilepegawai as $key => $value)
                    {
                      $optionid= $value["index"];
                      $optiontext= $value["nama"];
                      $optionselected= $value["selected"];
                    ?>
                      <option value="<?=$optionid?>" <?=$optionselected?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDokumenIndexId<?=$riwayatfield?>">Nama e-File<?=$riwayatfieldinfo?></label>
                </div>

              </div>
              <?
              }
              // area untuk upload file
              ?>

              <?
              for($i=0; $i<4;$i++)
              {
              ?>
              <br/>
              <?
              }
              ?>

            </form>
          </li>
        </ul>
      </div>

      <div id='divframepdf' class="col s12 m6" style="padding-top: 10px;">
        <input type="hidden" id="vnewframe" value="">
        <span id="labelriwayatframepdf"></span>
        <img id="infonewimage" style="width:inherit; width: 100%; height: 100%" />
        <iframe id="infonewframe" style="width: 100%; height: 160%" src=""></iframe>
      </div>
      
    </div>
  </div>

</div>
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">

  $(function(){
      $("#labelrumpunset").show();
      $("#labelrumpunselect").hide();
      reqJenisKursusId= "<?=$reqJenisKursusId?>";
      if(reqJenisKursusId == "")
      {
        $("#labelrumpunset").hide();
        $("#labelrumpunselect").show();
      }

      $('#ff').form({
        url:'validasi/diklat_kursus_json/add',
        onSubmit:function(){
          if($(this).form('validate')){}
          else
          {
            $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
            return false;
          }
        },
        success:function(data){
          <?
          if(!empty($cekquery))
          {
          ?>
            console.log(data);return false;
          <?
          }
          ?>
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          //$.messager.alert('Info', infodata, 'info');

          if(rowid == "xxx")
          {
            mbox.alert(infodata, {open_speed: 0});
          }
          else
          {
            mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
            {
              clearInterval(interval);
              mbox.close();

              reqStatusValidasi= $("#reqStatusValidasi").val();
              if(reqStatusValidasi == "1")
              {
                ajaxurl= "validasi/sess_json/sessdetil?m=diklatkursus&reqId=<?=$reqId?>&reqRowId="+rowid;
                // console.log(ajaxurl);return false;
                $.ajax({
                  cache: false,
                  url: ajaxurl,
                  processData: false,
                  contentType: false,
                  type: 'GET',
                  dataType: 'json',
                  success: function (response) {
                    // console.log(response);return false;

                    document.location.href= "app/loadUrl/app/pegawai_add?reqId=<?=$reqId?>";
                    /*tempindextab= 0;
                    newWindow = window.open("app/loadUrl/app/pegawai_add?reqId=<?=$reqId?>", 'Cetak'+tempindextab);
                    newWindow.focus();
                    tempindextab= parseInt(tempindextab) + 1;*/

                  },
                  error: function(xhr, status, error) {
                  },
                  complete: function () {
                  }
                });
              }
              else if(reqStatusValidasi == "2")
              {
                document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
              }
              else
              {
                document.location.reload();
              }

            }, 1000));
            $(".mbox > .right-align").css({"display": "none"});
          }
          
        }
      });

      $(".reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        $("#reqSubmit").click();
      });

      $(".reqkirim").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        mbox.custom({
          message: "Apakah Anda Yakin, valid. Pastikan entri data sudah sesuai ?",
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {
              $("#reqStatusValidasi").val(1);
              $("#reqSubmit").click();
              mbox.close();
            }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
            mbox.close();
           }
          }
          ]
        });
      });

      $(".reqbatal").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        mbox.custom({
          message: "Apakah Anda Yakin, tolak. Pastikan entri data sudah sesuai ?",
          options: {close_speed: 100},
          buttons: [
          {
            label: 'Ya',
            color: 'green darken-2',
            callback: function() {
              $("#reqStatusValidasi").val(2);
              $("#reqSubmit").click();
              mbox.close();
            }
          },
          {
            label: 'Tidak',
            color: 'grey darken-2',
            callback: function() {
            mbox.close();
           }
          }
          ]
        });
      });

  });

  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>
<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>