<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('Anak');
$this->load->model('Pendidikan');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

$set= new Anak();
$pendidikan= new Pendidikan();
$pendidikan->selectByParams(array());

if($reqRowId == ""){
  $reqMode = "insert";
}
else
{
  $reqMode = "update";
  $statement= " AND A.ANAK_ID = ".$reqRowId;
  $set->selectByParams(array(), -1,-1, $statement);
  $set->firstRow();

  $reqRowId= $set->getField('ANAK_ID');
  $reqNama       = $set->getField('NAMA');
  $reqTmpLahir     = $set->getField('TEMPAT_LAHIR');
  $reqTglLahir     = dateToPageCheck($set->getField('TANGGAL_LAHIR'));
  $reqLP       = $set->getField('JENIS_KELAMIN');
  $reqStatusKeluarga     = $set->getField('STATUS_KELUARGA');
  $reqStatusAktif  = $set->getField('STATUS_AKTIF');
  $reqDapatTunjangan = $set->getField('STATUS_TUNJANGAN');
  $reqPendidikan   = $set->getField('PENDIDIKAN_ID');
  $reqPekerjaan    = $set->getField('PEKERJAAN');
  $reqMulaiDibayar = dateToPageCheck($set->getField('AWAL_BAYAR'));
  $reqAkhirDibayar = dateToPageCheck($set->getField('AKHIR_BAYAR'));
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
        url:'anak_json/add',
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
		  mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
		  {
			  //mbox.close();
			  //document.location.href= "app/loadUrl/app/pegawai_add_anak_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
		  }, 1000));
		  
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

</head>

<body>    
  <!--Basic Form-->
  <div id="basic-form" class="section">
    <div class="row">
     <div class="col s12 m10 offset-m1">

       <ul class="collection card">
         <li class="collection-item ubah-color-warna">EDIT ANAK</li>
         <li class="collection-item">
          <div class="row">

           <form id="ff" method="post" enctype="multipart/form-data">

            <div class="row">
              <div class="input-field col s12 m6">

                <label for="reqNama">Nama</label>
                <input type="text" name="reqNama" id="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" class="required" /></td>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqTmpLahir">Tmp. Lahir</label>
                <input type="text" name="reqTmpLahir" id="reqTmpLahir" <?=$read?> value="<?=$reqTmpLahir?>" title="Tempat lahir harus diisi" class="required" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqTglLahir">Tgl. Lahir</label>
                <input type="text" id="reqTglLahir" name="reqTglLahir" maxlength="10" class="dateIna" onKeyDown="return format_date(event,'reqTglLahir');" <?=$read?> value="<?=$reqTglLahir?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <select <?=$disabled?> name="reqLP" id="reqLP">
                  <option value="L" <? if($reqLP == 'L') echo 'selected';?>>L</option>
                  <option value="P" <? if($reqLP == 'P') echo 'selected';?>>P</option>
                </select>
                <label for="reqLP">L/P</label>
              </div>
              <div class="input-field col s12 m6">
                <select <?=$disabled?> name="reqStatusKeluarga" id="reqStatusKeluarga">
                  <option value="1" <? if($reqStatusKeluarga == 1) echo 'selected';?>>Kandung</option>
                  <option value="2" <? if($reqStatusKeluarga == 2) echo 'selected';?>>Tiri</option>
                  <option value="3" <? if($reqStatusKeluarga == 3) echo 'selected';?>>Angkat</option>
                </select>
                <label for="reqStatusKeluarga">Status Keluarga</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="dapat" class="active">Tunjangan</label>
                <div class="col s3">
                  <input type="radio" id="dapat" <?=$disabled?> <? if($reqDapatTunjangan == '1') echo 'checked';?>  name="reqDapatTunjangan" value="1" />
                  <label for="dapat">Dapat</label>
                </div>
                <div class="col s3">
                  <input type="radio" id="tidak" <?=$disabled?> <? if($reqDapatTunjangan == '2') echo 'checked';?> name="reqDapatTunjangan" value="2" />
                  <label for="tidak">Tidak</label>
                </div>
              </div>
              <div class="input-field col s12 m6">
                <select <?=$disabled?> name="reqStatusAktif" id="reqStatusAktif">
                  <option value="1" <? if($reqStatusAktif == 1) echo 'selected';?>>Aktif</option>
                  <option value="2" <? if($reqStatusAktif == 2) echo 'selected';?>>Meninggal</option>
                </select>
                <label for="reqStatusAktif">Status Aktif</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <select name="reqPendidikan" id="reqPendidikan" <?=$disabled?>>
                  <? while($pendidikan->nextRow()){?>
                  <option value="<?=$pendidikan->getField('PENDIDIKAN_ID')?>" <? if($reqPendidikan == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                  <? }?>
                </select>
                <label for="reqPendidikan">Pendidikan</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqPekerjaan">Pekerjaan</label>
                <input type="text" <?=$read?> name="reqPekerjaan" id="reqPekerjaan" value="<?=$reqPekerjaan?>" />
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12 m6">
                <label for="reqMulaiDibayar">Mulai Dibayar</label>
                <input type="text" <?=$read?> id="reqMulaiDibayar" name="reqMulaiDibayar" class="dateIna" maxlength="10" onKeyDown="return format_date(event,'reqMulaiDibayar');" value="<?=$reqMulaiDibayar?>" />
              </div>
              <div class="input-field col s12 m6">
                <label for="reqAkhirDibayar">Akhir Dibayar</label>
                <td><input type="text" <?=$read?> id="reqAkhirDibayar" name="reqAkhirDibayar" class="dateIna" maxlength="10" onKeyDown="return format_date(event,'reqAkhirDibayar');" value="<?=$reqAkhirDibayar?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_anak_monitoring?reqId=<?=$reqId?>";
                    });
                  </script>

                  <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                  <input type="hidden" name="reqId" value="<?=$reqId?>" />
                  <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                  <button class="btn waves-effect waves-light green" style="font-size:9pt" type="submit" name="action">Simpan
                    <i class="mdi-content-save left hide-on-small-only"></i>
                  </button>
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
</body>
</html>