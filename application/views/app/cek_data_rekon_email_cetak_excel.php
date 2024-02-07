<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$tempPeriode= str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"cetak_rekon_email_".$tempPeriode.".xls\"");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model('Pegawai');
$this->load->model('SatuanKerja');

$reqModeDinas= $this->input->get("reqModeDinas");
$reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqBulan= $this->input->get("reqBulan");
$reqTahun= $this->input->get("reqTahun");
$reqSatuanKerjaTeknisId= $tempSatuanKerjaId= $reqSatuanKerjaId;
$reqStatusPegawaiId= $this->input->get("reqStatusPegawaiId");

if($reqSatuanKerjaId == "")
{
	$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
}
// echo $reqSatuanKerjaId;exit();

if($reqSatuanKerjaId == ""){}
else
{
	if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1")
	{
		if(isStrContain(strtoupper($this->USER_GROUP), "TEKNIS") == "1" && $reqSatuanKerjaTeknisId == ""){}
		else
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
				$statement= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
			}
		}
	}
	else
	{
		// echo $reqSatuanKerjaId;exit();
		// echo $this->SATUAN_KERJA_TIPE;exit();
		$skerja= new SatuanKerja();
		// if($this->SATUAN_KERJA_TIPE == "1")
		if($reqModeDinas == "1")
		{
			$reqSatuanKerjaId= $skerja->getSatuanKerjaTipe($reqSatuanKerjaId, $this->SATUAN_KERJA_TIPE);
		}
		else
		{
			$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
			// echo $skerja->query;exit();
		}
		unset($skerja);
		// echo $reqSatuanKerjaId;exit;
		$statement= " AND P.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
	}
}

// echo $statement;exit();



// echo $statement;exit;
if($_GET['sSearch'] == ""){}
else
{
	$searchJson= " AND (UPPER(P.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(P.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(P.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
}

$judulfield= "";
// $judulfield= array("No.", "NIP", "NIP Baru", "Nama", "Status", "Gol. Ruang", "TMT Pangkat", "Tipe Pegawai", "Eselon", "Jabatan", "TMT Jabatan", "Satuan Kerja", "TMT Pensiun", "Pendidikan");
$judulfield= array("No.", "NIP BARU", "NAMA", "HP", "EMAIL PRIBADI", "SATUAN KERJA", "SATUAN KERJA DETIL");

$field= "";
// $field= array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "STATUS_PEGAWAI", "GOL_RUANG", "TMT_PANGKAT", "TIPE_PEGAWAI", "ESELON", "JABATAN", "TMT_JABATAN", "NAMA_SATKER", "TMT_PENSIUN", "PENDIDIKAN");
$field= array("NO", "NIP_BARU", "NAMA", "HP", "KETERANGAN_1", "SATUAN_KERJA_UPT", "NAMA_SATUAN_KERJA");

// $statement.= " AND A.PEGAWAI_ID= 13478";

$sOrder = " ORDER BY SATUAN_KERJA_UPT ASC, NAMA_SATUAN_KERJA ASC";
$set= new Pegawai();
// $allRecord= $set->getCountByParamsCetakPegawai(array(), $statement.$searchJson);
$set->selectByParamsRekapCekDataRekonEmail(array(), -1, -1, $statement.$searchJson, $sOrder);
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
	.strmerah{
	  background-color:#FF0000;
	  mso-number-format:"\@";/*force text*/
	}
	</style>
<table style="width:100%">
        <tr>
        	<td rowspan="3"><img src="<?=base_url()."images/logo-daerah.png"?>" style="max-width:200px; max-height:200px"/></td>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">PEMERINTAH KABUPATEN JOMBANG</td>	
        </tr>
        <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">REKON DATA EMAIL ASN</td>	
        </tr>
        <tr>
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">KEADAAN BULAN <?=strtoupper(getNameMonth($reqBulan))?> TAHUN <?=$reqTahun?></td>	
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
						elseif($field[$i] == "NIP_BARU")
						{
							$tempValue= "'".$set->getField($field[$i]);
						}
						elseif($field[$i] == "HP")
						{
							$tempValue= "'".$set->getField($field[$i]);
						}
						else {
							$tempValue= $set->getField($field[$i]);
						}
		
                	?>
                		<td class="<?=$class_style?>"><?=$tempValue?></td>
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