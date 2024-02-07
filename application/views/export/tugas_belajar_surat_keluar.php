<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/SuratMasukBkd');
$this->load->model('persuratan/SuratMasukPegawai');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");

$statement= " AND SMP.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
$set= new SuratMasukBkd();
$set->selectByParamsCetakPengantarSatuOrang(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqJumlahUsulanPegawai= $set->getField('JUMLAH_USULAN_PEGAWAI');
$reqSuratKeluarNomor= $set->getField('NOMOR_SURAT_KELUAR');
$reqSuratKeluarTanggal= getFormattedDateTimeCheck($set->getField('TANGGAL_SURAT_KELUAR'), false);

if($reqSuratKeluarTanggal == "")
{
	$reqSuratKeluarTanggal= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".getNameMonth(date("m"))." ".date("Y");
}

$reqTtdSatuanKerja= $set->getField('TTD_SATUAN_KERJA');
$reqTtdNamaPejabat= $set->getField('TTD_NAMA_PEJABAT');
$reqTtdPangkat= $set->getField('TTD_PANGKAT');
$reqTtdNip= $set->getField('TTD_NIP');

//AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) 
$reqSuratSatuanKerjaAsalId= $set->getField('SATUAN_KERJA_ASAL_ID');
$reqSuratSatuanKerjaPengirimNama= $set->getField('SATUAN_KERJA_PENGIRIM');
$reqSuratSatuanKerjaPengirimKepala= ucwords($set->getField('SATUAN_KERJA_PENGIRIM_KEPALA'));
$reqSuratNomor= $set->getField('NOMOR');
$reqSuratTanggal= getFormattedDate($set->getField('TANGGAL'));
$reqSuratPerihal = $set->getField('PERIHAL');
$reqSuratKepada= $set->getField('KEPADA');
$reqSurat= $set->getField('NAMA_LENGKAP');
//echo $reqSuratKepada;exit;
$reqPegawaiSatuanKerja= $set->getField('SATUAN_KERJA_DETIL');
$reqPegawaiNama= $set->getField('NAMA');
$reqPegawaiNamaLengkap= $set->getField('NAMA_LENGKAP');
$reqPegawaiNipBaru= $set->getField('NIP_BARU');
$reqPegawaiTempatLahir= ucwordsPertama($set->getField('TEMPAT_LAHIR'));
$reqPegawaiTanggalLahir= dateToPageCheck($set->getField('TANGGAL_LAHIR'));
$reqPegawaiPangkatNama= $set->getField('PANGKAT_RIWAYAT_NAMA');
$reqPegawaiPangkatKode= $set->getField('PANGKAT_RIWAYAT_KODE');
$reqPegawaiJabatanNama= $set->getField('JABATAN_RIWAYAT_NAMA');
$reqPegawaiPendidikanNama= $set->getField('PENDIDIKAN_NAMA');
$reqPegawaiPendidikanJurusan= $set->getField('JURUSAN');
$reqPegawaiPendidikanUsulanNama= ucwordsPertama($set->getField('PENDIDIKAN_NAMA_US'));
$reqPegawaiPendidikanIdUsulan= $set->getField('PENDIDIKAN_ID_US');
$reqPegawaiPendidikanUsulanFakultas= ucwordsPertama($set->getField('NAMA_FAKULTAS_US'));
$reqPegawaiPendidikanUsulanJurusan= ucwordsPertama($set->getField('JURUSAN_US'));
$reqPegawaiPendidikanUsulanNamaSekolah= ucwordsPertama($set->getField('NAMA_SEKOLAH_US'));
//$reqPegawaiPendidikanUsulanNamaSekolah = $reqPegawaiPendidikanUsulanNamaSekolah;
$reqPegawaiPendidikanUsulanTempat= ucwordsPertama($set->getField('TEMPAT_US'));
$reqPegawaiSatuanKerjaNama= $set->getField('SATUAN_KERJA_PEGAWAI_SURAT_KELUAR');

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

$reqPegawaiPendidikanUsulanJurusan= str_replace("D-1 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-2 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-3 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-4 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-I ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-II ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-III ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("D-IV ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("S-1 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("S-2 ", '', $reqPegawaiPendidikanUsulanJurusan);
$reqPegawaiPendidikanUsulanJurusan= str_replace("S-3 ", '', $reqPegawaiPendidikanUsulanJurusan);


	if ($reqPegawaiPendidikanIdUsulan == 9){ //D-III
		$reqPegawaiDataPendidikanUsul = "Program Diploma Tiga (D-III) program studi / jurusan".$reqPegawaiPendidikanUsulanJurusan." pada ".$reqPegawaiPendidikanUsulanNamaSekolah." ".$reqPegawaiPendidikanUsulanTempat;

	} elseif ($reqPegawaiPendidikanIdUsulan == 10) {//D-IV
		$reqPegawaiDataPendidikanUsul = "Program Diploma IV program studi / jurusan ".$reqPegawaiPendidikanUsulanJurusan." pada ".$reqPegawaiPendidikanUsulanNamaSekolah." ".$reqPegawaiPendidikanUsulanTempat;

	} elseif ($reqPegawaiPendidikanIdUsulan == 11) {//S-1

		$reqPegawaiDataPendidikanUsul = "Program Sarjana (S-1) program studi ".$reqPegawaiPendidikanUsulanJurusan." Fakultas ".$reqPegawaiPendidikanUsulanFakultas." pada ".$reqPegawaiPendidikanUsulanNamaSekolah." ".$reqPegawaiPendidikanUsulanTempat;

	} elseif ($reqPegawaiPendidikanIdUsulan == 12) { //S-2
		$reqPegawaiDataPendidikanUsul = "program studi ".$reqPegawaiPendidikanUsulanJurusan." Program Pascasarjana pada ".$reqPegawaiPendidikanUsulanNamaSekolah." ".$reqPegawaiPendidikanUsulanTempat;

	} elseif ($reqPegawaiPendidikanIdUsulan == 13) { //S-3
		$reqPegawaiDataPendidikanUsul = "Program Doktor (S3) program studi / jurusan ".$reqPegawaiPendidikanUsulanJurusan." pada ".$reqPegawaiPendidikanUsulanNamaSekolah." ".$reqPegawaiPendidikanUsulanTempat;

	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
</head>
<body>
	<div id="containerijinbelajar">
		<div class="row content" style="text-align:center; margin-bottom:20px">
			<p style="font-size:14pt;margin: 0;">
				<strong>SURAT TUGAS BELAJAR</strong>
			</p>
            <p style="margin: 0;">Nomor : <?=$reqSuratKeluarNomor?></p>
		</div>
		<div class="row content">
			<table>
				<tr>
					<td width="80px" style="vertical-align:top">Dasar</td>
					<td width="10px" style="vertical-align:top">:</td>
					<td>
                    	<table>
                        	<tr>
                            	<td style="vertical-align:top">1.</td>
                                <td style="text-align: justify">Peraturan Bupati Jombang Nomor 9 Tahun 2011 tentang Persyaratan Mengikuti Pendidikan dan Pelatihan (DIKLAT), Tugas Belajar dan Izin Belajar bagi Pegawai Negeri Sipil Daerah Pemerintah Kabupaten Jombang;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">2.</td>
                                <td style="text-align: justify">Surat <?=$reqSuratSatuanKerjaPengirimKepala ?> Kabupaten Jombang Nomor : <?=$reqSuratNomor?> tanggal <?=$reqSuratTanggal?> perihal <?=$reqSuratPerihal?>.</td>
                            </tr>
                        </table>
					</td>
				</tr>
			</table>
		</div>
		
        <div class="row content" style="text-align:center; margin-top:10px; margin-bottom:10px">
			<p style="font-size:14pt;margin: 0;">
				<strong>MEMBERIKAN IZIN</strong>
			</p>
		</div>
        
		<div class="row content">
			<table>
				<tr>
					<td width="80px" style="vertical-align:top">Kepada</td>
					<td width="30px" style="vertical-align:top">:</td>
					<td>
						<table>
							<tr>
								<td width="110px">Nama</td>
								<td width="30px">:</td>
								<td><strong><?=$reqPegawaiNamaLengkap?></strong></td>
							</tr>
							<tr>
								<td>NIP</td>
								<td>:</td>
								<td><?=$reqPegawaiNipBaru?></td>
							</tr>
							<tr>
								<td>Pangkat/Gol</td>
								<td>:</td>
								<td><?=$reqPegawaiPangkatNama?> - (<?=$reqPegawaiPangkatKode?>)</td>
							</tr>
							<tr>
								<td>Tempat, TTL</td>
								<td>:</td>
								<td><?=$reqPegawaiTempatLahir?>,  <?=$reqPegawaiTanggalLahir?></td>
							</tr>
							<tr>
								<td>Pendidikan</td>
								<td>:</td>
								<td><?=$reqPegawaiPendidikanNama?></td>
							</tr>
							<tr>
								<td>Jabatan</td>
								<td>:</td>
								<td><?=$reqPegawaiJabatanNama?></td>
							</tr>
							<tr>
								<td valign="top">Satuan Kerja</td>
								<td valign="top">:</td>
								<td valign="top" style="text-align: justify">
                                	<?=$reqPegawaiSatuanKerjaNama?>
									<?php /*?>SDN Pengaron Mojowarno <br>
									Dinas Pendidikan Kabupaten Jombang <?php */?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>

		<div class="row content">
			<table>
				<tr>
					<td width="80px" style="vertical-align:top">Untuk</td>
					<td width="30px" style="vertical-align:top">:</td>
					<td style="text-align: justify">
						<p>
							Mengikuti pendidikan <?=$reqPegawaiDataPendidikanUsul?>, dengan ketentuan sebagai berikut : 
						</p>
                        <table style="width:100%">
                        	<tr>
                            	<td style="vertical-align:top">1.</td>
                                <td>Kegiatan perkuliahan dilaksanakan di luar jam kerja;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">2.</td>
                                <td>Kegiatan perkuliahan tidak mengganggu kelancaran tugas kedinasan sehari-hari;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">3.</td>
                                <td>Seluruh biaya pendidikan ditanggung sepenuhnya oleh PNS yang bersangkutan;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">4.</td>
                                <td>Tidak menuntut apabila dikemudian hari pendidikan yang ditempuh dinyatakan tidak memiliki dampak kepegawaian;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">5.</td>
                                <td>Setelah menyelesaikan pendidikan / lulus wajib melaporkan kelulusannya ke Badan Kepegawaian Daerah, Pendidikan dan Pelatihan Kabupaten Jombang;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">6.</td>
                                <td>Apabila dikemudian hari ternyata terdapat kekeliruan, maka surat izin belajar ini akan diubah dan diperbaiki sebagaimana mestinya.</td>
                            </tr>
                        </table>
					</td>
				</tr>
			</table>
		</div>

		<div class="row col-5 float-r" style="">
			<div  style="margin-bottom:60px">
				<table style="margin-bottom:10px">
					<tr>
						<td width="100px">Ditetapkan di</td>
						<td width="10px">:</td>
						<td>Jombang </td>
					</tr>
					<tr>
						<td>Pada tanggal</td>
						<td>:</td>
						<td><?=$reqSuratKeluarTanggal?></td>
					</tr>
				</table>
                <p style="margin-left:-25px; margin-bottom: 0px;">
					<span ><b>a.n</b></span>
                    <span style="text-transform: uppercase;"><b>BUPATI JOMBANG</b></span>
				</p>
				<span>
                    Kepala Badan Kepegawaian Daerah,<br/>Pendidikan Dan Pelatihan
				</span>
                
				<?php /*?><span style="margin-left: -27px;">
					a.n. BUPATI JOMBANG<br>
				</span>
				<span>
					<?=ucwordsPertama($reqTtdSatuanKerja)?>
				</span><?php */?>
                
			</div>
			<div>
				<span>
					<b>
						<u><?=$reqTtdNamaPejabat?></u>
					</b>
				</span>
				<br>
				<span><?=$reqTtdPangkat?></span>
				<br>
				<span>Nip. <?=$reqTtdNip?></span>
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
