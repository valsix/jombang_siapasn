<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/EntityBio.php');

  class VPersonnelArea extends EntityBio{ 

	var $query;
    /**
    * Class constructor.
    **/
    function VPersonnelArea()
	{
      $this->EntityBio(); 
    }

    function insert()
	{
		$this->setField("ID", $this->getNextId("ID","PERSONNEL_AREA"));

		$str = "
		INSERT INTO PERSONNEL_AREA
		(
			ID, AREA_CODE, AREA_NAME, IS_DEFAULT
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, FALSE
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
		UPDATE PERSONNEL_AREA
		SET
			AREA_CODE= '".$this->getField("AREA_CODE")."',
			AREA_NAME= '".$this->getField("AREA_NAME")."'
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.ID, A.AREA_CODE, A.AREA_NAME
    	FROM PERSONNEL_AREA A
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