<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class PersonnelArea extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PersonnelArea()
	{
      $this->Entity(); 
    }

    function insertAwalAreaIntegrasi()
	{
		$str = "
		INSERT INTO bio.PERSONNEL_AREA(ID, AREA_CODE, DEPT_CODE, AREA_NAME)
		SELECT ID, CAST(ID AS TEXT) AREA_CODE, DEPT_CODE, CAST(AREA_NAME AS CHARACTER VARYING(30)) AREA_NAME
		FROM
		(
			SELECT 
			row_number() over(ORDER BY CAST(DEPT_CODE AS NUMERIC)) + 1 ID
			, DEPT_CODE, 'Area ' || DEPT_NAME AREA_NAME
			from bio.personnel_department
			ORDER BY CAST(DEPT_CODE AS NUMERIC)
		) A
		WHERE NOT EXISTS
		(
			SELECT 1
			FROM
			(
				SELECT DEPT_CODE, AREA_NAME FROM bio.PERSONNEL_AREA
			) X
			WHERE A.DEPT_CODE = X.DEPT_CODE AND CAST(A.AREA_NAME AS CHARACTER VARYING(30)) = X.AREA_NAME
		)
		ORDER BY ID
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
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
		UPDATE bio.PERSONNEL_AREA
		SET 
			ID_BIO = ".$this->getField("ID_BIO").",
			STATUS_INTEGRASI = 1
		WHERE ID = '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
  } 
?>