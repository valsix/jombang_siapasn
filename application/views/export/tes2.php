<?
require_once 'lib/MPDF8/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);

$html='<html lang="en">
<head>
	<base href="http://103.142.14.14/" />
	<link rel="stylesheet" href="css/karpeg.css" type="text/css">
</head>
<body>
	<div id="container">
    	        <div class="row">
			<div style="margin:100px 0 20px 370px;">
				Jombang, 08 Mei 2020			</div>
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
							900/769/415.44/2020						</td>
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
							Yth. Kepala Badan Kepegawaian Daerah,						</td>
					</tr>
					<tr>
						<td>
							Lampiran
						</td>
						<td>:</td>
						<td>
							1 ( satu) berkas
						</td>
                        <td style="padding-left:31px; ">
                        	 Pendidikan dan Pelatihan						</td>
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
							a.n. MUHAMMAD NASHRULLOH													</td>
                        <td style="padding-left:31px; ">
                        								JOMBANG
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
        		<div class="row"></div>
		<div class="row content" style="margin-top:30px; text-align: justify;"></div>
		<div class="row content" style="text-align: justify;">
			<table style="text-align: justify; padding-left: -2px;">
				<tr>
					<!-- pemberian pensiun bagi PNS/pensiun janda/duda PNS -->
					<td>1.</td>
					<td>
						Bersama ini kami sampaikan dengan hormat usul kenaikan pangkat Pegawai Negeri Sipil di lingkungan Badan Pengelolaan Keuangan dan Aset Daerah sebagaimana terlampir.
						Sesuai ketentuan dalam Peraturan Pemerintah Nomor 99 Tahun 2000 jo Peraturan Pemerintah Nomor 12 Tahun 2002, yang bersangkutan telah memenuhi syarat untuk dapat dipertimbangkan kenaikan pangkatnya setingkat lebih lanjut.</td>
				</tr>
				<tr>
					<!-- pemberian pensiun PNS/pensiun janda/duda PNS -->
					<td>2.</td>
					<td>
						Bersama ini pula disampaikan bahwa berkas pendukung yang dikirimkan secara online melalui SIAP ASN BKDPP Kabupaten Jombang adalah benar adanya dan merupakan tanggung jawab saya selaku Kepala Badan Pengelolaan Keuangan dan Aset Daerah.
				</tr>
				<tr>
					<td>3.</td>
					<td>Demikian atas perhatian dan perkenannya, kami ucapkan terima kasih.</td>
				</tr>
			</table>
		</div>
		<div class="row col-6 float-r" style="margin-top:50px">
						<div style="margin-bottom:100px">
				<p style="margin-left:-45px; margin-bottom: 0px; ">
											<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
											<span style="text-transform: uppercase;">Kepala Badan Pengelolaan Keuangan dan</span>
				</p>
								<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px; ">
				<span style="text-transform: uppercase; "> Aset Daerah</span>
				<br/>
				
								<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span style="">KABUPATEN JOMBANG</span>
			</div>
			<!-- <div  style="margin-bottom:100px">
				<span>Kepala Badan Pengelolaan Keuangan dan Aset Daerah</span><br>
			</div> -->
						<div>
				<p style="margin-left:-20px; margin-top:0px; margin-bottom: 0px;">
				<span><b><u>MUHAMMAD NASHRULLOH, SE., M.Si</u></b></span><br>
				<span>Pembina Tk. I</span><br>
				<span>NIP. 196802021990031013</span>
			</div>
		</div>
		<div class="row"></div>
	</div>
</body>
</html>';
echo $html;exit;

$mpdf->WriteHTML('<h1>Hello world!</h1>');
$mpdf->Output('cetakpersonal.pdf','I');
?>