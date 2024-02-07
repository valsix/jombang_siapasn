<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/EntityBio.php');

  class VSyncEmployee extends EntityBio{ 

	var $query;
    /**
    * Class constructor.
    **/
    function VSyncEmployee()
	{
      $this->EntityBio(); 
    }

    function insert()
	{
		$str = "
		INSERT INTO SYNC_EMPLOYEE
		(
			ID, EMP_CODE, FLAG, DEPT_CODE, DEPT_NAME, FIRST_NAME, AREA_CODE, AREA_NAME, ACTIVE_STATUS, MULTI_AREA
		)
		VALUES 
		(
			".$this->getField("ID")."
			, '".$this->getField("ID")."'
			, 0
			, '1', 'Pemerintah Kabupaten Jombang'
			, '".$this->getField("FIRST_NAME")."'
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, ".$this->getField("ACTIVE_STATUS")."
			, ".$this->getField("MULTI_AREA")."
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
		UPDATE SYNC_EMPLOYEE
		SET
			FLAG= 0
			, FIRST_NAME= '".$this->getField("FIRST_NAME")."'
			, AREA_CODE= '".$this->getField("AREA_CODE")."'
			, AREA_NAME= '".$this->getField("AREA_NAME")."'
			, ACTIVE_STATUS= ".$this->getField("ACTIVE_STATUS")."
			, MULTI_AREA= ".$this->getField("MULTI_AREA")."
		WHERE ID= ".$this->getField("ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateactivestatus()
	{
		$str1 = "
		UPDATE PERSONNEL_EMPLOYEE
		SET
			IS_ACTIVE= ".$this->getField("IS_ACTIVE")."
		WHERE EMP_CODE= '".$this->getField("EMP_CODE")."'
		"; 
		$this->query = $str1;
		// echo $str;exit();
		return $this->execQuery($str1);
	}

	function resetarea()
	{
		$str = "
		UPDATE SYNC_EMPLOYEE
		SET
			FLAG = 0
			, UPDATE_TIME = NULL
			, SYNC_RET = NULL
			, AREA_CODE= '".$this->getField("AREA_CODE")."'
			, AREA_NAME= '".$this->getField("AREA_NAME")."'
			, MULTI_AREA= ".$this->getField("MULTI_AREA")."
		WHERE ID= ".$this->getField("ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    		A.ID, A.POST_TIME, A.FLAG, A.UPDATE_TIME, A.SYNC_RET, A.EMP_CODE, A.FIRST_NAME, A.LAST_NAME, A.DEPT_CODE, A.DEPT_NAME
    		, A.JOB_CODE, A.JOB_NAME, A.AREA_CODE, A.AREA_NAME, A.CARD_NO, A.MULTI_AREA, A.HIRE_DATE, A.GENDER
    		, A.BIRTHDAY, A.EMAIL, A.ACTIVE_STATUS
    	FROM SYNC_EMPLOYEE A
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
		FROM SYNC_EMPLOYEE A
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