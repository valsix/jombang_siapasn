<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('SkCpns');

$reqId= $this->input->get("reqId");

$set= new SkCpns();
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField('SK_CPNS_ID');
$tempNoNotaBAKN				= $set->getField('NO_NOTA');
$tempTanggalNotaBAKN		= dateToPageCheck($set->getField('TANGGAL_NOTA'));
$tempPejabatPenetapId		= $set->getField('PEJABAT_PENETAP_ID');
$tempPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');

$tempNamaPejabatPenetap		= $set->getField('NAMA_PENETAP');
$tempNIPPejabatPenetap		= $set->getField('NIP_PENETAP');
$tempNoSuratKeputusan		= $set->getField('NO_SK');
$tempTanggalSuratKeputusan	= dateToPageCheck($set->getField('TANGGAL_SK'));
$tempTerhitungMulaiTanggal	= dateToPageCheck($set->getField('TMT_CPNS'));
$tempGolRuang				= $set->getField('PANGKAT_ID');
$tempPangkatNama= $set->getField('PANGKAT_KODE');
$tempPangkatId= $set->getField('PANGKAT_ID');
$tempTanggalTugas			= dateToPageCheck($set->getField('TANGGAL_TUGAS'));
$tempTh 					= $set->getField('MASA_KERJA_TAHUN');
$tempBl 					= $set->getField('MASA_KERJA_BULAN');

$tempGajiPokok= $set->getField('GAJI_POKOK');
$tempTanggalPersetujuanNip= dateToPageCheck($set->getField("TANGGAL_PERSETUJUAN_NIP"));
$tempNoPersetujuanNip= $set->getField("NO_PERSETUJUAN_NIP");
$tempPendidikan= $set->getField("PENDIDIKAN_ID");
$tempJurusan= $set->getField("JURUSAN");

if($reqRowId == ""){
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
				url:'sk_cpns_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
					//alert(data);return false;
					$.messager.alert('Info', data, 'info');

					<?
					if($reqMode == "update")
					{
						?>
						// document.location.reload();
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

	<!-- SIMPLE TAB -->
	<script type="text/javascript" src="lib/simpletabs_v1.3/js/simpletabs_1.3.js"></script>
	<link href="lib/simpletabs_v1.3/css/simpletabs.css" type="text/css" rel="stylesheet">

</head>

<body class="bg-kanan-full">
	<div id="judul-popup">Tambah SK CPNS</div>
	<div id="konten">
		<div id="popup-tabel2">
			<form id="ff" method="post"  novalidate enctype="multipart/form-data">
				<table class="table_list table" cellspacing="1" width="100%" border="0">
					<tr>           
						<td width="20%">No. Nota BAKN</td><td width="2%">:</td>
						<td colspan="4">
							<input type="text"   name="reqNoNotaBAKN" value="<?=$tempNoNotaBAKN?>" <?php /*?>class="required" title="No. Nota BAKN harus diisi"<?php */?> />
							<input type="hidden" name ="reqSkcpnsId" value="<?=$tempSkcpnsId?>">
						</td>			
					</tr>
					<tr>           
						<td >Tanggal Nota BAKN</td><td>:</td>
						<td colspan="4">
							<input type="text"   name="reqTanggalNotaBAKN" id="reqTanggalNotaBAKN"  maxlength="10" class="<?php /*?>required<?php */?> dateIna" onkeydown="return format_date(event,'reqTanggalNotaBAKN');" value="<?=$tempTanggalNotaBAKN?>" <?php /*?>title="Tanggal Nota BAKN harus diisi"<?php */?>/>
							<!--&nbsp;&nbsp;<img style="cursor:pointer" onclick="xclear('reqTanggalNotaBAKN')" src="img/no_cal.gif">-->
						</td>			
					</tr>
					<tr>      
						<td width="5%">Pejabat Penetapan</td><td width="2%">:</td>
						<td colspan="4">
							<?=$tempPejabatPenetap?>
						</td>            
					</tr>
					<input type="hidden"   name="reqNamaPejabatPenetap" id="idNamaPejabatPenetap" onchange="getData()" value="<?=$tempNamaPejabatPenetap?>" <?php /*?>class="required"<?php */?>/>
					<input type="hidden"   name="reqNIPPejabatPenetap" id="idNIPPejabatPenetap" value="<?=$tempNIPPejabatPenetap?>" <?php /*?>class="required"<?php */?> />
					<tr>      
						<td width="5%">No. Surat Keputusan</td><td width="2%">:</td>
						<td colspan="4">
                        	<?=$tempNoSuratKeputusan?>
						</td>			
					</tr><tr>      
					<td width="5%">Tanggal Surat Keputusan</td><td width="2%">:</td>
					<td>
						<?=$tempTanggalSuratKeputusan?>
					</td>			
					<td width="15%">Terhitung Mulai Tanggal</td><td width="2%">:</td>
					<td>
						<?=$tempTerhitungMulaiTanggal?>
					</td>			
					<tr>           
						<td width="5%">Gol/Ruang</td><td>:</td>
						<td>
                        	<?=$tempPangkatNama?>
						</td>			
						<td width="5%">Tanggal Tugas</td><td width="2%">:</td>
						<td>
							<input type="text"   name="reqTanggalTugas" id="reqTanggalTugas" maxlength="10" class="dateIna" onkeydown="return format_date(event,'reqTanggalTugas');" value="<?=$tempTanggalTugas?>" <?php /*?>class="required"<?php */?>/>
							<!--&nbsp;&nbsp;<img style="cursor:pointer" onclick="xclear('reqTanggalTugas')" src="img/no_cal.gif">-->

						</td>
					</tr>
					<tr>
						<td width="5%">No. Persetujuan NIP</td><td width="2%">:</td>
						<td>
							<input type="text"   name="reqNoPersetujuanNIP" value="<?=$tempNoPersetujuanNip?>" />
						</td>
						<td width="5%">Tanggal Persetujuan NIP</td><td width="2%">:</td>
						<td>
							<input type="text"   name="reqTanggalPersetujuanNIP" id="reqTanggalPersetujuanNIP" maxlength="10" onkeydown="return format_date(event,'reqTanggalPersetujuanNIP');" value="<?=$tempTanggalPersetujuanNip?>" />
						</td>
					</tr>
					<tr>	
						<td colspan="2">Masa Kerja
							<td >
								<?=$tempTh?> Tahun
								<?=$tempBl?> Bulan
							</td>
						</td>
						<td>Gaji Pokok</td>
						<td>:</td>
						<td>
							<?=$tempGajiPokok?>
						</td>
					</tr>
					<tr>
						<td width="15%">Pendidikan</td><td width="2%">:</td>
						<td>
							
						</td>
						<td >Jurusan</td><td>:</td>
						<td >
							
						</td>
					</tr>
				</table>
				<input type="hidden" name="reqId" value="<?=$reqId?>" />
                <input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
				<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
				<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
			</form>
		</div>
	</div>
</div>
</body>
</html>