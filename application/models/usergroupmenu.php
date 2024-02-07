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

  class UserGroupMenu extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserGroupMenu()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */

		$str = "INSERT INTO user_group_website_menu(USER_GROUP_WEBSITE_ID, MENU_ID, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID) 
				VALUES(
				  ".$this->getField("USER_GROUP_WEBSITE_ID").",
				  '".$this->getField("MENU_ID")."'
				)"; 
				
		$this->query = $str;
		
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE user_group_website_menu SET
				  MENU_ID 				= '".$this->getField("MENU_ID")."'
				WHERE USER_GROUP_WEBSITE_ID 	= '".$this->getField("USER_GROUP_WEBSITE_ID")."'
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM user_group_website_menu
                	WHERE USER_GROUP_WEBSITE_ID = '".$this->getField("USER_GROUP_WEBSITE_ID")."'
				"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MENU_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=20000,$from=0)
	{
		$str = "
				SELECT USER_GROUP_WEBSITE_ID, MENU_ID
				FROM user_group_website_menu WHERE USER_GROUP_WEBSITE_ID IS NOT NULL
				"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = $val ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY MENU_ID ASC ";
				
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=20000,$from=0)
	{
		$str = "
				SELECT USER_GROUP_WEBSITE_ID, MENU_ID
				FROM user_group_website_menu WHERE USER_GROUP_WEBSITE_ID IS NOT NULL
				"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY MENU_ID ASC ";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MENU_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(USER_GROUP_WEBSITE_ID) AS ROWCOUNT FROM user_group_website_menu WHERE USER_GROUP_WEBSITE_ID IS NOT NULL "; 
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

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(USER_GROUP_WEBSITE_ID) AS ROWCOUNT FROM user_group_website_menu WHERE USER_GROUP_WEBSITE_ID IS NOT NULL "; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>