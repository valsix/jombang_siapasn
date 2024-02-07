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
  
  class Rumpun extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Rumpun()
	{
      $this->Entity(); 
    }

	function insert()
	{
		$this->setField("RUMPUN_ID", $this->getNextId("RUMPUN_ID","talent.rumpun")); 
      
		$str = "
		INSERT INTO talent.rumpun 
		(
			RUMPUN_ID, PERMEN_ID, KODE, NAMA, KETERANGAN
		) 
		VALUES 
		(
			".$this->getField("RUMPUN_ID")."
			, ".$this->getField("PERMEN_ID")."
			, '".$this->getField("KODE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("KETERANGAN")."'
		)
		"; 

		$this->id = $this->getField("RUMPUN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE talent.rumpun
		SET    
		KODE= ".$this->getField("KODE")."
		, NAMA= '".$this->getField("NAMA")."'
		, KETERANGAN= '".$this->getField("KETERANGAN")."'
		WHERE RUMPUN_ID = ".$this->getField("RUMPUN_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function delete()
    {
    	$str = "
    	UPDATE talent.rumpun SET
    	KETERANGAN = '1',
    	LAST_USER= '".$this->getField("LAST_USER")."',
    	LAST_DATE= ".$this->getField("LAST_DATE").",
    	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
    	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
    	WHERE RUMPUN_ID = ".$this->getField("RUMPUN_ID")."
    	";
    	$this->query = $str;
    	return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.RUMPUN_ID ASC ')
	{
		$str = "
		SELECT
			A.*
		FROM talent.rumpun A
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

  } 
?>