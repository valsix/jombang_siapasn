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
  
  class StatusPegawaiKedudukan extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function StatusPegawaiKedudukan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("STATUS_PEGAWAI_KEDUDUKAN_ID", $this->getNextId("STATUS_PEGAWAI_KEDUDUKAN_ID","STATUS_PEGAWAI_KEDUDUKAN")); 

		$str = "
			INSERT INTO STATUS_PEGAWAI_KEDUDUKAN (
				STATUS_PEGAWAI_KEDUDUKAN_ID, NAMA, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID").",
				'".$this->getField("NAMA")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				'".$this->getField("USER_LOGIN_ID")."',
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE STATUS_PEGAWAI_KEDUDUKAN
				SET    
					   NAMA   		= '".$this->getField("NAMA")."',
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   USER_LOGIN_ID	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  STATUS_PEGAWAI_KEDUDUKAN_ID    	= ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE STATUS_PEGAWAI_KEDUDUKAN
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   USER_LOGIN_ID	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  STATUS_PEGAWAI_KEDUDUKAN_ID    	= ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE STATUS_PEGAWAI_KEDUDUKAN SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE STATUS_PEGAWAI_KEDUDUKAN_ID = ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.STATUS_PEGAWAI_KEDUDUKAN_ID ASC')
	{
		$str = "
		SELECT
		A.STATUS_PEGAWAI_KEDUDUKAN_ID, A.STATUS_PEGAWAI_ID, A.NAMA, A.STATUS, A.TIPE_ID, A.JENIS_ID
		FROM STATUS_PEGAWAI_KEDUDUKAN A
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
	
    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.STATUS_PEGAWAI_KEDUDUKAN_ID ASC')
	{
		$str = "
				SELECT A.STATUS_PEGAWAI_KEDUDUKAN_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'AKtif' END STATUS_NAMA, 
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.STATUS_PEGAWAI_KEDUDUKAN_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.STATUS_PEGAWAI_KEDUDUKAN_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM STATUS_PEGAWAI_KEDUDUKAN A
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
				SELECT COUNT(A.STATUS_PEGAWAI_KEDUDUKAN_ID) AS ROWCOUNT 
				FROM STATUS_PEGAWAI_KEDUDUKAN A
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