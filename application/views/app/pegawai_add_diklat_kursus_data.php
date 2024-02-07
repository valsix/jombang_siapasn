<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('DiklatKursus');
$this->load->model('Rumpun');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
// echo $reqId; exit;
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010604";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

// kondisi untuk menu
$this->load->library('globalmenusapk');
$vmenusapk= new globalmenusapk();
$arrmenusapk= $vmenusapk->setmenusapk($tempMenuId);
// print_r($arrmenusapk);exit;
$lihatsapk= $arrmenusapk["lihat"];
$kirimsapk= $arrmenusapk["kirim"];
$tariksapk= $arrmenusapk["tarik"];
$syncsapk= $arrmenusapk["sync"];

$infomode= "TAMBAH";
if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $infomode= "UBAH";
  $reqMode = 'update';
}

if(empty($reqRowId)) $reqRowId= -1;

$statement= " AND A.DIKLAT_KURSUS_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set = new DiklatKursus();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query; exit;
$set->firstRow();

$reqNamaDiklat= $set->getField('NAMA');
$reqTipeKursus= $set->getField('TIPE_KURSUS_ID');
$reqJenisKursus= $set->getField('JENIS_KURSUS_NAMA');
$reqJenisKursusId= $set->getField('REF_JENIS_KURSUS_ID');
$reqJenisKursusData= $set->getField('REF_JENIS_KURSUS_DATA');
$reqTahun= $set->getField('TAHUN');
$reqRefInstansiId = $set->getField('REF_INSTANSI_ID');
$reqRefInstansi= $set->getField('REF_INSTANSI_INFO');
$reqPenyelenggara = $set->getField('PENYELENGGARA');
$reqNamaKursus= $set->getField('NAMA');
$reqTglSertifikat= dateToPageCheck($set->getField('TANGGAL_SERTIFIKAT'));
$reqNoSertifikat= $set->getField('NO_SERTIFIKAT');
$reqAngkatan= $set->getField('ANGKATAN');
$reqRumpunJabatan= $set->getField('RUMPUN_ID');
$reqRumpunJabatanNama= $set->getField('RUMPUN_NAMA');
$reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
$reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));
$reqJumlahJam= $set->getField('JUMLAH_JAM');
$reqNilaiKompentensi= $set->getField('NILAI_REKAM_JEJAK');
$reqTempat= $set->getField('TEMPAT');
$reqStatusLulus= $set->getField('STATUS_LULUS');
$reqBidangTerkaitId= $set->getField('BIDANG_TERKAIT_ID');
$reqRumpunNamaKompetensi= $set->getField('RUMPUN_NAMA');
$vidsapk= $set->getField("ID_SAPK");

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

$rumpun= new Rumpun();
$rumpun->selectByParams(array());

$statement= " AND A.TIPE_KURSUS_ID NOT IN (1)";
$tipekursus= new DiklatKursus();
$tipekursus->selecttipekursus(array(), -1,-1, $statement);

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
// print_r($arrinfonilairumpun);exit;

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "DIKLAT_KURSUS";
$reqDokumenKategoriFileId= "25"; // ambil dari table KATEGORI_FILE, cek sesuai mode
$arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
// print_r($arrsetriwayatfield);exit;

if(empty($reqRowId))
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru");
else
  $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId);

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

 <!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

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
    url:'diklat_kursus_json/add',
    onSubmit:function(){
      reqTipeKursus= $("#reqTipeKursus").val();

      if(reqTipeKursus == "")
      {
        $.messager.alert('Info', "Isikan terlebih dahulu tipe kursus", 'info');
        return false;
      }

      reqBidangTerkaitId= $("#reqBidangTerkaitId").val();
      if(reqBidangTerkaitId == "")
      {
        $.messager.alert('Info', "Isikan terlebih dahulu Bidang Terkait", 'info');
        return false;
      }

      if($(this).form('validate')){}
      else
      {
        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
        return false;
      }
    },
    success:function(data){
      // console.log(data);return false;
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
          document.location.href= "app/loadUrl/app/pegawai_add_diklat_kursus_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }
      
    }
  });

});

</script>

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
</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div id='main' class="col s12 m12" style="padding-left: 15px;">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infomode?> DIKLAT KURSUS</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
            
              <div class="row">
                <div class="input-field col s12 m6">
                  <select name="reqTipeKursus" id="reqTipeKursus">
                    <option value=""></option>
                    <?
                    while($tipekursus->nextRow())
                    {
                      $infoid= $tipekursus->getField("TIPE_KURSUS_ID");
                      $infonama= $tipekursus->getField("NAMA");
                    ?>
                    <option value="<?=$infoid?>" <? if($reqTipeKursus == $infoid){ echo "selected"; }?> ><?=$infonama?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqTipeKursus">Tipe Kursus</label>
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
                  <label for="reqNamaKursus">Nama Diklat/Kursus/Seminar/Workshop</label>
                  <input  placeholder="" required="" type="text" class="easyui-validatebox" name ='reqNamaKursus' value="<?=$reqNamaKursus?>" id="reqNamaKursus"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqNoSertifikat">No Sertifikat / Piagam </label>
                  <input placeholder="" required type="text" class="easyui-validatebox" name="reqNoSertifikat" <?=$read?> value="<?=$reqNoSertifikat?>" id="reqNoSertifikat" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglSertifikat">Tgl Sertifikat / Piagam</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSertifikat" id="reqTglSertifikat"  value="<?=$reqTglSertifikat?>" required maxlength="10" onKeyDown="return format_date(event,'reqTglSertifikat');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglMulai">Tgl Mulai </label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglSelesai">Tgl Selesai</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqJumlahJam">Jumlah Jam</label>
                  <input placeholder="" class="easyui-validatebox" required type="text" name="reqJumlahJam" id="reqJumlahJam" <?=$read?> value="<?=$reqJumlahJam?>" />
                </div>
                 <div class="input-field col s12 m2">
                  <label for="reqTahun">Tahun</label>
                  <input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
                  <input placeholder="" type="text" id="reqTahunText" disabled value="<?=$reqTahun?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m2">
                  <label for="reqAngkatan">Angkatan</label>
                  <input placeholder="" type="text" name="reqAngkatan" id="reqAngkatan"  <?=$read?> value="<?=$reqAngkatan?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTempat">Tempat</label>
                  <input placeholder="" type="text" name="reqTempat" id="reqAngkatan"  <?=$read?> value="<?=$reqTempat?>" />
                </div>
                <div class="input-field col s12 m2">
                  <select id="reqStatusLulus" name="reqStatusLulus">
                    <option value="">Belum</option>
                    <option value="1" <? if($reqStatusLulus == "1") echo "selected";?>>Ya</option>
                  </select>
                  <label for="reqStatusLulus">Lulus</label>
                </div>
                <div class="input-field col s12 m1">
                  <label for="reqNilaiKompentensi">Nilai Kompetensi</label>
                  <input type="hidden" name="reqNilaiKompentensi" id="reqNilaiKompentensi" value="<?=$reqNilaiKompentensi?>" />
                  <input placeholder="" disabled type="text" id="reqNilaiKompentensiText" value="<?=$reqNilaiKompentensi?>" />
                </div>
                <div class="input-field col s3 m2" id="labelrumpunset">
                    <label for="reqRumpunJabatanNama">Rumpun Jabatan</label>
                    <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunJabatanNama" value="<?=$reqRumpunJabatanNama?>" />
                </div>

                <div class="input-field col s3 m3">
                  
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
                  <label for="reqBidangTerkaitId">Bidang Terkait</label>
                </div>

                <div class="input-field col s3 m2">
                    <label for="reqRumpunNamaKompetensi">Rumpun Kompetensi</label>
                    <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                    <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunNamaKompetensi" value="<?=$reqRumpunNamaKompetensi?>" />
                </div>

                <!-- <div class="input-field col s3 m2" id="labelrumpunselect">
                  
                  <select id="reqRumpunJabatanSelect">
                    <option value=""></option>
                    <?
                    while ($rumpun->nextRow())
                    {
                      $optionid= $rumpun->getField("RUMPUN_ID");
                      $optiontext= $rumpun->getField("KETERANGAN");
                    ?>
                      <option value="<?=$optionid?>" <? if($reqRumpunJabatan == $optionid) echo "selected";?>><?=$optiontext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqRumpunJabatan">Rumpun Kompetensi</label>
                </div> -->

              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqRefInstansi"> Instansi Penyelenggara</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqRefInstansi" id="reqRefInstansiCari" <?=$read?> value="<?=$reqRefInstansi?>" title="Penyelenggara harus diisi" />
                  <input type="hidden" name="reqRefInstansiId" id="reqRefInstansiId" value="<?=$reqRefInstansiId?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPenyelenggara">OPD/Unit Kerja Penyelenggara</label>
                  <input placeholder="" type="text" name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" />
                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_diklat_kursus_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <?
                  // A;R;D
                  if($tempAksesMenu == "A")
                  {
                  ?>
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if(!empty($reqRowId) && !empty($kirimsapk)  && $reqRowId !=-1)
                  {
                  ?>
                  <button class="btn  waves-effect waves-light" style="font-size:9pt;background: #9C28B0;" type="button" id='buttonbtn'>
                    <input type="hidden" id="reqIdField" value="<?=$reqRowId?>" />
                    <input type="hidden" id="reqIdBkn" value="<?=$vidsapk?>" />
                    <input type="hidden" id="reqUrlBkn" value="kursus_json" />
                    <i class="mdi-content-save left hide-on-small-only"></i> <span > UPDATE KE SIASN BKN</span>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if(!empty($vidsapk) && !empty($lihatsapk))
                  {
                    $vdetilsapk= $vidsapk."___pegawai_add_diklat_kursus_sapk_data";
                    $vdetillabelsapk= "Data SAPK BKN";
                  ?>
                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id='buttondatasapk<?=$vdetilsapk?>'>
                    <input type="hidden" id="labelvsapk<?=$vdetilsapk?>" value="<?=$vdetillabelsapk?>" />
                    <span id="labelframesapk<?=$vdetilsapk?>">Cek <?=$vdetillabelsapk?></span>
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
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

<!--materialize js-->
<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<style type="text/css">
  .select-dropdown {
    max-height:250px !important; overflow:auto !important;
  }
</style>

<!-- tambahan lib cek tanggal -->
<script src="lib/moment/moment-with-locales.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('#reqTglSelesai').keyup(function() {
    var vtanggalakhir= $('#reqTglSelesai').val();
    var vtanggalawal= $('#reqTglMulai').val();
    var checktanggalakhir= moment(vtanggalakhir , 'DD-MM-YYYY', true).isValid();
    var checktanggalawal= moment(vtanggalawal , 'DD-MM-YYYY', true).isValid();
    // console.log(checktanggalakhir+'_'+checktanggalawal);
    if(checktanggalakhir == true && checktanggalawal == true)
    {
      var tglakhir = moment(vtanggalakhir, 'DD-MM-YYYY');  // format tanggal
      var tglawal = moment(vtanggalawal, 'DD-MM-YYYY'); 

      if (tglakhir.isSameOrAfter(tglawal)) {} 
      else 
      {
         $('#reqTglSelesai').val(vtanggalawal);
      }

      vtanggalakhir= $('#reqTglSelesai').val();
      vtahun= vtanggalakhir.substring(6,10);
      $("#reqTahun, #reqTahunText").val(vtahun);
    }

  });

  arrbidangterkait= JSON.parse('<?=JSON_encode($arrbidangterkait)?>');
  // console.log(arrbidangterkait);

  $("#reqBidangTerkaitId").change(function() { 
    var reqBidangTerkaitId= $("#reqBidangTerkaitId").val();

    reqNilaiKompentensiText= 0;
    infoid= reqBidangTerkaitId;
    varrbidangterkait= arrbidangterkait.filter(item => item.id === infoid);
    // console.log(varrbidangterkait);
    vtextid= vtext= "";
    if(Array.isArray(varrbidangterkait) && varrbidangterkait.length)
    {
      vtextid= varrbidangterkait[0]["rumpunid"];
      vtext= varrbidangterkait[0]["rumpun"];
    }

    $("#reqRumpunJabatan").val(vtextid);
    $("#reqRumpunNamaKompetensi").val(vtext);
  });

  // ambil rating penggalian
  getarrinfonilairumpun= JSON.parse('<?=JSON_encode($arrinfonilairumpun)?>');
  // console.log(getarrinfonilairumpun);

  $("#reqTipeKursus").change(function() { 
    var reqTipeKursus= $("#reqTipeKursus").val();

    reqNilaiKompentensiText= 0;
    infoid= reqTipeKursus;
    valarrinfonilairumpun= getarrinfonilairumpun.filter(item => item.ID === infoid);
    // console.log(valarrinfonilairumpun);
    if(Array.isArray(valarrinfonilairumpun) && valarrinfonilairumpun.length)
    {
      reqNilaiKompentensiText= valarrinfonilairumpun[0]["NILAI"];
    }
    
    /*if(reqTipeKursus == "1")
      reqNilaiKompentensiText= 15;
    else if(reqTipeKursus == "2")
      reqNilaiKompentensiText= 15;
    else if(reqTipeKursus == "3")
      reqNilaiKompentensiText= 10;*/

    $("#reqNilaiKompentensi, #reqNilaiKompentensiText").val(reqNilaiKompentensiText);
  });

  $("#reqJumlahJam, #reqTahun").keypress(function(e) {
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {
      return false;
    }
  });

  $('.materialize-textarea').trigger('autoresize');

  $("#reqRumpunJabatanSelect").change(function() { 
    var reqRumpunJabatan= $("#reqRumpunJabatanSelect").val();
    var reqRumpunJabatanNama= $("#reqRumpunJabatanSelect option:selected").text();
    // console.log(reqRumpunJabatanNama);

    $("#reqRumpunJabatan").val(reqRumpunJabatan);
    $("#reqRumpunJabatanNama").val(reqRumpunJabatanNama);
  });

  $('#reqJenisKursusCari').keyup(function() {
    reqJenisKursusCari= $(this).val();
    // console.log(reqJenisKursusCari);

    if(reqJenisKursusCari == "")
    {
      $("#reqJenisKursusData, #reqJenisKursusId, #reqRumpunJabatan, #reqRumpunJabatanNama").val("");
      $("#labelrumpunset").hide();
      $("#labelrumpunselect").show();
    }
  });

  $('#reqRefInstansiCari').keyup(function() {
    reqRefInstansiCari= $(this).val();
    // console.log(reqRefInstansiCari);

    if(reqRefInstansiCari == "")
    {
      $("#reqRefInstansiId").val("");
    }
  });

  $("#reqJenisKursusCari, #reqRefInstansiCari").each(function() {
      $(this).autocomplete({
          source:function(request, response) {
              var id= this.element.attr('id');
              // console.log(id);
              var urlAjax= "";

              if(id=="reqJenisKursusCari")
              {
                urlAjax= "diklat_kursus_json/jeniskursus";
              }
              else if (id=="reqRefInstansiCari")
              {
                urlAjax= "diklat_kursus_json/instansi";  
              }

              $.ajax({
                  url: urlAjax,
                  type: "GET",
                  dataType: "json",
                  data: { term: request.term },
                  success: function(responseData) {
                    // console.log(responseData);

                    if(Array.isArray(responseData) && responseData.length)
                    {
                      var array = responseData.map(function(element) {
                        return {desc: element['desc'], id: element['id'], label: element['label'], rumpun_id: element['rumpun_id'], rumpun_nama: element['rumpun_nama']};
                      });
                      response(array);
                    }
                    else
                    { 
                      if (id=="reqRefInstansiCari")
                      {
                        $("#reqRefInstansiId").val("");
                      }
                      response(null);
                    }
                  }
                })
          },
          focus: function (event, ui) 
          {
              var id= $(this).attr('id');
              var infoid= infolabel= "";
              infoid= ui.item.id;
              infolabel= ui.item.label;
              if(id=="reqJenisKursusCari")
              {
                // $("#reqJenisKursusCari").val(infolabel);
                // reqJenisKursusCari= $("#reqJenisKursusCari").val();
                // console.log(reqJenisKursusCari);
                // console.log(infolabel);
                // if(infolabel == reqJenisKursusCari)
                // {
                  $("#reqJenisKursusId").val(infoid);
                  $("#reqJenisKursusData").val(infolabel);
                  $("#reqRumpunJabatan").val(ui.item.rumpun_id).trigger('change');
                  $("#reqRumpunJabatanNama").val(ui.item.rumpun_nama).trigger('change');

                  $("#labelrumpunset").show();
                  $("#labelrumpunselect").hide();
                // }
              }
              else if (id=="reqRefInstansiCari")
              {
                 $("#reqRefInstansiId").val(infoid);
              }
          },
          autoFocus: true
      })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
      .append( "<a>" + item.desc  + "</a>" )
      .appendTo( ul );
    }
    ;
  });

  // untuk area untuk upload file
  vbase_url= "<?=base_url()?>";
  getarrlistpilihfilefield= JSON.parse('<?=JSON_encode($arrlistpilihfilefield)?>');
  // console.log(getarrlistpilihfilefield);

  // apabila butuh kualitas dokumen di ubah
  vselectmaterial= "1";
  // untuk area untuk upload file

</script>

<script type="text/javascript" src="lib/easyui/pelayanan-efile.js"></script>
<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>