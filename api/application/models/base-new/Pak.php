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
  
  class Pak extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Pak()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PAK")); 

		$str = "
			INSERT INTO validasi.PAK (
				PAK_ID, PEGAWAI_ID, NO_SK, TANGGAL_SK, TANGGAL_MULAI, TANGGAL_SELESAI, 
			    PERIODE_AWAL, PERIODE_AKHIR, PAK_AWAL, JABATAN_FT_ID, KREDIT_UTAMA, 
			    KREDIT_PENUNJANG, TOTAL_KREDIT, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			) 
			VALUES (
				 ".$this->getField("PAK_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("NO_SK")."',
				 ".$this->getField("TANGGAL_SK").",
				 ".$this->getField("TANGGAL_MULAI").",
				 ".$this->getField("TANGGAL_SELESAI").",
				 ".$this->getField("PERIODE_AWAL").",
				 ".$this->getField("PERIODE_AKHIR").",
				 '".$this->getField("PAK_AWAL")."',
				 ".$this->getField("JABATAN_FT_ID").",
				 ".$this->getField("KREDIT_UTAMA").",
				 ".$this->getField("KREDIT_PENUNJANG").",
				 ".$this->getField("TOTAL_KREDIT").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 ".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	
		$this->id = $this->getField("PAK_ID");
		$this->query = $str;
		// echo $this->$query;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE validasi.PAK
				SET    
				 	PEGAWAI_ID			= ".$this->getField("PEGAWAI_ID").",
				 	NO_SK				= '".$this->getField("NO_SK")."',
				 	TANGGAL_SK			= ".$this->getField("TANGGAL_SK").",
				 	TANGGAL_MULAI		= ".$this->getField("TANGGAL_MULAI").",
				 	TANGGAL_SELESAI		= ".$this->getField("TANGGAL_SELESAI").",
				 	PERIODE_AWAL		= ".$this->getField("PERIODE_AWAL").",
				 	PERIODE_AKHIR		= ".$this->getField("PERIODE_AKHIR").",
				 	PAK_AWAL			= '".$this->getField("PAK_AWAL")."',
				 	JABATAN_FT_ID		= ".$this->getField("JABATAN_FT_ID").",
				 	KREDIT_UTAMA		= ".$this->getField("KREDIT_UTAMA").",
				 	KREDIT_PENUNJANG	= ".$this->getField("KREDIT_PENUNJANG").",
				 	TOTAL_KREDIT		= ".$this->getField("TOTAL_KREDIT").",
				 	LAST_USER			= '".$this->getField("LAST_USER")."',
				 	LAST_DATE			= ".$this->getField("LAST_DATE").",
				 	USER_LOGIN_PEGAWAI_ID			= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	USER_LOGIN_ID			= ".$this->getField("USER_LOGIN_ID").",
				 	LAST_LEVEL			= ".$this->getField("LAST_LEVEL")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.PAK_ID ASC')
	{
		$str = "
		SELECT 
		A.PAK_ID, A.PEGAWAI_ID, A.NO_SK, A.TANGGAL_SK, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
		A.PERIODE_AWAL, A.PERIODE_AKHIR, A.PAK_AWAL, A.JABATAN_FT_ID, A.KREDIT_UTAMA, 
		A.KREDIT_PENUNJANG, A.TOTAL_KREDIT, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS,
		B.NAMA JABATAN
		FROM PAK A
		LEFT JOIN JABATAN_FT B ON A.JABATAN_FT_ID = B.JABATAN_FT_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.PAK_ID ASC')
	{
		$str = "
		SELECT 
		A.PAK_ID, A.PEGAWAI_ID, A.NO_SK, A.TANGGAL_SK, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
		A.PERIODE_AWAL, A.PERIODE_AKHIR, A.PAK_AWAL, A.JABATAN_FT_ID, A.KREDIT_UTAMA, 
		A.KREDIT_PENUNJANG, A.TOTAL_KREDIT,
		B.NAMA JABATAN, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_pak('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN JABATAN_FT B ON A.JABATAN_FT_ID = B.JABATAN_FT_ID
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PAK_ID) AS ROWCOUNT 
				FROM PAK A
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

  } 
?>