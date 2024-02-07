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
  
  class Eselon extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Eselon()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ESELON_ID", $this->getNextId("ESELON_ID","ESELON")); 
      
		$str = "
			INSERT INTO ESELON (
				ESELON_ID, NAMA, PANGKAT_MINIMAL, PANGKAT_MAKSIMAL, STATUS, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("ESELON_ID").",
				'".$this->getField("NAMA")."',
				 ".$this->getField("PANGKAT_MINIMAL").",
				 ".$this->getField("PANGKAT_MAKSIMAL").",
				 ".$this->getField("STATUS").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("ESELON_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE ESELON
				SET    
					NAMA				='".$this->getField("NAMA")."',
				 	PANGKAT_MINIMAL		= ".$this->getField("PANGKAT_MINIMAL").",
				 	PANGKAT_MAKSIMAL	= ".$this->getField("PANGKAT_MAKSIMAL").",
				 	LAST_USER			='".$this->getField("LAST_USER")."',
					LAST_DATE			= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID			= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID			= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  ESELON_ID = ".$this->getField("ESELON_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE ESELON
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  ESELON_ID    	= ".$this->getField("ESELON_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE ESELON SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE ESELON_ID = ".$this->getField("ESELON_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ESELON_ID ASC ')
	{
		$str = "
				SELECT A.ESELON_ID, A.NAMA, A.PANGKAT_MINIMAL, A.PANGKAT_MAKSIMAL, 
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM ESELON A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ESELON_ID ASC ')
	{
		$str = "
				SELECT A.ESELON_ID, A.NAMA, A.PANGKAT_MINIMAL, PMIN.KODE PANGKAT_MINIMAL_NAMA, A.PANGKAT_MAKSIMAL, PMAX.KODE PANGKAT_MAKSIMAL_NAMA,
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.ESELON_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.ESELON_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM ESELON A
				LEFT JOIN PANGKAT PMIN ON A.PANGKAT_MINIMAL = PMIN.PANGKAT_ID
				LEFT JOIN PANGKAT PMAX ON A.PANGKAT_MAKSIMAL = PMAX.PANGKAT_ID
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
				SELECT COUNT(A.ESELON_ID) AS ROWCOUNT 
				FROM ESELON A
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