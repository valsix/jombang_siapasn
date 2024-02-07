<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('UserGroup');
$this->load->model('AksesAppSimpeg');
$this->load->model('AksesAppAbsensi');

$set= new UserGroup();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParamsMonitoring(array("A.USER_GROUP_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
	$reqAksesAppSimpegId= $set->getField("AKSES_APP_SIMPEG_ID");
	$reqAksesAppAbsensiId= $set->getField("AKSES_APP_ABSENSI_ID");
	$reqTampil= $set->getField("TAMPIL_RESET");
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
			$("#reqAksesAppSimpegId").change(function() {
				var id= $(this).attr('id');
				var value= $(this).val();
				id= id.replace("req", "");
				
				if(value == '1' || value == '2' || value == '3' || value == '')
					$('.toggle'+id).css({"display":"none"});
				else
					$('.toggle'+id).css({"display":"inline"});
			});
			
			$('#ff').form({
				url:'user_group_json/add',
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
         					document.location.href= "app/loadUrl/app/user_group_add/?reqId="+rowid;
         					top.frames['mainFrame'].location.reload();
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}

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

				<div class="ubah-color-warna white-text" style="padding: 1em">Tambah User Group</div>
				<div id="area-form-inner">
					<form id="ff" class="form-horizontal" role="form" method="post"  novalidate enctype="multipart/form-data">
						

						<div class="row">
							<label for="reqNama" class="col s12 m2 label-control">Nama</label>
							<div class="input-field col s12 m10">
								<input name="reqNama" class="easyui-validatebox" required size="40" type="text" value="<?=$reqNama?>" />
							</div>
						</div>

						<div class="row">
							<label for="" class="col s12 m2 label-control">Pengaturan</label>
							<div class="input-field col s12 m10">
								<?
								$tempGroupId= 1;
								$tempGroupIdTable= "AKSES_APP_SIMPEG";
								?>
								<select name="reqAksesAppSimpegId" id="reqAksesAppSimpegId" class="required" title="Akses Simpeg harus dipilih" />
								<option value="">-- Pilih Nama Group Akses --</option>
								<?
								$set= new AksesAppSimpeg();
								$set->selectByParams(array());
								while($set->nextRow()){
									?>
									<option value="<?=$set->getField("AKSES_APP_SIMPEG_ID")?>" <? if($set->getField("AKSES_APP_SIMPEG_ID") == $reqAksesAppSimpegId) echo "selected"; ?>><?=$set->getField("NAMA")?></option>
									<?
								}
								?>
							</select> 
							<a onClick="OpenDHTML('app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$tempGroupId?>&reqTable=<?=$tempGroupIdTable?>');" ><img src="images/tree-add.png" width="15" height="15"/></a>
							<?
							$display = "";
							/*if($reqAksesAppSimpegId == 1 || $reqAksesAppSimpegId == 2 || $reqAksesAppSimpegId == 3 || $reqAksesAppSimpegId == "")
								$display= "display:none";*/
							?>
							<a class="toggleAksesAppSimpegId" style=" <?=$display?>" <?php /*?><?=$display?><?php */?> onClick="OpenDHTML('app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$tempGroupId?>&reqTable=<?=$tempGroupIdTable?>&reqRowId='+$('#reqAksesAppSimpegId').val());" ><img src="images/tree-edit.png" width="15" height="15"/></a>
						</div>
					</div>

					<div class="row">
							<label for="" class="col s12 m2 label-control">Pengaturan Absensi</label>
							<div class="input-field col s12 m10">
								<?
								$tempGroupId= 2;
								$tempGroupIdTable= "AKSES_APP_ABSENSI";
								?>
								<select name="reqAksesAppAbsensiId" id="reqAksesAppAbsensiId" class="required" title="Akses Absensi harus dipilih" />
								<option value="">-- Pilih Nama Group Akses --</option>
								<?
								$set= new AksesAppAbsensi();
								$set->selectByParams(array());
								while($set->nextRow()){
									?>
									<option value="<?=$set->getField("AKSES_APP_ABSENSI_ID")?>" <? if($set->getField("AKSES_APP_ABSENSI_ID") == $reqAksesAppAbsensiId) echo "selected"; ?>><?=$set->getField("NAMA")?></option>
									<?
								}
								?>
							</select> 
							<a onClick="OpenDHTML('app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$tempGroupId?>&reqTable=<?=$tempGroupIdTable?>');" ><img src="images/tree-add.png" width="15" height="15"/></a>
							<?
							$display = "";
							/*if($reqAksesAppAbsensiId == 1 || $reqAksesAppAbsensiId == 2 || $reqAksesAppAbsensiId == 3 || $reqAksesAppAbsensiId == "")
								$display= "display:none";*/
							?>
							<a class="toggleAksesAppAbsensiId" style=" <?=$display?>" <?php /*?><?=$display?><?php */?> onClick="OpenDHTML('app/loadUrl/app/akses_administrasi_add?reqId=<?=$reqId?>&reqMenuGroupId=<?=$tempGroupId?>&reqTable=<?=$tempGroupIdTable?>&reqRowId='+$('#reqAksesAppAbsensiId').val());" ><img src="images/tree-edit.png" width="15" height="15"/></a>
						</div>
					</div>

					<div class="row">
						<label for="reqTampilReset" class="col s12 m2 label-control">Tampil Reset Password</label>
						<div class="input-field col s12 m10" style="margin-top: 8px">
							<input type="radio"  name="reqTampil" id="reqYa" value="1" <?if ($reqTampil== 1) echo 'checked'?>  >  
							<label for="reqYa">Ya</label>
							<input type="radio"  name="reqTampil" id="reqTidak" <?if($reqTampil== "") echo 'checked'?>  value="">  
							<label for="reqTidak">Tidak</label>
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
</div>
<script>


	function OpenDHTML(url)
	{
		document.location.href= url;
	}
</script>
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