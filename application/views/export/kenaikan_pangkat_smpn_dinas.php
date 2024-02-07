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
	// echo $statement;exit();
	$set= new SuratMasukUpt();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqJenisKelamin= $set->getField('JENIS_KELAMIN');
	$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');
	$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
	$reqKategori= $set->getField('KATEGORI');
	$reqKategoriInfo= $set->getField('KATEGORI_INFO');

	$statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
}
elseif($reqStatusBkdUptId == "2")
{
	$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqJenisKelamin= $set->getField('JENIS_KELAMIN');
	$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');
	$reqKategori= $set->getField('KATEGORI');
	$reqKategoriInfo= $set->getField('KATEGORI_INFO');

	$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
}
elseif($reqStatusBkdUptId == "3")
{
	$statement= " AND SMP.USULAN_SURAT_ID = ".$reqId;
	$set= new SuratMasukBkd();
	$set->selectByParamsCetakPengantarSatuOrangUsulan(array(), -1, -1, $statement);
	$set->firstRow();
	//echo $set->query;exit;
	$reqJenisKelamin= $set->getField('JENIS_KELAMIN');
	$reqNomorSuratKeluar= $set->getField('NOMOR_SURAT_KELUAR');
	$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');
	$reqTanggalSuratKeluar= getFormattedDateJson($set->getField('TANGGAL_SURAT_KELUAR'));
	
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

$tempInfoSdr= "Saudara";
if($reqJenisKelamin == "L"){}
else
$tempInfoSdr= "Saudari";


if($reqKategori == "bup")
{
	$infohal1= "pemberian pensiun bagi PNS";
	$infohal2= "pensiun PNS";
}
elseif($reqKategori == "meninggal")
{
	$infohal1= "pemberian pensiun janda/duda PNS";
	$infohal2= "pensiun janda/duda PNS";
}

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
							Usul Kenaikan Pangkat
						</td>
                        <td style="padding-left:31px; ">
							<?
							if($reqStatusBkdUptId == "3")
							{
							?>
							SURABAYA
							<?
							}
							else
							{
							?>
							JOMBANG
							<?
							}
							?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							a.n. <?=$reqPegawaiNama?>
							<?
							if($reqJumlahUsulanPegawai > 1)
							{
							?>
							 dkk
							<?
							}
							?>
						</td>
                        <td style="padding-left:61px; ">
							
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
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
							Usul Kenaikan Pangkat
						</td>
                        <td style="padding-left:31px; ">
							di-
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							a.n. <?=$reqPegawaiNama?>
							<?
							if($reqJumlahUsulanPegawai > 1)
							{
							?>
							 dkk
							<?
							}
							?>
						</td>
                        <td style="padding-left:31px; ">
                        	<?
							if($reqStatusBkdUptId == "3")
							{
							?>
							SURABAYA
							<?
							}
							else
							{
							?>
							JOMBANG
							<?
							}
							?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
						</td>
					</tr>
				</table>
			</div>
		</div>
        <?
		}
        ?>
		<div class="row"></div>
		<div class="row content" style="margin-top:30px; text-align: justify;"></div>
		<div class="row content" style="text-align: justify;">
			<table style="text-align: justify; padding-left: -2px;">
				<tr>
					<!-- pemberian pensiun bagi PNS/pensiun janda/duda PNS -->
					<td>1.</td>
					<td>
						Bersama ini kami sampaikan dengan hormat usul kenaikan pangkat Pegawai Negeri Sipil di lingkungan <?=$reqSuratSatuanKerjaPengirimNama?> sebagaimana terlampir.
						Sesuai ketentuan dalam Peraturan Pemerintah Nomor 99 Tahun 2000 jo Peraturan Pemerintah Nomor 12 Tahun 2002, yang bersangkutan telah memenuhi syarat untuk dapat dipertimbangkan kenaikan pangkatnya setingkat lebih lanjut.</td>
				</tr>
				<tr>
					<!-- pemberian pensiun PNS/pensiun janda/duda PNS -->
					<td>2.</td>
					<td>
						Bersama ini pula disampaikan bahwa berkas pendukung yang dikirimkan secara online melalui SIAP ASN BKDPP Kabupaten Jombang adalah benar adanya dan merupakan tanggung jawab saya selaku <?=$reqSuratSatuanKerjaPengirimKepala?>.
				</tr>
				<tr>
					<td>3.</td>
					<td>Demikian atas perhatian dan perkenannya, kami ucapkan terima kasih.</td>
				</tr>
			</table>
		</div>
		<div class="row col-6 float-r" style="margin-top:50px">
			<?
			if($reqStatusBkdUptId == "3")
			{
			?>
			<div style="margin-bottom:100px">
				<p style="margin-left:-45px; margin-bottom: 0px; <?=$styleBoldAn?>">
					<span >a.n</span>
					<?
					$panjangSuratSatuanKerjaPengirimKepala= strlen($reqSuratSatuanKerjaPengirimKepala);
					?>
					<span style="text-transform: uppercase;">Bupati Jombang</span>
				</p>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span style=""><?=$reqJabatanPilihan?></span>
				<br/>
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
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
