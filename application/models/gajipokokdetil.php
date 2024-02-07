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
  
  class GajiPokokDetil extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function GajiPokokDetil()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("GAJI_POKOK_DETIL_ID", $this->getNextId("GAJI_POKOK_DETIL_ID","GAJI_POKOK_DETIL")); 
      
		$str = "
			INSERT INTO GAJI_POKOK_DETIL (
				GAJI_POKOK_DETIL_ID, GAJI_POKOK_ID, GAJI, TANGGAL_AWAL, TANGGAL_AKHIR, STATUS, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("GAJI_POKOK_DETIL_ID").",
				".$this->getField("GAJI_POKOK_ID").",
				".$this->getField("GAJI").",
				".$this->getField("TANGGAL_AWAL").",
				".$this->getField("TANGGAL_AKHIR").",
				".$this->getField("STATUS").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."				
			)
		"; 	
		$this->id = $this->getField("GAJI_POKOK_DETIL_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_POKOK_DETIL
				SET    
					GAJI_POKOK_ID= ".$this->getField("GAJI_POKOK_ID").",
					GAJI= ".$this->getField("GAJI").",
					TANGGAL_AWAL= ".$this->getField("TANGGAL_AWAL").",
					TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."					
				WHERE  GAJI_POKOK_DETIL_ID = ".$this->getField("GAJI_POKOK_DETIL_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_POKOK_DETIL
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  GAJI_POKOK_DETIL_ID  = ".$this->getField("GAJI_POKOK_DETIL_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM GAJI_POKOK_DETIL
            	WHERE GAJI_POKOK_DETIL_ID = ".$this->getField("GAJI_POKOK_DETIL_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GAJI_POKOK_DETIL_ID ASC')
	{
		$str = "
				SELECT A.GAJI_POKOK_DETIL_ID, A.GAJI_POKOK_ID, A.GAJI, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.STATUS, A.LAST_USER, A.LAST_DATE
				FROM GAJI_POKOK_DETIL A
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
				SELECT COUNT(A.GAJI_POKOK_DETIL_ID) AS ROWCOUNT 
				FROM GAJI_POKOK_DETIL A
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