<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('SkPns');

$reqId = $this->input->get("reqId");

$set= new SkPns();
$statement= " AND A.PEGAWAI_ID = ".$reqId;
$set->selectByParams(array(), -1,-1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField('SK_PNS_ID');
$tempPejabatPenetapan			= $set->getField('PEJABAT_PENETAP_ID');
$tempPejabatPenetapan			= $set->getField('PEJABAT_PENETAP_ID');
$tempNamaPejabatPenetap			= $set->getField('NAMA_PENETAP');
$tempNIPPejabatPenetap			= $set->getField('NIP_PENETAP');
$tempNoSuratKeputusan			= $set->getField('NO_SK');
$tempTanggalSuratKeputusan		= dateToPageCheck($set->getField('TANGGAL_SK'));
$tempTerhitungMulaiTanggal		= dateToPageCheck($set->getField('TMT_PNS'));
$tempNoDiklatPrajabatan			= $set->getField('NO_PRAJAB');
$tempTanggalDiklatPrajabatan	= dateToPageCheck($set->getField('TANGGAL_PRAJAB'));
$tempNoSuratUjiKesehatan		= $set->getField('NO_UJI_KESEHATAN');
$tempTanggalSuratUjiKesehatan	= dateToPageCheck($set->getField('TANGGAL_UJI_KESEHATAN'));
$tempGolRuang					= $set->getField('PANGKAT_ID');
$tempPengambilanSumpah			= $set->getField('SUMPAH');
$tempSKPNSId					= (int)$set->getField('SK_PNS_ID');
$tempTanggalSumpah				= $set->getField('TANGGAL_SUMPAH');
$tempNoSuratjiKesehatan			= $set->getField('NO_UJI_KESEHATAN');
$tempNoDiklatPrajabatan			= $set->getField('NO_PRAJAB');
$tempTh 						= $set->getField('MASA_KERJA_TAHUN');
$tempBl 						= $set->getField('MASA_KERJA_BULAN');

$tempNoBeritaAcara				= $set->getField('NOMOR_BERITA_ACARA');
$tempTanggalBeritaAcara			= dateToPageCheck($set->getField('TANGGAL_BERITA_ACARA'));
$tempKeteranganLPJ				= $set->getField('KETERANGAN_LPJ');

$tempPangkatNama= $set->getField('PANGKAT_KODE');
$tempPangkatId= $set->getField('PANGKAT_ID');

$tempGajiPokok= $set->getField('GAJI_POKOK');


$data = $set->getField('FOTO_BLOB');

$tempPejabatPenetapId= $set->getField('PEJABAT_PENETAP_ID');
$tempPejabatPenetap= $set->getField('PEJABAT_PENETAP_NAMA');

if($reqRowId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	// $set->selectByParams(array("PEGAWAI_ID"=>$reqId));
	// $set->firstRow();
	// $reqStatus= $set->getField("STATUS");
	
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
				url:'sk_pns_json/add',
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
	<div id="judul-popup">Tambah SK PNS</div>
	<div id="konten">
		<div id="popup-tabel2">
			<form id="ff" method="post"  novalidate enctype="multipart/form-data">
				<table class="table_list table" cellspacing="1" width="100%" border="0">
					<tr>      
						<td>Pejabat Penetapan</td>
						<td>:</td>
						<td colspan="4">
                        	<?=$tempPejabatPenetap?>
						</td>            
					</tr>
					<tr>      
						<td>Nama Pejabat Penetap</td>
						<td>:</td>
						<td>
							<input type="text"   name="reqNamaPejabatPenetap" value="<?=$tempNamaPejabatPenetap?>" />
						</td>			
					</tr>
					<tr>      
						<td>NIP Pejabat Penetap</td>
						<td>:</td>
						<td>
							<input type="text"   name="reqNIPPejabatPenetap" value="<?=$tempNIPPejabatPenetap?>" />
						</td>			
					</tr>
					<tr>      
                        <td>No. Surat Keputusan</td><td width="2%">:</td>
                        <td>
                            <?=$tempNoSuratKeputusan?>
                        </td>
                        <td width="25%">Tanggal Surat Keputusan</td><td width="2%">:</td>
                        <td>
                            <?=$tempTanggalSuratKeputusan?>
                        </td>
                    </tr>
                    <tr>           
                        <td>Terhitung Mulai Tanggal</td>
                        <td>:</td>
                        <td>
                            <?=$tempTerhitungMulaiTanggal?>
                        </td>			
                    </tr>
                    <tr>           
                        <td>No. Diklat Prajabatan</td><td width="2%">:</td>
                        <td>
                            <input type="text"   name="reqNoDiklatPrajabatan" value="<?=$tempNoDiklatPrajabatan?>" <?php /*?>class="required"<?php */?>/>
                        </td>
                        <td>Tanggal Diklat Prajabatan</td><td>:</td>
                        <td>
                            <input type="text"   name="reqTanggalDiklatPrajabatan" id="reqTanggalDiklatPrajabatan" maxlength="10" class="dateIna" onkeydown="return format_date(event,'reqTanggalDiklatPrajabatan');" value="<?=$tempTanggalDiklatPrajabatan?>" <?php /*?>class="required"<?php */?>/>
                        </td>
                    </tr>
                    <tr>           
                        <td >No. Surat Uji Kesehatan</td><td>:</td>
                        <td>
                            <input type="text"   name="reqNoSuratUjiKesehatan" value="<?=$tempNoSuratUjiKesehatan?>" />
                        </td>
                        <td>Tanggal Surat Uji Kesehatan</td><td>:</td>
                        <td>
                            <input type="text"   name="reqTanggalSuratUjiKesehatan" id="reqTanggalSuratUjiKesehatan" maxlength="10" onkeydown="return format_date(event,'reqTanggalSuratUjiKesehatan');" value="<?=$tempTanggalSuratUjiKesehatan?>"  />
                            <!--&nbsp;&nbsp;<img style="cursor:pointer" onclick="xclear('reqTanggalSuratUjiKesehatan')" src="img/no_cal.gif">-->
                           
                        </td>
                    </tr>
                    <tr>           
                    <td>Gol/Ruang</td>
                    <td>:</td>
                    <td>
                        <?=$tempPangkatNama?>
                    </td>			
                </tr>
                <tr>           
                    <td>Pengambilan Sumpah</td><td width="2%">:</td>
                    <td colspan="4">
                        <input type="checkbox" name="reqPengambilanSumpah" value="1" <? if($tempPengambilanSumpah == 1) echo 'checked'?> <?php /*?>class="required"<?php */?>/>
                    </td>			
                </tr>
                <tr>
                    <td>Masa Kerja</td>
                    <td>:</td>
                    <td ><?=$tempTh?> Th <?=$tempBl?> Bl
                    </td>
                    <td>Gaji Pokok</td>
                    <td>:</td>
                    <td>
                        <?=$tempGajiPokok?>
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