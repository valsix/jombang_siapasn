<? 
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');

  class JamKerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JamKerja()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("JAM_KERJA_ID", $this->getNextId("JAM_KERJA_ID","presensi.JAM_KERJA"));
		$str= "
		INSERT INTO presensi.JAM_KERJA 
		(
			JAM_KERJA_ID, JAM_KERJA_JENIS_ID, NAMA, JAM_AWAL, 
			JAM_AKHIR, TERLAMBAT_AWAL, TERLAMBAT_AKHIR, 
			STATUS, JAM_ISTIRAHAT
		) 
		VALUES(
			".$this->getField("JAM_KERJA_ID").",
			'".$this->getField("JAM_KERJA_JENIS_ID")."',
			'".$this->getField("NAMA")."',
			'".$this->getField("JAM_AWAL")."',	
			'".$this->getField("JAM_AKHIR")."',
			'".$this->getField("TERLAMBAT_AWAL")."',
			'".$this->getField("TERLAMBAT_AKHIR")."',
			'".$this->getField("STATUS")."',
			'".$this->getField("JAM_ISTIRAHAT")."'
		)"; 
		$this->id= $this->getField("JAM_KERJA_ID");
		$this->query= $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str= "
		UPDATE presensi.JAM_KERJA
		SET 
			JAM_KERJA_JENIS_ID= '".$this->getField("JAM_KERJA_JENIS_ID")."',
			NAMA= '".$this->getField("NAMA")."',
			JAM_AWAL= '".$this->getField("JAM_AWAL")."',
			JAM_AKHIR= '".$this->getField("JAM_AKHIR")."',
			TERLAMBAT_AWAL= '".$this->getField("TERLAMBAT_AWAL")."',
			TERLAMBAT_AKHIR= '".$this->getField("TERLAMBAT_AKHIR")."',
			STATUS= '".$this->getField("STATUS")."',
			JAM_ISTIRAHAT= '".$this->getField("JAM_ISTIRAHAT")."'
		WHERE JAM_KERJA_ID= ".$this->getField("JAM_KERJA_ID")."
		"; 
		$this->query= $str;
		return $this->execQuery($str);
    }

    function updateByField()
	{
		$str= "
		UPDATE presensi.JAM_KERJA A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		"; 
		$this->query= $str;
	
		return $this->execQuery($str);
    }	

    function updateByFieldWhereClause()
	{
		$str= "
		UPDATE presensi.JAM_KERJA A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."' WHERE ".$this->getField("CONDITION")."= '".$this->getField("CONDITION_VALUE")."'
		"; 
		$this->query= $str;
	
		return $this->execQuery($str);
    }

    function updateByFieldStatus()
	{
		$str= "
		UPDATE presensi.JAM_KERJA A SET
		".$this->getField("FIELD")."= '".$this->getField("FIELD_VALUE")."'
		WHERE JAM_KERJA_ID= ".$this->getField("JAM_KERJA_ID")."
		"; 
		$this->query= $str;
	
		return $this->execQuery($str);
    }	
	
	function delete()
	{
        $str= "
        DELETE FROM presensi.JAM_KERJA
        WHERE 
        JAM_KERJA_ID= ".$this->getField("JAM_KERJA_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str= "	
    	SELECT 
    	JAM_KERJA_ID, A.NAMA, B.NAMA JENIS, JAM_AWAL, 
    	JAM_AKHIR, TERLAMBAT_AWAL, TERLAMBAT_AKHIR, 
    	STATUS, B.JAM_KERJA_JENIS_ID JAM_KERJA_JENIS_ID,
    	JAM_ISTIRAHAT
    	FROM presensi.JAM_KERJA A LEFT JOIN presensi.JAM_KERJA_JENIS B ON A.JAM_KERJA_JENIS_ID= B.JAM_KERJA_JENIS_ID
    	WHERE 1= 1
    	"; 

    	while(list($key,$val)= each($paramsArray))
    	{
    		$str .= " AND $key= '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query= $str;
		// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement="")
    {
    	$str= "SELECT COUNT(JAM_KERJA_ID) AS ROWCOUNT FROM presensi.JAM_KERJA WHERE 1= 1 ".$statement; 
    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key= '$val' ";
    	}

    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return 0; 
    	
    }

    // SELECT REPLACE(TO_CHAR(TO_DATE('13-12-2019', 'DD-MM-YYYY'), 'DAY'), ' ', '')

  } 
?>