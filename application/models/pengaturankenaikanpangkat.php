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
  
  class PengaturanKenaikanPangkat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PengaturanKenaikanPangkat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENGATURAN_KENAIKAN_PANGKAT_ID", $this->getNextId("PENGATURAN_KENAIKAN_PANGKAT_ID","PENGATURAN_KENAIKAN_PANGKAT")); 

     	$str = "
			INSERT INTO PENGATURAN_KENAIKAN_PANGKAT (
				PENGATURAN_KENAIKAN_PANGKAT_ID, TANGGAL_PERIODE, TANGGAL_BATAS_AWAL_USUL
				, TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL, TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH
				, TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS
				, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID").",
				 ".$this->getField("TANGGAL_PERIODE").",
			     ".$this->getField("TANGGAL_BATAS_AWAL_USUL").",
			     ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL").",
			     ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH").",
			     ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS").",
			     '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
			     '".$this->getField("USER_LOGIN_ID")."',
			     ".$this->getField("USER_LOGIN_PEGAWAI_ID")."

			)
		"; 	
		$this->id = $this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENGATURAN_KENAIKAN_PANGKAT
				SET    
				 	TANGGAL_PERIODE	= ".$this->getField("TANGGAL_PERIODE").",
			     	TANGGAL_BATAS_AWAL_USUL	= ".$this->getField("TANGGAL_BATAS_AWAL_USUL").",
			     	TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL= ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL").",
				    TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH= ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH").",
				    TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS= ".$this->getField("TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS").",
			     	LAST_USER ='".$this->getField("LAST_USER")."',
			     	USER_LOGIN_ID ='".$this->getField("USER_LOGIN_ID")."',
			     	USER_LOGIN_PEGAWAI_ID =".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE  PENGATURAN_KENAIKAN_PANGKAT_ID = ".$this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENGATURAN_KENAIKAN_PANGKAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PENGATURAN_KENAIKAN_PANGKAT_ID    	= ".$this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PENGATURAN_KENAIKAN_PANGKAT SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PENGATURAN_KENAIKAN_PANGKAT_ID = ".$this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENGATURAN_KENAIKAN_PANGKAT_ID ASC')
	{
		$str = "
				SELECT A.PENGATURAN_KENAIKAN_PANGKAT_ID, A.TANGGAL_PERIODE, A.TANGGAL_BATAS_AWAL_USUL
				, A.TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL, A.TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH
				, A.TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS
				, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_TANGGAL_BATAS_AWAL_USUL
				FROM PENGATURAN_KENAIKAN_PANGKAT A
				WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENGATURAN_KENAIKAN_PANGKAT_ID ASC ')
	{
		$str = "
				SELECT A.PENGATURAN_KENAIKAN_PANGKAT_ID, A.TANGGAL_PERIODE, A.TANGGAL_BATAS_AWAL_USUL
				, A.TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL, A.TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH
				, A.TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS
				, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_TANGGAL_BATAS_AWAL_USUL,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.PENGATURAN_KENAIKAN_PANGKAT_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.PENGATURAN_KENAIKAN_PANGKAT_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM PENGATURAN_KENAIKAN_PANGKAT A
				WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM PENGATURAN_KENAIKAN_PANGKAT A
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM PENGATURAN_KENAIKAN_PANGKAT A
				WHERE 1 = 1 ".$statement; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }

  } 
?>