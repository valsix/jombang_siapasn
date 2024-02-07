<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<style type="text/css">
		body{
			/*margin-top: 60px*/
			font-size: 14pt;
		}
		tr{
			vertical-align: text-top;
		}
		#container{
			/*background-color: rgba(255,255,200,0.9);*/
			/*background-color: #ECEFD1;*/
			border: 1px solid grey;
			padding: 80px 40px 50px 40px;
			margin: auto;
			width: 800px
		}
		.float-l {
			float: left;
		}
		.float-r {
			float: right;
		}
		.row {
			position: inherit;
			clear: both;
		}
		.content{
			margin-left: 120px;
		}
		.col-1 {width: 8.33%;}
		.col-2 {width: 16.66%;}
		.col-3 {width: 25%;}
		.col-4 {width: 33.33%;}
		.col-5 {width: 41.66%;}
		.col-6 {width: 50%;}
		.col-7 {width: 58.33%;}
		.col-8 {width: 66.66%;}
		.col-9 {width: 75%;}
		.col-10 {width: 83.33%;}
		.col-11 {width: 91.66%;}
		.col-12 {width: 100%;}
	</style>
</head>
<body>
	<div id="container">
		<div class="row">
			<div style="margin:0 0 20px 420px;">
				Jombang,  {tglsurat(format dd mmmm yyyy)}
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
							{NoSuratUsulDinas}
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
							a.n. {nama_PNS_tanpa_gelar}
						</td>
					</tr>
				</table>
			</div>
			<div class="col-6 float-r">
				<div class="row" style="margin-left:20px">
					Kepada
				</div>
				<div class="row">
					Yth. Bupati Jombang
				</div>
				<div class="row" style="margin-left:20px">
					<table>
						<td style="vertical-align: text-top;">Cq.</td>
						<td>
							Kepala Badan Kepegawaian Daerah, Pendidikan dan Pelatihan
							<br>
							di-
						</td>
					</table>
				</div>
				<div class="row" style="text-align:center">
					<b>JOMBANG</b>
				</div>
			</div>
		</div>
		<div class="row"></div>
		<div class="row content" style="margin-top:30px; text-align: justify;">
			&emsp;&emsp;&emsp;Bersama ini kami sampaikan permohonan ijin belajar dari {NamaDinasPengirim} sebagaimana sudah 
			memenuhi syarat dan ketentuan sesuai Perbup Nomor 9 tahun 2011  tentang Persyaratan Mengikuti 
			Pendidikan dan Pelatihan, Tugas Belajar dan Ijin Belajar Bagi Pegawai Negeri Sipil Daerah 
			Pemerintah Kabupaten Jombang dan ketentuan lainnya, atas nama :
		</div>
		<div class="row content" style="margin-top:30px; text-align:justify">
			<table>
				<tr>
					<td width="25%">Nama</td>
					<td width="5%">:</td>
					<td width="70%">{nama_lengkap_pns_dan_gelar}</td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td>{nip_baru}</td>
				</tr>
				<tr>
					<td>Pangkat/gol</td>
					<td>:</td>
					<td>{pangkat / gol ( Pembina Tk. I / IV/b )}</td>
				</tr>
				<tr>
					<td>Jabatan</td>
					<td>:</td>
					<td>{jabatan terakhir}</td>
				</tr>
				<tr>
					<td>Unit kerja/Instansi</td>
					<td>:</td>
					<td>{unit kerja lengkap sampai induk ( SDN Kepuhkembeng I – UPTD Pendidikan Kecamatan Peterongan – Dinas Pendidikan}</td>
				</tr>
				<tr>
					<td>Pendidikan terakhir</td>
					<td>:</td>
					<td>{pendidikan yang diakui / jika tidak ada pendidikan cpns nya}</td>
				</tr>
				<tr>
					<td>Ijin belajar pada</td>
					<td>:</td>
					<td>{namasekolah}</td>
				</tr>
				<tr>
					<td>Program Pendidikan</td>
					<td>:</td>
					<td>{fakultas (optional untuk SMP/SMA)}</td>
				</tr>
				<tr>
					<td>Program studi/jurusan</td>
					<td>:</td>
					<td>{jurusan (optional untuk SMP/SMA)}</td>
				</tr>
				<tr>
					<td>Tempat perkuliahan</td>
					<td>:</td>
					<td>{tempat sekolah}</td>
				</tr>
			</table>
		</div>
		<div class="row content" style="margin-top:30px">
			&emsp;&emsp;&emsp;Demikian untuk menjadikan maklum.
		</div>
		<div class="row col-6 float-r" style="margin-top:70px">
			<div  style="margin-bottom:100px">
				<span>{KEPALA xxxxx x x xxxxx  xxxxxx xxxxxx xxxx  xxxxxx xxx}</span><br>
				<span>KABUPATEN JOMBANG</span>
			</div>
			<div>
				<span><b><u>{Nama Kepala Dinas/Badan}</u></b></span><br>
				<span><i>{PangkatKepalaDinas}</i></span><br>
				<span>NIP. {NipKepalaDinas}</span>
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>
