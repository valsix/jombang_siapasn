<?
/*$file= "uploads/8300/dcFUAqOTMR.pdf";
$newfile= "uploadszip/coba/dcFUAqOTMR.pdf";
copy($file,$newfile);

$file= "uploads/8300/M14kkB90Hw.pdf";
$newfile= "uploadszip/coba/M14kkB90Hw.pdf";
copy($file,$newfile);
exit;*/

// coba merger
include_once("lib/PDFMerger/PDFMerger.php");

// $filelokasiformatbaru= $_SERVER['DOCUMENT_ROOT']."uploadszip/coba/AKTA_NIKAH_1963091219.pdf";
$filelokasiformatbaru= "uploadszip/coba/AKTA_NIKAH_1963091219.pdf";
// echo $filelokasiformatbaru;exit;
$filelokasi1= "uploadszip/coba/wLvvoe8ooO.pdf";
// $filelokasi2= "uploadszip/coba/vKiverfim3.pdf";

// $filelokasi1= "uploadszip/coba/M14kkB90Hw.pdf";
$filelokasi2= "uploadszip/coba/dcFUAqOTMR.pdf";

// if(file_exists($filelokasi2)) echo "ASd";exit;

$pdf = new PDFMerger;
$pdf->addPDF($filelokasi1, 'all'); // page 1 from first file.
$pdf->addPDF($filelokasi2, 'all'); // page 1 from first file.
// $pdf->merge('browser'); // send the file to the browser.
$pdf->merge('file', $filelokasiformatbaru);
echo "ok";
exit;
?>