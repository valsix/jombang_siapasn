<?
// phpinfo();exit;
include_once("functions/string.func.php");
include_once("functions/date.func.php");
// require_once("lib/mergepdf/MergePdf.class.php");
include_once("lib/PDFMerger/PDFMerger.php");

$this->load->library('globalfilepegawai');

$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
$this->load->model('persuratan/CetakIjinBelajar');
$this->load->model('persuratan/Pensiun');

ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);
// ini_set('max_input_time', -1);

$reqUserLoginId= $this->USER_LOGIN_ID;

$reqDownload= $this->input->get("reqDownload");
$reqDownloadName= $this->input->get("reqDownloadName");
$reqMode= $this->input->get("reqMode");
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

// untuk kondisi file
$vfpeg= new globalfilepegawai();

$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
$persyaratanusulan= $vfpeg->persyaratanusulan($arrparam);
// print_r($persyaratanusulan);exit;

// default value ambil dari surat keluar
$tempNomorSuratKeluar= $persyaratanusulan["SURAT_KELUAR_NOMOR"];
$tempTanggalSuratKeluar= $persyaratanusulan["SURAT_KELUAR_TANGGAL"];
// kalau data belum ada ambil dari usulan
if(empty($tempNomorSuratKeluar))
{
  $tempNomorSuratKeluar= $persyaratanusulan["SEMENTARA_NOMOR"];
  $tempTanggalSuratKeluar= $persyaratanusulan["SEMENTARA_TANGGAL"];
}
// echo $tempNomorSuratKeluar;exit;

if($reqDownload == "1")
{
  /*$set= new SuratMasukPegawaiCheck();
  $set->selectByParamsUsulan(array(), -1,-1, $statement);
  $set->firstRow();
  $tempNomorSuratKeluar= $set->getField("NOMOR");

  if(empty($tempNomorSuratKeluar))
    $tempNomorSuratKeluar= $set->getField("ID_SEMENTARA");

  $tempTanggalSuratKeluar= $set->getField("TANGGAL");
  $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";*/
}

$arrparam= ["reqId"=>$reqId, "reqMode"=>$reqMode];
$arrpegawai= $vfpeg->persyaratanvalid($arrparam);
// print_r($arrpegawai);exit;

$indexZip= 0;
$files_to_zip= [];
foreach ($arrpegawai as $keypegawai => $valuepegawai)
{
  // print_r($valuepegawai);exit;
  $pegawaiinfoid= $valuepegawai["PEGAWAI_ID"];
  $tempNipBaru= $valuepegawai["PEGAWAI_NIP_BARU"];
  $tempNamaLengkap= $valuepegawai["PEGAWAI_NAMA_LENGKAP"];

  $buatfolderzip= $tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".str_replace("/","_",$tempNamaLengkap)."-".$tempNipBaru;
  // echo $buatfolderzip;exit;

  $direktori= "uploadszip/".$buatfolderzip."/";
  $direktoriFile= "uploadszip/".$buatfolderzip."/";

  if(file_exists($direktoriFile)){}
  else
  {
    makedirs($direktoriFile);
  }

  $arrlistpilihfilepegawai= $valuepegawai["arrlistpilihfilepegawai"];
  // print_r($arrlistpilihfilepegawai);exit;

  foreach ($arrlistpilihfilepegawai as $keyfile => $valuefile)
  {
    // print_r($valuefile);exit;

    $tempPath= $valuefile["vurl"];
    $tempNewPath= basename($tempPath);
    // echo $tempNewPath;exit;

    // set ghostscript
    $gsfilepdf= $vfpeg->gsfilepdf($direktori, $tempNewPath);
    // echo basename($gsfilepdf);exit;
    $tempNewPath= basename($gsfilepdf);

    // echo $tempNewPath;exit;
    $tempFormatBkn= $valuefile["FORMAT_BKN"];

    if(!file_exists($tempPath) || empty($tempFormatBkn))
      continue;

    $file= $tempPath;
    $linkfilePath= $direktori;
    $newfile= $direktori.$tempNewPath;
    // echo $newfile;exit;

    if(file_exists($newfile))
    {
      $files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
      $files_to_zip[$indexZip]["LINK"]= $newfile;
      $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
      $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
      $files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
      $indexZip++;
    }
    else
    {
      // echo $file;exit();
      // echo $newfile;exit;
      if(!copy($file,$newfile)){}
      else
      {
        $files_to_zip[$indexZip]["PEGAWAI_ID"]= $pegawaiinfoid;
        $files_to_zip[$indexZip]["LINK"]= $newfile;
        $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
        $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
        $files_to_zip[$indexZip]["FORMAT_BKN_PEGAWAI"]= $tempFormatBkn."-".$pegawaiinfoid;
        $indexZip++;
      }
    }
  }
}
// print_r($files_to_zip);exit;

$merfefile= [];
foreach ($files_to_zip as $keyfile => $valuefile)
{
  // print_r($valuefile);exit;
  $pegawaiinfoid= $valuefile["PEGAWAI_ID"];
  $varlink= $valuefile["LINK"];
  $infocarikey= $valuefile["FORMAT_BKN"]."-".$pegawaiinfoid;
  $arrkondisicheck= in_array_column($infocarikey, "FORMAT_BKN_PEGAWAI", $files_to_zip);

  $jumlahmerge= count($arrkondisicheck);
  // kalau data lebih dari satu
  if($jumlahmerge > 1)
  {
    $indexmerge= $arrkondisicheck[0];
    $linkmerge= $merfefile[$indexmerge]["LINK_MERGE"];
    if(empty($linkmerge))
    {
      array_push($merfefile, $valuefile);
      $jumlahmerge= 0;
    }
    else
    {
      $jumlahmerge= count($merfefile[$indexmerge]["LINK_MERGE"]);
    }

    // masukkan data merge link
    if($jumlahmerge == 0)
    {
      $arrmerge[$jumlahmerge]= $varlink;
      $merfefile[$indexmerge]["LINK_MERGE"]= $arrmerge;
    }
    else
    {
      array_push($merfefile[$indexmerge]["LINK_MERGE"], $varlink);
    }
  }
  else
  {
    array_push($merfefile, $valuefile);
  }
}
// print_r($merfefile);exit;

$data_files_to_zip= [];
foreach ($merfefile as $keyfile => $valuefile)
{
  $arrlinkmerge= $valuefile["LINK_MERGE"];
  // print_r($valuefile);exit;

  $linkfiledata= $valuefile["LINK_PATH"];
  $vlinkfile= $valuefile["LINK"];
  $namafilelama= basename($vlinkfile);
  $namafilebaru= $valuefile["FORMAT_BKN"].".pdf";

  $filelokasiformatlama= $vlinkfile;
  $filelokasiformatbaru= $linkfiledata.$namafilebaru;
  // penyesuian penamaan
  $filelokasiformatbaru= str_replace("S-3/Doktor", "S3", str_replace("Diploma III/Sarjana Muda", "D3", str_replace("D4/S-1", "S-1", str_replace("S-1/Sarjana", "S-1", $filelokasiformatbaru))));

  // kalau ada merge file
  if(!empty($arrlinkmerge))
  {
    if(file_exists($filelokasiformatbaru))
    {
      $pdf = new PDFMerger;
      foreach ($arrlinkmerge as $keymerge => $valuemerge)
      {
        $pdf->addPDF($valuemerge, 'all');
      }

      array_push($data_files_to_zip, $filelokasiformatbaru);
      // $pdf->merge('browser'); // send the file to the browser.
      $pdf->merge('file', $filelokasiformatbaru);
      unset($pdf);
    }

    /*foreach ($arrlinkmerge as $keymerge => $valuemerge)
    {
      if(file_exists($valuemerge))
      {
        unlink($valuemerge);
      }
    }*/
  }
  else
  {
    // kalau ada data sebelumnya hapus terlebih dahulu
    if(file_exists($filelokasiformatbaru))
    {
      unlink($filelokasiformatbaru);
    }

    if(file_exists($filelokasiformatlama))
    {
      rename($filelokasiformatlama, $filelokasiformatbaru);
      array_push($data_files_to_zip, $filelokasiformatbaru);
    }
  }
}
// print_r($data_files_to_zip);exit;

if($reqDownload == "1")
{
  // print_r($data_files_to_zip);exit;
  function deleteNonEmptyDir($dir) 
  {
     if (is_dir($dir)) 
     {
          $objects = scandir($dir);

          foreach ($objects as $object) 
          {
              if ($object != "." && $object != "..") 
              {
                  if (filetype($dir . "/" . $object) == "dir")
                  {
                      deleteNonEmptyDir($dir . "/" . $object); 
                  }
                  else
                  {
                      unlink($dir . "/" . $object);
                  }
              }
          }

          reset($objects);
          rmdir($dir);
      }
  }

  $buatfolderzip= "uploadszip/".str_replace("-","",$tempTanggalSuratKeluar)."-".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar));
  // echo $buatfolderzip;exit;
  // rmdir($buatfolderzip);
  
  if(!empty($data_files_to_zip))
  {
    // $setLokasiZip= "uploadszip/".str_replace("/","_",$tempNomorSuratKeluar)."_".$reqUserLoginId.".zip";
    $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";
    if(file_exists($setLokasiZip))
    {
     unlink($setLokasiZip);
    }
    // echo $setLokasiZip;exit;
    $result = create_zip($data_files_to_zip,$setLokasiZip);
    //deleteFileZip($data_files_to_zip);
    $down = $setLokasiZip;
  }

  $down= $setLokasiZip;
  //echo "--".$down;exit;

  ob_clean();
  ob_end_flush(); // more important function - (without - error corrupted zip)
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header('Content-Type: application/zip;\n');
  header("Content-Transfer-Encoding: Binary");
  header("Content-Disposition: attachment; filename=\"".basename($down)."\"");
  // readfile($down);
  // unlink($down);

  if(readfile($down))
  {
    unlink($down);
    // rmdir($buatfolderzip);
    deleteNonEmptyDir($buatfolderzip);
    // exit();
  }
  exit();
}
else
{
  if(!empty($data_files_to_zip))
  {
    $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";
    $down = $setLokasiZip;
    echo $down;exit;
  }
  else
  echo "0";
}
?>