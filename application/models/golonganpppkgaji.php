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
  
  class GolonganPppkGaji extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function GolonganPppkGaji()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("GOLONGAN_PPPK_GAJI_ID", $this->getNextId("GOLONGAN_PPPK_GAJI_ID","GOLONGAN_PPPK_GAJI")); 
      
		$str = "
			INSERT INTO GOLONGAN_PPPK_GAJI (
				GOLONGAN_PPPK_GAJI_ID, MASA_KERJA, GOLONGAN_PPPK_ID, STATUS, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("GOLONGAN_PPPK_GAJI_ID").",
				".$this->getField("MASA_KERJA").",
				".$this->getField("GOLONGAN_PPPK_ID").",
				".$this->getField("STATUS").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("GOLONGAN_PPPK_GAJI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GOLONGAN_PPPK_GAJI
				SET    
					MASA_KERJA= ".$this->getField("MASA_KERJA").",
					GOLONGAN_PPPK_ID= ".$this->getField("GOLONGAN_PPPK_ID").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."					
				WHERE  GOLONGAN_PPPK_GAJI_ID = ".$this->getField("GOLONGAN_PPPK_GAJI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GOLONGAN_PPPK_GAJI
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  GOLONGAN_PPPK_GAJI_ID  = ".$this->getField("GOLONGAN_PPPK_GAJI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE GOLONGAN_PPPK_GAJI SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE GOLONGAN_PPPK_GAJI_ID = ".$this->getField("GOLONGAN_PPPK_GAJI_ID")."
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
		SELECT A.GOLONGAN_PPPK_GAJI_ID, A.MASA_KERJA, A.GOLONGAN_PPPK_ID, B.GAJI
		FROM GOLONGAN_PPPK_GAJI A
		INNER JOIN
		(
			SELECT A.GOLONGAN_PPPK_GAJI_ID, GAJI
			FROM GOLONGAN_PPPK_GAJI_DETIL A
			INNER JOIN
			(
				SELECT GOLONGAN_PPPK_GAJI_ID, MAX(COALESCE(TANGGAL_AKHIR, CURRENT_DATE)) TANGGAL_AKHIR
				FROM GOLONGAN_PPPK_GAJI_DETIL
				GROUP BY GOLONGAN_PPPK_GAJI_ID
			) B ON A.GOLONGAN_PPPK_GAJI_ID = B.GOLONGAN_PPPK_GAJI_ID
			WHERE 1=1
		) B ON A.GOLONGAN_PPPK_GAJI_ID = B.GOLONGAN_PPPK_GAJI_ID
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GOLONGAN_PPPK_GAJI_ID ASC')
	{
		$str = "
				SELECT A.GOLONGAN_PPPK_GAJI_ID, A.MASA_KERJA, A.GOLONGAN_PPPK_ID, A.LAST_USER, A.LAST_DATE,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.GOLONGAN_PPPK_GAJI_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.GOLONGAN_PPPK_GAJI_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM GOLONGAN_PPPK_GAJI A
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

    function selectByParamsJoinPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.GOLONGAN_PPPK_GAJI_ID ASC')
	{
		$str = "
				SELECT A.GOLONGAN_PPPK_GAJI_ID, A.MASA_KERJA, A.GOLONGAN_PPPK_ID, B.KODE, B.NAMA,
						CASE WHEN A.STATUS = '1' THEN
							CONCAT('<a onClick=\"hapusData(''',A.GOLONGAN_PPPK_GAJI_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
						ELSE
							CONCAT('<a onClick=\"hapusData(''',A.GOLONGAN_PPPK_GAJI_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
						END LINK_URL_INFO
				FROM GOLONGAN_PPPK_GAJI A
				INNER JOIN	GOLONGAN_PPPK B ON A.GOLONGAN_PPPK_ID = B.GOLONGAN_PPPK_ID
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
				SELECT COUNT(A.GOLONGAN_PPPK_GAJI_ID) AS ROWCOUNT 
				FROM GOLONGAN_PPPK_GAJI A
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
		$str= "SELECT ambilpppkgajiberlakutgl('".substr($periode,4,4)."' || '-' || '".substr($periode,2,2)."' || '-' || '".substr($periode,0,2)."', ".$masakerja.", ".$pangkatid.") AS ROWCOUNT "; 
		$this->select($str);
		// echo $str;exit;
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>