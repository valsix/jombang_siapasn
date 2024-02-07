<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukBkdDisposisi');

$reqId= $this->input->get("reqId");
//$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
//echo $reqSatuanKerjaId."--";exit;
$reqSatuanKerjaId= $this->input->get("reqPegawaiPilihKepalaId");

$reqMode = 'update';
$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId."";
$set= new SuratMasukBkdDisposisi();
$set->selectByParamsDataSuratLookup(array(), -1, -1, $reqSatuanKerjaId, $reqId, "", $statement);
// $set->selectByParamsDataSurat(array(), -1, -1, $reqSatuanKerjaId, "", $statement);
$set->firstRow();
//echo $set->query;exit;
$reqRowId= $set->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
$reqJenisId= $set->getField("JENIS_ID");
$reqSatkerAsalNama= $set->getField("SATUAN_KERJA_ASAL_NAMA");
$reqTanggal= dateToPageCheck($set->getField("TANGGAL"));
$reqNomor= $set->getField("NOMOR");
$reqPerihal= $set->getField("PERIHAL");
$reqTanggalDisposisi= datetimeToPage($set->getField("TANGGAL_TERIMA"), "date");
$reqNomorAgenda= $set->getField("NO_AGENDA");
// $reqSatuanKerjaDiteruskanKepada= $set->getField("SATUAN_KERJA_TUJUAN_DITERUSKAN_JABATAN_NAMA");
// $reqSatuanKerjaDiteruskanKepada= $set->getField("KEPALA_SATUAN_KERJA_BKDPP_NAMA");
$reqSatuanKerjaDiteruskanKepada= "KEPALA BKDPP";

$tempIsiDisposisi= "";
$arrHistori= [];
$index_data= 0;
$statement= " AND A.SURAT_MASUK_BKD_ID = ".$reqId;
//$statementnode= " AND SATUAN_KERJA_ASAL_ID NOT IN (".$reqSatuanKerjaId.")";
$set_detil= new SuratMasukBkdDisposisi();
$set_detil->selectByParamsHistoriDisposisi($statement, $statementnode);
//echo $set_detil->query;exit;
while($set_detil->nextRow())
{
	$arrHistori[$index_data]["SURAT_MASUK_BKD_DISPOSISI_ID"] = $set_detil->getField("SURAT_MASUK_BKD_DISPOSISI_ID");
	$arrHistori[$index_data]["TANGGAL_DISPOSISI"] = $set_detil->getField("TANGGAL_DISPOSISI");
	$arrHistori[$index_data]["ISI"] = $set_detil->getField("ISI");
	$arrHistori[$index_data]["JABATAN_ASAL"] = $set_detil->getField("JABATAN_ASAL");
	$arrHistori[$index_data]["JABATAN_TUJUAN"] = $set_detil->getField("JABATAN_TUJUAN");
	
	/*if($tempIsiDisposisi == "")
	{
		$tempIsiDisposisi= "Yth.".$arrHistori[$index_data]["JABATAN_TUJUAN"]."g4nt1b4rIs-".$arrHistori[$index_data]["ISI"];
	}
	else
	{
		$tempIsiDisposisi.= "g4nt1b4rIsYth.".$arrHistori[$index_data]["JABATAN_TUJUAN"]."g4nt1b4rIs-".$arrHistori[$index_data]["ISI"];
	}*/
	$index_data++;
}
unset($set_detil);
$jumlah_histori= $index_data;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
</head>
<body>
	<div id="container">
		<!-- <div class="row">
        	<div style="height:2cm;"></div>
		</div> -->
		<div class="row">
			<div class="col-12">
				<table border="0" cellspacing="0" cellpadding="0" style="width:100%; font-size:14px !important">
                <!-- <tr>
                    <td colspan="6" valign="top" style="border:none !important; text-align:center; padding-bottom:15px !important">LEMBAR DISPOSISI</td>
                </tr> -->
                <tr>
                    <td width="15%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; border-left:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Surat dari</td>
                    <td width="2%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td width="33%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqSatkerAsalNama?></td>
                    <td width="20%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Diterima tanggal</td>
                    <td width="2%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td width="28%" style="border-top:#000 1px solid; border-bottom:#000 1px solid; border-right:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqTanggalDisposisi?></td>
                </tr>
                <tr>
                    <td valign="top" style="border-bottom:#000 1px solid; border-left:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;">Tanggal surat</td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td style="border-bottom:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqTanggal?></td>
                    <td style="border-bottom:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Nomor agenda</td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td style="border-bottom:#000 1px solid; border-right:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqNomorAgenda?></td>
                </tr>
                <tr>
                    <td style="border-bottom:#000 1px solid; border-left:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Nomor surat</td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td style="border-bottom:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqNomor?></td>
                    <td style="border-bottom:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Diteruskan kepada</td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td style="border-bottom:#000 1px solid; border-right:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"></td>
                </tr>
                <tr>
                    <td style="border-bottom:#000 1px solid; border-left:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top">Perihal</td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top">:</td>
                    <td style="border-bottom:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top"><?=$reqPerihal?></td>
                    <td style="border-bottom:#000 1px solid; padding-left:5px; padding-top: 9px; padding-bottom: 9px;" valign="top"> 1. Sekretaris BKDPP <br>
		    2. Kabid Kinerja, P & KA <br>
		    3. Kabid PKA <br></td>
                    <td style="border-bottom:#000 1px solid; text-align:center; padding-top: 9px; padding-bottom: 9px;" valign="top"></td>
                    <td style="border-bottom:#000 1px solid; border-right:#000 1px solid; padding-top: 9px; padding-bottom: 9px;" valign="top">		    		   
		    4. Kabid P. Karir <br>
		    5. Kabid PPI <br>
		    6. ...................... <br></td>
                </tr>
				</table>
			</div>
            
		</div>
        
        <div class="row">
			<div class="col-12 float-l">
				<table border="0" cellspacing="0" cellpadding="0" style="width:100%; padding-top:20px; font-size:14px !important">
                <tr>
                    <td colspan="6" valign="top" style="border:none !important; text-align:center; padding-bottom:15px !important">lSI DISPOSISI</td>
                </tr>
                <?
                $jumlah_histori= 0;
                for($index=0; $index < $jumlah_histori; $index++)
				{
					//$tempIsiDisposisi= "Yth.".$arrHistori[$index_data]["JABATAN_TUJUAN"]."g4nt1b4rIs-".$arrHistori[$index_data]["ISI"];

                    $infoisi= $arrHistori[$index]["ISI"];
                    $infoisi= str_replace("BY PASS SYSTEM", "", $infoisi);
				?>
                <tr>
                    <td width="100%" style="padding-left:5px; padding-top: 4px; padding-bottom: 4px;" valign="top"><?=$arrHistori[$index]["JABATAN_TUJUAN"]?></td>
                </tr>
                <tr>
                    <td width="100%" style="padding-left:5px; padding-top: 4px; padding-bottom: 4px;" valign="top"><?=$infoisi?></td>
                </tr>
                <?
				}
                ?>
				</table>
			</div>
            
		</div>
        
	</div>
</body>
</html>
