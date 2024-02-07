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
  
  class MailboxDetil extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function MailboxDetil()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MAILBOX_DETIL_ID", $this->getNextId("MAILBOX_DETIL_ID","MAILBOX_DETIL")); 

		$str = "
			INSERT INTO MAILBOX_DETIL (
				MAILBOX_DETIL_ID, MAILBOX_ID, TANGGAL, ISI, STATUS, TIPE, MAILBOX_KATEGORI_ID, USER_LOGIN_ID, SATUAN_KERJA_JAWAB_ID, PEGAWAI_ID, LAST_USER, LAST_DATE, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("MAILBOX_DETIL_ID").",
				".$this->getField("MAILBOX_ID").",
				".$this->getField("TANGGAL").",
				'".$this->getField("ISI")."',
				".$this->getField("STATUS").",
				".$this->getField("TIPE").",
				".$this->getField("MAILBOX_KATEGORI_ID").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("SATUAN_KERJA_JAWAB_ID").",
				".$this->getField("PEGAWAI_ID").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("MAILBOX_DETIL_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE MAILBOX_DETIL SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE MAILBOX_DETIL_ID = ".$this->getField("MAILBOX_DETIL_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.MAILBOX_DETIL_ID ASC')
	{
		$str = "
				SELECT MAILBOX_DETIL_ID, MAILBOX_ID, TANGGAL, ISI, STATUS, USER_LOGIN_ID
				FROM MAILBOX_DETIL A
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
				SELECT COUNT(A.MAILBOX_DETIL_ID) AS ROWCOUNT 
				FROM MAILBOX_DETIL A
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