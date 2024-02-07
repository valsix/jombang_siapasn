<?php
echo 'aaaa';
require_once('../src/Howtomakeaturn/PDFInfo/PDFInfo.php');
echo 'bbb';

use \Howtomakeaturn\PDFInfo\PDFInfo;
echo 'cccc';


$pdf = new PDFInfo(base_url().'uploads/3/726d2550c3899c6c5683e376d8fc34d7.pdf');
echo 'eee';

echo $pdf->title;
echo '<hr />';
echo $pdf->author;
echo '<hr />';
echo $pdf->creator;
echo '<hr />';
echo $pdf->producer;
echo '<hr />';
echo $pdf->creationDate;
echo '<hr />';
echo $pdf->modDate;
echo '<hr />';
echo $pdf->tagged;
echo '<hr />';
echo $pdf->form;
echo '<hr />';
echo $pdf->pages;
echo '<hr />';
echo $pdf->encrypted;
echo '<hr />';
echo $pdf->pageSize;
echo '<hr />';
echo $pdf->fileSize;
echo '<hr />';
echo $pdf->optimized;
echo '<hr />';
echo $pdf->PDFVersion;
echo '<hr />';
