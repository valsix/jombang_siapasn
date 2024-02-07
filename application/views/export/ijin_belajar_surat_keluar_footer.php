<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
include_once("functions/encrypt.func.php");

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqUrl= $this->input->get("reqUrl");

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
$set= new SuratMasukBkd();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqSetData= $set->getField('NOMOR_SURAT_KELUAR');
$reqSetData= mencrypt(str_replace(" ","", $reqSetData), "siapasn02052018");

$reqGenerateQr= $reqSetData;

include_once("lib/phpqrcode/qrlib.php");
$PNG_TEMP_DIR= 'uploads/';
//$this->load->library("AES");
//$aes = new AES();

//$encrypt_text = $aes->encrypt($reqGenerateQr);
$encrypt_text = $reqGenerateQr;
$filename= $PNG_TEMP_DIR.$reqUrl."-".$reqId.'.png';
$errorCorrectionLevel = 'L';
$matrixPointSize = 2;
QRcode::png($encrypt_text, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
//display generated file

//$footer='
//<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
//<td width="40%"></td>
//<td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
//<td width="33%" style="text-align: right; "><img style="width:50px; height:50px;" src="'.$PNG_TEMP_DIR.basename($filename).'" /></td>
//</tr></table>

$footer='
<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
<td width="40%"></td>
<td width="33%" align="center" style="font-weight: bold; font-style: italic;"></td>
<td width="33%" style="text-align: right; "><img style="width:80px; height:80px;" src="'.$PNG_TEMP_DIR.basename($filename).'" /></td>
</tr></table>
';
echo $footer;
?>