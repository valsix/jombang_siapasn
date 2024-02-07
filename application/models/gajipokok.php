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
  
  class GajiPokok extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function GajiPokok()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("GAJI_POKOK_ID", $this->getNextId("GAJI_POKOK_ID","GAJI_POKOK")); 
      
		$str = "
			INSERT INTO GAJI_POKOK (
				GAJI_POKOK_ID, MASA_KERJA, PANGKAT_ID, STATUS, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("GAJI_POKOK_ID").",
				".$this->getField("MASA_KERJA").",
				".$this->getField("PANGKAT_ID").",
				".$this->getField("STATUS").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("GAJI_POKOK_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_POKOK
				SET    
					MASA_KERJA= ".$this->getField("MASA_KERJA").",
					PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."					
				WHERE  GAJI_POKOK_ID = ".$this->getField("GAJI_POKOK_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_POKOK
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  GAJI_POKOK_ID  = ".$this->getField("GAJI_POKOK_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE GAJI_POKOK SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE GAJI_POKOK_ID = ".$this->getField("GAJI_POKOK_ID")."
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
    function selectByParamsGajiPokok($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT A.GAJI_POKOK_ID, A.MASA_KERJA, A.PANGKAT_ID, B.GAJI
		FROM GAJI_POKOK A
		INNER JOIN
		(
			SELECT A.GAJI_POKOK_ID, GAJI
			FROM GAJI_POKOK_DETIL A
			INNER JOIN
			(
				SELECT GAJI_POKOK_ID, MAX(COALESCE(TANGGAL_AKHIR, CURRENT_DATE)) TANGGAL_AKHIR
				FROM GAJI_POKOK_DETIL
				GROUP BY GAJI_POKOK_ID
			) B ON A.GAJI_POKOK_ID = B.GAJI_POKOK_ID
			WHERE 1=1
		) B ON A.GAJI_POKOK_ID = B.GAJI_POKOK_ID
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GAJI_POKOK_ID ASC')
	{
		$str = "
				SELECT A.GAJI_POKOK_ID, A.MASA_KERJA, A.PANGKAT_ID, A.LAST_USER, A.LAST_DATE,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.GAJI_POKOK_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.GAJI_POKOK_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM GAJI_POKOK A
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

    function selectByParamsJoinPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GAJI_POKOK_ID ASC')
	{
		$str = "
				SELECT A.GAJI_POKOK_ID, A.MASA_KERJA, A.PANGKAT_ID, B.KODE, B.NAMA,
						CASE WHEN A.STATUS = '1' THEN
							CONCAT('<a onClick=\"hapusData(''',A.GAJI_POKOK_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
						ELSE
							CONCAT('<a onClick=\"hapusData(''',A.GAJI_POKOK_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
						END LINK_URL_INFO
				FROM GAJI_POKOK A
				INNER JOIN	PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
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
				SELECT COUNT(A.GAJI_POKOK_ID) AS ROWCOUNT 
				FROM GAJI_POKOK A
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
	
    function getCountByParamsGajiBerlaku($periode, $masakerja, $pangkatid)
	{
		$str= "SELECT AMBIL_GAJI_BERLAKU_TGL('".substr($periode,4,4)."' || '-' || '".substr($periode,2,2)."' || '-' || '".substr($periode,0,2)."', ".$masakerja.", ".$pangkatid.") AS ROWCOUNT "; 
		$this->select($str);
		//echo $str;exit;
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>