<?

include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/PangkatRiwayat');
$this->load->model('Pangkat');
$this->load->model('PegawaiFile');

$sessionLoginLevel= $this->LOGIN_LEVEL;
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisKp= $this->input->get("reqJenisKp");
$reqMode= $this->input->get("reqMode");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010301";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$pangkat= new Pangkat();

$arrpangkat= [];
$set= new Pangkat();
$set->selectByParams(array());
// print_r ($set);exit;
while($set->nextRow())
{
  $arrdata= [];
  $arrdata["id"]= $set->getField("PANGKAT_ID");
  $arrdata["text"]= $set->getField("KODE");
  array_push($arrpangkat, $arrdata);
}

$arrjeniskp= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"")
  , array("id"=>"4", "text"=>"Reguler")
  , array("id"=>"11", "text"=>"Kenaikan Pangkat Pengabdian")
  , array("id"=>"5", "text"=>"Pilihan Struktural")
  , array("id"=>"6", "text"=>"Pilihan JFT")
  , array("id"=>"7", "text"=>"Pilihan PI/UD")
  , array("id"=>"10", "text"=>"Penambahan Masa Kerja")
  , array("id"=>"8", "text"=>"Hukuman disiplin")
  , array("id"=>"9", "text"=>"Pemulihan hukuman disiplin")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjeniskp, $arrdata);
}

$arrstlud= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Tingkat I")
  , array("id"=>"2", "text"=>"Tingkat II")
  , array("id"=>"3", "text"=>"Tingkat III")

);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstlud, $arrdata);
}


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


$statement= "";
$set= new PangkatRiwayat();

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

$reqValRowId= $set->getField('PANGKAT_RIWAYAT_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqTglStlud= dateToPageCheck($set->getField('TANGGAL_STLUD'));$valTglStlud= checkwarna($reqPerubahanData, 'TANGGAL_STLUD', "date");
if($reqTglStlud == "01-01-0001")
  $reqTglStlud= "";
$reqStlud= $set->getField('STLUD');$valStlud= checkwarna($reqPerubahanData, 'STLUD', $arrpangkat, array("id", "text"));
$reqNoStlud= $set->getField('NO_STLUD');$valNoStlud= checkwarna($reqPerubahanData, 'NO_STLUD');
$reqNoNota= $set->getField('NO_NOTA');$valNoNota= checkwarna($reqPerubahanData, 'NO_NOTA');
$reqNoSk= $set->getField('NO_SK');$valNoSk= checkwarna($reqPerubahanData, 'NO_SK');
$reqTh= $set->getField('MASA_KERJA_TAHUN');$valTh= checkwarna($reqPerubahanData, 'MASA_KERJA_TAHUN');
$reqBl= $set->getField('MASA_KERJA_BULAN');$valBl= checkwarna($reqPerubahanData, 'MASA_KERJA_BULAN');
$reqKredit= dotToComma($set->getField('KREDIT'));$valKredit= checkwarna($reqPerubahanData, 'KREDIT');
$reqJenisKp= $set->getField('JENIS_RIWAYAT');
$reqJenisKpNama= $set->getField('JENIS_RIWAYAT_NAMA');$valJenisKp= checkwarna($reqPerubahanData, 'JENIS_RIWAYAT', $arrjeniskp, array("id", "text"), $reqTempValidasiHapusId);
$reqKeterangan= $set->getField('KETERANGAN');$valKeterangan= checkwarna($reqPerubahanData, 'KETERANGAN');
$reqGajiPokok= $set->getField('GAJI_POKOK');$valGajiPokok= checkwarna($reqPerubahanData, 'GAJI_POKOK');
$reqTglNota= dateToPageCheck($set->getField('TANGGAL_NOTA'));$valTglNota= checkwarna($reqPerubahanData, 'TANGGAL_NOTA', "date");
$reqTglSk= dateToPageCheck($set->getField('TANGGAL_SK'));$valTglSk= checkwarna($reqPerubahanData, 'TANGGAL_SK', "date");
$reqTmtGol= dateToPageCheck($set->getField('TMT_PANGKAT'));$valTmtGol= checkwarna($reqPerubahanData, 'TMT_PANGKAT', "date");
$reqPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$reqPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');$valPejabatPenetap= checkwarna($reqPerubahanData, 'PEJABAT_PENETAP');
$reqGolRuang= $set->getField('PANGKAT_ID');$valGolRuang= checkwarna($reqPerubahanData, 'PANGKAT_ID', $arrpangkat, array("id", "text"));
$reqNoUrutCetak= $set->getField('NO_URUT_CETAK');$valNoUrutCetak= checkwarna($reqPerubahanData, 'NO_URUT_CETAK');

$tempAksiProses= "";
if($sessionLoginLevel < $LastLevel)
$tempAksiProses= "1";

$statement= " AND A.RIWAYAT_TABLE='PANGKAT_RIWAYAT' AND A.RIWAYAT_ID=".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
$set= new PegawaiFile();
$set->selectByParamsFile(array(), -1, -1, $statement, $reqId);
// echo $set->query;exit();

while($set->nextRow()){
  $reqPegawaiFieldId= $set->getField('PEGAWAI_FILE_ID');
  $reqPath= $set->getField('PATH');
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
  	function setGaji()
    {
      var reqTglSk= reqPangkatId= reqMasaKerja= "";
      reqTglSk= $("#reqTglSk").val();
      reqPangkatId= $("#reqGolRuang").val();
      reqMasaKerja= $("#reqTh").val();

      urlAjax= "gaji_pokok_json/gajipokok?reqPangkatId="+reqPangkatId+"&reqMasaKerja="+reqMasaKerja+"&reqTglSk="+reqTglSk;
      $.ajax({'url': urlAjax,'success': function(data){
       //if(data == ''){}
         //else
         //{
           tempValueGaji= parseFloat(data);
           //tempValueGaji= (tempValueGaji * 80) / 100;
           $("#reqGajiPokok").val(FormatCurrency(tempValueGaji));
         //}
       }});
    }

    $(function(){
     <?
     if($reqGajiPokok == "")
     {
       ?>
       setGaji();
       <?
     }
     ?>
       
       $("#reqGolRuang").change(function(){
        setGaji();
      });

       $("#reqTglSk, #reqTh").keyup(function(){
        setGaji();
      });

       $("#reqsimpan").click(function() { 
        if($("#ff").form('validate') == false){
          return false;
        }

        var reqTanggal= "";
        reqTanggal= $("#reqTmtGol").val();
        var s_url= "hari_libur_json/hakentrigaji?reqTanggal="+reqTanggal;
        $.ajax({'url': s_url,'success': function(dataajax){
          // return false;
          dataajax= dataajax.split(";");
          rowid= parseInt(dataajax[0]);
          infodata= dataajax[1];
          if(rowid == 1)
          {
            mbox.alert('Hubungi Sub Bidang Kepangkatan, Anda tidak berhak menambah data di atas TMT ' + infodata, {open_speed: 0});
            return false;
          }
          else
            $("#reqSubmit").click();
        }});
            
      });

       $('#ff').form({
        url:'validasi/pangkat_riwayat_json/add',
        onSubmit:function(){
          // return false;
         var reqJenisKp= $("#reqJenisKp").val();

         if(reqJenisKp == "")
         {
          mbox.alert("Lengkapi data Jenis Riwayat Pangkat terlebih dahulu", {open_speed: 0});
          return false;
         }
        
        var reqGolRuang= "";
        reqGolRuang= $("#reqGolRuang").val();
        
        if(reqGolRuang == "")
        {
         mbox.alert("Lengkapi data golongan ruang terlebih dahulu", {open_speed: 0});
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

       $('input[id^="reqPejabatPenetap"]').autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          if (id.indexOf('reqPejabatPenetap') !== -1)
          {
            var element= id.split('reqPejabatPenetap');
            var indexId= "reqPejabatPenetapId"+element[1];
            urlAjax= "pejabat_penetap_json/combo";
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
                  return {desc: element['desc'], id: element['id'], label: element['label'], statusht: element['statusht']};
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
      
      setJenisKenaikanPangkat();
      $('#reqJenisKp').bind('change', function(ev) {
       setJenisKenaikanPangkat();
     });

    });
    
function setJenisKenaikanPangkat()
{
  var reqJenisKp= "";
  reqJenisKp= $("#reqJenisKp").val();
		//$("#reqinfobkn,#reqinfokredit").hide();
		$("#reqinfostlud,#reqinfokredit").hide();
		$("#reqinfobkn,#setinfopangkat").show();
		$('#reqKredit').validatebox({required: false});
    $('#reqKredit').removeClass('validatebox-invalid');
    
   if(reqJenisKp == "")
   {
     document.location.href = "app/loadUrl/app/pegawai_add_pangkat_baru?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId=<?=$reqRowId?>";
   }
   else if(reqJenisKp == "7")
   {
			$("#reqinfostlud").show();
			$("#reqKredit").val("");
		}
		else if(reqJenisKp == "6")
		{
			$("#reqinfokredit").show();
			$('#reqKredit').validatebox({required: true});
      // console.log("s");
    }
    else
    {
     $("#reqStlud,#reqNoStlud,#reqTglStlud,#reqKredit").val("");
     $("#reqStlud").material_select();
   }
		
		
	}
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> PANGKAT</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">
               <?
               if($reqJenisKp == 1 || $reqJenisKp == 2)
               {

                ?>
                <label for="reqJenisKpNama">Jenis Riwayat Pangkat</label>
                <input type="hidden" id="reqJenisKp" name="reqJenisKp" value="<?=$reqJenisKp?>" />
                <input type="text" id="reqJenisKpNama" value="<?=$reqJenisKpNama?>" disabled />
                <?
              }
              else
              {
                ?>
                <label for="reqJenisKp" class="active <?=$valJenisKp['warna']?>">
                   Jenis Riwayat Pangkat
                    <?
                    if(!empty($valJenisKp['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisKp['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                </label>
                <select <?=$disabled?> name="reqJenisKp">
                  <?
                  foreach($arrjeniskp as $item) 
                  {
                    $selectvalid= $item["id"];
                    $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqJenisKp == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                  }
                  ?>
                </select>
                <? } ?>
              </div>
            </div>
            
            <div class="row">
            	
            </div>
            
            <span id="setinfopangkat">
              <div class="row">
                <div class="input-field col s12 m4">
                  <label for="reqNoSk" class="<?=$valNoSk['warna']?>">
                    No SK
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
                  <input type="text" class="easyui-validatebox" required id="reqNoSk" name="reqNoSk" <?=$read?> value="<?=$reqNoSk?>" title="No SK harus diisi"  />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTglSk" class="<?=$valTglSk['warna']?>">
                    Tgl SK
                    <?
                    if(!empty($valTglSk['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSk['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSk" id="reqTglSk"  value="<?=$reqTglSk?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSk');"/>
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqNoUrutCetak">No. Urut Cetak</label>
                  <input type="text" class="easyui-validatebox" id="reqNoUrutCetak" name="reqNoUrutCetak" <?=$read?> value="<?=$reqNoUrutCetak?>" />
                </div>
              </div>    

              <div class="row" id="reqinfostlud">
                <div class="input-field col s12 m4">
                  <select <?=$disabled?> name="reqStlud">
                    <?
                    foreach($arrstlud as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqStlud == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqStlud" class="<?=$valStlud['warna']?>">
                    STLUD
                    <?
                    if(!empty($valStlud['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStlud['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                </div> 
                <div class="input-field col s12 m4">
                  <label for="reqNoStlud" class="<?=$valNoStlud['warna']?>">
                    No. STLUD
                    <?
                    if(!empty($valNoStlud['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoStlud['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqNoStlud" name="reqNoStlud" <?=$read?> value="<?=$reqNoStlud?>" />
                </div>
                <div class="input-field col s12 m4">
                  <label for="reqTglStlud" class="<?=$valTglStlud['warna']?>">
                    No. STLUD
                    <?
                    if(!empty($valTglStlud['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglStlud['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglStlud" id="reqTglStlud"  value="<?=$reqTglStlud?>" maxlength="10" onKeyDown="return format_date(event,'reqTglStlud');"/>
                </div>
              </div>

              <div class="row" id="reqinfobkn">
                <div class="input-field col s12 m6">
                  <label for="reqNoNota" class="<?=$valNoNota['warna']?>">
                    No Nota BKN
                    <?
                    if(!empty($valNoNota['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoNota['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" name="reqNoNota" id="reqNoNota" <?=$read?> value="<?=$reqNoNota?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglNota" class="<?=$valTglNota['warna']?>">
                    Tgl Nota BKN
                    <?
                    if(!empty($valTglNota['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglNota['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglNota" id="reqTglNota"  value="<?=$reqTglNota?>" maxlength="10" onKeyDown="return format_date(event,'reqTglNota');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqGolRuang">
                    <?
                    foreach($arrpangkat as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqGolRuang == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                    }
                    ?>
                  </select>
                  <label for="reqGolRuang" class="<?=$valGolRuang['warna']?>">
                    Gol/Ruang
                    <?
                    if(!empty($valGolRuang['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGolRuang['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTmtGol" class="<?=$valTmtGol['warna']?>">
                    TMT SK
                    <?
                    if(!empty($valTmtGol['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTmtGol['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTmtGol" id="reqTmtGol"  value="<?=$reqTmtGol?>" maxlength="10" onKeyDown="return format_date(event,'reqTmtGol');"/>
                </div>
              </div>        

              <div class="row">
                <div class="input-field col s6 m3">
                  <label for="reqTh" class="<?=$valTh['warna']?>">
                    Masa Kerja Tahun
                    <?
                    if(!empty($valTh['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTh['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqTempTh" value="<?=$reqTh?>" />
                  <input type="text" class="easyui-validatebox" required name="reqTh" <?=$read?> value="<?=$reqTh?>" id="reqTh" title="Masa kerja tahun harus diisi" />
                </div>

                <div class="input-field col s6 m3">
                  <label for="reqBl" class="<?=$valBl['warna']?>">
                    Masa Kerja Bulan
                    <?
                    if(!empty($valBl['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valBl['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="hidden" name="reqTempBl" value="<?=$reqBl?>" />
                  <input type="text" class="easyui-validatebox" required name="reqBl" <?=$read?> value="<?=$reqBl?>" id="reqBl" title="Masa kerja bulan diisi" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqGajiPokok" class="<?=$valGajiPokok['warna']?>">
                    Gaji Pokok
                    <?
                    if(!empty($valGajiPokok['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGajiPokok['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" placeholder class="easyui-validatebox" required id="reqGajiPokok" name="reqGajiPokok" OnFocus="FormatAngka('reqGajiPokok')" OnKeyUp="FormatUang('reqGajiPokok')" OnBlur="FormatUang('reqGajiPokok')" value="<?=numberToIna($reqGajiPokok)?>" />
                </div>
                <div class="input-field col s12 m3" id="reqinfokredit">
                  <label for="reqKredit" class="<?=$valKredit['warna']?>">
                    Angka Kredit
                    <?
                    if(!empty($valKredit['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKredit['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" id="reqKredit" name="reqKredit" <?=$read?>  class="easyui-validatebox" value="<?=$reqKredit?>" onkeypress='kreditvalidate(event, this)' />
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPejabatPenetap" class="<?=$valPejabatPenetap['warna']?>">
                    Pejabat Penetapan
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
                  <textarea <?=$disabled?> name="reqKeterangan" id="reqKeterangan" class="required materialize-textarea"><?=$reqKeterangan?></textarea>
                  <label for="reqKeterangan" class="<?=$valKeterangan['warna']?>">
                    Keterangan
                    <?
                    if(!empty($valKeterangan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKeterangan['data']?></span>
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


                  <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
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
                      <button type="submit" style="display:none" id="reqSubmit"></button>
                      <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
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
                         if($tempAksiProses == "1"){}
                           else
                           {
                            ?>

                            <?
                        // A;R;D
                            if($tempAksesMenu == "A")
                            {
                              ?>
                              <button type="submit" style="display:none" id="reqSubmit"></button>
                              <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                                <i class="mdi-content-save left hide-on-small-only"></i>
                              </button>
                              <?
                            }
                            ?>

                            <?
                          }
                        }
                      }
                    }
                    ?>

                    <?
                    if ($reqPath==""){}
                      else
                      {
                        ?>


                  <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="bandingkan">Cek eFile
                    <i class="mdi-navigation-arrow-forward right hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#bandingkan").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_pangkat_data_efile?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqPeriode=<?=$reqPeriode?>";
                    });
                  </script>

                  <?
                }
                  ?>

                </div>
              </div>
              
            </span>

            <!-- </div> -->
          </form>
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
  
  $('#reqNoUrutCetak,#reqTh,#reqBl').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>