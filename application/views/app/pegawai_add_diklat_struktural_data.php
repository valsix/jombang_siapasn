<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('DiklatStruktural');
$this->load->model('Diklat');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$pelayananid= $this->input->get("pelayananid");
$pelayananjenis= $this->input->get("pelayananjenis");
$pelayananrowid= $this->input->get("pelayananrowid");
$pelayanankembali= $this->input->get("pelayanankembali");

$diklat= new Diklat();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010601";
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

$diklat->selectByParams(array());

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.DIKLAT_STRUKTURAL_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new DiklatStruktural();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqDiklat= $set->getField('DIKLAT_ID');
  $reqTahun= $set->getField('TAHUN');
  $reqNoSttpp= $set->getField('NO_STTPP');
  $reqPenyelenggara= $set->getField('PENYELENGGARA');
  $reqAngkatan= $set->getField('ANGKATAN');
  $reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
  $reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));
  $reqTglSttpp= dateToPageCheck($set->getField('TANGGAL_STTPP'));
  $reqTempat= $set->getField('TEMPAT');
  $reqJumlahJam= $set->getField('JUMLAH_JAM');

  $reqNilaiKompentensi= $set->getField('NILAI_REKAM_JEJAK');
  $reqRumpunJabatan= $set->getField('RUMPUN_ID');
  $reqRumpunJabatanNama= $set->getField('RUMPUN_NAMA');

  $reqJabatanRiwayatId= $set->getField("JABATAN_RIWAYAT_ID");
  $reqJabatanRiwayatNama= $set->getField("JABATAN_RIWAYAT_NAMA");
  $reqJabatanRiwayatEselon= $set->getField("JABATAN_RIWAYAT_ESELON");
  $reqJabatanRiwayatSatkerNama= $set->getField("JABATAN_RIWAYAT_SATKER");

  $vidsapk= $set->getField("ID_SAPK");
}

if(empty($reqNilaiKompentensi))
  $reqNilaiKompentensi= "15";

$diklat->selectByParams(array(),-1,-1, '');

// untuk kondisi file
$vfpeg= new globalfilepegawai();
$arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
// print_r($arrpilihfiledokumen);exit;

$riwayattable= "DIKLAT_STRUKTURAL";
$reqDokumenKategoriFileId= "11"; // ambil dari table KATEGORI_FILE, cek sesuai mode
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

<script type="text/javascript"> 
  $(function(){
    $('#ff').form({
      url:'diklat_struktural_json/add',
      onSubmit:function(){
        reqJabatanRiwayatId= $("#reqJabatanRiwayatId").val();
        if(reqJabatanRiwayatId == "")
        {
          mbox.alert("Isikan nama jabatan terlebih dahulu.", {open_speed: 0});
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
           <?
           if(!empty($pelayananid))
           {
           ?>
           vkembali= "app/loadUrl/app/pegawai_add_diklat_struktural_data?reqId=<?=$reqId?>&reqRowId="+rowid+"&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
           <?
           }
           else
           {
           ?>
           vkembali= "app/loadUrl/app/pegawai_add_diklat_struktural_data?reqId=<?=$reqId?>&reqRowId="+rowid;
           <?
           }
           ?>
           document.location.href= vkembali;
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
         <li class="collection-item ubah-color-warna">EDIT DIKLAT STRUKTURAL</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqDiklat" id="reqDiklat">
                    <?
                    while($diklat->nextRow())
                    {
                    ?>
                    <option <?=$read?> value="<?=$diklat->getField('DIKLAT_ID')?>" <? if($diklat->getField('DIKLAT_ID')==$reqDiklat) echo 'selected'?>><?=$diklat->getField('NAMA')?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqDiklat">Jenjang Diklat Struktural</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m4">
                  <label for="reqNoSttpp">No Sertifikat / Piagam</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqNoSttpp" id="reqNoSttpp" <?=$read?> value="<?=$reqNoSttpp?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglSttpp">Tgl Sertifikat / Piagam</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttpp" id="reqTglSttpp"  value="<?=$reqTglSttpp?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttpp');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglMulai">Tgl Mulai</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTglSelesai">Tgl Selesai</label>
                  <input placeholder="" required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
                <div class="input-field col s12 m1">
                  <label for="reqJumlahJam">Jam</label>
                  <input placeholder="" type="text" id="reqJumlahJam" class="easyui-validatebox" required name="reqJumlahJam" value="<?=$reqJumlahJam?>" />
                </div>
                <div class="input-field col s12 m1">
                  <label for="reqTahun">Tahun</label>
                  <input type="hidden" name="reqTahun" id="reqTahun" value="<?=$reqTahun?>" />
                  <input placeholder="" type="text" id="reqTahunText" disabled value="<?=$reqTahun?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m10">
                  <label for="reqJabatanRiwayatNama">Nama Jabatan</label>
                  <input type="hidden" name="reqJabatanRiwayatId" id="reqJabatanRiwayatId" value="<?=$reqJabatanRiwayatId?>" />
                  <input placeholder="" type="text" id="reqJabatanRiwayatNama" disabled value="<?=$reqJabatanRiwayatNama?>" />
                </div>
                <div class="input-field col s12 m10">
                  <label for="reqJabatanRiwayatEselon">Eselon</label>
                  <input placeholder="" type="text" id="reqJabatanRiwayatEselon" disabled value="<?=$reqJabatanRiwayatEselon?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqJabatanRiwayatSatkerNama">OPD Unit Kerja</label>
                  <input placeholder="" type="text" id="reqJabatanRiwayatSatkerNama" disabled value="<?=$reqJabatanRiwayatSatkerNama?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqAngkatan">Angkatan</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqAngkatan" id="reqAngkatan" <?=$read?> value="<?=$reqAngkatan?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTempat">Tempat</label>
                  <input placeholder="" type="text" id="reqTempat" name="reqTempat" <?=$read?> value="<?=$reqTempat?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqNilaiKompentensi">Nilai Kompetensi</label>
                  <input type="hidden" name="reqNilaiKompentensi" id="reqNilaiKompentensi" value="<?=$reqNilaiKompentensi?>" />
                  <input placeholder="" disabled type="text" id="reqNilaiKompentensiText" value="<?=$reqNilaiKompentensi?>" />
                </div>

                <div class="input-field col s3 m3" id="labelrumpunset">
                    <label for="reqRumpunJabatanNama">Rumpun Jabatan</label>
                    <input type="hidden" name="reqRumpunJabatan" id="reqRumpunJabatan" value="<?=$reqRumpunJabatan?>" />
                    <input placeholder="" type="text" disabled class="easyui-validatebox" id="reqRumpunJabatanNama" value="<?=$reqRumpunJabatanNama?>" />
                </div>
              </div>

              <div class="row">
              <div class="input-field col s12 m12">
                  <label for="reqPenyelenggara">Penyelenggara</label>
                  <input placeholder="" type="text" class="easyui-validatebox" required name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" title="Penyelenggara harus diisi" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      <?
                      if(!empty($pelayananid))
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_diklat_struktural_monitoring?reqId=<?=$reqId?>&pelayananid=<?=$pelayananid?>&pelayananjenis=<?=$pelayananjenis?>&pelayananrowid=<?=$pelayananrowid?>&pelayanankembali=<?=$pelayanankembali?>";
                      <?
                      }
                      else
                      {
                      ?>
                      vkembali= "app/loadUrl/app/pegawai_add_diklat_struktural_monitoring?reqId=<?=$reqId?>";
                      <?
                      }
                      ?>
                      document.location.href = vkembali;
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
                  if(!empty($reqRowId) && !empty($kirimsapk) )
                  {
                  ?>
                  <button class="btn  waves-effect waves-light" style="font-size:9pt;background: #9C28B0;" type="button" id='buttonbtn'>
                    <input type="hidden" id="reqIdField" value="<?=$reqRowId?>" />
                    <input type="hidden" id="reqIdBkn" value="<?=$vidsapk?>" />
                    <input type="hidden" id="reqUrlBkn" value="diklat_json" />
                    <i class="mdi-content-save left hide-on-small-only"></i> <span > UPDATE KE SIASN BKN</span>
                  </button>
                  <?
                  }
                  ?>

                  <?
                  if(!empty($vidsapk) && !empty($lihatsapk))
                  {
                    $vdetilsapk= $vidsapk."___pegawai_add_diklat_struktural_sapk_data";
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

  $('#reqTglMulai').keyup(function() {
    var vtanggalawal= $('#reqTglMulai').val();
    var checktanggalawal= moment(vtanggalawal , 'DD-MM-YYYY', true).isValid();
    if(checktanggalawal == true)
    {
      ajaxurl= "jabatan_riwayat_json/jabatandiklatstruktural?reqId=<?=$reqId?>&reqTanggal="+vtanggalawal;
      $.ajax({
        cache: false,
        url: ajaxurl,
        processData: false,
        contentType: false,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          if(Array.isArray(response) && response.length)
          {
            response= response[0];
            // console.log(response); return false;
            jabatanriwayatid= response.infoid;
            jabatanriwayatnama= response.infonama;
            jabatanriwayateselon= response.infoeselonnama;
            jabatanriwayatsatker= response.infosatkernama;
            jabatanriwayatrumpunid= response.inforumpunid;
            jabatanriwayatrumpunnama= response.inforumpunnama;

            $("#reqJabatanRiwayatId").val(jabatanriwayatid);
            $("#reqJabatanRiwayatNama").val(jabatanriwayatnama);
            $("#reqJabatanRiwayatEselon").val(jabatanriwayateselon);
            $("#reqJabatanRiwayatSatkerNama").val(jabatanriwayatsatker);
            $("#reqRumpunJabatan").val(jabatanriwayatrumpunid);
            $("#reqRumpunJabatanNama").val(jabatanriwayatrumpunnama);
          }
          else
          {
            // console.log("a");
          }
        },
        error: function(xhr, status, error) {
        },
        complete: function () {
        }
      });
    }
  });

  $("#reqJumlahJam, #reqTahun").keypress(function(e) {
    if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
    {
      return false;
    }
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
<script type="text/javascript" src="lib/easyui/pelayanan-bkndetil.js"></script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>