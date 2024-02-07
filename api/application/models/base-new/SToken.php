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
  
  class SToken extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SToken()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$str = "
			INSERT INTO s_token
			(
				VKEY, VTOKEN
			) 
			VALUES 
			(
				'".$this->getField("VKEY")."'
				, '".$this->getField("VTOKEN")."'
			)
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE s_token
		SET
		VTOKEN= '".$this->getField("VTOKEN")."'
		WHERE VKEY = '".$this->getField("VKEY")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT 
			A.*
		FROM s_token A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }

  } 
?>