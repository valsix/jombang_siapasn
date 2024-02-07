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
	<style>
		html, body{
			height:100%;
		}
		@media screen and (max-width:767px) {
			html, body{
				height: auto;
			}
		}
	</style>

</head>

<body>
	<div class="container-fluid full-height">
		<div class="row full-height">
			<div class="col-md-12 area-form full-height">


				<div id="judul-popup">Tambah SK PNS</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="form-group" >
							<label for="" class="col-md-2" >Pejabat Penetapan</label>
							<div class="col-md-4">
								<?=$tempPejabatPenetap?>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >Nama Pejabat Penetap</label>
							<div class="col-md-4">
								<input type="text"   name="reqNamaPejabatPenetap" value="<?=$tempNamaPejabatPenetap?>" class="form-control" />
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >NIP Pejabat Penetap</label>
							<div class="col-md-4">
								<input type="text"   name="reqNIPPejabatPenetap" value="<?=$tempNIPPejabatPenetap?>" class="form-control"/>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >No. Surat Keputusan</label>
							<div class="col-md-4">
								<?=$tempNoSuratKeputusan?>
							</div>
							<label for="" class="col-md-2" >Tanggal Surat Keputusan</label>
							<div class="col-md-4">
								<?=$tempTanggalSuratKeputusan?>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >Terhitung Mulai Tanggal</label>
							<div class="col-md-4">
								<?=$tempTerhitungMulaiTanggal?>
							</div>
							
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >No. Diklat Prajabatan</label>
							<div class="col-md-4">
								<input type="text"   name="reqNoDiklatPrajabatan" value="<?=$tempNoDiklatPrajabatan?>" class="form-control"/>
							</div>
							<label for="" class="col-md-2" >Tanggal Diklat Prajabatan</label>
							<div class="col-md-4">
								<input type="text"   name="reqTanggalDiklatPrajabatan" id="reqTanggalDiklatPrajabatan" maxlength="10" class="dateIna form-control" onkeydown="return format_date(event,'reqTanggalDiklatPrajabatan');" value="<?=$tempTanggalDiklatPrajabatan?>" <?php /*?>class="required"<?php */?>/>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >No. Surat Uji Kesehatan</label>
							<div class="col-md-4">
								<input type="text"   name="reqNoSuratUjiKesehatan" value="<?=$tempNoSuratUjiKesehatan?>" class="form-control"/>
							</div>
							<label for="" class="col-md-2" >Tanggal Surat Uji Kesehatan</label>
							<div class="col-md-4">
								<input type="text"   name="reqTanggalSuratUjiKesehatan" id="reqTanggalSuratUjiKesehatan" maxlength="10" class="form-control" onkeydown="return format_date(event,'reqTanggalSuratUjiKesehatan');" value="<?=$tempTanggalSuratUjiKesehatan?>"  />
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >Gol/Ruang</label>
							<div class="col-md-4">
								<?=$tempPangkatNama?>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >Pengambilan Sumpah</label>
							<div class="col-md-4">
								<input type="checkbox" name="reqPengambilanSumpah" value="1" <? if($tempPengambilanSumpah == 1) echo 'checked'?> class=""/>
							</div>
						</div>

						<div class="form-group" >
							<label for="" class="col-md-2" >Masa Kerja</label>
							<div class="col-md-2">
								<?=$tempTh?> Tahun
							</div>
							<div class="col-md-2">
								<?=$tempBl?> Bulan
							</div>
							<label for="" class="col-md-2" >Gaji Pokok</label>
							<div class="col-md-4">
								<?=$tempGajiPokok?>
							</div>
						</div>

						<input type="hidden" name="reqId" value="<?=$reqId?>" />
						<input type="hidden" name="reqRowId" value="<?=$reqRowId?>" />
						<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
						<div class="form-group" >
							<div class="col-md-2">
								<button type="submit" name="reqSubmit" class="btn btn-primary" value="Submit">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>