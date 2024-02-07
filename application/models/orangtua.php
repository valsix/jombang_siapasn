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
  
  class OrangTua extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function OrangTua()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ORANG_TUA_ID", $this->getNextId("ORANG_TUA_ID","ORANG_TUA")); 

		$str = "
			INSERT INTO ORANG_TUA (
				ORANG_TUA_ID, PEGAWAI_ID, JENIS_KELAMIN, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, PEKERJAAN, ALAMAT, KODEPOS, TELEPON, PROPINSI_ID, 
				KABUPATEN_ID, KELURAHAN_ID, KECAMATAN_ID, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("ORANG_TUA_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("JENIS_KELAMIN")."',
				 '".$this->getField("NAMA")."',
				 '".$this->getField("TEMPAT_LAHIR")."',
				 ".$this->getField("TANGGAL_LAHIR").",
				 '".$this->getField("PEKERJAAN")."',
				 '".$this->getField("ALAMAT")."',
				 '".$this->getField("KODEPOS")."',
				 '".$this->getField("TELEPON")."',
				 ".$this->getField("PROPINSI_ID").",
				 ".$this->getField("KABUPATEN_ID").",
				 ".$this->getField("KELURAHAN_ID").",
				 ".$this->getField("KECAMATAN_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("ORANG_TUA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE ORANG_TUA
				SET    
				 	ORANG_TUA_ID= ".$this->getField("ORANG_TUA_ID").",
				 	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				 	JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
				 	NAMA= '".$this->getField("NAMA")."',
				 	TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
				 	TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
				 	PEKERJAAN= '".$this->getField("PEKERJAAN")."',
				 	ALAMAT= '".$this->getField("ALAMAT")."',
				 	KODEPOS= '".$this->getField("KODEPOS")."',
				 	TELEPON= '".$this->getField("TELEPON")."',
				 	PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
				 	KABUPATEN_ID= ".$this->getField("KABUPATEN_ID").",
				 	KELURAHAN_ID= ".$this->getField("KELURAHAN_ID").",
				 	KECAMATAN_ID= ".$this->getField("KECAMATAN_ID").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_ID").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  ORANG_TUA_ID = ".$this->getField("ORANG_TUA_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE ORANG_TUA
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  ORANG_TUA_ID    	= ".$this->getField("ORANG_TUA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE ORANG_TUA SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE ORANG_TUA_ID = ".$this->getField("ORANG_TUA_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ORANG_TUA_ID ASC')
	{
		$str = "
				SELECT
				A.ORANG_TUA_ID, A.PEGAWAI_ID, A.JENIS_KELAMIN, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.PEKERJAAN
				, A.ALAMAT, A.KODEPOS, A.TELEPON
				, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				, A.PROPINSI_ID, PROP.NAMA PROPINSI_NAMA, A.KABUPATEN_ID, KAB.NAMA KABUPATEN_NAMA, A.KECAMATAN_ID, KEC.NAMA KECAMATAN_NAMA, A.KELURAHAN_ID DESA_ID, KEL.NAMA DESA_NAMA
				FROM ORANG_TUA A
				LEFT JOIN PROPINSI PROP ON PROP.PROPINSI_ID = A.PROPINSI_ID
				LEFT JOIN KABUPATEN KAB ON KAB.KABUPATEN_ID = A.KABUPATEN_ID
				LEFT JOIN KECAMATAN KEC ON KEC.KECAMATAN_ID = A.KECAMATAN_ID
				LEFT JOIN KELURAHAN KEL ON KEL.KECAMATAN_ID = A.KECAMATAN_ID AND KEL.KELURAHAN_ID = A.KELURAHAN_ID
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
		
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.ORANG_TUA_ID) AS ROWCOUNT 
				FROM ORANG_TUA A
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

  } 
?>