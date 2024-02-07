<?
include_once("functions/string.func.php");
include_once("functions/default.func.php");
include_once("functions/date.func.php");
require_once 'lib/MPDF8/vendor/autoload.php';

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

if($reqUrl == "cetak_disposisi")
{
	$mpdf = new \Mpdf\Mpdf(
		[
			'tempDir' => '/tmp'
			, 'format' => [234, 160]
			, 'margin_left' => 0
			, 'margin_right' => 2
			, 'margin_top' => 20
			, 'margin_bottom' => 6
			, 'margin_header' => 0
			, 'margin_footer' => 0
			, 'orientation' => 'P'
		]
	);
}
else
{
	$mpdf = new \Mpdf\Mpdf(
		[
			'tempDir' => '/tmp'
			, 'format' => 'FOLIO'
			, 'margin_left' => 5
			, 'margin_right' => 5
			, 'margin_top' => 6
			, 'margin_bottom' => 6
			, 'margin_header' => 9
			, 'margin_footer' => 9
			, 'orientation' => 'P'
		]
	);
}

if($reqPeriode == "")
{
	if($reqStatusBkdUptId == "")
	{
		$arrparse= array("reqGantiBaris"=>$reqGantiBaris, "reqId"=>$reqId, "reqPegawaiPilihKepalaId"=>$reqPegawaiPilihKepalaId, "reqTipeId"=>$reqTipeId, "reqJabatanManual"=>urlencode($reqJabatanManual), "reqJabatanPilihan"=>urlencode($reqJabatanPilihan));
		$html= $this->load->view("export/".$reqUrl, $arrparse, true);
	}
	else
	{
		$arrparse= array("reqGantiBaris"=>$reqGantiBaris, "reqId"=>$reqId, "reqPegawaiPilihKepalaId"=>$reqPegawaiPilihKepalaId, "reqTipeId"=>$reqTipeId, "reqJabatanManual"=>urlencode($reqJabatanManual), "reqJabatanPilihan"=>urlencode($reqJabatanPilihan), "reqStatusBkdUptId"=>$reqStatusBkdUptId);
		$html= $this->load->view("export/".$reqUrl, $arrparse, true);
		// echo $html;exit;
	}
}
else
{
	$arrparse= array("reqGantiBaris"=>$reqGantiBaris, "reqPeriode"=>$reqPeriode, "reqPegawaiId"=>$reqPegawaiId, "reqPegawaiPilihKepalaId"=>$reqPegawaiPilihKepalaId, "reqTipeId"=>$reqTipeId, "reqJabatanManual"=>urlencode($reqJabatanManual), "reqJabatanPilihan"=>urlencode($reqJabatanPilihan));
	$html= $this->load->view("export/".$reqUrl, $arrparse, true);
}

// $stylesheet = file_get_contents('css/'.$reqCss.'.css');
// $mpdf->WriteHTML($stylesheet,1);

$arrparse= array("reqUrl"=>$reqUrl, "reqId"=>$reqId, "reqPegawaiPilihKepalaId"=>$reqPegawaiPilihKepalaId, "reqTipeId"=>$reqTipeId, "reqStatusBkdUptId"=>$reqStatusBkdUptId);
$footer= $this->load->view("export/".$reqUrl."_footer", $arrparse, true);
$mpdf->SetHTMLFooter($footer);

// $mpdf->WriteHTML($html, 2);
$mpdf->WriteHTML($html);

if($reqUrlList == ""){}
else
{
	$mpdf->AddPage('L','','','','',5,5,12,32,5,5);
	$reqUrl= $reqUrlList;

	$arrparse= array("reqGantiBaris"=>$reqGantiBaris, "reqId"=>$reqId, "reqPegawaiPilihKepalaId"=>$reqPegawaiPilihKepalaId, "reqTipeId"=>$reqTipeId, "reqJabatanManual"=>urlencode($reqJabatanManual), "reqJabatanPilihan"=>urlencode($reqJabatanPilihan), "reqStatusBkdUptId"=>$reqStatusBkdUptId);
	$html= $this->load->view("export/".$reqUrl, $arrparse, true);
	//echo $html;exit;
	
	$mpdf->WriteHTML($html);
}

$mpdf->Output('cetakpersonal.pdf','I');
?>