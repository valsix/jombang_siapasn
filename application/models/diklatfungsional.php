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
  
  class DiklatFungsional extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function DiklatFungsional()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("DIKLAT_FUNGSIONAL_ID", $this->getNextId("DIKLAT_FUNGSIONAL_ID","DIKLAT_FUNGSIONAL")); 

		$str = "
			INSERT INTO DIKLAT_FUNGSIONAL (
				DIKLAT_FUNGSIONAL_ID, PEGAWAI_ID, TEMPAT, PENYELENGGARA, ANGKATAN, TAHUN, TANGGAL_MULAI, 
				TANGGAL_SELESAI, NO_STTPP, TANGGAL_STTPP, JUMLAH_JAM, NAMA, LAST_USER, LAST_DATE, LAST_LEVEL,
				USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("DIKLAT_FUNGSIONAL_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("TEMPAT")."',
				 '".$this->getField("PENYELENGGARA")."',
				 '".$this->getField("ANGKATAN")."',
				 ".$this->getField("TAHUN").",
				 ".$this->getField("TANGGAL_MULAI").",
				 ".$this->getField("TANGGAL_SELESAI").",
				 '".$this->getField("NO_STTPP")."',
				 ".$this->getField("TANGGAL_STTPP").",
				 ".$this->getField("JUMLAH_JAM").",
				 '".$this->getField("NAMA")."',
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("DIKLAT_FUNGSIONAL_ID");
		$this->query = $str;
		// echo $this->$query;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE DIKLAT_FUNGSIONAL
				SET   
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				 	TEMPAT= '".$this->getField("TEMPAT")."',
				 	PENYELENGGARA= '".$this->getField("PENYELENGGARA")."',
				 	ANGKATAN= '".$this->getField("ANGKATAN")."',
				 	TAHUN= ".$this->getField("TAHUN").",
				 	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				 	TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
				 	NO_STTPP= '".$this->getField("NO_STTPP")."',
				 	TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
				 	JUMLAH_JAM= ".$this->getField("JUMLAH_JAM").",
				 	NAMA= '".$this->getField("NAMA")."',
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  DIKLAT_FUNGSIONAL_ID = ".$this->getField("DIKLAT_FUNGSIONAL_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE DIKLAT_FUNGSIONAL
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  DIKLAT_FUNGSIONAL_ID    	= ".$this->getField("DIKLAT_FUNGSIONAL_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE DIKLAT_FUNGSIONAL SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE DIKLAT_FUNGSIONAL_ID = ".$this->getField("DIKLAT_FUNGSIONAL_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DIKLAT_FUNGSIONAL_ID ASC')
	{
		$str = "
				SELECT 
					A.DIKLAT_FUNGSIONAL_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.ANGKATAN, A.TAHUN, A.TANGGAL_MULAI, 
					A.TANGGAL_SELESAI, A.NO_STTPP, A.TANGGAL_STTPP, A.JUMLAH_JAM, A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS
				FROM DIKLAT_FUNGSIONAL A
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
				SELECT COUNT(A.DIKLAT_FUNGSIONAL_ID) AS ROWCOUNT 
				FROM DIKLAT_FUNGSIONAL A
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