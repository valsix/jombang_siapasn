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

    function insert()
	{
		$this->setField("ID", $this->getNextId("ID","bio.PERSONNEL_AREA"));

		$str = "
		INSERT INTO bio.PERSONNEL_AREA
		(
			ID, AREA_CODE, AREA_PARENT_CODE, AREA_NAME, STATUS_INTEGRASI, ID_BIO
		)
		VALUES 
		(
			'".$this->getField("ID")."'
			, '".$this->getField("AREA_CODE")."'
			, '".$this->getField("AREA_PARENT_CODE")."'
			, '".$this->getField("AREA_NAME")."'
			, ".$this->getField("STATUS_INTEGRASI")."
			, '".$this->getField("ID_BIO")."'
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
		UPDATE bio.PERSONNEL_AREA
		SET
			AREA_NAME= '".$this->getField("AREA_NAME")."',
			STATUS_INTEGRASI= ".$this->getField("STATUS_INTEGRASI").",
			ID_BIO= '".$this->getField("ID_BIO")."'
		WHERE ID= '".$this->getField("ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
	}

	function selectByParamsArea($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.ID, A.AREA_CODE, A.AREA_PARENT_CODE, A.AREA_NAME, A.ID_BIO
    	, B.NAMA_SINGKAT, B.SATUAN_KERJA_ID
    	, SYNCAREA.ID SYNC_AREA_ID
    	FROM bio.PERSONNEL_AREA A
    	INNER JOIN SATUAN_KERJA B ON CAST(A.AREA_PARENT_CODE AS NUMERIC) = B.SATUAN_KERJA_ID
    	LEFT JOIN bio.V_SYNC_AREA SYNCAREA ON A.AREA_CODE = SYNCAREA.AREA_CODE
    	WHERE 1 = 1
    	"; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
    	// echo $str;exit();
    	return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str = "
    	SELECT 
    	A.ID, A.AREA_CODE, A.AREA_PARENT_CODE, A.AREA_NAME, A.ID_BIO
    	, SYNCAREA.ID SYNC_AREA_ID
    	FROM bio.PERSONNEL_AREA A
    	LEFT JOIN bio.V_SYNC_AREA SYNCAREA ON A.AREA_CODE = SYNCAREA.AREA_CODE
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="ORDER BY A.SATUAN_KERJA_NAMA")
    {
    	$str = "
    	SELECT
    	B.ID, A.SATUAN_KERJA_ID, A.SATUAN_KERJA_NAMA, A.NAMA_SINGKAT, A.ID_BIO, A.AREA_NAMA
    	, CASE WHEN B.ID IS NOT NULL AND A.AREA_NAMA = A.NAMA_SINGKAT THEN 1 ELSE 0 END STATUS_INTEGRASI_ID
    	, CASE WHEN B.ID IS NOT NULL AND A.AREA_NAMA = A.NAMA_SINGKAT THEN 'Sudah' ELSE 'Belum' END STATUS_INTEGRASI_NAMA
		FROM
		(
			SELECT A.SATUAN_KERJA_ID, A.NAMA SATUAN_KERJA_NAMA, A.NAMA_SINGKAT, B.ID ID_BIO, B.AREA_NAME AREA_NAMA
			FROM SATUAN_KERJA A
			LEFT JOIN bio.V_PERSONNEL_AREA B ON A.SATUAN_KERJA_ID = CAST(B.AREA_CODE AS NUMERIC)
			WHERE 1=1 AND A.STATUS_MESIN_POSISI = 1
		) A
		LEFT JOIN bio.PERSONNEL_AREA B ON A.ID_BIO = B.ID_BIO
		WHERE 1=1
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
		FROM
		(
			SELECT A.SATUAN_KERJA_ID, A.NAMA SATUAN_KERJA_NAMA, A.NAMA_SINGKAT, B.ID ID_BIO, B.AREA_NAME AREA_NAMA
			FROM SATUAN_KERJA A
			LEFT JOIN bio.V_PERSONNEL_AREA B ON A.SATUAN_KERJA_ID = CAST(B.AREA_CODE AS NUMERIC)
			WHERE 1=1 AND A.STATUS_MESIN_POSISI = 1
		) A
		LEFT JOIN bio.PERSONNEL_AREA B ON A.ID_BIO = B.ID_BIO
		WHERE 1=1 ".$statement; 
		
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

    function getCountByParamsNextDetil($satuankerja)
	{
		$str = "
		SELECT 
		(
			COALESCE
			(
				(
					SELECT MAX(CAST(SPLIT_PART(AREA_CODE, '.', 2) AS NUMERIC)) 
					FROM bio.PERSONNEL_AREA
					WHERE AREA_PARENT_CODE = '".$satuankerja."'
				)
				, 0
			) + 1
		) ROWCOUNT";
		
    	$this->query = $str;
    	// echo $str;exit();
		$this->select($str);

		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }
	
  } 
?>