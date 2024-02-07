<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class DiklatLog extends Entity{ 

  	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function DiklatLog()
    {
    	$this->Entity(); 
    }


    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DIKLAT_ID ASC')
    {
    	$str = "
    	SELECT A.DIKLAT_ID, A.INFO_LOG, A.LAST_USER, A.LAST_DATE, 
    	CASE A.STATUS WHEN '1' THEN 'Insert' WHEN '2' THEN 'Update' ELSE 'Delete' END STATUS_NAMA
    	FROM DIKLAT_LOG A
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
    	SELECT COUNT(A.DIKLAT_ID) AS ROWCOUNT 
    	FROM ESELON A
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