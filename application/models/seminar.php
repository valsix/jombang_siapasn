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
  
  class Seminar extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Seminar()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SEMINAR_ID", $this->getNextId("SEMINAR_ID","SEMINAR"));
     	$str = "
			INSERT INTO SEMINAR (
				SEMINAR_ID, PEGAWAI_ID, TEMPAT, PENYELENGGARA, TANGGAL_MULAI, TANGGAL_SELESAI, NO_PIAGAM, TANGGAL_PIAGAM, 
				NAMA, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("SEMINAR_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("TEMPAT")."',
				  '".$this->getField("PENYELENGGARA")."',
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_SELESAI").",
				  '".$this->getField("NO_PIAGAM")."',
				  ".$this->getField("TANGGAL_PIAGAM").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SEMINAR_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SEMINAR
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	TEMPAT= '".$this->getField("TEMPAT")."',
				  	PENYELENGGARA= '".$this->getField("PENYELENGGARA")."',
				  	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				  	TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
				  	NO_PIAGAM= '".$this->getField("NO_PIAGAM")."',
				  	TANGGAL_PIAGAM= ".$this->getField("TANGGAL_PIAGAM").",
				  	NAMA= '".$this->getField("NAMA")."',
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  SEMINAR_ID = ".$this->getField("SEMINAR_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SEMINAR
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  SEMINAR_ID    	= ".$this->getField("SEMINAR_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SEMINAR SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SEMINAR_ID = ".$this->getField("SEMINAR_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SEMINAR_ID ASC')
	{
		$str = "
				SELECT 	
					A.SEMINAR_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
					A.NO_PIAGAM, A.TANGGAL_PIAGAM, A.STATUS,A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				FROM SEMINAR A
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
				SELECT COUNT(A.SEMINAR_ID) AS ROWCOUNT 
				FROM SEMINAR A
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