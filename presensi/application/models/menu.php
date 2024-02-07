<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  include_once("Entity.php");

  class Menu extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Menu()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MENU_ID", $this->getNextId("MENU_ID","MENU"));

		$str = "
				INSERT INTO MENU (
					MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, 
					NAMA, KETERANGAN, URUT) 
				VALUES ('".$this->getField("MENU_ID")."', '".$this->getField("MENU_PARENT_ID")."', '".$this->getField("MENU_GROUP_ID")."', 
					'".$this->getField("NAMA")."', '".$this->getField("KETERANGAN")."', '".$this->getField("URUT")."')
				"; 
		$this->id = $this->getField("MENU_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE MENU 
				SET 	MENU_PARENT_ID	= '".$this->getField("MENU_PARENT_ID")."',
						MENU_GROUP_ID	= '".$this->getField("MENU_GROUP_ID")."', 
						NAMA			= '".$this->getField("NAMA")."',
						KETERANGAN		= '".$this->getField("KETERANGAN")."',
						URUT			= '".$this->getField("URUT")."'
				WHERE   MENU_ID 		= '".$this->getField("MENU_ID")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM presensi.MENU
                WHERE 
                  MENU_ID = ".$this->getField("MENU_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.MENU_ID ASC")
	{
		/*$str = "
		SELECT MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, NAMA, KETERANGAN, URUT,
		LINK_FILE, STATUS_DEFAULT, (SELECT COUNT(1) FROM presensi.MENU X WHERE X.MENU_PARENT_ID = A.MENU_ID ) CHILD
		FROM presensi.MENU A
		WHERE A.MENU_ID IS NOT NULL 
		"; */

		$str = "
		SELECT MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, NAMA, KETERANGAN, URUT,
		LINK_FILE, (SELECT COUNT(1) FROM MENU X WHERE X.MENU_PARENT_ID = A.MENU_ID ) CHILD
		FROM MENU A
		WHERE A.MENU_ID IS NOT NULL
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	
	/*
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $akses_adm_intranet_id="", $table="", $order="ORDER BY A.MENU_ID ASC")
	{
		$str = "
                SELECT 
				A.MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, A.NAMA MENU, KETERANGAN, LINK_FILE, COALESCE(B.AKSES, 'A') AKSES, C.NAMA 
				FROM presensi.MENU A 
                LEFT JOIN ".$table."_MENU B ON A.MENU_ID = B.MENU_ID AND B.".$table."_ID = '".$akses_adm_intranet_id."' 
                LEFT JOIN ".$table." C ON C.".$table."_ID = '".$akses_adm_intranet_id."' 
                WHERE A.MENU_ID IS NOT NULL 
				"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
    }
	*/
	
	/*function selectByParamsMenu($statement = "", $order="ORDER BY URUT, A.MENU_ID ASC")
	{
		$str = "
				SELECT A.MENU_ID, NAMA, LINK_FILE, AKSES,
				(SELECT COUNT(USER_GROUP_MENU_ID) FROM USER_GROUP_MENU X WHERE SUBSTRING(X.MENU_ID, 1, 2) = A.MENU_ID) JUMLAH_MENU, 
				FROM presensi.MENU  A
				LEFT JOIN USER_GROUP_MENU B ON A.MENU_ID = B.MENU_ID
				WHERE 1 = 1
			    "; 
		
		$str .= $statement."  ".$order;
		
		
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }	*/
	
	function selectByParamsMenu($group_id, $akses_id, $table_prefix, $statement = "", $order="ORDER BY URUT, A.MENU_ID ASC")
	{
		$str = "
				SELECT A.MENU_ID, A.MENU_PARENT_ID, NAMA, LINK_FILE, LINK_DETIL_FILE, AKSES, ICON,
				COALESCE(MN.JUMLAH,0) JUMLAH_CHILD,
				(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id.") JUMLAH_MENU,
				(SELECT COUNT(".$table_prefix."_ID) FROM ".$table_prefix."_MENU X WHERE SUBSTR(X.MENU_ID, 1, 2) = A.MENU_ID AND ".$table_prefix."_ID = ".$akses_id." AND AKSES = 'D') JUMLAH_DISABLE
				FROM MENU  A
				LEFT JOIN ".$table_prefix."_MENU B ON A.MENU_ID = B.MENU_ID AND ".$table_prefix."_ID = ".$akses_id."
				LEFT JOIN
				(
					SELECT CASE WHEN COUNT(1) > 0 THEN 1 ELSE 0 END JUMLAH, MENU_PARENT_ID PARENT_ID FROM MENU A GROUP BY MENU_PARENT_ID ORDER BY MENU_PARENT_ID
				) MN ON A.MENU_ID = MN.PARENT_ID
				WHERE MENU_GROUP_ID = ".$group_id."
			    "; 
		
		$str .= $statement."  ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsMenuSub($group_id, $akses_id, $table_prefix, $statement = "", $order="ORDER BY URUT, A.MENU_ID ASC")
	{
		$str = "
				SELECT A.MENU_ID, NAMA, COALESCE(LINK_FILE, '#') LINK_FILE, AKSES,
				(SELECT COUNT(MENU_ID) FROM presensi.MENU X WHERE X.MENU_PARENT_ID = A.MENU_ID AND EXISTS (SELECT 1 FROM ".$table_prefix."_MENU Y WHERE ".$table_prefix."_ID = ".$akses_id." AND Y.MENU_ID = X.MENU_ID)) JUMLAH_MENU, 
				(SELECT COUNT(MENU_ID) FROM presensi.MENU X WHERE X.MENU_PARENT_ID = A.MENU_ID AND EXISTS (SELECT 1 FROM ".$table_prefix."_MENU Y WHERE ".$table_prefix."_ID = ".$akses_id." AND Y.MENU_ID = X.MENU_ID AND AKSES = 'D')) JUMLAH_DISABLE 				
				FROM presensi.MENU  A
				LEFT JOIN ".$table_prefix."_MENU B ON A.MENU_ID = B.MENU_ID AND ".$table_prefix."_ID = ".$akses_id."
				WHERE MENU_GROUP_ID = ".$group_id."
			    "; 
		
		$this->query = $str;
		$str .= $statement."  ".$order;
		
		return $this->selectLimit($str,-1,-1); 
    }	


	function selectByParamsMenuByPass($group_id, $akses_id, $table_prefix, $statement = "", $order="ORDER BY A.MENU_ID ASC")
	{
		$str = "
				 SELECT A.MENU_ID, NAMA, LINK_FILE, 'A' AKSES, 10 JUMLAH_MENU, 0 JUMLAH_DISABLE
				 FROM presensi.MENU  A
				 WHERE MENU_GROUP_ID = ".$group_id."
			    "; 
		
		$this->query = $str;
		$str .= $statement."  ".$order;
		//echo $str;
		return $this->selectLimit($str,-1,-1); 
    }		
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
				SELECT 
				MENU_ID, MENU_PARENT_ID, MENU_GROUP_ID, NAMA, KETERANGAN, LINK_FILE
				FROM presensi.MENU A WHERE MENU_ID IS NOT NULL
			    "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY MENU_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(MENU_ID) AS JUMLAHROW FROM presensi.MENU A
		        WHERE 0=0 ".$statement; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("JUMLAHROW"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(MENU_ID) AS JUMLAHROW FROM presensi.MENU A
		        WHERE 0=0 ".$statement; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("JUMLAHROW"); 
		else 
			return 0; 
    }	
  } 
?>