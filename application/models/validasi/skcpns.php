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
  include_once(APPPATH.'/models/Entity.php');
  
  class Skcpns extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Skcpns()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.SK_CPNS_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.NO_NOTA, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK,
		A.TMT_CPNS, A.TANGGAL_TUGAS, A.NO_STTPP, A.TANGGAL_STTPP, A.NAMA_PENETAP, A.NIP_PENETAP, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN,
		A.GAJI_POKOK, A.NO_PERSETUJUAN_NIP, A.TANGGAL_PERSETUJUAN_NIP, A.PENDIDIKAN_RIWAYAT_ID
		, PEND.PENDIDIKAN_NAMA, PEND.PENDIDIKAN_JURUSAN_NAMA
		, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, B.NAMA PANGKAT_NAMA
		, PP.NAMA PEJABAT_PENETAP_NAMA
		, A.FORMASI_CPNS_ID, A.JABATAN_TUGAS
		, A.JENIS_FORMASI_TUGAS_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, A.STATUS_SK_CPNS
		, A.SPMT_NOMOR, A.SPMT_TANGGAL, A.SPMT_TMT
		FROM SK_CPNS A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN
		(
			SELECT A.PENDIDIKAN_RIWAYAT_ID, B.NAMA PENDIDIKAN_NAMA, C.NAMA PENDIDIKAN_JURUSAN_NAMA
			FROM PENDIDIKAN_RIWAYAT A
			INNER JOIN PENDIDIKAN B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
			LEFT JOIN PENDIDIKAN_JURUSAN C ON A.PENDIDIKAN_JURUSAN_ID = C.PENDIDIKAN_JURUSAN_ID
			WHERE 1=1
		) PEND ON A.PENDIDIKAN_RIWAYAT_ID = PEND.PENDIDIKAN_RIWAYAT_ID
		WHERE 1 = 1
		"; 

		foreach ($paramsArray as $key => $val)
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
		$str = "SELECT COUNT(A.SK_CPNS_ID) AS ROWCOUNT 
				FROM SK_CPNS A
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

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.SK_CPNS")); 
     	$str = "
     	INSERT INTO validasi.SK_CPNS
     	(
	     	SK_CPNS_ID, TRIGER_PANGKAT, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, NO_NOTA, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_CPNS, TANGGAL_TUGAS, 
	     	NO_STTPP, TANGGAL_STTPP, NAMA_PENETAP, NIP_PENETAP, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, NO_PERSETUJUAN_NIP, 
	     	TANGGAL_PERSETUJUAN_NIP, FORMASI_CPNS_ID, JABATAN_TUGAS
	     	, JENIS_FORMASI_TUGAS_ID, JABATAN_FU_ID, JABATAN_FT_ID, STATUS_SK_CPNS, SPMT_NOMOR, SPMT_TANGGAL, SPMT_TMT
	     	, LAST_CREATE_USER, LAST_CREATE_DATE, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
	     	, TEMP_VALIDASI_ID
     	) 
		VALUES 
		(
			".$this->getField("SK_CPNS_ID").",
			NULL,
			".$this->getField("PEGAWAI_ID").",
			".$this->getField("PEJABAT_PENETAP_ID").",
			'".$this->getField("PEJABAT_PENETAP")."',
			".$this->getField("PANGKAT_ID").",
			'".$this->getField("NO_NOTA")."',
			".$this->getField("TANGGAL_NOTA").",
			'".$this->getField("NO_SK")."',
			".$this->getField("TANGGAL_SK").",
			".$this->getField("TMT_CPNS").",
			".$this->getField("TANGGAL_TUGAS").",
			'".$this->getField("NO_STTPP")."',
			".$this->getField("TANGGAL_STTPP").",
			'".$this->getField("NAMA_PENETAP")."',
			'".$this->getField("NIP_PENETAP")."',
			".$this->getField("MASA_KERJA_TAHUN").",
			".$this->getField("MASA_KERJA_BULAN").",
			".$this->getField("GAJI_POKOK").",
			'".$this->getField("NO_PERSETUJUAN_NIP")."',
			".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
			".$this->getField("FORMASI_CPNS_ID").",
			'".$this->getField("JABATAN_TUGAS")."',
			".$this->getField("JENIS_FORMASI_TUGAS_ID").",
			".$this->getField("JABATAN_FU_ID").",
			".$this->getField("JABATAN_FT_ID").",
			".$this->getField("STATUS_SK_CPNS").",
			'".$this->getField("SPMT_NOMOR")."',
			".$this->getField("SPMT_TANGGAL").",
			".$this->getField("SPMT_TMT").",
			'".$this->getField("LAST_USER")."',
			".$this->getField("LAST_DATE").",
			'".$this->getField("LAST_USER")."',
			".$this->getField("LAST_DATE").",
			".$this->getField("LAST_LEVEL").",
			".$this->getField("USER_LOGIN_ID").",
			".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			".$this->getField("TEMP_VALIDASI_ID")."
		)
		"; 	
		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

 	function update() 
	{
		$str = "		
		UPDATE validasi.SK_CPNS
		SET   
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			TRIGER_PANGKAT = NULL,
			PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
			PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
			PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
			NO_NOTA= '".$this->getField("NO_NOTA")."',
			TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
			NO_SK= '".$this->getField("NO_SK")."',
			TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
			TMT_CPNS= ".$this->getField("TMT_CPNS").",
			TANGGAL_TUGAS= ".$this->getField("TANGGAL_TUGAS").",
			TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
			NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
			NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
			MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
			MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
			GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
			NO_PERSETUJUAN_NIP= '".$this->getField("NO_PERSETUJUAN_NIP")."',
			TANGGAL_PERSETUJUAN_NIP= ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
			FORMASI_CPNS_ID= ".$this->getField("FORMASI_CPNS_ID").",
			JABATAN_TUGAS= '".$this->getField("JABATAN_TUGAS")."',
			JENIS_FORMASI_TUGAS_ID= ".$this->getField("JENIS_FORMASI_TUGAS_ID").",
			JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID").",
			JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID").",
			STATUS_SK_CPNS= ".$this->getField("STATUS_SK_CPNS").",
			SPMT_NOMOR= '".$this->getField("SPMT_NOMOR")."',
			SPMT_TANGGAL= ".$this->getField("SPMT_TANGGAL").",
			SPMT_TMT= ".$this->getField("SPMT_TMT").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
			VALIDASI= ".$this->getField("VALIDASI")."
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatetanggalvalidasi()
	{
		$str = "		
		UPDATE validasi.SK_CPNS 
		SET
			TANGGAL_VALIDASI= NOW()
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updatevalidasi()
	{
		$str = "		
		UPDATE validasi.SK_CPNS 
		SET
			VALIDASI= ".$this->getField("VALIDASI").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $rowid="", $statement='', $order='')
	{
		$str = "
		SELECT
		A.SK_CPNS_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.NO_NOTA, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK,
		A.TMT_CPNS, A.TANGGAL_TUGAS, A.NO_STTPP, A.TANGGAL_STTPP, A.NAMA_PENETAP, A.NIP_PENETAP, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN,
		A.GAJI_POKOK, A.NO_PERSETUJUAN_NIP, A.TANGGAL_PERSETUJUAN_NIP, A.PENDIDIKAN_RIWAYAT_ID
		, PEND.PENDIDIKAN_NAMA, PEND.PENDIDIKAN_JURUSAN_NAMA
		, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, B.NAMA PANGKAT_NAMA
		, PP.NAMA PEJABAT_PENETAP_NAMA
		, A.FORMASI_CPNS_ID, A.JABATAN_TUGAS
		, A.JENIS_FORMASI_TUGAS_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, A.STATUS_SK_CPNS
		, A.SPMT_NOMOR, A.SPMT_TANGGAL, A.SPMT_TMT
		, TEMP_VALIDASI_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_sk_cpns('".$pegawaiid."', '".$rowid."')) A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN
		(
			SELECT A.PENDIDIKAN_RIWAYAT_ID, B.NAMA PENDIDIKAN_NAMA, C.NAMA PENDIDIKAN_JURUSAN_NAMA
			FROM PENDIDIKAN_RIWAYAT A
			INNER JOIN PENDIDIKAN B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
			LEFT JOIN PENDIDIKAN_JURUSAN C ON A.PENDIDIKAN_JURUSAN_ID = C.PENDIDIKAN_JURUSAN_ID
			WHERE 1=1
		) PEND ON A.PENDIDIKAN_RIWAYAT_ID = PEND.PENDIDIKAN_RIWAYAT_ID
		WHERE 1 = 1
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }

  } 
?>