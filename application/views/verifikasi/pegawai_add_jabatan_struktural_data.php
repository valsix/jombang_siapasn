<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/JabatanRiwayat');
$this->load->model('Eselon');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisJabatan= $this->input->get("reqJenisJabatan");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010401";
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

$arrjenisjabatan= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"")
  , array("id"=>"1", "text"=>"Jabatan Struktural")
  , array("id"=>"2", "text"=>"Jabatan Fungsional Umum")
  , array("id"=>"3", "text"=>"Jabatan Fungsional Tertentu")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjenisjabatan, $arrdata);
}


$arreselon= [];
$set= new Eselon();
$set->selectByParams(array());
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("ESELON_ID");
  $arrdata["text"]= $set->getField("NAMA");
  array_push($arreselon, $arrdata);
}

$statement= "";
$set= new JabatanRiwayat();

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

$reqValRowId= $set->getField('JABATAN_RIWAYAT_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqIsManual= $set->getField('IS_MANUAL');$valIsManual= checkwarna($reqPerubahanData, 'IS_MANUAL');
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqSatkerId= $set->getField('SATKER_ID');
$reqNoSk= $set->getField('NO_SK');$valNoSk= checkwarna($reqPerubahanData, 'NO_SK');
$reqEselonId= $set->getField('ESELON_ID');
$reqJabatanFuId= $set->getField('JABATAN_FU_ID');
$reqJabatanFtId= $set->getField('JABATAN_FT_ID');
$reqNama= $set->getField('NAMA');$valNama= checkwarna($reqPerubahanData, 'NAMA');
$reqSatkerId= $set->getField('SATKER_ID');
$reqSatker= $set->getField('SATUAN_KERJA_NAMA_DETIL');$valSatker= checkwarna($reqPerubahanData, 'SATUAN_KERJA_NAMA_DETIL');
$reqNoPelantikan= $set->getField('NO_PELANTIKAN');$valNoPelantikan= checkwarna($reqPerubahanData, 'NO_PELANTIKAN');
$reqTunjangan= $set->getField('TUNJANGAN');$valTunjangan= checkwarna($reqPerubahanData, 'TUNJANGAN');
$reqTglSk= dateTimeToPageCheck($set->getField('TANGGAL_SK'));$valTglSk= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTmtJabatan= dateTimeToPageCheck($set->getField('TMT_JABATAN'));$valTmtJabatan= checkwarna($reqPerubahanData, 'TMT_JABATAN', "date");
$reqTglPelantikan= dateToPageCheck($set->getField('TANGGAL_PELANTIKAN'));$valTglPelantikan= checkwarna($reqPerubahanData, 'TANGGAL_PELANTIKAN', "date");
$reqBlnDibayar= dateToPageCheck($set->getField('BULAN_DIBAYAR'));$valBlnDibayar= checkwarna($reqPerubahanData, 'BULAN_DIBAYAR');
$reqKeteranganBUP= $set->getField('KETERANGAN_BUP');$valKeteranganBUP= checkwarna($reqPerubahanData, 'KETERANGAN_BUP');
$reqTmtEselon= dateTimeToPageCheck($set->getField('TMT_ESELON'));$valTmtEselon= checkwarna($reqPerubahanData, 'TMT_ESELON', "date");


$reqTmtWaktuJabatan= substr(datetimeToPage($set->getField('TMT_JABATAN'), "time"),0,5);
  if($reqTmtWaktuJabatan == "" || $reqTmtWaktuJabatan == "00:00"){}
  else
  $reqCheckTmtWaktuJabatan= "1";

  $reqJenisJabatan= 1;
  $reqTipePegawaiId= 11;

  if($reqJabatanFuId !== "" || $reqJabatanFtId !== "")
  {
    if($reqTipePegawaiId == "11" && $reqSatkerId !== ""){}
    else
    $reqNama= "";
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
    function settimetmt(info)
	{
		$("#reqInfoCheckTmtWaktuJabatan").hide();
		if($("#reqCheckTmtWaktuJabatan").prop('checked')) 
		{
			$("#reqInfoCheckTmtWaktuJabatan").show();
		}
		else
		{
			if(info == 2)
			$("#reqTmtWaktuJabatan").val("");
		}
	}
	
    function seinfodatacentang()
	{
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqinfoeselontext").show();
		}
	}
	
    function setcetang()
	{
		//alert($("#reqIsManual").prop('checked'));return false;
		$("#reqinfoeselontext,#reqinfoeselonselect").hide();
		if($("#reqIsManual").prop('checked')) 
		{
			$("#reqinfoeselonselect").show();
			$("#reqSelectEselonId,#reqEselonId, #reqEselonText, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqSatker").attr("readonly", false);
			$("#reqSelectEselonId").material_select();
			//$("#reqNama,#reqNamaId").val("");
		}
		else
		{
			$("#reqSatker").attr("readonly", true);
			$("#reqEselonId, #reqNama, #reqSatker, #reqSatkerId").val("");
			$("#reqinfoeselontext").show();
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
        url:'validasi/jabatan_riwayat_json/add',
        onSubmit:function(){
          var reqEselonId= "";
          reqEselonId= $("#reqEselonId").val();
          if(reqEselonId == "")
          {
            $.messager.alert('Info', "Lengkapi data Eselon terlebih dahulu", 'info');
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

     $("#reqCheckTmtWaktuJabatan").click(function () {
  		 settimetmt(2);
  	 });
	 
	 $("#reqIsManual").click(function () {
		setcetang();
	 });
	 
	 $('#reqSelectEselonId').bind('change', function(ev) {
       $("#reqEselonId").val($(this).val());
     });
	 
	 //$('input[id^="reqPejabatPenetap"], input[id^="reqNama"], input[id^="reqSatker"]').autocomplete({
	 $('input[id^="reqPejabatPenetap"], input[id^="reqNama"]').autocomplete({
      source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqNama') !== -1 || id.indexOf('reqSatker') !== -1)
        {
			if($("#reqIsManual").prop('checked')) 
			{
				return false;
			}
		}
		
		if (id.indexOf('reqPejabatPenetap') !== -1)
        {
          var element= id.split('reqPejabatPenetap');
          var indexId= "reqPejabatPenetapId"+element[1];
          urlAjax= "pejabat_penetap_json/combo";
        }
		else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqNamaId"+element[1];
          reqTanggalBatas= $("#reqTglSk").val();
          urlAjax= "satuan_kerja_json/namajabatan?reqTanggalBatas="+reqTanggalBatas+"&reqTipeJabatanId=x2";
        }
		else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
          var indexId= "reqSatkerId"+element[1];
          reqTanggalBatas= $("#reqTmtJabatan").val();
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
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
		else if (id.indexOf('reqNama') !== -1)
        {
          var element= id.split('reqNama');
          var indexId= "reqSatkerId"+element[1];
		  $("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  $("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  $("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
		else if (id.indexOf('reqSatker') !== -1)
        {
          var element= id.split('reqSatker');
		  var indexId= "reqSatkerId"+element[1];
		  $("#reqNama").val("").trigger('change');
        }

        var statusht= "";
            //statusht= ui.item.statusht;
            $("#"+indexId).val(ui.item.id).trigger('change');
          },
          //minLength:3,
          autoFocus: true
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };

      $("#reqJenisJabatan").change(function() { 
        var jenis_jabatan = $("#reqJenisJabatan").val();
        if(jenis_jabatan=="")
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==1)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_struktural_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==2)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_fungsional_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
        }
        else if(jenis_jabatan==3)
        {
          document.location.href = "app/loadUrl/app/pegawai_add_jabatan_tertentu_data?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqJenisJabatan=<?=$reqJenisJabatan?>";
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
<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> JABATAN STRUKTURAL</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12 m6">
                   <select <?=$disabled?> name="reqJenisJabatan" id="reqJenisJabatan">
                    <?
                    foreach($arrjenisjabatan as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqJenisJabatan== $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqPejabatPenetap">Jenis Jabatan</label>
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
                  <input type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$disabled?> value="<?=$reqNoSk?>" />
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
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk"  value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
              </div>
              
              <div class="row">
              	<div class="input-field col s12">
              	  <input type="checkbox" id="reqIsManual" name="reqIsManual" value="1" <? if($reqIsManual == 1) echo 'checked'?> />
                  <label for="reqIsManual"></label>
                  *centang jika jabatan luar kab jombang / jabatan sebelum tahun 2012
                </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNama" class="<?=$valNama['warna']?>">
                    Nama Jabatan
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
                  <?php /*?><input type="hidden" name="reqNamaId" id="reqNamaId" value="<?=$reqNamaId?>" /><?php */?> 
                  <input type="text" id="reqNama"  name="reqNama" <?=$read?> value="<?=$reqNama?>" class="easyui-validatebox" required />
                </div>
                <div class="input-field col s12 m1">
                    <input type="checkbox" id="reqCheckTmtWaktuJabatan" name="reqCheckTmtWaktuJabatan" value="1" <? if($reqCheckTmtWaktuJabatan == 1) echo 'checked'?>/>
                    <label for="reqCheckTmtWaktuJabatan"></label>
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTmtJabatan">
                    TMT Jabatan
                    <?
                    if(!empty($valTmtJabatan['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtJabatan['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtJabatan" id="reqTmtJabatan"  value="<?=$reqTmtJabatan?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtJabatan');"/>
                </div>
                <div class="input-field col s12 m2" id="reqInfoCheckTmtWaktuJabatan">
                	<input placeholder="00:00" id="reqTmtWaktuJabatan" name="reqTmtWaktuJabatan" type="text" class="" value="<?=$reqTmtWaktuJabatan?>" />
                    <label for="reqTmtWaktuJabatan">Time</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqSatker">
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
                  <input type="hidden" name="reqSatkerId" id="reqSatkerId" value="<?=$reqSatkerId?>" />
                  <input type="text" id="reqSatker" name="reqSatker" <?=$read?> value="<?=$reqSatker?>" class="easyui-validatebox" required readonly />
                </div>
              </div>

              <div class="row">
                <input type="hidden" name="reqEselonId" id="reqEselonId" value="<?=$reqEselonId?>" />
                <div class="input-field col s12 m6" id="reqinfoeselontext">
                  <label for="reqEselonText">
                    Eselon
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
                  <input type="text" id="reqEselonText" value="<?=$reqEselonNama?>" disabled />
                </div>
                
                <div class="input-field col s12 m6" id="reqinfoeselonselect">
                  <select <?=$disabled?> name="reqSelectEselonId" id="reqSelectEselonId">
                    <?
                    foreach($arreselon as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqEselonId== $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqEselonId">Eselon</label>
                </div>
                
                <div class="input-field col s12 m6">
                  <label for="reqTmtEselon" class="<?=$valTmtEselon['warna']?>">
                    TMT Eselon
                    <?
                    if(!empty($valTmtEselon['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtEselon['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtEselon" id="reqTmtEselon" value="<?=$reqTmtEselon?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtEselon');"/>
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
                  <input type="text" id="reqNoPelantikan" name="reqNoPelantikan" <?=$disabled?> value="<?=$reqNoPelantikan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglPelantikan" class="<?=$valTglPelantikan['warna']?>">
                    Tgl. Pelantikan
                    <?
                    if(!empty($valTglPelantikan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglPelantikan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglPelantikan" id="reqTglPelantikan"  value="<?=$reqTglPelantikan?>" maxlength="10" onKeyDown="return format_date(event,'reqTglPelantikan');"/>
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
                  <input type="text" id="reqTunjangan" name="reqTunjangan" OnFocus="FormatAngka('reqTunjangan')" OnKeyUp="FormatUang('reqTunjangan')" OnBlur="FormatUang('reqTunjangan')" value="<?=numberToIna($reqTunjangan)?>" />
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
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqBlnDibayar" id="reqBlnDibayar"  value="<?=$reqBlnDibayar?>" maxlength="10" onKeyDown="return format_date(event,'reqBlnDibayar');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap" class="<?=$valPejabatPenetap['warna']?>">
                    Pejabat Penetap
                    <?
                    if(!empty($valPejabatPenetap['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPejabatPenetap['data']?></span>
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

                  <input type="hidden"  name="reqTipePegawaiId" value="<?=$reqTipePegawaiId?>" />
                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <input type="hidden" name="reqTempValidasiId" value="<?=$reqTempValidasiId?>" />
                  <input type="hidden" name="reqTempValidasiHapusId" value="<?=$reqTempValidasiHapusId?>" />
                  
                  <?
                  if($reqRowId == "")
                  {
                    ?>
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
                  else
                  {
                    if($set->getField('STATUS') ==1 ){}
                      else
                      {
                       if($set->getField('DATA_HUKUMAN') == 0)
                       {
                        ?>
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
                  }
                  ?>
                </div>
              </div>

              <!-- </div> -->
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
<script type="text/javascript" src="lib/materializetemplate/js/plugins/formatter/jquery.formatter.min.js"></script>   

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();
  });

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqTmtWaktuJabatan').formatter({
	'pattern': '{{99}}:{{99}}',
	});
	
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>