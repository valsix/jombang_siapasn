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
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class CutiUsulanTmsTolak extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function CutiUsulanTmsTolak()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("CUTI_USULAN_TMS_TOLAK_ID", $this->getNextId("CUTI_USULAN_TMS_TOLAK_ID","cuti_usulan_tms_tolak")); 

     	$str = "
     	INSERT INTO cuti_usulan_tms_tolak
     	(
     		CUTI_USULAN_TMS_TOLAK_ID, CUTI_USULAN_ID, JENIS, KETERANGAN
     		, LAST_USER, LAST_DATE, LAST_LEVEL
     	)
     	VALUES
     	(
	     	".$this->getField("CUTI_USULAN_TMS_TOLAK_ID")."
	     	, ".$this->getField("CUTI_USULAN_ID")."
	     	, '".$this->getField("JENIS")."'
	     	, '".$this->getField("KETERANGAN")."'
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, ".$this->getField("LAST_LEVEL")."
     	)
		"; 	
		$this->id = $this->getField("CUTI_USULAN_TMS_TOLAK_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
		DELETE FROM cuti_usulan_tms_tolak
		WHERE MENU_ID = ".$this->getField("MENU_ID")."
		";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE DESC')
	{
		$str = "
		SELECT
			A.* 
		FROM cuti_usulan_tms_tolak A
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
		
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM cuti_usulan_tms_tolak A
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