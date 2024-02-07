<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
require_once 'lib/PHPWord/PHPWord.php';

$this->load->model('StrukturOrg');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;

$reqMode = 'update';

$tempIsiDisposisi= "";
$arrHistori= [];
$index_data= 0;
$statement= $reqId;
$statementnode= "";
$set_detil= new StrukturOrg();
$set_detil->selectByParamsSatkerId($statement, $statementnode);
echo $set_detil->query;exit;
while($set_detil->nextRow())
{

	$arrHistori[$index_data]["SATKER_ID"] = $set_detil->getField("SATKER_ID");
	$arrHistori[$index_data]["NAMA_SATKER"] = $set_detil->getField("NAMA_SATKER");
	$arrHistori[$index_data]["NIP"] = $set_detil->getField("NIP");
	$arrHistori[$index_data]["NAMA"] = $set_detil->getField("NAMA");
	$arrHistori[$index_data]["LAHIR"] = $set_detil->getField("LAHIR");
	$arrHistori[$index_data]["TL"] = $set_detil->getField("TL");
	$arrHistori[$index_data]["PANG"] = $set_detil->getField("PANG");
	$arrHistori[$index_data]["TMTPANG"] = $set_detil->getField("TMTPANG");
	$arrHistori[$index_data]["TMTJAB"] = $set_detil->getField("TMJAB");

	$index_data++;
}
unset($set_detil);
$jumlah_histori= $index_data;
//print_r($arrHistori);exit;
//echo $tempIsiDisposisi;exit;

$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate('templateworld/disposisi.docx');
$document->setValue("REQSURATDARI", $reqSatkerAsalNama);
$document->setValue("REQTANGGALSURATDARI", $reqTanggal);
$document->setValue("REQNOMORSURAT", $reqNomor);
$document->setValue("REQPERIHAL", $reqPerihal);
$document->setValue("REQDITERIMATANGGAL", $reqTanggalDisposisi);
$document->setValue("REQNOAGENDA", $reqNomorAgenda);
$document->setValue("REQKEPADA", $reqSatuanKerjaDiteruskanKepada);
$document->setValue("REQISIDISPOSISI", $tempIsiDisposisi);

$document->save('templateworld/disposisicetak.docx');

$down = 'templateworld/disposisicetak.docx';
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($down));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
readfile($down);
unlink($down);
exit;
?>