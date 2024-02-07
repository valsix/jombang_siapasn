<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('DiklatFungsional');
$this->load->model('Diklat');
$diklat= new Diklat();

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "010603";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

$diklat->selectByParams(array());

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
  $reqMode = 'update';
  $statement= " AND A.DIKLAT_FUNGSIONAL_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
  $set= new DiklatFungsional();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();

  $reqNamaDiklat= $set->getField('NAMA');
  $reqTahun= $set->getField('TAHUN');
  $reqNoSttpp= $set->getField('NO_STTPP');
  $reqPenyelenggara= $set->getField('PENYELENGGARA');
  $reqAngkatan= $set->getField('ANGKATAN');
  $reqTglMulai= dateToPageCheck($set->getField('TANGGAL_MULAI'));
  $reqTglSelesai= dateToPageCheck($set->getField('TANGGAL_SELESAI'));
  $reqTglSttpp= dateToPageCheck($set->getField('TANGGAL_STTPP'));
  // $reqreqat= $set->getField('reqAT');
  $reqJumlahJam= $set->getField('JUMLAH_JAM');

  $reqTempat= $set->getField('TEMPAT');

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

  <script type="text/javascript"> 
    $(function(){
      $('#ff').form({
        url:'diklat_fungsional_json/add',
        onSubmit:function(){
          if($(this).form('validate')){}
            else
            {
              $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
              return false;
            }
          },
          success:function(data){
          // alert(data);return false;
          data = data.split("-");
          rowid= data[0];
          infodata= data[1];
          //$.messager.alert('Info', infodata, 'info');
          mbox.alert(infodata, {open_speed: 500}, interval = window.setInterval(function() 
          {
			  clearInterval(interval);
			  mbox.close();
			  document.location.href= "app/loadUrl/app/pegawai_add_diklat_fungsional_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
         }, 1000));
		 $(".mbox > .right-align").css({"display": "none"});
          
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

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT DIKLAT FUNGSIONAL</li>
         <li class="collection-item">

            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNamaDiklat">Nama Diklat</label>
                  <input type="text" class="easyui-validatebox" required name="reqNamaDiklat" id="reqNamaDiklat" <?=$read?> value="<?=$reqNamaDiklat?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNoSttpp">No. STTPP</label>
                  <input type="text" class="easyui-validatebox" required name="reqNoSttpp" id="reqNoSttpp" <?=$read?> value="<?=$reqNoSttpp?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSttpp">Tgl. STTPP</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSttpp" id="reqTglSttpp"  value="<?=$reqTglSttpp?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSttpp');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqAngkatan">Angkatan</label>
                  <input type="text" name="reqAngkatan" <?=$read?> value="<?=$reqAngkatan?>" id="reqAngkatan" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTempat">Tempat</label>
                  <input type="text" name="reqTempat" id="reqTempat" <?=$read?> value="<?=$reqTempat?>" title="reqat harus diisi" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTglMulai">Tgl. Mulai</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglMulai" id="reqTglMulai"  value="<?=$reqTglMulai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglMulai');"/>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTglSelesai">Tgl Selesai</label>
                  <input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglSelesai" id="reqTglSelesai"  value="<?=$reqTglSelesai?>" maxlength="10" onKeyDown="return format_date(event,'reqTglSelesai');"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqJumlahJam">Jumlah Jam</label>
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
                  <label for="reqPenyelenggara">Penyelenggara</label>
                  <input type="text" class="easyui-validatebox" required name="reqPenyelenggara" id="reqPenyelenggara" <?=$read?> value="<?=$reqPenyelenggara?>" title="Penyelenggara harus diisi" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_diklat_fungsional_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
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