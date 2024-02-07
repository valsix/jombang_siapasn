<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/KlarifikasiRequired');

$reqId= $this->input->get("reqId");

$set= new KlarifikasiRequired();
$set->selectByParams(array("A.MENU_ID"=>$reqId));
// echo $set->query;exit();
$set->firstRow();
$reqRowId= $set->getField("MENU_ROW_ID");
$reqNama= $set->getField("NAMA");
$reqStatusUpload= $set->getField("STATUS_UPLOAD");
$reqBatasEntri= $set->getField("BATAS_ENTRI");

if(empty($reqRowId))
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
}

$simpan= "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>	
<base href="<?=base_url()?>">
<link rel="stylesheet" type="text/css" href="css/gaya.css">

<link rel="stylesheet" type="text/css" href="lib/easyui-autocomplete/themes/default/easyui.css">
<script type="text/javascript" src="lib/easyui-autocomplete/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/jquery.easyui.min.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/kalender-easyui.js"></script>
<script type="text/javascript" src="lib/easyui-autocomplete/globalfunction.js"></script>

<!-- BOOTSTRAP CORE -->
<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script src='lib/multifile-master/jquery.form.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MetaData.js' type="text/javascript" language="javascript"></script>
<script src='lib/multifile-master/jquery.MultiFile.js' type="text/javascript" language="javascript"></script>

<script type="text/javascript">	
$(function(){
	$.fn.window.defaults.closable = false;
	$('#ff').form({
		url:'main/klarifikasi_json/settingupload_add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data) {
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			if(rowid == "xxx")
			{
				$.messager.alert('Error', infodata, 'error');
			}
			else
			{
				$.messager.alert('Info',infodata,'info',function(){
					document.location.href = "app/loadUrl/main/<?=$jenis?>?reqId="+rowid;
				});
			}

			if(rowid == "xxx")
			{
				$.messager.alert('Error', infodata, 'error');
			}
			else
			{
				$.messager.alert('Info',infodata,'info',function(){
					document.location.href = "app/loadUrl/main/master_klarifikasi_upload_add?reqId=<?=$reqId?>";
				});
			}
		}
	});
});


</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Setting Klarifikasi Upload</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Nama Klarifikasi</td>
	                    <td>:</td>
	                    <td>
	                    	<label><?=$reqNama?></label>
	                    </td>
	                </tr>
					<tr>
                        <td>Status Upload</td>
                        <td>:</td>
                        <td>
                            <select name="reqStatusUpload" id="reqStatusUpload">
                                <option value="" <? if($reqStatusUpload == '') { ?> selected="selected" <? } ?>>Required</option>
                                <option value="1" <? if($reqStatusUpload == '1') { ?> selected="selected" <? } ?>>Tidak Required</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Batas Entri</td>
                        <td>:</td>
                        <td>
                        	<input style="width: 50px" type="text" name="reqBatasEntri" id="reqBatasEntri" value="<?=$reqBatasEntri?>" /> Hari
                        </td>
                    </tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/master_klarifikasi_upload'" class="btn btn-primary" value="Kembali" />
			<?
			if($simpan == "")
			{
			?>
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
			<?
			}
			?>
		</form>
	</div>

	<script type="text/javascript">
	$('#reqBatasEntri').bind('keyup paste', function(){
		this.value = this.value.replace(/[^0-9]/g, '');
	});
	</script>
</body>
</html>