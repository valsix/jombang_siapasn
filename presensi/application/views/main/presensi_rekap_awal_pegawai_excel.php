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

$reqId=  $this->input->get("reqId");
$reqTahun=  $this->input->get("reqTahun");
$reqBulan=  $this->input->get("reqBulan");

$reqPeriode= $reqBulan.$reqTahun;

$set= new PresensiRekap();
$arrInfoLog= [];
$set->selectpermohonanlog($reqPeriode, $reqId);
// echo $set->query;exit;
while($set->nextRow())
{
    $arrdata= [];
    $arrdata["KEY"]= $set->getField("PEGAWAI_ID")."-".$set->getField("INFOHARI");
    $arrdata["JAM"]= $set->getField("JAM");
    array_push($arrInfoLog, $arrdata);
}
unset($set);
// print_r($arrInfoLog);exit;

$arrdata = array();
$statement = " AND P.PEGAWAI_ID IN (".$reqId.")";
$set= new PresensiRekap();
$set->selectByParamsRekapAwalPegawaiDetil(array(), -1, -1, $reqPeriode, $statement);
// echo $set->query;exit;
$infofield= array( "JAM_MASUK_", "MASUK_", "JAM_PULANG_", "PULANG_", "EX_JAM_MASUK_", "EX_MASUK_", "LEMBUR_JAM_MASUK_", "LEMBUR_JAM_PULANG_", "ONCALL_JAM_MASUK_", "ONCALL_MASUK_", "INFO_LOG_");

$index=0;
while ($set->nextRow()) 
{
	$vpegawaiid= $set->getField("PEGAWAI_ID");
    $arrdata[$index]["PEGAWAI_ID"]= $vpegawaiid;
    $arrdata[$index]["NIP_BARU"]= $set->getField("NIP_BARU");
    $arrdata[$index]["NAMA_LENGKAP"]= $set->getField("NAMA_LENGKAP");

    for($i=0; $i<count($infofield); $i++)
    {
        for($n=1; $n <= 31; $n++)
        {
            $fieldkolom= $infofield[$i].$n;
            // echo $fieldkolom;exit;

            if($infofield[$i] == "INFO_LOG_")
            {
                $vinfolog= "";
                $infocarikey= $vpegawaiid."-".generateZero($n,2).$reqPeriode;
                // echo $infocarikey;exit;
                $logcheck= in_array_column($infocarikey, "KEY", $arrInfoLog);
                // print_r($logcheck);exit;

                if(!empty($logcheck))
                {
                    foreach ($logcheck as $vlog)
                    {
                        $vinfolog= getconcatseparator($vinfolog, $arrInfoLog[$vlog]["JAM"]);
                    }
                }
                $arrdata[$index][$fieldkolom]= $vinfolog;
            }
            else
            {
                $arrdata[$index][$fieldkolom]= $set->getField($fieldkolom);
            }
        }
    }
    $index++;
}
// print_r($arrdata);exit();

$jumlah_hari=  cal_days_in_month(CAL_GREGORIAN,(int)$reqBulan,$reqTahun);
// echo $jumlah_hari;exit;
$jumlahdata= $jumlah_hari;

$infohasil= "rekapperpegawai";
$objPHPexcel = PHPExcel_IOFactory::load("template/".$infohasil.".xlsx");

$sheetIndex= 0;
$objPHPexcel->setActiveSheetIndex($sheetIndex);
$obj= $objPHPexcel->getActiveSheet();

$warnamerah= new PHPExcel_Style_Color();
$warnamerah->setRGB("ff0000");
$warnabiru= new PHPExcel_Style_Color();
$warnabiru->setRGB("4dcaff");

$row = 8;
$rowawal= 7;
$obj->insertNewRowBefore($row, $jumlahdata-1);

$obj->setCellValue("B2", $arrdata[0]["NAMA_LENGKAP"]);
$obj->setCellValue("B3", "BULAN ".getNamePeriode($reqBulan.$reqTahun));

$infosql= "select b.masuk_normal masuk, keluar_normal keluar from presensi.kerja_jam_shift_pegawai a inner join presensi.kerja_jam_shift b on b.kerja_jam_shift_id = a.status where a.pegawai_id = '".$infopegawaiid."'";
$hasilsql = $this->db->query($infosql)->row();
if(!empty($hasilsql->masuk));
{
	$infomasuk= $hasilsql->masuk;
	$infokeluar= $hasilsql->keluar;
}

if(empty($infomasuk))
{
	$infosql= "select a.jenis_jam_kerja infonama FROM presensi.kerja_jam_pegawai a WHERE pegawai_id = '".$infopegawaiid."'";
	$hasilsql = $this->db->query($infosql)->row();
	$infonama= $hasilsql->infonama;
	if(empty($infonama))
		$infonama= "normal_5_hari";
	// echo $infonama;exit;
}
// echo $infonama;exit;
// echo $infomasuk;exit;

$z= 0;
for($i=1; $i<=$jumlah_hari; $i++)
{
	$date = generateZero($i,2).'-'.$reqBulan.'-'.$reqTahun;
	$nama_hari = date('l', strtotime($date));
	// echo $nama_hari."<br/>";

	if($nama_hari == "Saturday")
		$classHari = "sabtu";
	elseif($nama_hari == "Sunday")
		$classHari = "minggu";
	elseif($nama_hari == "Friday")
		$classHari = "jumat";
	else
	{
		$arrHari = explode(',', $hari_libur);

		for($j=1; $j<=count($arrHari); $j++)
		{
			if($i == $arrHari[$j])
			{
				$classHari = "libur";
				break;
			}
			else
				$classHari = "";
		}
	}

	if(!empty($infonama))
	{
		$infomasuk= $infokeluar= "";
		if($classHari == "jumat")
		{

			$infosql= "select masuk_normal masuk, keluar_normal keluar from presensi.kerja_jam where jenis_jam_kerja = '".$infonama."' and hari_khusus = '1'";
		}
		elseif($classHari == "sabtu")
		{
			
			$infosql= "select masuk_normal masuk, keluar_normal keluar from presensi.kerja_jam where jenis_jam_kerja = '".$infonama."' and hari_khusus = '2'";
		}
		elseif($classHari == "minggu"){}
		else
		{
			$infosql= "select masuk_normal masuk, keluar_normal keluar from presensi.kerja_jam where jenis_jam_kerja = '".$infonama."' and coalesce(nullif(hari_khusus, ''), null) is null";
		}

		if($classHari == "minggu"){}
		else
		{
			$hasilsql= $this->db->query($infosql)->row();
			// if($classHari == "sabtu")
			// {
			// 	// echo $infonama;exit;
			// 	print_r($hasilsql);exit;
			// }
			$infomasuk= $hasilsql->masuk;
			$infokeluar= $hasilsql->keluar;
		}
	}

	$ikolom= 1;
	$kolom= num2alpha($ikolom);
	$obj->setCellValue($kolom.$row,$i);
	$ikolom++;

	$kolom= num2alpha($ikolom);
	if(!empty($infomasuk))
		$obj->setCellValue($kolom.$row,getNamaHariIndo($nama_hari).PHP_EOL.$infomasuk." - ".$infokeluar);
	else
		$obj->setCellValue($kolom.$row,getNamaHariIndo($nama_hari));
	$ikolom++;

	for($n=0; $n<count($infofield); $n++)
    {
        $fieldkolom= $infofield[$n].$i;
        // echo $fieldkolom;exit;
        $infolabel= $arrdata[$z][$fieldkolom];

        $kolom= num2alpha($ikolom);
        // echo $infolabel;exit;

        if($infofield[$n] == "MASUK_" || $infofield[$n] == "PULANG_" || $infofield[$n] == "EX_MASUK_")
        {
        	$infowarna= setwarnakodeabsen($infolabel);
        	// echo $infolabel;exit;
        	// echo $infowarna;exit;
        	if(!empty($infowarna) && !empty($infolabel))
        	{
        		$labeltext= new PHPExcel_RichText();
        	}

        	if(!empty($infolabel))
        	{
	        	if($infowarna == "merah")
					$labeltext->createTextRun($infolabel)->getFont()->setColor($warnamerah);
				elseif($infowarna == "biru")
					$labeltext->createTextRun($infolabel)->getFont()->setColor($warnabiru);
				else
				{
					$obj->setCellValue($kolom.$row,$infolabel);
				}
        	}
			else
			{
				$obj->setCellValue($kolom.$row,$infolabel);
			}

			if(!empty($infowarna) && !empty($infolabel))
        	{
				$obj->setCellValue($kolom.$row, $labeltext);
				$obj->getStyle($kolom.$row)->getAlignment()->setWrapText(true);
			}
        }
        else
        	$obj->setCellValue($kolom.$row,$infolabel);

        $ikolom++;
    }
    $row++;
}
// exit;

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

$nippegawi= $set->getField("NIP_BARU");
$namapegawai= $set->getField("NAMA_LENGKAP");
$filename = $namapegawai."_".$reqTahun.$reqBulan."_".date('ymdhs');



header ( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
header ( 'Content-Disposition: attachment;filename="'.$filename.'.xlsx"' );
header ( 'Cache-Control: max-age=0' );
$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPexcel, 'Excel2007' );
$objWriter->save ( 'php://output' );
?>