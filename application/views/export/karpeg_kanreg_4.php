<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

// $this->load->model('persuratan/SuratMasukUpt';
// $this->load->model('persuratan/SuratMasukPegawai';

// $reqId= $this->input->get("reqId");

// $statement= " AND SMP.SURAT_MASUK_UPT_ID = ".$reqId;
// $set= new SuratMasukUpt();
// $set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
// $set->firstRow();
$reqJumlahUsulanPegawai= 1;

//AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) 

// $reqSuratSatuanKerjaAsalId= 'SATUAN_KERJA_ASAL_ID';
// $reqSuratSatuanKerjaPengirimNama= 'SATUAN_KERJA_PENGIRIM';
$reqSuratSatuanKerjaPengirimKepala= 'Kepala Badan Kepegawaian Daerah, Pendidikan dan Pelatihan';
$reqSuratNomor= '123/AD/1212/sas';
$reqSuratTanggal= '01 Maret 2018';
$reqSuratKepada= 'Kepala Kantor Regional II BKN';
// $reqSurat= 'NAMA_LENGKAP';

// $reqPegawaiSatuanKerja= 'SATUAN_KERJA_DETIL';
$reqPegawaiNama= 'Alan Wlker';
$reqPegawaiNamaLengkap= 'Alan Wlker Marsmellow';
$reqPegawaiNipBaru= '1234567890345678';
$reqPegawaiPangkatNama= 'Pembina Tk. I';
$reqPegawaiPangkatKode= 'IV/b';
$reqPegawaiJabatanNama= 'Kepala Dinas Perindustrian dan Perdagangan Dinas Perindustrian dan Perdagangan Dinas Perindustrian dan Perdagangan ';
// $reqPegawaiPendidikanNama= 'PENDIDIKAN_NAMA';
// $reqPegawaiPendidikanJurusan= 'JURUSAN';
// $reqPegawaiPendidikanUsulanNama= 'PENDIDIKAN_NAMA_US';
// $reqPegawaiPendidikanUsulanFakultas= 'NAMA_FAKULTAS_US';
// $reqPegawaiPendidikanUsulanJurusan= 'JURUSAN_US';
// $reqPegawaiPendidikanUsulanNamaSekolah= 'NAMA_SEKOLAH_US';
// $reqPegawaiPendidikanUsulanTempat= 'TEMPAT_US';

// $set= new SuratMasukPegawai();
// $statement= " AND A.SATUAN_KERJA_ID = ".$reqSuratSatuanKerjaAsalId;
// $set->selectByParamsKepala(array(), -1, -1, $statement, "ORDER BY JAB_RIW.TIPE_PEGAWAI_ID ASC, JAB_RIW.TMT_JABATAN DESC");
// $set->firstRow();
//echo $set->query;exit;

// $reqKepalaSatuanKerjaKepala= 'SATUAN_KERJA_KEPALA';
// $reqKepalaSatuanKerjaInduk= 'SATUAN_KERJA_INDUK';
$reqKepalaPegawaiNama= 'Dr. Marsmellow, M.Kom';
$reqKepalaPegawaiNipBaru= '1232348765434512';
$reqKepalaPegawaiPangkatNama= 'Kepala daerah jombang ';
// $reqKepalaPegawaiPangkatKode= 'PANGKAT_RIWAYAT_KODE';
// $reqKepalaPegawaiJabatanNama= 'JABATAN_RIWAYAT_NAMA';
// $reqPermohonan = 'Kehilangan';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/karpeg.css" type="text/css">
</head>
<body>
	<div id="container">
		<div class="row">
			<div style="margin:0 0 20px 400px;">
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
                        <td style="width:25%; padding-left:31px; ">
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
							Surabaya
						</td>
					</tr>
					<tr>
						<td>
							Hal
						</td>
						<td>:</td>
						<td>
							Permohonanan Kartu 
						</td>
                        <td style="padding-left:31px; ">
							di-
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td>
							Pegawai (KARPEG)  
						</td>
                        <td style="padding-left:61px; ">
							SURABAYA
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
			<?php /*?><div class="col-6 float-r">
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
					
				</div><?php */?>
		</div>
		<div class="row"></div>
		<div class="row content" style="margin-top:30px; text-align: justify;">
			&emsp;&emsp;&emsp;Bersama ini kami sampaikan berkas permohonan Kartu Pegawai Negeri Sipil (KARPEG)  di lingkup Pemerintah Kabupaten Jombang :
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
			beserta <?=$reqJumlahUsulanPegawai?> (<?=kekata($reqJumlahUsulanPegawai)?>) orang lainya sebagaimana daftar nama terlampir.<br>
			Sebagai bahan kelengkapan dengan ini dilampirkan berkas dari masing-masing pegawai sebagai berikut :
		</div>
		<div class="row content" style="text-align: justify;">
			<table>
				<tr>
					<td>1.</td>
					<td>Foto Copy SK CPNS</td>
				</tr>
				<tr>
					<td>2.</td>
					<td>Foto Copy SK PNS</td>
				</tr>
				<tr>
					<td>3.</td>
					<td>Foto Copy Surat Tanda Tamat Pendidikan dan Pelatihan (STTPL)</td>
				</tr>
				<tr>
					<td>4.</td>
					<td>Pas Foto ukuran 4 X 3 sebanyak  2 lbr</td>
				</tr>
			</table>
		</div>
		<div class="row content" style="">
			&emsp;&emsp;&emsp;Demikian atas bantuan  dan kerjasamanya disampaikan terima kasih.
		</div>
		<div class="row col-6 float-r" style="margin-top:50px">
			<div  style="margin-bottom:100px">
				<div style="margin-left: -28px"><b>an.	BUPATI JOMBANG</b></div>
				<span><?=$reqSuratSatuanKerjaPengirimKepala?></span><br>
			</div>
			<div>
				<span style="text-transform: uppercase;"><b><u><?=$reqKepalaPegawaiNama?></u></b></span><br>
				<span style="text-transform: uppercase;"><?=$reqKepalaPegawaiPangkatNama?></span><br>
				<span>NIP. <?=$reqKepalaPegawaiNipBaru?></span>
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
