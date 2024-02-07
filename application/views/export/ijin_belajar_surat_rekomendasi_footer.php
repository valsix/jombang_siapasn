<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqUrl= $this->input->get("reqUrl");

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
$set= new SuratMasukPegawai();
$set->selectByParamsCetakRekomendasi(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqGenerateQr= $set->getField('NAMA_LENGKAP');

include_once("lib/phpqrcode/qrlib.php");
$PNG_TEMP_DIR= 'uploads/';
$this->load->library("AES");
$aes = new AES();

$encrypt_text = $aes->encrypt($reqGenerateQr);
$filename= $PNG_TEMP_DIR.$reqUrl."-".$reqId.'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 2;
QRcode::png($encrypt_text, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file

$footer='
<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
<td width="40%"></td>
<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
<td width="33%" style="text-align: right; "><img src="'.$PNG_TEMP_DIR.basename($filename).'" /></td>
</tr></table>
';
echo $footer;
?>