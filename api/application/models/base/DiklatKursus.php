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
  
  class DiklatKursus extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function DiklatKursus()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.DIKLAT_KURSUS")); 

		$str = "
			INSERT INTO validasi.DIKLAT_KURSUS 
			(
				DIKLAT_KURSUS_ID, PEGAWAI_ID, TIPE_KURSUS_ID
				, NAMA, NO_SERTIFIKAT, TANGGAL_SERTIFIKAT, TANGGAL_MULAI, TANGGAL_SELESAI
				, TAHUN, JUMLAH_JAM, ANGKATAN, TEMPAT,PENYELENGGARA,REF_JENIS_KURSUS_ID, RUMPUN_ID
				, STATUS_LULUS, REF_INSTANSI_ID, REF_INSTANSI_NAMA, NILAI_REKAM_JEJAK
				, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_USER, LAST_CREATE_DATE
				, TEMP_VALIDASI_ID
			) 
			VALUES 
			(
				".$this->getField("DIKLAT_KURSUS_ID")."
				, ".$this->getField("PEGAWAI_ID")."
				, ".$this->getField("TIPE_KURSUS_ID")."
				, '".$this->getField("NAMA")."'
				, '".$this->getField("NO_SERTIFIKAT")."'
				, ".$this->getField("TANGGAL_SERTIFIKAT")."
				, ".$this->getField("TANGGAL_MULAI")."
				, ".$this->getField("TANGGAL_SELESAI")."
				, ".$this->getField("TAHUN")."
				, ".$this->getField("JUMLAH_JAM")."
				, '".$this->getField("ANGKATAN")."'
				, '".$this->getField("TEMPAT")."'
				, '".$this->getField("PENYELENGGARA")."'
				, ".$this->getField("REF_JENIS_KURSUS_ID")."
				, ".$this->getField("RUMPUN_ID")."
				, '".$this->getField("STATUS_LULUS")."'
				, ".$this->getField("REF_INSTANSI_ID")."
				, '".$this->getField("REF_INSTANSI_NAMA")."'
				, ".$this->getField("NILAI_REKAM_JEJAK")."
				, ".$this->getField("USER_LOGIN_ID")."
				, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				, ".$this->getField("LAST_CREATE_USER")."
				, NOW()
				, ".$this->getField("TEMP_VALIDASI_ID")."
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
		UPDATE validasi.DIKLAT_KURSUS
		SET
		TIPE_KURSUS_ID= ".$this->getField("TIPE_KURSUS_ID")."
	 	, NAMA= '".$this->getField("NAMA")."'
	 	, NO_SERTIFIKAT= '".$this->getField("NO_SERTIFIKAT")."'
	 	, TANGGAL_SERTIFIKAT= ".$this->getField("TANGGAL_SERTIFIKAT")."
	 	, TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI")."
	 	, TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI")."
	 	, TAHUN= ".$this->getField("TAHUN")."
	 	, JUMLAH_JAM= ".$this->getField("JUMLAH_JAM")."
	 	, ANGKATAN= '".$this->getField("ANGKATAN")."'
	 	, TEMPAT= '".$this->getField("TEMPAT")."'
	 	, STATUS_LULUS= '".$this->getField("STATUS_LULUS")."'
	 	, REF_INSTANSI_ID= ".$this->getField("REF_INSTANSI_ID")."
	 	, REF_INSTANSI_NAMA= '".$this->getField("REF_INSTANSI_NAMA")."'
	 	, PENYELENGGARA= '".$this->getField("PENYELENGGARA")."'
	 	, NILAI_REKAM_JEJAK= ".$this->getField("NILAI_REKAM_JEJAK")."
	 	, RUMPUN_ID= ".$this->getField("RUMPUN_ID")."
	 	, REF_JENIS_KURSUS_ID= ".$this->getField("REF_JENIS_KURSUS_ID")."
	 	, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
	 	, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	 	, LAST_USER= '".$this->getField("LAST_USER")."'
	 	, LAST_DATE= NOW()
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.DIKLAT_KURSUS_ID ASC')
	{
		$str = "
		SELECT 
			A.*
			, CASE A.TIPE_KURSUS_ID WHEN 1 THEN 'Diklat Teknis' WHEN 2 THEN 'Diklat Fungsional' WHEN 3 THEN 'Seminar / Workshop / Kursus / Magang / Sejenisnya' ELSE '' END TIPE_KURSUS_NAMA
			, B.NAMA JENIS_KURSUS_NAMA, R.KETERANGAN RUMPUN_NAMA, TK.NAMA TIPE_DIKLAT_NAMA
			, CASE WHEN A.REF_INSTANSI_ID IS NULL THEN A.REF_INSTANSI_NAMA ELSE C.NAMA END REF_INSTANSI_INFO
			, C.NAMA REF_JENIS_KURSUS_DATA
		FROM diklat_kursus A
		LEFT JOIN sapk.ref_jenis_kursus B ON A.REF_JENIS_KURSUS_ID =B.REF_JENIS_KURSUS_ID
		LEFT JOIN sapk.ref_instansi C ON A.REF_INSTANSI_ID = C.REF_INSTANSI_ID
		LEFT JOIN talent.rumpun R ON A.RUMPUN_ID = R.RUMPUN_ID
		LEFT JOIN tipe_kursus TK ON A.TIPE_KURSUS_ID = TK.TIPE_KURSUS_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.DIKLAT_KURSUS_ID ASC')
	{
		$str = "
		SELECT 
		A.*
		, CASE A.TIPE_KURSUS_ID WHEN 1 THEN 'Diklat Teknis' WHEN 2 THEN 'Diklat Fungsional' WHEN 3 THEN 'Seminar / Workshop / Kursus / Magang / Sejenisnya' ELSE '' END TIPE_KURSUS_NAMA
		, B.NAMA JENIS_KURSUS_NAMA, R.KETERANGAN RUMPUN_NAMA, TK.NAMA TIPE_DIKLAT_NAMA
		, CASE WHEN A.REF_INSTANSI_ID IS NULL THEN A.REF_INSTANSI_NAMA ELSE C.NAMA END REF_INSTANSI_INFO
		, C.NAMA REF_JENIS_KURSUS_DATA
		FROM (select * from validasi.validasi_pegawai_diklat_kursus('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN sapk.ref_jenis_kursus B ON A.REF_JENIS_KURSUS_ID =B.REF_JENIS_KURSUS_ID
		LEFT JOIN sapk.ref_instansi C ON A.REF_INSTANSI_ID = C.REF_INSTANSI_ID
		LEFT JOIN talent.rumpun R ON A.RUMPUN_ID = R.RUMPUN_ID
		LEFT JOIN tipe_kursus TK ON A.TIPE_KURSUS_ID = TK.TIPE_KURSUS_ID
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.DIKLAT_KURSUS_ID) AS ROWCOUNT 
				FROM DIKLAT_KURSUS A
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

    function selecttipekursus($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TIPE_KURSUS_ID ASC')
	{
		$str = "
		SELECT 
			A.*
		FROM tipe_kursus A
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

  } 
?>