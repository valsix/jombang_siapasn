<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
// include_once("lib/Classes/PHPExcel.php");
include_once("functions/date.func.php");
include_once("functions/string.func.php");
include_once("functions/default.func.php");

$this->load->model('Duk');
$this->load->library('excel');


$file = base_url().'app/loadUrl/template/cetak_duk.xlsx';

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqPangkatId= $this->input->get("reqPangkatId");

$objPHPExcel = PHPExcel_IOFactory::load($file);

/* VARIABLE */
// $objPHPexcel = PHPExcel_IOFactory::load(base_url().'app/loadUrl/template/cetak_duk.xlsx');
echo "asdf";exit;

$styleArrayFontBold = array(
	'font' => array(
	  'bold' => TRUE
	),
);

		
$currencyFormat= '_(Rp* #,##0_);_(Rp* (#,##0);_(Rp* "-"_);_(@_)';
$index_data_array=1;
for($checkbox_index=0;$checkbox_index<$index_data_array;$checkbox_index++)
{
	$sheetIndex= $checkbox_index;
	// set sheet
	$objPHPexcel->setActiveSheetIndex($sheetIndex);
	$objWorksheet = $objPHPexcel->getActiveSheet();
	
	if($checkbox_index == 0)
	{
		$row = 6;
		$tempRowAwal= 5;
		
		//$objWorksheet->setCellValue("B2",$tempKategori);
		
		$arrData="";
		$index_data= 0;
		
		if($reqSatuanKerjaId == ""){}
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

		$field= "";
		$field= array("DUK", "NAMA", "GOL_RUANG");
		$set= new Duk();
		$set->selectByParamsMonitoring($reqKategori, $statement);
		//echo $set->query;exit;
		while($set->nextRow())
		{
			for($index_field=0; $index_field < count($field); $index_field++)
			{
				$arrData[$index_data][$field[$index_field]] = $set->getField($field[$index_field]);
			}
			$index_data++;
		}
		//print_r($arrData);exit;
		
		$allRecord= $index_data;
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
			$col = 'B';	$objWorksheet->setCellValue($col.$row,'-'); $objWorksheet->mergeCells('B'.$row.':K'.$row.'');
			$i++;
		}
		
		//echo $set->query;exit;
		$nomor=1;
		for($checkbox_index=0; $checkbox_index < $index_data; $checkbox_index++)
		{
			$index_kolom= 2;
			for($i=0; $i<count($field); $i++)
			{
				$kolom= getColoms($index_kolom);
				if($field[$i] == "NO")
				{
					$objWorksheet->setCellValueExplicit($kolom.$row,$nomor, PHPExcel_Cell_DataType::TYPE_STRING);
				}
				elseif($field[$i] == "NIP_BARU")
				{
					$objWorksheet->setCellValue($kolom.$row," ".$arrData[$checkbox_index][$field[$i]]);
				}			
				elseif($field[$i] == "TGL_PELAKSANAAN")
				{
					$objWorksheet->setCellValue($kolom.$row, getFormattedDate($arrData[$checkbox_index][$field[$i]]));
				} 
					elseif($field[$i] == "TANGGAL")
				{
					$objWorksheet->setCellValue($kolom.$row, getFormattedDate($arrData[$checkbox_index][$field[$i]]));
				} 
				else
				{
					$objWorksheet->setCellValue($kolom.$row,$arrData[$checkbox_index][$field[$i]]);
				}
		
				$index_kolom++;
			}
			$nomor++;
			$row++;
		}
		
		//exit;
		if($allRecord == 1)
		{
			$objWorksheet->removeRow($tempRowAwal, 1);
		}
		elseif($allRecord > 1)
		{
			$objWorksheet->removeRow($tempRowAwal, 1);
		}
		elseif($allRecord > 0)
		{
			$objWorksheet->removeRow($tempRowAwal, 1);
			$objWorksheet->removeRow($tempRowAwal+1, 1);
			$objWorksheet->removeRow($tempRowAwal+2, 1);
		}
		
		$tempRowTotal= $row-1;
	}

}
	
$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save(base_url().'app/loadUrl/rekap_hasil.xls');

$down = base_url().'app/loadUrl/rekap_hasil.xls';
$filename= 'rekap_hasil.xls';
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
ob_clean();
flush();
readfile($down);
unlink($down);
//unlink($save);
unset($oPrinter);
exit;
		
exit();
?>