<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SettingKlarifikasiPegawai extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SettingKlarifikasiPegawai()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$str = "
		INSERT INTO presensi.SETTING_KLARIFIKASI_PEGAWAI
		(
			PEGAWAI_ID
		)
		VALUES 
		(
			".$this->getField("PEGAWAI_ID")."
		)
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
	{
        $str= "
        DELETE FROM presensi.SETTING_KLARIFIKASI_PEGAWAI
        WHERE 
        PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function deleteIn()
	{
        $str= "
        DELETE FROM presensi.SETTING_KLARIFIKASI_PEGAWAI
        WHERE 
        PEGAWAI_ID IN (".$this->getField("PEGAWAI_ID").")"; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC")
    {
    	$str = "
    	SELECT
		A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
		, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA, A.SATUAN_KERJA_ID
		FROM
		(
			SELECT
			A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
			, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA, A.SATUAN_KERJA_ID
			, A.ESELON_ID, A.PANGKAT_ID, A.TMT_PANGKAT
			FROM
			(
				SELECT
				PEGAWAI_ID EMP_CODE, CAST(NAMA AS CHARACTER VARYING(25)) FIRST_NAME, A.NIP_BARU
				, CAST(AMBIL_SATKER_ID_INDUK(SATUAN_KERJA_ID) AS CHARACTER VARYING(30)) AREA_CODE
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON
				, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
				, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				WHERE STATUS_PEGAWAI_ID IN (1,2)
			) A
		) A
		WHERE 1=1
    	"; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
    	// echo $order;exit();
    	// echo $str;exit();
    	return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
			, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA, A.SATUAN_KERJA_ID
			, A.ESELON_ID, A.PANGKAT_ID, A.TMT_PANGKAT
			FROM
			(
				SELECT
				PEGAWAI_ID EMP_CODE, CAST(NAMA AS CHARACTER VARYING(25)) FIRST_NAME, A.NIP_BARU
				, CAST(AMBIL_SATKER_ID_INDUK(SATUAN_KERJA_ID) AS CHARACTER VARYING(30)) AREA_CODE
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON
				, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
				, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				WHERE STATUS_PEGAWAI_ID IN (1,2)
			) A
		) A
		WHERE 1=1 ".$statement; 
		
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

    function getSatkerNamaDetil($val)
	{
		$str = "SELECT AMBIL_SATKER_NAMA_DETIL(".$val.") ROWCOUNT";
		
    	$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }
	
  } 
?>