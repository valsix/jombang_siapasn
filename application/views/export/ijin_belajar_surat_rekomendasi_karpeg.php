<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
$set= new SuratMasukPegawai();
$set->selectByParamsCetakRekomendasi(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqPegawaiSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
$reqPegawaiSatuanKerjaTtdId= $set->getField('SATUAN_KERJA_TTD_ID');
$reqPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqPegawaiNipBaru= $set->getField('NIP_BARU');
$reqPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPegawaiPendidikanUsulanNama= $set->getField('PENDIDIKAN_NAMA_US');
$reqPegawaiPendidikanUsulanFakultas= $set->getField('NAMA_FAKULTAS_US');
$reqPegawaiPendidikanUsulanJurusan= $set->getField('JURUSAN_US');
$reqPegawaiPendidikanUsulanNamaSekolah= $set->getField('NAMA_SEKOLAH_US');
//unset($set);

$statement= " AND A.SATUAN_KERJA_ID = ".$reqPegawaiSatuanKerjaTtdId;
$set->selectByParamsKepala(array(), -1, -1, $statement, "ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC");
$set->firstRow();
//echo $set->query;exit;
$reqKepalaSatuanKerjaInduk= $set->getField('SATUAN_KERJA_INDUK');
$reqKepalaPegawaiNama= $set->getField('NAMA_LENGKAP');
$reqKepalaPegawaiNipBaru= $set->getField('NIP_BARU');
$reqKepalaPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqKepalaPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqKepalaPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
//unset($set);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_rekomendasi.css" type="text/css">
</head>
<body>
	<div id="container">
		<div class="pad">
			<b><u><p style="font-size:14pt; text-align:center">SURAT REKOMENDASI</p></u></b>
			<div class="row " style="margin-top:30px">
				&emsp;&emsp;&emsp;Yang bertanda tangan dibawah ini  :
			</div>
			<div class="row " style="margin-top:15px">
				<table>
					<tr>
						<td width="200px">Nama</td>
						<td width="20px">:</td>
						<td><?=$reqKepalaPegawaiNama?></td>
					</tr>
					<tr>
						<td>NIP</td>
						<td>:</td>
						<td><?=$reqKepalaPegawaiNipBaru?></td>
					</tr>
					<tr>
						<td>Pangkat/Gol Ruang</td>
						<td>:</td>
						<td><?=$reqKepalaPegawaiPangkatNama?> / <?=$reqKepalaPegawaiPangkatKode?></td>
					</tr>
					<tr>
						<td>Jabatan</td>
						<td>:</td>
						<td><?=$reqKepalaPegawaiJabatanNama?></td>
					</tr>
					<tr>
						<td>Satuan Kerja</td>
						<td>:</td>
						<td><?=$reqKepalaSatuanKerjaInduk?></td>
					</tr>
				</table>
			</div>

			<div class="row " style="margin-top:30px">
				dengan ini memberikan rekomendasi / persetujuan kepada :
			</div>
			<div class="row " style="margin-top:15px">
				<table>
					<tr>
						<td width="200px">Nama</td>
						<td width="20px">:</td>
						<td><?=$reqPegawaiNama?></td>
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

			<div class="row " style="margin-top:30px">
				&emsp;&emsp;&emsp;Demikian untuk menjadikan maklum.
			</div>
			<div class="row col-6 float-r" style="margin-top:70px">
				<div  style="margin-bottom:100px">
					<span style="text-transform: uppercase;"><?=$reqKepalaPegawaiJabatanNama?></span><br>
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
	</div>
</body>
</html>
