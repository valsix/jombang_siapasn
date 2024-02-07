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
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class UserGroup extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserGroup()
	{
      $this->Entity(); 
    }
	
	function insertMenu()
	{
		$this->setField("AKSES_ADM_INTRANET_ID", $this->getNextId($this->getField("TABLE")."_ID",$this->getField("TABLE"))); 
		$str = "
				INSERT INTO ".$this->getField("TABLE")." (
				   ".$this->getField("TABLE")."_ID, NAMA) 
 			  	VALUES (
				  ".$this->getField("AKSES_ADM_INTRANET_ID").",
				  '".$this->getField("NAMA")."'
				)"; 
		$this->query = $str;
		$this->id = $this->getField("AKSES_ADM_INTRANET_ID");
		return $this->execQuery($str);
    }

    function updateMenu()
	{
		$str = "
				UPDATE ".$this->getField("TABLE")."
				SET    
					   NAMA  = '".$this->getField("NAMA")."'
				WHERE  ".$this->getField("TABLE")."_ID     = '".$this->getField("AKSES_ADM_INTRANET_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function insertMenuDetil()
	{
		//echo $this->getField("TABLE")."_MENU";exit;
		//$this->setField("AKSES_ADM_INTRANET_MENU_ID", $this->getNextId($this->getField("TABLE")."_MENU_ID", $this->getField("TABLE")."_MENU")); 

		$strmenu= "
		UPDATE MENU
		SET
		MENU_ICON_ID= '".$this->getField("MENU_ICON_ID")."'
		WHERE MENU_ID = '".$this->getField("MENU_ID")."'
		AND MENU_GROUP_ID = '".$this->getField("MENU_GROUP_ID")."'
		"; 
		$this->query = $strmenu;
		$this->execQuery($strmenu);

		$str = "
		INSERT INTO ".$this->getField("TABLE")."_MENU (
		".$this->getField("TABLE")."_ID, MENU_ID, AKSES) 
		VALUES 
		(
			'".$this->getField("AKSES_ADM_INTRANET_ID")."',
			'".$this->getField("MENU_ID")."',
			'".$this->getField("AKSES")."'
		)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateMenuDetil()
	{
		$str = "
				UPDATE AKSES_ADM_INTRANET_MENU
				SET    
					   AKSES_ADM_INTRANET_ID   		= '".$this->getField("AKSES_ADM_INTRANET_ID")."',
					   MENU_ID      				= '".$this->getField("MENU_ID")."',
					   AKSES      					= '".$this->getField("AKSES")."'
				WHERE  AKSES_ADM_INTRANET_MENU_ID   = '".$this->getField("AKSES_ADM_INTRANET_MENU_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function deleteMenuDetil()
	{
        $str = "DELETE FROM ".$this->getField("TABLE")."_MENU
                WHERE 
                  ".$this->getField("TABLE")."_ID = ".$this->getField("AKSES_ADM_INTRANET_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_GROUP_ID", $this->getNextId("USER_GROUP_ID","USER_GROUP"));

		$str = "
				INSERT INTO USER_GROUP (
				   USER_GROUP_ID, NAMA, AKSES_APP_SIMPEG_ID, AKSES_APP_ABSENSI_ID,TAMPIL_RESET
				)
 			  	VALUES (
				  ".$this->getField("USER_GROUP_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("AKSES_APP_SIMPEG_ID").",
				  ".$this->getField("AKSES_APP_ABSENSI_ID").",
				  ".$this->getField("TAMPIL_RESET")."


				)"; 
		$this->id = $this->getField("USER_GROUP_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_GROUP
				SET    
					   NAMA= '".$this->getField("NAMA")."',
					   AKSES_APP_SIMPEG_ID= ".$this->getField("AKSES_APP_SIMPEG_ID").",
					   AKSES_APP_ABSENSI_ID= ".$this->getField("AKSES_APP_ABSENSI_ID").",
					   TAMPIL_RESET= ".$this->getField("TAMPIL_RESET")."
					   WHERE  USER_GROUP_ID     = '".$this->getField("USER_GROUP_ID")."'
			 "; 
		$this->query = $str;
		//echo $this->query;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM user_group
                WHERE 
                  USER_GROUP_ID = ".$this->getField("USER_GROUP_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=20000,$from=0, $statement='',$order='ORDER BY USER_GROUP_ID ASC ')
	{
		$str = "	SELECT 
						USER_GROUP_ID, NAMA, AKSES_APP_SIMPEG_ID, AKSES_APP_PERSURATAN_ID, 
       					STATUS, LAST_USER, LAST_DATE
					FROM user_group A
					WHERE 1 = 1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USER_GROUP_ID ASC ')
	{
		$str = "
		SELECT 
			A.USER_GROUP_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA,
			CASE WHEN A.STATUS = '1' THEN
				CONCAT('<a onClick=\"hapusData(''',A.USER_GROUP_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
			ELSE
				CONCAT('<a onClick=\"hapusData(''',A.USER_GROUP_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
			END LINK_URL_INFO
			, A.AKSES_APP_SIMPEG_ID, B.NAMA AKSES_APP_SIMPEG_NAMA
			, A.AKSES_APP_ABSENSI_ID, C.NAMA AKSES_APP_ABSENSI_NAMA
			, A.TAMPIL_RESET
		FROM USER_GROUP A
		LEFT JOIN AKSES_APP_SIMPEG B ON A.AKSES_APP_SIMPEG_ID = B.AKSES_APP_SIMPEG_ID
		LEFT JOIN AKSES_APP_ABSENSI C ON A.AKSES_APP_ABSENSI_ID = C.AKSES_APP_ABSENSI_ID
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
	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT
				FROM USER_GROUP A
				LEFT JOIN AKSES_APP_SIMPEG B ON A.AKSES_APP_SIMPEG_ID = B.AKSES_APP_SIMPEG_ID
				LEFT JOIN AKSES_APP_ABSENSI C ON A.AKSES_APP_ABSENSI_ID = C.AKSES_APP_ABSENSI_ID
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
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(USER_GROUP_ID) AS ROWCOUNT FROM user_group A
		        WHERE 1 = 1 ".$statement; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>