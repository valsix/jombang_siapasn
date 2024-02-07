<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukUpt');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");

$statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
$set= new SuratMasukUpt();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;

//AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) 
$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
$reqSuratSatuanKerjaPengirimNama= $set->getField('SATUAN_KERJA_PENGIRIM');
$reqSuratSatuanKerjaPengirimKepala= $set->getField('SATUAN_KERJA_PENGIRIM_KEPALA');
$reqSuratNomor= $set->getField('NOMOR');
$reqSuratTanggal= dateToPageCheck($set->getField('TANGGAL'));
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
$statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
//$statement= " AND A.PEGAWAI_ID = ".$reqPegawaiPilihKepalaId;
$set->selectByParamsKepala(array(), -1, -1, $statement, "ORDER BY JAB_RIW.TIPE_PEGAWAI_ID ASC, JAB_RIW.TMT_JABATAN DESC");
$set->firstRow();
//echo $set->query;exit;
$reqKepalaSatuanKerjaKepala= $set->getField('SATUAN_KERJA_KEPALA');
$reqKepalaSatuanKerjaInduk= $set->getField('SATUAN_KERJA_INDUK');
$reqKepalaPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqKepalaPegawaiNipBaru= $set->getField('NIP_BARU');
$reqKepalaPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqKepalaPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqKepalaPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
</head>
<body>
	<div id="container">
		<div class="row">
			<div style="margin:0 0 20px 330px;">
				Jombang, <?=$reqSuratTanggal?>
			</div>
		</div>
		<div class="row">
			<div class="col-6 float-l">
				<table>
					<tr>
						<td width="27%">
							Nomor
						</td>
						<td width="5%">:</td>
						<td width="68%">
							<?=$reqSuratNomor?>
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
					</tr>
					<tr>
						<td>
							Lampiran
						</td>
						<td>:</td>
						<td>
							1 (satu) berkas
						</td>
					</tr>
					<tr>
						<td>
							Perihal
						</td>
						<td>:</td>
						<td>
							Permohonan Ijin Belajar
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
			<div class="col-6 float-r">
				<div class="row" style="margin-left:20px">
					Kepada
				</div>
				<div class="row" style="margin-left:-11px">
					Yth. <?=$reqSuratKepada?>
				</div>
				<div class="row" style="margin-left:40px; margin-top:10px">
					di-
				</div>
				<div class="row" style="margin-left:70px">
					<b>JOMBANG</b>
				</div>
					<?php /*?><table>
						<td style="vertical-align: text-top;">Yth.</td>
						<td>
							Kepala asd<?=$reqSuratKepada?>asd
							<br>
							di-
						</td>
					</table><?php */?>
				<?php /*?><div class="row" style="text-align:center">
					
			</div><?php */?>
		</div>
	</div>
	<div class="row"></div>
	<div class="row content" style="margin-top:30px; text-align: justify;">
		&emsp;&emsp;&emsp;Bersama ini kami sampaikan permohonan ijin belajar dari <?=$reqSuratSatuanKerjaPengirimNama?> 
		sebagaimana sudah memenuhi syarat dan ketentuan sesuai Perbup Nomor 9 tahun 2011  tentang Persyaratan Mengikuti 
		Pendidikan dan Pelatihan, Tugas Belajar dan Ijin Belajar Bagi Pegawai Negeri Sipil Daerah Pemerintah Kabupaten 
		Jombang dan ketentuan lainnya, atas nama :
	</div>
	<div class="row content" style="margin-top:30px; text-align:justify">
		<table>
			<tr>
				<td width="35%">Nama</td>
				<td width="5%">:</td>
				<td width="60%"><?=$reqPegawaiNamaLengkap?></td>
			</tr>
			<tr>
				<td>NIP</td>
				<td>:</td>
				<td><?=$reqPegawaiNipBaru?></td>
			</tr>
			<tr>
				<td>Pangkat/gol</td>
				<td>:</td>
				<td><?=$reqPegawaiPangkatNama?> / <?=$reqPegawaiPangkatKode?></td>
			</tr>
			<tr>
				<td>Jabatan</td>
				<td>:</td>
				<td><?=$reqPegawaiJabatanNama?></td>
			</tr>
			<tr>
				<td>Unit kerja/Instansi</td>
				<td>:</td>
				<td><?=$reqPegawaiSatuanKerja?></td>
			</tr>
			<tr>
				<td>Pendidikan terakhir</td>
				<td>:</td>
				<td><?=$reqPegawaiPendidikanNama?> / <?=$reqPegawaiPendidikanJurusan?></td>
			</tr>
			<tr>
				<td>Ijin belajar pada</td>
				<td>:</td>
				<td><?=$reqPegawaiPendidikanUsulanNamaSekolah?></td>
			</tr>
			<tr>
				<td>Program Pendidikan</td>
				<td>:</td>
				<td><?=$reqPegawaiPendidikanUsulanFakultas?></td>
			</tr>
			<tr>
				<td>Program studi/jurusan</td>
				<td>:</td>
				<td><?=$reqPegawaiPendidikanUsulanJurusan?></td>
			</tr>
			<tr>
				<td>Tempat perkuliahan</td>
				<td>:</td>
				<td><?=$reqPegawaiPendidikanUsulanTempat?></td>
			</tr>
		</table>
	</div>
	<div class="row content" style="margin-top:30px">
		&emsp;&emsp;&emsp;Demikian untuk menjadikan maklum.
	</div>
	<div class="row col-6 float-r" style="margin-top:70px">
		<div  style="margin-bottom:100px">
			<span style="text-transform: uppercase;"><?=$reqSuratSatuanKerjaPengirimKepala?></span><br>
			<span>KABUPATEN JOMBANG</span>
		</div>
		<div>
			<span style="text-transform: uppercase;"><b><u><?=$reqKepalaPegawaiNama?></u></b></span><br>
			<span><?=$reqKepalaPegawaiPangkatNama?></span><br>
			<span>NIP. <?=$reqKepalaPegawaiNipBaru?></span>
		</div>
	</div>
	<div class="row"></div>
</div>
</body>
</html>
