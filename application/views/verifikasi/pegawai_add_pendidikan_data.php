<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pendidikan');
$this->load->model('validasi/PendidikanRiwayat');

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

$arrgelartipe= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"", "text"=>"Tanpa gelar")
  , array("id"=>"1", "text"=>"Depan")
  , array("id"=>"2", "text"=>"Belakang")
  , array("id"=>"3", "text"=>"Depan Belakang")

);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrgelartipe, $arrdata);
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

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0105";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$tempKondisiCpns= "";
$statement= " AND A.STATUS IS NULL AND A.STATUS_PENDIDIKAN = '1' AND A.PEGAWAI_ID = ".$reqId;
$set= new PendidikanRiwayat();
$set->selectByPersonal(array(), -1, -1, $reqId, $reqRowHapusId, "", $statement);
if($set->firstRow())
  $tempKondisiCpns= 1;
unset($set);
//echo $tempKondisiCpns;exit;

$statement= "";
$set= new PendidikanRiwayat();

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

$reqValRowId= $set->getField('PENDIDIKAN_RIWAYAT_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNamaSekolah= $set->getField('NAMA');$valNamaSekolah= checkwarna($reqPerubahanData, 'NAMA', "", "", $reqTempValidasiHapusId);
$reqNamaFakultas= $set->getField('NAMA_FAKULTAS');
$reqPendidikanId= $set->getField('PENDIDIKAN_ID');$valPendidikan= checkwarna($reqPerubahanData, 'PENDIDIKAN_ID', $arrPendidikan, array("id", "text"), $reqTempValidasiHapusId);
$reqTglSttb= dateToPageCheck($set->getField('TANGGAL_STTB'));$valTglSttb= checkwarna($reqPerubahanData, 'TANGGAL_STTB', "date");
$reqJurusan= $set->getField('JURUSAN');$valJurusan= checkwarna($reqPerubahanData, 'JURUSAN', "", "", $reqTempValidasiHapusId);
$reqJurusanId= $set->getField('PENDIDIKAN_JURUSAN_ID');
$reqAlamatSekolah= $set->getField('TEMPAT');$valAlamatSekolah= checkwarna($reqPerubahanData, 'TEMPAT', "", "", $reqTempValidasiHapusId);
$reqKepalaSekolah= $set->getField('KEPALA');$valKepalaSekolah= checkwarna($reqPerubahanData, 'KEPALA', "", "", $reqTempValidasiHapusId);
$reqNoSttb= $set->getField('NO_STTB');$valNoSttb= checkwarna($reqPerubahanData, 'NO_STTB', "", "", $reqTempValidasiHapusId);
$reqStatus= $set->getField('STATUS');
$reqCheckStatusPendidikan= $set->getField('STATUS_PENDIDIKAN');
$reqNoSuratIjin= $set->getField('NO_SURAT_IJIN');$valNoSuratIjin= checkwarna($reqPerubahanData, 'NO_SURAT_IJIN', "", "", $reqTempValidasiHapusId);
$reqTglSuratIjin= dateToPageCheck($set->getField('TANGGAL_SURAT_IJIN'));$valTglSuratIjin= checkwarna($reqPerubahanData, 'TANGGAL_SURAT_IJIN', "date");
$reqGelarTipe= $set->getField('GELAR_TIPE');$valGelarTipe= checkwarna($reqPerubahanData, 'GELAR_TIPE', "", "", $reqTempValidasiHapusId);
// echo $reqGelarTipe;exit;
$reqGelarNamaDepan= $set->getField('GELAR_DEPAN');$valGelarNamaDepan= checkwarna($reqPerubahanData, 'GELAR_DEPAN', "", "", $reqTempValidasiHapusId);
$reqGelarNama= $set->getField('GELAR_NAMA');$valGelarNama= checkwarna($reqPerubahanData, 'GELAR_NAMA', "", "", $reqTempValidasiHapusId);
$reqPppkStatus= $set->getField('PPPK_STATUS');$valPppkStatus= checkwarna($reqPerubahanData, 'PPPK_STATUS', "", "", $reqTempValidasiHapusId);

$reqStatusPendidikanNama= $set->getField('STATUS_PENDIDIKAN_NAMA');

$arrstatuspendidikan= [];
$arrinfocombo= [];
if($tempKondisiCpns == "1" && $reqStatusPendidikan == "1")
{
  $arrinfocombo= array(
    array("id"=>"1", "text"=>"Pendidikan CPNS")
    , array("id"=>"2", "text"=>"Diakui")
    , array("id"=>"3", "text"=>"Belum Diakui")
    , array("id"=>"4", "text"=>"Riwayat")
  );
  
}
elseif($tempKondisiCpns == "1")
{
  $arrinfocombo= array(
    array("id"=>"2", "text"=>"Diakui")
    , array("id"=>"3", "text"=>"Belum Diakui")
    , array("id"=>"4", "text"=>"Riwayat")
  );
}
else
{
  $arrinfocombo= array(
    array("id"=>"1", "text"=>"Pendidikan CPNS")
    , array("id"=>"2", "text"=>"Diakui")
    , array("id"=>"3", "text"=>"Belum Diakui")
    , array("id"=>"4", "text"=>"Riwayat")
  );
}
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrstatuspendidikan, $arrdata);
}

$arrstatustugasijin= [];
if($reqStatus == "3")
{
  $arrinfocombo= array(
    array("id"=>"1", "text"=>"Ijin Belajar")
    , array("id"=>"2", "text"=>"Tugas Belajar")
  );
  for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
  {
    $arrdata= [];
    $arrdata["id"]= $arrinfocombo[$icombo]["id"];
    $arrdata["text"]= $arrinfocombo[$icombo]["text"];
    array_push($arrstatustugasijin, $arrdata);
  }
}
$reqStatusTugasIjinBelajar= $set->getField('STATUS_TUGAS_IJIN_BELAJAR');
$reqStatusPendidikan= $set->getField('STATUS_PENDIDIKAN');$valStatusPendidikan= checkwarna($reqPerubahanData, 'STATUS_PENDIDIKAN', $arrstatuspendidikan, array("id", "text"), $reqTempValidasiHapusId);
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
        url:'validasi/pendidikan_riwayat_json/add',
        onSubmit:function(){
          var reqJurusanId= reqPendidikanId= "";
          reqJurusanId= $("#reqJurusanId").val();
          reqPendidikanId= $("#reqPendidikanId").val();

          if(reqJurusanId == "" && parseInt(reqPendidikanId) > 6)
          {
           $.messager.alert('Info', "Jurusan tidak ada dalam sistem, hubungi admin untuk menambahkan data jurusan", 'info');
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

      $('input[id^="reqJurusan"]').autocomplete({
        source:function(request, response){
          var id= this.element.attr('id');
          var replaceAnakId= replaceAnak= urlAjax= "";

          if (id.indexOf('reqJurusan') !== -1)
          {
            var reqPendidikanId= "";
            reqPendidikanId= $("#reqPendidikanId").val();
            var element= id.split('reqJurusan');
            var indexId= "reqJurusanId"+element[1];
            urlAjax= "pendidikan_jurusan_json/combo?reqId="+reqPendidikanId;
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
          if (id.indexOf('reqJurusan') !== -1)
          {
            var element= id.split('reqJurusan');
            var indexId= "reqJurusanId"+element[1];
          }

          var statusht= "";
            //statusht= ui.item.statusht;
            $("#"+indexId).val(ui.item.id).trigger('change');
          },
          //minLength:3,
          autoFocus: true
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };

      $('#reqGelarTipe').bind('change', function(ev) {
       setGelarTipe();
     });

      $("#reqPendidikanId").change(function(){
       $("#reqJurusan, #reqJurusanId").val("");
     });

      setGelarTipe();

    });

    //
    function setGelarTipe()
    {
      var reqGelarTipe= "";
      reqGelarTipe= $("#reqGelarTipe").val();
      $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").hide();

      $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: false});
      $('#reqGelarNamaDepan,#reqGelarNama').removeClass('validatebox-invalid');

      if(reqGelarTipe == "")
      {
        $("#reqGelarNamaDepan,#reqGelarNama").val("");
      }
      else if(reqGelarTipe == "1")
      {
        $("#reqInfoNamaGelarDepan").show();
        $("#reqGelarNama").val("");
        $('#reqGelarNamaDepan').validatebox({required: true});
      }
      else if(reqGelarTipe == "2")
      {
        $("#reqInfoNamaGelarBelakang").show();
        $("#reqGelarNamaDepan").val("");
        $('#reqGelarNama').validatebox({required: true});
      }
      else
      {
        $("#reqInfoNamaGelarDepan,#reqInfoNamaGelarBelakang").show();
        $('#reqGelarNamaDepan,#reqGelarNama').validatebox({required: true});
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> PENDIDIKAN</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m3">
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
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> name="reqStatusPendidikan">
                    <?
                    foreach($arrstatuspendidikan as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqStatusPendidikan == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                    }
                    ?>
                  </select>
                  <label for="reqStatusPendidikan" class="<?=$valStatusPendidikan['warna']?>">
                    Status Pendidikan
                    <?
                    if(!empty($valStatusPendidikan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusPendidikan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqJurusan">Jurusan</label>
                  <input type="hidden" name="reqJurusanId" id="reqJurusanId" value="<?=$reqJurusanId?>" /> 
                  <input placeholder type="text" name="reqJurusan" id="reqJurusan" value="<?=$reqJurusan?>" title="Jurusan harus diisi" />
                </div>
              </div>    

              <div class="row">
                <?
                if($reqStatus == "3")
                {
                ?>
                  <div class="input-field col s12 m3">
                    <select <?=$disabled?>  name="reqStatusTugasIjinBelajar" >
                      <?
                      foreach($arrstatustugasijin as $item) 
                      {
                        $selectvalid= $item["id"];
                        $selectvaltext= $item["text"];
                        ?>
                        <option value="<?=$selectvalid?>" <? if($reqStatusTugasIjinBelajar == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                        <?
                      }
                      ?>
                    </select>
                    <label for="reqStatusTugasIjinBelajar" class="<?=$valStatusTugasIjinBelajar['warna']?>">
                    Status Ijin belajar / Tugas Belajar
                    <?
                    if(!empty($valStatusTugasIjinBelajar['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valStatusTugasIjinBelajar['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                    </label>
                  </div>
                <?
                }
                ?>
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> name="reqGelarTipe" id="reqGelarTipe">
                  <?
                  foreach($arrgelartipe as $item) 
                  {
                    $selectvalid= $item["id"];
                    $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqGelarTipe == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                  }
                  ?>
                  </select>
                  <label for="reqGelarTipe" class="<?=$valGelarTipe['warna']?>">
                    Tipe Gelar
                    <?
                    if(!empty($valGelarTipe['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGelarTipe['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarDepan">
                  <label for="reqGelarNamaDepan" class="<?=$valGelarNamaDepan['warna']?>">
                    Gelar Depan
                    <?
                    if(!empty($valGelarNamaDepan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGelarNamaDepan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                    </label>
                  <input placeholder type="text" class="easyui-validatebox" name="reqGelarNamaDepan" id="reqGelarNamaDepan" <?=$read?> value="<?=$reqGelarNamaDepan?>"/>
                </div>
                <div class="input-field col s12 m3" id="reqInfoNamaGelarBelakang">
                  <label for="reqGelarNama" class="<?=$valGelarNama['warna']?>">
                    Gelar Belakang
                    <?
                    if(!empty($valGelarNama['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valGelarNama['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" name="reqGelarNama" id="reqGelarNama" <?=$read?> value="<?=$reqGelarNama?>"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaSekolah" class="<?=$valNamaSekolah['warna']?>">
                    Nama Sekolah
                    <?
                    if(!empty($valNamaSekolah['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaSekolah['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqNamaSekolah" name="reqNamaSekolah" <?=$read?> value="<?=$reqNamaSekolah?>" title="Nama sekolah harus diisi" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqKepalaSekolah" class="<?=$valKepalaSekolah['warna']?>">
                    Kepala Sekolah
                    <?
                    if(!empty($valKepalaSekolah['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKepalaSekolah['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqKepalaSekolah" name="reqKepalaSekolah" <?=$read?> value="<?=$reqKepalaSekolah?>" />
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSttb" class="<?=$valNoSttb['warna']?>">
                    No. STTB
                    <?
                    if(!empty($valNoSttb['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSttb['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" required id="reqNoSttb" name="reqNoSttb" <?=$read?> value="<?=$reqNoSttb?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSttb" class="<?=$valTglSttb['warna']?>">
                    Tgl. STTB
                    <?
                    if(!empty($valTglSttb['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSttb['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttb" id="reqTglSttb" value="<?=$reqTglSttb?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttb');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSuratIjin" class="<?=$valNoSuratIjin['warna']?>">
                    No. Surat Ijin / Tugas Belajar
                    <?
                    if(!empty($valNoSuratIjin['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSttb['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" id="reqNoSuratIjin" name="reqNoSuratIjin" <?=$read?> value="<?=$reqNoSuratIjin?>"  />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSuratIjin" class="<?=$valTglSuratIjin['warna']?>">
                    Tgl. Surat Ijin / Tugas Belajar
                    <?
                    if(!empty($valNoSuratIjin['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSuratIjin['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSuratIjin" id="reqTglSuratIjin" value="<?=$reqTglSuratIjin?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSuratIjin');"/>
                </div>
              </div>    

              <div class="row">
                <div class="input-field col s6">
                  <label for="reqAlamatSekolah" class="<?=$valAlamatSekolah['warna']?>">
                    Tempat Sekolah
                    <?
                    if(!empty($valAlamatSekolah['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAlamatSekolah['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input placeholder type="text" class="easyui-validatebox" id="reqAlamatSekolah" name="reqAlamatSekolah" <?=$read?> value="<?=$reqAlamatSekolah?>" />
                </div>
                <div class="input-field col s12 m6">
                  <input type="checkbox" id="reqPppkStatus" name="reqPppkStatus" value="1" <? if($reqPppkStatus == 1) echo 'checked'?>/>
                  <label for="reqPppkStatus">Status PPPK</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <select <?=$disabled?> name="reqStatusValidasi" id="reqStatusValidasi">
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

                  <input type="hidden" name="reqNamaFakultas" value="<?=$reqNamaFakultas?>" />
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
                  for($index_loop=0; $index_loop < 7; $index_loop++)
                  {
                  ?>
                  <br/>
                  <?
                  }
                  ?>
                </div>
              </div>

              <!-- </div> -->
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>

</div>

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