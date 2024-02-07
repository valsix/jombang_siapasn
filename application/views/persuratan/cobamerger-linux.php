<?
function cekversi($vdetil, $vfpd)
{
  $vexe= explode(".", $vfpd);

  $namafile= $vexe[0];
  $exefile= $vexe[1];

  $srcfile= $vdetil.$namafile.".".$exefile;
  $srcfile_new= $vdetil.$namafile."-versi.".$exefile;

  $filepdf = fopen($srcfile,"r");
  if($filepdf) {
    $line_first = fgets($filepdf);
    fclose($filepdf);
  }
  else{
    echo "error opening the file.";
  }

  preg_match_all('!\d+!', $line_first, $matches);
         
  // save that number in a variable
  $pdfversion = implode('.', $matches[0]);

  if($pdfversion >= 1.3)
  {
    // kalau ada file versi maka tidak perlu buat kembali
    if(file_exists($srcfile_new))
    {
      return $srcfile_new;
    }

    // echo $srcfile_new."<br>";
    // echo $srcfile."<br>";
    // exit;

    shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="'.$srcfile_new.'" "'.$srcfile.'"');
    $validasifile= file($srcfile_new);
    $endfile= trim($validasifile[count($validasifile) - 1]);
    $nfile="%%EOF";

    // kalau tidak cocok, maka kembalikan file asli
    if ($endfile === $nfile) 
    {
      return $srcfile_new;
      // echo "good";
    }
    else
    {
      return $srcfile;
      // echo "corrupted";
    }
  }
  else
  {
    return $srcfile;
  }
}

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

// $filelokasi1= "uploadszip/coba/wLvvoe8ooO.pdf";
// $filelokasi2= "uploadszip/coba/vKiverfim3.pdf";
// $filelokasi1= "uploadszip/coba/wLvvoe8ooO-versi.pdf";
$filelokasi2= "uploadszip/coba/vKiverfim3-versi.pdf";

// $filelokasi1= "uploadszip/coba/M14kkB90Hw.pdf";
// $filelokasi2= "uploadszip/coba/dcFUAqOTMR.pdf";
$filelokasi1= "uploadszip/coba/M14kkB90Hw-versi.pdf";
// $filelokasi2= "uploadszip/coba/dcFUAqOTMR-versi.pdf";

$vdetil= "uploadszip/coba/";
// $vfpd= "wLvvoe8ooO.pdf";
// $vfpd= "vKiverfim3.pdf";

// $vfpd= "M14kkB90Hw.pdf";
// $vfpd= "dcFUAqOTMR.pdf";
// $vfpd= cekversi($vdetil, $vfpd);
// echo $vfpd;exit;
// if(file_exists($filelokasi2)) echo "ASd";exit;

$pdf = new PDFMerger;
$pdf->addPDF($filelokasi1, 'all'); // page 1 from first file.
$pdf->addPDF($filelokasi2, 'all'); // page 1 from first file.
// $pdf->merge('browser'); // send the file to the browser.
$pdf->merge('file', $filelokasiformatbaru);
echo "ok";
exit;
?>