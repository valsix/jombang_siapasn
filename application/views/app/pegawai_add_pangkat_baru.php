<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqJenisKp= $this->input->get("reqJenisKp");
$reqMode= $this->input->get("reqMode");
$reqPeriode= $this->input->get("reqPeriode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "1201";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);


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
	  $('#reqJenisKp').bind('change', function(ev) {
       setJenisKenaikanPangkat();
     });

    });
	
	function setJenisKenaikanPangkat()
    {
		var reqJenisKp= "";
		reqJenisKp= $("#reqJenisKp").val();
	    document.location.href = "app/loadUrl/app/pegawai_add_pangkat_data?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>&reqRowId=<?=$reqRowId?>&reqJenisKp="+reqJenisKp;
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

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT PANGKAT</li>
         <li class="collection-item">

          <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">
               <label for="reqJenisKp" class="active">Jenis Riwayat Pangkat</label>
                <select <?=$disabled?> name="reqJenisKp" id="reqJenisKp" >
                  <option value="" <? if($reqJenisKp == "") echo 'selected'?>></option>
                  <option value="4" <? if($reqJenisKp == 4) echo 'selected'?>>Reguler</option>
                  <option value="11" <? if($reqJenisKp == 11) echo 'selected'?>>Kenaikan Pangkat Pengabdian</option>
                  <option value="5" <? if($reqJenisKp == 5) echo 'selected'?>>Pilihan Struktural</option>
                  <option value="6" <? if($reqJenisKp == 6) echo 'selected'?>>Pilihan JFT</option>
                  <option value="7" <? if($reqJenisKp == 7) echo 'selected'?>>Pilihan PI/UD</option>
                  <option value="10" <? if($reqJenisKp == 10) echo 'selected'?>>Penambahan Masa Kerja</option>
                  <option value="8" <? if($reqJenisKp == 8) echo 'selected'?>>Hukuman disiplin</option>
                  <option value="9" <? if($reqJenisKp == 9) echo 'selected'?>>Pemulihan hukuman disiplin</option>
                </select>
              </div>
            </div>
            
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            <div class="row"><div class="input-field col s12 m6"></div></div>
            
            <div class="row">
              <div class="input-field col s12">
                <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                  <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                </button>

                <script type="text/javascript">
                  $("#kembali").click(function() { 
                    document.location.href = "app/loadUrl/app/pegawai_add_pangkat_monitoring?reqId=<?=$reqId?>&reqPeriode=<?=$reqPeriode?>";
                  });
                </script>

                <input type="hidden" name="reqPeriode" value="<?=$reqPeriode?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
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