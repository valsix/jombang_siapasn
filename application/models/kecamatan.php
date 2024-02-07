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
  
  class Kecamatan extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Kecamatan()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KECAMATAN_ID", $this->getNextId("KECAMATAN_ID","KECAMATAN")); 

		$str = "INSERT INTO KECAMATAN (
				   KECAMATAN_ID, KABUPATEN_ID, PROPINSI_ID, 
				   NAMA, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_CREATE_SATKER, LAST_UPDATE_USER, LAST_UPDATE_DATE, LAST_UPDATE_SATKER, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID) 
				VALUES (
				  ".$this->getField("KECAMATAN_ID").",
				  ".$this->getField("KABUPATEN_ID").",
				  ".$this->getField("PROPINSI_ID").",
				  '".$this->getField("NAMA")."',
				  ".$this->getField("LAST_CREATE_USER").",
				  ".$this->getField("LAST_CREATE_DATE").",
				  ".$this->getField("LAST_CREATE_SATKER").",
				  ".$this->getField("LAST_UPDATE_USER").",
				  ".$this->getField("LAST_UPDATE_DATE").",
				  ".$this->getField("LAST_UPDATE_SATKER").",
				  '".$this->getField("USER_LOGIN_ID")."',
				  '".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
				)"; 
				
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE KECAMATAN
				SET    
					   KABUPATEN_ID       	= ".$this->getField("KABUPATEN_ID").",
					   PROPINSI_ID    		= ".$this->getField("PROPINSI_ID").",
					   NAMA             	= '".$this->getField("NAMA")."',
					   USER_LOGIN_ID             	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID             	= '".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
				WHERE  KECAMATAN_ID         = ".$this->getField("KECAMATAN_ID")."
				"; 
				$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "DELETE FROM KECAMATAN
                WHERE 
                  KECAMATAN_ID = ".$this->getField("KECAMATAN_ID").""; 
				  
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
		$str = "SELECT KECAMATAN_ID, KABUPATEN_ID, PROPINSI_ID, NAMA
				FROM KECAMATAN WHERE KECAMATAN_ID IS NOT NULL"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA ASC";
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array())
	{
		$str = "SELECT COUNT(KECAMATAN_ID) AS ROWCOUNT FROM KECAMATAN WHERE KECAMATAN_ID IS NOT NULL "; 
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