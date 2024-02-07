<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("lib/MPDF60/mpdf.php");

$reqStatusBkdUptId= httpFilterGet("reqStatusBkdUptId");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqPeriode= httpFilterGet("reqPeriode");
$reqId= httpFilterGet("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqUrl= httpFilterGet("reqUrl");
$reqUrlList= httpFilterGet("reqUrlList");
$reqTipeId= httpFilterGet("reqTipeId");
$reqJabatanPilihan= httpFilterGet("reqJabatanPilihan");
$reqJabatanManual= httpFilterGet("reqJabatanManual");
$reqGantiBaris= httpFilterGet("reqGantiBaris");

//1=kepala;2=sekretaris;3=plt

$reqCss= httpFilterGet("reqCss");
$reqPegawaiId= httpFilterGet("reqPegawaiId");
$reqNamaJabatan= httpFilterGet("reqNamaJabatan");
$reqNamaPejabat= httpFilterGet("reqNamaPejabat");
$reqNipJabatan= httpFilterGet("reqNipJabatan");
//echo "asd";exit;
//$urlLink= "http://127.0.0.1/sertifikasi/bkkbn-admin/";
//$urlLink= "http://sertifikasi.bkkbn.go.id/admin/";
//echo $urlLink."json/rekap_ujian_hasil_kompetensi_template.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."&reqNamaJabatan=".urlencode($reqNamaJabatan)."&reqNamaPejabat=".urlencode($reqNamaPejabat)."&reqNipJabatan=".urlencode($reqNipJabatan);
//exit;
//$html= file_get_contents("".$urlLink."json/rekap_ujian_hasil_kompetensi_template.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."&reqNamaJabatan=".urlencode($reqNamaJabatan)."&reqNamaPejabat=".urlencode($reqNamaPejabat)."&reqNipJabatan=".urlencode($reqNipJabatan)."");

$baseurl= str_replace("\\", "", base_url());
if($reqPeriode == "")
{
	if($reqStatusBkdUptId == "")
	{
		// if($reqUrl == "cetak_disposisi")
		// {
		// 	// echo "a";exit;
		// 	$html= file_get_contents("app/loadUrl/export/".$reqUrl."?reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId);
		// 	$html= "http://siapasn.jombangkab.go.id/app/loadUrl/export/".$reqUrl."?reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId;
		// 	echo str_replace("\\", "", base_url())."-".$html;exit;
		// }
		// else
		// {
			$html= file_get_contents($baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan));
		// }
	}
	else
	$html= file_get_contents($baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan)."&reqStatusBkdUptId=".$reqStatusBkdUptId);
}
else
$html= file_get_contents($baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqPeriode=".$reqPeriode."&reqPegawaiId=".$reqPegawaiId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan));

// echo base_url()."-".$html;exit;

// echo $reqUrl;exit();
if($reqUrl == "cetak_disposisi")
{
	$mpdf = new mPDF('',    // mode - default ''
	 // 'FOLIO',    // format - A4, for example, default ''
	array(234, 160),
	 0,     // font size - default 0
	 'arial',    // default font family
	 0,    // margin_left
	 2,    // margin right
	 20,     // margin top
	 6,    // margin bottom
	 0,     // margin header
	 0,     // margin footer
	 'P');  // L - landscape, P - portrait
}
else
{
	$mpdf = new mPDF('',    // mode - default ''
	 'FOLIO',    // format - A4, for example, default ''
	 0,     // font size - default 0
	 '',    // default font family
	 5,    // margin_left
	 5,    // margin right
	 6,     // margin top
	 6,    // margin bottom
	 9,     // margin header
	 9,     // margin footer
	 'P');  // L - landscape, P - portrait
}
//$mpdf->SetDisplayMode('fullpage');

//$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('css/surat_rekomendasi.css');
$stylesheet = file_get_contents('css/'.$reqCss.'.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$footer= file_get_contents($baseurl."app/loadUrl/export/".$reqUrl."_footer?reqUrl=".$reqUrl."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqStatusBkdUptId=".$reqStatusBkdUptId);
// echo base_url()."app/loadUrl/export/".$reqUrl."_footer?reqUrl=".$reqUrl."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqStatusBkdUptId=".$reqStatusBkdUptId;exit;
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($html,2);

//$mpdf->setHeader();	// Clear headers before adding page
if($reqUrlList == ""){}
else
{
	// $mpdf->AddPage('L','','','','',5,5,5,5,8,2);
	$mpdf->AddPage('L','','','','',5,5,12,32,5,5);
	$reqUrl= $reqUrlList;
	$html= file_get_contents($baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan)."&reqStatusBkdUptId=".$reqStatusBkdUptId);
	//echo $html;exit;
	
	$mpdf->WriteHTML($html,2);
}

$mpdf->Output('cetakpersonal.pdf','I');
unlink($filename);
?>