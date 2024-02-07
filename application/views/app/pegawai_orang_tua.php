<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('OrangTua');
$this->load->model('KualitasFile');
$this->load->library('globalfilepegawai');

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011003";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$ayah = new OrangTua();
$ayah->selectByParams(array("PEGAWAI_ID" => $reqId, "JENIS_KELAMIN" => "L"),-1,-1, '');
$ayah->firstRow();
$ibu  = new OrangTua();
$ibu->selectByParams(array("PEGAWAI_ID" => $reqId, "JENIS_KELAMIN" => "P"),-1,-1, '');
$ibu->firstRow();

$reqIdAyah= (int)$ayah->getField("ORANG_TUA_ID");
$reqIdIbu= (int)$ibu->getField("ORANG_TUA_ID");
$reqNamaAyah= $ayah->getField('NAMA');
$reqNamaIbu= $ibu->getField('NAMA');
$reqTmptLahirAyah= $ayah->getField('TEMPAT_LAHIR');
$reqTmptLahirIbu= $ibu->getField('TEMPAT_LAHIR');
$reqTglLahirAyah= dateToPageCheck($ayah->getField('TANGGAL_LAHIR'));
$reqTglLahirIbu= dateToPageCheck($ibu->getField('TANGGAL_LAHIR'));
$reqUsiaAyah= $ayah->getField('USIA');
$reqUsiaIbu= $ibu->getField('USIA');
$reqPekerjaanAyah= $ayah->getField('PEKERJAAN');
$reqPekerjaanIbu= $ibu->getField('PEKERJAAN');
$reqAlamatAyah= $ayah->getField('ALAMAT');
$reqAlamatIbu= $ibu->getField('ALAMAT');
$reqPropinsiAyahId= $ayah->getField('PROPINSI_ID');
$reqPropinsiIbuId= $ibu->getField('PROPINSI_ID');
$reqKabupatenAyahId= $ayah->getField('KABUPATEN_ID');
$reqKabupatenIbuId= $ibu->getField('KABUPATEN_ID');
$reqKecamatanAyahId= $ayah->getField('KECAMATAN_ID');
$reqKecamatanIbuId= $ibu->getField('KECAMATAN_ID');
$reqDesaAyahId= $ayah->getField('DESA_ID');
$reqDesaIbuId= $ibu->getField('DESA_ID');

$reqPropinsiAyah= $ayah->getField('PROPINSI_NAMA');
$reqPropinsiIbu= $ibu->getField('PROPINSI_NAMA');
$reqKabupatenAyah= $ayah->getField('KABUPATEN_NAMA');
$reqKabupatenIbu= $ibu->getField('KABUPATEN_NAMA');
$reqKecamatanAyah= $ayah->getField('KECAMATAN_NAMA');
$reqKecamatanIbu= $ibu->getField('KECAMATAN_NAMA');
$reqDesaAyah= $ayah->getField('DESA_NAMA');
$reqDesaIbu= $ibu->getField('DESA_NAMA');

$reqKodePosAyah= $ayah->getField('KODEPOS');
$reqKodePosIbu= $ibu->getField('KODEPOS');
$reqTeleponAyah= $ayah->getField('TELEPON');
$reqTeleponIbu= $ibu->getField('TELEPON');
$reqStatusAktifAyah= $ayah->getField('STATUS_AKTIF');
$reqStatusAktifIbu= $ibu->getField('STATUS_AKTIF');

if($reqRowId == "")
{
  $reqMode = "insert";
}
else
{
  $reqMode = "update";
}

$arrlistpilihfilefield= [];
if($reqIdAyah > 0)
{
  // untuk kondisi file
  $vfpeg= new globalfilepegawai();
  $arrpilihfiledokumen= $vfpeg->pilihfiledokumen();
  // print_r($arrpilihfiledokumen);exit;

  $riwayattable= "ORANG_TUA";
  $reqDokumenKategoriFileId= "18"; // ambil dari table KATEGORI_FILE, cek sesuai mode
  $arrsetriwayatfield= $vfpeg->setriwayatfield($riwayattable);
  // print_r($arrsetriwayatfield);exit;

  $infodetilparam= [];
  $arrdata= [];
  $arrdata["ID_AYAH"]= $reqIdAyah;
  $arrdata["ID_IBU"]= $reqIdIbu;
  array_push($infodetilparam, $arrdata);
  // print_r($infodetilparam);exit;

  $suratmasukpegawaiid=$paramriwayatfield= "";
  // if(empty($reqRowId))
    $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, "baru", $suratmasukpegawaiid, $paramriwayatfield, $infodetilparam);
  // else
  //   $arrlistriwayatfilepegawai= $vfpeg->listpilihfilepegawai($reqId, $riwayattable, $reqRowId, $suratmasukpegawaiid, $paramriwayatfield, $infodetilparam);

  $arrlistpilihfile= $arrlistriwayatfilepegawai["pilihfile"];
  // print_r($arrlistpilihfile);exit;
  $arrlistriwayat= $arrlistriwayatfilepegawai["riwayat"];

  $arrlistpilihfilefield= [];
  $reqDokumenPilih= [];
  foreach ($arrsetriwayatfield as $key => $value)
  {
    $keymode= $value["riwayatfield"];
    $arrlistpilihfilefield[$keymode]= [];

    if(!empty($arrlistpilihfile))
    {
      $arrlistpilihfilefield[$keymode]= $vfpeg->ambilfilemode($arrlistpilihfile, $keymode, $riwayattable, "", $infodetilparam);

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

<!-- untuk format date baru -->
<script type="text/javascript" src='lib/datepickernew/jquery-1.8.3.min.js'></script>
<script type="text/javascript" src='lib/datepickernew/bootstrap.min.js'></script>
<link rel="stylesheet" href='lib/datepickernew/bootstrap.min.css' media="screen" />
<link rel="stylesheet" href="lib/datepickernew/bootstrap-datepicker.css" type="text/css" />
<script src="lib/datepickernew/bootstrap-datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript"> 
$(function(){
  
  $('#ff').form({
    url:'orang_tua_json/add',
    onSubmit:function(){
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
          document.location.href= "app/loadUrl/app/pegawai_orang_tua/?reqId=<?=$reqId?>";
        }, 1000));
        $(".mbox > .right-align").css({"display": "none"});
      }

    }
  });

  $("#reqPropinsiAyah, #reqKabupatenAyah, #reqKecamatanAyah, #reqDesaAyah, #reqPropinsiIbu, #reqKabupatenIbu, #reqKecamatanIbu, #reqDesaIbu").autocomplete({ 
    source:function(request, response){
      var id= this.element.attr('id');
      var replaceAnakId= replaceAnak= urlAjax= "";
      
      if (id.indexOf('reqPropinsiAyah') !== -1)
      {
        var element= id.split('reqPropinsiAyah');
        var indexId= "reqPropinsiAyahId"+element[1];
        urlAjax= "propinsi_json/combo";
        replaceAnakId= "reqKabupatenAyahId";
        replaceAnak= "reqKabupatenAyah";
        $("#reqKabupatenAyahId, #reqKecamatanAyahId, #reqDesaAyahId").val("");
        $("#reqKabupatenAyah, #reqKecamatanAyah, #reqDesaAyah").val("");
      }
      else if (id.indexOf('reqKabupatenAyah') !== -1)
      {
        var element= id.split('reqKabupatenAyah');
        var indexId= "reqKabupatenAyahId"+element[1];
        var idPropVal= $("#reqPropinsiAyahId").val();
        urlAjax= "kabupaten_json/combo?reqPropinsiId="+idPropVal;
        replaceAnakId= "reqKecamatanAyahId";
        replaceAnak= "reqKecamatanAyah";
        $("#reqKecamatanAyahId, #reqDesaAyahId").val("");
        $("#reqKecamatanAyah, #reqDesaAyah").val("");
      }
      else if (id.indexOf('reqKecamatanAyah') !== -1)
      {
        var element= id.split('reqKecamatanAyah');
        var indexId= "reqKecamatanAyahId"+element[1];
        var idPropVal= $("#reqPropinsiAyahId").val();
        var idKabVal= $("#reqKabupatenAyahId").val();
        urlAjax= "kecamatan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal;
        replaceAnakId= "reqDesaAyahId";
        replaceAnak= "reqDesaAyah";
        $("#reqDesaAyahId").val("");
        $("#reqDesaAyah").val("");
      }
      else if (id.indexOf('reqDesaAyah') !== -1)
      {
        var element= id.split('reqDesaAyah');
        var indexId= "reqDesaAyahId"+element[1];
        var idPropVal= $("#reqPropinsiAyahId").val();
        var idKabVal= $("#reqKabupatenAyahId").val();
        var idKecVal= $("#reqKecamatanAyahId").val();
        urlAjax= "kelurahan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal+"&reqKecamatanId="+idKecVal;
      }
      else if (id.indexOf('reqPropinsiIbu') !== -1)
      {
        var element= id.split('reqPropinsiIbu');
        var indexId= "reqPropinsiIbuId"+element[1];
        urlAjax= "propinsi_json/combo";
        replaceAnakId= "reqKabupatenIbuId";
        replaceAnak= "reqKabupatenIbu";
        $("#reqKabupatenIbuId, #reqKecamatanIbuId, #reqDesaIbuId").val("");
        $("#reqKabupatenIbu, #reqKecamatanIbu, #reqDesaIbu").val("");
      }
      else if (id.indexOf('reqKabupatenIbu') !== -1)
      {
        var element= id.split('reqKabupatenIbu');
        var indexId= "reqKabupatenIbuId"+element[1];
        var idPropVal= $("#reqPropinsiIbuId").val();
        urlAjax= "kabupaten_json/combo?reqPropinsiId="+idPropVal;
        replaceAnakId= "reqKecamatanIbuId";
        replaceAnak= "reqKecamatanIbu";
        $("#reqKecamatanIbuId, #reqDesaIbuId").val("");
        $("#reqKecamatanIbu, #reqDesaIbu").val("");
      }
      else if (id.indexOf('reqKecamatanIbu') !== -1)
      {
        var element= id.split('reqKecamatanIbu');
        var indexId= "reqKecamatanIbuId"+element[1];
        var idPropVal= $("#reqPropinsiIbuId").val();
        var idKabVal= $("#reqKabupatenIbuId").val();
        urlAjax= "kecamatan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal;
        replaceAnakId= "reqDesaIbuId";
        replaceAnak= "reqDesaIbu";
        $("#reqDesaIbuId").val("");
        $("#reqDesaIbu").val("");
      }
      else if (id.indexOf('reqDesaIbu') !== -1)
      {
        var element= id.split('reqDesaIbu');
        var indexId= "reqDesaIbuId"+element[1];
        var idPropVal= $("#reqPropinsiIbuId").val();
        var idKabVal= $("#reqKabupatenIbuId").val();
        var idKecVal= $("#reqKecamatanIbuId").val();
        urlAjax= "kelurahan_json/combo?reqPropinsiId="+idPropVal+"&reqKabupatenId="+idKabVal+"&reqKecamatanId="+idKecVal;
      }
      
      var field= "";
      
      field= "NAMA_ORDER";
      
      $.ajax({
        url: urlAjax,
        type: "GET",
        dataType: "json",
        data: { term: request.term },
        success: function(responseData){
          $("#"+indexId).val("").trigger('change');
          if(replaceAnakId == ""){}
          else
          {
          $("#"+replaceAnakId).val("").trigger('change');
          $("#"+replaceAnak).val("").trigger('change');
          }
          
          if(responseData == null)
          {
            response(null);
          }
          else
          {
            var array = responseData.map(function(element) {
              return {desc: element['desc'], id: element['id'], label: element['label']};
            });
            response(array);
          }
        }
      })
    },
    focus: function (event, ui) 
    { 
      var id= $(this).attr('id');
      var replaceAnakId= replaceAnak= "";
      
      if (id.indexOf('reqPropinsiAyah') !== -1)
      {
        var element= id.split('reqPropinsiAyah');
        var indexId= "reqPropinsiAyahId"+element[1];
        replaceAnakId= "reqKabupatenAyahId";
        replaceAnak= "reqKabupatenAyah";
        $("#reqKabupatenAyahId, #reqKecamatanAyahId, #reqDesaAyahId").val("");
        $("#reqKabupatenAyah, #reqKecamatanAyah, #reqDesaAyah").val("");
      }
      else if (id.indexOf('reqKabupatenAyah') !== -1)
      {
        var element= id.split('reqKabupatenAyah');
        var indexId= "reqKabupatenAyahId"+element[1];
        replaceAnakId= "reqKecamatanAyahId";
        replaceAnak= "reqKecamatanAyah";
        $("#reqKecamatanAyahId, #reqDesaAyahId").val("");
        $("#reqKecamatanAyah, #reqDesaAyah").val("");
      }
      else if (id.indexOf('reqKecamatanAyah') !== -1)
      {
        var element= id.split('reqKecamatanAyah');
        var indexId= "reqKecamatanAyahId"+element[1];
        replaceAnakId= "reqDesaAyahId";
        replaceAnak= "reqDesaAyah";
        $("#reqDesaAyahId").val("");
        $("#reqDesaAyah").val("");
      }
      else if (id.indexOf('reqDesaAyah') !== -1)
      {
        var element= id.split('reqDesaAyah');
        var indexId= "reqDesaAyahId"+element[1];
      }
      else if (id.indexOf('reqPropinsiIbu') !== -1)
      {
        var element= id.split('reqPropinsiIbu');
        var indexId= "reqPropinsiIbuId"+element[1];
        replaceAnakId= "reqKabupatenIbuId";
        replaceAnak= "reqKabupatenIbu";
        $("#reqKabupatenIbuId, #reqKecamatanIbuId, #reqDesaIbuId").val("");
        $("#reqKabupatenIbu, #reqKecamatanIbu, #reqDesaIbu").val("");
      }
      else if (id.indexOf('reqKabupatenIbu') !== -1)
      {
        var element= id.split('reqKabupatenIbu');
        var indexId= "reqKabupatenIbuId"+element[1];
        replaceAnakId= "reqKecamatanIbuId";
        replaceAnak= "reqKecamatanIbu";
        $("#reqKecamatanIbuId, #reqDesaIbuId").val("");
        $("#reqKecamatanIbu, #reqDesaIbu").val("");
      }
      else if (id.indexOf('reqKecamatanIbu') !== -1)
      {
        var element= id.split('reqKecamatanIbu');
        var indexId= "reqKecamatanIbuId"+element[1];
        replaceAnakId= "reqDesaIbuId";
        replaceAnak= "reqDesaIbu";
        $("#reqDesaIbuId").val("");
        $("#reqDesaIbu").val("");
      }
      else if (id.indexOf('reqDesaIbu') !== -1)
      {
        var element= id.split('reqDesaIbu');
        var indexId= "reqDesaIbuId"+element[1];
      }
      
      $("#"+indexId).val(ui.item.id).trigger('change');
    }, 
    //minLength:3,
    autoFocus: true
  }).autocomplete( "instance" )._renderItem = function( ul, item ) {
    return $( "<li>" )
    .append( "<a>" + item.desc + "</a>" )
    .appendTo( ul );
  };


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
  <div id="basic-form" class="section">
    <div class="row">

      <div id='main' class="col s12 m12" style="padding-left: 15px;">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">ORANG TUA</li>
          <li class="collection-item">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="col s12 m6 l6"><h4 class="header2">Ayah</h4></div>
                <div class="col s12 m6 l6"><h4 class="header2">Ibu</h4></div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaAyah">Nama Ayah</label>
                  <input placeholder="" type="text" id="reqNamaAyah" name="reqNamaAyah" value="<?=$reqNamaAyah?>" class="required" title="Nama ayah harus diisi" />
                  <input type="hidden" name="reqIdAyah" value="<?=$reqIdAyah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqNamaIbu">Nama Ibu</label>
                  <input placeholder="" type="text" id="reqNamaIbu" name="reqNamaIbu" value="<?=$reqNamaIbu?>" class="required" title="Nama ibu harus diisi"/>
                  <input type="hidden"  name="reqIdIbu" value="<?=$reqIdIbu?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTmptLahirAyah">Tempat Lahir Ayah</label>
                  <input placeholder="" type="text" name="reqTmptLahirAyah" id="reqTmptLahirAyah" value="<?=$reqTmptLahirAyah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTmptLahirIbu">Tempat Lahir</label>
                  <input placeholder="" type="text" name="reqTmptLahirIbu" id="reqTmptLahirIbu" value="<?=$reqTmptLahirIbu?>" /> 
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTglLahirAyah">Tgl Lahir Ayah</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglLahirAyah" id="reqTglLahirAyah" value="<?=$reqTglLahirAyah?>" maxlength="10" onKeyDown="return format_date(event,'reqTglLahirAyah');"/>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglLahirIbu">Tgl Lahir Ibu</label>
                  <input placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglLahirIbu" id="reqTglLahirIbu" value="<?=$reqTglLahirIbu?>" maxlength="10" onKeyDown="return format_date(event,'reqTglLahirIbu');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqPekerjaanAyah">Pekerjaan Ayah</label>
                  <input placeholder="" type="text" id="reqPekerjaanAyah" name="reqPekerjaanAyah" value="<?=$reqPekerjaanAyah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPekerjaanIbu">Pekerjaan Ibu</label>
                  <input placeholder="" type="text" id="reqPekerjaanIbu" name="reqPekerjaanIbu" value="<?=$reqPekerjaanIbu?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqAlamatAyah">Alamat Ayah</label>
                  <textarea  placeholder="" name="reqAlamatAyah" class="materialize-textarea" id="reqAlamatAyah" value="<?=$reqAlamatAyah?>"><?=$reqAlamatAyah?></textarea>        
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqAlamatIbu">Alamat Ibu</label>
                  <textarea placeholder="" name="reqAlamatIbu" id="reqAlamatIbu" class="materialize-textarea" value="<?=$reqAlamatIbu?>"><?=$reqAlamatIbu?></textarea> 
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqPropinsiAyahId" id="reqPropinsiAyahId" value="<?=$reqPropinsiAyahId?>" /> 
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqPropinsiAyah" 
                  data-options="validType:['sameAutoLoder[\'reqPropinsiAyah\', \'\']']"
                  value="<?=$reqPropinsiAyah?>" />
                  <label for="reqPropinsiAyah">Propinsi Ayah</label>
                </div>
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqPropinsiIbuId" id="reqPropinsiIbuId" value="<?=$reqPropinsiIbuId?>" /> 
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqPropinsiIbu" 
                  data-options="validType:['sameAutoLoder[\'reqPropinsiIbu\', \'\']']"
                  value="<?=$reqPropinsiIbu?>" />
                  <label for="reqPropinsiIbu">Propinsi Ibu</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqKabupatenAyahId" id="reqKabupatenAyahId" value="<?=$reqKabupatenAyahId?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqKabupatenAyah" 
                  data-options="validType:['sameAutoLoder[\'reqKabupatenAyah\', \'\']']"
                  value="<?=$reqKabupatenAyah?>" />
                  <label for="reqKabupatenAyah">Kabupaten Ayah</label>
                </div>
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqKabupatenIbuId" id="reqKabupatenIbuId" value="<?=$reqKabupatenIbuId?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqKabupatenIbu" 
                  data-options="validType:['sameAutoLoder[\'reqKabupatenIbu\', \'\']']"
                  value="<?=$reqKabupatenIbu?>" />
                  <label for="reqKabupatenIbu">Kabupaten Ibu</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqKecamatanAyahId" id="reqKecamatanAyahId" value="<?=$reqKecamatanAyahId?>" />
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqKecamatanAyah" 
                  data-options="validType:['sameAutoLoder[\'reqKecamatanAyah\', \'\']']"
                  value="<?=$reqKecamatanAyah?>" />
                  <label for="reqKecamatanAyah">Kecamatan Ayah</label>
                </div>
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqKecamatanIbuId" id="reqKecamatanIbuId" value="<?=$reqKecamatanIbuId?>" /> 
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqKecamatanIbu" 
                  data-options="validType:['sameAutoLoder[\'reqKecamatanIbu\', \'\']']"
                  value="<?=$reqKecamatanIbu?>" />
                  <label for="reqKecamatanIbu">Kecamatan Ibu</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqDesaAyahId" id="reqDesaAyahId" value="<?=$reqDesaAyahId?>" /> 
                  <input placeholder="" type="text" <?=$disabled?> class="easyui-validatebox" style="width:100%" id="reqDesaAyah" 
                  data-options="validType:['sameAutoLoder[\'reqDesaAyah\', \'\']']"
                  value="<?=$reqDesaAyah?>" />
                  <label for="reqDesaAyah">Desa Ayah</label>
                </div>
                <div class="input-field col s12 m6">
                  <input type="hidden" name="reqDesaIbuId" id="reqDesaIbuId" value="<?=$reqDesaIbuId?>" /> 
                  <input placeholder="" type="text" class="easyui-validatebox" style="width:100%" id="reqDesaIbu" 
                  data-options="validType:['sameAutoLoder[\'reqDesaIbu\', \'\']']"
                  value="<?=$reqDesaIbu?>" />
                  <label for="reqDesaIbu">Desa Ibu</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqKodePosAyah">Kode Pos Ayah</label>
                  <input placeholder="" type="text" id="reqKodePosAyah" name="reqKodePosAyah" value="<?=$reqKodePosAyah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqKodePosIbu">Kode Pos Ibu</label>
                  <input placeholder="" type="text" id="reqKodePosIbu" name="reqKodePosIbu" value="<?=$reqKodePosIbu?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTeleponAyah">Telepon Ayah</label>
                  <input placeholder="" type="text" id="reqTeleponAyah" name="reqTeleponAyah" value="<?=$reqTeleponAyah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTeleponIbu">Telepon Ibu</label>
                  <input placeholder="" type="text" id="reqTeleponIbu" name="reqTeleponIbu" value="<?=$reqTeleponIbu?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_orang_tua_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqPegawaiId" value="<?=$reqId?>" />
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
                </div>
              </div>

              <?
              if($reqIdAyah > 0)
              {
              ?>
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

                  $checkDokumenFileRiwayatField= explode("-", $riwayatfield);
                  $checkDokumenFileRiwayatField= $checkDokumenFileRiwayatField[0];

                  if($checkDokumenFileRiwayatField == "L")
                  {
                    $vidayah= $infodetilparam[0]["ID_AYAH"];
                    $vidibu= $infodetilparam[0]["ID_IBU"];
                    $inforowid= $vidayah;
                  }
                  else if($checkDokumenFileRiwayatField == "P")
                  {
                    $vidayah= $infodetilparam[0]["ID_AYAH"];
                    $vidibu= $infodetilparam[0]["ID_IBU"];
                    $inforowid= $vidibu;
                  }
                  $reqDokumenFileRiwayatId= $inforowid;
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
                    <input type="hidden" id="reqDokumenFileRiwayatId<?=$infonomor?>" name="reqDokumenFileRiwayatId[]" value="<?=$reqDokumenFileRiwayatId?>" />

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

    // untuk format date baru
    $('.formattanggalnew').datepicker({
      format: "dd-mm-yyyy"
    });
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
<style type="text/css">
  .mtmin{
    margin-top: -1px;
  }
</style>

</body>
</html>