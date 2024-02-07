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
  include_once(APPPATH.'/models/Entity.php');

  class UserLogin extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserLogin()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","app.user_login"));

		$str = "
				INSERT INTO app.user_login (
				   		USER_LOGIN_ID, USER_GROUP_ID, STATUS, LOGIN_USER, LOGIN_PASS, LOGIN_LEVEL, 
						LAST_USER, LAST_DATE, SATUAN_KERJA_ID, STATUS_MENU_KHUSUS) 
				VALUES ( '".$this->getField("USER_LOGIN_ID")."', 
						  ".$this->getField("USER_GROUP_ID").", 
						 '".$this->getField("STATUS")."',
						 '".$this->getField("LOGIN_USER")."',
						 '".md5($this->getField("LOGIN_PASS"))."', 
						 ".$this->getField("LOGIN_LEVEL").",
						 '".$this->getField("LAST_USER")."', 
						 ".$this->getField("LAST_DATE").", 
						 ".$this->getField("SATUAN_KERJA_ID").", 
						 ".$this->getField("STATUS_MENU_KHUSUS")."
				)
				"; 
		$this->id = $this->getField("USER_LOGIN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE app.user_login
				SET    USER_GROUP_ID	=  ".$this->getField("USER_GROUP_ID").", 
					   LOGIN_USER		= '".$this->getField("LOGIN_USER")."',
					   LOGIN_LEVEL		= ".$this->getField("LOGIN_LEVEL").",
					   LAST_USER		= '".$this->getField("LAST_USER")."', 
					   LAST_DATE		= ".$this->getField("LAST_DATE").", 
					   SATUAN_KERJA_ID	= ".$this->getField("SATUAN_KERJA_ID").", 
					   STATUS_MENU_KHUSUS= ".$this->getField("STATUS_MENU_KHUSUS")."
				WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'
			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
	function resetPassword()
	{
		$str = "
				UPDATE app.user_login
				SET    LOGIN_PASS       = '".$this->getField("LOGIN_PASS")."',
					   LAST_USER 		= '".$this->getField("LAST_USER")."',
					   LAST_DATE 		= ".$this->getField("LAST_DATE")."
				WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE app.user_login
				SET    
					   STATUS   		= ".$this->getField("STATUS").",
					   LAST_USER		= '".$this->getField("LAST_USER")."',
					   LAST_DATE		= ".$this->getField("LAST_DATE")."
				WHERE  USER_LOGIN_ID    = ".$this->getField("USER_LOGIN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM app.user_login
                WHERE 
                  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").""; 
				  
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
    function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY USER_LOGIN_ID ASC ')
	{
		$str = "	SELECT 
						USER_LOGIN_ID,  LOGIN_USER, LOGIN_PASS, PEGAWAI_ID, SATUAN_KERJA_ID
       					STATUS, LAST_USER, LAST_DATE 
					FROM app.user_login A
					WHERE 1 = 1 "; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM app.user_login A
		        WHERE 1 = 1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
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