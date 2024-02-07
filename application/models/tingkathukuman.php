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
  
  class TingkatHukuman extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function TingkatHukuman()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TINGKAT_HUKUMAN_ID", $this->getNextId("TINGKAT_HUKUMAN_ID", "TINGKAT_HUKUMAN"));

     	$str = "
     	INSERT INTO TINGKAT_HUKUMAN 
     	(
     		TINGKAT_HUKUMAN_ID, PER_PERATURAN_ID, NAMA, PERATURAN_ID
     		, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
     	) 
     	VALUES 
     	(
	     	".$this->getField("TINGKAT_HUKUMAN_ID")."
	     	, ".$this->getField("PER_PERATURAN_ID")."
	     	, '".$this->getField("NAMA")."'
	     	, ".$this->getField("PERATURAN_ID")."
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, '".$this->getField("USER_LOGIN_ID")."'
	     	, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
     	)
		"; 	
		$this->id = $this->getField("TINGKAT_HUKUMAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		$str = "		
		UPDATE TINGKAT_HUKUMAN
		SET    
			PER_PERATURAN_ID= ".$this->getField("PER_PERATURAN_ID")."
			, NAMA= '".$this->getField("NAMA")."'
			, PERATURAN_ID= ".$this->getField("PERATURAN_ID")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."'
		WHERE  TINGKAT_HUKUMAN_ID = ".$this->getField("TINGKAT_HUKUMAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		// echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
		UPDATE TINGKAT_HUKUMAN
		SET    
			STATUS= ".$this->getField("STATUS")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."'
		WHERE  TINGKAT_HUKUMAN_ID= ".$this->getField("TINGKAT_HUKUMAN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        UPDATE TINGKAT_HUKUMAN
        SET
	        STATUS = '1'
	        , LAST_USER= '".$this->getField("LAST_USER")."'
	        , LAST_DATE= ".$this->getField("LAST_DATE")."
	        , USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	        , USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."'
        WHERE TINGKAT_HUKUMAN_ID = ".$this->getField("TINGKAT_HUKUMAN_ID")."
        ";
		$this->query = $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectperaturan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PERATURAN_ID ASC')
	{
		$str = "
		SELECT 	
		A.*
		FROM PERATURAN A
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TINGKAT_HUKUMAN_ID ASC')
	{
		$str = "
		SELECT 	
		A.*
		FROM TINGKAT_HUKUMAN A
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
				SELECT COUNT(A.TINGKAT_HUKUMAN_ID) AS ROWCOUNT 
				FROM TINGKAT_HUKUMAN A
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