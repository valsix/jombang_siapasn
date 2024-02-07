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
  
  class TekenLog extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function TekenLog()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("TEKEN_LOG_ID", $this->getNextId("TEKEN_LOG_ID", "teken_log"));

		$str = "
		INSERT INTO teken_log
		(
			TEKEN_LOG_ID, JENIS, IP_ADDRESS, USER_AGENT, KETERANGAN, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES 
		(
			".$this->getField("TEKEN_LOG_ID")."
			, '".$this->getField("JENIS")."'
			, '".$this->getField("IP_ADDRESS")."'
			, '".$this->getField("USER_AGENT")."'
			, '".$this->getField("KETERANGAN")."'
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->query = $str;
		// echo $str;;exit();
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE DESC')
	{
		$str = "
		SELECT 
		A.*
		FROM teken_log A
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
		FROM teken_log A
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