<?php 
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("lib/Classes/PHPExcel.php");

$this->load->model('Duk');
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

$reqKondisiSatuanKerjaId= "";
if($reqSatuanKerjaId == ""){}
else
{
	if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
	{
		$reqSatuanKerjaId= "";
		if($tempSatuanKerjaId == ""){}
		else
		{
			$reqSatuanKerjaId= $tempSatuanKerjaId;
			$skerja= new SatuanKerja();
			$reqKondisiSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
		}
	}
	else
	{
		$skerja= new SatuanKerja();
		if($this->SATUAN_KERJA_TIPE == "1")
		{
			$reqKondisiSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
		}
		else
		{
			$reqKondisiSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		}
		unset($skerja);
	}
}

if($reqSatuanKerjaId == "")
{
	$statement= " AND A.SATUAN_KERJA_PROSES_ID IS NULL";
}
else
{
	$statement= " AND A.SATUAN_KERJA_PROSES_ID = ".$reqSatuanKerjaId;
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

$statement .= " AND A.PERIODE = '".$reqBulan.$reqTahun."'";

// echo $statement;exit();
$objPHPexcel = PHPExcel_IOFactory::load('template/cetak_duk.xlsx');

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$objWorksheet= $objPHPexcel->getActiveSheet();

$row = 10;
$tempRowAwal= 9;

$set= new Duk();
$allRecord= $set->getCountByParamsCetak($statement);
$set->selectByParamsCetak($statement);
//echo $set->query;exit;

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
	$col = 'A';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('A'.$row.':C'.$row.'');
	$i++;
}

$field= "";
$field= array("DUK", "NAMA", "NIP_BARU", "GOL_RUANG", "TMT_PANGKAT", "JABATAN", "ESELON", "TMT_JABATAN", "MASA_KERJA_TAHUN", "MASA_KERJA_BULAN", "DIKLAT_STRUKTURAL", "TAHUN_DIKLAT", "JUMLAH_JAM_DIKLAT", "PENDIDIKAN_NAMA", "TAHUN_LULUS", "TINGKAT_PENDIDIKAN", "USIA");
// TMT_PANGKAT,TMT_JABATAN,TINGKAT_PENDIDIKAN

$nomor=1;
$tempTotal= 0;
while($set->nextRow())
{
	$index_kolom= 1;
	for($i=0; $i<count($field); $i++)
	{
		// TMT_PANGKAT,TMT_JABATAN,TINGKAT_PENDIDIKAN
		$kolom= getColoms($index_kolom);
		if($field[$i] == "DUK")
		{
			$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
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
$objWriter->save('template/cetak_duk.xls');

$down = 'template/cetak_duk.xls';
$filename= 'cetak_duk.xls';
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