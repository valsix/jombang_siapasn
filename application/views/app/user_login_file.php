<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('UserLogin');

$reqId= $this->input->get("reqId");

if(!empty($reqId))
{
	$set= new UserLogin();
	$set->selectByParamsMonitoring(array("A.USER_LOGIN_ID"=>$reqId));
	$set->firstRow();
	$reqLoginUser= $set->getField("LOGIN_USER");

	$arrpegawaifileuser=[];
	$set= new UserLogin();
	$set->selectfileuser(array(), -1,-1, $reqId);
	while($set->nextRow())
	{
		$arrdata= [];
		$arrdata["ID_ROW"]= $set->getField("ID_ROW");
		$arrdata["STATUS"]= $set->getField("STATUS");
		$arrdata["KATEGORI_FILE_ID"]= $set->getField("KATEGORI_FILE_ID");
		$arrdata["KATEGORI_FILE_NAMA"]= $set->getField("KATEGORI_FILE_NAMA");
		$arrdata["RIWAYAT_TABLE"]= $set->getField("RIWAYAT_TABLE");
		$arrdata["RIWAYAT_ID"]= $set->getField("RIWAYAT_ID");
		$arrdata["RIWAYAT_FIELD"]= $set->getField("RIWAYAT_FIELD");
		$arrdata["INFO_DATA"]= $set->getField("INFO_DATA");
		$arrdata["NAMA"]= $set->getField("NAMA");
		$arrdata["URUT"]= $set->getField("URUT");
		array_push($arrpegawaifileuser, $arrdata);
	}
	// print_r($arrpegawaifileuser);exit;
}
else
{
	exit;
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

    <link href="lib/materializetemplate/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/style.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="lib/materializetemplate/css/custom/custom.min.css" type="text/css" rel="stylesheet" media="screen,projection">

    <link rel="stylesheet" href="lib/AdminLTE-2.4.0-rc/dist/css/skins/ubah-skin.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">
    
    <script type="text/javascript" src="lib/easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="lib/materializetemplate/js/plugins/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
    <script type="text/javascript" src="lib/easyui/globalfunction.js"></script>

    <!-- AUTO KOMPLIT -->
    <link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
    <script src="lib/autokomplit/jquery-ui.js"></script>

    <script type="text/javascript">	
	$(function(){
	
		$('#ff').form({
			url:'user_login_json/addfile',
			onSubmit:function(){
				return $(this).form('validate');
			},
			success:function(data){
				// console.log(data);return false;
				data = data.split("-");
				rowid= data[0];
				infodata= data[1];

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
	     				document.location.href= "app/loadUrl/app/user_login_file/?reqId="+rowid;
	     				// top.frames['mainFrame'].location.reload();
	     			}, 1000));
					$(".mbox > .right-align").css({"display": "none"});
	     		}

	        }
		});

	});
	</script>

	<link href="lib/mbox/mbox.css" rel="stylesheet">
	<script src="lib/mbox/mbox.js"></script>
	<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">

				<div class="ubah-color-warna white-text" style="padding: 1em">File Kondisi User Login</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="row">
							<label for="reqLoginUser" class="col s12 m12 label-control" style="text-align: left">
								Login User : <?=$reqLoginUser?>
							</label>
						</div>
						<div class="row" style="margin-top: 1em; background-color: black; color: white">
							<div class="col s12 m4"><b>Jenis File</b></div>
							<div class="col s12 m8"><b>Status</b></div>
						</div>
						<div class="divider"></div>
						<div class="row" id="tbDataData">
							<?
							$vgroup= "";
							foreach ($arrpegawaifileuser as $key => $value)
							{
								$vkategorifileid= $value["KATEGORI_FILE_ID"];
								$vkategorifilenama= $value["KATEGORI_FILE_NAMA"];
								$vriwayattable= $value["RIWAYAT_TABLE"];
								$vriwayatid= $value["RIWAYAT_ID"];
								$vriwayatfield= $value["RIWAYAT_FIELD"];
								$vnama= $value["NAMA"];
								$vstatus= $value["STATUS"];

								if($vgroup !== $vkategorifileid)
								{
							?>
								<div class="col s12 m12" style="background-color: grey; color: white">
									<?=$vkategorifilenama?>
								</div>	
							<?
								}
							?>
								<div class="col s12 m4">
									<?=$vnama?>
									<input type="hidden" name="reqKategoriFileId[]" id="reqKategoriFileId<?=$key?>" value="<?=$vkategorifileid?>" />
									<input type="hidden" name="reqRiwayatTable[]" id="reqRiwayatTable<?=$key?>" value="<?=$vriwayattable?>" />
									<input type="hidden" name="reqRiwayatId[]" id="reqRiwayatId<?=$key?>" value="<?=$vriwayatid?>" />
									<input type="hidden" name="reqRiwayatField[]" id="reqRiwayatField<?=$key?>" value="<?=$vriwayatfield?>" />
								</div>
								<div class="col s12 m8">
									<select name="reqStatus[]">
				                        <option value="" <? if($vstatus == "") echo "selected"; ?>>-</option>
				                        <option value="1" <? if($vstatus == "1") echo "selected"; ?>>Optional</option>
				                     </select>
								</div>
							<?
								$vgroup= $vkategorifileid;
							}
							?>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="hidden" name="reqId" value="<?=$reqId?>" />
								<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
								<button class="btn cyan waves-effect waves-light green" type="submit" name="action">
									<i class="mdi-content-save"></i>
									Simpan
								</button>
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