<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");

// $reqJabatanPilihan= str_replace("Plt. ", "", $reqJabatanPilihan);

if($reqStatusBkdUptId == "1")
{
	$statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
	$set= new SuratMasukUpt();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
	$statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
}
elseif($reqStatusBkdUptId == "2")
{
	$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
}
elseif($reqStatusBkdUptId == "3")
{
	$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrangUsulan(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	// $reqNomorSuratKeluar= $set->getField('NOMOR_SURAT_KELUAR');
	$reqNomorSuratKeluar= $set->getField('NOMOR_SURAT_KELUAR_USULAN');
	$reqTanggalSuratKeluar= getFormattedDateJson($set->getField('TANGGAL_SURAT_KELUAR'));
	// $reqTanggalSuratKeluar= getFormattedDateJson($set->getField('TANGGAL_SURAT_KELUAR_USULAN'));

	if($set->getField('TANGGAL_SURAT_KELUAR') == "")
	{
		$reqTanggalSuratKeluar= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".getNameMonth(date("m"))." ".date("Y");
	}
	
	$statement_satuan_kerja= " AND STATUS_SATUAN_KERJA_BKPP = 1";
	$skerja= new SuratMasukPegawai();
	$tempsatuankerjaidbkdpp= $skerja->getSatuanKerjaId($statement_satuan_kerja);

	$skerja= new SuratMasukPegawai();
	$reqSatuanKerjaId= $skerja->getSatuanKerja($tempsatuankerjaidbkdpp);
	unset($skerja);
	//echo $reqSatuanKerjaId;exit;
	$statement= " AND A.SATUAN_KERJA_ID IN (".$reqSatuanKerjaId.")";
}
	
	$styleBoldAn= "";
	if($reqJabatanManual == "")
	{
		$reqSuratSatuanKerjaPengirimKepala= $reqJabatanPilihan;
		// $reqSuratSatuanKerjaPengirimKepala= $set->getField('SATUAN_KERJA_PENGIRIM_KEPALA');
	}
	else
	{
		$styleBoldAn= "font-weight: bold;";
		$reqSuratSatuanKerjaPengirimKepala= $reqJabatanManual;
	}

	$reqSuratNomor= $set->getField('NOMOR');
	$reqSuratTanggal= getFormattedDateJson($set->getField('TANGGAL'));
	$reqSuratKepada= $set->getField('KEPADA');
	$reqPegawaiNama= $set->getField('NAMA');
	$reqPegawaiNamaLengkap= $set->getField('NAMA_LENGKAP');
	$reqPegawaiNipBaru= $set->getField('NIP_BARU');
	$reqPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
	$reqPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
	$reqPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
	
	$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
	$reqSuratSatuanKerjaPengirimNama= $set->getField('SATUAN_KERJA_PENGIRIM');
	$reqSurat= $set->getField('NAMA_LENGKAP');
	//echo $reqSuratKepada;exit;
	$reqPegawaiSatuanKerja= $set->getField('SATUAN_KERJA_DETIL');
	
	$reqKarpegKeterangan= $set->getField('KETERANGAN');
	//KPG_RIW.JENIS_KARPEG, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.
	
	$set= new SuratMasukPegawai();
	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
	$set->selectByParamsKepala(array(), -1, -1, $statement, "ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC");
	$set->firstRow();
	//echo $set->query;exit;
	$reqKepalaSatuanKerjaKepala= $set->getField('SATUAN_KERJA_KEPALA');
	$reqKepalaSatuanKerjaInduk= $set->getField('SATUAN_KERJA_INDUK');
	$reqKepalaPegawaiNama= $set->getField('NAMA_LENGKAP');
	$reqKepalaPegawaiNipBaru= $set->getField('NIP_BARU');
	$reqKepalaPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
	$reqKepalaPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
	$reqKepalaPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');

	if($reqStatusBkdUptId == "3")
	{		
		$reqSuratSatuanKerjaPengirimKepala= $reqKepalaSatuanKerjaKepala;
		$reqSuratKepada= "Kepala Kantor Regional II BKN    Surabaya";

		$reqSuratNomor= $reqNomorSuratKeluar;
		$reqSuratTanggal= $reqTanggalSuratKeluar;
	}

$reqJumlahUsulanPegawai= 1;
$reqPermohonan = 'Kehilangan';

$panjangKepada= strlen($reqSuratKepada);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/karpeg.css" type="text/css">
</head>
<body>
	<div id="container">
    	<?
		if($panjangKepada <= 34)
		{
        ?>
		<div class="row">
			<!-- <div style="margin:0 0 20px 370px;"> -->
			<div style="margin:100px 0 20px 370px;">
				Jombang, <?=$reqSuratTanggal?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table style="width:100%; margin-left:-50px">
					<tr>
						<td style="width:10%">
							Nomor
						</td>
						<td width="2%">:</td>
						<td style="width:40%; ">
							<?=$reqSuratNomor?>
						</td>
                        <td style="width:38%; padding-left:31px; ">
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
                        <td style="width:38%; ">
							Yth. <?=$reqSuratKepada?>
						</td>
					</tr>
					<tr>
						<td>
							Lampiran
						</td>
						<td>:</td>
						<td>
							<?=$reqJumlahUsulanPegawai?> (<?=kekata($reqJumlahUsulanPegawai)?>) berkas
						</td>
                        <td style="padding-left:31px; ">
							di-
						</td>
					</tr>
					<tr>
						<td>
							Hal
						</td>
						<td>:</td>
						<td>
							Permohonan cetak ulang
						</td>
                        <td style="padding-left:31px; ">
							JOMBANG
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							Karpeg karena <?=$reqPermohonan?> 
						</td>
                        <td style="padding-left:61px; ">
							
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							a.n. <?=$reqPegawaiNama?>
						</td>
					</tr>
				</table>
			</div>
		</div>
        <?
		}
		else
		{
        ?>
        <div class="row">
			<!-- <div style="margin:0 0 20px 370px;"> -->
			<div style="margin:100px 0 20px 370px;">
				Jombang, <?=$reqSuratTanggal?>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table style="width:100%; margin-left:-50px">
					<tr>
						<td style="width:10%">
							Nomor
						</td>
						<td width="2%">:</td>
						<td style="width:40%; ">
							<?=$reqSuratNomor?>
						</td>
                        <td style="width:38%; padding-left:31px; ">
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
                        <td style="width:38%; ">
							Yth. <?=substrfullword($reqSuratKepada,0,33)?>
						</td>
					</tr>
					<tr>
						<td>
							Lampiran
						</td>
						<td>:</td>
						<td>
							<?=$reqJumlahUsulanPegawai?> (<?=kekata($reqJumlahUsulanPegawai)?>) berkas
						</td>
                        <td style="padding-left:31px; ">
							<?=substr($reqSuratKepada,getpanjangword($reqSuratKepada,0, 33))?>
						</td>
					</tr>
					<tr>
						<td>
							Hal
						</td>
						<td>:</td>
						<td>
							Permohonan cetak ulang
						</td>
                        <td style="padding-left:31px; ">
							di-
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							Karpeg karena <?=$reqPermohonan?> 
						</td>
                        <td style="padding-left:31px; ">
							JOMBANG
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							a.n. <?=$reqPegawaiNama?>
						</td>
					</tr>
				</table>
			</div>
		</div>
        <?
		}
        ?>
		<div class="row"></div>
		<div class="row content" style="margin-top:30px; text-align: justify;">
			&emsp;&emsp;&emsp;Sehubungan dengan surat laporan Kehilangan Kartu Pegawai (KARPEG) : 
		</div>
		<div class="row content" style="">
			<table style="margin-left: -3px">
				<tr>
					<td style="width: 200px">Nama</td>
					<td>:</td>
					<td><?=$reqPegawaiNamaLengkap?></td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td><?=$reqPegawaiNipBaru?></td>
				</tr>
				<tr>
					<td>Pangkat/Gol. Ruang</td>
					<td>:</td>
					<td><?=$reqPegawaiPangkatNama?> / <?=$reqPegawaiPangkatKode?></td>
				</tr>
				<tr>
					<td>Jabatan</td>
					<td>:</td>
					<td><?=$reqPegawaiJabatanNama?></td>
				</tr>
			</table>
		</div>
		<div class="row content" style="text-align: justify;">
			maka dengan ini kami mohon diterbitkan kembali Kartu Pegawai (KARPEG) yang bersangkutan dan sebagai bahan pertimbangan dilampirkan berkas persyaratan sebagai berikut : 
		</div>
		<div class="row content" style="text-align: justify;">
			<table>
				<tr>
					<td>1.</td>
					<td>Laporan Kehilangan dari Kepolisian </td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Foto Copy SK CPNS;</td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Foto Copy SK PNS;</td>
				</tr>
				<tr>
					<td>4.</td>
					<td>Foto Copy Karpeg;</td>
				</tr>
				<tr>
					<td>5.</td>
					<td>Pas Foto ukuran 3x 3 Cm. (2 lbr)</td>
				</tr>
			</table>
		</div>
		<div class="row content" style="">
			&emsp;&emsp;&emsp;Demikian atas bantuan  dan kerjasamanya disampaikan terima kasih.
		</div>
		<div class="row col-6 float-r" style="margin-top:50px">
			<?
			if($reqStatusBkdUptId == "3")
			{
				$styleBoldAn= "";
			?>
			<!--<div style="margin-bottom:100px; margin-left: 200px">
				<div style="margin-left: -222px"><b>an.Bupati Jombang</b></div>
				<div style="margin-left: -200px">Kepala Badan Kepegawaian Daerah,<br/>Pendidikan dan Pelatihan</div>
			</div>			-->
			<div style="margin-bottom:100px">
				<p style="margin-left:-45px; margin-bottom: 0px; <?=$styleBoldAn?>">
					<?
					if($reqJabatanManual == "")
					{
					?>
					<?
					}
					else
					{
					?>
					a.n <?=$reqJabatanManual?>
					<br/>
					<?
					}
					$reqSuratSatuanKerjaPengirimKepala= $reqJabatanPilihan;
					$panjangSuratSatuanKerjaPengirimKepala= strlen($reqSuratSatuanKerjaPengirimKepala);
					?>
					<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<span style="text-transform: uppercase;"><?=substrfullword($reqSuratSatuanKerjaPengirimKepala,0,40)?></span>
				</p>
				<?
				if($panjangSuratSatuanKerjaPengirimKepala > 40)
				{
				?>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px; <?=$styleBoldAn?>">
				<span style="text-transform: uppercase; <?=$styleBoldAn?>"><?=substr($reqSuratSatuanKerjaPengirimKepala,getpanjangword($reqSuratSatuanKerjaPengirimKepala,0, 40))?></span>
				<br/>
				<?
				}
				?>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span style="">KABUPATEN JOMBANG</span>
			</div>

			<?
			}
			else
			{
			?>
			<div style="margin-bottom:100px">
				<p style="margin-left:-45px; margin-bottom: 0px; <?=$styleBoldAn?>">
					<?
					if($reqTipeId == "2")
					{
					?>
						<span >a.n</span>
					<?
					}
					elseif($reqTipeId == "3")
					{
					?>
						<span >plt. </span>
					<?
					}
					else
					{
						?>
						<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?
					}

					$panjangSuratSatuanKerjaPengirimKepala= strlen($reqSuratSatuanKerjaPengirimKepala);
					?>
					<span style="text-transform: uppercase;"><?=substrfullword($reqSuratSatuanKerjaPengirimKepala,0,40)?></span>
				</p>
				<?
				if($panjangSuratSatuanKerjaPengirimKepala > 40)
				{
				?>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px; <?=$styleBoldAn?>">
				<span style="text-transform: uppercase; <?=$styleBoldAn?>"><?=substr($reqSuratSatuanKerjaPengirimKepala,getpanjangword($reqSuratSatuanKerjaPengirimKepala,0, 40))?></span>
				<br/>
				<?
				}
				?>

				<?
				if($reqTipeId == "2")
				{
				?>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span style=""><?=$reqJabatanPilihan?></span>
				<br/>
				<?
				}
				?>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span style="">KABUPATEN JOMBANG</span>
			</div>
			<!-- <div  style="margin-bottom:100px">
				<span><?=$reqSuratSatuanKerjaPengirimKepala?></span><br>
			</div> -->
			<?
			}
			?>
			<div>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span><b><u><?=$reqKepalaPegawaiNama?></u></b></span><br>
				<span><?=$reqKepalaPegawaiPangkatNama?></span><br>
				<span>NIP. <?=$reqKepalaPegawaiNipBaru?></span>
				</p>
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
