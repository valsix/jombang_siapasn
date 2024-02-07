<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class KlarifikasiRequired extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function KlarifikasiRequired()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$str = "
		INSERT INTO presensi.klarifikasi_required
		(
			MENU_ID, STATUS_UPLOAD, BATAS_ENTRI
		)
		VALUES 
		(
			'".$this->getField("MENU_ID")."'
			, '".$this->getField("STATUS_UPLOAD")."'
			, ".$this->getField("BATAS_ENTRI")."
		)
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE presensi.klarifikasi_required
		SET
			STATUS_UPLOAD= '".$this->getField("STATUS_UPLOAD")."'
			, BATAS_ENTRI= ".$this->getField("BATAS_ENTRI")."
		WHERE MENU_ID= '".$this->getField("MENU_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order=" ORDER BY A.MENU_ID ")
    {
    	$str = "
    	SELECT
    	A.MENU_ID, A.NAMA, B.MENU_ID MENU_ROW_ID
    	, B.STATUS_UPLOAD, CASE B.STATUS_UPLOAD WHEN '1' THEN 'Tidak Required' ELSE 'Required' END STATUS_UPLOAD_NAMA
    	, COALESCE(B.BATAS_ENTRI,0) BATAS_ENTRI
    	FROM menu A
    	LEFT JOIN presensi.klarifikasi_required B ON A.MENU_ID = B.MENU_ID
    	WHERE 1=1 AND MENU_PARENT_ID = '51'
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
		FROM menu A
    	LEFT JOIN presensi.klarifikasi_required B ON A.MENU_ID = B.MENU_ID
    	WHERE 1=1 AND MENU_PARENT_ID = '51' ".$statement; 
		
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