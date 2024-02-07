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
  
  class Tambahanmasakerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Tambahanmasakerja()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.TAMBAHAN_MASA_KERJA")); 

		$str = "INSERT INTO validasi.TAMBAHAN_MASA_KERJA (
				   TAMBAHAN_MASA_KERJA_ID, TRIGER_PANGKAT, PEGAWAI_ID, NO_SK, 
				   TANGGAL_SK, TMT_SK, TAHUN_TAMBAHAN, 
				   BULAN_TAMBAHAN, TAHUN_BARU, BULAN_BARU
				   , NO_NOTA, TANGGAL_NOTA, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, GAJI_POKOK
				   , LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID)
				VALUES (
					".$this->getField("TAMBAHAN_MASA_KERJA_ID").",
					NULL,
					".$this->getField("PEGAWAI_ID").",
					'".$this->getField("NO_SK")."',
					".$this->getField("TANGGAL_SK").",
					".$this->getField("TMT_SK").",
					'".$this->getField("TAHUN_TAMBAHAN")."',
					'".$this->getField("BULAN_TAMBAHAN")."',
					'".$this->getField("TAHUN_BARU")."',
					'".$this->getField("BULAN_BARU")."',
					'".$this->getField("NO_NOTA")."',
					".$this->getField("TANGGAL_NOTA").",
					".$this->getField("PEJABAT_PENETAP_ID").",
					'".$this->getField("PEJABAT_PENETAP")."',
					".$this->getField("PANGKAT_ID").",
					".$this->getField("GAJI_POKOK").",
					'".$this->getField("LAST_USER")."',
					".$this->getField("LAST_DATE").",
					'".$this->getField("LAST_USER")."',
					".$this->getField("LAST_DATE").",
					".$this->getField("LAST_LEVEL").",
					".$this->getField("USER_LOGIN_ID").",
					".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					".$this->getField("TEMP_VALIDASI_ID")."
				)";
		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE validasi.TAMBAHAN_MASA_KERJA
		SET    
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			TRIGER_PANGKAT = NULL,
			NO_SK= '".$this->getField("NO_SK")."',
			TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
			TMT_SK= ".$this->getField("TMT_SK").",
			TAHUN_TAMBAHAN= '".$this->getField("TAHUN_TAMBAHAN")."',
			BULAN_TAMBAHAN= '".$this->getField("BULAN_TAMBAHAN")."',
			TAHUN_BARU= '".$this->getField("TAHUN_BARU")."',
			BULAN_BARU= '".$this->getField("BULAN_BARU")."',
			NO_NOTA= '".$this->getField("NO_NOTA")."',
			TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
			PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
			PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
			PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
			GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE  TEMP_VALIDASI_ID= '".$this->getField("TEMP_VALIDASI_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TANGGAL_SK ASC')
	{
		$str = "
		SELECT
			TAMBAHAN_MASA_KERJA_ID, PEGAWAI_ID, NO_SK, TANGGAL_SK, TMT_SK, TAHUN_TAMBAHAN, 
			BULAN_TAMBAHAN, TAHUN_BARU, BULAN_BARU, STATUS
		FROM TAMBAHAN_MASA_KERJA A
		WHERE 1=1  AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TANGGAL_SK ASC')
	{
		$str = "
		SELECT
			TAMBAHAN_MASA_KERJA_ID, PEGAWAI_ID, NO_SK, TANGGAL_SK, TMT_SK, TAHUN_TAMBAHAN, 
			BULAN_TAMBAHAN, TAHUN_BARU, BULAN_BARU,NO_NOTA,TANGGAL_NOTA,A.PANGKAT_ID,GAJI_POKOK,A.PEJABAT_PENETAP_ID
			, B.NAMA PEJABAT_PENETAP
			, C.KODE PANGKAT_KODE
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_tambahan_masa_kerja('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN PEJABAT_PENETAP B ON A.PEJABAT_PENETAP_ID = B.PEJABAT_PENETAP_ID
		LEFT JOIN PANGKAT C ON A.PANGKAT_ID = C.PANGKAT_ID
		WHERE 1=1  
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT 
		FROM TAMBAHAN_MASA_KERJA A
		WHERE 1=1 ".$statement; 
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