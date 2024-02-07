<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('KenaikanGajiBerkala');
$this->load->model('SatuanKerja');

$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$tempSatuanKerjaId= $reqSatuanKerjaId;
$reqStatusKgb= $this->input->get("reqStatusKgb");
$reqJenisKgb= $this->input->get("reqJenisKgb");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");

$tempPeriode= $reqTahun."_".getNameMonth($reqBulan)."_tanda_terima";
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"".$tempPeriode.".xls\"");

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}

if($reqSatuanKerjaId == ""){}
else
{
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
	}

	// $skerja= new SatuanKerja();
	// // kalau 1 maka dinas
	// if($this->SATUAN_KERJA_TIPE == "1")
	// {
	// 	$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
	// }
	// else
	// {
	// 	$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
	// }
	// unset($skerja);
	// echo $reqSatuanKerjaId;exit;
	// $statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
	// $statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
}

if($reqStatusKgb ==""){}
else
{
	if($reqStatusKgb == 99)
	$statement .= " AND A.STATUS_KGB IS NULL";
	else
	$statement .= " AND A.STATUS_KGB = '".$reqStatusKgb."'";
}

if($reqPangkatId ==''){}
else
{
	$statement .= " AND A.PANGKAT_ID = '".$reqPangkatId."'";
}

if($reqJenisKgb ==''){}
else
{
	$statement .= " AND CASE WHEN PR1.JENIS_KENAIKAN = 1 AND A.HUKUMAN_RIWAYAT_ID IS NULL THEN 2 WHEN A.HUKUMAN_RIWAYAT_ID IS NOT NULL THEN 3 WHEN HK.JENIS_HUKUMAN_ID = 4 THEN 4 ELSE 1 END = ".$reqJenisKgb;
}

$statement .= " AND A.PERIODE = '".$reqBulan.$reqTahun."'";
$searchJson= " AND (UPPER(P.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(P.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";

$judulfield= "";
$judulfield= array("NO", "NAMA<br/>NIP", "NO URUT", "UNIT KERJA", "TANDA TANGAN");

$field= "";
$field= array("NO", "NAMA_NIP", "NOMOR_URUT", "SATUAN_KERJA_KGB", "");

$sOrder= " ORDER BY AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID), PR1.PANGKAT_ID DESC";
$set= new KenaikanGajiBerkala();
// $allRecord= $set->getCountByParamsCetakPegawai(array(), $statement.$searchJson);
$set->selectByParamsData(array(), -1, -1, $statement.$searchJson, $sOrder);
// echo $set->query;exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<style>
	body, table{
		font-size:12px;
		font-family:Arial, Helvetica, sans-serif
	}
	th {
		text-align:center;
		font-weight: bold;
	}
	td {
		vertical-align: top;
  		text-align: left;
	}
	.str{
	  mso-number-format:"\@";/*force text*/
	}
	</style>
<table style="width:100%">
        <tr>
        	<td rowspan="3"><img src="<?=base_url()."images/logo-daerah.png"?>" style="max-width:200px; max-height:200px"/></td>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">DAFTAR NOMINATIF</td>	
        </tr>
        <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">KENAIKAN GAJI BERKALA PERIODE BULAN <?=strtoupper(getNameMonth($reqBulan))?> TAHUN <?=$reqTahun?></td>	
        </tr>
        <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">PEMERINTAH KABUPATEN JOMBANG</td>	
        </tr>
</table>
<br/>
<br/>
    	<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                <?
                for($i=0; $i < count($judulfield); $i++)
                {
                ?>
                	<th style="text-align: center;"><?=$judulfield[$i]?></th>
                <?
            	}
                ?>
                </tr>
            </thead>
            <tbody>
                <?
				$nomor = 1;
                while($set->nextRow())
                {
				?>
                	<tr>
                	<?
                	for($i=0; $i<count($field); $i++)
					{
						$tempValue= "";
						if($field[$i] == "NO")
						{
							$tempValue= $nomor;
						}
						elseif($field[$i] == "TANGGAL_LAHIR" || $field[$i] == "TMT_PANGKAT" || $field[$i] == "TMT_PENSIUN")
						{
							$tempValue= dateToPageCheck($set->getField($field[$i]));
						}
						elseif($field[$i] == "TMT_JABATAN")
						{
							$tempValue= dateTimeToPageCheck($set->getField($field[$i]));
						}
						elseif($field[$i] == "NAMA_NIP")
						{
							// $tempValue= ucwordsPertama($set->getField($field[$i]));
							$tempValue= $set->getField("NAMA_LENGKAP")."<br/>".$set->getField("NIP_BARU");
						}
						else
							$tempValue= $set->getField($field[$i]);
                	?>
                		<td class="str"><?=$tempValue?></td>
                	<?
                	}
                	?>
                    </tr>
				<?
					$nomor++;
                }
                ?>    
            </tbody>
        </table>
</body>
</html>