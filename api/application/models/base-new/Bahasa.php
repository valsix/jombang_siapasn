<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Bahasa extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Bahasa()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.BAHASA_ID ASC')
	{
		$str = "
		SELECT A.BAHASA_ID, A.PEGAWAI_ID, A.JENIS,
		CASE A.JENIS WHEN '1' THEN 'Asing' ELSE 'Daerah' END JENIS_NAMA,
		A.NAMA, A.KEMAMPUAN,
		CASE A.KEMAMPUAN WHEN '1' THEN 'Aktif' ELSE 'Pasif' END KEMAMPUAN_NAMA,
		A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM BAHASA A
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.BAHASA_ID) AS ROWCOUNT 
				FROM BAHASA A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.BAHASA")); 
		$str = "
			INSERT INTO validasi.BAHASA 
			(
				BAHASA_ID, PEGAWAI_ID, JENIS, NAMA, KEMAMPUAN
				, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				, TEMP_VALIDASI_ID
			) 
			VALUES 
			(
				".$this->getField("BAHASA_ID").",
				".$this->getField("PEGAWAI_ID").",
				'".$this->getField("JENIS")."',
				'".$this->getField("NAMA")."',
				'".$this->getField("KEMAMPUAN")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	
		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		$str = "		
		UPDATE validasi.BAHASA
		SET    
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			JENIS= '".$this->getField("JENIS")."',
			NAMA= '".$this->getField("NAMA")."',
			KEMAMPUAN= '".$this->getField("KEMAMPUAN")."',
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.BAHASA_ID ASC')
	{
		$str = "
		SELECT
			A.BAHASA_ID, A.PEGAWAI_ID, A.JENIS
			, CASE A.JENIS WHEN '1' THEN 'Asing' ELSE 'Daerah' END JENIS_NAMA
			, A.NAMA, A.KEMAMPUAN
			, CASE A.KEMAMPUAN WHEN '1' THEN 'Aktif' ELSE 'Pasif' END KEMAMPUAN_NAMA
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_bahasa('".$pegawaiid."', '".$id."', '".$rowid."')) A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>