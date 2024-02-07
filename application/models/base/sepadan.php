<?
include_once(APPPATH.'/models/Entity.php');
class Sepadan extends Entity{

  var $query;
  function Sepadan()
  {
    $this->Entity(); 
  }

  function selectjabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=" ORDER BY A1.TMT_JABATAN DESC")
  {
      $str = "
      SELECT 
      A.NIP_BARU, A.NAMA_LENGKAP
      , A.INFO_SATUAN_KERJA_NAMA
      , CASE A1.JENIS_JABATAN_ID WHEN '1' THEN 'Jabatan Struktural' WHEN '2' THEN 'Jabatan Fungsional Umum' WHEN '3' THEN 'Jabatan Fungsional Tertentu' END JENIS_JABATAN_NAMA
      , A1.* 
      FROM
      (
        SELECT
        (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
        , A.*
        FROM pegawai A
      ) A
      INNER JOIN jabatan_riwayat A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
      WHERE 1=1
      AND COALESCE(NULLIF(A1.ID_SAPK, ''), NULL) IS NULL
      AND (COALESCE(NULLIF(A1.STATUS, ''), NULL) IS NULL OR A1.STATUS = '2')
      ";
      
      foreach ($paramsArray as $key => $val)
      {
          $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$order;
      $this->query = $str;
      // echo $str;exit;
      return $this->selectLimit($str,$limit,$from); 
  }

  function selectkinerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=" ORDER BY A1.TAHUN DESC NULLS LAST")
  {
      $str = "
      SELECT 
      A.NIP_BARU, A.NAMA_LENGKAP
      , A.INFO_SATUAN_KERJA_NAMA
      , A1.* 
      FROM
      (
        SELECT
        (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
        , A.*
        FROM pegawai A
      ) A
      INNER JOIN penilaian_skp A1 ON A1.TAHUN >= 2022 AND A1.TAHUN <= 2023 AND A.PEGAWAI_ID = A1.PEGAWAI_ID
      WHERE 1=1
      AND COALESCE(NULLIF(A1.ID_SAPK, ''), NULL) IS NULL
      AND (COALESCE(NULLIF(A1.STATUS, ''), NULL) IS NULL OR A1.STATUS = '2')
      ";
      
      foreach ($paramsArray as $key => $val)
      {
          $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$order;
      $this->query = $str;
      return $this->selectLimit($str,$limit,$from); 
  }

  function selectdiklatkursus($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=" ORDER BY A1.TAHUN DESC NULLS LAST")
  {
      $str = "
      SELECT 
      A.NIP_BARU, A.NAMA_LENGKAP
      , A.INFO_SATUAN_KERJA_NAMA, TK.NAMA TIPE_DIKLAT_NAMA
      , A1.* 
      FROM
      (
        SELECT
        (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
        , A.*
        FROM pegawai A
      ) A
      INNER JOIN diklat_kursus A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
      LEFT JOIN tipe_kursus TK ON A1.TIPE_KURSUS_ID = TK.TIPE_KURSUS_ID
      WHERE 1=1
      AND COALESCE(NULLIF(A1.ID_SAPK, ''), NULL) IS NULL
      AND (COALESCE(NULLIF(A1.STATUS, ''), NULL) IS NULL OR A1.STATUS = '2')
      ";
      
      foreach ($paramsArray as $key => $val)
      {
          $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$order;
      $this->query = $str;
      return $this->selectLimit($str,$limit,$from); 
  }

  function selectdiklatstruktural($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=" ORDER BY A1.TAHUN DESC NULLS LAST")
  {
      $str = "
      SELECT 
      A.NIP_BARU, A.NAMA_LENGKAP
      , A.INFO_SATUAN_KERJA_NAMA, B.NAMA DIKLAT_NAMA
      , A1.* 
      FROM
      (
        SELECT
        (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
        , A.*
        FROM pegawai A
      ) A
      INNER JOIN diklat_struktural A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
      INNER JOIN diklat B ON A1.DIKLAT_ID = B.DIKLAT_ID
      WHERE 1=1
      AND COALESCE(NULLIF(A1.ID_SAPK, ''), NULL) IS NULL
      AND (COALESCE(NULLIF(A1.STATUS, ''), NULL) IS NULL OR A1.STATUS = '2')
      ";
      
      foreach ($paramsArray as $key => $val)
      {
          $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$order;
      $this->query = $str;
      return $this->selectLimit($str,$limit,$from); 
  }

  function selecthukuman($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=" ORDER BY A1.TMT_SK DESC")
  {
      $str = "
      SELECT 
      A.NIP_BARU, A.NAMA_LENGKAP
      , A.INFO_SATUAN_KERJA_NAMA, B.NAMA TINGKAT_HUKUMAN_NAMA, C.NAMA JENIS_HUKUMAN_NAMA
      , A1.* 
      FROM
      (
        SELECT
        (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
        , A.*
        FROM pegawai A
      ) A
      INNER JOIN hukuman A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID
      LEFT JOIN tingkat_hukuman B ON B.TINGKAT_HUKUMAN_ID = A1.TINGKAT_HUKUMAN_ID
      LEFT JOIN jenis_hukuman C ON C.JENIS_HUKUMAN_ID = A1.JENIS_HUKUMAN_ID
      WHERE 1=1
      AND COALESCE(NULLIF(A1.ID_SAPK, ''), NULL) IS NULL
      AND (COALESCE(NULLIF(A1.STATUS, ''), NULL) IS NULL OR A1.STATUS = '2')
      ";
      
      foreach ($paramsArray as $key => $val)
      {
          $str .= " AND $key = '$val' ";
      }
      
      $str .= $statement." ".$order;
      $this->query = $str;
      return $this->selectLimit($str,$limit,$from); 
  }

}