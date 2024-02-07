<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class Gajiriwayat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Gajiriwayat()
	{
      $this->Entity(); 
    }

    function insert()
	{

		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.GAJI_RIWAYAT"));
     	$str = "
			INSERT INTO validasi.GAJI_RIWAYAT (
				GAJI_RIWAYAT_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, NO_SK, TANGGAL_SK, TMT_SK, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, JENIS_KENAIKAN, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			) 
			VALUES (
				  ".$this->getField("GAJI_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_SK").",
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  ".$this->getField("JENIS_KENAIKAN").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  ".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	
  
		$this->id = $this->getField("GAJI_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE validasi.GAJI_RIWAYAT
				SET    
				  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  NO_SK= '".$this->getField("NO_SK")."',
				  TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  TMT_SK= ".$this->getField("TMT_SK").",
				  MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  JENIS_KENAIKAN= ".$this->getField("JENIS_KENAIKAN").",
				  LAST_USER= '".$this->getField("LAST_USER")."',
				  LAST_DATE= ".$this->getField("LAST_DATE").",
				  LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				  USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TMT_SK DESC')
	{
		$str = "
		SELECT 	
			A.GAJI_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, COALESCE(A.PEJABAT_PENETAP, PP.NAMA) PEJABAT_PENETAP, A.PANGKAT_ID, A.NO_SK, A.TANGGAL_SK,
			A.TMT_SK, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK, A.JENIS_KENAIKAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, CASE A.JENIS_KENAIKAN WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
			WHEN 3 THEN 'Gaji Berkala'
			WHEN 4 THEN 'Kenaikan Pangkat'
			WHEN 5 THEN 'KP Pilihan Struktural'
			WHEN 6 THEN 'KP Pilihan JFT'
			WHEN 7 THEN 'KP Pilihan PI/UD'
			WHEN 10 THEN 'Penambahan Masa Kerja'
			WHEN 8 THEN 'KP Hukuman disiplin'
			WHEN 9 THEN 'KP Pemulihan hukuman disiplin'
			ELSE '-' END JENIS_KENAIKAN_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
			, COALESCE(HK.HUKUMAN_ID,HK1.HUKUMAN_ID,0) DATA_HUKUMAN
		FROM GAJI_RIWAYAT A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN HUKUMAN HK ON A.GAJI_RIWAYAT_ID = HK.GAJI_RIWAYAT_TURUN_ID
		LEFT JOIN HUKUMAN HK1 ON A.GAJI_RIWAYAT_ID = HK1.GAJI_RIWAYAT_KEMBALI_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }


    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TMT_SK DESC')
	{
		$str = "
		SELECT 	
			A.GAJI_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, COALESCE(A.PEJABAT_PENETAP, PP.NAMA) PEJABAT_PENETAP, A.PANGKAT_ID, A.NO_SK, A.TANGGAL_SK,
			A.TMT_SK, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK, A.JENIS_KENAIKAN
			, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, CASE A.JENIS_KENAIKAN WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
			WHEN 3 THEN 'Kenaikan Gaji Berkala'
			WHEN 4 THEN 'Kenaikan Pangkat'
			WHEN 5 THEN 'KP Pilihan Struktural'
			WHEN 6 THEN 'KP Pilihan JFT'
			WHEN 7 THEN 'KP Pilihan PI/UD'
			WHEN 10 THEN 'Penambahan Masa Kerja'
			WHEN 8 THEN 'KP Hukuman disiplin'
			WHEN 9 THEN 'KP Pemulihan hukuman disiplin'
			ELSE '-' END JENIS_KENAIKAN_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
			, COALESCE(HK.HUKUMAN_ID,HK1.HUKUMAN_ID,0) DATA_HUKUMAN
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_gaji_riwayat('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN HUKUMAN HK ON A.GAJI_RIWAYAT_ID = HK.GAJI_RIWAYAT_TURUN_ID
		LEFT JOIN HUKUMAN HK1 ON A.GAJI_RIWAYAT_ID = HK1.GAJI_RIWAYAT_KEMBALI_ID
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.GAJI_RIWAYAT_ID) AS ROWCOUNT 
				FROM GAJI_RIWAYAT A
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