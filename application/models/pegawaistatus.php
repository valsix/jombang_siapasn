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
  
  class PegawaiStatus extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PegawaiStatus()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_STATUS_ID", $this->getNextId("PEGAWAI_STATUS_ID","PEGAWAI_STATUS")); 

     	$str = "
			INSERT INTO PEGAWAI_STATUS (
				PEGAWAI_STATUS_ID, PEGAWAI_ID, STATUS_PEGAWAI_ID, TMT, STATUS_PEGAWAI_KEDUDUKAN_ID, STATUS, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("PEGAWAI_STATUS_ID").",
			     ".$this->getField("PEGAWAI_ID").",
			     ".$this->getField("STATUS_PEGAWAI_ID").",
			     ".$this->getField("TMT").",
				 ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID").",
			     ".$this->getField("STATUS").",
			     '".$this->getField("LAST_USER")."',
			     ".$this->getField("LAST_DATE").",
			     '".$this->getField("USER_LOGIN_ID")."',
			     ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEGAWAI_STATUS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEGAWAI_STATUS
				SET    
			     	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			     	STATUS_PEGAWAI_ID= ".$this->getField("STATUS_PEGAWAI_ID").",
			     	TMT= ".$this->getField("TMT").",
					STATUS_PEGAWAI_KEDUDUKAN_ID= ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID").",
			     	LAST_USER= '".$this->getField("LAST_USER")."',
			     	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
			     	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			     	LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  PEGAWAI_STATUS_ID = ".$this->getField("PEGAWAI_STATUS_ID")."
				"; 
		$this->query = $str;
				  // echo $str;exit;

		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEGAWAI_STATUS
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PEGAWAI_STATUS_ID    = ".$this->getField("PEGAWAI_STATUS_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
				UPDATE PEGAWAI_STATUS SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE PEGAWAI_STATUS_ID = ".$this->getField("PEGAWAI_STATUS_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
		/*$strLog= "
				DELETE FROM PEGAWAI_STATUS_LOG
				WHERE PEGAWAI_STATUS_ID = ".$this->getField("PEGAWAI_STATUS_ID")."
				";
		$this->query = $strLog;
		$this->execQuery($strLog);*/
		
       $str = "
				DELETE FROM PEGAWAI_STATUS
				WHERE PEGAWAI_STATUS_ID = ".$this->getField("PEGAWAI_STATUS_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_STATUS_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, A.STATUS, A.TMT, A.STATUS_PEGAWAI_KEDUDUKAN_ID, A.LAST_USER, A.LAST_DATE,B.NAMA STATUS_PEGAWAI_INFO, C.NAMA KEDUDUKAN_INFO,
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.PEGAWAI_STATUS_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.PEGAWAI_STATUS_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM PEGAWAI_STATUS A
				LEFT JOIN STATUS_PEGAWAI B ON B.STATUS_PEGAWAI_ID = A.STATUS_PEGAWAI_ID
				LEFT JOIN STATUS_PEGAWAI_KEDUDUKAN C ON C.STATUS_PEGAWAI_KEDUDUKAN_ID = A.STATUS_PEGAWAI_KEDUDUKAN_ID
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
    
	function selectByParamsPegawaiTerakhir($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
		A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID
		FROM PEGAWAI_STATUS_TERAKHIR_RIWAYAT A
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
	
	function selectByParamsStatusKedudukan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		// AND TIPE_ID = 1 AND JENIS_ID = 1
		$str = "
		SELECT A.STATUS_PEGAWAI_KEDUDUKAN_ID, A.STATUS_PEGAWAI_ID, A.NAMA, A.TIPE_ID, A.JENIS_ID
		FROM STATUS_PEGAWAI_KEDUDUKAN A
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
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_STATUS_ID) AS ROWCOUNT 
				FROM PEGAWAI_STATUS A
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