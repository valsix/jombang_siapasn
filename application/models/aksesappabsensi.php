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
  * Entity-base class untuk mengimplementasikan tabel AKSES_APP_ABSENSI.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class AksesAppAbsensi extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function AksesAppAbsensi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("AKSES_APP_ABSENSI_ID", $this->getNextId("AKSES_APP_ABSENSI_ID","AKSES_APP_ABSENSI"));

		$str = "
					INSERT INTO AKSES_APP_ABSENSI (
					   AKSES_APP_ABSENSI_ID, MENU_ID, NAMA, AKSES, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID) 
 			  	VALUES (
				  ".$this->getField("AKSES_APP_ABSENSI_ID").",
				  ".$this->getField("MENU_ID").",
				  ".$this->getField("NAMA").",
				  ".$this->getField("AKSES").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				)"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE AKSES_APP_ABSENSI
				SET    
					   NAMA          = '".$this->getField("NAMA")."',
					   USER_LOGIN_ID          = '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID          = ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				WHERE  AKSES_APP_ABSENSI_ID     = '".$this->getField("AKSES_APP_ABSENSI_ID")."'

			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM AKSES_APP_ABSENSI
                WHERE 
                  AKSES_APP_ABSENSI_ID = ".$this->getField("AKSES_APP_ABSENSI_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "
					SELECT 
					AKSES_APP_ABSENSI_ID, NAMA
					FROM AKSES_APP_ABSENSI WHERE AKSES_APP_ABSENSI_ID IS NOT NULL
				"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ORDER BY NAMA DESC";
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsAkses($statement="")
	{
		$str = "
		SELECT
			A.USER_LOGIN_ID, A.USER_GROUP_ID, B.AKSES_APP_ABSENSI_ID, B.AKSES_APP_PERSURATAN_ID
			, M.MENU_ID, M.NAMA MENU_NAMA, ASIM.AKSES
		FROM USER_LOGIN A
		INNER JOIN USER_GROUP B ON A.USER_GROUP_ID = B.USER_GROUP_ID
		INNER JOIN AKSES_APP_ABSENSI_MENU ASIM ON B.AKSES_APP_ABSENSI_ID = ASIM.AKSES_APP_ABSENSI_ID
		INNER JOIN MENU M ON ASIM.MENU_ID = M.MENU_ID
		WHERE 1=1
		"; 
		
		$str .= $statement." ";
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	SELECT 
					AKSES_APP_ABSENSI_ID, NAMA
					FROM AKSES_APP_ABSENSI WHERE AKSES_APP_ABSENSI_ID IS NOT NULL
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
		$str = "SELECT COUNT(AKSES_APP_ABSENSI_ID) AS ROWCOUNT FROM AKSES_APP_ABSENSI
		        WHERE AKSES_APP_ABSENSI_ID IS NOT NULL ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(AKSES_APP_ABSENSI_ID) AS ROWCOUNT FROM AKSES_APP_ABSENSI
		        WHERE AKSES_APP_ABSENSI_ID IS NOT NULL ".$statement; 
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