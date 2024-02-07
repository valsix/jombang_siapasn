<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('jenisDokumen');

$set= new jenisDokumen();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("JENIS_DOKUMEN_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
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
				url:'jenis_dokumen_json/add',
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
         					document.location.href= "app/loadUrl/app/jenis_dokumen_add/?reqId="+rowid;
         				}, 1000));
						$(".mbox > .right-align").css({"display": "none"});
         			}
         			top.frames['mainFrame'].location.reload();
         		}
         	});
			

		});
	</script>

	<style>
	.form-bg{
		margin-top:10px;
		*background: linear-gradient(to right,#e16b47,#3d3537);
		font-family: 'Roboto', sans-serif;
		color: #000;
	}
	.form-horizontal .form-group{
		margin-bottom: 30px;
	}
	.form-horizontal hr{
		border: 1px solid rgba(255,255,255,0.3);
		margin: 20px 0;
	}
	.form-horizontal .control-label{
		text-align: left;
	}
	.form-horizontal .form-control{
		padding: 5px 15px;
		background: #fff;
		border: 1px solid #00c7c7;
	}
	.form-horizontal .form-control:focus{
		box-shadow:0 1px 10px #00c7c7;
	}
	.form-horizontal .btn-default{
		background: #00c7c7;
		border-color: transparent;
		color: #fff;
		margin-left: -45px;
		border-radius: 0 4px 4px 0;
	}
	.form-horizontal .btn-default:hover{
		background :#000;
		border-color: transparent;
		color: #fff;
	}
	.form-horizontal .checkbox-custom{
		opacity: 0;
		position: absolute;
	}
	.form-horizontal .checkbox-custom,
	.form-horizontal .checkbox-custom-label{
		display: inline-block;
		vertical-align: middle;
		margin: 5px;
		cursor: pointer;
	}
	.form-horizontal .checkbox-custom-label{
		position: relative;
	}
	.checkbox-custom + .checkbox-custom-label:before{
		content: "";
		display: inline-block;
		width: 30px;
		height: 30px;
		line-height:30px;
		border-radius: 50%;
		text-align: center;
		background: #fff;
		vertical-align: middle;
		margin-right: 10px;
	}
	.checkbox-custom:checked + .checkbox-custom-label:before{
		content: "\f00c";
		font-family: 'FontAwesome';
		background: #00c7c7;
		color: #fff;
		text-align: center;
		vertical-align: middle;
	}
	@media only screen and (max-width: 767px){
		.form-horizontal .btn-default{
			margin-left: -33px;
		}
	}
</style>

<link href="lib/mbox/mbox.css" rel="stylesheet">
<script src="lib/mbox/mbox.js"></script>
<link href="lib/mbox/mbox-modif.css" rel="stylesheet">
</head>

<body>
	<div class="container-fluid full-height">
		<div class="ubah-color-warna white-text" style="padding: 1em">Tambah jenis Dokumen</div>
		<div class="form-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<form id="ff" class="form-horizontal" method="post"  novalidate enctype="multipart/form-data">
							<div class="row">
								<label for="reqNama" class="col s12 m2 label-control">Nama</label>
								<div class="input-field col s12 m10">
									<input name="reqNama" id="reqNama" class="" type="text" required value="<?=$reqNama?>" />
									<!-- easyui-validatebox -->
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