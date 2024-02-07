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
  
  class Kelurahan extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Kelurahan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KELURAHAN_ID", $this->getNextId("KELURAHAN_ID","KELURAHAN")); 

		$str = "INSERT INTO KELURAHAN (
				   KELURAHAN_ID, KECAMATAN_ID, KABUPATEN_ID, 
				   NAMA, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER, LAST_UPDATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_SATKER, PROPINSI_ID, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID) 
				VALUES (
				  ".$this->getField("KELURAHAN_ID").",
				  ".$this->getField("KECAMATAN_ID").",
				  ".$this->getField("KABUPATEN_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("LAST_CREATE_USER").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("LAST_CREATE_SATKER").",
				  ".$this->getField("LAST_UPDATE_USER").",
				  ".$this->getField("LAST_UPDATE_DATE").",
				  ".$this->getField("LAST_UPDATE_SATKER").",
				  ".$this->getField("PROPINSI_ID").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE KELURAHAN
				SET    
					   KECAMATAN_ID       = ".$this->getField("KECAMATAN_ID").",
					   KABUPATEN_ID    	  = ".$this->getField("KABUPATEN_ID").",
					   NAMA               = '".$this->getField("NAMA")."',
					   PROPINSI_ID     	  = ".$this->getField("PROPINSI_ID").",
					   USER_LOGIN_ID     	  = ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID     	  = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  KELURAHAN_ID       = ".$this->getField("KELURAHAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM KELURAHAN
                WHERE 
                  KELURAHAN_ID = ".$this->getField("KELURAHAN_ID").""; 
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='')
	{
		$str = "SELECT KELURAHAN_ID, KECAMATAN_ID, KABUPATEN_ID, NAMA, PROPINSI_ID
				FROM KELURAHAN WHERE KELURAHAN_ID IS NOT NULL"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
		//echo $str;		
		return $this->selectLimit($str,$limit,$from); 
    }
    	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(KELURAHAN_ID) AS ROWCOUNT FROM KELURAHAN WHERE KELURAHAN_ID IS NOT NULL "; 
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