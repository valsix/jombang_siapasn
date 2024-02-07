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
  
  class JabatanFu extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function JabatanFu()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_FU_ID", $this->getNextId("JABATAN_FU_ID","JABATAN_FU")); 

		$str = "
			INSERT INTO JABATAN_FU (
				JABATAN_FU_ID, NAMA, ID_DATA, BIDANG_JABATAN, TIPE_PEGAWAI_ID, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("JABATAN_FU_ID").",
				 '".$this->getField("NAMA")."',
				 '".$this->getField("ID_DATA")."',
				 '".$this->getField("BIDANG_JABATAN")."',
				 '".$this->getField("TIPE_PEGAWAI_ID")."',
				 '".$this->getField("USER_LOGIN_ID")."',
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("JABATAN_FU_ID");
		$this->query = $str;
		// echo $this->$query;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE JABATAN_FU
				SET    
				 	NAMA= '".$this->getField("NAMA")."',
				 	ID_DATA= '".$this->getField("ID_DATA")."',
				 	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',				 	
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	BIDANG_JABATAN= '".$this->getField("BIDANG_JABATAN")."'
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE JABATAN_FU
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_FU_ID    	= ".$this->getField("JABATAN_FU_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE JABATAN_FU SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE JABATAN_FU_ID = ".$this->getField("JABATAN_FU_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY LENGTH(A.NAMA), A.NAMA')
	{
		$str = "
				SELECT 
					A.JABATAN_FU_ID, A.NAMA, A.ID_DATA, A.BIDANG_JABATAN
				FROM JABATAN_FU A
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
				SELECT COUNT(A.JABATAN_FU_ID) AS ROWCOUNT 
				FROM JABATAN_FU A
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