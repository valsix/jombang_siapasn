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
  
  class NilaiKuadran extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function NilaiKuadran()
	{
      $this->Entity(); 
  }
	
			    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.NILAI_KUADRAN_ID ASC")
			    {
			    	$str = "
			    	SELECT  A.NILAI_KUADRAN_ID, A.KODE, A.NAMA, A.KETERANGAN
			    	FROM NILAI_KUADRAN A
			    	WHERE 1=1 ";
			    	while(list($key,$val) = each($paramsArray))
			    	{
			    		$str .= " AND $key = '$val'";
			    	}

			    	$str .= $statement." ".$order;
			    	$this->query = $str;
			    	return $this->selectLimit($str,$limit,$from); 
			    }

			    function getCountByParams($paramsArray=array(), $statement="")
			    {
			    	$str = "SELECT COUNT(1) AS ROWCOUNT FROM NILAI_KUADRAN A WHERE 1=1 ".$statement;
			    	while(list($key,$val)=each($paramsArray))
			    	{
			    		$str .= " AND $key = 	'$val' ";
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