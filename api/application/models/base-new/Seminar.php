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
  
  class Seminar extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Seminar()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.SEMINAR_ID ASC')
	{
		$str = "
		SELECT 	
		A.SEMINAR_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
		A.NO_PIAGAM, A.TANGGAL_PIAGAM, A.STATUS,A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM SEMINAR A
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
		$str = "SELECT COUNT(A.SEMINAR_ID) AS ROWCOUNT 
				FROM SEMINAR A
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