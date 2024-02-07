<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('Pendidikan');

$set= new Pendidikan();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("PENDIDIKAN_ID"=>$reqId));
	$set->firstRow();
	$reqNama= $set->getField("NAMA");
	$reqPangkatMinimal= $set->getField("PANGKAT_MINIMAL");
	$reqPangkatMaksimal= $set->getField("PANGKAT_MAKSIMAL");
	$reqNoUrut= $set->getField("NO_URUT");
	// $reqKode= $set->getField("KODE");
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

	<link rel="stylesheet" type="text/css" href="lib/easyui/themes/default/easyui.css">
	<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="lib/easyui/jquery.easyui.min.js"></script>
	<script type="text/javascript" src="lib/easyui/kalender-easyui.js"></script>
	<script type="text/javascript" src="lib/easyui/globalfunction.js"></script>
	<script type="text/javascript">	
		$(function(){
			$('#ff').form({
				url:'pendidikan_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					$.messager.alert('Info', data, 'info');
					
					<?
					if($reqMode == "update")
					{
						?>
						document.location.reload();
						<?	
					}
					else
					{
						?>
						$('#rst_form').click();
						<?
					}
					?>
					top.frames['mainFrame'].location.reload();
				}
			});
			
		});
	</script>

	<!-- BOOTSTRAP CORE -->
	<link href="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah Pendidikan</div>
	<div id="konten">
		<div id="popup-tabel2">
			<form id="ff" method="post"  novalidate enctype="multipart/form-data">
				<table class="table">
					<thead>
						<tr>           
							<td>Nama</td>
							<td>:</td>
							<td>
								<input name="reqNama" class="easyui-validatebox" type="text" required value="<?=$reqNama?>" />
							</td>			
						</tr>  
						<tr>           
							<td>Pangkat Minimal</td>
							<td>:</td>
							<td>
								<input name="reqPangkatMinimal" class="easyui-validatebox" type="text" required value="<?=$reqPangkatMinimal?>" />
							</td>			
						</tr>  
						<tr>           
							<td>Pangkat Maksimal</td>
							<td>:</td>
							<td>
								<input name="reqPangkatMaksimal" class="easyui-validatebox" type="text" required value="<?=$reqPangkatMaksimal?>" />
							</td>			
						</tr>  
						<tr>           
							<td>No Urut</td>
							<td>:</td>
							<td>
								<input name="reqNoUrut" class="easyui-validatebox" type="text" required value="<?=$reqNoUrut?>" />
							</td>			
						</tr>  
						
						
					</table>
				</thead>
				<input type="hidden" name="reqId" value="<?=$reqId?>" />
				<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
				<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
				<input type="reset" id="rst_form"  class="btn btn-primary" value="Reset" />
				
			</form>
		</div>
	</div>
</div>
</body>
</html>