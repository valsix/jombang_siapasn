<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('persuratan/Pensiun');
$this->load->model('persuratan/SuratMasukPegawai');


$reqMode= $this->input->get("reqMode");
$reqJenis= $this->input->get("reqJenis");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$tempSatuanKerjaId= $reqSatuanKerjaId;
$reqStatusKgb= $this->input->get("reqStatusKgb");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqPangkatId= $this->input->get("reqPangkatId");
$reqTipe= $this->input->get("reqTipe");

$tempPeriode= str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"pensiun_".$tempPeriode.".xls\"");

$judulfield= "";
$judulfield= array("No.", "NIP BARU<br/>NIP LAMA", "NAMA", "GOL<br/>TMT", "JABATAN<br/>TMT<br/>ESELON", "UNIT KERJA", "TMT Pensiun", "Status");

$field= "";
$field= array("NO", "NIP_BARU_LAMA", "NAMA_LENGKAP", "GOL_TMT", "JABATAN_TMT_ESELON", "SATUAN_KERJA_NAMA", "TMT", "KONDISI_NAMA");

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}

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
			$skerja= new SuratMasukPegawai();
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			unset($skerja);
			//echo $reqSatuanKerjaId;exit;
			$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
	}
	else
	{
		$skerja= new SuratMasukPegawai();
		$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		unset($skerja);
		//echo $reqSatuanKerjaId;exit;
		$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
	}
}

// AND SATUAN_KERJA_PARENT_ID > 0
if($reqTipe == "bkd")
{
	$statement.= " 
	AND A.SATUAN_KERJA_ID IN
	(
	SELECT SATUAN_KERJA_ID FROM SATUAN_KERJA A WHERE TIPE_ID = 1
	AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")
	)";
}

// echo $statement;exit();
// if($reqSatuanKerjaId == ""){}
// else
// {
// 	$statement= " AND A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId;
// }

if($reqStatusKgb ==""){}
else
{
	$statement .= " AND A.STATUS_KGB = '".$reqStatusKgb."'";
}

if($reqBulan == "")
{
	if($reqTahun == ""){}
	else
	$statement .= " AND TO_CHAR(A.TMT, 'YYYY') = '".$reqTahun."'";
}
else
{
	if($reqTahun == ""){}
	else
	$statement .= " AND TO_CHAR(A.TMT, 'MMYYYY') = '".$reqBulan.$reqTahun."'";
}

if($reqJenis == "" || $reqJenis == "proses")
{
	$statement .= " AND A.JENIS IN ('bup', 'meninggal')";
}
else
$statement .= " AND A.JENIS = '".$reqJenis."'";

$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%' ) ";

$sOrder= "ORDER BY A.TMT";
$set = new Pensiun();
$set->selectByParamsMonitoringPegawai(array(), -1, -1, $statement.$searchJson, $sOrder);
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
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">PEMERINTAH KABUPATEN JOMBANG</td>	
        </tr>
        <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">DAFTAR PENSIUN PEGAWAI NEGERI SIPIL</td>	
        </tr>
        <!-- <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">KEADAAN BULAN <?=strtoupper(getNameMonth($reqBulan))?> TAHUN <?=$reqTahun?></td>	
        </tr> -->
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

						else if($field[$i] == "TMT")
							$tempValue= getFormattedDate($set->getField($field[$i]));
						else if($field[$i] == "PEGAWAI_INFO")
						{
							$tempPath= $set->getField("PATH");
							if($tempPath == "")
								$tempPath= "images/foto-profile.jpg";

							$tempValue= '<img src="'.$tempPath.'" style="width:100%;height:100%;" />';
						}
						else if($field[$i] == "NIP_BARU_LAMA")
							$tempValue= $set->getField("NIP_BARU")."<br/>".$set->getField("NIP_LAMA");
						else if($field[$i] == "GOL_TMT")
							$tempValue= $set->getField("PANGKAT_RIWAYAT_KODE")."<br/>".dateToPageCheck($set->getField("PANGKAT_RIWAYAT_TMT"));
						else if($field[$i] == "JABATAN_TMT_ESELON")
							$tempValue= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
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