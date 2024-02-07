<?php
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
include_once("lib/MPDF60/mpdf.php");
// include_once("lib/mPDF-v6.1.0/mpdf.php");

// error_reporting(-1);
// ini_set('display_errors', 1); 

$reqStatusBkdUptId= $this->input->get("reqStatusBkdUptId");
$reqPegawaiId= $this->input->get("reqPegawaiId");
$reqPeriode= $this->input->get("reqPeriode");
$reqId= $this->input->get("reqId");
$reqPegawaiPilihKepalaId= $this->input->get("reqPegawaiPilihKepalaId");
$reqUrl= $this->input->get("reqUrl");
$reqUrlList= $this->input->get("reqUrlList");
$reqTipeId= $this->input->get("reqTipeId");
$reqJabatanPilihan= $this->input->get("reqJabatanPilihan");
$reqJabatanManual= $this->input->get("reqJabatanManual");
$reqGantiBaris= $this->input->get("reqGantiBaris");

//1=kepala;2=sekretaris;3=plt

$reqCss= $this->input->get("reqCss");
$reqNamaJabatan= $this->input->get("reqNamaJabatan");
$reqNamaPejabat= $this->input->get("reqNamaPejabat");
$reqNipJabatan= $this->input->get("reqNipJabatan");

//echo "asd";exit;
//$urlLink= "http://127.0.0.1/sertifikasi/bkkbn-admin/";
//$urlLink= "http://sertifikasi.bkkbn.go.id/admin/";
//echo $urlLink."json/rekap_ujian_hasil_kompetensi_template.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."&reqNamaJabatan=".urlencode($reqNamaJabatan)."&reqNamaPejabat=".urlencode($reqNamaPejabat)."&reqNipJabatan=".urlencode($reqNipJabatan);
//exit;
//$html= file_get_contents("".$urlLink."json/rekap_ujian_hasil_kompetensi_template.php?reqId=".$reqId."&reqPegawaiId=".$reqPegawaiId."&reqNamaJabatan=".urlencode($reqNamaJabatan)."&reqNamaPejabat=".urlencode($reqNamaPejabat)."&reqNipJabatan=".urlencode($reqNipJabatan)."");

$baseurl= str_replace("\\", "", base_url());
// echo $baseurl;exit;
$arrContextOptions=array(
  "ssl"=>array(
    "verify_peer"=>false,
    "verify_peer_name"=>false,
  ),
);
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

			$urllink= $baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan);
			// echo $urllink;exit;
			$html= file_get_contents($urllink, false, stream_context_create($arrContextOptions));

			// $data = array("reqGantiBaris" => $reqGantiBaris, "reqId" => $reqId, "reqPegawaiPilihKepalaId" => $reqPegawaiPilihKepalaId, "reqTipeId" => $reqTipeId, "reqJabatanManual" => urlencode($reqJabatanManual), "reqJabatanPilihan" => urlencode($reqJabatanPilihan));
			// // print_r($data);exit;
			// $urllink= "app/loadUrl/export/".$reqUrl;
			// $html= $this->load->view($urllink, $data, true);
			// echo $html;exit;
		// }
	}
	else
	{
		$urllink= $baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan)."&reqStatusBkdUptId=".$reqStatusBkdUptId;
		$html= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
	}
}
else
{
	$urllink= $baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqPeriode=".$reqPeriode."&reqPegawaiId=".$reqPegawaiId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan);
	$html= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
}
// echo $html;exit;

// echo $reqUrl;exit();
if($reqUrl == "cetak_disposisi")
{
	$mpdf = new mPDF('',    // mode - default ''
	 // 'FOLIO',    // format - A4, for example, default ''
	array(234, 160),
	 0,     // font size - default 0
	 'arial',    // default font family
	 0,    // margin_left
	 9,    // margin right
	 20,     // margin top
	 6,    // margin bottom
	 0,     // margin header
	 0,     // margin footer
	 'P');  // L - landscape, P - portrait
	
	/*$mpdf = new mPDF('',    // mode - default ''
	 // 'FOLIO',    // format - A4, for example, default ''
	array(234, 160),
	 0,     // font size - default 0
	 'arial',    // default font family
	 2,    // margin_left
	 2,    // margin right
	 43,     // margin top
	 6,    // margin bottom
	 0,     // margin header
	 0,     // margin footer
	 'P');  // L - landscape, P - portrait*/
}
else
{
	$mpdf = new mPDF('',    // mode - default ''
	'FOLIO',    // format - A4, for example, default ''
	 0,     // font size - default 0
	 'Arial',    // default font family
	 5,    // margin_left
	 5,    // margin right
	 6,     // margin top
	 6,    // margin bottom
	 9,     // margin header
	 9,     // margin footer
	 'P');  // L - landscape, P - portrait
}
// echo $html;exit;
//$mpdf->SetDisplayMode('fullpage');

//$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// echo $html;exit;

// LOAD a stylesheet
// $urllink= $baseurl.'css/'.$reqCss.'.css';
// $stylesheet= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
$urllink= 'css/'.$reqCss.'.css';
// // echo $urllink;exit;
$stylesheet= file_get_contents($urllink);
// echo $stylesheet;exit;
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
// echo $html;exit;
if($reqUrl !== "cetak_disposisi")
{
	$urllink= $baseurl."app/loadUrl/export/".$reqUrl."_footer?reqUrl=".$reqUrl."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqStatusBkdUptId=".$reqStatusBkdUptId;
	// echo $urllink;exit;
	$footer= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
	$mpdf->SetHTMLFooter($footer);
}
$mpdf->WriteHTML($html,2);
// echo $html;exit;

//$mpdf->setHeader();	// Clear headers before adding page
if($reqUrlList == ""){}
else
{
	// $mpdf->AddPage('L','','','','',5,5,5,5,8,2);
	$mpdf->AddPage('L','','','','',5,5,12,32,5,5);
	$reqUrl= $reqUrlList;
	$urllink= $baseurl."app/loadUrl/export/".$reqUrl."?reqGantiBaris=".$reqGantiBaris."&reqId=".$reqId."&reqPegawaiPilihKepalaId=".$reqPegawaiPilihKepalaId."&reqTipeId=".$reqTipeId."&reqJabatanManual=".urlencode($reqJabatanManual)."&reqJabatanPilihan=".urlencode($reqJabatanPilihan)."&reqStatusBkdUptId=".$reqStatusBkdUptId;
	// echo $urllink;exit;
	$html= file_get_contents($urllink, false, stream_context_create($arrContextOptions));
	$mpdf->WriteHTML($html,2);
	// echo $html;exit;
}

$mpdf->Output('cetakpersonal.pdf','I');
unlink($filename);
?>