<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();


$this->load->model('validasi/Mertua');

$reqId= $this->input->get("reqId");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011003";
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

$statementayah= " AND JENIS_KELAMIN = 'L' ";
$statementibu= " AND JENIS_KELAMIN = 'P' ";


$ayah = new Mertua();
$ibu  = new Mertua();

$infoperubahan= "Perubahan Data";
// if(!empty($reqRowHapusId))
// {
//   $infoperubahan= "Hapus Data";
//   $ayah->selectByPersonal(array(), -1, -1, $reqId, "", $statementayah);
//   $ibu->selectByPersonal(array(), -1, -1, $reqId, "", $statementibu);
// }
// else
  $ayah->selectByPersonal(array(), -1, -1, $reqId, $reqRowId, $statementayah);
  $ibu->selectByPersonal(array(), -1, -1, $reqId, $reqRowId, $statementibu);


// echo $ayah->query;exit;

$ayah->firstRow();

$ibu->firstRow();

// print_r ($ayah->query);exit;
$reqTempValidasiIdAyah= $ayah->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $ayah->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasiAyah= $ayah->getField('VALIDASI');
$reqPerubahanDataAyah= $ayah->getField('PERUBAHAN_DATA');

$reqTempValidasiIdIbu= $ibu->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $ibu->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasiIbu= $ibu->getField('VALIDASI');
$reqPerubahanDataIbu= $ibu->getField('PERUBAHAN_DATA');



$reqRowId= $ayah->getField('MERTUA_ID');

$reqIdAyah       = (int)$ayah->getField("MERTUA_ID");
$reqIdIbu        = (int)$ibu->getField("MERTUA_ID");
$reqNamaAyah     = $ayah->getField('NAMA');$valNamaAyah  = checkwarna($reqPerubahanDataAyah, 'NAMA');
$reqNamaIbu      = $ibu->getField('NAMA');
$reqTmptLahirAyah  = $ayah->getField('TEMPAT_LAHIR');$valTmptLahirAyah = checkwarna($reqPerubahanDataAyah, 'USIA');
$reqTmptLahirIbu   = $ibu->getField('TEMPAT_LAHIR');$valTmptLahirIbu= checkwarna($reqPerubahanDataIbu, 'TEMPAT_LAHIR');
// print_r($valTmptLahirIbu) ;exit;

$reqTglLahirAyah   = dateToPageCheck($ayah->getField('TANGGAL_LAHIR'));
$reqTglLahirIbu    = dateToPageCheck($ibu->getField('TANGGAL_LAHIR'));
$reqUsiaAyah     = $ayah->getField('USIA');$valUsiaAyah = checkwarna($reqPerubahanDataIbu, 'USIA');
$reqUsiaIbu      = $ibu->getField('USIA');
$reqPekerjaanAyah    = $ayah->getField('PEKERJAAN');
$reqPekerjaanIbu   = $ibu->getField('PEKERJAAN');
$reqAlamatAyah     = $ayah->getField('ALAMAT');
$reqAlamatIbu      = $ibu->getField('ALAMAT');
$reqPropinsiAyahId= $ayah->getField('PROPINSI_ID');
$reqPropinsiIbuId= $ibu->getField('PROPINSI_ID');
$reqKabupatenAyahId= $ayah->getField('KABUPATEN_ID');
$reqKabupatenIbuId= $ibu->getField('KABUPATEN_ID');
$reqKecamatanAyahId= $ayah->getField('KECAMATAN_ID');
$reqKecamatanIbuId= $ibu->getField('KECAMATAN_ID');
$reqDesaAyahId= $ayah->getField('DESA_ID');
$reqDesaIbuId= $ibu->getField('DESA_ID');

$reqPropinsiAyah   = $ayah->getField('PROPINSI_NAMA');
$reqPropinsiIbu    = $ibu->getField('PROPINSI_NAMA');
$reqKabupatenAyah    = $ayah->getField('KABUPATEN_NAMA');
$reqKabupatenIbu   = $ibu->getField('KABUPATEN_NAMA');
$reqKecamatanAyah    = $ayah->getField('KECAMATAN_NAMA');
$reqKecamatanIbu   = $ibu->getField('KECAMATAN_NAMA');
$reqDesaAyah     = $ayah->getField('DESA_NAMA');
$reqDesaIbu      = $ibu->getField('DESA_NAMA');

$reqKodePosAyah    = $ayah->getField('KODEPOS');
$reqKodePosIbu     = $ibu->getField('KODEPOS');
$reqTeleponAyah    = $ayah->getField('TELEPON');
$reqTeleponIbu     = $ibu->getField('TELEPON');
$reqStatusAktifAyah  = $ayah->getField('STATUS_AKTIF');
$reqStatusAktifIbu   = $ibu->getField('STATUS_AKTIF');

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
    $('#ff').form({
      url:'validasi/mertua_json/add',
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
              document.location.href= "app/loadUrl/verifikasi/validasi_verifikator/";
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
 <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
  
</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">

      <div class="col s12 m10 offset-m1 ">
       <form id="ff" method="post" enctype="multipart/form-data">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna"><?=$infoperubahan?> MERTUA</li>
          <li class="collection-item">

           <div class="row">
            <div class="col s12 m6 l6">
              <h4 class="header2">Ayah</h4>
            </div>
            <div class="col s12 m6 l6">
              <h4 class="header2">Ibu</h4>
            </div>
          </div>

          <div class="row">

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqNamaAyah" class="<?=$valNamaAyah['warna']?>">
                  Nama Ayah
                  <?
                  if(!empty($valNamaAyah['data']))
                  {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaAyah['data']?></span>
                    </a>
                    <?
                  }
                  ?>
                </label>
                <input type="text" id="reqNamaAyah" name="reqNamaAyah" value="<?=$reqNamaAyah?>" class="required" title="Nama ayah harus diisi" />
                <input type="hidden"  name="reqIdAyah" value="<?=$reqIdAyah?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqNamaIbu" class="<?=$valNamaIbu['warna']?>">Nama Ibu
                <?
                if(!empty($valNamaIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqNamaIbu" name="reqNamaIbu" value="<?=$reqNamaIbu?>" class="required" title="Nama ibu harus diisi"/>
                <input type="hidden"  name="reqIdIbu" value="<?=$reqIdIbu?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqTmptLahirAyah" class="<?=$valTmptLahirAyah['warna']?>">Tempat Lahir Ayah
                <?
                if(!empty($valTmptLahirAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmptLahirAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text"  name="reqTmptLahirAyah" id="reqTmptLahirAyah" value="<?=$reqTmptLahirAyah?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqTmptLahirIbu" class="<?=$valTmptLahirIbu['warna']?>">Tempat Lahir
                <?
                if(!empty($valTmptLahirIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmptLahirIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text"  name="reqTmptLahirIbu" id="reqTmptLahirIbu" value="<?=$reqTmptLahirIbu?>" /> 
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqTglLahirAyah" class="<?=$valTglLahirAyah['warna']?>">Tgl Lahir Ayah
                <?
                if(!empty($valTglLahirAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglLahirAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglLahirAyah" id="reqTglLahirAyah"  value="<?=$reqTglLahirAyah?>" maxlength="10" onKeyDown="return format_date(event,'reqTglLahirAyah');"/>
              </div>
              <div class="input-field col s12 m6">
                <label for="reqTglLahirIbu" class="<?=$valTglLahirIbu['warna']?>">Tgl Lahir Ibu
                <?
                if(!empty($valTglLahirIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglLahirIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglLahirIbu" id="reqTglLahirIbu"  value="<?=$reqTglLahirIbu?>" maxlength="10" onKeyDown="return format_date(event,'reqTglLahirIbu');"/>
              </div>
            </div>


            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqPekerjaanAyah" class="<?=$valPekerjaanAyah['warna']?>">Pekerjaan Ayah
                <?
                if(!empty($valPekerjaanAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPekerjaanAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqPekerjaanAyah" name="reqPekerjaanAyah" value="<?=$reqPekerjaanAyah?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqPekerjaanIbu" class="<?=$valPekerjaanIbu['warna']?>">Pekerjaan Ibu
                <?
                if(!empty($valPekerjaanIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPekerjaanIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqPekerjaanIbu" name="reqPekerjaanIbu" value="<?=$reqPekerjaanIbu?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqAlamatAyah"  class="<?=$valAlamatAyah['warna']?>">Alamat Ayah
                 <?
                if(!empty($valAlamatAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAlamatAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <textarea  name = "reqAlamatAyah" class="materialize-textarea" id="reqAlamatAyah" value="<?=$reqAlamatAyah?>"><?=$reqAlamatAyah?></textarea>        
              </div>
              <div class="input-field col s12 m6">
                <label for="reqAlamatIbu" class="<?=$valAlamatIbu['warna']?>">Alamat Ibu
                <?
                if(!empty($valAlamatIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAlamatIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <textarea name = "reqAlamatIbu" id="reqAlamatIbu" class="materialize-textarea" value="<?=$reqAlamatIbu?>"><?=$reqAlamatIbu?></textarea> 
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqPropinsiAyahId" id="reqPropinsiAyahId" value="<?=$reqPropinsiAyahId?>" /> 
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqPropinsiAyah" 
                data-options="validType:['sameAutoLoder[\'reqPropinsiAyah\', \'\']']"
                value="<?=$reqPropinsiAyah?>" />
                <label for="reqPropinsiAyah" class="<?=$valPropinsiAyah['warna']?>">Propinsi Ayah
                 <?
                if(!empty($valPropinsiAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPropinsiAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqPropinsiIbuId" id="reqPropinsiIbuId" value="<?=$reqPropinsiIbuId?>" /> 
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqPropinsiIbu" 
                data-options="validType:['sameAutoLoder[\'reqPropinsiIbu\', \'\']']"
                value="<?=$reqPropinsiIbu?>" />
                <label for="reqPropinsiIbu" class="<?=$valPropinsiIbu['warna']?>">Propinsi Ibu
                <?
                if(!empty($valPropinsiIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPropinsiIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqKabupatenAyahId" id="reqKabupatenAyahId" value="<?=$reqKabupatenAyahId?>" />
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqKabupatenAyah" 
                data-options="validType:['sameAutoLoder[\'reqKabupatenAyah\', \'\']']"
                value="<?=$reqKabupatenAyah?>" />
                <label for="reqKabupatenAyah" class="<?=$valKabupatenAyah['warna']?>">Kabupaten Ayah
                 <?
                if(!empty($valKabupatenAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKabupatenAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqKabupatenIbuId" id="reqKabupatenIbuId" value="<?=$reqKabupatenIbuId?>" />
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqKabupatenIbu" 
                data-options="validType:['sameAutoLoder[\'reqKabupatenIbu\', \'\']']"
                value="<?=$reqKabupatenIbu?>" />
                <label for="reqKabupatenIbu" class="<?=$valKabupatenIbu['warna']?>">Kabupaten Ibu
                <?
                if(!empty($valKabupatenIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKabupatenIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqKecamatanAyahId" id="reqKecamatanAyahId" value="<?=$reqKecamatanAyahId?>" />
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqKecamatanAyah" 
                data-options="validType:['sameAutoLoder[\'reqKecamatanAyah\', \'\']']"
                value="<?=$reqKecamatanAyah?>" />
                <label for="reqKecamatanAyah" class="<?=$valKecamatanAyah['warna']?>">Kecamatan Ayah
                <?
                if(!empty($valKecamatanAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKecamatanAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqKecamatanIbuId" id="reqKecamatanIbuId" value="<?=$reqKecamatanIbuId?>" /> 
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqKecamatanIbu" 
                data-options="validType:['sameAutoLoder[\'reqKecamatanIbu\', \'\']']"
                value="<?=$reqKecamatanIbu?>" />
                <label for="reqKecamatanIbu" class="<?=$valKecamatanIbu['warna']?>">Kecamatan Ibu
                <?
                if(!empty($valKecamatanIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKecamatanIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqDesaAyahId" id="reqDesaAyahId" value="<?=$reqDesaAyahId?>" /> 
                <input type="text" <?=$disabled?> class="easyui-validatebox" style="width:100%" id="reqDesaAyah" 
                data-options="validType:['sameAutoLoder[\'reqDesaAyah\', \'\']']"
                value="<?=$reqDesaAyah?>" />
                <label for="reqDesaAyah" class="<?=$valDesaAyah['warna']?>">Desa Ayah
                 <?
                if(!empty($valDesaAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valDesaAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
              <div class="input-field col s12 m6">
                <input type="hidden" name="reqDesaIbuId" id="reqDesaIbuId" value="<?=$reqDesaIbuId?>" /> 
                <input type="text" class="easyui-validatebox" style="width:100%" id="reqDesaIbu" 
                data-options="validType:['sameAutoLoder[\'reqDesaIbu\', \'\']']"
                value="<?=$reqDesaIbu?>" />
                <label for="reqDesaIbu" class="<?=$valDesaIbu['warna']?>">Desa Ibu
                <?
                if(!empty($valDesaIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valDesaIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqKodePosAyah" class="<?=$valKodePosAyah['warna']?>">Kode Pos Ayah
                <?
                if(!empty($valKodePosAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKodePosAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqKodePosAyah" name="reqKodePosAyah" value="<?=$reqKodePosAyah?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqKodePosIbu" class="<?=$valKodePosIbu['warna']?>">Kode Pos Ibu
                <?
                if(!empty($valKodePosIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKodePosIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqKodePosIbu" name="reqKodePosIbu" value="<?=$reqKodePosIbu?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqTeleponAyah" class="<?=$valTeleponAyah['warna']?>">Telepon Ayah
                <?
                if(!empty($valTeleponAyah['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTeleponAyah['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqTeleponAyah" name="reqTeleponAyah" value="<?=$reqTeleponAyah?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqTeleponIbu" class="<?=$valTeleponIbu['warna']?>">Telepon Ibu
                <?
                if(!empty($valTeleponIbu['data']))
                {
                  ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTeleponIbu['data']?></span>
                    </a>
                  <?
                }
                ?>
                </label>
                <input type="text" id="reqTeleponIbu" name="reqTeleponIbu" value="<?=$reqTeleponIbu?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m12">
                <select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
                  <option value="" <? if($reqStatusSkCpns == "") echo 'selected';?>></option>
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
            </div>

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


                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
                <input type="hidden" name="reqTempValidasiIdAyah" value="<?=$reqTempValidasiIdAyah?>" />
                <input type="hidden" name="reqTempValidasiIdIbu" value="<?=$reqTempValidasiIdIbu?>" />
                <input type="hidden" name="reqTempValidasiHapusId" value="<?=$reqTempValidasiHapusId?>" />
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

          </li>
        </ul>
      </form>
    </div>






  </div>
  <!-- jQuery Library -->

  <!--materialize js-->
  <script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('select').material_select();
    });

    $('.materialize-textarea').trigger('autoresize');

  </script>
  
  <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
  <script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>