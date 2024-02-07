<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Anak');
$this->load->model('Pendidikan');
$this->load->model('Pensiun');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011002";
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

$statement= " AND JENIS_ID = 7 AND A.PEGAWAI_ID = ".$reqId;
$set= new Pensiun();
$tempJumlah= $set->getCountByParamsSuratMasukPegawai(array(), $statement);
// $tempJumlah= 0;

$arrPendidikan= [];
$set= new pendidikan();
$set->selectByParams(array());
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PENDIDIKAN_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arrPendidikan, $arrdata);
}

$arrstatuskeluarga= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Kandung")
  , array("id"=>"2", "text"=>"Tiri")
  , array("id"=>"3", "text"=>"Angkat")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatuskeluarga, $arrdata);
}

$arrJenisKelamin= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"L", "text"=>"L")
  , array("id"=>"P", "text"=>"P")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrJenisKelamin, $arrdata);
}

$arrstatusaktif= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Aktif")
  , array("id"=>"2", "text"=>"Meninggal")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusaktif, $arrdata);
}

$arrstatusmenikah= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Sudah")
  , array("id"=>"", "text"=>"Belum")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusmenikah, $arrdata);
}

$arrstatusbekerja= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Sudah")
  , array("id"=>"", "text"=>"Belum")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusbekerja, $arrdata);
}

$statement= "";
$set= new Anak();

$infoperubahan= "Perubahan Data";
if(!empty($reqRowHapusId))
{
  $infoperubahan= "Hapus Data";
  $set->selectByParamsPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
}
else
  $set->selectByParamsPersonal(array(), -1, -1, $reqId, $reqRowHapusId, $reqRowId, $statement);

// echo $set->query;exit;
$set->firstRow();
$reqTempValidasiId= $set->getField('TEMP_VALIDASI_ID');
$reqTempValidasiHapusId= $set->getField('TEMP_VALIDASI_HAPUS_ID');
$reqValidasi= $set->getField('VALIDASI');
$reqPerubahanData= $set->getField('PERUBAHAN_DATA');

$reqValRowId= $set->getField('ANAK_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqStatusKeluarga= $set->getField("STATUS_KELUARGA");$valJenisBahasa= checkwarna($reqPerubahanData, 'STATUS_KELUARGA', $arrstatuskeluarga, array("id", "text"), $reqTempValidasiHapusId);
$reqNama = $set->getField('NAMA');$valNama= checkwarna($reqPerubahanData, 'NAMA', "", "", $reqTempValidasiHapusId);
$reqTempatLahir= $set->getField('TEMPAT_LAHIR');$valTempatLahir= checkwarna($reqPerubahanData, 'TEMPAT_LAHIR', "", "", $reqTempValidasiHapusId);
$reqTanggalLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));$valTanggalLahir= checkwarna($reqPerubahanData, 'TANGGAL_LAHIR', "date");
$reqJenisKelamin= $set->getField('JENIS_KELAMIN');$valJenisKelamin= checkwarna($reqPerubahanData, 'JENIS_KELAMIN', $arrJenisKelamin, array("id", "text"), $reqTempValidasiHapusId);
$reqStatusKeluarga= $set->getField('STATUS_KELUARGA');$valStatusKeluarga=checkwarna($reqPerubahanData, 'STATUS_KELUARGA', $arrstatuskeluarga, array("id", "text"), $reqTempValidasiHapusId);
$reqStatusAktif= $set->getField('STATUS_AKTIF');$valStatusAktif= checkwarna($reqPerubahanData, 'STATUS_AKTIF', $arrstatusaktif, array("id", "text"), $reqTempValidasiHapusId);
$reqStatusNikah= $set->getField('STATUS_NIKAH');$valStatusNikah= checkwarna($reqPerubahanData, 'STATUS_NIKAH', $arrstatusmenikah, array("id", "text"), $reqTempValidasiHapusId);
$reqStatusBekerja= $set->getField('STATUS_BEKERJA');$valStatusBekerja= checkwarna($reqPerubahanData, 'STATUS_BEKERJA', $arrstatusbekerja, array("id", "text"), $reqTempValidasiHapusId);
$reqDapatTunjangan= $set->getField('STATUS_TUNJANGAN');$valDapatTunjangan= checkwarna($reqPerubahanData, 'STATUS_TUNJANGAN');
$reqPendidikanId= $set->getField('PENDIDIKAN_ID');$valPendidikan= checkwarna($reqPerubahanData, 'PENDIDIKAN_ID', $arrPendidikan, array("id", "text"), $reqTempValidasiHapusId);
$reqPekerjaan= $set->getField('PEKERJAAN');$valPekerjaan= checkwarna($reqPerubahanData, 'PEKERJAAN', "", "", $reqTempValidasiHapusId);
$reqAwalBayar= dateToPageCheck($set->getField('AWAL_BAYAR'));$valAwalBayar= checkwarna($reqPerubahanData, 'AWAL_BAYAR', "date");
$reqAkhirBayar= dateToPageCheck($set->getField('AKHIR_BAYAR'));$valAkhirBayar= checkwarna($reqPerubahanData, 'AKHIR_BAYAR', "date");
$reqSuamiIstriId= $set->getField('SUAMI_ISTRI_ID');$valSuamiIstri= checkwarna($reqPerubahanData, 'SUAMI_ISTRI_ID', $arrSuamiIstri, array("id", "text"), $reqTempValidasiHapusId);
$reqSuamiIstri= $set->getField('SUAMI_ISTRI_NAMA');
$reqNoInduk= $set->getField('NOMOR_INDUK');$valNoInduk= checkwarna($reqPerubahanData, 'NOMOR_INDUK');
$reqTanggalMeninggal= dateToPageCheck($set->getField('TANGGAL_MENINGGAL'));$valTanggalMeninggal= checkwarna($reqPerubahanData, 'TANGGAL_MENINGGAL', "date");
$reqPensiunAnakId= $set->getField('PENSIUN_ANAK_ID');



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
        url:'validasi/anak_json/add',
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

    
	$('input[id^="reqSuamiIstri"]').each(function(){
  		$(this).autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";
		  
		  if (id.indexOf('reqSuamiIstri') !== -1)
          {
            var element= id.split('reqSuamiIstri');
            var indexId= "reqSuamiIstriId"+element[1];
            urlAjax= "suami_istri_json/combo?reqId=<?=$reqId?>";
          }

          $.ajax({
            url: urlAjax,
            type: "GET",
            dataType: "json",
            data: { term: request.term },
            success: function(responseData){
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
          if (id.indexOf('reqSuamiIstri') !== -1)
          {
            var element= id.split('reqSuamiIstri');
            var indexId= "reqSuamiIstriId"+element[1];
          }

          	$("#"+indexId).val(ui.item.id).trigger('change');
          },
          autoFocus: true
        })
		.autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
      	};
	  });
	  
	  setstatusaktif();
	  $("#reqStatusAktif").change(function() { 
	  	setstatusaktif();
	  });
	  
	});
	
	function setstatusaktif()
	{
		var reqStatusAktif= $("#reqStatusAktif").val();
		$("#reqLabelTanggalMeninggal").hide();
		if(reqStatusAktif == "2")
		{
			$("#reqLabelTanggalMeninggal").show();
		}
		else
		{
			$("#reqTanggalMeninggal").val("");
		}
	}
	//reqLabelTanggalMeninggal;reqTanggalMeninggal;reqStatusAktif;
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
      <div class="col s12 m10 offset-m1">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna"><?=$infoperubahan?> ANAK</li>
          <li class="collection-item">
            <div class="row">

             <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="col s12 m12">

                 <div class="row">
                  <div class="input-field col s12 m12">
                   <label for="reqNama" class="<?=$valNama['warna']?>">
                    Nama
                    <?
                    if(!empty($valNama['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNama['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                    </label>
                    <input type="text" class="easyui-validatebox" required name="reqNama" id="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" /></td>
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m4">
                    <select <?=$disabled?> name="reqStatusKeluarga">
                      <?
                      foreach($arrstatuskeluarga as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusKeluarga == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusKeluarga" class="<?=$valStatusKeluarga['warna']?>">
                      Status Keluarga
                      <?
                      if(!empty($valStatusKeluarga['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusKeluarga['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                  </div>
                  <div class="input-field col s12 m8">
                    <label for="reqSuamiIstri" class="<?=$valSuamiIstri['warna']?>">
                      Nama Bapak / Ibu
                      <?
                      if(!empty($valSuamiIstri['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSuamiIstri['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input type="hidden" name="reqSuamiIstriId" id="reqSuamiIstriId" value="<?=$reqSuamiIstriId?>" />
                    <input type="text" id="reqSuamiIstri" name="reqSuamiIstri" <?=$read?> value="<?=$reqSuamiIstri?>" class="easyui-validatebox" />
                  </div>
                </div>
                
                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqTempatLahir" class="<?=$valTempatLahir['warna']?>">
                      Tmp. Lahir
                      <?
                      if(!empty($valTempatLahir['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTempatLahir['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input type="text" class="easyui-validatebox" required name="reqTempatLahir" id="reqTempatLahir" <?=$read?> value="<?=$reqTempatLahir?>" title="Tempat lahir harus diisi" />
                  </div>
                  <div class="input-field col s12 m2">
                    <label for="reqTanggalLahir" class="<?=$valTanggalLahir['warna']?>">
                      Tgl. Lahir
                      <?
                      if(!empty($valTanggalLahir['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalLahir['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalLahir" id="reqTanggalLahir"  value="<?=$reqTanggalLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalLahir');"/>
                  </div>
                  <div class="input-field col s12 m2">
                    <select <?=$disabled?> name="reqJenisKelamin">
                      <?
                      foreach($arrJenisKelamin as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqJenisKelamin == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqJenisKelamin" class="<?=$valJenisKelamin['warna']?>">
                      L/P
                      <?
                      if(!empty($valJenisKelamin['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisKelamin['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m10">
                    <label for="reqNoInduk" class="<?=$valNoInduk['warna']?>">
                      NIK
                      <?
                      if(!empty($valNoInduk['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoInduk['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input name="reqNoInduk" class="easyui-validatebox" id="reqNoInduk"  type="text"  value="<?=$reqNoInduk?>" />    
                  </div>
                  <div class="input-field col s12 m2">
                    <a class="btn-floating btn-small waves-effect waves-light green"><i class="mdi-file-attachment"></i></a>
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m6">
                    <select <?=$disabled?> name="reqPendidikanId">
                      <?
                      foreach($arrPendidikan as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqPendidikanId == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqPendidikanId" class="<?=$valPendidikan['warna']?>">
                      Pendidikan
                      <?
                      if(!empty($valPendidikan['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPendidikan['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqPekerjaan" class="<?=$valPekerjaan['warna']?>">
                      Pekerjaan
                      <?
                      if(!empty($valPekerjaan['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPekerjaan['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input type="text" <?=$read?> name="reqPekerjaan" id="reqPekerjaan" value="<?=$reqPekerjaan?>" />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m4">
                    <input type="checkbox" id="reqDapatTunjangan" name="reqDapatTunjangan" value="1" <? if($reqDapatTunjangan == 1) echo 'checked'?> />
                    <label for="reqDapatTunjangan">Tunjangan</label>
                  </div>
                  <div class="input-field col s12 m2">
                    <label for="reqAwalBayar" class="<?=$valAwalBayar['warna']?>">
                      Mulai Dibayar
                      <?
                      if(!empty($valAwalBayar['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAwalBayar['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqAwalBayar" id="reqAwalBayar"  value="<?=$reqAwalBayar?>" maxlength="10" onKeyDown="return format_date(event,'reqAwalBayar');"/>
                  </div>
                  <div class="input-field col s12 m2">
                    <label for="reqAkhirBayar" class="<?=$valAkhirBayar['warna']?>">
                      Akhir Dibayar
                      <?
                      if(!empty($valAkhirBayar['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAkhirBayar['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqAkhirBayar" id="reqAkhirBayar"  value="<?=$reqAkhirBayar?>" maxlength="10" onKeyDown="return format_date(event,'reqAkhirBayar');"/>
                  </div>
                </div>

                <div class="row">

                  <div class="input-field col s12 m2">
                    <select <?=$disabled?> name="reqStatusAktif">
                      <?
                      foreach($arrstatusaktif as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusAktif == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusAktif" class="<?=$valStatusAktif['warna']?>">
                      Status Aktif
                      <?
                      if(!empty($valStatusAktif['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusAktif['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                  </div>
                  
                  <div class="input-field col s12 m2" id="reqLabelTanggalMeninggal">
                    <label for="reqTanggalMeninggal" class="<?=$valTanggalMeninggal['warna']?>">
                      Tgl Meninggal
                      <?
                      if(!empty($valTanggalMeninggal['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalMeninggal['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                    <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMeninggal" id="reqTanggalMeninggal"  value="<?=$reqTanggalMeninggal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMeninggal');"/>
                  </div>
                  
                  <div class="input-field col s12 m2">
                    <select <?=$disabled?> name="reqStatusNikah">
                      <?
                      foreach($arrstatusmenikah as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusNikah == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusNikah" class="<?=$valStatusNikah['warna']?>">
                      Status Menikah
                      <?
                      if(!empty($valStatusNikah['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusNikah['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
                  </div>

                  <div class="input-field col s12 m2">
                    <select <?=$disabled?> name="reqStatusBekerja">
                      <?
                      foreach($arrstatusbekerja as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusBekerja == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusBekerja" class="<?=$valStatusBekerja['warna']?>">
                      Status Bekerja
                      <?
                      if(!empty($valStatusBekerja['data']))
                      {
                        ?>
                        <a class="tooltipe" href="javascript:void(0)">
                          <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusBekerja['data']?></span>
                        </a>
                        <?
                      }
                      ?>
                    </label>
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

                    <?
                    if($tempJumlah == 0)
                    {
                    if($reqPensiunAnakId == "")
                    {
                    ?>
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
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
                    <?
                    }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>
      </li>
    </ul>

  </div>
</div>
</div>
<!-- jQuery Library -->
<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->

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