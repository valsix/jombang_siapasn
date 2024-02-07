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
  
  class Penghargaan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Penghargaan()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PENGHARGAAN")); 
		$str = "
			INSERT INTO validasi.PENGHARGAAN 
			(
				PENGHARGAAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NAMA, NO_SK, TANGGAL_SK, TAHUN
				, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				, TEMP_VALIDASI_ID
			) 
			VALUES 
			(
				".$this->getField("PENGHARGAAN_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("PEJABAT_PENETAP_ID").",
				'".$this->getField("PEJABAT_PENETAP")."',
				'".$this->getField("NAMA")."',
				'".$this->getField("NO_SK")."',
				".$this->getField("TANGGAL_SK").",
				".$this->getField("TAHUN").",
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
		UPDATE validasi.PENGHARGAAN
		SET    
				PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				NAMA= '".$this->getField("NAMA")."',
				NO_SK= '".$this->getField("NO_SK")."',
				TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				TAHUN= ".$this->getField("TAHUN").",
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
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.PENGHARGAAN_ID ASC')
	{
		$str = "
		SELECT 	
		A.PENGHARGAAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NAMA,
		CASE A.NAMA WHEN '1' THEN 'Satya Lencana Karya Satya X (Perunggu)'
		WHEN '2' THEN 'Satya Lencana Karya Satya XX (Perak)' WHEN '3' THEN 'Satya Lencana Karya Satya XXX (Emas)' ELSE 'Belum di tentukan' END NAMA_NAMA,
		A.NO_SK, A.TANGGAL_SK, A.TAHUN, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS, B.NAMA PEJABAT_PENETAP_NAMA
		FROM PENGHARGAAN A
		LEFT JOIN PEJABAT_PENETAP B ON A.PEJABAT_PENETAP_ID = B.PEJABAT_PENETAP_ID
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

    function selectByParamsPersonal($paramsArray=array(),$limit=-1,$from=-1,$pegawaiid, $id="", $rowid="",$statement='', $order='ORDER BY A.PENGHARGAAN_ID ASC')
	{
		$str = "
		SELECT 	
		A.PENGHARGAAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NAMA,
		CASE A.NAMA WHEN '1' THEN 'Satya Lencana Karya Satya X (Perunggu)'
		WHEN '2' THEN 'Satya Lencana Karya Satya XX (Perak)' WHEN '3' THEN 'Satya Lencana Karya Satya XXX (Emas)' ELSE 'Belum di tentukan' END NAMA_NAMA,
		A.NO_SK, A.TANGGAL_SK, A.TAHUN,B.NAMA PEJABAT_PENETAP_NAMA
		, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_penghargaan('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN PEJABAT_PENETAP B ON A.PEJABAT_PENETAP_ID = B.PEJABAT_PENETAP_ID
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
				SELECT COUNT(A.PENGHARGAAN_ID) AS ROWCOUNT 
				FROM PENGHARGAAN A
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