<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class PersonnelEmployeeArea extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PersonnelEmployeeArea()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("ID", $this->getNextId("ID","bio.PERSONNEL_EMPLOYEE_AREA"));

		$str = "
		INSERT INTO bio.PERSONNEL_EMPLOYEE_AREA
		(
			ID, EMPLOYEE_ID, AREA_ID, STATUS_DEFAULT, PERSONNEL_EMPLOYEE_ID, AREA_CODE, AREA_NAME, EMP_CODE, UPDATEBIO
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, ".$this->getField("EMPLOYEE_ID")."
			, ".$this->getField("AREA_ID")."
			, ".$this->getField("STATUS_DEFAULT")."
			, ".$this->getField("PERSONNEL_EMPLOYEE_ID")."
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, '".$this->getField("EMP_CODE")."'
			, ".$this->getField("UPDATEBIO")."
		)
		";

		$this->query = $str;
		$this->id = $this->getField("ID");
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertdatalama()
	{
		$str = "
		INSERT INTO bio.PERSONNEL_EMPLOYEE_AREA
		(
			ID, EMPLOYEE_ID, AREA_ID, STATUS_DEFAULT, PERSONNEL_EMPLOYEE_ID, AREA_CODE, AREA_NAME, EMP_CODE, UPDATEBIO
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, ".$this->getField("EMPLOYEE_ID")."
			, ".$this->getField("AREA_ID")."
			, ".$this->getField("STATUS_DEFAULT")."
			, ".$this->getField("PERSONNEL_EMPLOYEE_ID")."
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, '".$this->getField("EMP_CODE")."'
			, ".$this->getField("UPDATEBIO")."
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
		UPDATE bio.PERSONNEL_EMPLOYEE_AREA
		SET
			EMPLOYEE_ID= ".$this->getField("EMPLOYEE_ID")."
			, AREA_ID= ".$this->getField("AREA_ID")."
		WHERE ID= ".$this->getField("ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatebio()
	{
		$str = "
		UPDATE bio.PERSONNEL_EMPLOYEE_AREA
		SET
			UPDATEBIO= NULL
		WHERE ID= ".$this->getField("ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
	{
        $str= "
        DELETE FROM bio.PERSONNEL_EMPLOYEE_AREA
        WHERE 
        PERSONNEL_EMPLOYEE_ID= ".$this->getField("PERSONNEL_EMPLOYEE_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParamsSimple($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.STATUS_DEFAULT, A.ID")
    {
    	$str = "
    	SELECT 
    	ID, EMPLOYEE_ID, AREA_ID, STATUS_DEFAULT, PERSONNEL_EMPLOYEE_ID, AREA_CODE, AREA_NAME, EMP_CODE, UPDATEBIO
    	FROM bio.PERSONNEL_EMPLOYEE_AREA A
    	WHERE 1 = 1
    	"; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
    	// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.STATUS_DEFAULT, A.ID")
    {
    	// $str = "
    	// SELECT 
    	// ID, EMPLOYEE_ID, AREA_ID, STATUS_DEFAULT, PERSONNEL_EMPLOYEE_ID, AREA_CODE, AREA_NAME, EMP_CODE, UPDATEBIO
    	// FROM bio.PERSONNEL_EMPLOYEE_AREA A
    	// WHERE 1 = 1
    	// "; 

    	$str = "
    	SELECT
		A.ID, VBIO.EMPLOYEE_ID, VBIO.AREA_ID, A.STATUS_DEFAULT, A.PERSONNEL_EMPLOYEE_ID, VBIO.AREA_CODE, VBIO.AREA_NAME, VBIO.EMP_CODE, A.UPDATEBIO
		FROM
		(
			SELECT
			A.EMPLOYEE_ID, A.AREA_ID, B.AREA_CODE, B.AREA_NAME, C.EMP_CODE
			FROM bio.V_PERSONNEL_EMPLOYEE_AREA A
			INNER JOIN bio.V_PERSONNEL_AREA B ON A.AREA_ID = B.ID
			INNER JOIN bio.V_PERSONNEL_EMPLOYEE C ON A.EMPLOYEE_ID = C.ID
			WHERE 1=1
		) VBIO
		LEFT JOIN bio.PERSONNEL_EMPLOYEE_AREA A ON A.EMP_CODE = VBIO.EMP_CODE AND A.EMPLOYEE_ID = VBIO.EMPLOYEE_ID AND A.AREA_ID = VBIO.AREA_ID
		WHERE 1 = 1
    	";

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
    	// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM bio.PERSONNEL_EMPLOYEE_AREA A
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
	
  } 
?>