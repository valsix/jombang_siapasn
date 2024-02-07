<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/CetakIjinBelajar');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");

$statement= " AND A.CETAK_IJIN_BELAJAR_ID = ".$reqId;
$set= new CetakIjinBelajar();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqGenerateQr= $set->getField('NOMOR_SURAT_KELUAR');

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
<td width="33%" style="text-align: right; "><img style="width:50px; height:50px;" src="'.$PNG_TEMP_DIR.basename($filename).'" /></td>
</tr></table>
';
echo $footer;
?>