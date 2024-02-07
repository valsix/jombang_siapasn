<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Cuti');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "0108";
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


$arrjeniscuti= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Cuti Tahunan")
  , array("id"=>"2", "text"=>"Cuti Besar")
  , array("id"=>"3", "text"=>"Cuti Sakit")
  , array("id"=>"4", "text"=>"Cuti Bersalin")
  , array("id"=>"5", "text"=>"Cuti Alasan Penting")
  , array("id"=>"6", "text"=>"Cuti Bersama")
  , array("id"=>"7", "text"=>"CLTN")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjeniscuti, $arrdata);
}

$statement= "";
$set= new Cuti();

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


$reqValRowId= $set->getField('CUTI_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNoSurat= $set->getField('NO_SURAT');$valNoSurat= checkwarna($reqPerubahanData, 'NO_SURAT', "", "", $reqTempValidasiHapusId);
$reqJenisCuti= $set->getField('JENIS_CUTI');$valJenisCuti= checkwarna($reqPerubahanData, 'JENIS_CUTI', $arrjeniscuti, array("id", "text"), $reqTempValidasiHapusId);
$reqTanggalPermohonan= dateToPageCheck($set->getField('TANGGAL_PERMOHONAN'));$valTanggalPermohonan= checkwarna($reqPerubahanData, 'TANGGAL_PERMOHONAN', "date");
$reqTanggalSurat= dateToPageCheck($set->getField('TANGGAL_SURAT'));$valTanggalSurat= checkwarna($reqPerubahanData, 'TANGGAL_SURAT', "date");
$reqTanggalMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTanggalMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
$reqTanggalSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));$valTanggalSelesai= checkwarna($reqPerubahanData, 'TANGGAL_SELESAI', "date");
$reqLama= $set->getField('LAMA');
$reqCutiKeterangan= $set->getField('CUTI_KETERANGAN');$valCutiKeterangan= checkwarna($reqPerubahanData, 'CUTI_KETERANGAN', "", "", $reqTempValidasiHapusId);
$reqKeterangan= $set->getField('KETERANGAN');$valKeterangan= checkwarna($reqPerubahanData, 'KETERANGAN', "", "", $reqTempValidasiHapusId);




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
        url:'validasi/cuti_json/add',
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

    });

  </script>

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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> CUTI</li>
         <li class="collection-item">
           <div class="row">

             <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqJenisCuti">
                    <option value="" <? if($reqJenisCuti == "") echo 'selected';?>></option>
                    <?
                    foreach($arrjeniscuti as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                      ?>
                      <option value="<?=$selectvalid?>" <? if($reqJenisCuti == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                      <?
                    }
                    ?>
                  </select>
                  <label for="reqJenisCuti" class="<?=$valJenisCuti['warna']?>">
                    Jenis Cuti
                    <?
                    if(!empty($valJenisCuti['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisCuti['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSurat" class="<?=$valNoSurat['warna']?>">
                    No Surat
                    <?
                    if(!empty($valNoSurat['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSurat['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required <?=$read?> name="reqNoSurat" id="reqNoSurat" value="<?=$reqNoSurat?>" title="No surat harus diisi" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalSurat" class="<?=$valTanggalSurat['warna']?>">
                    Tanggal Surat
                    <?
                    if(!empty($valTanggalSurat['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalSurat['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSurat" id="reqTanggalSurat"  value="<?=$reqTanggalSurat?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSurat');"/>
                </div>
              </div>

               <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqCutiKeterangan" class="<?=$valLama['warna']?>">
                    Lama
                    <?
                    if(!empty($valLama['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valLama['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="hidden" <?=$read?> id="reqLama" name="reqLama" value="<?=$reqLama?>" />
                  <input type="text" class="easyui-validatebox" required <?=$read?> name="reqCutiKeterangan" id="reqCutiKeterangan" value="<?=$reqCutiKeterangan?>" />
                </div>
                 <div class="input-field col s12 m6">
                  <label for="reqTanggalPermohonan" class="<?=$valTanggalPermohonan['warna']?>">
                    Tanggal Permohonan
                    <?
                    if(!empty($valTanggalPermohonan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalPermohonan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPermohonan" id="reqTanggalPermohonan"  value="<?=$reqTanggalPermohonan?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPermohonan');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTanggalMulai" class="<?=$valTanggalMulai['warna']?>">
                    Tanggal Mulai
                    <?
                    if(!empty($valTanggalMulai['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalMulai['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalMulai" id="reqTanggalMulai"  value="<?=$reqTanggalMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalMulai');"/>
                </div>
                 <div class="input-field col s12 m6">
                  <label for="reqTanggalSelesai" class="<?=$valTanggalSelesai['warna']?>">
                    Tanggal Selesai
                    <?
                    if(!empty($valTanggalSelesai['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTanggalSelesai['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalSelesai" id="reqTanggalSelesai"  value="<?=$reqTanggalSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalSelesai');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
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
                  <textarea class="easyui-validatebox materialize-textarea" <?=$disabled?> name="reqKeterangan" id="reqKeterangan"><?=$reqKeterangan?></textarea>
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