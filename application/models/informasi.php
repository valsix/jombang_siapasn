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
  
  class Informasi extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Informasi()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("INFORMASI_ID", $this->getNextId("INFORMASI_ID","INFORMASI")); 

     	$str = "
			INSERT INTO INFORMASI (
				INFORMASI_ID, INFORMASI_KATEGORI_ID, NAMA, KETERANGAN, TANGGAL_AWAL, TANGGAL_AKHIR, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("INFORMASI_ID").",
				  ".$this->getField("INFORMASI_KATEGORI_ID").",
				 '".$this->getField("NAMA")."',
			     '".$this->getField("KETERANGAN")."',
				  ".$this->getField("TANGGAL_AWAL").",
				  ".$this->getField("TANGGAL_AKHIR").",
			     '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."

			)
		"; 	
		$this->id = $this->getField("INFORMASI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function insertUser()
	{
     	$str = "
			INSERT INTO INFORMASI_USER (
				INFORMASI_ID, USER_LOGIN_ID, LAST_USER, LAST_DATE
			) 
			VALUES (
				".$this->getField("INFORMASI_ID").",
				".$this->getField("USER_LOGIN_ID").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE INFORMASI
				SET    
				 	INFORMASI_KATEGORI_ID	= ".$this->getField("INFORMASI_KATEGORI_ID").",
					NAMA			= '".$this->getField("NAMA")."',
			     	KETERANGAN		= '".$this->getField("KETERANGAN")."',
				  	TANGGAL_AWAL	= ".$this->getField("TANGGAL_AWAL").",
				  	TANGGAL_AKHIR	= ".$this->getField("TANGGAL_AKHIR").",
			     	LAST_USER		= '".$this->getField("LAST_USER")."',
				 	LAST_DATE		= ".$this->getField("LAST_DATE").",
				 	USER_LOGIN_ID		= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID		= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  INFORMASI_ID = ".$this->getField("INFORMASI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function uploadFile()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE INFORMASI
				SET    
					   LINK_FILE   	= '".$this->getField("LINK_FILE")."',
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  INFORMASI_ID = ".$this->getField("INFORMASI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE INFORMASI
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  INFORMASI_ID    	= ".$this->getField("INFORMASI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE INFORMASI SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE INFORMASI_ID = ".$this->getField("INFORMASI_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.INFORMASI_ID ASC')
	{
		$str = "
				SELECT A.INFORMASI_ID, A.INFORMASI_KATEGORI_ID, A.NAMA, A.KETERANGAN, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.LINK_FILE,
					CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM INFORMASI A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.INFORMASI_ID ASC ')
	{
		$str = "
				SELECT A.INFORMASI_ID, A.NAMA, A.KETERANGAN, A.TANGGAL_AWAL, A.TANGGAL_AKHIR, A.LINK_FILE, 
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.INFORMASI_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.INFORMASI_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM INFORMASI A
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
				SELECT COUNT(A.INFORMASI_ID) AS ROWCOUNT 
				FROM INFORMASI A
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM INFORMASI A
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

    function selectByParamsUserInformasi($userloginid= "", $statement='', $sOrder= "")
	{
		if(empty($userloginid))
			$userloginid= -1;

		$str = "
		SELECT A.INFORMASI_ID, A.NAMA, A.KETERANGAN, A.TANGGAL_AWAL, B.INFORMASI_ID INFORMASI_USER_ID
		FROM INFORMASI A
		LEFT JOIN INFORMASI_USER B ON A.INFORMASI_ID = B.INFORMASI_ID AND B.USER_LOGIN_ID = ".$userloginid."
		WHERE 1=1 ".$statement;

		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function getCountByParamsUserInformasi($userloginid= "", $statement='')
	{
		if(empty($userloginid))
			$userloginid= -1;
		
		$str = "
		SELECT COUNT(1) ROWCOUNT
		FROM INFORMASI A
		LEFT JOIN INFORMASI_USER B ON A.INFORMASI_ID = B.INFORMASI_ID AND B.USER_LOGIN_ID = ".$userloginid."
		WHERE 1=1
		AND B.INFORMASI_ID IS NULL ".$statement;

		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }


  } 
?>