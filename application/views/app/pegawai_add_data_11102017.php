<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

/* CHECK USER LOGIN 
$CI =& get_instance();
$CI->checkUserLogin();*/

$this->load->model('Pegawai');
$this->load->model('Bank');
$this->load->model('Agama');
$this->load->model('SatuanKerja');
$this->load->model('JenisPegawai');

$set= new Pegawai();
$setBank= new Bank();
$setAgama= new Agama();
$setSatuanKerja= new SatuanKerja();
$setJenisPegawai= new JenisPegawai();

$setBank->selectByParams();
$setAgama->selectByParams();
$setSatuanKerja->selectByParams();
$setJenisPegawai->selectByParams();

$reqId = $this->input->get("reqId");

if($reqId == ""){
	$reqMode = "insert";
}
else
{
	$reqMode = "update";	
	$set->selectByParams(array("PEGAWAI_ID"=>$reqId));
	$set->firstRow();
	$reqStatus= $set->getField("STATUS");
	$reqSatuanKerja= $set->getField("SATUAN_KERJA_ID");
	$reqJenisPegawai= $set->getField("JENIS_PEGAWAI_ID");
	$reqTipePegawai= $set->getField("TIPE_PEGAWAI_ID");
	$reqStatusPegawai= $set->getField("STATUS_PEGAWAI");
	$reqNipLama= $set->getField("NIP_LAMA");
	$reqNipBaru= $set->getField("NIP_BARU");
	$reqNama= $set->getField("NAMA");
	$reqGelarDepan= $set->getField("GELAR_DEPAN");
	$reqGelarBelakang= $set->getField("GELAR_BELAKANG");
	$reqTempatLahir= $set->getField("TEMPAT_LAHIR");
	$reqTanggalLahir= $set->getField("TANGGAL_LAHIR");
	$reqJenisKelamin= $set->getField("JENIS_KELAMIN");
	$reqStatusKawin= $set->getField("STATUS_KAWIN");
	$reqSukuBangsa= $set->getField("SUKU_BANGSA");
	$reqGolonganDarah= $set->getField("GOLONGAN_DARAH");
	$reqEmail= $set->getField("EMAIL");
	$reqAlamat= $set->getField("ALAMAT");
	$reqRt= $set->getField("RT");
	$reqRw= $set->getField("RW");
	$reqKodePos= $set->getField("KODEPOS");
	$reqTelepon= $set->getField("TELEPON");
	$reqHp= $set->getField("HP");
	$reqKartuPegawai= $set->getField("KARTU_PEGAWAI");
	$reqAskes= $set->getField("ASKES");
	$reqTaspen= $set->getField("TASPEN");
	$reqNpwp= $set->getField("NPWP");
	$reqNik= $set->getField("NIK");
	$reqNoRekening= $set->getField("NO_REKENING");
	$reqSkKonversiNip= $set->getField("SK_KONVERSI_NIP");
	$reqBank= $set->getField("BANK_ID");
	$reqAgama= $set->getField("AGAMA_ID");
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
				url:'pegawai_json/add',
				onSubmit:function(){
					return $(this).form('validate');
				},
				success:function(data){
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
	<div id="judul-popup">Tambah Pegawai</div>
	<div id="konten">
		<div id="popup-tabel2">
			<form id="ff" method="post"  novalidate enctype="multipart/form-data">
				<div class="simpleTabs" style="margin-left:-9px !important">
					<ul class="simpleTabsNavigation">
						<li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i>Data Pribadi</a></li>
						<li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i>Data Alamat</a></li>
						<li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i>Data Kontak</a></li>
						<li><a href="#" id="btnReload"><i class="fa fa-line-chart fa-lg"></i>Data Lainnya</a></li>
					</ul>

					<div class="simpleTabsContent">
						<table class="table">
							<!-- <thead> -->
							<tr>
								<td>Nama</td>
								<td>:</td>
								<td>
									<input required name="reqNama" class="easyui-validatebox"   type="text"  value="<?=$reqNama?>" />
								</td>
							</tr>
							
							<tr>
								<td>Tempat Lahir</td>
								<td>:</td>
								<td>
									<input name="reqTempatLahir" class="easyui-validatebox"   type="text"  value="<?=$reqTempatLahir?>" />
								</td>
							</tr>
							<tr>
								<td>Tanggal Lahir</td>
								<td>:</td>
								<td>
									<input name="reqTanggalLahir" class="easyui-validatebox"   type="date"  value="<?=$reqTanggalLahir?>" />
								</td>
							</tr>
							<tr>
								<td>Jenis Kelamin</td>
								<td>:</td>
								<td>
									<select name="reqJenisKelamin" id="reqJenisKelamin"   >
										<option value="1" <? if($reqJenisKelamin=='1') echo ' selected'?>>Laki-laki</option>
										<option value="2" <? if($reqJenisKelamin=='2') echo ' selected'?>>Perempuan</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Status Kawin</td>
								<td>:</td>
								<td>
									<select name="reqStatusKawin" id="reqStatusKawin"   >
										<option value="1" <? if($reqStatusKawin=='1') echo ' selected'?>>Menikah</option>
										<option value="2" <? if($reqStatusKawin=='2') echo ' selected'?>>Belum Menikah</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Suku Bangsa</td>
								<td>:</td>
								<td>
									<input name="reqSukuBangsa" class="easyui-validatebox"   type="text"  value="<?=$reqSukuBangsa?>" />
								</td>
							</tr>
							<tr>
								<td>Golongan Darah</td>
								<td>:</td>
								<td>
									<select name="reqGolonganDarah" id="reqGolonganDarah"   >
										<option value="A" <? if($reqGolonganDarah=='A') echo ' selected'?>>A</option>
										<option value="B" <? if($reqGolonganDarah=='B') echo ' selected'?>>B</option>
										<option value="AB" <? if($reqGolonganDarah=='AB') echo ' selected'?>>AB</option>
										<option value="O" <? if($reqGolonganDarah=='O') echo ' selected'?>>O</option>
									</select>
								</td>
							</tr>
							
							<tr>
								<td>No Rekening</td>
								<td>:</td>
								<td>
									<input name="reqNoRekening" class="easyui-validatebox"   type="text"  value="<?=$reqNoRekening?>" />
								</td>
							</tr>
							
							<tr>
								<td>Bank</td>
								<td>:</td>
								<td>
									<select name="reqBank" id="reqBank"   >
										<?
										while ($setBank->nextRow()) 
										{
											?>
											<option value="<?=$setBank->getField('BANK_ID')?>" <? if($reqBank==$setBank->getField("BANK_ID")) echo ' selected'?>><?=$setBank->getField("NAMA")?></option>
											<?
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Agama</td>
								<td>:</td>
								<td>
									<select name="reqAgama" id="reqAgama"   >
										<?
										while ($setAgama->nextRow()) 
										{
											?>
											<option value="<?=$setAgama->getField('AGAMA_ID')?>" <? if($reqAgama==$setAgama->getField("AGAMA_ID")) echo ' selected'?>><?=$setAgama->getField("NAMA")?></option>
											<?
										}
										?>
									</select>
								</td>
							</tr>
							<!-- </thead> -->
						</table>
					</div>
					<div class="simpleTabsContent">
						<table class="table">
							<!-- <thead> -->
							<tr>
								<td>Alamat</td>
								<td>:</td>
								<td>
									<input name="reqAlamat" class="easyui-validatebox"   type="text"  value="<?=$reqAlamat?>" />
								</td>
							</tr>
							<tr>
								<td>RT</td>
								<td>:</td>
								<td>
									<input name="reqRt" class="easyui-validatebox"   type="text"  value="<?=$reqRt?>" />
								</td>
							</tr>
							<tr>
								<td>RW</td>
								<td>:</td>
								<td>
									<input name="reqRw" class="easyui-validatebox"   type="text"  value="<?=$reqRw?>" />
								</td>
							</tr>
							<tr>
								<td>Kode Pos</td>
								<td>:</td>
								<td>
									<input name="reqKodePos" class="easyui-validatebox"   type="text"  value="<?=$reqKodePos?>" />
								</td>
							</tr>
							<!-- </thead> -->
						</table>
					</div>
					<div class="simpleTabsContent">
						<table class="table">
							<!-- <thead> -->
							<tr>
								<td>Email</td>
								<td>:</td>
								<td>
									<input name="reqEmail" class="easyui-validatebox"   type="text"  value="<?=$reqEmail?>" />
								</td>
							</tr>
							<tr>
								<td>Telepon</td>
								<td>:</td>
								<td>
									<input name="reqTelepon" class="easyui-validatebox"   type="text"  value="<?=$reqTelepon?>" />
								</td>
							</tr>
							<tr>
								<td>HP</td>
								<td>:</td>
								<td>
									<input name="reqHp" class="easyui-validatebox"   type="text"  value="<?=$reqHp?>" />
								</td>
							</tr>
							<tr>
								<td>Kartu Pegawai</td>
								<td>:</td>
								<td>
									<input name="reqKartuPegawai" class="easyui-validatebox"   type="text"  value="<?=$reqKartuPegawai?>" />
								</td>
							</tr>
							<!-- </thead> -->
						</table>
					</div>
					<div class="simpleTabsContent">
						<table class="table">
							<!-- <thead> -->
							<!-- <tr>
								<td>Status</td>
								<td>:</td>
								<td>
									<select name="reqStatus" id="reqStatus"   >
										<option value=''>Aktif</option>
										<option value="1" <? if($reqStatus=='1') echo ' selected'?>>Tidak Aktif</option>
									</select>
								</td>
							</tr> -->
							<tr>
								<td>Satuan Kerja</td>
								<td>:</td>
								<td>
									<select name="reqSatuanKerja" id="reqSatuanKerja"   >
										<?
										while ($setSatuanKerja->nextRow()) 
										{
											?>
											<option value="<?=$setSatuanKerja->getField('SATUAN_KERJA_ID')?>" <? if($reqSatuanKerja==$setSatuanKerja->getField("SATUAN_KERJA_ID")) echo ' selected'?>><?=$setSatuanKerja->getField("NAMA")?></option>
											<?
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Jenis Pegawai</td>
								<td>:</td>
								<td>
									<select name="reqJenisPegawai" id="reqJenisPegawai"   >
										<?
										while ($setJenisPegawai->nextRow()) 
										{
											?>
											<option value="<?=$setJenisPegawai->getField('JENIS_PEGAWAI_ID')?>" <? if($reqJenisPegawai==$setJenisPegawai->getField("JENIS_PEGAWAI_ID")) echo ' selected'?>><?=$setJenisPegawai->getField("NAMA")?></option>
											<?
										}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Tipe Pegawai (id)</td>
								<td>:</td>
								<td>
									<input name="reqTipePegawai" class="easyui-validatebox"   type="number"  value="<?=$reqTipePegawai?>" />
								</td>
							</tr>
							<tr>
								<td>Status Pegawai</td>
								<td>:</td>
								<td>
									<input name="reqStatusPegawai" class="easyui-validatebox"   type="number"  value="<?=$reqStatusPegawai?>" />
								</td>
							</tr>
							<tr>
								<td>NIP Lama</td>
								<td>:</td>
								<td>
									<input name="reqNipLama" class="easyui-validatebox"   type="text"  value="<?=$reqNipLama?>" />
								</td>
							</tr>
							<tr>
								<td>NIP Baru</td>
								<td>:</td>
								<td>
									<input required name="reqNipBaru" class="easyui-validatebox"   type="text"  value="<?=$reqNipBaru?>" />
								</td>
							</tr>
							<tr>
								<td>Gelar Depan</td>
								<td>:</td>
								<td>
									<input name="reqGelarDepan" class="easyui-validatebox"   type="text"  value="<?=$reqGelarDepan?>" />
								</td>
							</tr>
							<tr>
								<td>Gelar Belakang</td>
								<td>:</td>
								<td>
									<input name="reqGelarBelakang" class="easyui-validatebox"   type="text"  value="<?=$reqGelarBelakang?>" />
								</td>
							</tr>
							<tr>
								<td>Askes</td>
								<td>:</td>
								<td>
									<input name="reqAskes" class="easyui-validatebox"   type="text"  value="<?=$reqAskes?>" />
								</td>
							</tr>
							<tr>
								<td>Taspen</td>
								<td>:</td>
								<td>
									<input name="reqTaspen" class="easyui-validatebox"   type="text"  value="<?=$reqTaspen?>" />
								</td>
							</tr>

							<tr>
								<td>NPWP</td>
								<td>:</td>
								<td>
									<input name="reqNpwp" class="easyui-validatebox"   type="text"  value="<?=$reqNpwp?>" />
								</td>
							</tr>
							<tr>
								<td>NIK</td>
								<td>:</td>
								<td>
									<input name="reqNik" class="easyui-validatebox"   type="text"  value="<?=$reqNik?>" />
								</td>
							</tr>
							<tr>
								<td>SK Konversi NIP</td>
								<td>:</td>
								<td>
									<input name="reqSkKonversiNip" class="easyui-validatebox"   type="text"  value="<?=$reqSkKonversiNip?>" />
								</td>
							</tr>
							<!-- </thead> -->
						</table>
					</div>
				</div>

				<input type="hidden" name="reqId" value="<?=$reqId?>" />
				<input type="hidden" name="reqMode" value="<?=$reqMode?>" />
				<input type="submit" name="reqSubmit"  class="btn btn-primary" value="Submit" />
			</form>
		</div>
	</div>
</body>
</html>