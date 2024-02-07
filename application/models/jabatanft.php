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
  
  class JabatanFt extends Entity{ 

  	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function JabatanFt()
    {
    	$this->Entity(); 
    }
    
    function insert()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$this->setField("JABATAN_FT_ID", $this->getNextId("JABATAN_FT_ID","JABATAN_FT")); 

    	$str = "
    	INSERT INTO JABATAN_FT (
    	JABATAN_FT_ID, JABATAN_FT_PARENT_ID, JFT_ID, JFT_ID_PARENT, NAMA, ID_DATA, PANGKAT_ID_MIN, PANGKAT_ID_MAX, BUP, TIPE_PEGAWAI_ID, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
    	) 
    	VALUES (
    	".$this->getField("JABATAN_FT_ID").",
    	".$this->getField("JABATAN_FT_PARENT_ID").",
    	".$this->getField("JFT_ID").",
    	".$this->getField("JFT_ID_PARENT").",
    	'".$this->getField("NAMA")."',
    	'".$this->getField("ID_DATA")."',
    	".$this->getField("PANGKAT_ID_MIN").",
    	".$this->getField("PANGKAT_ID_MAX").",
    	".$this->getField("BUP").",
    	".$this->getField("TIPE_PEGAWAI_ID").",
    	'".$this->getField("LAST_USER")."',
    	".$this->getField("LAST_DATE").",
    	".$this->getField("USER_LOGIN_ID").",
    	".$this->getField("USER_LOGIN_PEGAWAI_ID")."
    )
    "; 	
    $this->id = $this->getField("JABATAN_FT_ID");
    $this->query = $str;
		// echo $this->$query;exit;
    return $this->execQuery($str);
}


function update()
{
	/*Auto-generate primary key(s) by next max value (integer) */
	$str = "		
	UPDATE JABATAN_FT
	SET    
	JABATAN_FT_PARENT_ID= ".$this->getField("JABATAN_FT_PARENT_ID").",
	NAMA= '".$this->getField("NAMA")."',
	ID_DATA= '".$this->getField("ID_DATA")."',
	PANGKAT_ID_MIN= ".$this->getField("PANGKAT_ID_MIN").",
	PANGKAT_ID_MAX= ".$this->getField("PANGKAT_ID_MAX").",
	BUP= ".$this->getField("BUP").",
	LAST_USER= '".$this->getField("LAST_USER")."',
	LAST_DATE= ".$this->getField("LAST_DATE").",
	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	WHERE  JABATAN_FT_ID = ".$this->getField("JABATAN_FT_ID")."
	"; 
	$this->query = $str;
		// echo $str;exit;
	return $this->execQuery($str);
}

function updateStatus()
{
	/*Auto-generate primary key(s) by next max value (integer) */
	$str = "		
	UPDATE JABATAN_FT
	SET    
	STATUS   	= ".$this->getField("STATUS").",
	LAST_USER	= '".$this->getField("LAST_USER")."',
	LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
	LAST_DATE	= ".$this->getField("LAST_DATE").",
	USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
	USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	WHERE  JABATAN_FT_ID    	= ".$this->getField("JABATAN_FT_ID")."
	"; 
	$this->query = $str;
		// echo $str;exit;
	return $this->execQuery($str);
}

function delete()
{
	$str = "
	UPDATE JABATAN_FT SET
	STATUS = '1',
	LAST_USER= '".$this->getField("LAST_USER")."',
	LAST_DATE= ".$this->getField("LAST_DATE").",
	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	WHERE JABATAN_FT_ID = ".$this->getField("JABATAN_FT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JABATAN_FT_ID ASC')
    {
    	$str = "
    	SELECT 
    	A.*
    	FROM JABATAN_FT A
    	WHERE 1 = 1
    	"; 
    	
    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	
    	$str .= $statement." ".$order;
    	$this->query = $str;
        // echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    	
    }
    
    function getCountByParams($paramsArray=array(), $statement='')
    {
    	$str = "
    	SELECT COUNT(A.JABATAN_FT_ID) AS ROWCOUNT 
    	FROM JABATAN_FT A
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