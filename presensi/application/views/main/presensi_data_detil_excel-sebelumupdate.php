<?
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("lib/Classes/PHPExcel.php");

$CI =& get_instance();
$CI->checkUserLogin();

ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);

$this->load->model('main/PresensiRekap');
$this->load->model('main/SatuanKerja');

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqStatus= $this->input->get("reqStatus");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqPeriode= $reqBulan.$reqTahun;
$reqPencarian= $this->input->get("reqPencarian");

if($reqSatuanKerjaId== "")
{
	$IdCetakSatuanKerjaId =  $this->SATUAN_KERJA_ID;
}
else
{
	$IdCetakSatuanKerjaId =  $this->SATUAN_KERJA_ID;
}

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}
$tempSatuanKerjaId= $reqSatuanKerjaId;

$satuankerjakondisi= "";
if($reqSatuanKerjaId == "")
{
	$satuankerjakondisi= " 
	AND EXISTS
	(
		SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
		AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
	) ";
}
else
{
	$satuankerjakondisi= " 
	AND EXISTS
	(
		SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
		AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
	) ";
	
	$skerja= new SatuanKerja();
	$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
	// echo $skerja->query;exit();
	unset($skerja);
	// echo $reqSatuanKerjaId;exit;
	$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}
// echo $satuankerjakondisi;exit();
$statement= $satuankerjakondisi;

if(empty($reqStatus))
{
	$statement.= " AND P.STATUS_PEGAWAI_ID IN (1,2)";
}
else
{
	if($reqStatus == "xxx"){}
	else
	{
		$statement.= " AND 
		(
			P.STATUS_PEGAWAI_ID IN (3,4,5)
			AND 
			EXISTS
			(
				SELECT 1
				FROM
				(
					SELECT PEGAWAI_STATUS_ID
					FROM pegawai_status
					WHERE TMT >= TO_DATE('01".$reqPeriode."', 'DDMMYYYY')
				) XXX WHERE P.PEGAWAI_STATUS_ID = XXX.PEGAWAI_STATUS_ID
			)
		)
		";
	}
}
// echo $statement;exit;

$searchJson = " AND ( UPPER(P.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%' OR UPPER(P.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%' )";

$namasatuankerja= "Semua Satuan Kerja";
if(!empty($tempSatuanKerjaId))
{
	$skerja= new SatuanKerja();
	$skerja->selectByParams(array("A.SATUAN_KERJA_ID"=>$tempSatuanKerjaId));
	$skerja->firstRow();
	$namasatuankerja= $skerja->getField("NAMA");
	// echo $namasatuankerja;exit;
}

$infoperiode= $reqTahun."-".$reqBulan;

$infohasil= "rekappresensidetil";
$objPHPexcel = PHPExcel_IOFactory::load("template/".$infohasil.".xlsx");

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$obj= $objPHPexcel->getActiveSheet();

$row = 7;
$rowawal= 6;

$obj->setCellValue("A2", $namasatuankerja);
$obj->setCellValue("A3", "BULAN ".getNamePeriode($reqBulan.$reqTahun));

$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
$set= new PresensiRekap();
$jumlahdata= $set->getCountByParamsPresensiAbsen(array(), $reqPeriode, $searchJson.$statement);
$set->selectByParamsPresensiAbsen(array(), -1, -1, $reqPeriode, $searchJson.$statement, $sOrder);
//echo $set->query;exit;

if($jumlahdata > 1)
{
	$obj->insertNewRowBefore($row, $jumlahdata-1);
}
elseif($jumlahdata > 0)
{
	$obj->insertNewRowBefore($row, $jumlahdata);
}
elseif($jumlahdata == 0)
{
	$kolomakhir= "I";
	$col = 'A';	$obj->setCellValue($col.$row,'-'); $obj->mergeCells('A'.$row.':'.$kolomakhir.$row.'');
	$i++;
}

$field= array("PEGAWAI_ID", "NAMA_LENGKAP", "NIP_BARU", "TANGGAL", "WAKTU", "TIPE_PRESENSI", "TIPE_LOG", "MESIN_PRESENSI", "TANGGAL_DATA_MASUK");
// print_r($field);exit;

$nomor=1;
while($set->nextRow())
{
	$infopegawaiid= $set->getField("PEGAWAI_ID");
	$ikolom= 0;
	for($i=0; $i<count($field); $i++)
	{
		$kolom= num2alpha($ikolom);

		if($field[$i] == "NIP_BARU")
		{
			// $obj->getCellByColumnAndRow($kolom.$row)->setValueExplicit($set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			// $obj->setCellValueExplicit($kolom.$row,$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			// $obj->setValueExplicit($kolom.$row,$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);

			// $obj->getCell($kolom.$row)->setValueExplicit($set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			$obj->setCellValue($kolom.$row," ".$set->getField($field[$i]));
			// $obj->getStyle($kolom.$row)->getNumberFormat()->setFormatCode('0');
		}
		elseif($field[$i] == "TANGGAL")
		{
			$infovalue= dateToPageCheck($set->getField($field[$i]));
			$obj->setCellValue($kolom.$row,$infovalue);
		}
		else if($field[$i] == "TANGGAL_DATA_MASUK")
		{
			$infovalue= dateTimeToPageCheck($set->getField($field[$i]));
			$obj->setCellValue($kolom.$row,$infovalue);
		}
		else
		{
			$obj->setCellValue($kolom.$row,$set->getField($field[$i]));
		}

		/*if($field[$i] == "NIP_BARU")
		{
			// echo $set->getField($field[$i]);exit;
			$obj->getStyle($kolom.$row)->getNumberFormat()->setFormatCode('00');
		}*/

		$ikolom++;
	}
	$nomor++;
	$row++;
}

//exit;
if($jumlahdata > 1)
{
	$obj->removeRow($rowawal, 1);
}
elseif($jumlahdata > 0)
{
	$obj->removeRow($rowawal, 1);
	$obj->removeRow($rowawal+1, 1);
	$obj->removeRow($rowawal+2, 1);
}
elseif($jumlahdata == 0)
{
	$obj->removeRow($rowawal, 1);
	$obj->removeRow($rowawal+1, 1);
	$obj->removeRow($rowawal+2, 1);
}

// $obj->getStyle('C'.$rowawal.':C'.$row.'')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$filename = "presensidetil_".$IdCetakSatuanKerjaId."_".$reqTahun.$reqBulan."_".date('ymdhs');

header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
header ( 'Content-Disposition: attachment;filename="'.$filename.'.xlsx"' );
header ( 'Cache-Control: max-age=0' );
$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPexcel, 'Excel2007' );
$objWriter->save ( 'php://output' );
?>