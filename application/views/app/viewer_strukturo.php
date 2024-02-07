<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
require_once 'lib/PHPWord/PHPWord.php';

$this->load->model('SatuanKerja');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);


$tanggalHariIni= date("d-m-Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;

$statement= " AND A.SATUAN_KERJA_ID = ".$reqId."";
$setsat= new SatuanKerja();
$setsat->selectByParams(array(), -1, -1, "", $statement);
$setsat->firstRow();
//echo $set->query;exit;
$namasingkat= $setsat->getField("NAMA_SINGKAT");

$filedocx = 'https://siapasn.jombangkab.go.id/templateworld/so/cetak/so_'.$namasingkat."_".$reqId.'.docx';
?>

<!DOCTYPE html>
<html>
<head>
<title>Viewer Struktur Organisasi</title>
</head>
<body>

<iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<? echo $filedocx; ?>' width='1000' height='600px' frameborder='0'>
    This is an embedded <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by <a target='_blank' href='http://office.com/webapps'>Office Online</a>.
</iframe>

</body>
</html>

<?
?>