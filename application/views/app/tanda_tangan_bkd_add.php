<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('TandaTanganBkd');
$this->load->model('Pangkat');

$pangkat= new Pangkat();
$set= new TandaTanganBkd();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("TANDA_TANGAN_BKD_ID"=>$reqId));
	$set->firstRow();
	$reqMulaiBerlaku= dateToPageCheck($set->getField("MULAI_BERLAKU"));
	$reqAkhirBerlaku= dateToPageCheck($set->getField("AKHIR_BERLAKU"));
	$reqNoNomenklaturKab= $set->getField("NO_NOMENKLATUR_KAB");
	$reqNoNomenklaturBkd= $set->getField("NO_NOMENKLATUR_BKD");
	$reqNama= $set->getField("NAMA");
	$reqPltJabatan= $set->getField("PLT_JABATAN");
	$reqNamaPejabat= $set->getField("NAMA_PEJABAT");
	$reqKodePangkat= $set->getField("KODE_PANGKAT");
	$reqPangkat= $set->getField("PANGKAT");
	$reqNip= $set->getField("NIP");
	$reqPejabatPenetap= $set->getField("PEJABAT_PENETAP");
	$reqPejabatPenetapLengkap= $set->getField("PEJABAT_PENETAP_LENGKAP");
	$reqPangkatId= $set->getField("PANGKAT_ID");
	$reqGolRuang= $reqPangkatId."-".$reqKodePangkat;
}

$pangkat->selectByParams(array());

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
				url:'tanda_tangan_json/add',
				onSubmit:function(){
					var reqPangkatId= "";
					reqPangkatId= $("#reqPangkatId").val();
					
					if(reqPangkatId == "")
					{
						$.messager.alert('Info', "Lengkapi data terlebih dahulu", 'info');
						return false;
					}
					return $(this).form('validate');
				},
				success:function(data){
					// alert(data);return false;
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
         					document.location.href= "app/loadUrl/app/tanda_tangan_bkd_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
         			top.frames['mainFrame'].location.reload();
         		}
         	});
			
			$("#reqGolRuang").change(function(){
				reqGolRuang= $("#reqGolRuang").val();
				reqGolRuang= String(reqGolRuang);
				reqGolRuang= reqGolRuang.split('-');
				$("#reqPangkatId").val(reqGolRuang[0]);
				$("#reqKodePangkat").val(reqGolRuang[1]);
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

				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah Tanda Tangan Bkd</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="" class="col s12 m2 label-control">Mulai Berlaku</label>
							<div class="input-field col s12 m10">
								<input name="reqMulaiBerlaku" class="easyui-datebox" required value="<?=$reqMulaiBerlaku?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Akhir Berlaku</label>
							<div class="input-field col s12 m10">
								<input name="reqAkhirBerlaku" class="easyui-datebox" value="<?=$reqAkhirBerlaku?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">No Nomenklatur Kab</label>
							<div class="input-field col s12 m10">
								<input name="reqNoNomenklaturKab" maxlength="3" class="form-control easyui-validatebox" type="text" required value="<?=$reqNoNomenklaturKab?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">No Nomenklatur BKD</label>
							<div class="input-field col s12 m10">
								<input name="reqNoNomenklaturBkd" maxlength="2" class="form-control easyui-validatebox" type="text" required value="<?=$reqNoNomenklaturBkd?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Nama</label>
							<div class="input-field col s12 m10">
								<input name="reqNama" class="form-control easyui-validatebox" type="text" required value="<?=$reqNama?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">PLT Jabatan</label>
							<div class="input-field col s12 m10">
								<select name="reqPltJabatan">
									<option value=""></option>
									<option value="PLT" <? if($reqPltJabatan == "PLT") echo "selected"?>>PLT</option>
								</select>
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Nama Pejabat</label>
							<div class="input-field col s12 m10">
								<input name="reqNamaPejabat" class="form-control easyui-validatebox" type="text" required value="<?=$reqNamaPejabat?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Kode Pangkat</label>
							<div class="input-field col s12 m10">
								<select id="reqGolRuang" name="reqGolRuang">
									<option value=""></option>
									<? 
									while($pangkat->nextRow())
									{
										?>
										<option value="<?=$pangkat->getField('PANGKAT_ID')."-".$pangkat->getField('KODE')?>" <? if($reqGolRuang == $pangkat->getField('PANGKAT_ID')."-".$pangkat->getField('KODE')) echo 'selected';?>><?=$pangkat->getField('KODE')?></option>
										<? 
									}
									?>
								</select>
								<input name="reqPangkatId" id="reqPangkatId" type="hidden" required value="<?=$reqPangkatId?>" />
								<input name="reqKodePangkat" id="reqKodePangkat" type="hidden" value="<?=$reqKodePangkat?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Pangkat</label>
							<div class="input-field col s12 m10">
								<input name="reqPangkat" class="form-control easyui-validatebox" type="text" required value="<?=$reqPangkat?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">NIP</label>
							<div class="input-field col s12 m10">
								<input name="reqNip" class="form-control easyui-validatebox" type="text" required value="<?=$reqNip?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Pejabat Penetap</label>
							<div class="input-field col s12 m10">
								<input name="reqPejabatPenetap" class="form-control easyui-validatebox" type="text" required value="<?=$reqPejabatPenetap?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Pejabat Penetap Lengkap</label>
							<div class="input-field col s12 m10">
								<input name="reqPejabatPenetapLengkap" class="form-control easyui-validatebox" type="text" required value="<?=$reqPejabatPenetapLengkap?>" />
							</div>
						</div>

						<div class="row">
							<div class="input-field col s12 m3 offset-m2">
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
								<input type="submit" name="reqSubmit"  class="btn green" value="Submit" />
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script> -->
	<script type="text/javascript" src="lib/materializetemplate/js/my-materialize.min.js"></script>
	<script src="lib/AdminLTE-2.4.0-rc/dist/js/ubah-skin.js"></script>
	<script type="text/javascript">
		$(document).ready( function () {
			$('select').material_select();
		});
	</script>
</body>
</html>