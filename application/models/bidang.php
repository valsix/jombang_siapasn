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
  * Entity-base class untuk mengimplementasikan tabel website.bidang.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class Bidang extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Bidang()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("BIDANG_ID", $this->getNextId("BIDANG_ID","website.bidang")); 	
		$str = "
				INSERT INTO website.bidang (
				   BIDANG_ID, NAMA, KETERANGAN, LAST_CREATE_USER, LAST_CREATE_DATE,
				   USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				   ) 
 			  	VALUES (
				  ".$this->getField("BIDANG_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_CREATE_USER")."',
				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				)"; 
		$this->id = $this->getField("BIDANG_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE website.bidang
				SET    
					   NAMA           	= '".$this->getField("NAMA")."',
					   KETERANGAN       = '".$this->getField("KETERANGAN")."',
					   LAST_UPDATE_USER	= '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE	= ".$this->getField("LAST_UPDATE_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  BIDANG_ID     	= '".$this->getField("BIDANG_ID")."'
			 "; //FOTO= '".$this->getField("FOTO")."',
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM website.bidang
                WHERE 
                  BIDANG_ID = ".$this->getField("BIDANG_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=20000,$from=0, $statement="", $order=" ORDER BY BIDANG_ID ASC ")
	{
		$str = "
				SELECT 
				BIDANG_ID, NAMA, KETERANGAN
				FROM website.bidang
				WHERE 1 = 1
				"; 
		//, FOTO
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=20000,$from=0, $statement="")
	{
		$str = "
				SELECT BIDANG_ID, NAMA, KETERANGAN
				FROM website.bidang
				WHERE 1 = 1
			    "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY NAMA ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(BIDANG_ID) AS ROWCOUNT FROM website.bidang
		        WHERE BIDANG_ID IS NOT NULL ".$statement; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->select($str);
		$this->query = $str; 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(BIDANG_ID) AS ROWCOUNT FROM website.bidang
		        WHERE BIDANG_ID IS NOT NULL ".$statement; 
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