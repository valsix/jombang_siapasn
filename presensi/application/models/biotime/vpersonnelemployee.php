<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/EntityBio.php');

  class VPersonnelEmployee extends EntityBio{ 

	var $query;
    /**
    * Class constructor.
    **/
    function VPersonnelEmployee()
	{
      $this->EntityBio(); 
    }

    function insert()
	{
		$this->setField("ID", $this->getNextId("ID","PERSONNEL_EMPLOYEE"));

		$str = "
		INSERT INTO PERSONNEL_EMPLOYEE
		(
			ID, EMP_CODE, FIRST_NAME
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, '".$this->getField("EMP_CODE")."'
			, '".$this->getField("FIRST_NAME")."'
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
		UPDATE PERSONNEL_EMPLOYEE
		SET
			EMP_CODE= '".$this->getField("EMP_CODE")."',
			FIRST_NAME= '".$this->getField("FIRST_NAME")."'
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function updatePin()
	{
		$str = "
		UPDATE PERSONNEL_EMPLOYEE
		SET
			DEVICE_PASSWORD= '".$this->getField("DEVICE_PASSWORD")."'
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
    	A.ID, A.EMP_CODE, A.FIRST_NAME
    	FROM PERSONNEL_EMPLOYEE A
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