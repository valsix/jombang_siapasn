<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");

$this->load->model('persuratan/CetakIjinBelajar');

$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");

$statement= " AND A.CETAK_IJIN_BELAJAR_ID = ".$reqId;
$set= new CetakIjinBelajar();
$set->selectByParams(array(), -1, -1, $statement);
$set->firstRow();
//echo $set->query;exit;
$reqSuratKeluarNomor= $set->getField('NOMOR_SURAT_KELUAR');
$reqSuratKeluarTanggal= getFormattedDateTimeCheck($set->getField('TANGGAL_SURAT_KELUAR'), false);
$reqTtdSatuanKerja= $set->getField('NAMA_JABATAN_TTD_SURAT_KELUAR');
$reqTtdNamaPejabat= $set->getField('NAMA_PEJABAT_TTD_SURAT_KELUAR');
$reqTtdPangkat= $set->getField('PANGKAT_PEJABAT_TTD_SURAT_KELUAR');
$reqTtdNip= $set->getField('NIP_PEJABAT_TTD_SURAT_KELUAR');
$reqSuratNomor= $set->getField('NOMOR_SURAT_DINAS');
$reqSuratTanggal= getFormattedDate($set->getField('TANGGAL_SURAT_DINAS'));
$reqPegawaiNama= $set->getField('PEGAWAI_NAMA');
$reqPegawaiNipBaru= $set->getField('PEGAWAI_NIP_BARU');
$reqPegawaiPangkatNama= $set->getField('PEGAWAI_PANGKAT');
$reqPegawaiTtl= $set->getField('PEGAWAI_TTL');
$reqPegawaiJabatanNama= $set->getField('PEGAWAI_JABATAN');
$reqPegawaiPendidikanNama= $set->getField('PEGAWAI_PENDIDIKAN');
$reqPegawaiPendidikanUsulanNama= $set->getField('PENDIDIKAN_NAMA');
$reqPegawaiPendidikanUsulanJurusan= $set->getField('PENDIDIKAN_JURUSAN');
$reqPegawaiPendidikanUsulanNamaSekolah= $set->getField('PENDIDIKAN_SEKOLAH');
$reqPegawaiSatuanKerjaNama= $set->getField('PEGAWAI_SATUAN_KERJA');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>" />
	<link rel="stylesheet" href="css/surat_pengantar.css" type="text/css">
</head>
<body>
	<div id="container">
		<div class="row content" style="text-align:center; margin-bottom:20px">
			<p style="font-size:16pt;margin: 0;">
				<strong>SURAT IZIN BELAJAR</strong>
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
                                <td>Peraturan Bupati Jombang Nomor 9 Tahun 2011 tentang Persyaratan Mengikuti Pendidikan dan Pelatihan (DIKLAT), Tugas Belajar dan Izin Belajar bagi Pegawai Negeri Sipil Daerah Pemerintah Kabupaten Jombang;</td>
                            </tr>
                            <tr>
                            	<td style="vertical-align:top">2.</td>
                                <td>Surat Kepala Dinas Pendidikan Kabupaten Jombang tanggal <?=$reqSuratTanggal?> nomor : <?=$reqSuratNomor?> perihal Permohonan Izin Belajar.</td>
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
								<td width="200px">Nama</td>
								<td width="30px">:</td>
								<td><strong><?=$reqPegawaiNama?></strong></td>
							</tr>
							<tr>
								<td>NIP</td>
								<td>:</td>
								<td><?=$reqPegawaiNipBaru?></td>
							</tr>
							<tr>
								<td>Pangkat/Gol</td>
								<td>:</td>
								<td><?=$reqPegawaiPangkatNama?></td>
							</tr>
							<tr>
								<td>Tempat, tanggal lahir</td>
								<td>:</td>
								<td><?=$reqPegawaiTtl?></td>
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
								<td style="vertical-align:top">Satuan Kerja</td>
								<td style="vertical-align:top">:</td>
								<td>
                                	<?=$reqPegawaiSatuanKerjaNama?>
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
					<td>
						<p>
							Mengikuti pendidikan Program <?=$reqPegawaiPendidikanUsulanNama?> program studi / jurusan Pendidikan <?=$reqPegawaiPendidikanUsulanJurusan?>  pada <?=$reqPegawaiPendidikanUsulanNamaSekolah?>, dengan ketentuan sebagai berikut : 
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
                                <td>Apabila dikemudian hari ternyata terdapat kekeliruan, maka surat izin belajar ini akan diubah dan diperbaiki sebagaimana mestinya.</td>
                            </tr>
                        </table>
					</td>
				</tr>
			</table>
		</div>

		<div class="row col-5 float-r" style="">
			<div  style="margin-bottom:80px">
				<table style="margin-bottom:10px">
					<tr>
						<td width="110px">Ditetapkan di</td>
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
					<span >a.n</span>
                    <span style="text-transform: uppercase;">BUPATI JOMBANG</span>
				</p>
				<?php /*?><span style="margin-left: -27px;">
					a.n. BUPATI JOMBANG<br>
				</span><?php */?>
				<span>
					<?php /*?><?=ucwordsPertama($reqTtdSatuanKerja)?><?php */?>
                    Kepala Badan Kepegawaian Daerah,<br/>Pendidikan Dan Pelatihan
				</span>
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
