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
  
  class Saudara extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Saudara()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.SAUDARA_ID ASC')
	{
		$str = "
		SELECT
		A.SAUDARA_ID, A.PEGAWAI_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, A.PEKERJAAN, 
		A.ALAMAT, A.KODEPOS, A.TELEPON, A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID, A.KELURAHAN_ID,
		A.STATUS_HIDUP, A.STATUS_SDR,  A.STATUS,
		CASE A.STATUS_HIDUP WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' END STATUS_HIDUP_NAMA,
		CASE A.STATUS_SDR WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' WHEN '3' THEN 'Angkat' END STATUS_SDR_NAMA,
		A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM SAUDARA A
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.SAUDARA_ID) AS ROWCOUNT 
				FROM SAUDARA A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>