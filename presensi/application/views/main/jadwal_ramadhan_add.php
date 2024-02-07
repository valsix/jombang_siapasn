<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$validasiakses= $CI->validasiakses('5506');

$this->load->model('main/KerjaJam');

$reqId= $this->input->get("reqId");

if(empty($reqId))
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
	$set= new KerjaJam();
	$set->selectByParamsJamRamadhan(array("A.JADWAL_RAMADHAN_ID"=>$reqId));
	// echo $set->query;exit();
	$set->firstRow();

	$reqMulaiBerlaku= dateTimeToPageCheck($set->getField("MULAI_BERLAKU_INFO"));
	$reqAkhirBerlaku= dateTimeToPageCheck($set->getField("AKHIR_BERLAKU_INFO"));
	// $reqMulaiBerlaku= dateToPageCheck($set->getField("MULAI_BERLAKU_INFO"));
	// $reqAkhirBerlaku= dateToPageCheck($set->getField("AKHIR_BERLAKU_INFO"));
	$reqStatusJamKerja= $set->getField("STATUS_JAM_KERJA");

	$reqLastUser= $set->getField("LAST_USER");
	$reqLastUpdate= dateTimeToPageCheck($set->getField("LAST_DATE_INFO"));
}

$simpan= $disabled= "";
if($validasiakses == "R")
{
	$simpan= "1";
	$disabled= "disabled";
}
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
		url:'main/jadwal_json/jadwalramadhan_add',
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
					document.location.href = "app/loadUrl/main/jadwal_ramadhan_add?reqId=<?=$reqId?>";
				});
			}
		}
	});
});


</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Jadwal Ramadhan</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
	                    <td>Tanggal</td>
	                    <td>:</td>
	                    <td>
	                    	<input class="easyui-datetimebox" name="reqMulaiBerlaku" data-options="required:true, showSeconds:true" style="width:155px" value="<?=$reqMulaiBerlaku?>" <?=$disabled?> />
	                    	s/d
	                    	<input class="easyui-datetimebox" name="reqAkhirBerlaku" data-options="required:true, showSeconds:true" style="width:155px" value="<?=$reqAkhirBerlaku?>" <?=$disabled?> />
	                    </td>
	                </tr>
					<tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            <select name="reqStatusJamKerja" id="reqStatusJamKerja">
                                <option value="" <? if($reqStatusJamKerja == '') { ?> selected="selected" <? } ?>>Aktif</option>
                                <option value="1" <? if($reqStatusJamKerja == '1') { ?> selected="selected" <? } ?>>Tidak Aktif</option>
                            </select>
                        </td>
                    </tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/jadwal_ramadhan'" class="btn btn-primary" value="Kembali" />
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