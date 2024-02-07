<?

$reqSuratTanggal = '01 Maret 2018 ';
$reqSuratNomor = '822 / 1.1 / 415.41 / 2018';
$reqSuratHal = 'Kenaikan Gaji Berkala';
$reqSuratnama = 'RUDY SETIAWAN';
$reqSuratNip = '198006232010011002';
$reqSuratPangkat = 'Pengatur Muda / (II/a)';
$reqSuratUnitKerja = 'Sub Bagian Umum – Sekretariat – Dinas Pertanian';

$reqDitetapkanTanggal = '02 Januari 2016';
$reqDitetapkanNomor = '822.2 / 00404 / 415.42 / 2016';
$reqDitetapkanGajiLama = 'Rp. 1.801.100,00';
$reqDitetapkanMasaKerja = '07 Tahun 00 Bulan';
$reqDitetapkanTMT = '01 Maret 2016';

$reqKenaikanGajiBaru = 'Rp. 1.854.900,00';
$reqKenaikanMasaKerja = '09 Tahun 00 Bulan';
$reqKenaikanTmt = '01 Maret 2018';

$reqDibayarTanggal = '01 April 2019';
$reqDibayarHukuman = 'Surat Nomor xxx/1313/415.41/2018';
$reqDibayarHukumanTanggal = '01 Januari 2018';

$reqPenandatanganJabatan = 'KEPALA BADAN KEPEGAWAIAN DAERAH PENDIDIKAN DAN PELATIHAN';
$reqPenandatanganNama = 'Drs. MUNTHOLIP, M.Si';
$reqPenandatanganPangkat = 'Pembina Utama Muda';
$reqPenandatanganNip = '196210241994031008';
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
			<div style="height: 150px;">
				<!-- KOP SURAT -->
			</div>
			<hr>
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
						<td style="padding-left:58px; ">
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
						<td style="padding-left:58px; ">
							<b>Kabupaten Jombang</b> 
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td style="padding-left:58px; ">
							di-
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td style="padding-left:78px; ">
							JOMBANG
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row content" style="margin-top:15px; text-align: justify;">
			&emsp;&emsp;&emsp;Sehubungan dengan telah dipenuhinya masa kerja dan persyaratan untuk kenaikan gaji berkala, dengan ini diberitahukan bahwa sesuai <b>Peraturan Pemerintah Nomor 15 Tahun 2012</b> <sup>i</sup>, <b>Pegawai Negeri Sipil</b> <sup>ii</sup> tersebut dibawah ini :
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
					<td><b><?=$reqDitetapkanPejabat?>BUPATI JOMBANG</b></td>
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
			yang seharusnya diberikan kenaikan gaji berkala hingga memperoleh :
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
		<div class="row content" style="margin-top:15px">
			Kenaikan gaji berkalanya dibayarkan mulai tanggal <b><?=$reqDibayarTanggal?></b>   karena yang bersangkutan terkena hukuman disiplin sesuai dengan <b><?=$reqDibayarHukuman?></b> tanggal <b><?=$reqDibayarHukumanTanggal?></b>.
		</div>
		<div class="row content" style="margin-top:15px">
			Demikian untuk menjadikan periksa dan penyelesaian lebih lanjut.
		</div>


		<div class="row col-6 float-r" style="margin-top:15px">
			<div style="margin-bottom:70px">
				<p style="margin-bottom: 0px; text-align: center;">
					<span style="text-transform: uppercase;">
						<b>
							<?=$reqPenandatanganJabatan?>,
							<br>	
							KABUPATEN JOMBANG,
						</b>
					</span>
				</p>
			</div>
			<div style="margin-left: 100px">
				<span style="text-transform: uppercase;"><b><u><?=$reqPenandatanganNama?></u></b></span><br>
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
					<td><b>Kepala Dinas Pertanian</b></td>
				</tr>
				<tr>
					<td></td>
					<td>2.</td>
					<td>Pegawai Yang Bersangkutan</td>
				</tr>
			</table>
		</div>
		<hr>
		<div class="row" style="margin-top:15px">
			<table style="font-weight: bold;">
				<tr>
					<td><sup>i</sup></td>
					<td>Diambilkan dari gaji pokok</td>
				</tr>
				<tr>
					<td><sup>ii</sup></td>
					<td>sesuai dengan Status Pegawai di FIP, jika CPNS ditulis Calon Pegawai Negeri Sipil, gajinya 80%</td>
				</tr>
				<tr>
					<td><sup>iii</sup></td>
					<td>Sesuai dengan TMT KGB berikutnya di Hukdis.</td>
				</tr>
			</table>
		</div>

		<div class="row"></div>
	</div>
</body>
</html>
