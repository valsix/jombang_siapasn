<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$tempPeriode= str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"rekam_jejak_".$tempPeriode.".xls\"");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("talent/RekapTalent");
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
$arrparam= array("satuankerjaid"=>$reqSatuanKerjaId, "tipepegawaiid"=>$reqTipePegawaiId, "statuspegawaiid"=>$reqStatusPegawaiId, "eselongroupid"=>$reqEselonGroupId);
$statement= $vfpeg->getparampegawai($arrparam);

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

// $statement.= " AND A.PEGAWAI_ID = 8887";
$set= new RekapTalent();
$sOrder = "";
$set->selectpegawai(array(), -1, -1, $statement.$searchJson);
// echo $set->query;exit;

$arrtabledata= array(
  array("label"=>"NIP", "field"=> "NIP_BARU", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Jabatan<br/>TMT<br/>Eselon", "field"=> "JABATAN_TMT_ESELON", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"Gol. Ruang", "field"=> "PANGKAT_RIWAYAT_KODE", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
  , array("label"=>"RIWAYAT JABATAN/ESELON", "field"=> "REKAM_JEJAK_DETIL", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"")
);

$jumlahrowhead= count($arrtabledata) + count($inforumpun) - 1;
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
  mso-number-format:"\@";
}
</style>
<table style="width:100%">
	<tr>
		<td rowspan="3"><img src="<?=base_url()."images/logo-daerah.png"?>" style="max-width:200px; max-height:200px"/></td>
		<td colspan="<?=$jumlahrowhead?>" style="font-size:13px;font-weight:bold; text-align: center;">PEMERINTAH KABUPATEN JOMBANG</td>	
	</tr>
	<tr>
		<td colspan="<?=$jumlahrowhead?>" style="font-size:13px;font-weight:bold; text-align: center;">TABEL PENILAIAN REKAM JEJAK</td>	
	</tr>
</table>
<br/>
<br/>
<table style="width:100%" border="1" cellspacing="0" cellpadding="0">
	<thead>
        <tr>
            <?php
            foreach($arrtabledata as $valkey => $valitem) 
            {
            ?>
            <th rowspan="2" style="text-align: center;"><?=$valitem["label"]?></th>
            <?
            }
            ?>
            <th style="text-align:center" colspan="<?=count($inforumpun)?>">NILAI PER RUMPUN</th>
        </tr>
        <tr>
        <?
		foreach ($inforumpun as $key => $value)
		{
		?>
				<th style="text-align:center"><?=$value["nama"]?></th>
		<?
		}
		?>
		</tr>
    </thead>
    <tbody>
        <?
        $reqMode= "jabatanriwayat";
		$nomor = 1;
        while($set->nextRow())
        {
        	$infoid= $set->getField("PEGAWAI_ID");
        	// echo $infoid;exit;

        	$arrparam= array("reqMode"=>$reqMode, "reqId"=>$infoid);
			$arrdetilriwayat= $vfpeg->infodetil($arrparam);
			// print_r($arrdetilriwayat);exit;
		?>
        	<tr>
	        	<?
	        	foreach($arrtabledata as $valkey => $valitem) 
	            {
	            	$valkeydetil= $valitem["field"];
	            	if(!empty($arrdetilriwayat))
	            		$inforowspan= count($arrdetilriwayat);
	            	else
	            		$inforowspan= 0;

					$vreturn= "";
					if($valkeydetil == "NO")
					{
						$vreturn= $nomor;
					}
					else if( $valkeydetil == 'JABATAN_TMT_ESELON')
					{
						$vreturn= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
					}
					else if( $valkeydetil == 'REKAM_JEJAK_DETIL')
					{
						$inforowspan= "";
						$vreturn= $arrdetilriwayat[0]["nama"];
					}
					else
						$vreturn= $set->getField($valkeydetil);
	        	?>
	        		<td class="str" rowspan="<?=$inforowspan?>"><?=$vreturn?></td>
	        	<?
	        	}
	        	?>

	        	<?
        		$inforumpunid= $arrdetilriwayat[0]["id"];
        		$arrrumpunid= [];
				if(!empty($inforumpunid))
				{
					$arrrumpunid= explode(",", $inforumpunid);
				}
				$inforumpunvalue= $arrdetilriwayat[0]["nilai"];

        		foreach ($inforumpun as $key => $value)
				{
					$vrumpunid= $value["id"];
					$vnilai= 0;

					if(in_array($vrumpunid, $arrrumpunid))
					{
						$vnilai= coalesce($inforumpunvalue,"0");
					}
				?>
					<td class="str"><?=$vnilai?></td>
				<?
				}
				?>
            </tr>

            <?
            if(!empty($arrdetilriwayat))
            {
            	foreach ($arrdetilriwayat as $k => $v)
            	{
            		if($k == 0)
            			continue;

            		$infonama= $v["nama"];
            		$inforumpunid= $v["id"];
            		$arrrumpunid= [];
					if(!empty($inforumpunid))
					{
						$arrrumpunid= explode(",", $inforumpunid);
					}
					$inforumpunvalue= $v["nilai"];
            ?>
            	<tr>
	        		<td class="str"><?=$infonama?></td>
	        		<?
	        		foreach ($inforumpun as $key => $value)
					{
						$vrumpunid= $value["id"];
						$vnilai= 0;

						if(in_array($vrumpunid, $arrrumpunid))
						{
							$vnilai= coalesce($inforumpunvalue,"0");
						}
					?>
						<td class="str"><?=$vnilai?></td>
					<?
					}
					?>
            	</tr>
            <?
            	}
            }
            ?>
            
		<?
			$nomor++;
        }
        ?>    
    </tbody>
</table>
</body>
</html>