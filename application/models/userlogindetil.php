
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
  
  class UserLoginDetil extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserLoginDetil()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_DETIL_ID", $this->getNextId("USER_LOGIN_DETIL_ID","USER_LOGIN_DETIL")); 
      
		$str = "
			INSERT INTO USER_LOGIN_DETIL (
				USER_LOGIN_DETIL_ID, USER_LOGIN_ID, PEGAWAI_ID, TANGGAL_AWAL, TANGGAL_AKHIR, LAST_USER, LAST_DATE
			) 
			VALUES (
				".$this->getField("USER_LOGIN_DETIL_ID").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("TANGGAL_AWAL").",
				".$this->getField("TANGGAL_AKHIR").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE")."
			)
		"; 	
		$this->id = $this->getField("USER_LOGIN_DETIL_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE USER_LOGIN_DETIL
				SET    
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					TANGGAL_AWAL= ".$this->getField("TANGGAL_AWAL").",
					TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  USER_LOGIN_DETIL_ID = ".$this->getField("USER_LOGIN_DETIL_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE USER_LOGIN_DETIL
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  USER_LOGIN_DETIL_ID  = ".$this->getField("USER_LOGIN_DETIL_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM USER_LOGIN_DETIL
            	WHERE USER_LOGIN_DETIL_ID = ".$this->getField("USER_LOGIN_DETIL_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USER_LOGIN_DETIL_ID ASC')
	{
		$str = "
				SELECT A.USER_LOGIN_DETIL_ID, A.USER_LOGIN_ID, A.PEGAWAI_ID, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.STATUS, A.LAST_USER, A.LAST_DATE
				FROM USER_LOGIN_DETIL A
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USER_LOGIN_DETIL_ID ASC')
	{
		$str = "
				SELECT A.USER_LOGIN_DETIL_ID, A.USER_LOGIN_ID, A.PEGAWAI_ID, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.STATUS, A.LAST_USER, A.LAST_DATE, NIP_BARU, NAMA PEGAWAI_NAMA
				FROM USER_LOGIN_DETIL A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.USER_LOGIN_DETIL_ID) AS ROWCOUNT 
				FROM USER_LOGIN_DETIL A
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