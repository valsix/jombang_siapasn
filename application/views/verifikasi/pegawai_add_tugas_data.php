<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/JabatanTambahan');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010402";
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

$arrstatusplt= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"")
  , array("id"=>"plt", "text"=>"Plt.")
  , array("id"=>"plh", "text"=>"Plh.")
  , array("id"=>"21", "text"=>"Pendidikan")
  , array("id"=>"22", "text"=>"Kesehatan")
  , array("id"=>"23", "text"=>"Lainnya")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatusplt, $arrdata);
}

$statement= "";
$set= new JabatanTambahan();

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

$reqValRowId= $set->getField('JABATAN_TAMBAHAN_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNamaTugas= $set->getField('NAMA');$valNamaTugas= checkwarna($reqPerubahanData, 'NAMA');
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqNoSk= $set->getField('NO_SK');$valNoSk= checkwarna($reqPerubahanData, 'NO_SK');
$reqTanggalSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));$valTanggalSk= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTmtTugas= dateTimeToPageCheck($set->getField('TMT_JABATAN'));$valTmtTugas= checkwarna($reqPerubahanData, 'TMT_JABATAN', "date");
$reqTmtJabatanAkhir= dateTimeToPageCheck($set->getField('TMT_JABATAN_AKHIR'));$valTmtJabatanAkhir= checkwarna($reqPerubahanData, 'TMT_JABATAN_AKHIR', "date");
$reqTugasTambahanId= $set->getField('TUGAS_TAMBAHAN_ID');
$reqStatusPlt= $set->getField('STATUS_PLT');$valStatusPlt= checkwarna($reqPerubahanData, 'STATUS_PLT', $arrstatusplt, array("id", "text"));
$reqSatker= $set->getField('SATKER_NAMA');$valSatker= checkwarna($reqPerubahanData, 'SATKER_NAMA');
$reqSatkerId= $set->getField('SATKER_ID');
$reqIsManual= $set->getField('IS_MANUAL');$valIsManual= checkwarna($reqPerubahanData, 'IS_MANUAL');
$reqNoPelantikan= $set->getField('NO_PELANTIKAN');$valNoPelantika= checkwarna($reqPerubahanData, 'NO_PELANTIKAN');
$reqTanggalPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));$valTanggalPelantikan= checkwarna($reqPerubahanData, 'TANGGAL_PELANTIKAN', "date");
$reqTunjangan= $set->getField('TUNJANGAN');$valTunjangan= checkwarna($reqPerubahanData, 'TUNJANGAN');
$reqBulanDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));$valBulanDibayar= checkwarna($reqPerubahanData, 'BULAN_DIBAYAR');
$reqTmtWaktuTugas= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);$valTmtWaktuTugas= checkwarna($reqPerubahanData, 'TMT_JABATAN', "date");
if($reqTmtWaktuTugas == "" || $reqTmtWaktuTugas == "00:00"){}
  else
    $reqCheckTmtWaktuTugas= "1";
  
  if($reqTmtJabatanAkhir == ""){}
    else
      $reqIsAktif= "1";

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
    function settimetmt(info)
	{
		$("#reqInfoCheckTmtWaktuTugas").hide();
		if($("#reqCheckTmtWaktuTugas").prop('checked')) 
		{
			$("#reqInfoCheckTmtWaktuTugas").show();
		}
		else
		{
			if(info == 2)
			$("#reqTmtWaktuTugas").val("");
		}
	}
	
	function setcetang()
	{

		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);

		}
		else
		{
			$("#reqNamaTugas, #reqTugasTambahanId, #reqSatker, #reqSatkerId").val("");

		}
	}
	
	function seinfodatacentang()
	{
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqSatker").attr("readonly", false);
		}
		else
		{
		}
	}
	
	function setaktif()
	{
		if($("#reqIsAktif").prop('checked')) 
		{
		  $("#reqInfoCheckTmtSelesai").show();
		}
		else
		{
		  $("#reqInfoCheckTmtSelesai").hide();
		  $("#reqTmtJabatanAkhir").val('');
		}
	}
	  
	$(function(){
      settimetmt(1);
	  <?
	  if($reqRowId == "")
	  {
	  ?>
	  setcetang();
	  <?
	  }
	  else
	  {
	  ?>
	  seinfodatacentang();
	  <?
	  }
	  ?>
	  $('#ff').form({
        url:'validasi/jabatan_tambahan_json/add',
        onSubmit:function(){
          var reqStatusPlt= $("#reqStatusPlt").val();

          if(reqStatusPlt == "")
          {
            mbox.alert("Lengkapi data Jenis Tugas terlebih dahulu", {open_speed: 0});
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

      $("#reqCheckTmtWaktuTugas").click(function () {
		 settimetmt(2);
	  });
	 
	  $("#reqIsManual").click(function () {
        setcetang();
      });

      setaktif();
	  $("#reqIsAktif").click(function () {
        setaktif();
      });
	  
	  $("#reqStatusPlt").change(function() { 
		$("#reqNamaTugas,#reqTugasTambahanId,#reqSatker,#reqSatkerId").val("");
	  });

	  $('input[id^="reqPejabatPenetap"], input[id^="reqNamaTugas"], input[id^="reqSatker"]').each(function(){
  		$(this).autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";
		  
          if (id.indexOf('reqNamaTugas') !== -1 || id.indexOf('reqSatker') !== -1)
          {
           if($("#reqIsManual").prop('checked')) 
           {
            $("#reqSatker").attr("readonly", false);
            return false;
          }
        }

        if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
        else if (id.indexOf('reqNamaTugas') !== -1)
        {
         var reqStatusPlt= $("#reqStatusPlt").val();
         var element= id.split('reqNamaTugas');
         reqTanggalBatas= $("#reqTanggalSk").val();
         urlAjax= "jabatan_tambahan_json/namajabatan?reqTanggalBatas="+reqTanggalBatas+"&reqStatusPlt="+reqStatusPlt;
       }
       else if (id.indexOf('reqSatker') !== -1)
       {
        var element= id.split('reqSatker');
        var indexId= "reqSatkerId"+element[1];
        reqTanggalBatas= $("#reqTmtTugas").val();
        urlAjax= "satuan_kerja_json/auto?reqTanggalBatas="+reqTanggalBatas;
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
              return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], satuan_kerja_id: element['satuan_kerja_id'], satuan_kerja_validasi: element['satuan_kerja_validasi']};
            });
            response(array);
          }
        }
      })
    },
    focus: function (event, ui) 
    { 
      var id= $(this).attr('id');
      if (id.indexOf('reqPejabatPenetap') !== -1)
      {
        var element= id.split('reqPejabatPenetap');
        var indexId= "reqPejabatPenetapId"+element[1];
      }
      else if (id.indexOf('reqNamaTugas') !== -1)
      {
        var element= id.split('reqNamaTugas');
        var indexId= "reqTugasTambahanId";
      }
      else if (id.indexOf('reqSatker') !== -1)
      {
        var element= id.split('reqSatker');
        var indexId= "reqSatkerId"+element[1];
            //$("#reqNama").val("").trigger('change');
          }

          var statusht= "";
            //statusht= ui.item.statusht;
            $("#"+indexId).val(ui.item.id).trigger('change');
            if (id.indexOf('reqNamaTugas') !== -1)
            {
              var reqStatusPlt= $("#reqStatusPlt").val();

              $("#reqSatker").attr("readonly", true);
              if(reqStatusPlt == "plt"){}
                else
                {
                 if(ui.item.satuan_kerja_validasi == "1")
                 {
                  $("#reqSatker").attr("readonly", false);
                }
              }

              $("#reqSatkerId").val(ui.item.satuan_kerja_id).trigger('change');
              $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
            }

          },
          autoFocus: true
        })

		.autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc  + "</a>" )
        .appendTo( ul );
      }
	  ;
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

 <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> TUGAS</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s6 m6">
                  <select <?=$disabled?> name="reqStatusPlt">
                    <?
                    foreach($arrstatusplt as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqStatusPlt == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label class="<?=$valStatusPlt['warna']?>">Jenis Tugas</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSk" class="<?=$valNoSk['warna']?>">
                    No. SK
                    <?
                    if(!empty($valNoSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox"  id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSk" class="<?=$valNoSk['warna']?>">
                    Tgl. SK
                    <?
                    if(!empty($valTanggalSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSk" id="reqTanggalSk"  value="<?=$reqTanggalSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSk');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                  <label for="reqIsManual"></label>
                  *centang jika nama Tugas luar kab jombang / Tugas sebelum tahun 2012
                </div>
              </div>
              <br>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaTugas" class="<?=$valNamaTugas['warna']?>">
                    Nama Tugas
                    <?
                    if(!empty($valNamaTugas['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaTugas['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox"  id="reqNamaTugas" name="reqNamaTugas" <?=$disabled?> value="<?=$reqNamaTugas?>" />
                  <input type="hidden" name="reqTugasTambahanId" id="reqTugasTambahanId" value="<?=$reqTugasTambahanId?>" />
                </div>
                <div class="input-field col s12 m2">
                  <label for="reqTmtTugas" class="<?=$valTmtTugas['warna']?>">
                    TMT Tugas
                    <?
                    if(!empty($valTmtTugas['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtTugas['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtTugas" id="reqTmtTugas"  value="<?=$reqTmtTugas?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtTugas');"/>
                </div>
                <div class="input-field col s12 m1">
                  <input type="checkbox" id="reqCheckTmtWaktuTugas" name="reqCheckTmtWaktuTugas" value="1" <? if($reqCheckTmtWaktuTugas == 1) echo 'checked'?>/>
                  <label for="reqCheckTmtWaktuTugas"></label>
                </div>
                <div class="input-field col s12 m1" id="reqInfoCheckTmtWaktuTugas">
                	<input placeholder="00:00" id="reqTmtWaktuTugas" name="reqTmtWaktuTugas" type="text" class="" value="<?=$reqTmtWaktuTugas?>" />
                    <label for="reqTmtWaktuTugas">Time</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatker" class="<?=$valSatker['warna']?>">
                    Satuan Kerja
                    <?
                    if(!empty($valSatker['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valSatker['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqSatker" name="reqSatker" <?=$read?> <?php /*?>readonly<?php */?> value="<?=$reqSatker?>" class="easyui-validatebox" required />
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoPelantikan" class="<?=$valNoPelantikan['warna']?>">
                    No. Pelantikan
                    <?
                    if(!empty($valNoPelantikan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoPelantikan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox"  id="reqNoPelantikan" name="reqNoPelantikan" <?=$disabled?> value="<?=$reqNoPelantikan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalPelantikan" class="<?=$valTanggalPelantikan['warna']?>">
                    Tgl. Pelantikan
                    <?
                    if(!empty($valTanggalPelantikan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalPelantikan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPelantikan" id="reqTanggalPelantikan"  value="<?=$reqTanggalPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPelantikan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTunjangan" class="<?=$valTunjangan['warna']?>">
                    Tunjangan
                    <?
                    if(!empty($valTunjangan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTunjangan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqBlnDibayar" class="<?=$valBlnDibayar['warna']?>">
                    Bln. Dibayar
                    <?
                    if(!empty($valBlnDibayar['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBlnDibayar['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBulanDibayar" id="reqBulanDibayar"  value="<?=$reqBulanDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBulanDibayar');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqBlnDibayar" class="<?=$valBlnDibayar['warna']?>">
                    Bln. Dibayar
                    <?
                    if(!empty($valBlnDibayar['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBlnDibayar['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqPejabatPenetapId" id="reqPejabatPenetapId" value="<?=$reqPejabatPenetapId?>" /> 
                  <input type="text" id="reqPejabatPenetap"  name="reqPejabatPenetap" <?=$read?> value="<?=$reqPejabatPenetap?>" class="easyui-validatebox" required />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqIsAktif" name="reqIsAktif" value="1" <? if($reqIsAktif == 1) echo 'checked'?> />
                  <label for="reqIsAktif"></label>
                  *centang jika sudah tidak aktif
                </div>
                <div class="input-field col s12 m2" id="reqInfoCheckTmtSelesai">
                  <label for="reqTmtJabatanAkhir" class="<?=$valTmtJabatanAkhir['warna']?>">
                    TMT Selesai Tugas
                    <?
                    if(!empty($valTmtJabatanAkhir['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtJabatanAkhir['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatanAkhir" id="reqTmtJabatanAkhir"  value="<?=$reqTmtJabatanAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatanAkhir');"/>
                </div>
              </div>
              <br>

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
              
            </form>
          </li>
        </ul>
      </div>
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
  
  $('#reqKredit').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>