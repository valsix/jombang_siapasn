<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

$tempPeriode= str_replace("-","_",date("d-m-Y"));
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=\"perumpunan_".$tempPeriode.".xls\"");

$CI =& get_instance();
$CI->checkUserLogin();

$this->load->model("talent/RekapTalent");
$this->load->library('globalrekappegawai');
$vfpeg= new globalrekappegawai();
$getperumpunan= $vfpeg->getperumpunan();
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
// $statement.= " AND A.PEGAWAI_ID = 8300";
$set= new RekapTalent();
$sOrder = "";
$sOrder = "";
if(!empty($reqUrutkan))
{
	if($reqUrutkanVal == "desc")
		$sOrder= " ORDER BY (COALESCE(R1.NILAI,0) + COALESCE(R2.NILAI,0) + COALESCE(R3.NILAI,0)) DESC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";
	else
		$sOrder= " ORDER BY (COALESCE(R1.NILAI,0) + COALESCE(R2.NILAI,0) + COALESCE(R3.NILAI,0)) ASC, A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.PANGKAT_RIWAYAT_TMT ASC";

	$set->selectrumpunpegawai(array(), -1, -1, $reqUrutkan, $statement.$searchJson, $sOrder);
}
else
	$set->selectpegawai(array(), -1, -1, $statement.$searchJson);

// echo $set->query;exit;

$jumlahdetil= 4;
$jumlahrumpun= count($inforumpun);
$arrtabledata= array(
  array("label"=>"NIP", "field"=> "NIP_BARU", "display"=>"",  "width"=>"20", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Nama", "field"=> "NAMA_LENGKAP", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Jabatan<br/>TMT<br/>Eselon", "field"=> "JABATAN_TMT_ESELON", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"Gol. Ruang", "field"=> "PANGKAT_RIWAYAT_KODE", "display"=>"",  "width"=>"", "colspan"=>"", "rowspan"=>"2")
  , array("label"=>"NILAI RUMPUN", "field"=> "RUMPUN_TOTAL", "display"=>"",  "width"=>"", "colspan"=>$jumlahrumpun, "rowspan"=>"")
  , array("label"=>$getperumpunan[0]["rumpunketerangan"], "field"=> "REKAM_JEJAK_TOTAL", "display"=>"",  "width"=>"", "colspan"=>$jumlahrumpun, "rowspan"=>"")
  , array("label"=>$getperumpunan[1]["rumpunketerangan"], "field"=> "KUALIFIKASI_TOTAL", "display"=>"",  "width"=>"", "colspan"=>$jumlahrumpun, "rowspan"=>"")
  , array("label"=>$getperumpunan[2]["rumpunketerangan"], "field"=> "KOMPETENSI_TOTAL", "display"=>"",  "width"=>"", "colspan"=>$jumlahrumpun, "rowspan"=>"")
);

$jumlahrowhead= count($arrtabledata) - $jumlahdetil + ($jumlahrumpun * $jumlahdetil);
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
		<td colspan="<?=$jumlahrowhead?>" style="font-size:13px;font-weight:bold; text-align: center;">TABEL PENILAIAN PERUMPUNAN</td>	
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
            	$labelnama= $valitem["label"];
            	$labelrowspan= $valitem["rowspan"];
            	$labelcolspan= $valitem["colspan"];
            ?>
            <th colspan="<?=$labelcolspan?>" rowspan="<?=$labelrowspan?>" style="text-align: center;"><?=$labelnama?></th>
            <?
            }
            ?>
        </tr>
        <tr>
        <?
        for($x=0; $x < $jumlahdetil; $x++)
        {
			foreach ($inforumpun as $key => $value)
			{
		?>
			<th style="text-align:center"><?=$value["kode"]?></th>
		<?
			}
		}
		?>
		</tr>
    </thead>
    <tbody>
        <?
        $nilairumpun= $vfpeg->getperumpunan();
		$nomor = 1;
        while($set->nextRow())
        {
        	$infoid= $set->getField("PEGAWAI_ID");
        	// echo $infoid;exit;

        	$infomode= "jabatanriwayat";
			$arrparam= array("pegawaiid"=>$infoid, "infomode"=>$infomode);
			$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
			$infomode= "pendidikanriwayat";
			$arrparam= array("pegawaiid"=>$infoid, "infomode"=>$infomode);
			$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
			$infomode= "diklatriwayat";
			$arrparam= array("pegawaiid"=>$infoid, "infomode"=>$infomode);
			$arrperumpunan[$infomode]= $vfpeg->getparamperumpunan($arrparam);
			// print_r($arrperumpunan);exit;

			$rekamjejak= $arrperumpunan["jabatanriwayat"];
			$kualifikasi= $arrperumpunan["pendidikanriwayat"];
			$kompetensi= $arrperumpunan["diklatriwayat"];
		?>
        	<tr>
	        	<?
	        	foreach($arrtabledata as $valkey => $valitem) 
	            {
	            	$valkeydetil= $valitem["field"];

	            	if( $valkeydetil == 'RUMPUN_TOTAL' || $valkeydetil == 'REKAM_JEJAK_TOTAL' || $valkeydetil == 'KUALIFIKASI_TOTAL' || $valkeydetil == 'KOMPETENSI_TOTAL')
	            	{
	            		if($valkeydetil == 'RUMPUN_TOTAL')
	            		{
		            		foreach ($inforumpun as $key => $value)
		            		{
		            			$vrumpunid= $value["id"];
		            			$vnilai= 0;

		            			foreach ($nilairumpun as $keyrumpun => $vrumpun)
								{
									$vrumpunnilaiid= $vrumpun["rumpunnilaiid"];
									$vpersentase= $vrumpun["rumpunpersentase"];

									$infocari= $vrumpunid;
									$infoparam= [];

									if($vrumpunnilaiid == "1")
									{
										$infoparam= $rekamjejak;
										$arraycari= in_array_column($infocari, "id", $infoparam);
									}
									else if($vrumpunnilaiid == "2")
									{
										$infoparam= $kualifikasi;
										$arraycari= in_array_column($infocari, "id", $infoparam);
									}
									else if($vrumpunnilaiid == "3")
									{
										$infoparam= $kompetensi;
										$arraycari= in_array_column($infocari, "id", $infoparam);
									}

									// print_r($arraycari);exit;
									if(!empty($arraycari))
									{
										$inforumpunvalue= $infoparam[$arraycari[0]]["nilai"];
										$inforumpunvalue= $vpersentase*coalesce($inforumpunvalue,"0");

										$vnilai+= $inforumpunvalue;
									}
								}
				?>
								<td class="str"><?=$vnilai?></td>
				<?

		            		}
	            		}
	            		else if($valkeydetil == 'REKAM_JEJAK_TOTAL' || $valkeydetil == 'KUALIFIKASI_TOTAL' || $valkeydetil == 'KOMPETENSI_TOTAL')
	            		{
	            			$param= [];
	            			if($valkeydetil == 'REKAM_JEJAK_TOTAL')
	            				$param= $rekamjejak;
	            			else if($valkeydetil == 'KUALIFIKASI_TOTAL')
	            				$param= $kualifikasi;
	            			else if($valkeydetil == 'KOMPETENSI_TOTAL')
	            				$param= $kompetensi;

	            			foreach ($inforumpun as $key => $value)
							{
								$vrumpunid= $value["id"];
								$vnilai= 0;

								$infocari= $vrumpunid;
								$arraycari= in_array_column($infocari, "id", $param);
								// print_r($arraycari);exit;
								if(!empty($arraycari))
								{
									$inforumpunvalue= $param[$arraycari[0]]["nilai"];
									$vnilai= coalesce($inforumpunvalue,"0");
								}

				?>
								<td class="str"><?=$vnilai?></td>
				<?
							}
	            		}
	            	}
	            	else
	            	{
						$vreturn= "";
						if($valkeydetil == "NO")
						{
							$vreturn= $nomor;
						}
						else if( $valkeydetil == 'JABATAN_TMT_ESELON')
						{
							$vreturn= $set->getField("JABATAN_RIWAYAT_NAMA")."<br/>".dateTimeToPageCheck($set->getField("JABATAN_RIWAYAT_TMT"))."<br/>".$set->getField("JABATAN_RIWAYAT_ESELON");
						}
						else
							$vreturn= $set->getField($valkeydetil);
	        	?>
	        			<td class="str" rowspan="<?=$inforowspan?>"><?=$vreturn?></td>
	        	<?
	        		}
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