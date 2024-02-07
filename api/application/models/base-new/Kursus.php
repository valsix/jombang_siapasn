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
  
  class Kursus extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Kursus()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.KURSUS_ID ASC')
	{
		$str = "
		SELECT 	
		A.KURSUS_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.TANGGAL_SELESAI, A.TANGGAL_MULAI, A.NO_PIAGAM, A.STATUS, 
		A.TANGGAL_PIAGAM, A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM KURSUS A
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
		$str = "SELECT COUNT(A.KURSUS_ID) AS ROWCOUNT 
				FROM KURSUS A
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