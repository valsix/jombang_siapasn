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
  
  class JenisIdDokumen extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisIdDokumen()
	{
      $this->Entity(); 
    }

    function selectbyparams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ID ASC')
    {
    	$str = "
    	SELECT 
    	*
    	FROM sapk.jenis_id_dokumen A
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
    	FROM  sapk.jenis_id_dokumen A
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