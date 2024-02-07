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
// echo $set->query;exit;
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

				<div id="judul-popup">Tambah SK CPNS</div>
				<div id="area-form-inner">
					<form id="ff" method="post"  novalidate enctype="multipart/form-data">

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>No. Nota BAKN</label>
									<input type="text"   name="reqNoNotaBAKN" value="<?=$tempNoNotaBAKN?>" class="form-control" <?php /*?> title="No. Nota BAKN harus diisi"<?php */?> />
									<input type="hidden" name ="reqSkcpnsId" value="<?=$tempSkcpnsId?>">
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label>Tanggal Nota BAKN</label>
									<input type="text"   name="reqTanggalNotaBAKN" id="reqTanggalNotaBAKN"  maxlength="10" class="form-control dateIna" onkeydown="return format_date(event,'reqTanggalNotaBAKN');" value="<?=$tempTanggalNotaBAKN?>" <?php /*?>title="Tanggal Nota BAKN harus diisi"<?php */?>/>
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >Pejabat Penetapan</label>
									<input type="text" class="form-control" disabled style="padding:0" value="<?=$tempPejabatPenetap?>"/>
								</div>
							</div>
						</div>


						<input type="hidden"   name="reqNamaPejabatPenetap" id="idNamaPejabatPenetap" onchange="getData()" value="<?=$tempNamaPejabatPenetap?>" <?php /*?>class="required"<?php */?>/>
						<input type="hidden"   name="reqNIPPejabatPenetap" id="idNIPPejabatPenetap" value="<?=$tempNIPPejabatPenetap?>" <?php /*?>class="required"<?php */?> />
						
						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >No. Surat Keputusan</label>
									<input type="text" class="form-control" disabled style="padding:0" value="<?=$tempNoSuratKeputusan?>"/>
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >Gol/Ruang</label>
									<input type="text" class="form-control" disabled style="padding:0" value="<?=$tempPangkatNama?>"/>
								</div>
								<div class="col-md-6">
									<label >Tanggal Tugas</label>
									<input type="text" class="form-control" disabled style="padding:0" value="<?=$tempTerhitungMulaiTanggal?>"/>
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >Tanggal Surat Keputusan</label>
									<input type="text" class="form-control" disabled style="padding:0" value="<?=$tempTanggalSuratKeputusan?>"/>
								</div>
								<div class="col-md-6">
									<label >Terhitung Mulai Tanggal</label>
									<input type="text"   name="reqTanggalTugas" id="reqTanggalTugas" maxlength="10" class="dateIna required form-control" onkeydown="return format_date(event,'reqTanggalTugas');" value="<?=$tempTanggalTugas?>" <?php /*?>class="required"<?php */?>/>
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >No. Persetujuan NIP</label>
									<input type="text"   name="reqNoPersetujuanNIP" value="<?=$tempNoPersetujuanNip?>"  class="required form-control"/>
								</div>
								<div class="col-md-6">
									<label >Tanggal Persetujuan NIP</label>
									<input type="text"   name="reqTanggalPersetujuanNIP" id="reqTanggalPersetujuanNIP" class="required form-control" maxlength="10" onkeydown="return format_date(event,'reqTanggalPersetujuanNIP');" value="<?=$tempTanggalPersetujuanNip?>" />
								</div>
							</div>
						</div>

						<div class="form-group" >
							<div class="row">
								<div class="col-md-3">
									<label >Masa Kerja</label>
									<input type="text" value="<?=$tempTh?> Tahun" disabled  class="form-control"/>
								</div>
								<div class="col-md-3">
									<label >&nbsp</label>
									<input type="text" value="<?=$tempBl?> Bulan" disabled  class="form-control"/>
								</div>
								<div class="col-md-6">
									<label >Gaji Pokok</label>
									<input type="text"  class="form-control" value="<?=$tmpeGajiPokok?>" />
								</div>
							</div>
						</div>


						<div class="form-group" >
							<div class="row">
								<div class="col-md-6">
									<label >Pendidikan</label>
									<input type="text" value="" disabled  class="form-control"/>
								</div>
								<div class="col-md-6">
									<label >Jurusan</label>
									<input type="text" value="" disabled  class="form-control"/>
								</div>
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