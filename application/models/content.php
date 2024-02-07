<? 

  /***
  * Entity-base class untuk mengimplementasikan tabel kategori.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class Content extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Content()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KONTEN_ID", $this->getNextId("KONTEN_ID","website.konten")); 
		
		$str = "INSERT INTO website.konten(KONTEN_ID, PARENT_KONTEN_ID, URUT, NAMA, KETERANGAN, ISI, STATUS_CONTENT_MENU, STATUS_LOCKED, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID) 
				VALUES(
				  ".$this->getField("KONTEN_ID").",
				  ".$this->getField("PARENT_KONTEN_ID").",
				  ".$this->getField("URUT").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("ISI")."',
				  ".$this->getField("STATUS_CONTENT_MENU").",
				  ".$this->getField("STATUS_LOCKED").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				)"; 
				
		$this->id = $this->getField("KONTEN_ID");		
		$this->query = $str;
		return $this->execQuery($str);
    }

	function update_file()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE website.konten SET
				  LINK_URL = '".$this->getField("LINK_URL")."',
				  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").",
				  USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				$this->query = $str;
				//echo $str;
		return $this->execQuery($str);
    }

    function updateINA()
	{
		$str = "UPDATE website.konten SET
				  KETERANGAN = '".$this->getField("KETERANGAN")."',
				  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").",
				  USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }
	
    function updateEN()
	{
		$str = "UPDATE website.konten SET
				  DESCRIPTION = '".$this->getField("DESCRIPTION")."',
				  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").",
				  USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }	
	
	function updateContent()
	{
		$str = "UPDATE website.konten SET
				  KETERANGAN = '".$this->getField("KETERANGAN")."',
				  USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."',
				  USER_LOGIN_PEGAWAI_ID = '".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
				WHERE KONTEN_ID = '".$this->getField("KONTEN_ID")."'
				"; 
				
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
	}
	
	function updateStatusContentMenu($KONTEN_ID, $value)
	{
		$str = "UPDATE website.konten SET
				  STATUS_CONTENT_MENU = '".$value."'
				WHERE KONTEN_ID = '".$KONTEN_ID."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	function updateUrut($KONTEN_ID, $value)
	{
		$str = "UPDATE website.konten SET
				  URUT = '".$value."'
				WHERE KONTEN_ID = '".$KONTEN_ID."'
				"; 
				
		$this->query = $str;
		return $this->execQuery($str);
	}
	
	function delete()
	{
        $str = "DELETE FROM website.konten
                WHERE 
                  KONTEN_ID = '".$this->getField("KONTEN_ID")."'"; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=20000,$from=0)
	{
		$str = "SELECT KONTEN_ID, PARENT_KONTEN_ID, URUT, NAMA, KETERANGAN, ISI, LINK_URL, STATUS_CONTENT_MENU, STATUS_LOCKED, DESCRIPTION, NAME
				FROM website.konten WHERE KONTEN_ID IS NOT NULL"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = $val ";
		}
		
		$str .= " ORDER BY URUT ASC, NAMA ASC ";
		$this->query = $str;
		return $this->selectClobLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=20000,$from=0)
	{
		$str = "SELECT KONTEN_ID, PARENT_KONTEN_ID, URUT, NAMA, KETERANGAN, ISI, LINK_URL, STATUS_CONTENT_MENU, STATUS_LOCKED
				FROM website.konten WHERE KONTEN_ID IS NOT NULL"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= " ORDER BY URUT ASC, NAMA ASC";
				
		return $this->selectClobLimit($str,$limit,$from);
    }	
   
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM website.konten WHERE KONTEN_ID IS NOT NULL "; 
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

    function getMenuCaption($link_web)
	{
		$str = "SELECT NAMA_MENU  FROM MENU WHERE 1 = 1 AND LINK_WEB = '".$link_web."' "; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("NAMA_MENU"); 
		else 
			return ""; 
    }
	
    function getMenuCaptionEnglish($link_web)
	{
		$str = "SELECT NAMA_MENU_EN FROM MENU WHERE 1 = 1 AND LINK_WEB = '".$link_web."' "; 
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("NAMA_MENU_EN"); 
		else 
			return ""; 
    }	

    function getCountByParamsLike($paramsArray=array())
	{
		$str = "SELECT COUNT(KONTEN_ID) AS ROWCOUNT FROM website.konten WHERE KONTEN_ID IS NOT NULL "; 
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
	
	function getContentTitle($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		return $this->getField('NAMA');
	}

	function getContentTitleEnglish($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('NAME');
	}
		
	function getContentText($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('KETERANGAN');
	}

	function getContentTextEnglish($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		if($this->getField('DESCRIPTION') == "")
			return $this->getField('KETERANGAN'); 
		else
			return $this->getField('DESCRIPTION');
	}
	
	function getContent($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('ISI');
	}

	function getLinkUrl($varKONTEN_ID)
	{
		$this->selectByParams(array('KONTEN_ID' => $varKONTEN_ID));
		$this->firstRow();
		
		return $this->getField('LINK_URL');
	}
		
  } 
?>