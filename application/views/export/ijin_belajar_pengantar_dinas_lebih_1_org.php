<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");
//1=kepala;2=sekretaris;3=plt

$statement= " AND SMP.SURAT_MASUK_BKD_ID = ".$reqId;
$set= new SuratMasukBkd();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');

//AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) 
$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
$reqSuratSatuanKerjaPengirimNama= $set->getField('SATUAN_KERJA_PENGIRIM');

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
$reqSurat= $set->getField('NAMA_LENGKAP');
//echo $reqSuratKepada;exit;
$reqPegawaiSatuanKerja= $set->getField('SATUAN_KERJA_DETIL');
$reqPegawaiNama= $set->getField('NAMA');
$reqPegawaiNamaLengkap= $set->getField('NAMA_LENGKAP');
$reqPegawaiNipBaru= $set->getField('NIP_BARU');
$reqPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPegawaiPendidikanNama= $set->getField('PENDIDIKAN_NAMA');
$reqPegawaiPendidikanJurusan= $set->getField('JURUSAN');
$reqPegawaiPendidikanUsulanNama= $set->getField('PENDIDIKAN_NAMA_US');
$reqPegawaiPendidikanUsulanFakultas= $set->getField('NAMA_FAKULTAS_US');
$reqPegawaiPendidikanUsulanJurusan= $set->getField('JURUSAN_US');
$reqPegawaiPendidikanUsulanNamaSekolah= $set->getField('NAMA_SEKOLAH_US');
$reqPegawaiPendidikanUsulanTempat= $set->getField('TEMPAT_US');

$set= new SuratMasukPegawai();
//$statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
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

$panjangKepada= strlen($reqSuratKepada);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
</head>
<body>
	<div id="container">
		<?
		if($panjangKepada <= 34)
		{
        ?>
		<div class="row">
			<div style="margin:0 0 20px 393px;">
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
							Segera
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
							Perihal
						</td>
						<td>:</td>
						<td>
							Permohonan Izin / Tugas Belajar
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
                        <td style="padding-left:61px; ">
							
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							dkk
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
			<div style="margin:0 0 20px 393px;">
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
							Segera
						</td>
                        <td style="width:38%; ">
							Yth. <?=substr($reqSuratKepada,0,33)?>
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
							<?=substr($reqSuratKepada,33)?>
						</td>
					</tr>
					<tr>
						<td>
							Perihal
						</td>
						<td>:</td>
						<td>
							Permohonan Izin / Tugas Belajar
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
						</td>
                        <td style="padding-left:31px; ">
							JOMBANG
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							dkk
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
			&emsp;&emsp;&emsp;Bersama ini kami sampaikan permohonan ijin / tugas belajar dari <?=$reqSuratSatuanKerjaPengirimNama?> sebagaimana sudah 
			memenuhi syarat dan ketentuan sesuai Perbup Nomor 9 tahun 2011  tentang Persyaratan Mengikuti Pendidikan dan 
			Pelatihan, Tugas Belajar dan Ijin Belajar Bagi Pegawai Negeri Sipil Daerah Pemerintah Kabupaten Jombang dan 
			ketentuan lainnya, atas nama <?=$reqPegawaiNamaLengkap?>, NIP <?=$reqPegawaiNipBaru?>, dkk sebanyak <?=$reqJumlahUsulanPegawai?> orang.
		</div>
		<div class="row content" style="margin-top:30px">
			&emsp;&emsp;&emsp;Demikian untuk menjadikan maklum.
		</div>
		<div class="row col-6 float-r" style="margin-top:50px">
			<?
			if($reqStatusBkdUptId == "3")
			{
				$styleBoldAn= "";
			?>
			<!-- <div style="margin-bottom:100px; margin-left: 200px">
				<div style="margin-left: -222px"><b>an.Bupati Jombang</b></div>
				<div style="margin-left: -200px">Kepala Badan Kepegawaian Daerah,<br/>Pendidikan dan Pelatihan</div>
			</div> -->
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
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
