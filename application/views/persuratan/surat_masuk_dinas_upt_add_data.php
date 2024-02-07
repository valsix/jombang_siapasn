<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
if($reqId=="")
{
}
else
{
  $reqMode = 'update';
  $statement= " AND A.SURAT_MASUK_UPT_ID = ".$reqId."";
  $set= new SuratMasukUpt();
  $set->selectByParams(array(), -1, -1, $statement);
  $set->firstRow();
  // echo $set->query;exit;
  $reqTerbaca= $set->getField("TERBACA");
  $reqNomor= $set->getField("NOMOR");
  $reqAgenda= $set->getField("NO_AGENDA");
  $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
  $reqTanggalDiteruskan= dateToPageCheck($set->getField("TANGGAL_DITERUSKAN"));
  $reqTanggalBatas= dateToPageCheck($set->getField("TANGGAL_BATAS"));
  $reqKepada= $set->getField("KEPADA");
  $reqPerihal= $set->getField("PERIHAL");
  $reqSatkerTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
  $reqSatkerAsalId= $set->getField("SATUAN_KERJA_ASAL_ID");

  $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
  $reqSatkerTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");
  $reqStatusKirim= $set->getField("STATUS_KIRIM");

  $tempPerihalInfo= $reqPerihal;
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
$(function(){
    $('#ff').form({
      url:'surat/surat_masuk_upt_json/add',
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
            parent.reloadparenttab();
						<?
						if($reqId == "")
						{
						?>
							top.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
						<?
						}
						else
						{
						?>
							document.location.href= "app/loadUrl/persuratan/surat_masuk_upt_add_data/?reqId="+rowid+"&reqJenis=<?=$reqJenis?>";
						<?
						}
						?>
			     }, 1000));
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

</head>

<body>
  <div id="basic-form" class="section">
    <div class="row">
      <div class="col s12 m12" style="padding-left: 15px;">

        <ul class="collection card">
          <li class="collection-item ubah-color-warna">Usulan Pelayanan <?=$reqJenisNama?></li>
          <li class="collection-item">

            <div class="row">
              <form id="ff" method="post" enctype="multipart/form-data">

                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqSatuanKerjaTujuanNama">Satuan Kerja Dari</label>
                    <input type="text" id="reqSatuanKerjaTujuanNama" disabled value="<?=$reqSatkerAsalNama?>"  />
                  </div>

                  <div class="input-field col s12 m6">
                    <label for="reqKepada">Kepada</label>
                    <input type="hidden" name="reqKepada" value="<?=$reqKepada?>" />
                    <input type="text" id="reqKepada" <?=$read?> value="<?=$reqKepada?>" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12 m6">
                    <label for="reqNomor">Nomor</label>
                    <input class="easyui-validatebox" type="text" name="reqNomor" id="reqNomor" <?=$read?> value="<?=$reqNomor?>" disabled />
                  </div>
                  <div class="input-field col s12 m6">
                    <label for="reqTanggal">Tanggal Surat</label>
                    <input class="easyui-validatebox" type="text" name="reqTanggal" id="reqTanggal" <?=$read?> value="<?=$reqTanggal?>" disabled />
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                    <label for="reqPerihal">Perihal</label>
                    <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
                    <input type="text" id="reqPerihal" <?=$read?> value="<?=$tempPerihalInfo?>" disabled />
                  </div>
                </div>

                <div class="row">
                  <div class="input-field col s12 m12">
                    <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                    <input type="hidden" name="reqId" value="<?=$reqId?>" />
                    <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                    <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                    <?
                    if($reqStatusKirim == "1")
                    {
                      if($reqTerbaca == "1"){}
                      else
                      {
                    ?>
                      <button class="btn blue waves-effect waves-light" style="font-size:9pt" type="button" id="reqberkasditerima">Berkas di terima
                      <i class="mdi-content-inbox left hide-on-small-only"></i>
                      </button>
                    <?
									   }
								    }
                    ?>
                    <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="tambah" onClick="parent.closeparenttab()">Close
                      <i class="mdi-navigation-close left hide-on-small-only"></i>
                    </button>

                    <?
                    // kalau data sudah terbaca dan terkirim maka muncul cek kelengkapan
                    if($reqStatusKirim == "1" && $reqTerbaca == "1")
                    {
                    ?>
                    <button class="btn purple waves-effect waves-light" style="font-size:9pt" type="button" id="reqcekkelengkapanfile">Cek Kelengkapan File
                      <i class="mdi-content-inbox left hide-on-small-only"></i>
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

<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('select').material_select();

    $("#reqcekkelengkapanfile").click(function() {
      document.location.href = "app/loadUrl/persuratan/surat_masuk_dinas_upt_add_pegawai?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
    });
	
    $("#reqberkasditerima").click(function() { 
      var reqSatuanKerjaTujuanNama= "";
      reqSatuanKerjaTujuanNama= $("#reqSatuanKerjaTujuanNama").val();
		
  		mbox.custom({
  		   message: "Apakah Anda Yakin, Akan menerima berkas dari <?=$reqSatkerAsalNama?>. Pastikan dokumen pegawai sudah di terima. ?",
  		   options: {close_speed: 100},
  		   buttons: [
  			   {
  				   label: 'Ya',
  				   color: 'green darken-2',
  				   callback: function() {
  					   var s_url= "surat/surat_masuk_upt_json/baca_surat?reqId=<?=$reqId?>&reqStatusBerkas=3";
  						$.ajax({'url': s_url,'success': function(dataajax){
  							mbox.alert("Surat telah diterima", {open_speed: 500}, interval = window.setInterval(function() 
  							{
  								clearInterval(interval);
  								mbox.close();
  								//$.messager.alert('Info', "Surat telah diterima", 'info');
  								parent.reloadparenttab();
  								document.location.href= "app/loadUrl/persuratan/surat_masuk_dinas_upt_add_data/?reqId=<?=$reqId?>&reqJenis=<?=$reqJenis?>";
  							}, 1000));
  							$(".mbox > .right-align").css({"display": "none"});
  						}});
  					   //console.log('do action for yes answer');
  					   mbox.close();
  				   }
  			   },
  			   {
  				   label: 'Tidak',
  				   color: 'grey darken-2',
  				   callback: function() {
  					   //console.log('do action for no answer');
  					   mbox.close();
  				   }
  			   }
  		   ]
  		});
		
	  });
	
  });

  $('.materialize-textarea').trigger('autoresize');

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>