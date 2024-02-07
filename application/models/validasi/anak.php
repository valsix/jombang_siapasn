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
  
  class Anak extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Anak()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.ANAK")); 

		$str = "
			INSERT INTO validasi.ANAK (
				ANAK_ID, PEGAWAI_ID, SUAMI_ISTRI_ID, PENDIDIKAN_ID, NAMA, NOMOR_INDUK, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_KELUARGA, STATUS_TUNJANGAN, PEKERJAAN, AWAL_BAYAR, AKHIR_BAYAR, STATUS_NIKAH, LAST_USER, LAST_DATE, LAST_LEVEL, STATUS_AKTIF, TANGGAL_MENINGGAL, STATUS_BEKERJA ,USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			) 
			VALUES (
				".$this->getField("ANAK_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("SUAMI_ISTRI_ID").",
				".$this->getField("PENDIDIKAN_ID").",
				'".$this->getField("NAMA")."',
				'".$this->getField("NOMOR_INDUK")."',
				'".$this->getField("TEMPAT_LAHIR")."',
				".$this->getField("TANGGAL_LAHIR").",
				'".$this->getField("JENIS_KELAMIN")."',
				".$this->getField("STATUS_KELUARGA").",
				".$this->getField("STATUS_TUNJANGAN").",
				'".$this->getField("PEKERJAAN")."',
				".$this->getField("AWAL_BAYAR").",
				".$this->getField("AKHIR_BAYAR").",
				".$this->getField("STATUS_NIKAH").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("STATUS_AKTIF").",
				".$this->getField("TANGGAL_MENINGGAL").",
				".$this->getField("STATUS_BEKERJA").",
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
		UPDATE validasi.ANAK
		SET    
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			SUAMI_ISTRI_ID= ".$this->getField("SUAMI_ISTRI_ID").",
			PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
			NAMA= '".$this->getField("NAMA")."',
			NOMOR_INDUK= '".$this->getField("NOMOR_INDUK")."',
			TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
			TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
			JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
			STATUS_KELUARGA= ".$this->getField("STATUS_KELUARGA").",
			STATUS_TUNJANGAN= ".$this->getField("STATUS_TUNJANGAN").",
			PEKERJAAN= '".$this->getField("PEKERJAAN")."',
			AWAL_BAYAR= ".$this->getField("AWAL_BAYAR").",
			AKHIR_BAYAR= ".$this->getField("AKHIR_BAYAR").",
			STATUS_NIKAH= ".$this->getField("STATUS_NIKAH").",
			STATUS_AKTIF= ".$this->getField("STATUS_AKTIF").",
			TANGGAL_MENINGGAL= ".$this->getField("TANGGAL_MENINGGAL").",
			STATUS_BEKERJA= ".$this->getField("STATUS_BEKERJA").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			VALIDASI= ".$this->getField("VALIDASI")."
		WHERE TEMP_VALIDASI_ID= ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatetanggalvalidasi()
	{
		$str = "		
		UPDATE validasi.ANAK 
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
		UPDATE validasi.ANAK 
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
        AND HAPUS_NAMA= 'ANAK' AND VALIDASI IS NULL
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
        AND HAPUS_NAMA= 'ANAK' AND VALIDASI IS NULL
        ";
				  
		$this->query = $str;
		// echo $str;exit;
        return $this->execQuery($str);
    }

	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TANGGAL_LAHIR ASC')
	{
		$str = "
		SELECT 
		A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, B.NAMA SUAMI_ISTRI_NAMA, A.PENDIDIKAN_ID, A.NAMA, A.NOMOR_INDUK, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, 
		A.STATUS_KELUARGA, A.STATUS_TUNJANGAN, A.PEKERJAAN, A.AWAL_BAYAR, A.AKHIR_BAYAR, A.STATUS_NIKAH, A.STATUS_BEKERJA, A.STATUS, A.STATUS_AKTIF,
		CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' ELSE 'Angkat' END STATUS_KELUARGA_NAMA,
		CASE A.STATUS_AKTIF WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' ELSE '' END STATUS_NAMA
		, A.TANGGAL_MENINGGAL, PA.ANAK_ID PENSIUN_ANAK_ID,C.NAMA PENDIDIKAN_NAMA, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM ANAK A
		LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		LEFT JOIN
		(
		SELECT PEGAWAI_ID, ANAK_ID
		FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK
		GROUP BY PEGAWAI_ID, ANAK_ID
		) PA ON A.PEGAWAI_ID = PA.PEGAWAI_ID AND A.ANAK_ID = PA.ANAK_ID
		LEFT JOIN PENDIDIKAN C ON C.PENDIDIKAN_ID = A.PENDIDIKAN_ID
		WHERE 1 = 1
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }


    function selectByParamsPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TANGGAL_LAHIR ASC')
	{
		$str = "
		SELECT 
		A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, B.NAMA SUAMI_ISTRI_NAMA, A.PENDIDIKAN_ID, A.NAMA, A.NOMOR_INDUK, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, 
		A.STATUS_KELUARGA, A.STATUS_TUNJANGAN, A.PEKERJAAN, A.AWAL_BAYAR, A.AKHIR_BAYAR, A.STATUS_NIKAH, A.STATUS_BEKERJA, A.STATUS, A.STATUS_AKTIF,
		CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' ELSE 'Angkat' END STATUS_KELUARGA_NAMA,
		CASE A.STATUS_AKTIF WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' ELSE '' END STATUS_NAMA
		, A.TANGGAL_MENINGGAL, PA.ANAK_ID PENSIUN_ANAK_ID,C.NAMA PENDIDIKAN_NAMA, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_anak('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		LEFT JOIN
		(
		SELECT PEGAWAI_ID, ANAK_ID
		FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK
		GROUP BY PEGAWAI_ID, ANAK_ID
		) PA ON A.PEGAWAI_ID = PA.PEGAWAI_ID AND A.ANAK_ID = PA.ANAK_ID
		LEFT JOIN PENDIDIKAN C ON C.PENDIDIKAN_ID = A.PENDIDIKAN_ID
		WHERE 1 = 1
		"; 

		foreach ($paramsArray as $key => $val)
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
		$str = "
				SELECT COUNT(A.ANAK_ID) AS ROWCOUNT 
				FROM ANAK A
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