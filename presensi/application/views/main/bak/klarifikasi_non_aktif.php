<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('main/SettingKlarifikasi');
$this->load->model('bio/PersonnelEmployeeArea');
$this->load->model('siap/SatuanKerja');

$reqId= $this->input->get("reqId");
$reqMode= $this->input->get("reqMode");

$set= new SettingKlarifikasi();
$set->selectByParams(array());
// echo $set->query;exit();
$set->firstRow();
$reqId= $set->getField("SETTING_KLARIFIKASI_ID");
$reqMasaBerlakuAwal= dateToPageCheck($set->getField("INFO_MASA_BERLAKU_AWAL"));
$reqMasaBerlakuAkhir= dateToPageCheck($set->getField("INFO_MASA_BERLAKU_AKHIR"));

if(empty($reqId))
{
	$reqMode = "insert";
}
else
{
	$reqMode = "update";
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
	$('#ff').form({
		url:'main/setting_klarifikasi_json/add',
		onSubmit:function(){
			return validasitanggal();
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
				$.messager.alert('Info', infodata, 'info');
				reloadawal();
			}
		}
	});

	$('#reqMasaBerlakuAwal').datebox({
		onChange: function(date){
			// $('#reqMasaBerlakuAkhir').datebox('setValue', '');		
		}
	});
	
	$('#reqMasaBerlakuAkhir').datebox({
		onChange: function(date){
			// validasitanggal();
		}
	});
	
});

function setvalidasitanggal(tanggalawal, tanggalakhir)
{
	var dt1   = parseInt(tanggalakhir.substring(0,2),10); 
	var mon1  = parseInt(tanggalakhir.substring(3,5),10) - 1;
	var yr1   = parseInt(tanggalakhir.substring(6,10),10); 

	var dt2   = parseInt(tanggalawal.substring(0,2),10); 
	var mon2  = parseInt(tanggalawal.substring(3,5),10) - 1; 
	var yr2   = parseInt(tanggalawal.substring(6,10),10); 
	var date1 = new Date(yr1, mon1, dt1); 
	var date2 = new Date(yr2, mon2, dt2); 
	// console.log(date2+" < "+date1);return false;
	return (date2 > date1);
}

function validasitanggal()
{
	var mulai = $('#reqMasaBerlakuAwal').datebox('getValue');	
	var selesai = $('#reqMasaBerlakuAkhir').datebox('getValue');		
	
	/*if(mulai == "")
	{
		$.messager.alert('Info', "Isi tanggal mulai terlebih dahulu.", 'info');		
		$('#reqMasaBerlakuAkhir').datebox('setValue', '');
		return false;
	}*/
	
	// console.log(mulai+";"+selesai);
	// var selisih = get_day_between(mulai, selesai);
		
	if(setvalidasitanggal(mulai, selesai))
	{
		$.messager.alert('Info', "Tanggal akhir lebih kecil.", 'info');		
		$('#reqMasaBerlakuAkhir').datebox('setValue', '');
		return false;
	}
}

function reloadawal()
{
	document.location.href = "app/loadUrl/main/klarifikasi_non_aktif";
}
</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Setting Klarifikasi</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
                	<tr>
						<td>Tanggal</td>
						<td>:</td>
						<td>
							<input class="easyui-datebox" id="reqMasaBerlakuAwal" name="reqMasaBerlakuAwal" value="<?=$reqMasaBerlakuAwal?>" style="width: 100px" /> s/d <input class="easyui-datebox" id="reqMasaBerlakuAkhir" name="reqMasaBerlakuAkhir" value="<?=$reqMasaBerlakuAkhir?>" style="width: 100px" />
						</td>
					</tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="submit" name="reqSubmit" class="btn btn-primary" value="Simpan" />
		</form>
	</div>
</body>
</html>