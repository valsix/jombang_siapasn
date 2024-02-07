<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

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

	<!-- BOOTSTRAP -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!--<script src="js/jquery-1.10.2.min.js"></script>-->
	<script src="lib/bootstrap/js/jquery.min.js"></script>
	<script src="lib/bootstrap/js/bootstrap.js"></script>
	<link href="lib/bootstrap/css/bootstrap.css" rel="stylesheet">

	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css" type="text/css">

	<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
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
         				mbox.alert(infodata, {open_speed: 500}, window.setInterval(function() 
         				{
         					mbox.close();
         					document.location.href= "app/loadUrl/app/jenis_dokumen_add/?reqId="+rowid;
         				}, 1000));
         			}
					// top.frames['mainFrame'].location.reload();
				}
			});
			

		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	
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
		<div id="judul-popup">Tambah jenis Dokumen</div>
		<div class="form-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-2 col-md-8">
						<form id="ff" class="form-horizontal" method="post"  novalidate enctype="multipart/form-data">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="exampleInputName2">Nama</label>
								<div class="col-sm-9">
									<input name="reqNama" class="form-control easyui-validatebox" type="text" required value="<?=$reqNama?>" />
								</div>
							</div>
							<div class="form-group" >
								<div class="col-md-2">
									<input type="hidden" name="reqId" value="<?=$reqId?>" />
									<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
									<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</body>
</html>