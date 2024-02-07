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

  class TambahanMasaKerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function TambahanMasaKerja()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TAMBAHAN_MASA_KERJA_ID", $this->getNextId("TAMBAHAN_MASA_KERJA_ID","TAMBAHAN_MASA_KERJA")); 

		$str = "INSERT INTO TAMBAHAN_MASA_KERJA (
				   TAMBAHAN_MASA_KERJA_ID, TRIGER_PANGKAT, PEGAWAI_ID, NO_SK, 
				   TANGGAL_SK, TMT_SK, TAHUN_TAMBAHAN, 
				   BULAN_TAMBAHAN, TAHUN_BARU, BULAN_BARU
				   , NO_NOTA, TANGGAL_NOTA, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, GAJI_POKOK
				   , LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID)
				VALUES (
					".$this->getField("TAMBAHAN_MASA_KERJA_ID").",
					NULL,
					'".$this->getField("PEGAWAI_ID")."',
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
					".$this->getField("LAST_LEVEL").",
					".$this->getField("USER_LOGIN_ID").",
					".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				)";
		$this->id = $this->getField("TAMBAHAN_MASA_KERJA_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
		UPDATE TAMBAHAN_MASA_KERJA
		SET    
			PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."',
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
		WHERE  TAMBAHAN_MASA_KERJA_ID= '".$this->getField("TAMBAHAN_MASA_KERJA_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function update_format()
	{
		$str = "
				UPDATE TAMBAHAN_MASA_KERJA
				SET
					   UKURAN= ".$this->getField("UKURAN").",
					   FORMAT= '".$this->getField("FORMAT")."',
					   USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID= '".$this->getField("USER_LOGIN_PEGAWAI_ID")."'
				WHERE  PEGAWAI_ID = '".$this->getField("PEGAWAI_ID")."'
			 "; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE TAMBAHAN_MASA_KERJA
				SET    
					   TRIGER_PANGKAT = NULL,
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  TAMBAHAN_MASA_KERJA_ID= ".$this->getField("TAMBAHAN_MASA_KERJA_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE TAMBAHAN_MASA_KERJA SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE TAMBAHAN_MASA_KERJA_ID = ".$this->getField("TAMBAHAN_MASA_KERJA_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL_SK"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY TANGGAL_SK ASC")
	{
		$str = "
		SELECT
			A.TAMBAHAN_MASA_KERJA_ID, A.PEGAWAI_ID, A.NO_SK, A.TANGGAL_SK, A.TMT_SK
			, A.TAHUN_TAMBAHAN, A.BULAN_TAMBAHAN, A.TAHUN_BARU, A.BULAN_BARU, A.STATUS
			, A.PANGKAT_ID, A.NO_NOTA, A.TANGGAL_NOTA, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.GAJI_POKOK
		FROM TAMBAHAN_MASA_KERJA A
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		WHERE 1=1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ".$sOrder;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","TANGGAL_SK"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM TAMBAHAN_MASA_KERJA A
		WHERE 1=1 ".$statement;

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