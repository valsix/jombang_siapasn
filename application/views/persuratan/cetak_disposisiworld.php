<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
require_once 'lib/PHPWord/PHPWord.php';

$this->load->model('persuratan/SuratMasukBkdDisposisi');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;

$reqMode = 'update';
$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
$set= new SuratMasukBkdDisposisi();
$set->selectByParamsDataSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
$reqJenisId= $set->getField("JENIS_ID");
$reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
$reqSatuanKerjaDiteruskanKepadaId= $set->getField("SATUAN_KERJA_DITERUSKAN_ID");
$reqSatuanKerjaDiteruskanKepada= $set->getField("SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA");
$reqNomor= $set->getField("NOMOR");
$reqNomorAgenda= $set->getField("NO_AGENDA");
$reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
$reqPerihal= $set->getField("PERIHAL");
$reqTerdisposisi= $set->getField("TERDISPOSISI");
$reqBatasSatuanKerjaCariId= $set->getField("BATAS_SATUAN_KERJA_CARI_ID");
$reqTanggalDisposisi= $set->getField("TANGGAL_TERIMA");
$reqIsi= $set->getField("ISI");

$reqTerbaca= $set->getField("TERBACA");
$reqTerbacaDisposisi= $set->getField("TERBACA_DISPOSISI");
$reqStatusKelompokPegawaiUsul= $set->getField("STATUS_KELOMPOK_PEGAWAI_USUL");

$tempIsiDisposisi= "";
$arrHistori= [];
$index_data= 0;
$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
$statementnode= " AND SATUAN_KERJA_ASAL_ID NOT IN (".$reqSatuanKerjaId.")";
$set_detil= new SuratMasukBkdDisposisi();
$set_detil->selectByParamsHistoriDisposisi($statement, $statementnode);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrHistori[$index_data]["SURAT_MASUK_BKD_DISPOSISI_ID"] = $set_detil->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
	$arrHistori[$index_data]["TANGGAL_DISPOSISI"] = $set_detil->getField("TANGGAL_DISPOSISI");
	$arrHistori[$index_data]["ISI"] = $set_detil->getField("ISI");
	$arrHistori[$index_data]["JABATAN_ASAL"] = $set_detil->getField("JABATAN_ASAL");
	$arrHistori[$index_data]["JABATAN_TUJUAN"] = $set_detil->getField("JABATAN_TUJUAN");
	
	if($tempIsiDisposisi == "")
	{
		$tempIsiDisposisi= "Yth.".$arrHistori[$index_data]["JABATAN_TUJUAN"]."g4nt1b4rIs-".$arrHistori[$index_data]["ISI"];
	}
	else
	{
		$tempIsiDisposisi.= "g4nt1b4rIsYth.".$arrHistori[$index_data]["JABATAN_TUJUAN"]."g4nt1b4rIs-".$arrHistori[$index_data]["ISI"];
	}
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