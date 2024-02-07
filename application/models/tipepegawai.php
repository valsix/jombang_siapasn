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
  
  class TipePegawai extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function TipePegawai()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TIPE_PEGAWAI_ID", $this->getNextId("TIPE_PEGAWAI_ID","TIPE_PEGAWAI"));

     	$str = "
			INSERT INTO TIPE_PEGAWAI (
				TIPE_PEGAWAI_ID, TIPE_PEGAWAI_PARENT_ID, KODE, NAMA, NO_URUT, LAST_USER, LAST_DATE, 
				STATUS_AMBIL_DATA, STATUS_TAMPIL_JABATAN, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("TIPE_PEGAWAI_ID").",
				".$this->getField("TIPE_PEGAWAI_PARENT_ID").",
				'".$this->getField("KODE")."',
				'".$this->getField("NAMA")."',
				".$this->getField("NO_URUT").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				'".$this->getField("STATUS_AMBIL_DATA")."',
				'".$this->getField("STATUS_TAMPIL_JABATAN")."',
				'".$this->getField("USER_LOGIN_ID")."',
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
	
		$this->id = $this->getField("TIPE_PEGAWAI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE TIPE_PEGAWAI
				SET    
			    	TIPE_PEGAWAI_ID= ".$this->getField("TIPE_PEGAWAI_ID").",
					TIPE_PEGAWAI_PARENT_ID= ".$this->getField("TIPE_PEGAWAI_PARENT_ID").",
					KODE= '".$this->getField("KODE")."',
					NAMA= '".$this->getField("NAMA")."',
					NO_URUT= ".$this->getField("NO_URUT").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					STATUS_AMBIL_DATA= '".$this->getField("STATUS_AMBIL_DATA")."',
					USER_LOGIN_PEGAWAI_ID=".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					USER_LOGIN_ID='".$this->getField("USER_LOGIN_ID")."',
					STATUS_TAMPIL_JABATAN= '".$this->getField("STATUS_TAMPIL_JABATAN")."'
				WHERE  TIPE_PEGAWAI_ID = ".$this->getField("TIPE_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE TIPE_PEGAWAI
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					    USER_LOGIN_PEGAWAI_ID=".$this->getField("USER_LOGIN_PEGAWAI_ID").",
						USER_LOGIN_ID='".$this->getField("USER_LOGIN_ID")."',
				WHERE  TIPE_PEGAWAI_ID    	= ".$this->getField("TIPE_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE TIPE_PEGAWAI SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_PEGAWAI_ID=".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					USER_LOGIN_ID='".$this->getField("USER_LOGIN_ID")."',
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE TIPE_PEGAWAI_ID = ".$this->getField("TIPE_PEGAWAI_ID")."
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
    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TIPE_PEGAWAI_ID ASC')
	{
		$str = "
				SELECT 	
					A.TIPE_PEGAWAI_ID, A.TIPE_PEGAWAI_PARENT_ID, A.KODE, A.NAMA, A.NO_URUT, A.LAST_USER, A.LAST_DATE, A.STATUS, 
					A.STATUS_AMBIL_DATA, A.STATUS_TAMPIL_JABATAN
				FROM TIPE_PEGAWAI A
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
				SELECT COUNT(A.TIPE_PEGAWAI_ID) AS ROWCOUNT 
				FROM TIPE_PEGAWAI A
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