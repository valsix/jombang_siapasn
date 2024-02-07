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
  
  class PangkatRiwayat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PangkatRiwayat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PANGKAT_RIWAYAT_ID", $this->getNextId("PANGKAT_RIWAYAT_ID","PANGKAT_RIWAYAT"));
     	$str = "
			INSERT INTO PANGKAT_RIWAYAT (
				PANGKAT_RIWAYAT_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, STLUD, NO_STLUD, 
				TANGGAL_STLUD, NO_NOTA, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_PANGKAT, KREDIT, JENIS_RIWAYAT, 
				KETERANGAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, NO_URUT_CETAK, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("PANGKAT_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  '".$this->getField("STLUD")."',
				  '".$this->getField("NO_STLUD")."',
				  ".$this->getField("TANGGAL_STLUD").",
				  '".$this->getField("NO_NOTA")."',
				  ".$this->getField("TANGGAL_NOTA").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_PANGKAT").",
				  ".$this->getField("KREDIT").",
				  ".$this->getField("JENIS_RIWAYAT").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  ".$this->getField("NO_URUT_CETAK").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PANGKAT_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function insertHukuman()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PANGKAT_RIWAYAT_ID", $this->getNextId("PANGKAT_RIWAYAT_ID","PANGKAT_RIWAYAT"));
     	$str = "
			INSERT INTO PANGKAT_RIWAYAT (
				PANGKAT_RIWAYAT_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, 
				NO_SK, TANGGAL_SK, TMT_PANGKAT, JENIS_RIWAYAT, STATUS,
				KETERANGAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, LAST_USER, LAST_DATE, LAST_LEVEL
				, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("PANGKAT_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_PANGKAT").",
				  ".$this->getField("JENIS_RIWAYAT").",
				  ".$this->getField("STATUS").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PANGKAT_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		//STATUS=  NULL,
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PANGKAT_RIWAYAT
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  	STLUD= '".$this->getField("STLUD")."',
				  	NO_STLUD= '".$this->getField("NO_STLUD")."',
				  	TANGGAL_STLUD= ".$this->getField("TANGGAL_STLUD").",
				  	NO_NOTA= '".$this->getField("NO_NOTA")."',
				  	TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_PANGKAT= ".$this->getField("TMT_PANGKAT").",
				  	KREDIT= ".$this->getField("KREDIT").",
				  	JENIS_RIWAYAT= ".$this->getField("JENIS_RIWAYAT").",
				  	KETERANGAN= '".$this->getField("KETERANGAN")."',
				  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
					NO_URUT_CETAK=".$this->getField("NO_URUT_CETAK").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateHukuman()
	{
		//STATUS=  NULL,
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PANGKAT_RIWAYAT
				SET    
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_PANGKAT= ".$this->getField("TMT_PANGKAT").",
				  	JENIS_RIWAYAT= ".$this->getField("JENIS_RIWAYAT").",
				  	KETERANGAN= '".$this->getField("KETERANGAN")."',
				  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PANGKAT_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   USER_LOGIN_ID	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PANGKAT_RIWAYAT_ID    = ".$this->getField("PANGKAT_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function insertDataBkn()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PANGKAT_RIWAYAT_ID", $this->getNextId("PANGKAT_RIWAYAT_ID","PANGKAT_RIWAYAT"));
     	$str = "
			INSERT INTO PANGKAT_RIWAYAT (
				PANGKAT_RIWAYAT_ID, PEGAWAI_ID,NO_SK,TMT_PANGKAT,PANGKAT_ID,TANGGAL_SK,MASA_KERJA_TAHUN,MASA_KERJA_BULAN,NO_NOTA,TANGGAL_NOTA,KETERANGAN,JENIS_RIWAYAT
			) 
			VALUES (
			".$this->getField("PANGKAT_RIWAYAT_ID").",
			".$this->getField("PEGAWAI_ID").",
			'".$this->getField("NO_SK")."',
			".$this->getField("TMT_PANGKAT").",
			".$this->getField("PANGKAT_ID").",
			".$this->getField("TANGGAL_SK").",
			".$this->getField("MASA_KERJA_TAHUN").",
			".$this->getField("MASA_KERJA_BULAN").",
			'".$this->getField("NO_NOTA")."',
			".$this->getField("TANGGAL_NOTA").",
			'".$this->getField("KETERANGAN")."',
				".$this->getField("JENIS_RIWAYAT")."
				
			)
		"; 	
		$this->id = $this->getField("PANGKAT_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

     function updateBknData()
    {
		$str = "		
		UPDATE PANGKAT_RIWAYAT
		SET    
		
		PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		NO_SK= '".$this->getField("NO_SK")."',
		KETERANGAN= '".$this->getField("KETERANGAN")."',
		TMT_PANGKAT= ".$this->getField("TMT_PANGKAT").",
		PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
		TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
		JENIS_RIWAYAT= ".$this->getField("JENIS_RIWAYAT").",
		MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
		MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
		NO_NOTA= '".$this->getField("NO_NOTA")."',
		TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA")."
	
		WHERE  PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

       function updateIdSapk()
    {
		$str = "		
		UPDATE PANGKAT_RIWAYAT
		SET    
		
		 	 ID_SAPK= '".$this->getField("ID_SAPK")."'
	
		WHERE  PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PANGKAT_RIWAYAT SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
		$strLog= "
				DELETE FROM PANGKAT_RIWAYAT_LOG
				WHERE PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
				";
		$this->query = $strLog;
		$this->execQuery($strLog);
		
       $str = "
				DELETE FROM PANGKAT_RIWAYAT
				WHERE PANGKAT_RIWAYAT_ID = ".$this->getField("PANGKAT_RIWAYAT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PANGKAT_RIWAYAT_ID ASC')
	{
		$str = "
				SELECT 	
					A.PANGKAT_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.STLUD, A.NO_STLUD, A.TANGGAL_STLUD, A.NO_NOTA
					, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK, A.TMT_PANGKAT, A.KREDIT, A.JENIS_RIWAYAT, A.KETERANGAN, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
					, A.GAJI_POKOK, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
					, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
					, CASE A.JENIS_RIWAYAT WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
					WHEN 4 THEN 'Reguler'
					WHEN 11 THEN 'Kenaikan Pangkat Pengabdian'
					WHEN 5 THEN 'Pilihan Struktural'
					WHEN 6 THEN 'Pilihan JFT'
					WHEN 7 THEN 'Pilihan PI/UD'
					WHEN 10 THEN 'Penambahan Masa Kerja'
					WHEN 8 THEN 'Hukuman disiplin'
					WHEN 9 THEN 'Pemulihan hukuman disiplin'
					ELSE '-' END JENIS_RIWAYAT_NAMA
					, PP.NAMA PEJABAT_PENETAP_NAMA
					, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
					, A.LAST_USER, A.LAST_DATE, A.NO_URUT_CETAK
					, COALESCE(HK.HUKUMAN_ID,HK1.HUKUMAN_ID,0) DATA_HUKUMAN,A.ID_SAPK
				FROM PANGKAT_RIWAYAT A
				LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
				LEFT JOIN HUKUMAN HK ON A.PANGKAT_RIWAYAT_ID = HK.PANGKAT_RIWAYAT_TURUN_ID AND (COALESCE(NULLIF(HK.STATUS, ''), NULL) IS NULL OR HK.STATUS = '2')
				LEFT JOIN HUKUMAN HK1 ON A.PANGKAT_RIWAYAT_ID = HK1.PANGKAT_RIWAYAT_KEMBALI_ID AND (COALESCE(NULLIF(HK1.STATUS, ''), NULL) IS NULL OR HK1.STATUS = '2')
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
				SELECT COUNT(A.PANGKAT_RIWAYAT_ID) AS ROWCOUNT 
				FROM PANGKAT_RIWAYAT A
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
	
	function getCountByParamsGajiRiwayatId($statement='')
	{
		$str = "
				SELECT A.GAJI_RIWAYAT_ID AS ROWCOUNT 
				FROM GAJI_RIWAYAT A
				WHERE 1 = 1 ".$statement; 
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "";
    }

  } 
?>