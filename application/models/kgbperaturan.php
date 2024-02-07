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
  
  class KgbPeraturan extends Entity{ 

  	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function KgbPeraturan()
    {
    	$this->Entity(); 
    }

    function insert()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$this->setField("KGB_PERATURAN_ID", $this->getNextId("KGB_PERATURAN_ID","KGB_PERATURAN"));

    	$str = "
    	INSERT INTO KGB_PERATURAN (
    	KGB_PERATURAN_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NAMA, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
    	) 
    	VALUES (
    	".$this->getField("KGB_PERATURAN_ID").",
    	".$this->getField("MULAI_BERLAKU").",
    	".$this->getField("AKHIR_BERLAKU").",
    	'".$this->getField("NAMA")."',
        '".$this->getField("USER_LOGIN_ID")."',
        ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
    	"; 	
    	$this->id = $this->getField("KGB_PERATURAN_ID");
    	$this->query = $str;
		//echo $str;exit;
    	return $this->execQuery($str);
    }


    function update()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$str = "		
    	UPDATE KGB_PERATURAN
    	SET    
    	MULAI_BERLAKU= ".$this->getField("MULAI_BERLAKU").",
    	AKHIR_BERLAKU= ".$this->getField("AKHIR_BERLAKU").",
    	NAMA= '".$this->getField("NAMA")."',
        USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
    	WHERE KGB_PERATURAN_ID = ".$this->getField("KGB_PERATURAN_ID")."
    	"; 
    	$this->query = $str;
        // echo $str;exit();
    	return $this->execQuery($str);
    }

    function updateStatus()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$str = "		
    	UPDATE KGB_PERATURAN
    	SET    
    	STATUS   	= ".$this->getField("STATUS").",
    	LAST_USER	= '".$this->getField("LAST_USER")."',
        USER_LOGIN_ID'".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID".$this->getField("USER_LOGIN_PEGAWAI_ID").",
    	LAST_DATE	= ".$this->getField("LAST_DATE")."
    	WHERE  KGB_PERATURAN_ID    	= ".$this->getField("KGB_PERATURAN_ID")."
    	"; 
    	$this->query = $str;
    	return $this->execQuery($str);
    }

    function delete()
    {
        /*$str = "
		DELETE FROM KGB_PERATURAN
    	WHERE KGB_PERATURAN_ID = ".$this->getField("KGB_PERATURAN_ID")."
    	";*/ 
    	$str = "
    	UPDATE KGB_PERATURAN SET
    	STATUS = '1',
    	LAST_USER= '".$this->getField("LAST_USER")."',
        USER_LOGIN_ID'".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID".$this->getField("USER_LOGIN_PEGAWAI_ID").",
        LAST_DATE= ".$this->getField("LAST_DATE")."
        WHERE KGB_PERATURAN_ID = ".$this->getField("KGB_PERATURAN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.KGB_PERATURAN_ID ASC')
    {
    	$str = "
    	SELECT 
    	A.KGB_PERATURAN_ID, A.MULAI_BERLAKU, A.AKHIR_BERLAKU, A.NAMA
    	FROM KGB_PERATURAN A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.KGB_PERATURAN_ID ASC')
    {
    	$str = "
    	SELECT A.KGB_PERATURAN_ID, A.MULAI_BERLAKU, A.AKHIR_BERLAKU, A.NAMA
    	FROM KGB_PERATURAN A
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
    	SELECT COUNT(A.KGB_PERATURAN_ID) AS ROWCOUNT 
    	FROM KGB_PERATURAN A
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