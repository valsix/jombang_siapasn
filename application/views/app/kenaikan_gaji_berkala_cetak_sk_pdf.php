<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/encrypt.func.php");
include_once("functions/date.func.php");

$this->load->model('Cetak');
$this->load->model('KenaikanGajiBerkala');

$this->load->model('SatuanKerja');

$tempSatuanKerjaId= $reqSatuanKerjaId= $this->input->get("reqSatuanKerjaId");
$reqJenis= $this->input->get("reqJenis");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqTahun= $this->input->get("reqTahun");
$reqBulan= $this->input->get("reqBulan");
$reqJenisKgb= $this->input->get("reqJenisKgb");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");

$reqPeriode = $reqBulan.$reqTahun;

//------ Put the values that you want --------
$statement .= " AND A.PERIODE = '".$reqPeriode."'";
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
			$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
		}
	}
	else
	{
		$skerja= new SatuanKerja();
		$reqSatuanKerjaId= $skerja->getSatuanKerja($reqSatuanKerjaId);
		unset($skerja);
		// echo $reqSatuanKerjaId;exit;
		$statement.= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
	}
			
	
}

if($reqPegawaiId==""){}
else
{
	$statement .= " AND A.PEGAWAI_ID IN (".$reqPegawaiId.")";
}

if($reqJenisKgb ==''){}
else
{
	$statement .= " AND CASE WHEN PR1.JENIS_KENAIKAN = 1 AND A.HUKUMAN_RIWAYAT_ID IS NULL THEN 2 WHEN A.HUKUMAN_RIWAYAT_ID IS NOT NULL THEN 3 WHEN HK.JENIS_HUKUMAN_ID = 4 THEN 4 ELSE 1 END = ".$reqJenisKgb;
}
// echo $statement;exit;
$statement .= " ORDER BY AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID), PR1.PANGKAT_ID DESC";
include_once('lib/phpqrcode/qrlib.php');

// print_r($picture);exit();

unset($set);

$set= new Cetak();
$set->selectByParams(array(), -1, -1, $statement);
// echo $set->query;exit;
$jumlah=$set->rowCount;
$i=1;
while($set->nextRow())
{
	$reqTanggalSK= getFormattedDate($set->getField("TANGGAL"));
	$reqSKNomorBaru= $set->getField("SK_NOMOR_BARU");
	$reqSKNomorLama= $set->getField("NO_SK_LAMA");
	// print_r($reqSKNomorLama);exit;
	$reqNamaPeraturan= $set->getField("NAMA_PERATURAN");
	$reqJenisKenaikan= $set->getField("JENIS_KENAIKAN");
	if($reqJenisKenaikan==1)
	{
		$reqStatusPegawai= "Calon Pegawai Negeri Sipil ";
	}
	else
	{
		$reqStatusPegawai= "Pegawai Negeri Sipil ";
	}

	
	$reqNamaLengkap= $set->getField("NAMA_LENGKAP");
	$reqNipBaru= $set->getField("NIP_BARU");
	$reqPangkatNama= $set->getField("PANGKAT_NAMA");
	$reqSatuanKerjaNama= $set->getField("SATUAN_KERJA_NAMA");
	$reqPejabatPenetapNamaLama= $set->getField("PEJABAT_PENETAP_NAMA_LAMA");
	$reqTanggalSKLama= getFormattedDate($set->getField("TANGGAL_SK_LAMA"));
	$reqGajiPokokLama= $set->getField("GAJI_POKOK_LAMA");
	$reqGajiPokokLama= number_format($reqGajiPokokLama, 2, ',', '.');
	$reqMasaKerjaLama= $set->getField("MASA_KERJA_LAMA");
	$reqTmtLama= getFormattedDate($set->getField("TMT_SK_LAMA"));
	$reqHukumanRiwayatId= $set->getField("HUKUMAN_RIWAYAT_ID");
	if($reqHukumanRiwayatId==0)
	{
		$reqHukumanKeterangan= " diberikan kenaikan gaji berkala hingga memperoleh :";
	}
	else
	{
		$reqHukumanKeterangan= " yang seharusnya diberikan kenaikan gaji berkala hingga memperoleh :";
	}
	$reqGajiBaru= $set->getField("GAJI_BARU");
	
	$reqGajiBaru= number_format($reqGajiBaru, 2, ',', '.');
	$reqTerbilang= ucwords(terbilang($set->getField("GAJI_BARU")));
	$reqMasaKerjaBaru= $set->getField("MASA_KERJA_BARU");
	$reqTMTBaru= getFormattedDate($set->getField("TMT_SK_BARU"));
	$reqTmtBerikutGaji= getFormattedDate($set->getField("TMT_BERIKUT_GAJI"));
	$reqNoSkHukuman= $set->getField("NO_SK_HUKUMAN");
	$reqTanggalSkHukuman= getFormattedDate($set->getField("TANGGAL_SK_HUKUMAN"));
	$reqCheckTanggalSkHukuman= $set->getField("TANGGAL_SK_HUKUMAN");
	// var_dump($reqTanggalSkHukuman);exit;
	$reqPejabatPenetapLengkap= $set->getField("PEJABAT_PENETAP_LENGKAP_TTD");
	$reqNamaPejabatTtd= $set->getField("NAMA_PEJABAT_TTD");
	$reqPangkatTtd= $set->getField("PANGKAT_TTD");
	$reqNipTtd= $set->getField("NIP_TTD");
	$reqSatkerInduk= $set->getField("SATKER_INDUK");

	$tempNoSK= $set->getField("SK_NOMOR_BARU");
	$tempNIPBaru= $set->getField("NIP_BARU");
	$tempPegawaiId= $set->getField("PEGAWAI_ID");
	$tempPeriode= $set->getField("PERIODE");
	// $tempUnix = "I_LOVE_U";
	$tempUnix = $tempPeriode."_".$tempPegawaiId;

	$codeContents= mencrypt(str_replace(" ","", "SK_KGB_BKDPP_".$tempUnix."_".$tempNoSK), "siapasn02052018");
	//echo $codeContents;exit;
	if($tempNoSK == "/.")
	{
		echo "hubungi admin, kode qr anda kurang tepat.";exit;
	}

	// how to save PNG codes to server 
	$tempDir= "lib/phpqrcode/uploads/"; 

	// we need to generate filename somehow,  
	// with md5 or with database ID used to obtains $codeContents... 
	$fileName = str_replace("/","_", $tempNoSK."_".$tempNIPBaru).'.png'; 
	// print_r($fileName);exit;
	
	$pngAbsoluteFilePath = $tempDir.$fileName; 
	$urlRelativeFilePath = $tempDir.$fileName; 

	$linkfile=base_url().$pngAbsoluteFilePath;

	
	if (file_exists($pngAbsoluteFilePath)) { 
		unlink($pngAbsoluteFilePath);
	} 

	// generating 
	if (!file_exists($pngAbsoluteFilePath)) { 
		QRcode::png($codeContents, $pngAbsoluteFilePath); 
		// echo "asdfasdf";exit;
	}
	$set_code = new KenaikanGajiBerkala();
	$set_code->setField("PERIODE", $reqPeriode);
	$set_code->setField("PEGAWAI_ID", $tempPegawaiId);
	$set_code->setField("QR_CODE", $fileName);
	$set_code->updateQrCode();

?>


	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial" >
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 25%" colspan="2"></td>
			<td style="width: 20%"></td>
			<td style="width: 20%"> Jombang, <?=$reqTanggalSK?></td>
		</tr>
		<tr>
			<td style="width: 10%"> Nomor </td>
			<td style="width: 25%"colspan="2">: <b><?=$reqSKNomorBaru?> </b></td>
			<td style="width: 25%"></td>
			<td style="width: 20%"> Kepada.  </td>
		</tr>
		<tr>
			<td style="width: 10%"> Sifat </td>
			<td style="width: 20%"colspan="2">: Penting</td>
			<td style="width: 25%; text-align: right"> Yth. Sdr. </td>
			<td style="width: 20%; vertical-align: top" rowspan="3"><b>Kepala Badan Pengelolaan 
			<br>Keuangan dan Aset Daerah 
			<br>Kabupaten Jombang</td>
		</tr>
		<tr>
			<td style="width: 10%"> Lampiran </td>
			<td style="width: 40%"colspan="2">:  - </td>
			<td style="width: 10%"> </td>
		</tr>
		<tr>
			<td style=""> Hal </td>
			<td style="width: 10%" colspan="2"> : <b> Kenaikan Gaji Berkala </b>  </td>
			<td style="width: 10%"> </td>
		</tr>
		<tr>
			<td style=""></td>
			<td style="width: 10%" colspan="2"></td>
			<td style="width: 10%"></td>
			<td style="">di -</td>
		</tr>
		<tr>
			<td style=""></td>
			<td style="width: 10%" colspan="2"> </td>
			<td style="width: 10%"> </td>
			<td style="">J O M B A N G</td>
		</tr>
	</table>

	<br>
	<br>
	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px " >
		<tr>
			<td></td>
			<td colspan="4"> &ensp;&ensp;&ensp;Sehubungan dengan telah dipenuhinya masa kerja dan persyaratan untuk
				kenaikan gaji berkala, dengan ini diberitahukan bahwa sesuai 
				<b><?=$reqNamaPeraturan?>, <?=$reqStatusPegawai?> </b> tersebut dibawah ini </td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Nama</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqNamaLengkap?> </b> </td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">NIP</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqNipBaru?> </b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Pangkat / Gol.Ruang</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqPangkatNama?> </b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%;vertical-align: top">Unit Kerja</td>
			<td style="width: 13%;text-align: right;vertical-align: top" >:</td>
			<td style=" text-align: justify;vertical-align: top" colspan="3"><b><?=$reqSatuanKerjaNama?> </b></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4">berdasarkan surat keterangan terakhir tentang gaji/pangkat yang ditetapkan :</td>
		</tr>
	</table>
	<br>
	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px" >
		<tr>
			<td style="width: 10%"></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Pejabat</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqPejabatPenetapNamaLama?> </b> </td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Tanggal</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqTanggalSKLama?> </b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Nomor</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqSKNomorLama?></b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Gaji Pokok ( lama )</td>
			<td style="width: 13%;text-align: right" >:</td>
			<td style=" text-align: justify;" colspan="3"><b>Rp. <?=$reqGajiPokokLama?></b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Masa Kerja Golongan</td>
			<td style="width: 13%;text-align: right" >:</td>
			<td style=" text-align: justify;" colspan="3"><b><?=$reqMasaKerjaLama?></b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">TMT Berlakunya Gaji</td>
			<td style="width: 13%;text-align: right" >:</td>
			<td style=" text-align: justify;" colspan="3"><b><?=$reqTmtLama?></b></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="4"><?=$reqHukumanKeterangan?></td>
		</tr>
	</table>

	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px" >
		<tr>
			<td style="width: 10%"></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%;vertical-align: top">Gaji Pokok ( baru ) </td>
			<td style="width: 13%;text-align: right;vertical-align: top"">:</td>
			<td style="" colspan="3";vertical-align: top"><b>Rp. <?=$reqGajiBaru?>  (<?=$reqTerbilang?> Rupiah) </b> </td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">Masa Kerja Golongan</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqMasaKerjaBaru?></b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%">TMT Berlakunya Gaji</td>
			<td style="width: 13%;text-align: right">:</td>
			<td style="" colspan="3"><b><?=$reqTMTBaru?></b></td>
		</tr>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<? if(!empty($reqCheckTanggalSkHukuman))
			{
			?>
				<td colspan="4">Kenaikan gaji berkalanya dibayarkan mulai tanggal <?=$reqTmtBerikutGaji?> karena yang bersangkutan terkena hukuman disiplin sesuai dengan Surat Nomor <?=$reqNoSkHukuman?> tanggal <?=$reqTanggalSkHukuman?></td>
			<?
			}
			else
			{
			?>
				<td colspan="4"></td>
			<?
			}
			?>
		</tr>
		<tr>
			<td></td>
			<td colspan="4">Demikian untuk menjadikan periksa dan penyelesaian lebih lanjut.</td>
		</tr>
	</table>
	<br>

	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px;" >
		<tr>
			<td style="width: 10%"></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%"><img src="<?=$linkfile?>"> </td>
			<td style="width: 13%;text-align: right"></td>
			<td style="vertical-align: top;" colspan="3"><b><?=$reqPejabatPenetapLengkap?></b>  </td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%"></td>
			<td style="width: 30%;text-align: right"></td>
			<td style="text-align: left" colspan="3"><b></b></td>
		</tr>
		<tr>
			<td style="width: 10%"></td>
			<td style="width: 20%"></td>
			<td style="width: 30%;"></td>
			<td style="text-align: left"><u><b><?=$reqNamaPejabatTtd?></b></u>
			<br>
			<?=$reqPangkatTtd?>
			<br>
			NIP. <?=$reqNipTtd?>
			</td>
		</tr>
	</table>

	<table style="width: 100%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px;" >
		<tr>
			<td style="width: 10%">TEMBUSAN disampaikan kepada</td>
			<td style="width: 10%"> </td>
			<td style="width: 13%;text-align: right"></td>
		</tr>
	</table>

	<table style="width: 50%;border-collapse: 1px solid black;font-size: 16px;font-family: arial;margin-left:10px;"  >
		<tr>
			<td style="width: 15%;vertical-align: top">Yth. Sdr.</td>
			<td style="width: 5%;vertical-align: top">1 .</td>
			<td style="width: 90%">
				<b><?=$reqSatkerInduk?> </b>
			</td>
		</tr>
		<tr>
			<td style="width: 15%;vertical-align: top"></td>
			<td style="width: 5%;vertical-align: top">2 .</td>
			<td style="width: 90%">
				Pegawai Yang Bersangkutan
			</td>
		</tr>
	</table>
	<? if($i == $jumlah)
	{}
	else
	{
		?>
		<p style="page-break-after: always;">&nbsp;</p>
		<?
	}
	?>
	<!-- <br> -->
<?
$i++;
}
?>


