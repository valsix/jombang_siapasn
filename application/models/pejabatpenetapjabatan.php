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
  
  class PejabatPenetapJabatan extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PejabatPenetapJabatan()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEJABAT_PENETAP_JABATAN_ID", $this->getNextId("PEJABAT_PENETAP_JABATAN_ID","PEJABAT_PENETAP_JABATAN")); 
     	$str = "
			INSERT INTO PEJABAT_PENETAP_JABATAN (
				PEJABAT_PENETAP_JABATAN_ID, PEJABAT_PENETAP_ID, NAMA, NIP, TANGGAL_AWAL, TANGGAL_AKHIR, 
				LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				   ".$this->getField("PEJABAT_PENETAP_JABATAN_ID").",
				   ".$this->getField("PEJABAT_PENETAP_ID").",
				   '".$this->getField("NAMA")."',
				   '".$this->getField("NIP")."',
				   ".$this->getField("TANGGAL_AWAL").",
				   ".$this->getField("TANGGAL_AKHIR").",
				   '".$this->getField("LAST_USER")."',
				   ".$this->getField("LAST_DATE").",
				   '".$this->getField("USER_LOGIN_ID")."',
				   ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEJABAT_PENETAP_JABATAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEJABAT_PENETAP_JABATAN
				SET    
			       	PEJABAT_PENETAP_ID=".$this->getField("PEJABAT_PENETAP_ID").",
				   	NAMA='".$this->getField("NAMA")."',
				   	NIP='".$this->getField("NIP")."',
				   	TANGGAL_AWAL=".$this->getField("TANGGAL_AWAL").",
				   	TANGGAL_AKHIR=".$this->getField("TANGGAL_AKHIR").",
				   	STATUS='".$this->getField("STATUS")."',
				   	LAST_USER='".$this->getField("LAST_USER")."',
				   	USER_LOGIN_ID='".$this->getField("USER_LOGIN_ID")."',
				   	USER_LOGIN_PEGAWAI_ID=".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				   	LAST_DATE=".$this->getField("LAST_DATE")."
				WHERE  PEJABAT_PENETAP_JABATAN_ID = ".$this->getField("PEJABAT_PENETAP_JABATAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEJABAT_PENETAP_JABATAN
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PEJABAT_PENETAP_JABATAN_ID    = ".$this->getField("PEJABAT_PENETAP_JABATAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
				UPDATE PEJABAT_PENETAP_JABATAN SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE PEJABAT_PENETAP_JABATAN_ID = ".$this->getField("PEJABAT_PENETAP_JABATAN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEJABAT_PENETAP_JABATAN_ID ASC')
	{
		$str = "
				SELECT 	A.PEJABAT_PENETAP_JABATAN_ID, A.PEJABAT_PENETAP_ID, A.NAMA, A.NIP, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, 
						A.STATUS, A.LAST_USER, A.LAST_DATE
				FROM PEJABAT_PENETAP_JABATAN A
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
				SELECT COUNT(A.PEJABAT_PENETAP_JABATAN_ID) AS ROWCOUNT 
				FROM PEJABAT_PENETAP_JABATAN A
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