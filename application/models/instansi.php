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
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class Instansi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Instansi()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.REF_INSTANSI_ID ASC')
    {
    	$str = "
    	SELECT 
    	*
    	FROM sapk.ref_instansi A
    	WHERE 1 = 1
    	"; 
    	
    	foreach ($paramsArray as $key => $val)
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
    	SELECT COUNT(1) AS ROWCOUNT 
    	FROM  sapk.ref_instansi A
    	WHERE 1 = 1 ".$statement; 
    	foreach ($paramsArray as $key => $val)
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