<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('InformasiKategori');
$this->load->model('Informasi');

$set= new Informasi();

$informasi_kategori= new InformasiKategori();
$informasi_kategori->selectByParams();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("INFORMASI_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
	$reqKeterangan= $set->getField("KETERANGAN");
	$reqInformasiKategoriId = $set->getField("INFORMASI_KATEGORI_ID");
	$reqTanggalAwal= dateToPageCheck($set->getField("TANGGAL_AWAL"));
	$reqTanggalAkhir= dateToPageCheck($set->getField("TANGGAL_AKHIR"));
	$reqLinkFileTemp= $set->getField("LINK_FILE");
	// $reqStatus= $set->getField("STATUS_NAMA");
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Untitled Document</title>
	<base href="<?=base_url()?>" />

	<link rel="stylesheet" type="text/css" href="css/gaya.css">
	<link rel="stylesheet" href="css/admin.css" type="text/css">

	<!-- BOOTSTRAP -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->

	<!-- <script src="js/jquery-1.10.2.min.js"></script> -->
	<!-- <script src="lib/bootstrap/js/jquery.min.js"></script> -->
	<!-- <script src="lib/bootstrap/js/bootstrap.js"></script> -->
	<!-- <link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet"> -->

	<!-- MATERIAL CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

  	<script type="text/javascript" src="lib/easyui/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'informasi_data_json/add',
				onSubmit:function(){
					return $(this).form('validate');
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
         					document.location.href= "app/loadUrl/app/informasi_data_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
					// top.parent.parent.parent.closePopup(data);
					top.frames['mainFrame'].location.reload();
				}
			});

		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<!-- <link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->

	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
	<script src="lib/ckeditor/ckeditor.js"></script>
</head>

<body>

	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah Informasi</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqInformasiKategori" class="col s12 m2 label-control">Informasi Kategori</label>
							<div class="input-field col s12 m10">
								<select id="reqInformasiKategori" name="reqInformasiKategori" class="form-control" style="height: 33px;">
									<?
									while($informasi_kategori->nextRow())
									{
										?>
										<option value="<?=$informasi_kategori->getField("INFORMASI_KATEGORI_ID")?>" <? if($informasi_kategori->getField("INFORMASI_KATEGORI_ID")==$reqInformasiKategoriId) echo ' selected'?>><?=$informasi_kategori->getField("NAMA")?></option>
										<?
									}
									?>
								</select>
							</div>
						</div>

						<div class="row">
							<label for="reqNama" class="col s12 m2 label-control">Nama</label>
							<div class="input-field col s12 m10">
								<input name="reqNama" id="reqNama" class="" type="text" required value="<?=$reqNama?>" />
								<!-- easyui-validatebox -->
							</div>
						</div>
                        
                        <div class="row">
							<label for="reqNama" class="col s12 m2 label-control">Keterangan</label>
							<div class="input-field col s12 m10">
								<textarea name="reqKeterangan" id="reqKeterangan" style="width:100%; height:100%"><?=$reqKeterangan?></textarea>
							</div>
						</div>
                        
                        <div class="row">
							<label for="reqTanggalAwal" class="col s12 m2 label-control">Tanggal Awal</label>
							<div class="input-field col s12 m10">
                  				<input required data-options="validType:'dateValidPicker'" style="width:20%" type="text" name="reqTanggalAwal" id="reqTanggalAwal"  value="<?=$reqTanggalAwal?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAwal');"/>
								<!-- easyui-validatebox -->
							</div>
						</div>
                        
                        <div class="row">
							<label for="reqTanggalAkhir" class="col s12 m2 label-control">Tanggal Akhir</label>
							<div class="input-field col s12 m10">
                  				<input required data-options="validType:'dateValidPicker'" style="width:20%" type="text" name="reqTanggalAkhir" id="reqTanggalAkhir"  value="<?=$reqTanggalAkhir?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalAkhir');"/>
								<!-- easyui-validatebox -->
							</div>
						</div>
                        
                        <div class="row">
							<label for="reqFile" class="col s12 m2 label-control">UPLOAD FILE</label>
							<div class="input-field col s12 m10">
                                <input id="reqLinkFile" name="reqLinkFile" type="file" <?php /*?>accept="jpg|jpeg|png"<?php */?> value="" maxlength="1" />
                                <?
                                if($reqLinkFileTemp == ""){}
                                else
                                {
                                ?>
                                <input type="hidden" name="reqLinkFileTemp" value="<?=$reqLinkFileTemp?>" />
               					<br />temp : <?=$reqLinkFileTemp?>
               					<?
               					}
               					?>
							</div>
						</div>

						<div class="input-field col s12 m3 offset-m2">
							<input type="hidden" name="reqId" value="<?=$reqId?>" />
							<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
							<input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
	<script>
        // Replace the <textarea id="reqKeterangan"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'reqKeterangan');
        //config.extraPlugins = 'tab';
    </script>

	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});
	</script>
</body>
</html>