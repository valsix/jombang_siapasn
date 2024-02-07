<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/SuratMasukBkdDisposisi');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;

if($reqId==""){}
else
{
   $reqMode = 'update';
   $statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
   $set= new SuratMasukBkdDisposisi();
   $set->selectByParamsDataSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
   $set->firstRow();
   //echo $set->query;exit;
   // print_r($set);
   $reqRowId= $set->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
   $reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
   $reqSatuanKerjaDiteruskanKepadaId= $set->getField("SATUAN_KERJA_DITERUSKAN_ID");
   $reqSatuanKerjaDiteruskanKepada= $set->getField("SATUAN_KERJA_TUJUAN_DITERUSKAN_NAMA");
   $reqNomor= $set->getField("NOMOR");
   $reqNomorAgenda= $set->getField("NO_AGENDA");
   $reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
   $reqPerihal= $set->getField("PERIHAL");
   $reqTerdisposisi= $set->getField("TERDISPOSISI");
   $reqBatasSatuanKerjaCariId= $set->getField("BATAS_SATUAN_KERJA_CARI_ID");
   $reqTanggalDisposisi= $set->getField("TANGGAL_TERIMA");
   $reqIsi= $set->getField("ISI");
   
   $reqTerbaca= $set->getField("TERBACA");
   $reqStatusKelompokPegawaiUsul= $set->getField("STATUS_KELOMPOK_PEGAWAI_USUL");
}

//if($reqTanggalDisposisi == "")
//$reqTanggalDisposisi= $tanggalHariIni;

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
            url:'surat/surat_masuk_bkd_disposisi_json/add',
            onSubmit:function(){
                if($(this).form('validate')){}
                    else
                    {
                        $.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
                        return false;
                    }
                },
                success:function(data){
                //alert(data);return false;
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
						
						<?
						if($reqId == "")
						{
							?>
							top.location.href= "app/loadUrl/persuratan/surat_masuk_add/?reqId=<?=$reqId?>";
							<?
						}
						else
						{
							?>
							document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data/?reqId=<?=$reqId?>";
							<?
						}
						?>
						
						
					}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
				}

            }
        });
		
		$('input[id^="reqSatuanKerjaDiteruskanKepada"]').autocomplete({
        source:function(request, response){
        var id= this.element.attr('id');
        var replaceAnakId= replaceAnak= urlAjax= "";

        if (id.indexOf('reqSatuanKerjaDiteruskanKepada') !== -1)
        {
          var element= id.split('reqSatuanKerjaDiteruskanKepada');
          var indexId= "reqSatuanKerjaDiteruskanKepadaId"+element[1];
          urlAjax= "satuan_kerja_json/namajabatan?reqId=<?=$reqBatasSatuanKerjaCariId?>";
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
                return {desc: element['desc'], id: element['id'], label: element['label'], satuan_kerja: element['satuan_kerja'], eselon_id: element['eselon_id'], eselon_nama: element['eselon_nama']};
              });
              response(array);
            }
          }
        })
      },
      focus: function (event, ui) 
      { 
        var id= $(this).attr('id');
        if (id.indexOf('reqSatuanKerjaDiteruskanKepada') !== -1)
        {
          var element= id.split('reqSatuanKerjaDiteruskanKepada');
          var indexId= "reqSatuanKerjaDiteruskanKepadaId"+element[1];
		  //$("#reqSatker").val(ui.item.satuan_kerja).trigger('change');
		  //$("#reqEselonId").val(ui.item.eselon_id).trigger('change');
		  //$("#reqEselonText").val(ui.item.eselon_nama).trigger('change');
        }
        
		//statusht= ui.item.statusht;
         $("#"+indexId).val(ui.item.id).trigger('change');
       },
       autoFocus: true
       }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        //return
        return $( "<li>" )
        .append( "<a>" + item.desc + "</a>" )
        .appendTo( ul );
      };
	  
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
                <li class="collection-item ubah-color-warna">Usulan Pelayanan <?=$reqJenisNama?></li>
                <li class="collection-item">

                  <div class="row">
                    <form id="ff" method="post" enctype="multipart/form-data">
                    	
                        <?
						if($reqId == ""){}
						else
						{
                        ?>
                        <div class="row">
                          <div class="input-field col s12 m4">
                            <label for="reqSatuanKerjaAsalNama">Surat Dari</label>
                            <input type="text" id="reqSatuanKerjaAsalNama" disabled value="<?=$reqSatkerAsalNama?>" />
                          </div>
                          <div class="input-field col s12 m4">
                            <label for="reqTanggal">Tanggal Surat</label>
                            <input type="text" id="reqTanggal" disabled value="<?=$reqTanggal?>" />
                          </div>
                          <div class="input-field col s12 m4">
                          	<label for="reqNomor">Nomor Surat</label>
                            <input type="text" id="reqNomor" disabled value="<?=$reqNomor?>" />
                            </div>
                        </div>
                        
                        <div class="row">
                          <div class="input-field col s12 m6">
                          	<label for="reqPerihal">Perihal</label>
                            <input type="hidden" name="reqPerihal" value="<?=$reqPerihal?>" />
                            <input type="text" id="reqPerihal" <?=$read?> value="<?=$reqPerihal?>" disabled />
                          </div>
                          
                          <div class="input-field col s12 m6">
                          	<input type="hidden" name="reqTanggalDisposisi" id="reqTanggalDisposisi" <?=$read?> value="<?=datetimeToPage($reqTanggalDisposisi, "datetime")?>" />
                            <label for="reqTanggal">Di Terima Tanggal</label>
                            <input type="text" id="reqTanggal" <?=$read?> value="<?=datetimeToPage($reqTanggalDisposisi,"date")?>" disabled />
                            <?php /*?><input required class="easyui-validatebox" type="text" name="reqTanggalDisposisi" id="reqTanggalDisposisi" <?=$read?> value="<?=$reqTanggalDisposisi?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalDisposisi');"/><?php */?>
                            </div>
                        </div>
                        <?
						}
                        ?>
                        
                        <?
						if($reqStatusKelompokPegawaiUsul == "1")
						{
							if($reqTerbaca == "1")
							{
                        ?>
                        <div class="row">
                          <div class="input-field col s12 m6">
                            <label for="reqNomorAgenda">Nomor Agenda</label>
                            <input type="hidden" name="reqNomorAgenda" value="<?=$reqNomorAgenda?>" />
                            <input type="text" id="reqNomorAgenda" value="<?=$reqNomorAgenda?>" disabled />
                          </div>
                          
                          <div class="input-field col s12 m6">
                          	<label for="reqSatuanKerjaDiteruskanKepada">Diteruskan Kepada</label>
                            <input type="text" id="reqSatuanKerjaDiteruskanKepada" name="reqSatuanKerjaDiteruskanKepada" <?=$read?> value="<?=$reqSatuanKerjaDiteruskanKepada?>" class="easyui-validatebox" required />
                            <input type="hidden" name="reqSatuanKerjaDiteruskanKepadaId" id="reqSatuanKerjaDiteruskanKepadaId" value="<?=$reqSatuanKerjaDiteruskanKepadaId?>" />
                          </div>
                            
                        </div>
                        
                        <div class="row">
                          <div class="input-field col s12">
                          	<textarea name="reqIsi" id="reqIsi" required class="easyui-validatebox materialize-textarea"><?=$reqIsi?></textarea>
                  			<label for="reqIsi">Isi Disposisi</label>
                          </div>
                        </div>
                        <?
							}
							else
							{
						?>
                        <?
							}
						}
                        ?>
                        
                        <div class="row">
                            <div class="input-field col s12 m12">
                                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
                                <input type="hidden" name="reqId" value="<?=$reqId?>" />
                                <input type="hidden" name="reqJenis" value="<?=$reqJenis?>" />
                                <input type="hidden" name="reqMode" value="<?=$reqMode?>" />
                                <?
								if($reqTerdisposisi == "1"){}
								else
								{
								?>
                                <button type="submit" style="display:none" id="reqSubmit"></button>
                                <button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">Simpan
                                    <i class="mdi-content-save left hide-on-small-only"></i>
                                </button>
                                <?
									if($reqSatuanKerjaDiteruskanKepadaId == ""){}
									else
									{
                                ?>
                                <button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" id="reqkirim">Kirim
                                    <i class="mdi-content-forward left hide-on-small-only"></i>
                                </button>
                                <?
									}
								}
                                ?>
                            </div>
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
	
	$("#reqkirim").click(function() { 
		var reqSatuanKerjaDiteruskanKepada= "";
		reqSatuanKerjaDiteruskanKepada= $("#reqSatuanKerjaDiteruskanKepada").val();
		$.messager.confirm('Konfirmasi', "Apakah Anda Yakin, Kirim Ke "+reqSatuanKerjaDiteruskanKepada+" ?",function(r){
			if (r){
				$.getJSON("surat/surat_masuk_bkd_disposisi_json/statuskirim/?reqRowId=<?=$reqRowId?>",
				function(data){
					$.messager.alert('Info', data.PESAN, 'info');
					document.location.href= "app/loadUrl/persuratan/surat_masuk_add_data/?reqId=<?=$reqId?>";
				});
			}
		});
	  //document.location.href = "app/loadUrl/app/pegawai_add_anak_monitoring?reqId=<?=$reqId?>";
	});
	
});

  $('.materialize-textarea').trigger('autoresize');
  
  $('#reqNomorAgenda').bind('keyup paste', function(){
   this.value = this.value.replace(/[^0-9]/g, '');
 });

</script>

<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

</body>
</html>