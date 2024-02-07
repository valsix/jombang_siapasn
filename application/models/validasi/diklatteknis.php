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
  
  class DiklatTeknis extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DiklatTeknis()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.DIKLAT_TEKNIS")); 

		$str = "
			INSERT INTO validasi.DIKLAT_TEKNIS (
				DIKLAT_TEKNIS_ID, PEGAWAI_ID, TEMPAT, PENYELENGGARA, ANGKATAN, TAHUN, TANGGAL_MULAI, TANGGAL_SELESAI, NO_STTPP,	TANGGAL_STTPP, JUMLAH_JAM, NAMA, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			) 
			VALUES (
				 ".$this->getField("DIKLAT_TEKNIS_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("TEMPAT")."',
				 '".$this->getField("PENYELENGGARA")."',
				 '".$this->getField("ANGKATAN")."',
				 ".$this->getField("TAHUN").",
				 ".$this->getField("TANGGAL_MULAI").",
				 ".$this->getField("TANGGAL_SELESAI").",
				 '".$this->getField("NO_STTPP")."',
				 ".$this->getField("TANGGAL_STTPP").",
				 ".$this->getField("JUMLAH_JAM").",
				 '".$this->getField("NAMA")."',
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
		// echo $this->$query;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		$str = "		
				UPDATE validasi.DIKLAT_TEKNIS
				SET    
				 	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				 	TEMPAT= '".$this->getField("TEMPAT")."',
				 	PENYELENGGARA= '".$this->getField("PENYELENGGARA")."',
				 	ANGKATAN= '".$this->getField("ANGKATAN")."',
				 	TAHUN= ".$this->getField("TAHUN").",
				 	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				 	TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
				 	NO_STTPP= '".$this->getField("NO_STTPP")."',
				 	TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
				 	JUMLAH_JAM= ".$this->getField("JUMLAH_JAM").",
				 	NAMA= '".$this->getField("NAMA")."',
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	VALIDASI= ".$this->getField("VALIDASI")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


     function updatetanggalvalidasi()
	{
		$str = "		
		UPDATE validasi.DIKLAT_TEKNIS 
		SET
			TANGGAL_VALIDASI= NOW()
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatevalidasi()
	{
		$str = "		
		UPDATE validasi.DIKLAT_TEKNIS 
		SET
			VALIDASI= ".$this->getField("VALIDASI").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatevalidasihapusdata()
	{
        $str = "
        UPDATE validasi.HAPUS_DATA
        SET
	        VALIDASI= ".$this->getField("VALIDASI").",
	        TANGGAL_VALIDASI= NOW()
        WHERE 
        TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
        AND HAPUS_NAMA= 'DIKLAT_TEKNIS' AND VALIDASI IS NULL
        ";
				  
		$this->query = $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }

    function deletehapusdata()
	{
        $str = "
        DELETE FROM validasi.HAPUS_DATA
        WHERE 
        TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
        AND HAPUS_NAMA= 'DIKLAT_TEKNIS' AND VALIDASI IS NULL
        ";
				  
		$this->query = $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.DIKLAT_TEKNIS_ID ASC')
	{
		$str = "
		SELECT 
		A.DIKLAT_TEKNIS_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.ANGKATAN, A.TAHUN, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, A.NO_STTPP, 
		A.TANGGAL_STTPP, A.JUMLAH_JAM, A.NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS
		FROM DIKLAT_TEKNIS A
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.DIKLAT_TEKNIS_ID ASC')
	{
		$str = "
		SELECT 
		A.DIKLAT_TEKNIS_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.ANGKATAN, A.TAHUN, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, A.NO_STTPP, 
		A.TANGGAL_STTPP, A.JUMLAH_JAM, A.NAMA, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_diklat_teknis('".$pegawaiid."', '".$id."', '".$rowid."')) A
		WHERE 1 = 1
		"; 

		foreach ($paramsArray as $key => $val)
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
				SELECT COUNT(A.DIKLAT_TEKNIS_ID) AS ROWCOUNT 
				FROM DIKLAT_TEKNIS A
				WHERE 1 = 1 ".$statement; 
		foreach ($paramsArray as $key => $val)
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