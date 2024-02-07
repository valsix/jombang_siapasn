<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class PersonnelEmployee extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PersonnelEmployee()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("ID", $this->getNextId("ID","bio.PERSONNEL_EMPLOYEE"));

		$str = "
		INSERT INTO bio.PERSONNEL_EMPLOYEE
		(
			ID, EMP_CODE, FIRST_NAME, DEPT_CODE, DEPT_NAME, JUMLAH_AREA, ACTIVE_STATUS, AREA_CODE, AREA_NAME, DEVICE_PASSWORD, STATUS_INTEGRASI
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, '".$this->getField("EMP_CODE")."'
			, '".$this->getField("FIRST_NAME")."'
			, '1', 'Pemerintah Kabupaten Jombang'
			, ".$this->getField("JUMLAH_AREA")."
			, ".$this->getField("ACTIVE_STATUS")."
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, '".$this->getField("DEVICE_PASSWORD")."'
			, 1
		)
		";

		$this->query = $str;
		$this->id = $this->getField("ID");
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE bio.PERSONNEL_EMPLOYEE
		SET
			EMP_CODE= '".$this->getField("EMP_CODE")."',
			FIRST_NAME= '".$this->getField("FIRST_NAME")."',
			JUMLAH_AREA= ".$this->getField("JUMLAH_AREA").",
			ACTIVE_STATUS= ".$this->getField("ACTIVE_STATUS").",
			AREA_CODE= '".$this->getField("AREA_CODE")."',
			AREA_NAME= '".$this->getField("AREA_NAME")."',
			DEVICE_PASSWORD= '".$this->getField("DEVICE_PASSWORD")."',
			STATUS_INTEGRASI= 1
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateIdBio()
	{
		$str = "
		UPDATE bio.PERSONNEL_EMPLOYEE
		SET
			ID_BIO= ".$this->getField("ID_BIO")."
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatePinIdBio()
	{
		$str = "
		UPDATE bio.PERSONNEL_EMPLOYEE
		SET
			ID_BIO= ".$this->getField("ID_BIO")."
			, DEVICE_PASSWORD= '".$this->getField("DEVICE_PASSWORD")."'
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsSync($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT
			P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
			, P.FIRST_NAME
			, CASE WHEN B.IS_ACTIVE IS TRUE THEN 1 ELSE 0 END IS_AKTIF
			, B.DEVICE_PASSWORD
			, P.JABATAN_RIWAYAT_NAMA, P.JABATAN_RIWAYAT_ESELON, P.JABATAN_RIWAYAT_TMT
			, P.ESELON_ID, P.PANGKAT_ID, P.TMT_PANGKAT, P.SATUAN_KERJA_ID
			, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, B1.ID V_PERSONNEL_EMPLOYEE_ID
			, A.ID, A.EMP_CODE, A.FIRST_NAME, A.STATUS_INTEGRASI, A.ID_BIO, A.DEPT_CODE, A.DEPT_NAME, A.JUMLAH_AREA, A.ACTIVE_STATUS
			, A.AREA_CODE, A.AREA_NAME
			, COALESCE(C1.JUMLAH,0) JUMLAH_FP, COALESCE(C2.JUMLAH,0) JUMLAH_FF
		FROM
		(
			SELECT
			PEGAWAI_ID, A.NIP_BARU
			, CAST(A.NAMA AS CHARACTER VARYING(25)) FIRST_NAME
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
			WHERE 1=1
		) P
		LEFT JOIN bio.PERSONNEL_EMPLOYEE A ON P.PEGAWAI_ID = CAST(A.EMP_CODE AS NUMERIC)
		LEFT JOIN bio.V_PERSONNEL_EMPLOYEE B ON P.PEGAWAI_ID = CAST(B.EMP_CODE AS NUMERIC)
		LEFT JOIN bio.V_SYNC_EMPLOYEE B1 ON P.PEGAWAI_ID = CAST(B1.EMP_CODE AS NUMERIC)
		LEFT JOIN bio.V_GROUP_ICLOCK_BIODATA C1 ON C1.EMPLOYEE_ID = B.ID AND C1.BIO_TYPE = 1
		LEFT JOIN bio.V_GROUP_ICLOCK_BIODATA C2 ON C2.EMPLOYEE_ID = B.ID AND C2.BIO_TYPE = 2
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.ID, A.EMP_CODE, A.FIRST_NAME, A.ID_BIO, A.DEVICE_PASSWORD
    	FROM bio.PERSONNEL_EMPLOYEE A
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

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM bio.PERSONNEL_EMPLOYEE A
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

    function selectByParamsMonitoringbAK($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.FIRST_NAME")
    {
    	$str = "
    	SELECT
		B.ID, A.EMP_CODE, A.FIRST_NAME, A.ID_BIO
		, CASE WHEN B.ID IS NOT NULL AND A.FIRST_NAME = B.FIRST_NAME THEN 1 ELSE 0 END STATUS_INTEGRASI_ID
		, CASE WHEN B.ID IS NOT NULL AND A.FIRST_NAME = B.FIRST_NAME THEN 'Sudah' ELSE 'Belum' END STATUS_INTEGRASI_NAMA
		FROM
		(
			SELECT B.ID ID_BIO, A.EMP_CODE, A.FIRST_NAME
			FROM
			(
				SELECT
				PEGAWAI_ID EMP_CODE, CAST(NAMA AS CHARACTER VARYING(25)) FIRST_NAME, 
				CAST(AMBIL_SATKER_ID_INDUK(SATUAN_KERJA_ID) AS CHARACTER VARYING(30)) AREA_CODE
				FROM PEGAWAI A
				WHERE STATUS_PEGAWAI_ID IN (1,2)
			) A
			LEFT JOIN bio.V_PERSONNEL_EMPLOYEE B ON A.EMP_CODE = CAST(B.EMP_CODE AS NUMERIC)
			WHERE EXISTS (SELECT 1 FROM bio.PERSONNEL_AREA X WHERE A.AREA_CODE = X.AREA_CODE)
		) A
		LEFT JOIN bio.PERSONNEL_EMPLOYEE B ON A.ID_BIO = B.ID_BIO
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

    function getAmbilSatkerMesin($val)
	{
		$str = "SELECT presensi.AMBIL_SATKER_MESIN(".$val.") ROWCOUNT";
		
    	$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }

    function getAmbilSatkerMesinId($val)
	{
		$str = "SELECT presensi.AMBIL_SATKER_MESIN_ID(".$val.") ROWCOUNT";
		
    	$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }

    function getPegawaiAreaAbsensi($val)
	{
		$str = "SELECT presensi.P_AREA_ABSENSI(".$val.") ROWCOUNT";
		
    	$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.ESELON_ID ASC, A.PANGKAT_ID DESC, A.TMT_PANGKAT ASC")
    {
    	$str = "
    	SELECT
    	A.ID_BIO, A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
    	, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA, A.SATUAN_KERJA_ID
    	, IS_AKTIF, JUMLAH_FP, JUMLAH_FF
    	, CASE WHEN B.ID IS NOT NULL AND A.FIRST_NAME = B.FIRST_NAME THEN 1 ELSE 0 END STATUS_INTEGRASI_ID
    	, CASE WHEN B.ID IS NOT NULL AND A.FIRST_NAME = B.FIRST_NAME THEN 'Sudah' ELSE 'Belum' END STATUS_INTEGRASI_NAMA
		FROM
		(
			SELECT
			B.ID ID_BIO, A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
			, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA, A.SATUAN_KERJA_ID
			, CASE WHEN B.IS_ACTIVE IS TRUE THEN 1 ELSE 0 END IS_AKTIF
			, COALESCE(C1.JUMLAH,0) JUMLAH_FP, COALESCE(C2.JUMLAH,0) JUMLAH_FF
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
			LEFT JOIN bio.V_PERSONNEL_EMPLOYEE B ON A.EMP_CODE = CAST(B.EMP_CODE AS NUMERIC)
			LEFT JOIN bio.V_GROUP_ICLOCK_BIODATA C1 ON C1.EMPLOYEE_ID = B.ID AND C1.BIO_TYPE = 1
			LEFT JOIN bio.V_GROUP_ICLOCK_BIODATA C2 ON C2.EMPLOYEE_ID = B.ID AND C2.BIO_TYPE = 2
		) A
		LEFT JOIN bio.PERSONNEL_EMPLOYEE B ON A.ID_BIO = B.ID_BIO
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
			B.ID ID_BIO, A.EMP_CODE, A.FIRST_NAME, A.NIP_BARU
			, CASE WHEN B.IS_ACTIVE IS TRUE THEN 1 ELSE 0 END IS_AKTIF
			, A.NAMA_LENGKAP
			FROM
			(
				SELECT
				PEGAWAI_ID EMP_CODE, CAST(NAMA AS CHARACTER VARYING(25)) FIRST_NAME, A.NIP_BARU
				, CAST(AMBIL_SATKER_ID_INDUK(SATUAN_KERJA_ID) AS CHARACTER VARYING(30)) AREA_CODE
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				WHERE STATUS_PEGAWAI_ID IN (1,2)
			) A
			LEFT JOIN bio.V_PERSONNEL_EMPLOYEE B ON A.EMP_CODE = CAST(B.EMP_CODE AS NUMERIC)
		) A
		LEFT JOIN bio.PERSONNEL_EMPLOYEE B ON A.ID_BIO = B.ID_BIO
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
	
  } 
?>