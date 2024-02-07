<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('bio/PersonnelArea');
$this->load->model('siap/SatuanKerja');

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

if($reqMode == "insert")
{
	if($reqId == "0")
	{
		$areaparentcode= "xxx";
	}

	$reqSatuanKerjaId= $reqId;
	$reqId= "";
}

if($reqId == "")
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
	$set= new PersonnelArea();
	$set->selectByParams(array("A.ID" => $reqId));
	$set->firstRow();
	$areaparentcode= $set->getField("AREA_PARENT_CODE");
	$reqDetilId= $set->getField("SYNC_AREA_ID");
	$reqCode= $set->getField("AREA_CODE");
	$reqAreaName= $set->getField("AREA_NAME");
	// echo $areaparentcode;exit();

	if($areaparentcode !== "xxx")
	{
		$set= new PersonnelArea();
		$set->selectByParamsArea(array("A.ID" => $reqId));
		// echo $set->query;exit();
		$set->firstRow();
		$reqId= $set->getField("SYNC_AREA_ID");
		$reqCode= $set->getField("AREA_CODE");
		$reqSatuanKerjaId= $set->getField("SATUAN_KERJA_ID");
		$reqAreaName= $set->getField("AREA_NAME");
		unset($set);
	}
	else
	{
		$reqId= $reqDetilId;
	}

}

if($areaparentcode !== "xxx")
{
	$set= new SatuanKerja();
	$set->selectByParams(array("A.SATUAN_KERJA_ID" => $reqSatuanKerjaId));
	// echo $set->query;exit();
	$set->firstRow();
	$reqInfoSatuanKerja= $set->getField("NAMA_SINGKAT");
	unset($set);
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
<script src="lib/startbootstrap-sb-admin-2-1.0.7/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- AUTO KOMPLIT -->
<link rel="stylesheet" href="lib/autokomplit/jquery-ui.css">
<script src="lib/autokomplit/jquery-ui.js"></script>

<script type="text/javascript">	
$(function(){

	// $.messager.progress({title:'Proses integrasi data.',msg:'Proses data...'});
	// var bar = $.messager.progress('bar');
	// bar.progressbar({text: ''});
	// $.messager.progress('close');

	$('#ff').form({
		url:'bio/area_json/adddetil',
		onSubmit:function(){
			var reqAreaName= "";

			reqAreaName= $("#reqAreaName").val();
			if(reqAreaName == "" || reqAreaName == null )
			{
				$.messager.alert('Info', "isikan terlebih dahulu Nama Area.", 'info');
				return false;
			}
			else
				return $(this).form('validate');
		},
		success:function(data){
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			if(rowid == "xxx")
			{
				$.messager.alert('Info', infodata, 'info');
			}
			else
			{
				$.messager.progress({title:'Proses integrasi data.',msg:'Proses data...'});
				var bar = $.messager.progress('bar');
				bar.progressbar({text: ''});

				checksync();
			}
		}
	});
	
});

function checksync()
{
	reqCode= "<?=$reqCode?>";
	urlAjax= "bio/area_json/flagbelumsyncarea?reqCode="+reqCode;
	$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
		if(parseInt(datajson) > 0)
		{
			checksync();
		}
		else
		{
			urlAjax= "bio/area_json/syncpersonalarea?reqCode="+reqCode;
			$.ajax({'url': urlAjax, dataType: 'json', 'success': function(datajson){
				$.messager.progress('close');
				reloadawal();
			}});
		}
	}});
}

function reloadawal()
{
	document.location.href = "app/loadUrl/biotime/area";
}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Area</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<?
					if($areaparentcode !== "xxx")
					{
					?>
                	<tr>
						<td>Satuan Kerja SIAP ASN</td>
						<td>:</td>
						<td>
							<label id="reqInfoSatuanKerja"><?=$reqInfoSatuanKerja?></label>
						</td>
					</tr>
					<?
					}
					?>
					<tr>
	                    <td>Nama Area</td>
                        <td>:</td>
                    	<td>
							<input type="text" class="easyui-validatebox" id="reqAreaName" name="reqAreaName" value="<?=$reqAreaName?>" style="width:50%" />
                    	</td>
	                </tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqSatuanKerjaId" value="<?=$reqSatuanKerjaId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="button" onclick="reloadawal()" class="btn btn-primary" value="Kembali" />
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
		</form>
	</div>
</body>
</html>