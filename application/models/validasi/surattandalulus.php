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
  
  class SuratTandaLulus extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuratTandaLulus()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.SURAT_TANDA_LULUS"));
     	$str = "
			INSERT INTO validasi.SURAT_TANDA_LULUS (
				SURAT_TANDA_LULUS_ID, PEGAWAI_ID, JENIS_ID, NO_STLUD, TANGGAL_STLUD, TANGGAL_MULAI, TANGGAL_AKHIR, NILAI_NPR, NILAI_NT, PENDIDIKAN_RIWAYAT_ID, PENDIDIKAN_ID, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID,TEMP_VALIDASI_ID
			) 
			VALUES (
				  ".$this->getField("SURAT_TANDA_LULUS_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("JENIS_ID").",
				  '".$this->getField("NO_STLUD")."',
				  ".$this->getField("TANGGAL_STLUD").",
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_AKHIR").",
				  ".$this->getField("NILAI_NPR").",
				  ".$this->getField("NILAI_NT").",
				  ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				  ".$this->getField("PENDIDIKAN_ID").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  ".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_TANDA_LULUS_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
    function update()
	{
		//STATUS=  NULL,
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE validasi.SURAT_TANDA_LULUS
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	JENIS_ID= ".$this->getField("JENIS_ID").",
				  	NO_STLUD= '".$this->getField("NO_STLUD")."',
				  	TANGGAL_STLUD= ".$this->getField("TANGGAL_STLUD").",
				  	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				  	TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR").",
				  	NILAI_NPR= ".$this->getField("NILAI_NPR").",
				  	NILAI_NT= ".$this->getField("NILAI_NT").",
				  	PENDIDIKAN_RIWAYAT_ID= ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				  	PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
					VALIDASI= ".$this->getField("VALIDASI")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

     function updatetanggalvalidasi()
	{
		$str = "		
		UPDATE validasi.SURAT_TANDA_LULUS 
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
		UPDATE validasi.SURAT_TANDA_LULUS 
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
        AND HAPUS_NAMA= 'SURAT_TANDA_LULUS' AND VALIDASI IS NULL
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
        AND HAPUS_NAMA= 'SURAT_TANDA_LULUS' AND VALIDASI IS NULL
        ";
				  
		$this->query = $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.SURAT_TANDA_LULUS_ID ASC')
	{
		$str = "
		SELECT 	
		A.SURAT_TANDA_LULUS_ID, A.PEGAWAI_ID, A.JENIS_ID, A.NO_STLUD, A.TANGGAL_STLUD
		, A.TANGGAL_MULAI, A.TANGGAL_AKHIR, A.NILAI_NPR, A.NILAI_NT, A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID
		, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		, CASE A.JENIS_ID 
		WHEN 1 THEN 'STL Ujian Dinas I' 
		WHEN 2 THEN 'STL Ujian Dinas II' 
		WHEN 3 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah SMA'
		WHEN 4 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah D-4/S-1'
		WHEN 5 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah S-2'
		ELSE '' END JENIS_NAMA
		, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
		, A.LAST_USER, A.LAST_DATE
		FROM SURAT_TANDA_LULUS A
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

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.SURAT_TANDA_LULUS_ID ASC')
	{
		$str = "
		SELECT 	
		A.SURAT_TANDA_LULUS_ID, A.PEGAWAI_ID, A.JENIS_ID, A.NO_STLUD, A.TANGGAL_STLUD
		, A.TANGGAL_MULAI, A.TANGGAL_AKHIR, A.NILAI_NPR, A.NILAI_NT, A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID
		, CASE A.JENIS_ID 
		WHEN 1 THEN 'STL Ujian Dinas I' 
		WHEN 2 THEN 'STL Ujian Dinas II' 
		WHEN 3 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah SMA'
		WHEN 4 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah D-4/S-1'
		WHEN 5 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah S-2'
		ELSE '' END JENIS_NAMA
		, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_surat_tanda_lulus('".$pegawaiid."', '".$id."', '".$rowid."')) A
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
		$str = "SELECT COUNT(A.SURAT_TANDA_LULUS_ID) AS ROWCOUNT 
				FROM SURAT_TANDA_LULUS A
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