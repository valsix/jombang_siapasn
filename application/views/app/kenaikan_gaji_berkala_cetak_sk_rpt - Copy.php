<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/encrypt.func.php");
include_once("functions/date.func.php");

$this->load->model('KenaikanGajiBerkala');
$this->load->model('SatuanKerja');

//set_time_limit(3);
ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

/* VARIABLE */
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqJenis= $this->input->get("reqJenis");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqJenisKgb= $this->input->get("reqJenisKgb");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");


//------ PATH REPORT -------
$xml_file = "report/web.xml"; 
$data_xml = simplexml_load_file($xml_file);
$data_xml_db=2;
$data_xml_user=3;
$data_xml_pass=4;
$data_xml_path=5;
$data_xml_connection=6;
$path = $data_xml->path->path->configValue->$data_xml_path;
$connection = trim($data_xml->path->path->configValue->$data_xml_connection); 
$user = trim($data_xml->path->path->configValue->$data_xml_user);
$pass = trim($data_xml->path->path->configValue->$data_xml_pass); 
$database = trim($data_xml->path->path->configValue->$data_xml_db); 

// echo $user."-".$pass."-".$path."-".$connection;exit;

//------  Variables ------
if($reqJenis==1)
{
	$name = "CETAK_KGB";
}
elseif ($reqJenis==2) 
{
	$name = "CETAK_KGB_KOP";
}
elseif ($reqJenis==3) 
{
	$name = "CETAK_KGB_2016";
}
else
{
	$name = "CETAK_KGB_CPNS";	
}

$my_report	= $path."report\\".$name.".rpt";
$my_pdf		= $path."report\\".$name.".pdf";

//------ Create a new COM Object of Crytal Reports XI ------
/*try
{
$ObjectFactory = new COM("CrystalRuntime.Application.11");
// $ObjectFactory = new COM("WScript.Shell");
// $ObjectFactory = new COM("{166EB857-8CCD-4D83-8F27-CADDB2800374}");
	
}
catch ( exception $e )
{
echo 'caught exception: ' . $e->getMessage () . ', error trace: ' . $e->getTraceAsString ();
}
echo "asd";exit;
*/

$ObjectFactory= new COM("CrystalRuntime.Application.11");
//------ Create a instance of library Application -------
//$crapp=$ObjectFactory->CreateObject("CrystalDesignRunTime.Application.11");

//------ Open your rpt file ------
$creport = $ObjectFactory->OpenReport($my_report, 1);

//- Set database logon info - must have
$creport->Database->Tables(1)->SetLogOnInfo($connection, $database, $user, $pass);

// echo $connection."-".$database."-".$user."-".$pass;exit;


//------ Connect to Oracle 9i DataBase ------
//$crapp->LogOnServer('crdb_oracle.dll','YOUR_TNS','YOUR_TABLE','YOUR_LOGIN','YOUR_PASSWORD');

$reqPeriode = $reqBulan.$reqTahun;

//------ Put the values that you want --------
$statement .= " AND A.PERIODE = '".$reqPeriode."'";

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}

if($reqSatuanKerjaId == ""){}
else
{
	$skerja= new SatuanKerja();
	// kalau 1 maka dinas
	if($this->SATUAN_KERJA_TIPE == "1")
	{
		$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
	}
	else
	{
		$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
	}
	unset($skerja);
	// echo $reqSatuanKerjaId;exit;
	$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
	// $statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
}

if($reqPegawaiId==""){}
else
{
	$statement .= " AND A.PEGAWAI_ID IN (".$reqPegawaiId.")";
}

if($reqJenisKgb ==''){}
else
{
	$statement .= " AND CASE WHEN PR1.JENIS_KENAIKAN = 1 AND A.HUKUMAN_RIWAYAT_ID IS NULL THEN 2 WHEN A.HUKUMAN_RIWAYAT_ID IS NOT NULL THEN 3 WHEN HK.JENIS_HUKUMAN_ID = 4 THEN 4 ELSE 1 END = ".$reqJenisKgb;
}
// echo $statement;exit;
$statement .= " ORDER BY AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID), PR1.PANGKAT_ID DESC";
include_once('lib/phpqrcode/qrlib.php');

$set= new KenaikanGajiBerkala();
$set->selectByParamsData(array(), -1, -1, $statement);
echo $set->query;exit;
while($set->nextRow())
{
	$tempNoSK= $set->getField("SK_NOMOR_BARU");
	$tempNIPBaru= $set->getField("NIP_BARU");
	$tempPegawaiId= $set->getField("PEGAWAI_ID");
	$tempPeriode= $set->getField("PERIODE");
	// $tempUnix = "I_LOVE_U";
	$tempUnix = $tempPeriode."_".$tempPegawaiId;

	$codeContents= mencrypt(str_replace(" ","", "SK_KGB_BKDPP_".$tempUnix."_".$tempNoSK), "siapasn02052018");
	//echo $codeContents;exit;

	// how to save PNG codes to server 
	$tempDir= "lib/phpqrcode/uploads/"; 

	// we need to generate filename somehow,  
	// with md5 or with database ID used to obtains $codeContents... 
	$fileName = str_replace("/","_", $tempNoSK."_".$tempNIPBaru).'.png'; 
	
	$pngAbsoluteFilePath = $tempDir.$fileName; 
	$urlRelativeFilePath = $tempDir.$fileName; 
	
	if (file_exists($pngAbsoluteFilePath)) { 
		unlink($pngAbsoluteFilePath);
	} 

	// generating 
	if (!file_exists($pngAbsoluteFilePath)) { 
		QRcode::png($codeContents, $pngAbsoluteFilePath); 
		// echo "asdfasdf";exit;
	}

	$set_code = new KenaikanGajiBerkala();
	$set_code->setField("PERIODE", $reqPeriode);
	$set_code->setField("PEGAWAI_ID", $tempPegawaiId);
	$set_code->setField("QR_CODE", $fileName);
	$set_code->updateQrCode();
}

$picture =  $path."lib\\phpqrcode\\uploads\\";

// echo $statement;exit;

// $statement = " AND NIP_BARU = '196206122007012003'";

// echo $reqInfoPangkat;exit;
$creport->DiscardSavedData;
$zz = $creport->ParameterFields(1)->SetCurrentValue($statement);
$zz = $creport->ParameterFields(2)->SetCurrentValue($picture);
// $zz = $creport->ParameterFields(2)->SetCurrentValue("Kepala Badan Kepegawaian Daerah, Pendidikan dan Pelatihan");

$creport->ReadRecords();
// echo "asdfasdf";exit;


//echo 'adasdas'.$creport;

//------ Export to PDF -------
$creport->ExportOptions->DiskFileName=$my_pdf;
$creport->ExportOptions->FormatType=31;
$creport->ExportOptions->DestinationType=1;
$creport->Export(false);

//------ Release the variables
$creport = null;
$crapp = null;
$ObjectFactory = null;

$file_name = "kenaikan_gaji_berkala";

$len = filesize("$my_pdf");
header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=$file_name");
readfile("$my_pdf"); 
?> 