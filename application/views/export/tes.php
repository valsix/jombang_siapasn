<?
require_once 'lib/MPDF8/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);
$mpdf->WriteHTML('<h1>Hello world!</h1>');
$mpdf->Output('cetakpersonal.pdf','I');
?>