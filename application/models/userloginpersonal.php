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

  class UserLoginPersonal extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserLoginPersonal()
	{
      $this->Entity(); 
    }
	

    function insertpass()
	{
		$str = "
		INSERT INTO app.user_login_personal
		(
			PEGAWAI_ID, STATUS, LOGIN_USER, LOGIN_PASS, 
			LAST_USER, LAST_DATE, SATUAN_KERJA_ID
		)
		VALUES
		(
			".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("STATUS")."'
			, '".$this->getField("LOGIN_USER")."'
			, '".$this->getField("LOGIN_PASS")."'
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("SATUAN_KERJA_ID")."
		)
		"; 
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE app.user_login_personal
		SET
		LOGIN_USER= '".$this->getField("LOGIN_USER")."'
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_DATE= ".$this->getField("LAST_DATE")."
		, SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID")."
		WHERE PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function resetPassword()
	{
		$str = "
		UPDATE app.user_login_personal
		SET
		STATUS= '".$this->getField("STATUS")."'
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM app.user_login_personal
        WHERE 
        PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
        ";
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
    function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY PEGAWAI_ID ASC ')
	{
		$str = "	
		SELECT 
		*
		FROM app.user_login_personal A
		WHERE 1 = 1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement." ".$order;
		$this->query = $str;
		
		return $this->selectLimit($str,$limit,$from); 
		
    }

    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(PEGAWAI_ID) AS ROWCOUNT FROM app.user_login_personal A
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