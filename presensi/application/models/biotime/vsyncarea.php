<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/EntityBio.php');

  class VSyncArea extends EntityBio{ 

	var $query;
    /**
    * Class constructor.
    **/
    function VSyncArea()
	{
      $this->EntityBio(); 
    }

    function insert()
	{
		$str = "
		INSERT INTO SYNC_AREA
		(
			ID, FLAG, AREA_CODE, AREA_NAME
		)
		VALUES 
		(
			".$this->getField("ID")."
			, 0
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
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
		UPDATE SYNC_AREA
		SET
			FLAG= 0,
			AREA_CODE= '".$this->getField("AREA_CODE")."',
			AREA_NAME= '".$this->getField("AREA_NAME")."'
		WHERE ID= ".$this->getField("ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function insertDetil()
	{
		$str = "
		INSERT INTO SYNC_AREA
		(
			ID, FLAG, AREA_CODE, AREA_NAME
		)
		VALUES 
		(
			".$this->getField("ID")."
			, 0
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
		)
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updateDetil()
	{
		$str = "
		UPDATE SYNC_AREA
		SET
			FLAG= 0,
			AREA_NAME= '".$this->getField("AREA_NAME")."'
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
    	A.ID, A.POST_TIME, A.FLAG, A.UPDATE_TIME, A.SYNC_RET, A.AREA_CODE, A.AREA_NAME
    	FROM SYNC_AREA A
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
		FROM SYNC_AREA A
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