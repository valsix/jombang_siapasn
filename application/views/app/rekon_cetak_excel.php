<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$tempPeriode= str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"rekon_".$tempPeriode.".xls\"");

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
				$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
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
		$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		//$statement= " AND ( A.SATUAN_KERJA_ID = ANY( AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$reqSatuanKerjaId.") ) OR A.SATUAN_KERJA_ID = ".$reqSatuanKerjaId." )";
	}
}

// echo $statement;exit();


if($reqStatusPegawaiId == ""){}
else if($reqStatusPegawaiId == "126")
{
	$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2,6)";
}
else if($reqStatusPegawaiId == "12")
{
	$statement.= " AND A.STATUS_PEGAWAI_ID IN (1,2)";
}
else if($reqStatusPegawaiId == "hk")
{
	$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI)";
}
else if($reqStatusPegawaiId == "pk")
{
	$statement.= " AND EXISTS(SELECT 1 FROM HUKUMAN_TERAKHIR WHERE CURRENT_DATE >= G.TANGGAL_AKHIR)";
}
else if(isStrContain($reqStatusPegawaiId, "spk"))
{
	$reqStatusPegawaiId= str_replace("spk", "", $reqStatusPegawaiId);
	$statement.= " AND A.PEGAWAI_ID IN (SELECT B.PEGAWAI_ID FROM STATUS_PEGAWAI_KEDUDUKAN A INNER JOIN PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID WHERE A.STATUS_PEGAWAI_KEDUDUKAN_ID = ".$reqStatusPegawaiId.")";
}
else
{
	$statement.= " AND A.STATUS_PEGAWAI_ID = ".$reqStatusPegawaiId;
}

// echo $statement;exit;
if($_GET['sSearch'] == ""){}
else
{
	$searchJson= " AND (UPPER(A.NAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_LAMA) LIKE '%".strtoupper($_GET['sSearch'])."%' OR UPPER(A.NIP_BARU) LIKE '%".strtoupper($_GET['sSearch'])."%') ";
}

$judulfield= "";
// $judulfield= array("No.", "NIP", "NIP Baru", "Nama", "Status", "Gol. Ruang", "TMT Pangkat", "Tipe Pegawai", "Eselon", "Jabatan", "TMT Jabatan", "Satuan Kerja", "TMT Pensiun", "Pendidikan");
$judulfield= array("No.", "NIP Lama / Baru", "Nama", "Status", "Gol./TMT", "Tipe Pegawai", "Eselon", "Jabatan / TMT", "Satuan Kerja", "TMT Pensiun", "Tk Pendidikan", "Pendidikan");

$field= "";
// $field= array("NO", "NIP_LAMA", "NIP_BARU", "NAMA", "STATUS_PEGAWAI", "GOL_RUANG", "TMT_PANGKAT", "TIPE_PEGAWAI", "ESELON", "JABATAN", "TMT_JABATAN", "NAMA_SATKER", "TMT_PENSIUN", "PENDIDIKAN");
$field= array("NO", "NIP_LAMA_BARU", "NAMA", "STATUS_PEGAWAI", "GOL_RUANG_TMT_PANGKAT", "TIPE_PEGAWAI", "ESELON", "JABATAN_TMT_JABATAN", "NAMA_SATKER", "TMT_PENSIUN", "TK_PENDIDIKAN", "PENDIDIKAN");

// $statement.= " AND A.PEGAWAI_ID= 13478";

$sOrder = " ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC";
$set= new Pegawai();
// $allRecord= $set->getCountByParamsCetakPegawai(array(), $statement.$searchJson);
$set->selectByParamsCetakRekon(array(), -1, -1, $statement.$searchJson, $sOrder);
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
            <td colspan="<?=count($judulfield)-1?>" style="font-size:13px;font-weight:bold; text-align: center;">REKON DATA ASN</td>	
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
	                $arraytkpendidikan = array("SLTA", "SLTA Kejuruan", "Diploma I", "Diploma II", "Diploma III/Sarjana Muda", "Diploma IV", "S-1/Sarjana", "S-2", "S-3/Doktor");

					if (in_array($set->getField("TK_PENDIDIKAN"), $arraytkpendidikan))
					  {
					  	if ($set->getField("PENDIDIKAN")==""){
					  		$pendidikankosong = 1;
					  	} else {
					  		$pendidikankosong = 0;
					  	}

					  }
					else
					  {
					  	$pendidikankosong = 0;
					  }

                	for($i=0; $i<count($field); $i++)
					{
						$tempValue= "";
						if($field[$i] == "NO")
						{
							$tempValue= $nomor;
						}
						elseif($field[$i] == "NIP_LAMA_BARU")
						{
							$tempValue= $set->getField("NIP_LAMA")."<br/>".$set->getField("NIP_BARU");
						}
						elseif($field[$i] == "TANGGAL_LAHIR" || $field[$i] == "TMT_PENSIUN")
						{
							$tempValue= dateToPageCheck($set->getField($field[$i]));
						}
						elseif($field[$i] == "PENDIDIKAN" || $field[$i] == "UNIT_KERJA_INDUK")
						{
							$tempValue= ucwordsPertama($set->getField($field[$i]));
						}
						elseif($field[$i] == "GOL_RUANG_TMT_PANGKAT")
						{
							$tempValue= $set->getField("GOL_RUANG")."<br/>".dateTimeToPageCheck($set->getField("TMT_PANGKAT"));
						}
						elseif($field[$i] == "JABATAN_TMT_JABATAN")
						{
							$tempValue= ucwordsPertama($set->getField("JABATAN"))."<br/>".dateTimeToPageCheck($set->getField("TMT_JABATAN"));
						}						
						else {
							$tempValue= $set->getField($field[$i]);
						}

						if($i==11 && $pendidikankosong==1){
						//	if($pendidikankosong==1){
								$class_style = "strmerah";
							} 
							else {
								$class_style = "str";
							}
					//	} 
					//	else {
					//			$class_style = "str";
					//	}
		
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