<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class PersonnelDepartment extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PersonnelDepartment()
	{
      $this->Entity(); 
    }

    function insertIntegrasi()
	{
		$str = "
		INSERT INTO bio.PERSONNEL_DEPARTMENT(DEPT_CODE, DEPT_NAME)
		SELECT CAST(SATUAN_KERJA_ID AS CHARACTER VARYING(50)) DEPT_CODE, CAST(NAMA AS CHARACTER VARYING(100)) DEPT_NAME
		FROM SATUAN_KERJA A
		WHERE STATUS_MESIN_POSISI = 1
		AND NOT EXISTS
		(
			SELECT 1
			FROM bio.PERSONNEL_DEPARTMENT X
			WHERE CAST(X.DEPT_CODE AS NUMERIC) = A.SATUAN_KERJA_ID
		)
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.DEPT_CODE, A.DEPT_NAME, A.STATUS_INTEGRASI, A.ID_BIO
    	FROM bio.PERSONNEL_DEPARTMENT A
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
		FROM bio.PERSONNEL_DEPARTMENT A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.DEPT_CODE, A.DEPT_NAME, A.STATUS_INTEGRASI
    	, CASE A.STATUS_INTEGRASI WHEN 1 THEN 'Sudah' ELSE 'Belum' END STATUS_INTEGRASI_NAMA
    	, A.ID_BIO
    	FROM bio.PERSONNEL_DEPARTMENT A
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

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM bio.PERSONNEL_DEPARTMENT A
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
		UPDATE bio.PERSONNEL_DEPARTMENT
		SET 
			ID_BIO = ".$this->getField("ID_BIO").",
			STATUS_INTEGRASI = 1
		WHERE DEPT_CODE = '".$this->getField("DEPT_CODE")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
  } 
?>