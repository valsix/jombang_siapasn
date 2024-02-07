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
  
  class Kursus extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Kursus()
	{
      $this->Entity(); 
    }
 
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KURSUS_ID", $this->getNextId("KURSUS_ID","KURSUS"));
     	$str = "
			INSERT INTO KURSUS (
				KURSUS_ID, PEGAWAI_ID, TEMPAT, PENYELENGGARA, TANGGAL_SELESAI, TANGGAL_MULAI, NO_PIAGAM, TANGGAL_PIAGAM, 
				NAMA, LAST_USER, LAST_DATE, LAST_LEVEL, STATUS, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("KURSUS_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("TEMPAT")."',
				  '".$this->getField("PENYELENGGARA")."',
				  ".$this->getField("TANGGAL_SELESAI").",
				  ".$this->getField("TANGGAL_MULAI").",
				  '".$this->getField("NO_PIAGAM")."',
				  ".$this->getField("TANGGAL_PIAGAM").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("STATUS").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("KURSUS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE KURSUS
				SET    
				   	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	TEMPAT= '".$this->getField("TEMPAT")."',
				  	PENYELENGGARA= '".$this->getField("PENYELENGGARA")."',
				  	TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
				  	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				  	NO_PIAGAM= '".$this->getField("NO_PIAGAM")."',
				  	TANGGAL_PIAGAM= ".$this->getField("TANGGAL_PIAGAM").",
				  	NAMA= '".$this->getField("NAMA")."',
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  KURSUS_ID = ".$this->getField("KURSUS_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE KURSUS
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  KURSUS_ID    	= ".$this->getField("KURSUS_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE KURSUS SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE KURSUS_ID = ".$this->getField("KURSUS_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.KURSUS_ID ASC')
	{
		$str = "
				SELECT 	
					A.KURSUS_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.TANGGAL_SELESAI, A.TANGGAL_MULAI, A.NO_PIAGAM, A.STATUS, 
					A.TANGGAL_PIAGAM, A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				FROM KURSUS A
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
				SELECT COUNT(A.KURSUS_ID) AS ROWCOUNT 
				FROM KURSUS A
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