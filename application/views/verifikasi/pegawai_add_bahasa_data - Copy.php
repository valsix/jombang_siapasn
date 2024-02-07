<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/Bahasa');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqRowHapusId= $this->input->get("reqRowHapusId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011304";
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

$arrjenisbahasa= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Asing")
  , array("id"=>"2", "text"=>"Daerah")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrjenisbahasa, $arrdata);
}

$arrkemampuanbicara= [];
$arrinfocombo= [];
$arrinfocombo= array(
  array("id"=>"1", "text"=>"Aktif")
  , array("id"=>"2", "text"=>"Pasif")
);
for($icombo=0; $icombo < count($arrinfocombo); $icombo++)
{
  $arrdata= [];
  $arrdata["id"]= $arrinfocombo[$icombo]["id"];
  $arrdata["text"]= $arrinfocombo[$icombo]["text"];
  array_push($arrkemampuanbicara, $arrdata);
}

$statement= "";
$set= new Bahasa();

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

$reqValRowId= $set->getField('BAHASA_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqJenisBahasa= $set->getField("JENIS");$valJenisBahasa= checkwarna($reqPerubahanData, 'JENIS', $arrjenisbahasa, array("id", "text"), $reqTempValidasiHapusId);
$reqNamaBahasa= $set->getField('NAMA');$valNamaBahasa= checkwarna($reqPerubahanData, 'NAMA', "", "", $reqTempValidasiHapusId);
$reqKemampuanBicara= $set->getField("KEMAMPUAN");$valKemampuanBicara= checkwarna($reqPerubahanData, 'KEMAMPUAN', $arrkemampuanbicara, array("id", "text"), $reqTempValidasiHapusId);
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
        url:'validasi/bahasa_json/add',
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> PENGUASAAN BAHASA</li>
         <li class="collection-item">
          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="input-field col s12">
                  <select <?=$disabled?> name="reqJenisBahasa">
                    <?
                    foreach($arrjenisbahasa as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqJenisBahasa == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqJenisBahasa" class="<?=$valJenisBahasa['warna']?>">
                    Jenis Bahasa
                    <?
                    if(!empty($valJenisBahasa['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJenisBahasa['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <label for="reqNamaBahasa" class="<?=$valNamaBahasa['warna']?>">
                    Nama Bahasa
                    <?
                    if(!empty($valNamaBahasa['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaBahasa['data']?></span>
                    </a>
                    <?
                    }
                    ?>
                  </label>
                  <input type="text" name="reqNamaBahasa" id="reqNamaBahasa" <?=$read?> value="<?=$reqNamaBahasa?>" title="Nama bahasa harus diisi" class="required" /></td>
                </div>
              </div>


              <div class="row">
                <div class="input-field col s12">
                  <select <?=$disabled?> name="reqKemampuanBicara">
                    <?
                    foreach($arrkemampuanbicara as $item) 
                    {
                      $selectvalid= $item["id"];
                      $selectvaltext= $item["text"];
                    ?>
                    <option value="<?=$selectvalid?>" <? if($reqKemampuanBicara == $selectvalid) echo "selected";?>><?=$selectvaltext?></option>
                    <?
                    }
                    ?>
                  </select>
                  <label for="reqKemampuanBicara" class="<?=$valKemampuanBicara['warna']?>">
                    Kemampuan Bicara
                    <?
                    if(!empty($valKemampuanBicara['data']))
                    {
                    ?>
                    <a class="tooltipe" href="javascript:void(0)">
                      <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valKemampuanBicara['data']?></span>
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

                  <input type="hidden" name="reqRowId" value="<?=$reqValRowId?>" />
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
</html>