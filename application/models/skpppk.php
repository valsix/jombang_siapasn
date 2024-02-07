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
  
  class SkPppk extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SkPppk()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SK_PPPK_ID", $this->getNextId("SK_PPPK_ID","SK_PPPK"));
     	$str = "
			INSERT INTO SK_PPPK (
			SK_PPPK_ID, TRIGER_PANGKAT, PEGAWAI_ID
			, NIP_PPPK, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, GOLONGAN_PPPK_ID, NO_NOTA
			, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_PPPK, TMT_PPPK_AKHIR, NO_URUT, TANGGAL_TUGAS
			, NO_STTPP, TANGGAL_STTPP, NAMA_PENETAP, NIP_PENETAP, MASA_KERJA_TAHUN, MASA_KERJA_BULAN
			, GAJI_POKOK, NO_PERSETUJUAN_NIP
			, TANGGAL_PERSETUJUAN_NIP, FORMASI_PPPK_ID, JABATAN_TUGAS
			, JENIS_FORMASI_TUGAS_ID, JABATAN_FU_ID, JABATAN_FT_ID, STATUS_SK_PPPK, SPMT_NOMOR, SPMT_TANGGAL, SPMT_TMT
			, PENDIDIKAN_RIWAYAT_ID
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES 
			(
				".$this->getField("SK_PPPK_ID").",
				NULL,
				".$this->getField("PEGAWAI_ID").",
				'".$this->getField("NIP_PPPK")."',
				".$this->getField("PEJABAT_PENETAP_ID").",
				'".$this->getField("PEJABAT_PENETAP")."',
				".$this->getField("GOLONGAN_PPPK_ID").",
				'".$this->getField("NO_NOTA")."',
				".$this->getField("TANGGAL_NOTA").",
				'".$this->getField("NO_SK")."',
				".$this->getField("TANGGAL_SK").",
				".$this->getField("TMT_PPPK").",
				".$this->getField("TMT_PPPK_AKHIR").",
				".$this->getField("NO_URUT").",
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
				".$this->getField("FORMASI_PPPK_ID").",
				'".$this->getField("JABATAN_TUGAS")."',
				".$this->getField("JENIS_FORMASI_TUGAS_ID").",
				".$this->getField("JABATAN_FU_ID").",
				".$this->getField("JABATAN_FT_ID").",
				".$this->getField("STATUS_SK_PPPK").",
				'".$this->getField("SPMT_NOMOR")."',
				".$this->getField("SPMT_TANGGAL").",
				".$this->getField("SPMT_TMT").",
				".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SK_PPPK_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

 	function update() 
	{
		$str = "		
		UPDATE SK_PPPK
		SET
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			TRIGER_PANGKAT = NULL,
			NIP_PPPK= '".$this->getField("NIP_PPPK")."',
			PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
			PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
			GOLONGAN_PPPK_ID= ".$this->getField("GOLONGAN_PPPK_ID").",
			NO_NOTA= '".$this->getField("NO_NOTA")."',
			TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
			NO_SK= '".$this->getField("NO_SK")."',
			TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
			TMT_PPPK= ".$this->getField("TMT_PPPK").",
			TMT_PPPK_AKHIR= ".$this->getField("TMT_PPPK_AKHIR").",
			NO_URUT= ".$this->getField("NO_URUT").",
			TANGGAL_TUGAS= ".$this->getField("TANGGAL_TUGAS").",
			TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
			NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
			NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
			MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
			MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
			GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
			NO_PERSETUJUAN_NIP= '".$this->getField("NO_PERSETUJUAN_NIP")."',
			TANGGAL_PERSETUJUAN_NIP= ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
			FORMASI_PPPK_ID= ".$this->getField("FORMASI_PPPK_ID").",
			JABATAN_TUGAS= '".$this->getField("JABATAN_TUGAS")."',
			JENIS_FORMASI_TUGAS_ID= ".$this->getField("JENIS_FORMASI_TUGAS_ID").",
			JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID").",
			JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID").",
			STATUS_SK_PPPK= ".$this->getField("STATUS_SK_PPPK").",
			SPMT_NOMOR= '".$this->getField("SPMT_NOMOR")."',
			SPMT_TANGGAL= ".$this->getField("SPMT_TANGGAL").",
			SPMT_TMT= ".$this->getField("SPMT_TMT").",
			PENDIDIKAN_RIWAYAT_ID= ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE SK_PPPK_ID = ".$this->getField("SK_PPPK_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_PPPK
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	GOLONGAN_PPPK_ID= ".$this->getField("GOLONGAN_PPPK_ID").",
				  	NO_NOTA= '".$this->getField("NO_NOTA")."',
				  	TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_PPPK= ".$this->getField("TMT_PPPK").",
				  	TANGGAL_TUGAS= ".$this->getField("TANGGAL_TUGAS").",
				  	NO_STTPP= '".$this->getField("NO_STTPP")."',
				  	TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
				  	NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
				  	NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
				  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  	NO_PERSETUJUAN_NIP= '".$this->getField("NO_PERSETUJUAN_NIP")."',
				  	TANGGAL_PERSETUJUAN_NIP= ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
				  	PENDIDIKAN_RIWAYAT_ID= ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  SK_PPPK_ID = ".$this->getField("SK_PPPK_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
		UPDATE SK_PPPK
		SET    
			STATUS= ".$this->getField("STATUS").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE SK_PPPK_ID= ".$this->getField("SK_PPPK_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateHapusStatus()
	{
		/*$str1 = "		
		UPDATE PANGKAT_RIWAYAT
		SET
			STATUS= '1',
			LAST_USER= '".$this->getField("LAST_USER")."',
			USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND JENIS_RIWAYAT = 1
		AND (COALESCE(NULLIF(STATUS, ''), NULL) IS NULL OR STATUS = '2')
		"; 
		$this->query = $str1;
		// echo $str1;exit;
		$this->execQuery($str1);

		$str = "		
		UPDATE GAJI_RIWAYAT
		SET
			STATUS= '1',
			LAST_USER= '".$this->getField("LAST_USER")."',
			USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND JENIS_KENAIKAN = 1
		AND (COALESCE(NULLIF(STATUS, ''), NULL) IS NULL OR STATUS = '2')
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);*/
    }
	
	function delete()
	{
        $str = "
				UPDATE SK_PPPK SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SK_PPPK_ID = ".$this->getField("SK_PPPK_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function hapus()
	{
        $str = "
				DELETE FROM SK_PPPK 
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SK_PPPK_ID ASC')
	{
		$str = "
		SELECT
			A.*
			, PEND.PENDIDIKAN_NAMA, PEND.PENDIDIKAN_JURUSAN_NAMA
			, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
		FROM SK_PPPK A
		LEFT JOIN GOLONGAN_PPPK B ON A.GOLONGAN_PPPK_ID = B.GOLONGAN_PPPK_ID
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
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.SK_PPPK_ID) AS ROWCOUNT 
				FROM SK_PPPK A
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

    function selectByParamsFt($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.NAMA ASC')
	{
		$str = "
		SELECT
			A.*
		FROM JABATAN_FT_PPPK A
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

  } 
?>