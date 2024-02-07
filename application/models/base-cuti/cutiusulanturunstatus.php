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
  
  class CutiUsulanTurunStatus extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function CutiUsulanTurunStatus()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("CUTI_USULAN_TURUN_STATUS_ID", $this->getNextId("CUTI_USULAN_TURUN_STATUS_ID","cuti_usulan_turun_status")); 

     	$str = "
     	INSERT INTO cuti_usulan_turun_status
     	(
     		CUTI_USULAN_TURUN_STATUS_ID, CUTI_USULAN_ID, POSISI_MENU_ID, KETERANGAN
     		, LAST_USER, LAST_DATE, LAST_LEVEL
     	)
     	VALUES
     	(
	     	".$this->getField("CUTI_USULAN_TURUN_STATUS_ID")."
	     	, ".$this->getField("CUTI_USULAN_ID")."
	     	, '".$this->getField("POSISI_MENU_ID")."'
	     	, '".$this->getField("KETERANGAN")."'
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, ".$this->getField("LAST_LEVEL")."
     	)
		"; 	
		$this->id = $this->getField("CUTI_USULAN_TURUN_STATUS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE cuti_usulan_turun_status
		SET    
		 	URUTAN= ".$this->getField("URUTAN")."
		 	, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		WHERE MENU_ID = '".$this->getField("MENU_ID")."'
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function statusPegawaiTms()
	{
        $str = "
		UPDATE cuti_usulan
		SET
		 	STATUS_TMS= ".$this->getField("STATUS_TMS").",
		 	LAST_USER= '".$this->getField("LAST_USER")."',
		 	LAST_DATE= ".$this->getField("LAST_DATE").",
		 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE CUTI_USULAN_ID= ".$this->getField("CUTI_USULAN_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
		DELETE FROM cuti_usulan_turun_status
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
		FROM cuti_usulan_turun_status A
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
		FROM cuti_usulan_turun_status A
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