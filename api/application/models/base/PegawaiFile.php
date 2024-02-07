<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class PegawaiFile extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PegawaiFile()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $pegawaiid="", $order=' ORDER BY A.PEGAWAI_FILE_ID ASC')
	{
		$str = "
		SELECT 
		A.PEGAWAI_FILE_ID, A.PEGAWAI_ID, A.RIWAYAT_TABLE, A.RIWAYAT_FIELD, A.RIWAYAT_ID
		, A.FILE_KUALITAS_ID, B.NAMA FILE_KUALITAS_NAMA, A.PATH, A.PATH_ASLI,
		A.STATUS_VERIFIKASI, A.KETERANGAN, A.STATUS
		, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
		, XX.NAMA_ROW INFO_GROUP_DATA
		, A.KATEGORI_FILE_ID
		FROM PEGAWAI_FILE A
		LEFT JOIN KUALITAS_FILE B ON A.FILE_KUALITAS_ID = B.KUALITAS_FILE_ID
		LEFT JOIN
		(
			SELECT CONCAT(CAST(A.NO_URUT AS TEXT),'-',CAST(A.PEGAWAI_ID AS TEXT),'-',CAST(A.RIWAYAT_TABLE AS TEXT),'-',CAST(A.RIWAYAT_FIELD AS TEXT),'-',CAST(A.RIWAYAT_ID AS TEXT)) ID_ROW,
			CASE WHEN B.URUT = 2 OR B.URUT = 3 THEN B.NAMA ELSE CONCAT(B.NAMA,'-',A.INFO_DATA) END NAMA_ROW
			FROM RIWAYAT_FILE A
			INNER JOIN KATEGORI_FILE B ON A.NO_URUT = B.KATEGORI_FILE_ID
			WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiid."
		) XX ON CONCAT(CAST(A.KATEGORI_FILE_ID AS TEXT),'-',CAST(A.PEGAWAI_ID AS TEXT),'-',CAST(A.RIWAYAT_TABLE AS TEXT),'-',CAST(A.RIWAYAT_FIELD AS TEXT),'-',CAST(A.RIWAYAT_ID AS TEXT)) = XX.ID_ROW
		WHERE 1=1 AND A.PEGAWAI_ID = ".$pegawaiid."
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsLastRiwayatTable($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
		A.PEGAWAI_FILE_ID, A.PEGAWAI_ID, A.RIWAYAT_TABLE, A.RIWAYAT_FIELD, A.RIWAYAT_ID, 
		A.FILE_KUALITAS_ID, A.PATH, A.STATUS_VERIFIKASI, A.KETERANGAN, A.STATUS, 
		A.KATEGORI_FILE_ID, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		, SPLIT_PART(A.PATH, '/', 3) PATH_NAMA
		FROM PEGAWAI_FILE A
		INNER JOIN
		(
			SELECT
			PEGAWAI_ID, RIWAYAT_TABLE, RIWAYAT_ID
			FROM PEGAWAI_FILE A
			WHERE 1=1
			GROUP BY PEGAWAI_ID, RIWAYAT_TABLE, RIWAYAT_ID
		) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.RIWAYAT_TABLE = B.RIWAYAT_TABLE AND A.RIWAYAT_ID = B.RIWAYAT_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>