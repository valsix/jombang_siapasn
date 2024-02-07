<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('main/HariLibur');

$set= new HariLibur();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("HARI_LIBUR_ID"=>$reqId));
	$set->firstRow();
	$reqStatusCutiBersama= $set->getField('STATUS_CUTI_BERSAMA');
	$reqNama= $set->getField('NAMA');
	$reqKeterangan= $set->getField('KETERANGAN');
	$reqTanggalAwal= dateToPageCheck($set->getField('TANGGAL_AWAL'));
	$reqTanggalAkhir= dateToPageCheck($set->getField('TANGGAL_AKHIR'));
	$reqTanggalFix= $set->getField('TANGGAL_FIX');
	$reqCabangId= $set->getField('CABANG_ID');
	$reqHari= substr($reqTanggalFix,0,2);
	$reqBulan= substr($reqTanggalFix,2,2);
	if($reqTanggalFix)
		$reqPilih= 2;
	else
		$reqPilih= 1;
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
function setValue()
{
	status=$('#reqPilih').val();
	if(status == 'Dinamis'){
		$('#reqTanggalAwal').addClass('required');
		$('#reqTanggalAkhir').addClass('required');
		$('#reqBulan').removeClass('required');
		$('#reqHari').removeClass('required');
		
		$('#reqBulan').val('');$('#reqHari').val('');
		
		$('#tr_tanggal_awal').show();
		$('#tr_tanggal_akhir').show();
		$('#tr_tanggal_fix').hide();
	}
	else if(status == 'Statis'){
		$('#reqTanggalAwal').removeClass('required');
		$('#reqTanggalAkhir').removeClass('required');
		$('#reqBulan').addClass('required');
		$('#reqHari').addClass('required');
		
		$('#reqTanggalAwal').val('');
		$('#reqTanggalAkhir').val('');
		
		$('#tr_tanggal_awal').hide();
		$('#tr_tanggal_akhir').hide();
		$('#tr_tanggal_fix').show();
	}
}

$(function(){
	setValue();
	$('#ff').form({
		url:'main/klarifikasi_json/harilibur_add',
		onSubmit:function(){
			return $(this).form('validate');
		},
		success:function(data) {
			// console.log(data);return false;
			data = data.split("-");
			rowid= data[0];
			infodata= data[1];

			$.messager.alert('Info', infodata, 'info');

			if(rowid == "xxx"){}
			else
				document.location.href = "app/loadUrl/main/harilibur_add?reqId=<?=$reqId?>";
		}
	});
});


</script>
</head>

<body class="bg-permohonan">
	<div class="area-permohonan">
		<div class="judul-monitoring"><span>Form Hari Libur</span></div>

		<form id="ff" method="post" novalidate enctype="multipart/form-data">
			<table class="table">
				<thead>
					<tr>
						<td>Pilih</td>
						<td>
							<?
							if($reqId == "")
							{
								?>
								<select name="reqPilih" id="reqPilih" onchange="setValue()">
									<option>Dinamis</option>
									<option>Statis</option>
								</select>
								<?
							}
							else
							{
								?>
								<select name="reqPilih" id="reqPilih" disabled onchange="setValue()">
									<option <? if($reqPilih == 1) echo 'selected'?>>Dinamis</option>
									<option <? if($reqPilih == 2) echo 'selected'?>>Statis</option>
								</select>
								<?
							}
							?>
						</td>			
					</tr>
					<tr id="tr_tanggal_awal">    
						<td>Tanggal Awal</td>
						<td>
							<input id="reqTanggalAwal" name="reqTanggalAwal" class="easyui-datebox" value="<?=$reqTanggalAwal?>" style="width:100px" />
						</td>
					</tr>  
					<tr id="tr_tanggal_akhir">
						<td>Tanggal Akhir</td>
						<td>
							<input id="reqTanggalAkhir" name="reqTanggalAkhir" class="easyui-datebox" value="<?=$reqTanggalAkhir?>" style="width:100px" />
						</td>
					</tr> 
					<tr id="tr_tanggal_fix">
						<td>Tanggal Fix</td>
						<td>
							<select name="reqHari" id="reqHari">
								<option></option>
								<?
								for($i=1;$i<31;$i++)
								{
									?>
									<option value="<?=$i?>" <? if($i == $reqHari) echo 'selected';?>><?=$i?></option>
									<?
								}
								?>
							</select>
							&nbsp;&nbsp;
							<select name="reqBulan" id="reqBulan">
								<option></option>
								<?
								for($i=1;$i<=12;$i++)
								{
									?>
									<option value="<?=$i?>" <? if($i == $reqBulan) echo 'selected';?>><?=getNameMonth($i)?></option>
									<?
								}
								?>
							</select>
						</td>			
					</tr>                    
					<tr>           
						<td>Nama</td>
						<td>
							<input name="reqNama" class="easyui-validatebox" required style="width:200px" type="text" value="<?=$reqNama?>" />
						</td>			
					</tr>
					<tr>
						<td>Keterangan</td>

						<td>
							<textarea name="reqKeterangan" style="width:250px; height:10 0px;"><?=$reqKeterangan?></textarea>
						</td>
					</tr>
					<tr>
						<td>Status Cuti Bersama</td>
						<td><input type="checkbox" name="reqStatusCutiBersama" value="1" <? if($reqStatusCutiBersama == 1) { ?> checked <? } ?>></td>
					</tr>
				</thead>
			</table>

			<input type="hidden" name="reqId" value="<?=$reqId?>" />
			<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
			<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
			<input type="button" onclick="document.location.href='app/loadUrl/main/master_libur'" class="btn btn-primary" value="Kembali" />
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