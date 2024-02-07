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
  
  class SessionLog extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function SessionLog()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$str = "
		INSERT INTO SESSION_LOG 
		(
			IP_ADDRESS, KETERANGAN, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES (
			'".$this->getField("IP_ADDRESS")."',
			'".$this->getField("KETERANGAN")."',
			'".$this->getField("LAST_USER")."',
			".$this->getField("LAST_DATE").",
			".$this->getField("USER_LOGIN_ID").",
			".$this->getField("USER_LOGIN_PEGAWAI_ID")."
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
		IP_ADDRESS, KETERANGAN, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		FROM SESSION_LOG A
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
				FROM SESSION_LOG A
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