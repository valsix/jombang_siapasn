<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("lib/Classes/PHPExcel.php");

// ini_set('memory_limit', -1);
// ini_set('max_execution_time', -1);

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

// ini_set('display_errors', '1'); 
// //ini_set('display_startup_errors', '1'); 
// error_reporting(E_ALL);

$tempPeriode= str_replace("-","_",date("d-m-Y"));

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("talent/RekapTalent2023");
$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();
$inforumpun= $vfpeg->inforumpun();

$reqModeDinas= $this->input->get("reqModeDinas");
$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqEselonGroupId= $this->input->get("reqEselonGroupId");
$reqTipePegawaiId= $this->input->get("reqTipePegawaiId");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqSatuanKerjaTeknisId= $tempSatuanKerjaId= $reqSatuanKerjaId;
$reqButtonMode= $this->input->get("reqButtonMode");

if($reqSatuanKerjaId == "")
{
	$tempSatuanKerjaId= $reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}

// untuk filter global
// $reqSatuanKerjaId= 66;
// $reqTipePegawaiId= 11;
$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "mode"=>"tanpastatus");
// print_r($arrparam);exit;
$statementglobal= $vfpeg->getparampegawai($arrparam);
// echo $statementglobal;exit;

$searchJson= "";
if(!empty($reqPencarian))
{
	$searchJson= " 
	AND 
	(
		UPPER(A.NAMA_LENGKAP) LIKE '%".strtoupper($reqPencarian)."%'
		OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($reqPencarian)."%'
	)";
}

$objPHPexcel = PHPExcel_IOFactory::load('template/rekap_sidak.xlsx');
// echo "Asd";exit;
$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$objWorksheet= $objPHPexcel->getActiveSheet();

// $objWorksheet->setCellValue("A4", "PERIODE : ".getNamePeriode($reqBulan.$reqTahun));

$row = 10;
$tempRowAwal= 9;

$arrkolom= array("PEGAWAI_ID", "NIP_BARU", "NAMA_LENGKAP", "SATUAN_KERJA_NAMA", "PENILAIAN_SKP_ID", "PREDIKAT_KINERJA", "SKOR_KINERJA", "PENGHARGAAN_ID", "PENGHARGAAN_NAMA", "PENGHARGAAN_NILAI", "HASIL_PENGHARGAAN", "HUKUMAN_ID", "HUKUMAN_NAMA", "HUKUMAN_NILAI", "HASIL_HUKUMAN", "PENILAIAN_KOMPETENSI_ID", "SKOR_KOMPETENSI", "SKOR_KONVERSI_KOMPETENSI", "HASIL_KOMPETENSI", "HASIL_JABATAN_MASA_KERJA", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_A", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_P", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_M", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_H", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_E", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_PU", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_T", "DIKLAT_STRUKTURAL_NAMA", "DIKLAT_STRUKTURAL_NILAI", "HASIL_DIKLAT_STRUKTURAL", "HASIL_DIKLAT_KURSUS_RUMPUN_A", "HASIL_DIKLAT_KURSUS_RUMPUN_P", "HASIL_DIKLAT_KURSUS_RUMPUN_M", "HASIL_DIKLAT_KURSUS_RUMPUN_H", "HASIL_DIKLAT_KURSUS_RUMPUN_E", "HASIL_DIKLAT_KURSUS_RUMPUN_PU", "HASIL_DIKLAT_KURSUS_RUMPUN_T", "HASIL_PENDIDIKAN_RUMPUN_A", "HASIL_PENDIDIKAN_RUMPUN_P", "HASIL_PENDIDIKAN_RUMPUN_M", "HASIL_PENDIDIKAN_RUMPUN_H", "HASIL_PENDIDIKAN_RUMPUN_E", "HASIL_PENDIDIKAN_RUMPUN_PU", "HASIL_PENDIDIKAN_RUMPUN_T", "SKOR_PER_RUMPUN_A", "SKOR_PER_RUMPUN_P", "SKOR_PER_RUMPUN_M", "SKOR_PER_RUMPUN_H", "SKOR_PER_RUMPUN_E", "SKOR_PER_RUMPUN_PU", "SKOR_PER_RUMPUN_T", "SKOR_POTENSIAL", "KUADRAN_PEGAWAI", "NAMA_POTENSIAL");
$set= new RekapTalent2023();
$statement= "";
// $statement=" AND A.PEGAWAI_ID IN (8300,1862,4089,11761, 1)";
// $statement=" AND A.PEGAWAI_ID IN (8300,8887)";
// $statement=" AND A.PEGAWAI_ID IN (8300)";
// $statement=" AND A.PEGAWAI_ID IN (406)";
// $statement=" AND A.PEGAWAI_ID IN (8300,25,111,406)";
// $statement=" AND A.PEGAWAI_ID IN (3312)";

$globalpegawai= "";
$arrRekap= [];
$set->selectbypegawai2023(array(),-1,-1, $statementglobal.$statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$vpegawaiid= $set->getField("PEGAWAI_ID");
	$globalpegawai= getconcatseparator($globalpegawai, $vpegawaiid);
	$arrdata= [];
	foreach ($arrkolom as $value) 
	{
    	$arrdata[$value]= $set->getField($value);
	}
    array_push($arrRekap, $arrdata);
}
unset($set);
// print_r($arrRekap);exit;
// echo $globalpegawai;exit;
// $statement=" AND A.PEGAWAI_ID IN (8300,1862,4089,11761, 1)";
$statement= "";
if(!empty($globalpegawai))
{
	$statement=" AND A.PEGAWAI_ID IN (".$globalpegawai.")";
}
// $statement=" AND A.PEGAWAI_ID IN (8300,8887)";
// $statement=" AND A.PEGAWAI_ID IN (406)";

$set= new RekapTalent2023();
$arrkolom= array("JABATAN_RIWAYAT_ID", "PEGAWAI_ID", "JENJANG_NAMA", "SKOR_JABATAN", "LAMA_TAHUN", "LAMA_BULAN", "TOTAL_LAMA", "NILAI_JENJANG", "NAMA_JABATAN", "BIDANG_URUSAN", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T");
$arrJabatanRekap= [];
$set->selectbypegawaijabatan2023(array(),-1,-1,$statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	foreach ($arrkolom as $value) 
	{
    	$arrdata[$value]= $set->getField($value);
	}
    array_push($arrJabatanRekap, $arrdata);
}
unset($set);
// print_r($arrJabatanRekap);exit;

$set= new RekapTalent2023();
$arrkolom= array("DIKLAT_KURSUS_ID", "PEGAWAI_ID", "DIKLAT_KURSUS_NAMA", "DIKLAT_KURSUS_JP", "BIDANG_TERKAIT_NAMA", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T");
$arrDiklatKursusRekap= [];
$set->selectbypegawaidiklatkursus2023(array(),-1,-1,$statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	foreach ($arrkolom as $value) 
	{
    	$arrdata[$value]= $set->getField($value);
	}
    array_push($arrDiklatKursusRekap, $arrdata);
}
unset($set);
// print_r($arrDiklatKursusRekap);exit;

$set= new RekapTalent2023();
$arrkolom= array("PENDIDIKAN_RIWAYAT_ID", "PEGAWAI_ID", "JENJANG_NAMA", "JURUSAN", "TANGGAL_STTB", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T");
$arrPendidikanRekap= [];
$set->selectbypegawaipendidikan2023(array(),-1,-1,$statement);
// echo $set->query;exit;
while($set->nextRow())
{
	$arrdata= [];
	foreach ($arrkolom as $value) 
	{
    	$arrdata[$value]= $set->getField($value);
	}
    array_push($arrPendidikanRekap, $arrdata);
}
unset($set);
// print_r($arrPendidikanRekap);exit;

$jumlahrowbatastotal= 0;
$jumlahrowdetil= 0;
$arrjumlahdetil= [];
foreach ($arrRekap as $key => $value) 
{
	$vpegawaiid= $value["PEGAWAI_ID"];
	// echo $vpegawaiid;
	$jumlahrowbatastotal+=2;

	$jumlahjabatan= 0;
	if(!empty($arrJabatanRekap))
	{
		$arrjabatan= in_array_column($vpegawaiid, "PEGAWAI_ID", $arrJabatanRekap);
		if(!empty($arrjabatan))
		{
			$jumlahjabatan= count($arrjabatan);
		}
		// print_r($arrjabatan);exit;
	}

	$jumlahdiklatkursus= 0;
	if(!empty($arrDiklatKursusRekap))
	{
		$arrdiklatkursus= in_array_column($vpegawaiid, "PEGAWAI_ID", $arrDiklatKursusRekap);
		if(!empty($arrdiklatkursus))
		{
			$jumlahdiklatkursus= count($arrdiklatkursus);
		}
		// print_r($arrdiklatkursus);exit;
	}

	$jumlahpendidikan= 0;
	if(!empty($arrPendidikanRekap))
	{
		$arrpendidikan= in_array_column($vpegawaiid, "PEGAWAI_ID", $arrPendidikanRekap);
		if(!empty($arrpendidikan))
		{
			$jumlahpendidikan= count($arrpendidikan);
		}
		// print_r($arrpendidikan);exit;
	}

	$getmaxvariable= getmaxvariable($jumlahjabatan, $jumlahdiklatkursus, $jumlahpendidikan);
	if($getmaxvariable > 0)
	{
		$arrdata= [];
		$arrdata["PEGAWAI_ID"]= $vpegawaiid;
		$arrdata["JUMLAH"]= $getmaxvariable;
		$arrdata["JUMLAH_JABATAN"]= $jumlahjabatan;
		$arrdata["arrjabatan"]= $arrjabatan;
		$arrdata["JUMLAH_DIKLAT_KURSUS"]= $jumlahdiklatkursus;
		$arrdata["arrdiklatkursus"]= $arrdiklatkursus;
		$arrdata["JUMLAH_PENDIDIKAN"]= $jumlahpendidikan;
		$arrdata["arrpendidikan"]= $arrpendidikan;
		array_push($arrjumlahdetil, $arrdata);

		if($getmaxvariable > 1)
		{
			$jumlahrowdetil+= $getmaxvariable;
		}
	}
	// echo "-<br/>";
}
// print_r($arrjumlahdetil);exit;

$allRecord= count($arrRekap)+$jumlahrowdetil+$jumlahrowbatastotal;
// echo $allRecord;exit;

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
}

$field= array("SATUAN_KERJA_NAMA", "NAMA_NIP_BARU", "PREDIKAT_KINERJA", "SKOR_KINERJA", "PENGHARGAAN_NAMA", "PENGHARGAAN_NILAI", "HUKUMAN_NAMA", "HUKUMAN_NILAI", "SKOR_KOMPETENSI", "SKOR_KONVERSI_KOMPETENSI"
	// untuk jabatan masa kerja
	, "", "", "", "", ""
	// untuk jabatan
	, "", "", "", "", "", "", "", "", ""
	, "DIKLAT_STRUKTURAL_JENJANG", "DIKLAT_STRUKTURAL_NAMA", "DIKLAT_STRUKTURAL_NILAI"
	// untuk diklat kursus
	, "", "", "", "", "", "", "", "", "", ""
	// untuk pendidikan
	, "", "", "", "", "", "", "", "", ""
	, "SKOR_PER_RUMPUN_A", "SKOR_PER_RUMPUN_P", "SKOR_PER_RUMPUN_M", "SKOR_PER_RUMPUN_H", "SKOR_PER_RUMPUN_E", "SKOR_PER_RUMPUN_PU", "SKOR_PER_RUMPUN_T"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
	, "RUMPUN_NAMA", "RUMPUN_SKOR"
);

$fieldtotal= array("", "", "", "", "", "HASIL_PENGHARGAAN", "", "HASIL_HUKUMAN", "", "HASIL_KOMPETENSI"
	// untuk jabatan masa kerja
	, "", "", "", "", "HASIL_JABATAN_MASA_KERJA"
	// untuk jabatan
	, "", "", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_A", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_P", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_M", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_H", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_E", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_PU", "HASIL_JABATAN_MASA_JABATAN_RUMPUN_T"
	, "", "", "HASIL_DIKLAT_STRUKTURAL"
	// untuk diklat kursus
	, "", "", "", "HASIL_DIKLAT_KURSUS_RUMPUN_A", "HASIL_DIKLAT_KURSUS_RUMPUN_P", "HASIL_DIKLAT_KURSUS_RUMPUN_M", "HASIL_DIKLAT_KURSUS_RUMPUN_H", "HASIL_DIKLAT_KURSUS_RUMPUN_E", "HASIL_DIKLAT_KURSUS_RUMPUN_PU", "HASIL_DIKLAT_KURSUS_RUMPUN_T"
	// untuk pendidikan
	, "", "", "HASIL_PENDIDIKAN_RUMPUN_A", "HASIL_PENDIDIKAN_RUMPUN_P", "HASIL_PENDIDIKAN_RUMPUN_M", "HASIL_PENDIDIKAN_RUMPUN_H", "HASIL_PENDIDIKAN_RUMPUN_E", "HASIL_PENDIDIKAN_RUMPUN_PU", "HASIL_PENDIDIKAN_RUMPUN_T"
	, "", "", "", "", "", "", ""
);

$detilfieldjabatan= array(
	// untuk jabatan masa kerja
	"JENJANG_NAMA", "SKOR_JABATAN", "LAMA_TAHUN", "LAMA_BULAN", "NILAI_JENJANG"
	// untuk jabatan
	, "NAMA_JABATAN", "BIDANG_URUSAN", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T"
);

$detilfielddiklatkursus= array(
	// untuk diklat kursus
	"DIKLAT_KURSUS_NAMA", "DIKLAT_KURSUS_JP", "BIDANG_TERKAIT_NAMA", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T"
);

$detilfieldpendidikan= array(
	// untuk pendidikan
	"JENJANG_NAMA", "JURUSAN", "RUMPUN_A", "RUMPUN_P", "RUMPUN_M", "RUMPUN_H", "RUMPUN_E", "RUMPUN_PU", "RUMPUN_T"
);

foreach ($arrRekap as $key => $value) 
{
	$arrrangking= array(
		array("KODE"=>"A", "NILAI"=>$value["SKOR_PER_RUMPUN_A"])
		, array("KODE"=>"P", "NILAI"=>$value["SKOR_PER_RUMPUN_P"])
		, array("KODE"=>"M", "NILAI"=>$value["SKOR_PER_RUMPUN_M"])
		, array("KODE"=>"H", "NILAI"=>$value["SKOR_PER_RUMPUN_H"])
		, array("KODE"=>"E", "NILAI"=>$value["SKOR_PER_RUMPUN_E"])
		, array("KODE"=>"PU", "NILAI"=>$value["SKOR_PER_RUMPUN_PU"])
		, array("KODE"=>"T", "NILAI"=>$value["SKOR_PER_RUMPUN_T"])
	);
	// print_r($arrrangking);
	new_array_multisort($arrrangking, ['NILAI' => SORT_DESC, 'KODE' => SORT_ASC]);
	// print_r($arrrangking);exit;

	$vpegawaiid= $value["PEGAWAI_ID"];
	$rowrangking= 0;
	$rowdetil= $row;
	$detilkolomawal= $rowdatacolom= 0;
	foreach ($field as $vd) {

		// ambil row rangking
		if($rowrangking == 0 && $vd == "RUMPUN_NAMA")
		{
			$rowrangking= $rowdatacolom;
		}

		$vfield= "";
		if($vd == "NAMA_NIP_BARU")
		{
			$vfield= $value["NAMA_LENGKAP"]."\n".$value["NIP_BARU"];
			// $vfield= $value["NAMA_LENGKAP"];
		}
		else if($vd == "DIKLAT_STRUKTURAL_JENJANG")
		{
			$vfield= "Sesuai Jenjang Jabatan";
		}
		else
			$vfield= $value[$vd];

		// echo toAlpha($rowdatacolom).$row."<br/>";
		$objWorksheet->setCellValue(toAlpha($rowdatacolom).$row, $vfield);
		$rowdatacolom++;
	}
	$detilkolomakhir= $rowdatacolom;

	// mengisi rating data
	if($rowrangking > 0)
	{
		foreach ($arrrangking as $kr => $vr) 
		{
			// print_r($vr);exit;
			$objWorksheet->setCellValue(toAlpha($rowrangking).$row, $vr["KODE"]);$rowrangking++;
			$objWorksheet->setCellValue(toAlpha($rowrangking).$row, $vr["NILAI"]);$rowrangking++;
		}
	}

	// print_r($value);exit;
	$row++;

	$carijumlahdetil= in_array_column($vpegawaiid, "PEGAWAI_ID", $arrjumlahdetil);
	if(!empty($carijumlahdetil))
	{
		$infojumlahdetil= $arrjumlahdetil[$carijumlahdetil[0]];
		// print_r($infojumlahdetil);exit;
		$getmaxvariable= $infojumlahdetil["JUMLAH"];
		$getjumlahjabatan= $infojumlahdetil["JUMLAH_JABATAN"];
		$arrjabatan= $infojumlahdetil["arrjabatan"];
		$getjumlahdiklatkursus= $infojumlahdetil["JUMLAH_DIKLAT_KURSUS"];
		$arrdiklatkursus= $infojumlahdetil["arrdiklatkursus"];
		$getjumlahpendidikan= $infojumlahdetil["JUMLAH_PENDIDIKAN"];
		$arrpendidikan= $infojumlahdetil["arrpendidikan"];
		// print_r($arrpendidikan);exit;

		$vrowdetil= $rowdetil;
		for($id=0; $id<$getjumlahjabatan; $id++)
		{
			$rowdatacolom= 10;
			foreach ($detilfieldjabatan as $vd) {
				$vindex= $arrjabatan[$id];
				$vfield= $arrJabatanRekap[$vindex][$vd];
				// echo $vfield."\n";
				$objWorksheet->setCellValue(toAlpha($rowdatacolom).$vrowdetil, $vfield);
				$rowdatacolom++;
			}
			$vrowdetil++;
		}

		$vrowdetil= $rowdetil;
		for($id=0; $id<$getjumlahdiklatkursus; $id++)
		{
			$rowdatacolom= 27;
			foreach ($detilfielddiklatkursus as $vd) {
				$vindex= $arrdiklatkursus[$id];
				$vfield= $arrDiklatKursusRekap[$vindex][$vd];
				// echo $vfield."\n";
				$objWorksheet->setCellValue(toAlpha($rowdatacolom).$vrowdetil, $vfield);
				$rowdatacolom++;
			}
			$vrowdetil++;
		}

		$vrowdetil= $rowdetil;
		for($id=0; $id<$getjumlahpendidikan; $id++)
		{
			$rowdatacolom= 37;
			foreach ($detilfieldpendidikan as $vd) {
				$vindex= $arrpendidikan[$id];
				$vfield= $arrPendidikanRekap[$vindex][$vd];
				// echo $vfield."\n";
				$objWorksheet->setCellValue(toAlpha($rowdatacolom).$vrowdetil, $vfield);
				$rowdatacolom++;
			}
			$vrowdetil++;
		}

		if($getmaxvariable > 1)
		{
			$row= ($row -1) + $getmaxvariable;
		}
	}

	// untuk data total
	$rowdatatotalcolom= 0;
	foreach ($fieldtotal as $vd) {

		$vfield= "";
		$vfield= $value[$vd];

		// echo toAlpha($rowdatatotalcolom).$row."<br/>";
		$objWorksheet->setCellValue(toAlpha($rowdatatotalcolom).$row, $vfield);
		$rowdatatotalcolom++;
	}
	$row++;

	// untuk batas hitam
	$rangekolom= toAlpha($detilkolomawal).$row.":".toAlpha($detilkolomakhir).$row;
	$objWorksheet->getStyle($rangekolom)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF000000');
	$row++;
	// echo "-".$vpegawaiid."<br/>";
}
// exit;

function getmaxvariable($jumlahjabatan, $jumlahdiklatkursus, $jumlahpendidikan)
{
	$vreturn= 0;
	if($jumlahjabatan >= $jumlahdiklatkursus && $jumlahjabatan >= $jumlahpendidikan)
		$vreturn= $jumlahjabatan;
	else if($jumlahdiklatkursus >= $jumlahjabatan && $jumlahdiklatkursus >= $jumlahpendidikan)
		$vreturn= $jumlahdiklatkursus;
	else if($jumlahpendidikan >= $jumlahjabatan && $jumlahpendidikan >= $jumlahdiklatkursus)
		$vreturn= $jumlahpendidikan;

	return $vreturn;
}

//exit;
if($allRecord > 1)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
}
elseif($allRecord > 0)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
	// $objWorksheet->removeRow($tempRowAwal+1, 1);
	// $objWorksheet->removeRow($tempRowAwal+2, 1);
}
/*elseif($allRecord == 0)
{
	$objWorksheet->removeRow($tempRowAwal, 1);
	$objWorksheet->removeRow($tempRowAwal+1, 1);
	$objWorksheet->removeRow($tempRowAwal+2, 1);
}*/

$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
ob_end_clean();
$objWriter->save('template/rekap_sidak.xls');
// echo $vpegawaiid."ASd";exit;
// $objWriter->save('/var/www/siapasn/template/rekap_sidak.xls');
// echo "asd";exit
// echo "xx".$down;exit;
$down = 'template/rekap_sidak.xls';
$filename= 'rekap_sidak.xls';
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