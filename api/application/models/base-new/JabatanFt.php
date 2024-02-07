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
  
  class JabatanFt extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JabatanFt()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JABATAN_FT_ID ASC')
    {
    	$str = "
    	SELECT 
    	A.JABATAN_FT_ID, A.JABATAN_FT_PARENT_ID, A.NAMA, A.ID_DATA, A.PANGKAT_ID_MIN, A.PANGKAT_ID_MAX, A.BUP, A.LAST_USER, A.LAST_DATE
    	FROM JABATAN_FT A
    	WHERE 1 = 1
    	"; 
    	
    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	
    	$str .= $statement." ".$order;
    	$this->query = $str;
    	return $this->selectLimit($str,$limit,$from); 
    	
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
    {
    	$str = "
    	SELECT COUNT(A.JABATAN_FT_ID) AS ROWCOUNT 
    	FROM JABATAN_FT A
    	WHERE 1 = 1 ".$statement; 
    	while(list($key,$val)=each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	$this->query = $str;
    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return 0;  
    }

 

  } 
?>