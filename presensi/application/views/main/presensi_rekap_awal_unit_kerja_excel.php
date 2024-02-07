<?php 
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

// http://localhost/simpeg/jombang-absensi-new/app/loadUrl/main/presensi_rekap_awal_unit_kerja_excel.php?reqSatuanKerjaId=66&reqBulan=02&reqTahun=2021&reqMode=

$reqMode= $this->input->get("reqMode");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqPeriode= $reqBulan.$reqTahun;
$reqPencarian= $this->input->get("reqPencarian");
$reqModeDinas= $this->input->get("reqModeDinas");

if($reqSatuanKerjaId== "")
{
	$IdCetakSatuanKerjaId =  $this->SATUAN_KERJA_ID;
}
else
{
	$IdCetakSatuanKerjaId =  $this->SATUAN_KERJA_ID;
}

$infojudul= "Rekap Awal ";
if(!empty($reqMode))
	$infojudul= "Rekap Awal Detail ";

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}
$tempSatuanKerjaId= $reqSatuanKerjaId;

$satuankerjakondisi= "";
if($reqSatuanKerjaId == "")
{
	$satuankerjakondisi= " 
	AND EXISTS(
	SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND COALESCE(MASA_BERLAKU_AWAL,CURRENT_DATE) <= CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE)
	AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
	)";
}
else
{
	/*$satuankerjakondisi= " 
	AND EXISTS
	(
		SELECT 1 FROM SATUAN_KERJA S WHERE 1=1 AND MASA_BERLAKU_AWAL < CURRENT_DATE AND COALESCE(MASA_BERLAKU_AKHIR,CURRENT_DATE) >= CURRENT_DATE
		AND P.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
	) ";*/
	
	$infostatuskhususdinas= $this->STATUS_KHUSUS_DINAS;
	$skerja= new SatuanKerja();
	if($reqModeDinas == "1" && $infostatuskhususdinas == "1")
	{
		$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
	}
	else
	{
		$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
	}
	// echo $skerja->query;exit();
	unset($skerja);
	// echo $reqSatuanKerjaId;exit;
	$satuankerjakondisi.= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}
// echo $satuankerjakondisi;exit();
$statementdetil= $satuankerjakondisi;

// $statementdetil.= " AND P.PEGAWAI_ID IN (8300, 7424)";
/*$statementdetil.= " AND 
(
	P.STATUS_PEGAWAI_ID IN (1,2)
	OR
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
)";*/
// echo $statementdetil;exit;
// echo alpha2num(num2alpha(30));exit;
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
$namasatuankerja.= " ";

$infoperiode= $reqTahun."-".$reqBulan;
$maxhari= getDay(date("Y-m-t",strtotime($infoperiode)));

$warnamerah= new PHPExcel_Style_Color();
$warnamerah->setRGB("ff0000");
$warnabiru= new PHPExcel_Style_Color();
$warnabiru->setRGB("4dcaff");
// $infojamkosong= "                  ";
// $infojamkosongdetil= "         ";

$infojamkosong= $infojamkosongdetil= "         ";

$infohasil= "rekapawalunitkerja";
$objPHPexcel = PHPExcel_IOFactory::load("template/".$infohasil.$maxhari.".xlsx");

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$obj= $objPHPexcel->getActiveSheet();

$row = 6;
$rowawal= 5;

$obj->setCellValue("B2", $infojudul.$namasatuankerja.getNamePeriode($reqBulan.$reqTahun));

$infotanggaldetil= "01".$reqPeriode;
$sOrder = " ORDER BY P.ESELON_ID ASC, P.PANGKAT_ID DESC, P.PANGKAT_RIWAYAT_TMT ASC";
$set= new PresensiRekap();
$jumlahdata= $set->getCountByParamsNewRekapAwalUnitKerja(array(), $infotanggaldetil, $reqPeriode, $searchJson.$statementdetil);
$set->selectByParamsNewRekapAwalUnitKerja(array(), -1, -1, $infotanggaldetil, $reqPeriode, $statementdetil, $searchJson.$statement, $sOrder);
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
	$kolomakhir= "AI";
	if($maxhari == 28)
		$kolomakhir= "AF";
	elseif($maxhari == 29)
		$kolomakhir= "AG";
	elseif($maxhari == 30)
		$kolomakhir= "AH";

	$col = 'B';	$obj->setCellValue($col.$row,'-'); $obj->mergeCells('A'.$row.':'.$kolomakhir.$row.'');
	$i++;
}

if($maxhari == 28)
{
	$field= array("JENIS", "NIP_BARU_NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28");
}
elseif($maxhari == 29)
{
	$field= array("JENIS", "NIP_BARU_NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28","HARIINFO29");
}
elseif($maxhari == 30)
{
	$field= array("JENIS", "NIP_BARU_NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28","HARIINFO29","HARIINFO30");
}
else
{
	$field= array("JENIS", "NIP_BARU_NAMA_LENGKAP", "JABATAN_RIWAYAT_NAMA", "HARIINFO1", "HARIINFO2", "HARIINFO3","HARIINFO4","HARIINFO5","HARIINFO6","HARIINFO7","HARIINFO8","HARIINFO9","HARIINFO10","HARIINFO11","HARIINFO12","HARIINFO13","HARIINFO14","HARIINFO15","HARIINFO16","HARIINFO17","HARIINFO18","HARIINFO19","HARIINFO20","HARIINFO21","HARIINFO22","HARIINFO23","HARIINFO24","HARIINFO25","HARIINFO26","HARIINFO27","HARIINFO28","HARIINFO29","HARIINFO30","HARIINFO31");
}
// print_r($field);exit;

$nomor=1;
while($set->nextRow())
{
	$infopegawaiid= $set->getField("PEGAWAI_ID");

	$infosql= "select b.nama_jam_kerja infonama from presensi.kerja_jam_shift_pegawai a inner join presensi.kerja_jam_shift b on b.kerja_jam_shift_id = a.status where a.pegawai_id = '".$infopegawaiid."'";
	$hasilsql = $this->db->query($infosql)->row();
	if(!empty($hasilsql->infonama));
	{
		$infopegawaijenis= $hasilsql->infonama;
	}

	if(empty($infopegawaijenis))
	{
		$infosql= "select a.jenis_jam_kerja infonama FROM presensi.kerja_jam_pegawai a WHERE pegawai_id = '".$infopegawaiid."'";
		$hasilsql = $this->db->query($infosql)->row();
		$infopegawaijenis= infojenisjamkerja($hasilsql->infonama);
	}

	$ikolom= 1;
	for($i=0; $i<count($field); $i++)
	{
		$kolom= num2alpha($ikolom);

		if($field[$i] == "JENIS")
		{
			$infovalue= $infopegawaijenis;
			$obj->setCellValue($kolom.$row,$infovalue);
		}
		elseif($field[$i] == "NIP_BARU_NAMA_LENGKAP")
		{
			$infovalue= $set->getField("NAMA_LENGKAP").PHP_EOL."NIP ".$set->getField("NIP_BARU").PHP_EOL." ( ".$infopegawaiid." )";
			// echo $kolom.$row;exit;
			$obj->setCellValue($kolom.$row,$infovalue);
			// $obj->setValue($kolom.$row,$infovalue);
		}
		elseif($field[$i] == "HARIINFO1" || $field[$i] == "HARIINFO2" || $field[$i] == "HARIINFO3" || $field[$i] == "HARIINFO4" || $field[$i] == "HARIINFO5" || $field[$i] == "HARIINFO6" || $field[$i] == "HARIINFO7" || $field[$i] == "HARIINFO8" || $field[$i] == "HARIINFO9" || $field[$i] == "HARIINFO10" || $field[$i] == "HARIINFO11" || $field[$i] == "HARIINFO12" || $field[$i] == "HARIINFO13" || $field[$i] == "HARIINFO14" || $field[$i] == "HARIINFO15" || $field[$i] == "HARIINFO16" || $field[$i] == "HARIINFO17" || $field[$i] == "HARIINFO18" || $field[$i] == "HARIINFO19" || $field[$i] == "HARIINFO20" || $field[$i] == "HARIINFO21" || $field[$i] == "HARIINFO22" || $field[$i] == "HARIINFO23" || $field[$i] == "HARIINFO24" || $field[$i] == "HARIINFO25" || $field[$i] == "HARIINFO26" || $field[$i] == "HARIINFO27" || $field[$i] == "HARIINFO28" || $field[$i] == "HARIINFO29" || $field[$i] == "HARIINFO30" || $field[$i] == "HARIINFO31")
		{
			$infohari= str_replace("HARIINFO", "", $field[$i]);

			$in1= $set->getField("N_JAM_MASUK_".$infohari); if(empty($in1)) $in1= "-".$infojamkosong;
			$in2= $set->getField("N_MASUK_".$infohari);
			$out1= $set->getField("N_JAM_PULANG_".$infohari); if(empty($out1)) $out1= "-".$infojamkosong;
			$out2= $set->getField("N_PULANG_".$infohari);
			$ask1= $set->getField("EX_JAM_MASUK_".$infohari); if(empty($ask1)) $ask1= "-".$infojamkosong;
			$ask2= $set->getField("EX_MASUK_".$infohari);

			$labeltext= new PHPExcel_RichText();
			if(!empty($reqMode))
				$labeltext->createTextRun($in1." ");
			else
				$labeltext->createTextRun(" ");

			$infowarna= setwarnakodeabsen($in2);
			if($infowarna == "merah")
				$labeltext->createTextRun($in2)->getFont()->setColor($warnamerah);
			elseif($infowarna == "biru")
				$labeltext->createTextRun($in2)->getFont()->setColor($warnabiru);
			else
			{
				if(!empty($in2))
					$labeltext->createTextRun($in2);
				else
				{
					if(!empty($reqMode))
						$labeltext->createTextRun($infojamkosongdetil);
					else
						$labeltext->createTextRun("-");
				}
			}

			if(!empty($reqMode))
				$labeltext->createTextRun(PHP_EOL.$out1." ");
			else
				$labeltext->createTextRun(PHP_EOL." ");
			$infowarna= setwarnakodeabsen($out2);
			if($infowarna == "merah")
				$labeltext->createTextRun($out2)->getFont()->setColor($warnamerah);
			elseif($infowarna == "biru")
				$labeltext->createTextRun($out2)->getFont()->setColor($warnabiru);
			else
			{
				if(!empty($out2))
					$labeltext->createTextRun($out2);
				else
				{
					if(!empty($reqMode))
						$labeltext->createTextRun($infojamkosongdetil);
					else
						$labeltext->createTextRun("-");
				}
			}

			if(!empty($reqMode))
				$labeltext->createTextRun(PHP_EOL.$ask1." ");
			else
				$labeltext->createTextRun(PHP_EOL." ");
			$infowarna= setwarnakodeabsen($ask2);
			if($infowarna == "merah")
				$labeltext->createTextRun($ask2)->getFont()->setColor($warnamerah);
			elseif($infowarna == "biru")
				$labeltext->createTextRun($ask2)->getFont()->setColor($warnabiru);
			else
			{
				if(!empty($ask2))
					$labeltext->createTextRun($ask2);
				else
				{
					if(!empty($reqMode))
						$labeltext->createTextRun($infojamkosongdetil);
					else
						$labeltext->createTextRun("-");
				}
			}

			$obj->setCellValue($kolom.$row, $labeltext);
			$obj->getStyle($kolom.$row)->getAlignment()->setWrapText(true);
		}
		else
		{
			$obj->setCellValue($kolom.$row,$set->getField($field[$i]));
		}

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

// $obj->getStyle('B'.$rowawal.':B'.$row.'')->getAlignment()->setWrapText(true);
$filename = "rekapawalunitkerja_".$IdCetakSatuanKerjaId."_".$reqTahun.$reqBulan."_".date('ymdhs');

header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
header ( 'Content-Disposition: attachment;filename="'.$filename.'.xlsx"' );
header ( 'Cache-Control: max-age=0' );
$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPexcel, 'Excel2007' );
$objWriter->save ( 'php://output' );
?>