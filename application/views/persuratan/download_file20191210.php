<?
include_once("functions/string.func.php");
include_once("functions/date.func.php");
require_once("lib/mergepdf/MergePdf.class.php");

$this->load->model('persuratan/SuratMasukPegawaiCheck');
$this->load->model('persuratan/SuratMasukPegawai');
$this->load->model('persuratan/SuratMasukPegawaiTurunStatus');
$this->load->model('persuratan/CetakIjinBelajar');
$this->load->model('persuratan/Pensiun');

ini_set("memory_limit","500M");
ini_set('max_execution_time', 520);

/*$set= new MergePdf();
$set->merge(
  Array(
    "lib/mergepdf/test/file-a.pdf",
    "lib/mergepdf/test/file-b.pdf"
  ), "F", "uploads/tes.pdf"
);
exit();*/

$reqUserLoginId= $this->USER_LOGIN_ID;

$reqDownload= $this->input->get("reqDownload");
$reqDownloadName= $this->input->get("reqDownloadName");
$reqMode= $this->input->get("reqMode");
$reqId= $this->input->get("reqId");
$reqRowId= $this->input->get("reqRowId");
$reqBreadCrum= $this->input->get("reqBreadCrum");
$reqJenis= $this->input->get("reqJenis");
$reqJenisNama= setjenisinfo($reqJenis);

$arrInfo="";
$index_data= 0;

if($reqMode == "personal")
{
  $statement= " AND A.SURAT_MASUK_PEGAWAI_ID = ".$reqId;
}
else
{
  $statement= " AND A.USULAN_SURAT_ID = ".$reqId;
}

if($reqDownload == "1")
{
  $set= new SuratMasukPegawaiCheck();
  $set->selectByParamsUsulan(array(), -1,-1, $statement);
  $set->firstRow();
  $tempNomorSuratKeluar= $set->getField("NOMOR");

  if($tempNomorSuratKeluar == "")
  $tempNomorSuratKeluar= $set->getField("ID_SEMENTARA");

  $tempTanggalSuratKeluar= $set->getField("TANGGAL");
  $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";
  // echo $setLokasiZip;exit();

  /*function deleteNonEmptyDir($dir) 
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

  $set= new SuratMasukPegawaiCheck();
  $set->selectByParamsUsulan(array(), -1,-1, $statement);
  $set->firstRow();
  $tempNomorSuratKeluar= $set->getField("NOMOR");
  $tempTanggalSuratKeluar= $set->getField("TANGGAL");
  $setLokasiZip= "uploadszip/".str_replace("/","_",$tempNomorSuratKeluar).".zip";
  // echo $setLokasiZip;exit();

  $buatfolderzip= "uploadszip/".str_replace("-","",$tempTanggalSuratKeluar)."-".str_replace("/","_",$tempNomorSuratKeluar);
  // echo $buatfolderzip;
  // rmdir($buatfolderzip);
  deleteNonEmptyDir($buatfolderzip);
  // exit();

  $down= $setLokasiZip;

  ob_clean();
  ob_end_flush(); // more important function - (without - error corrupted zip)
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header('Content-Type: application/zip;\n');
  header("Content-Transfer-Encoding: Binary");
  header("Content-Disposition: attachment; filename=\"".basename($down)."\"");
  readfile($down);
  unlink($down);

  if(readfile($down))
  {
    unlink($down);
    rmdir($buatfolderzip);
  }


  exit();*/
}

// $statement= " AND A.SURAT_MASUK_PEGAWAI_ID IN (445, 480)";
$set= new SuratMasukPegawaiCheck();
$set->selectByParamsUsulan(array(), -1,-1, $statement);
// echo $set->query;exit;
while($set->nextRow())
{
  //one
  $reqKategori= $set->getField("KATEGORI");
  $arrInfo[$index_data]["NOMOR"] = $set->getField("NOMOR");
  $arrInfo[$index_data]["ID_SEMENTARA"] = $set->getField("ID_SEMENTARA");
  $arrInfo[$index_data]["KATEGORI_FILE_ID"] = $set->getField("KATEGORI_FILE_ID");
  $arrInfo[$index_data]["JENIS_ID"] = $set->getField("JENIS_ID");
  $arrInfo[$index_data]["SURAT_MASUK_BKD_ID"] = $set->getField("SURAT_MASUK_BKD_ID");
  $arrInfo[$index_data]["SURAT_MASUK_UPT_ID"] = $set->getField("SURAT_MASUK_UPT_ID");
  $arrInfo[$index_data]["PEGAWAI_ID"] = $set->getField("PEGAWAI_ID");

  $arrInfo[$index_data]["RIWAYAT_ID"] = $set->getField("RIWAYAT_ID");
  
  $arrInfo[$index_data]["JENIS_PELAYANAN_ID"] = $set->getField("JENIS_PELAYANAN_ID");
  $arrInfo[$index_data]["TIPE"] = $set->getField("TIPE");
  $arrInfo[$index_data]["KATEGORI"] = $set->getField("KATEGORI");
  $arrInfo[$index_data]["NAMA"] = $set->getField("NAMA");
  $arrInfo[$index_data]["LINK_FILE"] = $set->getField("LINK_FILE");
  $arrInfo[$index_data]["STATUS_INFORMASI"] = $set->getField("STATUS_INFORMASI");
  $arrInfo[$index_data]["INFORMASI_TABLE"] = $set->getField("INFORMASI_TABLE");
  $arrInfo[$index_data]["INFORMASI_FIELD"] = $set->getField("INFORMASI_FIELD");
  $arrInfo[$index_data]["PANGKAT_RIWAYAT_ID"] = $set->getField("PANGKAT_RIWAYAT_ID");
  $arrInfo[$index_data]["PENDIDIKAN_RIWAYAT_ID"] = $set->getField("PENDIDIKAN_RIWAYAT_ID");

  $arrInfo[$index_data]["NIP_BARU"] = $set->getField("NIP_BARU");
  // $arrInfo[$index_data]["NAMA_LENGKAP"] = $set->getField("NAMA_LENGKAP");
  $arrInfo[$index_data]["NAMA_LENGKAP"] = str_replace("'", "", $set->getField("NAMA_PEGAWAI"));
  $arrInfo[$index_data]["TANGGAL"] = str_replace("-", "", $set->getField("TANGGAL"));

  $arrInfo[$index_data]["TAHUN_SURAT"] = $set->getField("TAHUN_SURAT");
  $arrInfo[$index_data]["JENIS_ID"] = $set->getField("JENIS_ID");

  
  $arrInfo[$index_data]["JABATAN_RIWAYAT_ID"] = $set->getField("JABATAN_RIWAYAT_ID");
  $arrInfo[$index_data]["JABATAN_TAMBAHAN_ID"] = $set->getField("JABATAN_TAMBAHAN_ID");

  $tempDataKpStatusSuratTandaLulus= $set->getField("KP_STATUS_SURAT_TANDA_LULUS");
  $tempDataKpStatusPendidikanBelumDiakui= $set->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI");

  $tempDataKpStatusStrukturalId= $set->getField("KP_STATUS_STRUKTURAL_ID");
  $tempDataKpStatusStrukturalNama= $set->getField("KP_STATUS_STRUKTURAL_NAMA");
  $tempDataKpDiklatStrukturalId= $set->getField("KP_DIKLAT_STRUKTURAL_ID");

  $tempDataKpPakLamaId= $set->getField("KP_PAK_LAMA_ID");
  $tempDataKpPakBaruId= $set->getField("KP_PAK_BARU_ID");
  $tempDataKpStatusSertifikatKeaslian= $set->getField("KP_STATUS_SERTIFIKAT_KEASLIAN");
  $tempDataKpSertifikatKeaslianId= $set->getField("KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID");
  $tempDataStatusKpSertifikatPendidik= $set->getField("KP_STATUS_SERTIFIKAT_PENDIDIK");
  $tempDataKpSertifikatPendidikId= $set->getField("KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID");

  if($reqKategori == "kpreguler")
  {
    if($tempDataKpStatusSuratTandaLulus == "" && $set->getField("NOMOR_CHECK") == 5)
    {
      continue;
    }

    if($tempDataKpStatusPendidikanBelumDiakui == "" && $set->getField("NOMOR_CHECK") >= 6)
    {
      continue;
    }
    else
    {
      if($set->getField("NOMOR_CHECK") >= 6)
      {
        // $arrInfo[$index_data]["LINK_FILE"]= "1";
        $arrInfo[$index_data]["PENDIDIKAN_RIWAYAT_ID"] = $set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID");
        if($set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID") == "" && $set->getField("NOMOR_CHECK") <= 9)
          continue;
      }
    }

  }
  elseif($reqKategori == "kpstruktural")
  {
    if($set->getField("NOMOR_CHECK") == 5)
    {
      if($tempDataKpStatusStrukturalId == "")
        continue;
      elseif($set->getField("TIPE") == "2" && $tempDataKpStatusSuratTandaLulus == "")
      {
        continue;
      }
      elseif($set->getField("TIPE") == "1" && $tempDataKpDiklatStrukturalId == "")
      {
        continue;
      }

    }

    if($tempDataKpStatusPendidikanBelumDiakui == "" && $set->getField("NOMOR_CHECK") >= 6)
    {
      continue;
    }
    else
    {
      if($set->getField("NOMOR_CHECK") >= 6)
      {
        // $arrInfo[$index_data]["LINK_FILE"]= "1";
        $arrInfo[$index_data]["PENDIDIKAN_RIWAYAT_ID"] = $set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID");
        if($set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID") == "" && $set->getField("NOMOR_CHECK") <= 9)
          continue;
      }
    }

  }
  elseif($reqKategori == "kpjft")
  {
    if($set->getField("NOMOR_CHECK") == 6)
    {
      if($tempDataStatusKpSertifikatPendidik == "")
        continue;
    }

    if($set->getField("NOMOR_CHECK") == 7)
    {
      if($tempDataKpStatusSertifikatKeaslian == "")
        continue;
    }

    if($tempDataKpStatusPendidikanBelumDiakui == "" && $set->getField("NOMOR_CHECK") >= 10)
    {
      continue;
    }
    else
    {
      if($set->getField("NOMOR_CHECK") >= 10)
      {
        // $arrInfo[$index_data]["LINK_FILE"]= "1";
        $arrInfo[$index_data]["PENDIDIKAN_RIWAYAT_ID"] = $set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID");
        if($set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID") == "" && $set->getField("NOMOR_CHECK") <= 13)
          continue;
      }
    }

  }

  $tempDataKpPakId= "";
  if($reqKategori == "kpjft" && $set->getField("NOMOR_CHECK") == 4)
  {
    if($tempDataKpPakLamaId == ""){}
    else
    $tempDataKpPakId= $tempDataKpPakLamaId;
  }

  if($reqKategori == "kpjft" && $set->getField("NOMOR_CHECK") == 5)
  {
    if($tempDataKpPakLamaId == ""){}
    else
    $tempDataKpPakId= $tempDataKpPakBaruId;
  }

  $arrInfo[$index_data]["KP_PAK_ID"] = $tempDataKpPakId;
  $arrInfo[$index_data]["KP_STATUS_SERTIFIKAT_KEASLIAN"] = $tempDataKpStatusSertifikatKeaslian;
  $arrInfo[$index_data]["KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID"] = $tempDataKpSertifikatKeaslianId;
  $arrInfo[$index_data]["KP_STATUS_SERTIFIKAT_PENDIDIK"] = $tempDataStatusKpSertifikatPendidik;
  $arrInfo[$index_data]["KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID"] = $tempDataKpSertifikatPendidikId;

  $arrInfo[$index_data]["KP_STATUS_SURAT_TANDA_LULUS"] = $tempDataKpStatusSuratTandaLulus;
  $arrInfo[$index_data]["KP_SURAT_TANDA_LULUS_ID"] = $set->getField("KP_SURAT_TANDA_LULUS_ID");
  $arrInfo[$index_data]["KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI"] = $tempDataKpStatusPendidikanBelumDiakui;
  $arrInfo[$index_data]["KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID"] = $set->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID");
  $arrInfo[$index_data]["KP_PEGAWAI_FILE_ID"] = $set->getField("KP_PEGAWAI_FILE_ID");
  $arrInfo[$index_data]["KP_DIKLAT_ID"] = $set->getField("KP_DIKLAT_ID");
  $arrInfo[$index_data]["KP_DIKLAT_STRUKTURAL_ID"] = $tempDataKpDiklatStrukturalId;

  $arrInfo[$index_data]["NAMA_CHECK"] = $set->getField("NAMA_CHECK");

  $index_data++;
}
$jumlah_info= $index_data;
// print_r($arrInfo);exit;

if($index_data > 0)
{
  $tempNomorSuratKeluar= $arrInfo[0]["NOMOR"];
  if($tempNomorSuratKeluar == "")
  $tempNomorSuratKeluar= $arrInfo[0]["ID_SEMENTARA"];

  $tempTanggalSuratKeluar= $arrInfo[0]["TANGGAL"];
  $reqJenisId= $arrInfo[0]["JENIS_ID"];
}
// echo $tempNomorSuratKeluar;exit;


$indexZip= 0;
$tempJumlahChecked= 0;
// $tempTahunSurat= $reqTahunSurat;

$index_tahun=2;
if($reqJenisId == 7)
$index_tahun=1;

$tempAnakRowNum= 1;
$index_suami_istri= 0;

$tempDataPegawaiId= "";
for($index=0; $index < $jumlah_info; $index++)
{
  $reqTahunSurat= $arrInfo[$index]["TAHUN_SURAT"];
  $tempKategoriFileId= $arrInfo[$index]["KATEGORI_FILE_ID"];
  $tempSuratMasukPegawaiCheckId= $arrInfo[$index]["SURAT_MASUK_PEGAWAI_CHECK_ID"];
  $tempJenisId= $arrInfo[$index]["JENIS_ID"];
  $tempSuratMasukBkdId= $arrInfo[$index]["SURAT_MASUK_BKD_ID"];
  $tempSuratMasukUptId= $arrInfo[$index]["SURAT_MASUK_UPT_ID"];
  $tempPegawaiId= $arrInfo[$index]["PEGAWAI_ID"];
  $tempNomor= $arrInfo[$index]["NOMOR"];
  $tempJenisPelayananId= $arrInfo[$index]["JENIS_PELAYANAN_ID"];
  $tempTipe= $arrInfo[$index]["TIPE"];
  $tempNamaCheck= $arrInfo[$index]["NAMA_CHECK"];
  $tempInfoChecked= $arrInfo[$index]["INFO_CHECKED"];
  $tempLinkFile= $arrInfo[$index]["LINK_FILE"];
  $tempStatusInformasi= $arrInfo[$index]["STATUS_INFORMASI"];
  $tempInformasiTable= $arrInfo[$index]["INFORMASI_TABLE"];
  $tempInformasiField= $arrInfo[$index]["INFORMASI_FIELD"];
  
  $tempPangkatRiwayatId= $arrInfo[$index]["PANGKAT_RIWAYAT_ID"];
  $tempJabatanRiwayatId= $arrInfo[$index]["JABATAN_RIWAYAT_ID"];
  $tempJabatanTugasId= $arrInfo[$index]["JABATAN_TAMBAHAN_ID"];
  $tempPendidikanRiwayatId= $arrInfo[$index]["PENDIDIKAN_RIWAYAT_ID"];

  $tempKpSuratTandaLulusId= $arrInfo[$index]["KP_SURAT_TANDA_LULUS_ID"];
  $tempKpPendidikanRiwayatBelumDiakuiId= $arrInfo[$index]["KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID"];

  $tempKpPegawaiFileId= $arrInfo[$index]["KP_PEGAWAI_FILE_ID"];
  $tempKpDiklatId= $arrInfo[$index]["KP_DIKLAT_ID"];
  $tempKpDiklatStrukturalId= $arrInfo[$index]["KP_DIKLAT_STRUKTURAL_ID"];

  $tempDataKpPakId= $arrInfo[$index]["KP_PAK_ID"];


  $tempNipBaru= $arrInfo[$index]["NIP_BARU"];
  $tempNamaLengkap= $arrInfo[$index]["NAMA_LENGKAP"];

  $reqKategori= $arrInfo[$index]["KATEGORI"];

  if($tempDataPegawaiId == $tempPegawaiId){}
  else
  {
    $index_suami_istri= 0;
    $arrSuamiIstri="";
    $index_data= 0;
    $sOrder= "ORDER BY A.TANGGAL_KAWIN";
    $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsPensiunSuamiIstri(array(),-1,-1, $statement, $reqKategori, $sOrder);
    // echo $set_detil->query;exit();
    while($set_detil->nextRow())
    {
      $arrSuamiIstri[$index_data]["SUAMI_ISTRI_ID"] = $set_detil->getField("SUAMI_ISTRI_ID");
      $arrSuamiIstri[$index_data]["NAMA"] = $set_detil->getField("NAMA");
      $arrSuamiIstri[$index_data]["TANGGAL_KAWIN"] = dateToPageCheck($set_detil->getField("TANGGAL_KAWIN"));
      $arrSuamiIstri[$index_data]["CERAI_TMT"] = dateToPageCheck($set_detil->getField("CERAI_TMT"));
      $arrSuamiIstri[$index_data]["KEMATIAN_TMT"] = dateToPageCheck($set_detil->getField("KEMATIAN_TMT"));
      $arrSuamiIstri[$index_data]["STATUS_S_I"] = $set_detil->getField("STATUS_S_I");
      $arrSuamiIstri[$index_data]["STATUS_S_I_NAMA"] = $set_detil->getField("STATUS_S_I_NAMA");
      $arrSuamiIstri[$index_data]["STATUS_S_I_RIWAYAT_FIELD"] = $set_detil->getField("STATUS_S_I_RIWAYAT_FIELD");
      $index_data++;
    }
    $jumlah_suami_istri= $index_data;
    // print_r($arrSuamiIstri);exit();
  }

  $tempDataPegawaiId= $tempPegawaiId;

  // ambil data
  if($tempInformasiTable == "PANGKAT_RIWAYAT")
  {
    if($tempNama == "Surat Pemberitahuan KGB")
    {
      $statement= " AND GR.JENIS_KENAIKAN = 3 AND GR.PEGAWAI_ID = ".$tempPegawaiId;
      $set_detil= new SuratMasukPegawaiCheck();
      $set_detil->selectByParamsKgb(array(),-1,-1, $statement);
      // echo $set_detil->query;exit();
      $set_detil->firstRow();
      $tempRiwayatId= $set_detil->getField("RIWAYAT_ID");
      $tempInformasiTableAwal= $tempInformasiTable;
      $tempInformasiTable= $set_detil->getField("RIWAYAT_TABLE");
      $tempInformasiField= $set_detil->getField("RIWAYAT_FIELD");
      $tempInfoRiwayatField= "";
    }
    else
    {

        $tempRiwayatId= $tempPangkatRiwayatId;

        if($tempKategoriFileId == "1" || $tempKategoriFileId == "2"){}
        else
        {
          $statement= " AND A.PANGKAT_RIWAYAT_ID = ".$tempPangkatRiwayatId;

          // KATEGORI_FILE_ID
          $set_detil= new SuratMasukPegawaiCheck();
          $set_detil->selectByParamsPangkat(array(),-1,-1, $statement);
          $set_detil->firstRow();

          // if($tempPegawaiId == "7105")
          // {
          //   echo $set_detil->query;exit;
          // }

          $tempPangkatKode= $set_detil->getField("PANGKAT_KODE");
          $tempPangkatTahun= $set_detil->getField("MASA_KERJA_TAHUN");
          $tempPangkatBulan= $set_detil->getField("MASA_KERJA_BULAN");
          unset($set_detil);
          
          if($tempInformasiField == "PANGKAT_INFO")
          {
            $tempInfoValue= $tempPangkatKode;
          }
          elseif($tempInformasiField == "MASA_KERJA")
          {
            $tempInfoValue= $tempPangkatTahun." tahun ".$tempPangkatBulan." bln";
          }
        }

        $tempInfoRiwayatField= $tempKpPegawaiFileId= "";

    }
    
  }
  elseif
  (
    $tempInformasiTable == "KGB"
    ||
    (
      ($tempInformasiTable == "GAJI_RIWAYAT" && $tempJenisPelayananId == 7)
    )
  )
  {
    $statement= " AND GR.JENIS_KENAIKAN = 3 AND GR.PEGAWAI_ID = ".$tempPegawaiId;
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsKgb(array(),-1,-1, $statement);
    // echo $set_detil->query;exit();
    $set_detil->firstRow();
    $tempRiwayatId= $set_detil->getField("RIWAYAT_ID");
    $tempInformasiTableAwal= $tempInformasiTable;
    $tempInformasiTable= $set_detil->getField("RIWAYAT_TABLE");
    $tempInformasiField= $set_detil->getField("RIWAYAT_FIELD");
    $tempInfoRiwayatField= $tempKpPegawaiFileId= "";
  }
  elseif($tempInformasiTable == "JABATAN_RIWAYAT")
  {
    $tempRiwayatId= $tempJabatanRiwayatId;
    // echo $tempKategoriFileId;exit();
    if($tempKategoriFileId == "1" || $tempKategoriFileId == "2"){}
    else
    {
      $statement= " AND A.JABATAN_RIWAYAT_ID = ".$tempRiwayatId;

      // KATEGORI_FILE_ID
      $set_detil= new SuratMasukPegawaiCheck();
      $set_detil->selectByParamsJabatan(array(),-1,-1, $statement);
      $set_detil->firstRow();
      // echo $set_detil->query;exit;
      /*$tempPangkatKode= $set_detil->getField("PANGKAT_KODE");
      $tempPangkatTahun= $set_detil->getField("MASA_KERJA_TAHUN");
      $tempPangkatBulan= $set_detil->getField("MASA_KERJA_BULAN");*/
      // echo $tempInformasiField;exit();
      $tempInfoValue= $set_detil->getField($tempInformasiField);
      unset($set_detil);
      
      /*if($tempInformasiField == "PANGKAT_INFO")
      {
        $tempInfoValue= $tempPangkatKode;
      }
      elseif($tempInformasiField == "MASA_KERJA")
      {
        $tempInfoValue= $tempPangkatTahun." tahun ".$tempPangkatBulan." bln";
      }*/
    }

    $tempInfoRiwayatField= $tempKpPegawaiFileId= "";
    
  }
  elseif($tempInformasiTable == "JABATAN_TAMBAHAN")
  {
    $tempRiwayatId= $tempJabatanTugasId;

    $statement= " AND A.JABATAN_TAMBAHAN_ID = ".$tempRiwayatId;

    // KATEGORI_FILE_ID
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsTugas(array(),-1,-1, $statement);
    $set_detil->firstRow();
    $tempInfoValue= $set_detil->getField($tempInformasiField);
    unset($set_detil);
    
    $tempInfoRiwayatField= $tempKpPegawaiFileId= "";
  }
  elseif($tempInformasiTable == "SURAT_TANDA_LULUS")
  {
    $tempRiwayatId= $tempKpSuratTandaLulusId;
    $statement= " AND A.SURAT_TANDA_LULUS_ID = ".$tempRiwayatId;
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsSuratTandaLulus(array(),-1,-1, $statement);
    $set_detil->firstRow();
    // echo $set_detil->query;exit;
    $tempInfoValue= $set_detil->getField("NO_STLUD");
    unset($set_detil);
    // echo $tempInfoValue;exit();

    $tempInformasiTableAwal= $tempInformasiTable;
    $tempKpPegawaiFileId= $tempInfoRiwayatField= "";
  }
  elseif($tempNamaCheck == "Sertifikat Pendidik")
  {
    $tempKpPegawaiFileId= $tempDataKpSertifikatPendidikId;
    $tempInformasiTable= "";
  }
  elseif($tempNamaCheck == "Sertifikat Keaslian PAK")
  {
    $tempKpPegawaiFileId= $tempDataKpSertifikatKeaslianId;
    $tempInformasiTable= "";
  }
  elseif($tempInformasiTable == "DIKLAT_STRUKTURAL")
  {
    $tempRiwayatId= $tempKpDiklatStrukturalId;
    $statement= " AND A.DIKLAT_STRUKTURAL_ID = ".$tempRiwayatId;
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsDiklatStruktural(array(),-1,-1, $statement);
    $set_detil->firstRow();
    // echo $set_detil->query;exit;
    $tempInfoValue= $set_detil->getField("NO_STTPP");
    $tempInformasiTableAwal= $tempInformasiTable;
    $tempKpPegawaiFileId= $tempInfoRiwayatField= "";
    unset($set_detil);
    // echo $tempInfoValue;exit();
    
  }
  elseif($tempInformasiTable == "PAK")
  {
    $tempRiwayatId= $tempDataKpPakId;
    $statement= " AND A.PAK_ID = ".$tempRiwayatId;
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsPak(array(),-1,-1, $statement);
    $set_detil->firstRow();
    // echo $set_detil->query;exit;
    $tempInfoValue= $set_detil->getField("NO_SK");
    $tempInformasiTableAwal= $tempInformasiTable;
    $tempKpPegawaiFileId= $tempInfoRiwayatField= "";
    unset($set_detil);
    // echo $tempInfoValue;exit();
    
  }
  elseif($tempInformasiTable == "PENDIDIKAN_RIWAYAT")
  {
    if($tempJenisPelayananId == 10)
    $tempRiwayatId= $tempKpPendidikanRiwayatBelumDiakuiId;
    else
    $tempRiwayatId= $tempPendidikanRiwayatId;

    // $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$tempPendidikanRiwayatId;
    $statement= " AND A.PENDIDIKAN_RIWAYAT_ID = ".$tempRiwayatId;

    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsPendidikan(array(),-1,-1, $statement);
    $set_detil->firstRow();
    //$set_detil->query;exit;
    $tempPendidikanNama= $set_detil->getField("PENDIDIKAN_NAMA");
    $tempPendidikanJurusan= $set_detil->getField("JURUSAN");
    $tempInformasiTableAwal= $tempInformasiTable;
    $tempInfoRiwayatField= $tempTipe;
    unset($set_detil);
    
    if($tempInformasiField == "PENDIDIKAN_NAMA")
    {
      $tempInfoValue= $tempPendidikanNama." / ".$tempPendidikanJurusan;
    }

    // $tempKpPegawaiFileId= "";

  }
  elseif($tempInformasiTable == "PENILAIAN_SKP")
  {
    if($tempDataSKPPegawaiId == $tempPegawaiId)
    {
      $index_tahun--;
    }
    else
    {
      $index_tahun=2;
      if($reqJenisId == 7 || $reqJenisId == 10)
        $index_tahun=1;
    }

    $tempDataSKPPegawaiId= $tempPegawaiId;
    $tempTahunSurat= $reqTahunSurat - $index_tahun;
    // $index_tahun--;
    
    $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.TAHUN = ".$tempTahunSurat;
    $set_detil= new SuratMasukPegawaiCheck();
    $set_detil->selectByParamsPenilaianSkp(array(),-1,-1, $statement);
    $set_detil->firstRow();
    // echo $set_detil->query;exit;
    $tempSkpTahun= $tempTahunSurat;
    $tempPenilaianSkpId= $set_detil->getField("PENILAIAN_SKP_ID");
    unset($set_detil);
    
    $tempRiwayatId= $tempPenilaianSkpId;
    $tempInfoValue= $tempSkpTahun;
    $tempInfoRiwayatField= "ppk";

    // $tempKpPegawaiFileId= "";
  }
  elseif($tempInformasiTable == "TAMBAHAN_MASA_KERJA")
  {
    $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId;
    $set_detil= new Pensiun();
    $set_detil->selectByParamsTambahanMasaKerja(array(), -1,-1, $statement, $sOrder);
    $set_detil->firstRow();
    $tempTambahanMasaKerjaId= $set_detil->getField("TAMBAHAN_MASA_KERJA_ID");
    if($tempTambahanMasaKerjaId == "")
      continue;
    
  }
  elseif($tempInformasiTable == "SUAMI_ISTRI")
  {
    if(isStrContain($tempTipe, "aktanikah"))
    {
      // echo $index_suami_istri."--".$jumlah_suami_istri;exit();
      if($index_suami_istri < $jumlah_suami_istri){}
      else
        continue;

      $tempSuamiIstriId= $arrSuamiIstri[$index_suami_istri]["SUAMI_ISTRI_ID"];
      $tempSuamiIstriNama= $arrSuamiIstri[$index_suami_istri]["NAMA"];
      $tempSuamiIstriTanggalKawin= $arrSuamiIstri[$index_suami_istri]["TANGGAL_KAWIN"];
      $tempSuamiIstriCeraiTmt= $arrSuamiIstri[$index_suami_istri]["CERAI_TMT"];
      $tempSuamiIstriKematianTmt= $arrSuamiIstri[$index_suami_istri]["KEMATIAN_TMT"];
      $tempSuamiIstriStatusSi= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I"];
      $tempSuamiIstriStatusSiNama= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I_NAMA"];
      $tempSuamiIstriStatusSiRiwayatField= $arrSuamiIstri[$index_suami_istri]["STATUS_S_I_RIWAYAT_FIELD"];

      $tempNama= "Nama Suami/Istri";
      $tempInfoValue= $tempSuamiIstriNama;

      $index_suami_istri++;

      $tempInfoRiwayatField= "aktanikah";

      $tempRiwayatId= $tempSuamiIstriId;
      // echo $tempRiwayatId;exit();
    }
    elseif(isStrContain($tempTipe, "kariskarsu"))
    {
      // untuk ambil data kartu keluarga
      $tempInformasiTable= "PEGAWAI";
      $tempRiwayatId= 6;
      $tempInfoRiwayatField= "";
    }
    else
    {
      if($tempTipe == "skkonversinipbaru")
      {
        $sOrder= "ORDER BY A.TANGGAL_KAWIN DESC";
        $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')";
        $set_detil= new Pensiun();
        $set_detil->selectByParamsSuamiIstri(array(), -1,-1, $statement, $sOrder);
        $set_detil->firstRow();
        //$set_detil->query;exit;
        $tempSuamiIstriId= $set_detil->getField("SUAMI_ISTRI_ID");
        $tempSuamiIstriStatusPns= $set_detil->getField("STATUS_PNS");
        if($tempSuamiIstriStatusPns == "")
        {
          continue;
        }
        $tempInfoRiwayatField= $tempTipe;
        $tempRiwayatId= $tempSuamiIstriId;
      }
      else
      {
        if($jumlah_suami_istri > 0)
        {
          $index_terakhir_suami_istri= $jumlah_suami_istri - 1;
          $tempSuamiIstriId= $arrSuamiIstri[$index_terakhir_suami_istri]["SUAMI_ISTRI_ID"];
          $tempInfoRiwayatField= $tempTipe;
          $tempRiwayatId= $tempSuamiIstriId;
          // echo $tempSuamiIstriId."-asd".$index;exit();
        }
      }

    }
  }
  elseif($tempInformasiTable == "ANAK")
  {
    $statement= " AND A.NOMOR = ".$tempAnakRowNum;
    $set_detil= new Pensiun();
    $set_detil->selectByParamsAnak($tempPegawaiId, $reqKategori, $statement);
    $set_detil->firstRow();
    // echo $set_detil->query;exit;
    $tempAnakId= $set_detil->getField("ANAK_ID");
    $tempAnakUsia= $set_detil->getField("ANAK_USIA");
    $tempAnakTanggalLahir= dateToPageCheck($set_detil->getField("ANAK_TANGGAL_LAHIR"));
    $tempInfoRiwayatField= "aktakelahiran";
    $tempNama= "Nama Anak";
    $tempInfoValue= $set_detil->getField("ANAK_NAMA");
    unset($set_detil);

    if($tempAnakId == "")
    continue;

    $tempRiwayatId= $tempAnakId;
    $tempAnakRowNum++;
  }
  elseif($tempInformasiTable == "PEGAWAI")
  {
    if(isStrContain($tempTipe, "kariskarsu"))
      $tempRiwayatId= 6;
    else
      $tempRiwayatId= $tempKategoriFileId;

    $tempInfoRiwayatField= "";
    // echo $tempRiwayatId;exit();
  }

  $tempInfoValue= "";
  
  //get path file
  $tempPath="";
  if($tempLinkFile == ""){}
  else
  {
    //one
    //$statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."'";

    if($tempKategoriFileId == "1" && $tempInformasiTable !== "PEGAWAI")
    $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = 'skcpns'";
    elseif($tempKategoriFileId == "2" && $tempInformasiTable !== "PEGAWAI")
    {
      if($tempTipe == "sttplprajabatan")
      {
          $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = '".$tempTipe."'";
      }
      else
      {
          $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_FIELD = 'skpns'";
      }
    }
    elseif($tempInfoRiwayatField !== "")
    {
      // if($tempPegawaiId == 7105)
      // {
      //   echo $tempInfoRiwayatField."-".$tempPegawaiId;exit();
      // }
      $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID = ".$tempRiwayatId." AND A.RIWAYAT_FIELD = '".$tempInfoRiwayatField."'";
    }
    elseif($tempKpPegawaiFileId !== "")
    {
      // if($tempPegawaiId == 7105)
      // {
      //   echo "b-".$tempPegawaiId;exit();
      // }
      
      if($tempInformasiTable == "")
        $statement= " AND A.PEGAWAI_FILE_ID = ".$tempKpPegawaiFileId."  AND A.PEGAWAI_ID = ".$tempPegawaiId." AND COALESCE(NULLIF(A.RIWAYAT_TABLE, ''), NULL) IS NULL";
      else
      $statement= " AND A.PEGAWAI_FILE_ID = ".$tempKpPegawaiFileId."  AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID = ".$tempRiwayatId;
    }
    else
    {
      if($tempRiwayatId == 9 || $tempRiwayatId == 19)
      $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID IN (9,19)";
      else
      $statement= " AND A.PEGAWAI_ID = ".$tempPegawaiId." AND A.RIWAYAT_TABLE = '".$tempInformasiTable."' AND A.RIWAYAT_ID = ".$tempRiwayatId;
    }

    $set_detil= new SuratMasukPegawaiCheck();

    if($tempNamaCheck == "Sertifikat Pendidik" || $tempNamaCheck == "Sertifikat Keaslian PAK")
    {
      $set_detil->selectByParamsFile(array(),-1,-1, " AND A.PEGAWAI_FILE_ID = ".$tempKpPegawaiFileId."  AND A.PEGAWAI_ID = ".$tempPegawaiId);
    }
    else
      $set_detil->selectByParamsDownload(array(),-1,-1, $statement);

    $set_detil->firstRow();
    
    // if($tempNamaCheck == "Sertifikat Pendidik" || $tempNamaCheck == "Sertifikat Keaslian PAK")
    // if($tempInformasiTable == "PEGAWAI" && $tempRiwayatId == "2")
    // if($tempInformasiTable == "PEGAWAI" && $tempKategoriFileId == "12")
    // if($tempInformasiTable == "PANGKAT_RIWAYAT" && $tempKategoriFileId == "4")
    // if($tempInformasiTable == "PENDIDIKAN_RIWAYAT" && $tempTipe == "ijinbelajar")
    // if($tempInformasiTable == "PANGKAT_RIWAYAT" && $tempPegawaiId == "7105")
    // if($tempInformasiTable == "PANGKAT_RIWAYAT")
    if($tempInformasiTable == "JABATAN_TAMBAHAN" && $tempPegawaiId == "7105")
    // if($tempInformasiTableAwal == "KGB")
    // if($tempLinkFile == "KGB")
    
    // if($tempInformasiTable == "PENILAIAN_SKP")
    // if($tempInformasiTable == "ANAK")
    // if($tempTipe == "skjandaduda")
    {
    // echo $set_detil->query;exit;
    }
    $tempPath= $set_detil->getField("PATH");
    $tempNewPath = basename($tempPath);
    $tempFormatBkn= $set_detil->getField("FORMAT_BKN");

    if($tempNamaCheck == "Sertifikat Pendidik")
    {
      $tempFormatBkn= "SERDIK_".$tempNipBaru;
    }
    elseif($tempNamaCheck == "Sertifikat Keaslian PAK")
    {
      $tempFormatBkn= "PAK_SERTIFIKASI_".$tempNipBaru;
    }

    if($tempFormatBkn == "")
      continue;

    /*if($tempFormatBkn == "")
    {
      $tempNewPath= explode(".", $tempNewPath);
      $tempFormatBkn= $tempNewPath[0];
    }*/

    // echo $set_detil->query."</br>";
    // echo $tempFormatBkn."</br>";
    // echo $tempPath."</br>";exit;

    if($tempInformasiTable == "PEGAWAI" && $tempRiwayatId == "2")
    {
    // echo $tempPath."ASD";exit;
    }

    if(file_exists($tempPath)){}
    else
    $tempPath= "";

    if($tempInformasiTable == "JABATAN_RIWAYAT" && $reqKategori == "kpjft" && $tempPath !== "")
    {
      // echo $tempFormatBkn;exit();
      $tempFormatBkn= str_replace("SK_JABATAN", "SK_JABFUNG", $tempFormatBkn);
    }

  }
  // echo "-".$tempPath."-".$tempFormatBkn."<br/>";
  // echo "-".$tempPath."-".$tempNewPath."<br/>";
  // exit();

  // $buatfolderzip= $tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".$tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".str_replace("/","_",$tempNamaLengkap)."-".$tempNipBaru;
  $buatfolderzip= $tempTanggalSuratKeluar."-".str_replace("/","_",$tempNomorSuratKeluar)."/".str_replace("/","_",$tempNamaLengkap)."-".$tempNipBaru;

  // $direktori= "uploads/berkas-".str_replace(" ","",$tempNipBaru)."/";
  // $direktorihapus= "uploads/berkas-".str_replace(" ","",$tempNipBaru);

  // $direktori= "uploads/".str_replace(" ","",$buatfolderzip)."/";
  // $direktorihapus= "uploads/".str_replace(" ","",$buatfolderzip);

  $direktori= "uploadszip/".$buatfolderzip."/";
  $direktoriFile= "uploadszip/".$buatfolderzip."/";
  // $direktorihapus= "uploadszip/".$buatfolderzip;

  // echo $direktori;exit();

  // tglsk-nomorsk/tglsk-nomorsk/namapns-nipbaru

  if(file_exists($direktoriFile)){}
  else
  {
    makedirs($direktoriFile);
  }
  // exit();

  if($tempPath == ""){}
  else
  {
    $file= $tempPath;
    $linkfilePath= $direktori;
    $newfile= $direktori.$tempNewPath;
    // $newfile= $direktori.$tempFormatBkn.".pdf";
    // echo $newfile;exit();

    if(file_exists($newfile))
    {
      $files_to_zip[$indexZip]["LINK"]= $newfile;
      $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
      $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
      // $files_to_zip[$indexZip]= $newfile;
      $indexZip++;
    }
    else
    {
      // echo $file;exit();
      if(!copy($file,$newfile)){}
      else
      {
        // echo $newfile;exit();
        $files_to_zip[$indexZip]["LINK"]= $newfile;
        $files_to_zip[$indexZip]["LINK_PATH"]= $linkfilePath;
        $files_to_zip[$indexZip]["FORMAT_BKN"]= $tempFormatBkn;
        // $files_to_zip[$indexZip]= $newfile;
        $indexZip++;
      }
    }

    // $files_to_zip[$indexZip]["LINK"]= $newfile;
    // $indexZip++;

    // echo $file."xxx".$newfile."</br>";
    // echo $newfile."</br>";
    // exit;
  }
  
}
// print_r($files_to_zip); exit();

// $indexZip=2;
// echo $direktori;exit();

$index_data_baru= 0;
$data_files_to_zip= "";
for($index_file=0; $index_file < $indexZip; $index_file++)
{
  // merge file pdf
  $linkfiledata= $files_to_zip[$index_file]["LINK_PATH"];
  // echo $linkfiledata;exit();

  $namafilelama= basename($files_to_zip[$index_file]["LINK"]);
  $namafilebaru= $files_to_zip[$index_file]["FORMAT_BKN"].".pdf";
  $namafilebaruBackup= $files_to_zip[$index_file]["FORMAT_BKN"]."backup.pdf";

  $filelokasiformatlama= $files_to_zip[$index_file]["LINK"];
  // echo $filelokasiformatlama."<br/>";//exit();
  $filelokasiformatbaru= $linkfiledata.$namafilebaru;
  $filelokasiformatbaru= str_replace("D4/S-1", "S-1", str_replace("S-1/Sarjana", "S-1", $filelokasiformatbaru));

  // echo $filelokasiformatbaru;exit();
  $filelokasiformatbarubackup= $linkfiledata.$namafilebaru;
  // echo $filelokasiformatbarubackup;exit();

  // if($files_to_zip[$index_file]["FORMAT_BKN"] == "SK_CPNS_196108261986021002")
  // {
  //   echo $filelokasiformatlama."<br/>".$filelokasiformatbaru."<br/>".$filelokasiformatbarubackup;exit();
  // }

  $checkfile= "";
  if(file_exists($filelokasiformatbaru)){}
  else
    $checkfile= "1";

  // coba kondisi
  if($checkfile == "1")
  {
    // kalau file ada maka di merge
    if(file_exists($filelokasiformatbaru))
    {
      rename($filelokasiformatbaru, $filelokasiformatbarubackup);
      $set_merge= new MergePdf();
      $set_merge->merge(
        Array(
          $filelokasiformatbarubackup,
          $filelokasiformatlama
        ), "F", $filelokasiformatbaru
      );
      unlink($filelokasiformatlama);
    }
    // kalau belum ada maka rename file lama
    else
    {
      if($filelokasiformatlama == $filelokasiformatbaru){}
      else
      rename($filelokasiformatlama, $filelokasiformatbaru);
    }

  }

  if($checkfile == "")
  {
    $data_files_to_zip[$index_data_baru]= $filelokasiformatbaru;
    $index_data_baru++;
  }

  // echo $namafilelama."<br/>".$namafilebaru."<br/><br/>";
  // exit();
  // namafilezip
}
// print_r($data_files_to_zip);exit();
// echo $reqDownload;exit();
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

  // $buatfolderzip= "uploadszip/".str_replace("-","",$tempTanggalSuratKeluar)."-".str_replace("/","_",$tempNomorSuratKeluar);
  $buatfolderzip= "uploadszip/".str_replace("-","",$tempTanggalSuratKeluar)."-".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar));
  // echo $buatfolderzip;exit;
  // rmdir($buatfolderzip);
  
  if($indexZip > 0)
  {
    // $setLokasiZip= "uploadszip/".str_replace("/","_",$tempNomorSuratKeluar)."_".$reqUserLoginId.".zip";
    $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";
    if(file_exists($setLokasiZip))
    {
     unlink($setLokasiZip);
    }
    // echo $setLokasiZip;exit;
    $result = create_zip($data_files_to_zip,$setLokasiZip);
    //echo "A";
    //deleteFileZip($data_files_to_zip);
    $down = $setLokasiZip;
  //echo "A";
  }
  //echo "A";

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

  /*$setLokasiZip= "uploads/".str_replace("/","_",$tempNomorSuratKeluar).".zip";
  // $setLokasiZip= "uploads/".$buatfolderzip.".zip";
  // echo $setLokasiZip;exit();

  // if(file_exists($setLokasiZip))
  // {
  //   unlink($setLokasiZip);
  // }

  // $result = create_zip($data_files_to_zip,$setLokasiZip);
  // exit();
  // deleteFileZip($data_files_to_zip);
  $down = $setLokasiZip;
  //header('location:'.$down);
  header('Content-Description: File Transfer');
  header('Content-Type: application/zip');
  header('Content-Disposition: attachment; filename='.basename($down));
  //header('Content-Disposition: attachment; filename='.$down);
  header('Content-Transfer-Encoding: binary');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
  header('Content-Length: ' . filesize($down));
  readfile($down);
  // unlink($down);
  // rmdir($direktorihapus);

  header('Accept-Ranges: bytes');
  if(readfile($down))
  {
    // unlink($down);
    // rmdir($direktorihapus);
  }*/
}
else
{
  // print_r($data_files_to_zip);exit;
  if($indexZip > 0)
  {
    // $setLokasiZip= "uploadszip/".str_replace("/","_",$tempNomorSuratKeluar)."_".$reqUserLoginId.".zip";
    $setLokasiZip= "uploadszip/".str_replace(" ", "_", str_replace("/","_",$tempNomorSuratKeluar))."_".$reqUserLoginId.".zip";
    // $setLokasiZip= "uploads/".$buatfolderzip.".zip";

    // if(file_exists($setLokasiZip))
    // {
    //   unlink($setLokasiZip);
    // }

    // ob_clean();
    // ob_end_flush(); // more important function - (without - error corrupted zip)

    // $result = create_zip($data_files_to_zip,$setLokasiZip);
    // deleteFileZip($data_files_to_zip);
    $down = $setLokasiZip;
    echo $down;exit;
  }
  else
  echo "0";
}
?>