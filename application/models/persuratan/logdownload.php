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
  include_once(APPPATH.'/models/Entity.php');
  
  class LogDownload extends Entity{ 

  	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function LogDownload()
	{
      $this->Entity(); 
    }

	function insert()
	{
     	$str = "
			INSERT INTO persuratan.log_download
			(
				LAST_USER, INFO_MODE, LAST_CREATE_DATE, STATUS
			)
			VALUES 
			(
				'".$this->getField("LAST_USER")."'
				, '".$this->getField("INFO_MODE")."'
				, NOW()
				, 1
			)
		"; 	
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatemulai()
	{
		$str = "		
		UPDATE persuratan.log_download
		SET
			LAST_CREATE_DATE= NOW()
			, STATUS= 1
		WHERE LAST_USER = '".$this->getField("LAST_USER")."'
		AND INFO_MODE = '".$this->getField("INFO_MODE")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	function updateselesai()
	{
		$str = "		
		UPDATE persuratan.log_download
		SET
			LAST_UPDATE_DATE= NOW()
			, INFO_LINK= '".$this->getField("INFO_LINK")."'
			, STATUS= 2
		WHERE LAST_USER = '".$this->getField("LAST_USER")."'
		AND INFO_MODE = '".$this->getField("INFO_MODE")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        DELETE FROM persuratan.log_download
        WHERE LAST_USER = '".$this->getField("LAST_USER")."'
        AND INFO_MODE = '".$this->getField("INFO_MODE")."'
        ";
		$this->query = $str;
		return $this->execQuery($str);
    }
    
	function selectparams($paramsArray=array(), $limit=-1, $from=-1, $statement='')
	{
		$str = "	
		SELECT
			A.*
		FROM persuratan.log_download A
		WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
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