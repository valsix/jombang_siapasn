<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/encrypt.func.php");

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqUrl= $this->input->get("reqUrl");
$reqId= $this->input->get("reqId");
$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");

if($reqStatusBkdUptId == "1")
{
	$statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
	$set= new SuratMasukUpt();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	// echo $set->query;exit;
	$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
	$reqSetData= "SP_KARPEG_UPTD_".$reqId."_".$set->getField('NOMOR');

	$reqSetData= mencrypt(str_replace(" ","", $reqSetData), "siapasn02052018");
	$statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
}
elseif($reqStatusBkdUptId == "2")
{
	$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqSetData= $set->getField('NOMOR');

	$reqSetData= mencrypt(str_replace(" ","", $reqSetData), "siapasn02052018");
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
}
elseif($reqStatusBkdUptId == "3")
{
	$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrangUsulan(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqSetData= $reqNomorSuratKeluar= $set->getField('NOMOR_SURAT_KELUAR');
	$reqTanggalSuratKeluar= getFormattedDateJson($set->getField('TANGGAL_SURAT_KELUAR'));
	$reqSetData= mencrypt(str_replace(" ","", $reqSetData), "siapasn02052018");

	$statement_satuan_kerja= " AND STATUS_SATUAN_KERJA_BKPP = 1";
	$skerja= new SuratMasukPegawai();
	$tempsatuankerjaidbkdpp= $skerja->getSatuanKerjaId($statement_satuan_kerja);

	$skerja= new SuratMasukPegawai();
	$reqSatuanKerjaId= $skerja->getSatuanKerja($tempsatuankerjaidbkdpp);
	unset($skerja);
	//echo $reqSatuanKerjaId;exit;
	$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}

$reqGenerateQr= $reqSetData;

include_once("lib/phpqrcode/qrlib.php");
$PNG_TEMP_DIR= 'uploads/';
// $this->load->library("AES");
// $aes = new AES();

// $encrypt_text = $aes->encrypt($reqGenerateQr);
$encrypt_text = $reqGenerateQr;
$filename= $PNG_TEMP_DIR.$reqUrl."-".$reqId.'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 2;
QRcode::png($encrypt_text, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file

$footer='
<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
<td width="40%"></td>
<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
<td width="33%" style="text-align: right; "><img style="width:80px; height:80px;" src="'.$PNG_TEMP_DIR.basename($filename).'" /></td>
</tr></table>
';
echo $footer;
?>