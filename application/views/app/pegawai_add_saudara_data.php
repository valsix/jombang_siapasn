<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Saudara');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");
$CI->checkpegawai($reqId);
$tempUserLoginId= $this->USER_LOGIN_ID;
$tempMenuId= "011005";
$tempAksesMenu= $CI->checkmenupegawai($tempUserLoginId, $tempMenuId);

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
 $reqMode = 'update';
 $statement= " AND A.SAUDARA_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
 $set= new Saudara();
 $set->selectByParams(array(), -1, -1, $statement);
 $set->firstRow();

 // $reqSaudaraId= $set->getField("SAUDARA_ID");
 $reqPegawaiId= $set->getField("PEGAWAI_ID");
 $reqNama= $set->getField("NAMA");
 $reqTmpLahir= $set->getField("TEMPAT_LAHIR");
 $reqTglLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
 $reqJenisKelamin= $set->getField("JENIS_KELAMIN");
 $reqPekerjaan= $set->getField("PEKERJAAN");
 $reqAlamat= $set->getField("ALAMAT");
 $reqKodePos= $set->getField("KODEPOS");
 $reqTelepon= $set->getField("TELEPON");
 $reqPropinsi= $set->getField("PROPINSI_ID");
 $reqKabupaten= $set->getField("KABUPATEN_ID");
 $reqKecamatan= $set->getField("KECAMATAN_ID");
 $reqKelurahan= $set->getField("KELURAHAN_ID");
 $reqStatusHidup= $set->getField("STATUS_HIDUP");
 $reqStatusSdr= $set->getField("STATUS_SDR");
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
        url:'saudara_json/add',
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
			  document.location.href= "app/loadUrl/app/pegawai_add_saudara_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
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
         <li class="collection-item ubah-color-warna">EDIT SAUDARA</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNama">Nama</label>
                  <input type="text" id="reqNama" name="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" class="required" /></td>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqTmpLahir">Tmp. Lahir</label>
                  <input type="text" id="reqTmpLahir" name="reqTmpLahir" <?=$read?> value="<?=$reqTmpLahir?>" <?php /*?>title="Tempat lahir harus diisi" class="required"<?php */?> />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTglLahir">Tgl. Lahir  </label>
                  <input class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTglLahir" id="reqTglLahir"  value="<?=$reqTglLahir?>" maxlength="10" onKeyDown="return format_date(event,'reqTglLahir');"/>
                </div>
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqStatusSdr" id="reqStatusSdr">
                    <option value=""></option>
                    <option value="1" <? if($reqStatusSdr == 1) echo 'selected';?>>Kandung</option>
                    <option value="2" <? if($reqStatusSdr == 2) echo 'selected';?>>Tiri</option>
                    <option value="3" <? if($reqStatusSdr == 3) echo 'selected';?>>Angkat</option>
                  </select>
                  <label for="reqStatusSdr">Status</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqJenisKelamin" id="reqJenisKelamin">
                    <option value="L" <? if($reqJenisKelamin == 'L') echo 'selected';?>>L</option>
                    <option value="P" <? if($reqJenisKelamin == 'P') echo 'selected';?>>P</option>
                  </select>
                  <label for="reqJenisKelamin">L/P</label>
                </div>
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqStatusHidup" id="reqStatusHidup">
                    <option value=""></option>
                    <option value="1" <? if($reqStatusHidup == 1) echo 'selected';?>>Aktif</option>
                    <option value="2" <? if($reqStatusHidup == 2) echo 'selected';?>>Meninggal</option>
                  </select>
                  <label for="reqStatusHidup">Status Aktif</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqPekerjaan">Pekerjaan</label>
                  <input type="text" id="reqPekerjaan" name="reqPekerjaan" <?=$read?> value="<?=$reqPekerjaan?>" />
                </div>
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqPropinsi" id="reqPropinsi">
                    <option value=""></option>

                  </select>
                  <label for="reqPropinsi">Propinsi</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqAlamat">Alamat</label>
                  <textarea <?=$disabled?> name="reqAlamat" id="reqAlamat" class="materialize-textarea"><?=$reqAlamat?></textarea>
                </div>
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> id="reqKabupaten" name="reqKabupaten">
                    <option value=""></option>

                  </select>
                  <label for="reqKabupaten">Kabupaten</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m3">
                  <label for="reqKodePos">Kode Pos</label>
                  <input type="text"  name="reqKodePos" id="reqKodePos" <?=$read?> value="<?=$reqKodePos?>" />
                </div>
                <div class="input-field col s12 m3">
                  <label for="reqTelepon">Telepon </label>
                  <input type="text"  name="reqTelepon" id="reqTelepon" <?=$read?> value="<?=$reqTelepon?>" />
                </div>
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> id="reqKecamatan" name="reqKecamatan">
                    <option value=""></option>

                  </select>
                  <label for="reqKecamatan">Kecamatan</label>
                </div>
                <div class="input-field col s12 m3">
                  <select <?=$disabled?> id="reqKelurahan" name="reqKelurahan">
                    <option value=""></option>

                  </select>
                  <label for="reqKelurahan">Kelurahan</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_saudara_monitoring?reqId=<?=$reqId?>";
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