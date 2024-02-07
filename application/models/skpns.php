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
  
  class Skpns extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Skpns()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SK_PNS_ID", $this->getNextId("SK_PNS_ID","SK_PNS"));
     	$str = "
		INSERT INTO SK_PNS 
		(
			SK_PNS_ID, TRIGER_PANGKAT, PEGAWAI_ID, PANGKAT_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_PNS, SUMPAH, NAMA_PENETAP
			, NIP_PENETAP, NO_UJI_KESEHATAN, TANGGAL_UJI_KESEHATAN, NO_PRAJAB, TANGGAL_PRAJAB, TANGGAL_SUMPAH, MASA_KERJA_TAHUN
			, MASA_KERJA_BULAN, GAJI_POKOK, TANGGAL_BERITA_ACARA, NOMOR_BERITA_ACARA, KETERANGAN_LPJ, NO_SK_SUMPAH
			, PEJABAT_PENETAP_SUMPAH, PEJABAT_PENETAP_SUMPAH_ID
			, JENIS_JABATAN_ID, STATUS_CALON_JFT, JABATAN_TUGAS, JABATAN_FU_ID, JABATAN_FT_ID, JALUR_PENGANGKATAN
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES 
		(
			".$this->getField("SK_PNS_ID").",
			NULL,
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("PANGKAT_ID").",
			".$this->getField("PEJABAT_PENETAP_ID").",
			'".$this->getField("PEJABAT_PENETAP")."',
			'".$this->getField("NO_SK")."',
			".$this->getField("TANGGAL_SK").",
			".$this->getField("TMT_PNS").",
			".$this->getField("SUMPAH").",
			'".$this->getField("NAMA_PENETAP")."',
			'".$this->getField("NIP_PENETAP")."',
			'".$this->getField("NO_UJI_KESEHATAN")."',
			".$this->getField("TANGGAL_UJI_KESEHATAN").",
			'".$this->getField("NO_PRAJAB")."',
			".$this->getField("TANGGAL_PRAJAB").",
			".$this->getField("TANGGAL_SUMPAH").",
			".$this->getField("MASA_KERJA_TAHUN").",
			".$this->getField("MASA_KERJA_BULAN").",
			".$this->getField("GAJI_POKOK").",
			".$this->getField("TANGGAL_BERITA_ACARA").",
			'".$this->getField("NOMOR_BERITA_ACARA")."',
			'".$this->getField("KETERANGAN_LPJ")."',
			'".$this->getField("NO_SK_SUMPAH")."',
			'".$this->getField("PEJABAT_PENETAP_SUMPAH")."',
			".$this->getField("PEJABAT_PENETAP_SUMPAH_ID").",
			".$this->getField("JENIS_JABATAN_ID").",
			".$this->getField("STATUS_CALON_JFT").",
			'".$this->getField("JABATAN_TUGAS")."',
			".$this->getField("JABATAN_FU_ID").",
			".$this->getField("JABATAN_FT_ID").",
			".$this->getField("JALUR_PENGANGKATAN").",
			'".$this->getField("LAST_USER")."',
			".$this->getField("LAST_DATE").",
			".$this->getField("LAST_LEVEL").",
			'".$this->getField("USER_LOGIN_ID")."',
			".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->id = $this->getField("SK_PNS_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
    
    function update()
	{
		$str = "		
		UPDATE SK_PNS
		SET    
		  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			TRIGER_PANGKAT = NULL,
		  	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
		  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
		  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
		  	NO_SK= '".$this->getField("NO_SK")."',
		  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
		  	TMT_PNS= ".$this->getField("TMT_PNS").",
		  	SUMPAH= ".$this->getField("SUMPAH").",
		  	NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
		  	NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
		  	NO_UJI_KESEHATAN= '".$this->getField("NO_UJI_KESEHATAN")."',
		  	TANGGAL_UJI_KESEHATAN= ".$this->getField("TANGGAL_UJI_KESEHATAN").",
		  	NO_PRAJAB= '".$this->getField("NO_PRAJAB")."',
		  	TANGGAL_PRAJAB= ".$this->getField("TANGGAL_PRAJAB").",
		  	TANGGAL_SUMPAH= ".$this->getField("TANGGAL_SUMPAH").",
		  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
		  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
		  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
		  	TANGGAL_BERITA_ACARA= ".$this->getField("TANGGAL_BERITA_ACARA").",
		  	NOMOR_BERITA_ACARA= '".$this->getField("NOMOR_BERITA_ACARA")."',
		  	KETERANGAN_LPJ= '".$this->getField("KETERANGAN_LPJ")."',
		  	NO_SK_SUMPAH= '".$this->getField("NO_SK_SUMPAH")."',
		  	PEJABAT_PENETAP_SUMPAH= '".$this->getField("PEJABAT_PENETAP_SUMPAH")."',
		  	PEJABAT_PENETAP_SUMPAH_ID= ".$this->getField("PEJABAT_PENETAP_SUMPAH_ID").",
		  	JENIS_JABATAN_ID= ".$this->getField("JENIS_JABATAN_ID").",
			STATUS_CALON_JFT= ".$this->getField("STATUS_CALON_JFT").",
			JABATAN_TUGAS= '".$this->getField("JABATAN_TUGAS")."',
			JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID").",
			JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID").",
			JALUR_PENGANGKATAN= ".$this->getField("JALUR_PENGANGKATAN").",
		  	LAST_USER= '".$this->getField("LAST_USER")."',
		  	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
		  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
		  	LAST_DATE= ".$this->getField("LAST_DATE").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			WHERE SK_PNS_ID = ".$this->getField("SK_PNS_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_PNS
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	SUMPAH= ".$this->getField("SUMPAH").",
				  	NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
				  	NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
				  	NO_UJI_KESEHATAN= '".$this->getField("NO_UJI_KESEHATAN")."',
				  	TANGGAL_UJI_KESEHATAN= ".$this->getField("TANGGAL_UJI_KESEHATAN").",
				  	NO_PRAJAB= '".$this->getField("NO_PRAJAB")."',
				  	TANGGAL_PRAJAB= ".$this->getField("TANGGAL_PRAJAB").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  	PEJABAT_PENETAP_SUMPAH= '".$this->getField("PEJABAT_PENETAP_SUMPAH")."',
				  	PEJABAT_PENETAP_SUMPAH_ID= ".$this->getField("PEJABAT_PENETAP_SUMPAH_ID").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	LAST_DATE= ".$this->getField("LAST_DATE")."
					WHERE  SK_PNS_ID = ".$this->getField("SK_PNS_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_PNS
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  SK_PNS_ID    	= ".$this->getField("SK_PNS_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SK_PNS SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SK_PNS_ID = ".$this->getField("SK_PNS_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SK_PNS_ID ASC')
	{
		$str = "
		SELECT 	
		A.SK_PNS_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NO_SK, A.TANGGAL_SK, A.TMT_PNS
		, A.SUMPAH, A.NAMA_PENETAP, A.NIP_PENETAP
		, A.NO_UJI_KESEHATAN, A.TANGGAL_UJI_KESEHATAN, A.NO_PRAJAB, A.TANGGAL_PRAJAB, A.TANGGAL_SUMPAH
		, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
		, A.GAJI_POKOK, A.TANGGAL_BERITA_ACARA, A.NOMOR_BERITA_ACARA, A.KETERANGAN_LPJ, A.NO_SK_SUMPAH
		, A.PEJABAT_PENETAP_SUMPAH, A.PEJABAT_PENETAP_SUMPAH_ID
		, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, A.PANGKAT_ID, B.NAMA PANGKAT_NAMA
		, PP.NAMA PEJABAT_PENETAP_NAMA
		, A.JENIS_JABATAN_ID, A.STATUS_CALON_JFT, A.JABATAN_TUGAS, A.JABATAN_FU_ID, A.JABATAN_FT_ID, A.JALUR_PENGANGKATAN
		FROM SK_PNS A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
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
				SELECT COUNT(A.SK_PNS_ID) AS ROWCOUNT 
				FROM SK_PNS A
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