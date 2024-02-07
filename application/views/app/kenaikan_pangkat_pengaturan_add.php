<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('PengaturanKenaikanPangkat');

$set= new PengaturanKenaikanPangkat();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("A.PENGATURAN_KENAIKAN_PANGKAT_ID"=>$reqId));
	$set->firstRow();
	$reqTanggalPeriode= dateToPageCheck($set->getField("TANGGAL_PERIODE"));
	$reqTanggalBatasAwalUsul= dateToPageCheck($set->getField("TANGGAL_BATAS_AWAL_USUL"));
	$reqTanggalBatasAkhirUsulStruktural= dateToPageCheck($set->getField("TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL"));
	$reqTanggalBatasAkhirUsulNonTigaBawah= dateToPageCheck($set->getField("TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH"));
	$reqTanggalBatasAkhirUsulNonEmpatAtas= dateToPageCheck($set->getField("TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS"));
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

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">

	<!-- BOOTSTRAP -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->

	<!--<script src="js/jquery-1.10.2.min.js"></script>-->
	<!-- <script src="lib/bootstrap/js/jquery.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.js"></script>
	<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet"> -->

	<!-- MATERIAL CORE CSS-->    
	<link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
	<link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

	<link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

	<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'pengaturan_kenaikan_pangkat_json/add',
				onSubmit:function(){
					return $(this).form('validate');
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
         					document.location.href= "app/loadUrl/app/kenaikan_pangkat_pengaturan_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
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
</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">Pengaturan Kenaikan Pangkat</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqNama" class="col s12 m6 label-control">Periode</label>
							<div class="input-field col s12 m2">
								<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalPeriode" id="reqTanggalPeriode" value="<?=$reqTanggalPeriode?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalPeriode');"/>
							</div>
						</div>

						<div class="row">
							<label for="reqTanggalBatasAwalUsul" class="col s12 m6 label-control">Batas Awal Usul</label>
							<div class="input-field col s12 m2">
								<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalBatasAwalUsul" id="reqTanggalBatasAwalUsul" value="<?=$reqTanggalBatasAwalUsul?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalBatasAwalUsul');"/>
							</div>
						</div>

						<!-- <div class="row">
							<label for="reqTanggalBatasAkhirUsulStruktural" class="col s12 m6 label-control">Batas Akhir Usul Struktural</label>
							<div class="input-field col s12 m2">
								<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalBatasAkhirUsulStruktural" id="reqTanggalBatasAkhirUsulStruktural" value="<?=$reqTanggalBatasAkhirUsulStruktural?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalBatasAkhirUsulStruktural');"/>
							</div>
						</div> -->

						<div class="row">
							<label for="reqTanggalBatasAkhirUsulNonTigaBawah" class="col s12 m6 label-control">Batas Akhir Usul Non-Struktural (Gol III/d kebawah)</label>
							<div class="input-field col s12 m2">
								<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalBatasAkhirUsulNonTigaBawah" id="reqTanggalBatasAkhirUsulNonTigaBawah" value="<?=$reqTanggalBatasAkhirUsulNonTigaBawah?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalBatasAkhirUsulNonTigaBawah');"/>
							</div>
						</div>

						<div class="row">
							<label for="reqTanggalBatasAkhirUsulNonEmpatAtas" class="col s12 m6 label-control">Batas Akhir Usul Non-Struktural (Gol IV/a keatas)</label>
							<div class="input-field col s12 m2">
								<input required class="easyui-validatebox" data-options="validType:'dateValidPicker'" type="text" name="reqTanggalBatasAkhirUsulNonEmpatAtas" id="reqTanggalBatasAkhirUsulNonEmpatAtas" value="<?=$reqTanggalBatasAkhirUsulNonEmpatAtas?>" maxlength="10" onKeyDown="return format_date(event,'reqTanggalBatasAkhirUsulNonEmpatAtas');"/>
							</div>
						</div>

						<div class="row">
							<div class="input-field col s12 m3 offset-m2">
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
								<input type="submit" name="reqSubmit" class="btn green" value="Simpan" />
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});
	</script>
</body>
</html>