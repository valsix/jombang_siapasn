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

    function insertAwalPegawaiIntegrasi()
	{
		$str = "
		INSERT INTO bio.PERSONNEL_EMPLOYEE(EMP_CODE, FIRST_NAME, DEPARTMENT_ID)
		SELECT A.EMP_CODE, A.FIRST_NAME, A.DEPARTMENT_ID DEPARTMENT_ID 
		FROM
		(
			SELECT CAST(A.PEGAWAI_ID AS CHARACTER VARYING(20)) EMP_CODE
			, CAST(
			(CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END)
			AS CHARACTER VARYING(25)) FIRST_NAME
			, AMBIL_SATKER_ID_INDUK(A.SATUAN_KERJA_ID) DEPARTMENT_ID 
			FROM PEGAWAI A
			WHERE 1=1 AND STATUS_PEGAWAI_ID IN (1,2)
		) A
		INNER JOIN bio.PERSONNEL_DEPARTMENT B ON A.DEPARTMENT_ID  = CAST(B.DEPT_CODE AS INTEGER)
		WHERE 1=1 AND B.ID_BIO IS NOT NULL
		AND NOT EXISTS
		(
			SELECT 1
			FROM bio.PERSONNEL_EMPLOYEE X
			WHERE X.EMP_CODE = A.EMP_CODE
		)
		"; 
		$this->query = $str;
		// echo $str;exit;
		$this->execQuery($str);

		$str1 = "
		INSERT INTO bio.PERSONNEL_EMPLOYEE_AREA(EMP_CODE, DEPT_CODE, AREA_CODE)
		SELECT A.EMP_CODE, B.DEPT_CODE, C.AREA_CODE
		FROM
		(
			SELECT CAST(A.PEGAWAI_ID AS CHARACTER VARYING(20)) EMP_CODE
			, AMBIL_SATKER_ID_INDUK(A.SATUAN_KERJA_ID) DEPARTEMENT_ID
			FROM PEGAWAI A
			WHERE 1=1 AND STATUS_PEGAWAI_ID IN (1,2)
		) A
		INNER JOIN bio.PERSONNEL_DEPARTMENT B ON A.DEPARTEMENT_ID = CAST(B.DEPT_CODE AS INTEGER)
		INNER JOIN 
		(
			SELECT DEPT_CODE, AREA_CODE FROM bio.PERSONNEL_AREA
		) C ON B.DEPT_CODE = C.DEPT_CODE
		WHERE 1=1
		AND NOT EXISTS
		(
			SELECT 1
			FROM bio.PERSONNEL_EMPLOYEE_AREA X
			WHERE X.EMP_CODE = A.EMP_CODE
			AND X.DEPT_CODE = B.DEPT_CODE AND X.AREA_CODE = C.AREA_CODE
		)
		"; 
		$this->query = $str1;
		// echo $str;exit;
		return $this->execQuery($str1);
    }

    function selectByParamsIntegrasi($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.EMP_CODE, A.FIRST_NAME, C.ID_BIO AREA, C1.ID_BIO DEPARTMENT, B.DEPT_CODE, C.AREA_CODE
    	FROM bio.PERSONNEL_EMPLOYEE A
    	INNER JOIN bio.PERSONNEL_EMPLOYEE_AREA B ON A.EMP_CODE = B.EMP_CODE AND A.DEPARTMENT_ID  = CAST(B.DEPT_CODE AS INTEGER)
    	INNER JOIN bio.PERSONNEL_AREA C ON C.DEPT_CODE = B.DEPT_CODE AND C.AREA_CODE = B.AREA_CODE
    	INNER JOIN bio.PERSONNEL_DEPARTMENT C1 ON C1.DEPT_CODE = B.DEPT_CODE
    	WHERE 1 = 1
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.ID, A.AREA_CODE, A.DEPT_CODE, A.AREA_NAME, A.ID_BIO
    	FROM bio.PERSONNEL_AREA A
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
		FROM bio.PERSONNEL_AREA A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY B.DEPT_NAME, A.AREA_NAME")
    {
    	$str = "
    	SELECT 
    	B.DEPT_NAME, A.ID, A.AREA_CODE, A.DEPT_CODE, A.AREA_NAME
    	, A.STATUS_INTEGRASI
    	, CASE A.STATUS_INTEGRASI WHEN 1 THEN 'Sudah' ELSE 'Belum' END STATUS_INTEGRASI_NAMA
    	, A.ID_BIO
    	FROM bio.PERSONNEL_AREA A
    	INNER JOIN bio.PERSONNEL_DEPARTMENT B ON A.DEPT_CODE = B.DEPT_CODE
    	WHERE 1 = 1
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
		FROM bio.PERSONNEL_AREA A
    	INNER JOIN bio.PERSONNEL_DEPARTMENT B ON A.DEPT_CODE = B.DEPT_CODE
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

    function updateIdBio()
	{
		$str = "
		UPDATE bio.PERSONNEL_EMPLOYEE
		SET 
			ID_BIO = ".$this->getField("ID_BIO").",
			STATUS_INTEGRASI = 1
		WHERE EMP_CODE = '".$this->getField("EMP_CODE")."'
		"; 
		$this->query = $str;
		// echo $str;
		$this->execQuery($str);

		$str1 = "
		UPDATE bio.PERSONNEL_EMPLOYEE_AREA
		SET 
			STATUS_INTEGRASI = 1
		WHERE EMP_CODE = '".$this->getField("EMP_CODE")."'
		AND DEPT_CODE = '".$this->getField("DEPT_CODE")."'
		AND AREA_CODE = '".$this->getField("AREA_CODE")."'
		"; 
		// echo $str1;
		$this->query = $str1;
		return $this->execQuery($str1);
    }
	
  } 
?>