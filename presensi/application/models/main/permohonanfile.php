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

class PermohonanFile extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function PermohonanFile()
	{
	  $this->Entity(); 
	}

	function insert()
	{
		$this->setField("PERMOHONAN_FILE_ID", $this->getNextId("PERMOHONAN_FILE_ID","presensi.PERMOHONAN_FILE"));

		$str= "
		INSERT INTO presensi.PERMOHONAN_FILE
		(
			PERMOHONAN_FILE_ID, PEGAWAI_ID, PERMOHONAN_TABLE_NAMA, PERMOHONAN_TABLE_ID, 
			NAMA, TIPE, LINK_FILE, KETERANGAN, LAST_USER, LAST_DATE, 
			USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_DATE, USER_LOGIN_CREATE_ID
		) 
		VALUES 
		( 
			".$this->getField("PERMOHONAN_FILE_ID").",
			'".$this->getField("PEGAWAI_ID")."',
			'".$this->getField("PERMOHONAN_TABLE_NAMA")."',
			".$this->getField("PERMOHONAN_TABLE_ID").",
			'".$this->getField("NAMA")."',
			'".$this->getField("TIPE")."',
			'".$this->getField("LINK_FILE")."',
			'".$this->getField("KETERANGAN")."',
			'".$this->getField("LAST_USER")."',
			NOW(),
			".$this->getField("USER_LOGIN_ID").",
			".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			NOW(),
			".$this->getField("USER_LOGIN_CREATE_ID")."
		)
		"; 
		$this->id= $this->getField("PERMOHONAN_FILE_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function delete()
	{
        $str= "
        DELETE FROM presensi.PERMOHONAN_FILE
        WHERE 
        PERMOHONAN_FILE_ID= ".$this->getField("PERMOHONAN_FILE_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_FILE_ID DESC")
	{
		$str = "
		SELECT
			PERMOHONAN_FILE_ID, PEGAWAI_ID, PERMOHONAN_TABLE_NAMA, PERMOHONAN_TABLE_ID, 
			NAMA, TIPE, LINK_FILE, KETERANGAN, STATUS, LAST_USER, LAST_DATE, 
			USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_DATE, USER_LOGIN_CREATE_ID
			FROM presensi.PERMOHONAN_FILE A
		WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM presensi.PERMOHONAN_FILE A
		WHERE 1=1 ".$statement; 
		
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