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
  
  class Skpppk extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Skpppk()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.SK_PPPK"));
     	$str = "
			INSERT INTO validasi.SK_PPPK (
			SK_PPPK_ID, PEGAWAI_ID
			, NIP_PPPK, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, GOLONGAN_PPPK_ID, NO_NOTA
			, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_PPPK, TANGGAL_TUGAS
			, NO_STTPP, TANGGAL_STTPP, NAMA_PENETAP, NIP_PENETAP, MASA_KERJA_TAHUN, MASA_KERJA_BULAN
			, GAJI_POKOK, NO_PERSETUJUAN_NIP
			, TANGGAL_PERSETUJUAN_NIP, FORMASI_PPPK_ID, JABATAN_TUGAS
			, JENIS_FORMASI_TUGAS_ID, JABATAN_FU_ID, JABATAN_FT_ID, STATUS_SK_PPPK, SPMT_NOMOR, SPMT_TANGGAL, SPMT_TMT
			, PENDIDIKAN_RIWAYAT_ID
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			, TEMP_VALIDASI_ID
			) 
			VALUES 
			(
				".$this->getField("SK_PPPK_ID").",
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
		UPDATE validasi.SK_PPPK
		SET
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			NIP_PPPK= '".$this->getField("NIP_PPPK")."',
			PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
			PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
			GOLONGAN_PPPK_ID= ".$this->getField("GOLONGAN_PPPK_ID").",
			NO_NOTA= '".$this->getField("NO_NOTA")."',
			TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
			NO_SK= '".$this->getField("NO_SK")."',
			TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
			TMT_PPPK= ".$this->getField("TMT_PPPK").",
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
		WHERE TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.BAHASA_ID ASC')
	{
		$str = "
		SELECT A.BAHASA_ID, A.PEGAWAI_ID, A.JENIS,
		CASE A.JENIS WHEN '1' THEN 'Asing' ELSE 'Daerah' END JENIS_NAMA,
		A.NAMA, A.KEMAMPUAN,
		CASE A.KEMAMPUAN WHEN '1' THEN 'Aktif' ELSE 'Pasif' END KEMAMPUAN_NAMA,
		A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM BAHASA A
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
	 

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.SK_PPPK_ID ASC')
	{
		$str = "
		SELECT
			A.SK_PPPK_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.GOLONGAN_PPPK_ID, A.NO_NOTA, A.TANGGAL_NOTA
			, A.NO_SK, A.TANGGAL_SK, A.TMT_PPPK, A.TANGGAL_TUGAS, A.NO_STTPP, A.TANGGAL_STTPP, A.NAMA_PENETAP
			, A.NIP_PENETAP, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
			, PEND.PENDIDIKAN_NAMA, PEND.PENDIDIKAN_JURUSAN_NAMA
			, A.GAJI_POKOK, A.NO_PERSETUJUAN_NIP, A.TANGGAL_PERSETUJUAN_NIP, A.PENDIDIKAN_RIWAYAT_ID
			, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
			, A.FORMASI_PPPK_ID, A.JABATAN_TUGAS
			, A.JENIS_FORMASI_TUGAS_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, A.STATUS_SK_PPPK
			, A.SPMT_NOMOR, A.SPMT_TANGGAL, A.SPMT_TMT
			, A.NIP_PPPK
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_sk_pppk('".$pegawaiid."', '".$id."', '".$rowid."')) A
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
    	$str = "SELECT COUNT(A.BAHASA_ID) AS ROWCOUNT 
    	FROM BAHASA A
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