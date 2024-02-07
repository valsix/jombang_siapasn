<?php 
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("lib/Classes/PHPExcel.php");

$this->load->model('PenilaianSkp');
$this->load->model('SatuanKerja');

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqPangkatId= $this->input->get("reqPangkatId");

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}

$statementAktif= "";
if($reqSatuanKerjaId == "")
{
	if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
	{
		$statementAktif= " AND EXISTS(
		SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
		AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		)";
	}
}
else
{
	$statementAktif= " AND EXISTS(
	SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
	AND A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
	)";
	
	if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" || $this->STATUS_SATUAN_KERJA_BKD == 1)
	{
		$reqSatuanKerjaId= "";
		if($tempSatuanKerjaId == ""){}
		else
		{
			$reqSatuanKerjaId= $tempSatuanKerjaId;
			$skerja= new SatuanKerja();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
	}
	else
	{
		// echo $reqSatuanKerjaId;exit();
		// echo $this->SATUAN_KERJA_TIPE;exit();
		$skerja= new SatuanKerja();
		// if($this->SATUAN_KERJA_TIPE == "1")
		// {
		// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
		// }
		// else
		// {
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		// }
		// echo $skerja->query;exit();
		unset($skerja);
		// echo $reqSatuanKerjaId;exit;
		$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
	}
}

if($reqTipePegawaiId ==""){}
else
{
	$statement .= " AND A.TIPE_PEGAWAI_ID LIKE '".$reqTipePegawaiId."%'";
}

if($reqPangkatId ==''){}
else
{
	$statement .= " AND A.PANGKAT_ID = '".$reqPangkatId."'";
}

$statement .= " AND PSKP.TAHUN = '".$reqTahun."'";
$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";

// echo $statement;exit();
$objPHPexcel = PHPExcel_IOFactory::load('template/cetak_skp.xlsx');

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$objWorksheet= $objPHPexcel->getActiveSheet();

$row = 9;
$tempRowAwal= 8;

$set= new PenilaianSkp();
$allRecord= $set->getCountByParamsRekap(array(), $statement.$searchJson);
$set->selectByParamsRekap(array(), -1, -1,$statement.$searchJson);
//echo $set->query;exit;

$objWorksheet->setCellValue("C4",$reqTahun);

$sOrder= "";
if($allRecord > 1)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord-1);
}
elseif($allRecord > 0)
{
	$objWorksheet->insertNewRowBefore($row, $allRecord);
}
elseif($allRecord == 0)
{
	$col = 'B';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('B'.$row.':Q'.$row.'');
	$i++;
}

$field= "";
$field= array("NIP_BARU", "NAMA_LENGKAP", "SKP_NILAI", "SKP_HASIL", "ORIENTASI_NILAI", "INTEGRITAS_NILAI", "KOMITMEN_NILAI", "DISIPLIN_NILAI", "KERJASAMA_NILAI", "KEPEMIMPINAN_NILAI", "JUMLAH_NILAI", "RATA_NILAI", "PERILAKU_HASIL", "PRESTASI_HASIL", "SATUAN_KERJA_NAMA", "SATUAN_KERJA_INDUK");
// TMT_PANGKAT,TMT_JABATAN,TINGKAT_PENDIDIKAN

$nomor=1;
$tempTotal= 0;
while($set->nextRow())
{
	$index_kolom= 2;
	for($i=0; $i<count($field); $i++)
	{
		// TMT_PANGKAT,TMT_JABATAN,TINGKAT_PENDIDIKAN
		$kolom= getColoms($index_kolom);
		if($field[$i] == "DUK")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "NIP_BARU")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,"'".$set->getField($field[$i]), PHPExcel_Cell_DataType::TYPE_STRING);
			//$objWorksheet->setCellValueExplicit($kolom.$row,$set->getField($field[$i])."\t", PHPExcel_Cell_DataType::TYPE_STRING);
		}
		elseif($field[$i] == "TMT_PANGKAT" || $field[$i] == "TMT_JABATAN")
		{
			$tempValue= dateToPageCheck($set->getField($field[$i]));
			$objWorksheet->setCellValue($kolom.$row,$tempValue);
		}
		elseif($field[$i] == "PERSENTASE")
		{
			$tempValue= str_replace(".",",",$set->getField($field[$i]));
			$objWorksheet->setCellValue($kolom.$row,$tempValue);
		}
		else
		{
			$objWorksheet->setCellValue($kolom.$row,$set->getField($field[$i]));
		}

		$index_kolom++;
	}
	$nomor++;
	$row++;
}

//exit;
if($allRecord > 1)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
}
elseif($allRecord > 0)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
	$objWorksheet->removeRow($tempRowAwal+1, 1);
	$objWorksheet->removeRow($tempRowAwal+2, 1);
}
elseif($allRecord == 0)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
	$objWorksheet->removeRow($tempRowAwal+1, 1);
	$objWorksheet->removeRow($tempRowAwal+2, 1);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('template/cetak_skp.xls');

$down = 'template/cetak_skp.xls';
$filename= 'cetak_skp.xls';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
readfile($down);
unlink($down);
exit;
?>