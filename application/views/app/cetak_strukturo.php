<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
require_once 'lib/PHPWord/PHPWord.php';

$this->load->model('strukturorg');
$this->load->model('SatuanKerja');

$reqId= $this->input->get("reqId");
$reqJenis= $this->input->get("reqJenis");
$reqMode= $this->input->get("reqMode");
$reqJenisNama= setjenisinfo($reqJenis);

$tanggalHariIni= date("d-m-Y");
$reqSatuanKerjaId= $this->SATUAN_KERJA_ID;
$tempStatusKelompokPegawaiUsul= $this->STATUS_KELOMPOK_PEGAWAI_USUL;
$reqLoginLevel= $this->LOGIN_LEVEL;

$reqMode = 'update';

//ambil data satuan kerja induk
$statement= " AND A.SATUAN_KERJA_ID = ".$reqId."";
$setsat= new SatuanKerja();
$setsat->selectByParams(array(), -1, -1, "", $statement);
$setsat->firstRow();
//echo $set->query;exit;
$reqNamaInduk= $setsat->getField("NAMA");


//PENGECEKAN KECAMATAN, SMPN, PUSKESMAS, UPT, DLL untuk setup templatenya sama semua
$satkerinduk = $reqNamaInduk;
$cekkecamatan = 'Kecamatan';
$cekkelurahan = 'Kelurahan';
$cekupt = 'UPT ';
$cekwilker = 'Wilker';
$ceksmpn = 'SMPN';
$ceksdn = 'SDN ';
$cektkn = 'TK Negeri';
$cekpuskesmas = 'Puskesmas';
$hasilcekkecamatan = strpos(strtolower($satkerinduk), strtolower($cekkecamatan));
$hasilcekkelurahan = strpos(strtolower($satkerinduk), strtolower($cekkelurahan));
$hasilcekupt = strpos(strtolower($satkerinduk), strtolower($cekupt));
$hasilcekwilker = strpos(strtolower($satkerinduk), strtolower($cekwilker));
$hasilceksmpn = strpos(strtolower($satkerinduk), strtolower($ceksmpn));
$hasilceksdn = strpos(strtolower($satkerinduk), strtolower($ceksdn));
$hasilcektkn = strpos(strtolower($satkerinduk), strtolower($cektkn));
$hasilcekpuskesmas = strpos(strtolower($satkerinduk), strtolower($cekpuskesmas));



// The !== operator can also be used.  Using != would not work as expected
// because the position of 'a' is 0. The statement (0 != false) evaluates
// to false.
if ($hasilcekkecamatan !== false) {
	//jika benar
	$namatemplate = "kecamatan";
} elseif ($hasilcekkelurahan !== false) {
	//jika benar
	$namatemplate = "kelurahan";
} elseif ($hasilcekupt !== false) {
	//jika benar
	$namatemplate = "upt ";
} elseif ($hasilcekwilker !== false) {
	//jika benar
	$namatemplate = "wilker";
} elseif ($hasilceksmpn !== false) {
	//jika benar
	$namatemplate = "smpn";
} elseif ($hasilceksdn !== false) {
	//jika benar
	$namatemplate = "sdn ";
} elseif ($hasilceksdn !== false) {
	//jika benar
	$namatemplate = "tk negeri";
} elseif ($hasilceksdn !== false) {
	//jika benar
	$namatemplate = "puskesmas";
} else {
	$namatemplate = $reqId;
}



$statement= $reqId;
$statementnode= "";
$set_detil= new StrukturOrg();
$set_detil->selectByParamsSatkerId($statement, $statementnode);
// echo $set_detil->query;exit;
// echo $set_detil->errorMsg;exit;
$PHPWord = new PHPWord();
$document = $PHPWord->loadTemplate('templateworld/so/so'.$namatemplate.'.docx');
while($set_detil->nextRow())
{
	//setup untuk penamaan file dengan variabel $namasingkat
	$tempid = $set_detil->getField("SATKER_ID");
	if($reqId == $tempid)
	{
		$namasingkat = $set_detil->getField("NAMA_SINGKAT_SATKER");
	}

	//SETUP AWAL
	if(!empty($set_detil->getField("NAMA")))
	{
		$garistengah = ' - ';
		$koma = ', ';
		$jab = 'TMT JAB. ';
	} else {
		$garistengah = '';
		$koma = '';
		$jab = '';
	}

	switch ($namatemplate) {
		  case "kecamatan":
			$namaunor = $set_detil->getField("NAMA_JABATAN");
			$cekcamat = 'camat ';
			$ceksekretaris = 'sekretaris';
			$cekumpeg = 'kepegawaian';
			$cekkeuangan = 'keuangan';
			$cektatapem = 'pemerintahan';
			$cekpemberdayaan = 'pemberdayaan';
			$ceksosbud = 'sosial';
			$cektrantib = 'ketentraman';
			$hasilcekcamat = strpos(strtolower($namaunor), strtolower($cekcamat));
			$hasilceksekretaris = strpos(strtolower($namaunor), strtolower($ceksekretaris));
			$hasilcekumpeg = strpos(strtolower($namaunor), strtolower($cekumpeg));
			$hasilcekkeuangan = strpos(strtolower($namaunor), strtolower($cekkeuangan));
			$hasilcektatapem = strpos(strtolower($namaunor), strtolower($cektatapem));
			$hasilcekpemberdayaan = strpos(strtolower($namaunor), strtolower($cekpemberdayaan));
			$hasilceksosbud = strpos(strtolower($namaunor), strtolower($ceksosbud));
			$hasilcektrantib = strpos(strtolower($namaunor), strtolower($cektrantib));
			if ($hasilcekcamat !== false) {
				//jika benar
				$idunor = "1";
			} elseif ($hasilceksekretaris !== false) {
				//jika benar
				$idunor= "2";
			} elseif ($hasilcekumpeg !== false) {
				//jika benar
				$idunor = "3";
			} elseif ($hasilcekkeuangan !== false) {
				//jika benar
				$idunor = "4";
			} elseif ($hasilcektatapem !== false) {
				//jika benar
				$idunor = "5";
			} elseif ($hasilcekpemberdayaan !== false) {
				//jika benar
				$idunor = "6";
			} elseif ($hasilceksosbud !== false) {
				//jika benar
				$idunor = "7";
			} elseif ($hasilcektrantib !== false) {
				//jika benar
				$idunor = "8";
			} else {
			}

			$NIPTEMP = "nip_".$idunor;
			$NAMATEMP = "nama_".$idunor;
			$LAHIRTEMP = "lahir_".$idunor;
			$TLTEMP = "tl_".$idunor;
			$PANGKATTEMP = "pangkat_".$idunor;
			$TMTPANGTEMP = "tmtpang_".$idunor;
			$TMTJABTEMP = "tmtjab_".$idunor;

			$NAMASATKER = "namasatker";
			//set value judul SO
			$document->setValue($NAMASATKER, $reqNamaInduk);

			if(!empty($set_detil->getField("NAMA")))
			{
				$document->setValue($NAMATEMP, $set_detil->getField("NAMA"));
				$document->setValue($NIPTEMP, $set_detil->getField("NIP"));
				$document->setValue($LAHIRTEMP, $set_detil->getField("LAHIR").$koma);
				$document->setValue($TLTEMP, $set_detil->getField("TL"));
				$document->setValue($PANGKATTEMP, $set_detil->getField("PANG").$garistengah);
				$document->setValue($TMTPANGTEMP, $set_detil->getField("TMTPANG"));
				$document->setValue($TMTJABTEMP, $jab.$set_detil->getField("TMTJAB"));
			} else {
				$document->setValue($NAMATEMP, "");
				$document->setValue($NIPTEMP, "");
				$document->setValue($LAHIRTEMP, "");
				$document->setValue($TLTEMP, "");
				$document->setValue($PANGKATTEMP, "");
				$document->setValue($TMTPANGTEMP, "");
				$document->setValue($TMTJABTEMP, "");
			}
				
		  break;

		  default:


			$NIPTEMP = "nip_".$set_detil->getField("SATKER_ID");
			$NAMATEMP = "nama_".$set_detil->getField("SATKER_ID");
			$LAHIRTEMP = "lahir_".$set_detil->getField("SATKER_ID");
			$TLTEMP = "tl_".$set_detil->getField("SATKER_ID");
			$PANGKATTEMP = "pangkat_".$set_detil->getField("SATKER_ID");
			$TMTPANGTEMP = "tmtpang_".$set_detil->getField("SATKER_ID");
			$TMTJABTEMP = "tmtjab_".$set_detil->getField("SATKER_ID");

			if(!empty($set_detil->getField("NAMA")))
			{
				$document->setValue($NAMATEMP, $set_detil->getField("NAMA"));
				$document->setValue($NIPTEMP, $set_detil->getField("NIP"));
				$document->setValue($LAHIRTEMP, $set_detil->getField("LAHIR").$koma);
				$document->setValue($TLTEMP, $set_detil->getField("TL"));
				$document->setValue($PANGKATTEMP, $set_detil->getField("PANG").$garistengah);
				$document->setValue($TMTPANGTEMP, $set_detil->getField("TMTPANG"));
				$document->setValue($TMTJABTEMP, $jab.$set_detil->getField("TMTJAB"));
			} else {
				$document->setValue($NAMATEMP, "");
				$document->setValue($NIPTEMP, "");
				$document->setValue($LAHIRTEMP, "");
				$document->setValue($TLTEMP, "");
				$document->setValue($PANGKATTEMP, "");
				$document->setValue($TMTPANGTEMP, "");
				$document->setValue($TMTJABTEMP, "");
			}
		}




/*
	$NIPTEMP = "nip_".$set_detil->getField("SATKER_ID");
	$NAMATEMP = "nama_".$set_detil->getField("SATKER_ID");
	$LAHIRTEMP = "lahir_".$set_detil->getField("SATKER_ID");
	$TLTEMP = "tl_".$set_detil->getField("SATKER_ID");
	$PANGKATTEMP = "pangkat_".$set_detil->getField("SATKER_ID");
	$TMTPANGTEMP = "tmtpang_".$set_detil->getField("SATKER_ID");
	$TMTJABTEMP = "tmtjab_".$set_detil->getField("SATKER_ID");


	$document->setValue($NAMATEMP, $set_detil->getField("NAMA"));
	$document->setValue($NIPTEMP, $set_detil->getField("NIP"));
	$document->setValue($LAHIRTEMP, $set_detil->getField("LAHIR"));
	$document->setValue($TLTEMP, $set_detil->getField("TL"));
	$document->setValue($PANGKATTEMP, $set_detil->getField("PANG"));
	$document->setValue($TMTPANGTEMP, $set_detil->getField("TMTPANG"));
	$document->setValue($TMTJABTEMP, $set_detil->getField("TMTJAB"));
*/

}
unset($set_detil);

//echo $test;exit;
//echo $NAMATEMP;
//echo $set_detil->getField("NAMA");
//exit;


$document->save('templateworld/so/cetak/so_'.$namasingkat."_".$reqId.'.docx');

$down = 'templateworld/so/cetak/so_'.$namasingkat."_".$reqId.'.docx';
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($down));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($down));
readfile($down);
//unlink($down);
exit;

?>