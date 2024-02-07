<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratKeluarBkd');

$CI =& get_instance();
$CI->checkUserLogin();

$infoid= $reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");

$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;

$statement= "
AND EXISTS
(
	SELECT 1
	FROM
	(
		SELECT SURAT_KELUAR_BKD_ID, SATUAN_KERJA_ASAL_ID FROM persuratan.SURAT_KELUAR_BKD_SEDIAAN GROUP BY SURAT_KELUAR_BKD_ID, SATUAN_KERJA_ASAL_ID
	) XXX
	WHERE XXX.SATUAN_KERJA_ASAL_ID = ".$reqSatuanKerjaId." AND A.SURAT_KELUAR_BKD_ID = XXX.SURAT_KELUAR_BKD_ID
)
AND
( 
	(COALESCE(NULLIF(A.STATUS_KIRIM, ''), NULL) IS NULL AND A.SATUAN_KERJA_TERIMA_SURAT_ID = ".$reqSatuanKerjaId.")
	OR
	(COALESCE(NULLIF(A.STATUS_KIRIM, ''), NULL) IS NOT NULL)
)
AND A.IS_SEDIAAN = '1'
AND A.SURAT_KELUAR_BKD_ID = ".$reqId."
AND B1.SURAT_KELUAR_BKD_SEDIAAN_ID = ".$reqRowId."
";
$set= new SuratKeluarBkd();
$set->selectByParamsSediaan(array(), -1, -1, $statement);
// echo $set->query;exit;
$set->firstRow();
$reqId= $set->getField("SURAT_KELUAR_BKD_ID");
// echo $reqId;exit();

// kembalikan ke surat keluar
if(empty($reqId))
{
	// redirect("app");
	echo '<script language="javascript">';
	echo "parent.closeparenttab()";
	// echo "window.location.href = 'app/loadUrl/persuratan/surat_masuk_sediaan';";
	echo '</script>';
	exit();
	// redirect("app/loadUrl/persuratan/surat_keluar_teknis_add_surat_data/?reqId=".$infoid);
}

$reqId= $set->getField("SURAT_KELUAR_BKD_ID");
$reqAsalSurat= $set->getField("ASAL_SURAT");
$reqTanggalSurat= dateToPageCheck($set->getField("TANGGAL_SURAT"));
$reqTanggalTerima= dateToPageCheck($set->getField("TANGGAL_TERIMA"));
$reqTanggalDisposisi= dateToPageCheck($set->getField("TANGGAL_DISPOSISI"));
$reqNomorSurat= $set->getField("NOMOR_SURAT");
$reqPerihal= $set->getField("PERIHAL");
$reqTerbaca= $set->getField("TERBACA");
$reqSatuanKerjaTujuanAkhirId= $set->getField("SATUAN_KERJA_TUJUAN_AKHIR_ID");
$reqSatuanKerjaTujuanId= $set->getField("SATUAN_KERJA_TUJUAN_ID");
$reqSatuanKerjaTujuanNama= $set->getField("SATUAN_KERJA_TUJUAN_NAMA");
$reqSatuanKerjaIdAksi= $set->getField("SATUAN_KERJA_ID_AKSI");
$reqSatuanKerjaAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
$reqStatusDisposisi= $set->getField("TERDISPOSISI");
$reqIsi= $set->getField("ISI");

$arrstatusdisposisi= [];

if($reqSatuanKerjaTujuanAkhirId == $reqSatuanKerjaIdAksi)
{
	$reqSatuanKerjaTujuanId= $reqSatuanKerjaTujuanNama= "";
	$arrstatusdata= array(
	  array("ID"=>"3", "TEXT"=>"Surat dapat diambil")
	  , array("ID"=>"2", "TEXT"=>"Revisi")
	);
}
else
{
	$arrstatusdata= array(
	  array("ID"=>"1", "TEXT"=>"Teruskan")
	  , array("ID"=>"2", "TEXT"=>"Revisi")
	);
}

foreach($arrstatusdata as $valkey => $valitem) 
{
	$vid= $valitem["ID"];
	$vtext= $valitem["TEXT"];

	$arrdata= [];
    $arrdata["ID"]= $vid;
    $arrdata["TEXT"]= $vtext;
    array_push($arrstatusdisposisi, $arrdata);
}

$tempJudul= "Surat Masuk Sediaan";

$haksimpan= $disabled= "";
if($reqSatuanKerjaId !== $reqSatuanKerjaIdAksi || $reqStatusDisposisi == 3)
{
	$disabled= "disabled";
	$haksimpan= "1";
	$tempJudul.= ", untuk ".$reqSatuanKerjaAsalNama;
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

    	setstatusdisposisi();
    	$("#reqStatusDisposisi").change(function() {
    		setstatusdisposisi();
    	});

    	$("#reqsimpan").click(function() { 
			if($("#ff").form('validate') == false){
				return false;
			}
			
			reqStatusDisposisi= $("#reqStatusDisposisi").val();
			reqTanggalDisposisi= $("#reqTanggalDisposisi").val();

			if(reqStatusDisposisi == "")
			{
				mbox.alert("Pilih status surat terlebih dahulu.", {open_speed: 0});
			}
			else
			{
				statussimpan= "";
				if(reqStatusDisposisi == 2)
				{
					statussimpan= "1";
					$("#reqTanggalDisposisi").val("");
					infotambahan= "revisi";
				}
				else
				{
					reqSatuanKerjaTujuanNama= "<?=$reqSatuanKerjaTujuanNama?>";
					if(reqSatuanKerjaTujuanNama == "")
						infotambahan= "final";
					else
						infotambahan= "diteruskan, kepada "+reqSatuanKerjaTujuanNama;

					reqTanggalDisposisiPanjang= reqTanggalDisposisi.length;
					if(reqTanggalDisposisiPanjang == 10)
					{
						statussimpan= "1";
					}
					else
					{
						mbox.alert("Isikan terlebih dahulu, tanggal diteruskan.", {open_speed: 0});
					}
				}

				if(statussimpan == "1")
				{
					info= "Apakah anda Yakin untuk "+infotambahan+" ?";
					mbox.custom({
					   message: info,
					   options: {close_speed: 100},
					   buttons: [
						   {
							   label: 'Ya',
							   color: 'green darken-2',
							   callback: function() {
								   $("#reqSubmit").click();
								   mbox.close();
							   }
						   },
						   {
							   label: 'Tidak',
							   color: 'grey darken-2',
							   callback: function() {
								   mbox.close();
							   }
						   }
					   ]
					});
				}
			}
		
		});

		$('#ff').form({
            url:'surat/surat_keluar_bkd_json/addteruskan',
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
						document.location.href= "app/loadUrl/persuratan/surat_masuk_sediaan_add_data/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
					}, 1000));
				}

            }
        });

    	$("#reqterimaberkas").click(function() { 

			mbox.custom({
			   message: "Apakah anda yakin terima, surat masuk sediaan.",
			   options: {close_speed: 100},
			   buttons: [
				   {
					   label: 'Ya',
					   color: 'green darken-2',
					   callback: function() {
							$.getJSON("surat/surat_keluar_bkd_json/statusterima/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>",
							function(data){
								// console.log(data);return false;
								mbox.alert(data.PESAN, {open_speed: 500}, interval = window.setInterval(function() 
								{
									clearInterval(interval);
									parent.reloadparenttab();
									document.location.href= "app/loadUrl/persuratan/surat_masuk_sediaan_add_data/?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>";
								}, 1000));
								$(".mbox > .right-align").css({"display": "none"});
							});
							mbox.close();
					   }
				   },
				   {
					   label: 'Tidak',
					   color: 'grey darken-2',
					   callback: function() {
						   mbox.close();
					   }
				   }
			   ]
			});
		});


    });

    function setstatusdisposisi()
    {
    	reqStatusDisposisi= $("#reqStatusDisposisi").val();
    	$("#labeltanggal").show();
    	if(reqStatusDisposisi == 2)
    	{
    		$("#labeltanggal").hide();
    	}
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
  <link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<div id="basic-form" class="section">
		<div class="row">
			<div class="col s12 m10 offset-m1">

				<ul class="collection card">
					<li class="collection-item ubah-color-warna"><?=$tempJudul?> <?=$reqJenisNama?></li>
					<li class="collection-item">

						<?
						// kalau belum tarbaca, aksi untuk terima berkas
						if(empty($reqTerbaca))
						{
						?>
						<div class="row">
							<div class="input-field col s12 m4">
								<label for="reqAsalSurat">Surat Dari</label>
								<input placeholder="" type="text" id="reqAsalSurat" disabled value="<?=$reqAsalSurat?>" />
							</div>
							<div class="input-field col s12 m4">
								<label for="reqTanggalSurat">Tanggal Surat</label>
								<input placeholder="" type="text" id="reqTanggalSurat" disabled value="<?=$reqTanggalSurat?>" />
							</div>
							<div class="input-field col s12 m4">
								<label for="reqNomorSurat">Nomor Surat</label>
								<input placeholder="" type="text" id="reqNomorSurat" disabled value="<?=$reqNomorSurat?>" />
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12 m6">
								<label for="reqPerihal">Perihal</label>
								<input placeholder="" type="text" id="reqPerihal" <?=$read?> value="<?=$reqPerihal?>" disabled />
							</div>
						</div>

						<?
						if(empty($haksimpan))
						{
						?>
						<button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqterimaberkas">Terima Berkas <i class="mdi-content-save left hide-on-small-only"></i>
						</button>
						<?
						}
						?>

						<button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" onClick="parent.closeparenttab()">Close
							<i class="mdi-navigation-close left hide-on-small-only"></i>
						</button>
						<?
						}
						else
						{
						?>
						<form id="ff" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="input-field col s12 m4">
									<label for="reqAsalSurat">Surat Dari</label>
									<input placeholder="" type="text" id="reqAsalSurat" disabled value="<?=$reqAsalSurat?>" />
								</div>
								<div class="input-field col s12 m4">
									<label for="reqTanggalSurat">Tanggal Surat</label>
									<input placeholder="" type="text" id="reqTanggalSurat" disabled value="<?=$reqTanggalSurat?>" />
								</div>
								<div class="input-field col s12 m4">
									<label for="reqNomorSurat">Nomor Surat</label>
									<input placeholder="" type="text" id="reqNomorSurat" disabled value="<?=$reqNomorSurat?>" />
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12 m6">
									<label for="reqPerihal">Perihal</label>
									<input placeholder="" type="text" id="reqPerihal" <?=$read?> value="<?=$reqPerihal?>" disabled />
								</div>
								<div class="input-field col s12 m2">
									<label for="reqTanggalTerima">Di Terima Tanggal</label>
									<input type="text" id="reqTanggalTerima" <?=$read?> value="<?=$reqTanggalTerima?>" disabled />
								</div>
							</div>

							<?
                            $tempUrlFile= "uploadsurat/keluar/".$reqId.".pdf";
                            if(file_exists($tempUrlFile))
							{
                            ?>
                            <div class="file_input input-field col s12 m1" style="padding-bottom: 50px">
                            	<label class="labelupload">
                            		<a href="javascript:void(0)" title="Lihat File" onClick="parent.setload('surat_keluar_add_data_agenda_file?reqId=<?=$reqId?>&reqRowId=<?=$reqRowId?>&reqbackurl=surat_masuk_sediaan_add_data&reqUrlFile=<?=$tempUrlFile?>')">
                            			<i class="mdi-editor-attach-file" style="font-family: "Roboto",sans-serif,Material-Design-Icons !important; font-size: 14px !important;">Lihat File</i>
                            		</a>
                            	</label>
                            </div>

                            <div id="file_input_text_div" class=" input-field col s12 m10">
                            	<input class="file_input_text" type="text" disabled readonly id="file_input_text" />
                            	<label for="file_input_text"></label>
                            </div>
                            <?
                        	}
                        	?>

							<div class="input-field col s12 m12">
								<select id="reqStatusDisposisi" name="reqStatusDisposisi" <?=$disabled?> >
									<option value="" <? if($reqStatusDisposisi=="") echo 'selected';?>>Pilih salah satu</option>
									<?
									foreach($arrstatusdisposisi as $valkey => $valitem) 
                                    {
                                    	$vid= $valitem["ID"];
                                    	$vtext= $valitem["TEXT"];
									?>
									<option value="<?=$vid?>" <? if($reqStatusDisposisi == $vid) echo 'selected';?>><?=$vtext?></option>
									<?
									}
									?>
								</select>
								<label for="reqStatusDisposisi">Status Surat</label>
							</div>

							<?
							$infodisplay= "";
							$infotanggal= "Diteruskan";
							if($reqSatuanKerjaTujuanAkhirId == $reqSatuanKerjaIdAksi)
							{
								$infodisplay= "none";
								$infotanggal= "Di valid";
							}
							?>
							<div class="row" id="labeltanggal">
								<div class="input-field col s12 m4">
									<label for="reqTanggalDisposisi">Tanggal <?=$infotanggal?></label>
									<input autocomplete="on" placeholder="" class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalDisposisi" id="reqTanggalDisposisi" <?=$read?> value="<?=$reqTanggalDisposisi?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalDisposisi');" <?=$disabled?> />
								</div>
								<div class="input-field col s12 m4" style="display: <?=$infodisplay?>">
									<label for="reqPerihal">Diteruskan Kepada</label>
									<input type="hidden" name="reqSatuanKerjaTujuanId" value="<?=$reqSatuanKerjaTujuanId?>" />
									<input type="hidden" name="reqSatuanKerjaTujuanNama" value="<?=$reqSatuanKerjaTujuanNama?>" />
									<input placeholder="" type="text" id="reqSatuanKerjaTujuanNama" <?=$read?> value="<?=$reqSatuanKerjaTujuanNama?>" disabled />
								</div>
	                        </div>

							<div class="row">
								<div class="input-field col s12 m12">
									<label for="reqIsi">Catatan / Keterangan</label>
									<input autocomplete="on" placeholder="" type="text" id="reqIsi" name="reqIsi" <?=$read?> value="<?=$reqIsi?>" <?=$disabled?> />
								</div>
							</div>

							<input type="hidden" name="reqId" value="<?=$reqId?>" />
							<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />

							<?
							if(empty($disabled))
							{
							?>
							<button type="submit" style="display:none" id="reqSubmit"></button>
							<button class="btn waves-effect waves-light green" style="font-size:9pt" type="button" id="reqsimpan">
								Simpan
								<i class="mdi-content-save left hide-on-small-only"></i>
							</button>
							<?
							}
							?>

							<button class="btn red waves-effect waves-light" style="font-size:9pt" type="button" onClick="parent.closeparenttab()">Close
								<i class="mdi-navigation-close left hide-on-small-only"></i>
							</button>

						</form>
						<?
						}
						?>

					</li>
				</ul>

			</div>
		</div>
	</div>

	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>

	<!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
	<link href="lib/materializetemplate/js/plugins/prism/prism.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/js/plugins/perfect-scrollbar/perfect-scrollbar.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/js/plugins/chartist-js/chartist.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<!--dropify-->
	<script type="text/javascript" src="lib/materializetemplate/js/plugins/dropify/js/dropify.min1.js"></script>
	<link href="lib/materializetemplate/js/plugins/dropify/css/dropify.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<script type="text/javascript">
	  $(document).ready(function() {
	    $('select').material_select();
	  });

	  $('.materialize-textarea').trigger('autoresize');
	</script>

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>

	<style type="text/css">
	.file_input_div {
	  margin-top: -30px;
	}

	.labelupload {
	  margin-left: -12px;
	}

	.file_input {
	}

	#file_input_text_div {
	}

	.none {
	  display: none;
	}

	</style>

	<!-- tambahan lib cek tanggal -->
	<script src="lib/moment/moment-with-locales.js"></script>
	<script type="text/javascript">
		$('#reqTanggalDisposisi').keyup(function() {
			var reqtglawal= $('#reqTanggalSurat').val();
			var reqtglakhir= $('#reqTanggalDisposisi').val();
			var checktglawal  = moment(reqtglawal , 'DD-MM-YYYY', true).isValid();
			var checktglakhir  = moment(reqtglakhir , 'DD-MM-YYYY', true).isValid();
			if(checktglawal == true && checktglakhir == true)
			{
				var tglawal = moment(reqtglawal, 'DD-MM-YYYY'); 
				var tglakhir = moment(reqtglakhir, 'DD-MM-YYYY');

				if (tglakhir.isSameOrAfter(tglawal)) {} 
				else 
				{
					$('#reqTanggalDisposisi').val(reqtglawal);
				}
			}

		});
	</script>
	
</body>