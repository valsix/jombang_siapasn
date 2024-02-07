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
  
  class RefPenghargaan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function RefPenghargaan()
	{
      $this->Entity(); 
    }

    function selectbyparams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.REF_PENGHARGAAN_ID ASC')
    {
    	$str = "
    	SELECT 
    	*
    	FROM sapk.ref_penghargaan A
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
	 
    function getcountbyparams($paramsArray=array(), $statement='')
    {
    	$str = "
    	SELECT COUNT(1) AS ROWCOUNT 
    	FROM  sapk.ref_penghargaan A
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