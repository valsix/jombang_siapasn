<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
$this->load->model('persuratan/CetakPdf');
$this->load->model('persuratan/TandaTanganBkd');

$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqPeriode= $this->input->get("reqPeriode");

$tanggalHariIni= date("Y-m-d");

$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiId." AND A.PERIODE = '".$reqPeriode."'";
$set= new CetakPdf();
$set->selectByParamsKenaikanGajiBerkala(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;

$reqSuratTanggal= getFormattedDateJson($tanggalHariIni);//'01 Maret 2018 ';
$reqPangkatBaruTanggalSk= dateToPageCheck($set->getField("PANGKAT_BARU_TANGGAL_SK"));
$reqSuratNomor= $set->getField("NOMOR_SURAT");//'822 / 1.1 / 415.41 / 2018';
$reqSuratHal= 'Kenaikan Gaji Berkala';
$reqSuratnama= $set->getField("NAMA_LENGKAP");//'RUDY SETIAWAN';
$reqSuratNip= $set->getField("NIP_BARU");//'198006232010011002';
$reqSuratPangkat= $set->getField("PANGKAT_DASAR_NAMA")." / (".$set->getField("PANGKAT_DASAR_KODE").")";//'Pengatur Muda / (II/a)';
$reqSuratUnitKerja= $set->getField("PEGAWAI_SATUAN_KERJA_NAMA_DETIL");//'Sub Bagian Umum – Sekretariat – Dinas Pertanian Sub Bagian Umum – Sekretariat – Dinas Pertanian Sub Bagian Umum – Sekretariat – Dinas Pertanian Sub Bagian Umum – Sekretariat – Dinas Pertanian ';

$reqDitetapkanPejabat= $set->getField("PANGKAT_DASAR_PEJABAT_PENETAP");
$reqDitetapkanTanggal= getFormattedDateJson($set->getField("PANGKAT_DASAR_TANGGAL_SK"));//'02 Januari 2016';
$reqDitetapkanNomor= $set->getField("PANGKAT_DASAR_NO_SK");//'822.2 / 00404 / 415.42 / 2016';
$reqDitetapkanGajiLama= currencyToPage($set->getField("PANGKAT_DASAR_GAJI"));//'Rp. 1.801.100,00';
$reqDitetapkanMasaKerja= $set->getField("PANGKAT_DASAR_MASA_KERJA_TAHUN")." Tahun ".$set->getField("PANGKAT_DASAR_MASA_KERJA_BULAN")." Bulan";//'07 Tahun 00 Bulan';
$reqDitetapkanTMT= getFormattedDateJson($set->getField("PANGKAT_DASAR_TMT"));//'01 Maret 2016';

$reqKenaikanGajiBaru= currencyToPage($set->getField("PANGKAT_BARU_GAJI"));//'Rp. 1.854.900,00';
$reqKenaikanMasaKerja= $set->getField("PANGKAT_BARU_MASA_KERJA_TAHUN")." Tahun ".$set->getField("PANGKAT_BARU_MASA_KERJA_BULAN")." Bulan";//'09 Tahun 00 Bulan';
$reqKenaikanTmt= getFormattedDateJson($set->getField("PANGKAT_BARU_TMT"));//'01 Maret 2018';

$reqDibayarTanggal= getFormattedDateJson($set->getField("TMT_BERIKUT_GAJI"));//'01 April 2019';
$reqDibayarHukuman= $set->getField("HUKUMAN_NO_SK");//'Surat Nomor xxx/1313/415.41/2018';
$reqDibayarHukumanTanggal= getFormattedDateJson($set->getField("HUKUMAN_TMT_SK"));//'01 Januari 2018';
$reqHukumanId= $set->getField("HUKUMAN_RIWAYAT_ID");
$reqSatuanKerjaKepalaInduk= $set->getField("SATUAN_KERJA_KEPALA_INDUK");

$statement= " AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(TO_DATE('".$reqPangkatBaruTanggalSk."', 'DD-MM-YYYY')))";
$tandatangan= new TandaTanganBkd();
$tandatangan->selectByParams(array(),-1,-1, $statement);
// echo $tandatangan->query;exit;
$tandatangan->firstRow();
$reqPenandatanganPlt= $tandatangan->getField("PLT_JABATAN");
$tempJabatan= str_replace("\\n", "<br/>", $tandatangan->getField("PEJABAT_PENETAP"));
$tempJabatanLengkap= str_replace("\\n", "<br/>", $tandatangan->getField("PEJABAT_PENETAP_LENGKAP"));

if($tempJabatanLengkap == "")
$reqPenandatanganJabatan= $tempJabatan;
else
$reqPenandatanganJabatan= $tempJabatan;

//'KEPALA BADAN KEPEGAWAIAN DAERAH PENDIDIKAN DAN PELATIHAN';
if($reqPenandatanganPlt == ""){}
else
$reqPenandatanganJabatan= ucwordsPertama($reqPenandatanganPlt).". ".$reqPenandatanganJabatan;

$reqPenandatanganNama= $tandatangan->getField("NAMA_PEJABAT");//'Drs. MUNTHOLIP, M.Si';
$reqPenandatanganPangkat= $tandatangan->getField("PANGKAT");//'Pembina Utama Muda';
$reqPenandatanganNip= $tandatangan->getField("NIP");//'196210241994031008';
//echo $reqPenandatanganJabatan."**".$tandatangan->getField("PEJABAT_PENETAP_LENGKAP");exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/format_export.css" type="text/css">
</head>
<body>
	<div id="container">

		<div class="row">
			<div style="height: 5cm; width: 100%;">
				<!-- KOP SURAT -->
			</div>
		</div>

		<div class="row">
			<div style="margin:0 0 20px 457px;">
				Jombang, <b><?=$reqSuratTanggal?></b>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table style="width:100%; margin-left:0px">
					<tr>
						<td style="width:10%">
							Nomor
						</td>
						<td width="2%">:</td>
						<td style="width:40%; ">
							<b><?=$reqSuratNomor?></b>
						</td>
						<td style="width:25%; padding-left:31px;">
							Kepada
						</td>
					</tr>
					<tr>
						<td>
							Sifat
						</td>
						<td>:</td>
						<td>
							Penting
						</td>
						<td style="">
							Yth. Sdr. <b>Kepala Badan Pengelolaan</b>
						</td>
					</tr>
					<tr>
						<td>
							Lampiran
						</td>
						<td>:</td>
						<td>
							-
						</td>
						<td style="padding-left:62px; ">
							<b>Keuangan dan Aset Daerah</b>
						</td>
					</tr>
					<tr>
						<td>
							Hal
						</td>
						<td>:</td>
						<td>
							<b><?=$reqSuratHal?></b>
						</td>
						<td style="padding-left:62px; ">
							<b>Kabupaten Jombang</b> 
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td style="padding-left:62px; ">
							di-
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td style="padding-left:95px; ">
							J O M B A N G
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row content" style="margin-top:25px; text-align: justify;">
			&emsp;&emsp;&emsp;Sehubungan dengan telah dipenuhinya masa kerja dan persyaratan untuk kenaikan gaji berkala, dengan ini diberitahukan bahwa sesuai <b>Peraturan Pemerintah Nomor 15 Tahun 2012</b>, <b>Pegawai Negeri Sipil</b> tersebut dibawah ini :
		</div>
		<div class="row content" style="margin-top:15px; text-align:justify">
			<table>
				<tr>
					<td width="150">Nama</td>
					<td>:</td>
					<td><b><?=$reqSuratnama?></b></td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td><b><?=$reqSuratNip?></b></td>
				</tr>
				<tr>
					<td>Pangkat/Gol Ruang</td>
					<td>:</td>
					<td><b><?=$reqSuratPangkat?></b></td>
				</tr>
				<tr>
					<td>Unit Kerja</td>
					<td>:</td>
					<td><b><?=$reqSuratUnitKerja?></b></td>
				</tr>
			</table>
		</div>
		<div class="row content" style="margin-top:15px">
			berdasarkan surat keterangan terakhir tentang gaji/pangkat yang ditetapkan :
		</div>
		<div class="row content" style="margin-top:15px; text-align:justify">
			<table>
				<tr>
					<td width="150">Pejabat</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanPejabat?></b></td>
				</tr>
				<tr>
					<td>Tanggal</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanTanggal?></b></td>
				</tr>
				<tr>
					<td>Nomor</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanNomor?></b></td>
				</tr>
				<tr>
					<td>Gaji Pokok ( lama )</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanGajiLama?></b></td>
				</tr>
				<tr>
					<td>Masa Kerja Golongan</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanMasaKerja?></b></td>
				</tr>
				<tr>
					<td>TMT Berlakunya Gaji</td>
					<td>:</td>
					<td><b><?=$reqDitetapkanTMT?></b></td>
				</tr>
			</table>
		</div>
		<div class="row content" style="margin-top:15px">
			<?
			if($reqHukumanId == ""){}
			else
			{
            ?>
            yang seharusnya 
            <?
			}
            ?>
            diberikan kenaikan gaji berkala hingga memperoleh :
		</div>
		<div class="row content" style="margin-top:15px; text-align:justify">
			<table>
				<tr>
					<td width="150">Gaji Pokok ( baru )</td>
					<td>:</td>
					<td><b><?=$reqKenaikanGajiBaru?></b></td>
				</tr>
				<tr>
					<td>Masa Kerja Golongan</td>
					<td>:</td>
					<td><b><?=$reqKenaikanMasaKerja?></b></td>
				</tr>
				<tr>
					<td>TMT Berlakunya Gaji</td>
					<td>:</td>
					<td><b><?=$reqKenaikanTmt?></b></td>
				</tr>
			</table>
		</div>
        
        <?
		if($reqHukumanId == ""){}
		else
		{
		?>
		<div class="row content" style="margin-top:15px">
			Kenaikan gaji berkalanya dibayarkan mulai tanggal <b><?=$reqDibayarTanggal?></b>   karena yang bersangkutan terkena hukuman disiplin sesuai dengan <b><?=$reqDibayarHukuman?></b> tanggal <b><?=$reqDibayarHukumanTanggal?></b>.
		</div>
		<?
		}
        ?>
        
        <div class="row content" style="margin-top:15px">
			Demikian untuk menjadikan periksa dan penyelesaian lebih lanjut.
		</div>
        
		<div class="row col-6 float-r" style="margin-top:10px">
			<div style="margin-bottom:50px">
				<p style="margin-bottom: 0px; text-align: center;">
					<span<?php /*?> style="text-transform: uppercase;"<?php */?>>
						<b>
							<?=$reqPenandatanganJabatan?>,
						</b>
					</span>
				</p>
			</div>
			<div style="margin-left: 100px">
				<span><b><?=$reqPenandatanganNama?></b></span><br>
				<span><?=$reqPenandatanganPangkat?></span><br>
				<span>NIP. <?=$reqPenandatanganNip?></span>
			</div>
		</div>

		<div class="row" style="margin-top:15px">
			TEMBUSAN disampaikan kepada<br>
			<table>
				<tr>
					<td>Yth. Sdr.</td>
					<td>1.</td>
					<td><b><?=$reqSatuanKerjaKepalaInduk?></b></td>
				</tr>
				<tr>
					<td></td>
					<td>2.</td>
					<td>Pegawai Yang Bersangkutan</td>
				</tr>
			</table>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
