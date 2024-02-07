<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/personal.func.php");


$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('validasi/DiklatTeknis');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010602";
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

$statement= "";
$set= new DiklatTeknis();

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

$reqValRowId= $set->getField('DIKLAT_TEKNIS_ID');
if(empty($reqValRowId))
{
  $infoperubahan= "Data Baru";
}

$reqNamaDiklat= $set->getField('NAMA');$valNamaDiklat= checkwarna($reqPerubahanData, 'NAMA');
$reqTahun= $set->getField('TAHUN');$valTahun= checkwarna($reqPerubahanData, 'TAHUN');
$reqNoSttpp= $set->getField('NO_STTPP');$valNoSttpp= checkwarna($reqPerubahanData, 'NO_STTPP');
$reqPenyelenggara= $set->getField('PENYELENGGARA');$valPenyelenggara= checkwarna($reqPerubahanData, 'PENYELENGGARA');
$reqAngkatan= $set->getField('ANGKATAN');$valAngkatan= checkwarna($reqPerubahanData, 'ANGKATAN');
$reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));$valTglMulai= checkwarna($reqPerubahanData, 'TANGGAL_MULAI', "date");
$reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));$valTglSelesai= checkwarna($reqPerubahanData, 'TANGGAL_SELESAI', "date");
$reqTglSttpp= dateToPageCheck($set->getField('TANGGAL_STTPP'));$valTglSttpp= checkwarna($reqPerubahanData, 'TANGGAL_STTPP', "date");
$reqTempat= $set->getField('TEMPAT');$valTempat= checkwarna($reqPerubahanData, 'TEMPAT');
$reqJumlahJam= $set->getField('JUMLAH_JAM');$valJumlahJam= checkwarna($reqPerubahanData, 'JUMLAH_JAM');

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
        url:'validasi/diklat_teknis_json/add',
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
         <li class="collection-item ubah-color-warna"><?=$infoperubahan?> DIKLAT TEKNIS</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">
            
              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqNamaDiklat" class="<?=$valNamaDiklat['warna']?>">
                   Nama Diklat
                    <?
                    if(!empty($valNamaDiklat['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNamaDiklat['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required name="reqNamaDiklat" id="reqNamaDiklat" <?=$read?> value="<?=$reqNamaDiklat?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSttpp" class="<?=$valNoSttpp['warna']?>">
                    No. STTPP
                    <?
                    if(!empty($valNoSttpp['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valNoSttpp['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required name="reqNoSttpp" id="reqNoSttpp" <?=$read?> value="<?=$reqNoSttpp?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSttpp" class="<?=$valTglSttpp['warna']?>">
                   Tgl. STTPP
                    <?
                    if(!empty($valTglSttpp['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSttpp['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttpp" id="reqTglSttpp"  value="<?=$reqTglSttpp?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttpp');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqAngkatan" class="<?=$valAngkatan['warna']?>">
                   Angkatan
                    <?
                    if(!empty($valAngkatan['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valAngkatan['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" name="reqAngkatan" <?=$read?> value="<?=$reqAngkatan?>" id="reqAngkatan" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTempat" class="<?=$valTempat['warna']?>">
                   Tempat
                    <?
                    if(!empty($valTempat['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTempat['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" name="reqTempat" id="reqTempat" <?=$read?> value="<?=$reqTempat?>" title="reqat harus diisi" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTglMulai" class="<?=$valTglMulai['warna']?>">
                   Tgl Mulai
                    <?
                    if(!empty($valTglMulai['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglMulai['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSelesai" class="<?=$valTglSelesai['warna']?>">
                   Tgl Selesai
                    <?
                    if(!empty($valTglSelesai['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valTglSelesai['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJumlahJam" class="<?=$valJumlahJam['warna']?>">
                   Jumlah Jam
                    <?
                    if(!empty($valJumlahJam['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valJumlahJam['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text"  name="reqJumlahJam" <?=$read?> value="<?=$reqJumlahJam?>" id="reqJumlahJam" />
                  <script>
                    $("#reqJumlahJam, #reqTahun").keypress(function(e) {
                      if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57))
                      {
                        return false;
                      }
                    });
                  </script>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m12">
                  <label for="reqPenyelenggara" class="<?=$valPenyelenggara['warna']?>">
                   Penyelenggara
                    <?
                    if(!empty($valPenyelenggara['data']))
                    {
                      ?>
                      <a class="tooltipe" href="javascript:void(0)">
                        <i class="fa fa-question-circle text-white"></i><span class="classic"><?=$valPenyelenggara['data']?></span>
                      </a>
                      <?
                    }
                    ?>
                  </label>
                  <input type="text" class="easyui-validatebox" required name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" title="Penyelenggara harus diisi" />
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