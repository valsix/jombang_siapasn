<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('SuamiIstri');
$this->load->model('Pendidikan');

$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqMode= $this->input->get("reqMode");

if($reqRowId=="")
{
  $reqMode = 'insert';
}
else
{
 $reqMode = 'update';
 $statement= " AND A.SUAMI_ISTRI_ID = ".$reqRowId." AND A.PEGAWAI_ID = ".$reqId;
 $set= new SuamiIstri();
 $set->selectByParams(array(), -1, -1, $statement);
 $set->firstRow();

 $reqPendidikanId= $set->getField("PENDIDIKAN_ID");
 $reqNama= $set->getField("NAMA");
 $reqTempatLahir= $set->getField("TEMPAT_LAHIR");
 $reqTanggalLahir= dateToPageCheck($set->getField("TANGGAL_LAHIR"));
 $reqTanggalKawin= dateToPageCheck($set->getField("TANGGAL_KAWIN"));
 $reqKartu= $set->getField("KARTU");
 $reqStatusPns= $set->getField("STATUS_PNS");
 $reqNipPns= $set->getField("NIP_PNS");
 $reqPekerjaan= $set->getField("PEKERJAAN");
 $reqStatusTunjangan= $set->getField("STATUS_TUNJANGAN");
 $reqStatusBayar= $set->getField("STATUS_BAYAR");
 $reqBulanBayar= $set->getField("BULAN_BAYAR");
 $reqStatusSI= $set->getField("STATUS_S_I");
}

$pendidikan= new Pendidikan();
$pendidikan->selectByParams();
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
        url:'suami_istri_json/add',
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
			  mbox.close();
			  document.location.href= "app/loadUrl/app/pegawai_add_suami_istri_data/?reqId=<?=$reqId?>&reqRowId="+rowid;
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
         <li class="collection-item ubah-color-warna">EDIT SUAMI/ISTRI</li>
         <li class="collection-item">

          <div class="row">
            <form id="ff" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNama">Nama Suami/Istri</label>
                  <input type="text" class="easyui-validatebox" required id="reqNama" name="reqNama" <?=$read?> value="<?=$reqNama?>" title="Nama harus diisi" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqKartu">Karis/karsu</label>
                  <input type="text" class="easyui-validatebox" name="reqKartu" id="reqKartu" <?=$read?> value="<?=$reqKartu?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTempatLahir">Tempat Lahir</label>
                  <input type="text" class="easyui-validatebox" id="reqTempatLahir" name="reqTempatLahir" <?=$read?> value="<?=$reqTempatLahir?>" />
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqTanggalLahir">Tgl. Lahir</label>
                  <input id="reqTanggalLahir" type="text" name="reqTanggalLahir"  <?=$disabled?> onKeyDown="return format_date(event,'reqTanggalLahir');" value="<?=$reqTanggalLahir?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqTanggalKawin">Tgl. nikah</label>
                  <input id="reqTanggalKawin" type="text" name="reqTanggalKawin"  <?=$disabled?>  onkeydown="return format_date(event,'reqTanggalKawin');" value="<?=$reqTanggalKawin?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <select <?=$disabled?> name="reqStatusSI" id="reqStatusSI">
                    <option value="1" <? if($reqStatusSI == 1) echo 'selected';?>>nikah</option>
                    <option value="2" <? if($reqStatusSI == 2) echo 'selected';?>>cerai</option>
                    <option value="3" <? if($reqStatusSI == 3) echo 'selected';?>>meninggal</option>
                  </select>
                  <label for="reqStatusSI">Status</label>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqStatusPns">PNS</label>
                  <input type="text" id="reqStatusPns" name="reqStatusPns" value="<?=$reqStatusPns?>"/>
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <label for="reqNipPns">NIP(PNS)</label>
                  <input type="text" class="easyui-validatebox"  name="reqNipPns" <?=$read?> id="reqNipPns" value="<?=$reqNipPns?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12 m6">
                  <select name="reqPendidikanId" id="reqPendidikanId">
                    <? 
                    while($pendidikan->nextRow())
                    {
                      ?>
                      <option value="<?=$pendidikan->getField('pendidikan_id')?>" <? if($reqPendidikanId == $pendidikan->getField('PENDIDIKAN_ID')) echo 'selected';?>><?=$pendidikan->getField('NAMA')?></option>
                      <? 
                    }
                    ?>
                  </select>
                  <label for="reqPendidikanId">Pendidikan</label>
                </div>
                <div class="input-field col s12 m6">
                  <label for="reqPekerjaan">Pekerjaan</label>
                  <input type="text" <?=$read?> class="easyui-validatebox" id="reqPekerjaan" name="reqPekerjaan" value="<?=$reqPekerjaan?>" />
                </div>
              </div>

              <div class="row">
                <div class="input-field col s12">
                  <button class="btn orange waves-effect waves-light" style="font-size:9pt" type="button" id="kembali">Kembali
                    <i class="mdi-navigation-arrow-back left hide-on-small-only"></i>
                  </button>

                  <script type="text/javascript">
                    $("#kembali").click(function() { 
                      document.location.href = "app/loadUrl/app/pegawai_add_suami_istri_monitoring?reqId=<?=$reqId?>";
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