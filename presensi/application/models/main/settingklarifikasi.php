<? 
  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class SettingKlarifikasi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SettingKlarifikasi()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("SETTING_KLARIFIKASI_ID", $this->getNextId("SETTING_KLARIFIKASI_ID","presensi.SETTING_KLARIFIKASI"));

		$str = "
		INSERT INTO presensi.SETTING_KLARIFIKASI
		(
			SETTING_KLARIFIKASI_ID, MASA_BERLAKU_AWAL, MASA_BERLAKU_AKHIR
		)
		VALUES 
		(
			'".$this->getField("SETTING_KLARIFIKASI_ID")."'
			, ".$this->getField("MASA_BERLAKU_AWAL")."
			, ".$this->getField("MASA_BERLAKU_AKHIR")."
		)
		";

		$this->query = $str;
		$this->id = $this->getField("SETTING_KLARIFIKASI_ID");
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function update()
	{
		$str = "
		UPDATE presensi.SETTING_KLARIFIKASI
		SET
			MASA_BERLAKU_AWAL= ".$this->getField("MASA_BERLAKU_AWAL")."
			, MASA_BERLAKU_AKHIR= ".$this->getField("MASA_BERLAKU_AKHIR")."
		WHERE SETTING_KLARIFIKASI_ID= ".$this->getField("SETTING_KLARIFIKASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function delete()
	{
        $str= "
        DELETE FROM presensi.SETTING_KLARIFIKASI
        WHERE 
        SETTING_KLARIFIKASI_ID= ".$this->getField("SETTING_KLARIFIKASI_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	SETTING_KLARIFIKASI_ID, MASA_BERLAKU_AWAL, MASA_BERLAKU_AKHIR
    	, TO_CHAR(A.MASA_BERLAKU_AWAL, 'YYYY-MM-DD') INFO_MASA_BERLAKU_AWAL
    	, TO_CHAR(A.MASA_BERLAKU_AKHIR, 'YYYY-MM-DD') INFO_MASA_BERLAKU_AKHIR
    	FROM presensi.SETTING_KLARIFIKASI A
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
		FROM presensi.SETTING_KLARIFIKASI A
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