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
  include_once('Entity.php');
  
  class SuratMasukPegawaiCheck extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratMasuk()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_CHECK_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_CHECK_ID","PERSURATAN.SURAT_MASUK_PEGAWAI_CHECK")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI_CHECK (
				SURAT_MASUK_PEGAWAI_CHECK_ID, SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, NOMOR
				, JENIS_PELAYANAN_ID, TIPE, NAMA, INFO_CHECKED, LINK_FILE, STATUS_INFORMASI, INFORMASI_TABLE, INFORMASI_FIELD, KATEGORI_FILE_ID
				, KATEGORI)
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_CHECK_ID").",
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("NOMOR").",
				 ".$this->getField("JENIS_PELAYANAN_ID").",
				 '".$this->getField("TIPE")."',
				 '".$this->getField("NAMA")."',
				 ".$this->getField("INFO_CHECKED").",
				 '".$this->getField("LINK_FILE")."',
				 ".$this->getField("STATUS_INFORMASI").",
				 '".$this->getField("INFORMASI_TABLE")."',
				 '".$this->getField("INFORMASI_FIELD")."',
				 ".$this->getField("KATEGORI_FILE_ID").",
				 '".$this->getField("KATEGORI")."'
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_CHECK_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI_CHECK
				SET
				 	INFO_CHECKED= ".$this->getField("INFO_CHECKED")."
				WHERE SURAT_MASUK_PEGAWAI_CHECK_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_CHECK_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKeteranganTeknis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	KETERANGAN_TEKNIS= '".$this->getField("KETERANGAN_TEKNIS")."'
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusVerifikasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_VERIFIKASI= ".$this->getField("STATUS_VERIFIKASI")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateBatalValidasiKarpeg()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str= "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_VERIFIKASI= ".$this->getField("STATUS_VERIFIKASI")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		$this->execQuery($str);
		
		$str1= "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI_CHECK
				SET
				 	INFO_CHECKED= ".$this->getField("INFO_CHECKED")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str1;
		// echo $str;exit;
		return $this->execQuery($str1);
    }
	
	function updateBatalStatusSuratKeluar()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_SURAT_KELUAR= ".$this->getField("STATUS_SURAT_KELUAR")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		/*$this->execQuery($str);
		
		$str= "		
				DELETE FROM PERSURATAN.SURAT_KELUAR_BKD
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;*/
		// echo $str;exit;
		
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusSuratKeluar()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_SURAT_KELUAR= ".$this->getField("STATUS_SURAT_KELUAR")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		$this->execQuery($str);
		
		$str= "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
				 	STATUS_TERIMA= ".$this->getField("STATUS_SURAT_KELUAR")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PERSURATAN.URAT_MASUK SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE SURAT_MASUK_PEGAWAI_CHECK_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_CHECK_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_PEGAWAI_CHECK_ID ASC')
	{
		$str = "
				SELECT
					A.SURAT_MASUK_PEGAWAI_CHECK_ID, A.NOMOR, A.JENIS_PELAYANAN_ID, A.TIPE, A.NAMA, A.NAMA_FILE, 
					A.NAMA_FILE2, A.LINK_FILE, A.INFO_CHECKED, A.SATUAN_KERJA_ASAL_ID, B.NAMA SATUAN_KERJA_ASAL_NAMA, C.NAMA SATUAN_KERJA_TUJUAN_NAMA
				FROM PERSURATAN.SURAT_MASUK_PEGAWAI_CHECK A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA C ON A.INFO_CHECKED = C.SATUAN_KERJA_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsUsulan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=' ORDER BY B.JENIS_PELAYANAN_ID, B.NOMOR, A.PEGAWAI_ID')
	{
		$str = "
		SELECT
			B.KATEGORI_FILE_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID,
			B.JENIS_PELAYANAN_ID, B.TIPE, B.KATEGORI, B.LINK_FILE, B.STATUS_INFORMASI
			, B.INFORMASI_TABLE, B.INFORMASI_FIELD
			, A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID
			, P.NIP_BARU, SK.NOMOR, SK.TANGGAL
			, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, P.NAMA NAMA_PEGAWAI
		FROM persuratan.SURAT_MASUK_PEGAWAI A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN persuratan.SURAT_KELUAR_BKD SK ON SK.USULAN_SURAT_ID = A.USULAN_SURAT_ID
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsMonitoringCheck($paramsArray=array(),$limit=-1,$from=-1, $statement='', $idrow='', $statementdetil="", $order=' ORDER BY A.JENIS_PELAYANAN_ID, A.NOMOR')
	{
		//, $id=''
		//LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID = ".$idrow." AND B.PEGAWAI_ID = ".$id."
		$str = "
		SELECT
			COALESCE(B.NOMOR, A.NOMOR) NOMOR, COALESCE(B.JENIS_PELAYANAN_ID, A.JENIS_PELAYANAN_ID) JENIS_PELAYANAN_ID
			, B.SURAT_MASUK_PEGAWAI_CHECK_ID
			, COALESCE(B.TIPE, A.TIPE) TIPE, COALESCE(B.NAMA, A.NAMA) NAMA, A.NAMA_FILE, A.NAMA_FILE2
			, CASE WHEN COALESCE(NULLIF(B.LINK_FILE, ''), NULL) IS NULL THEN A.LINK_FILE ELSE B.LINK_FILE END LINK_FILE
			, COALESCE(B.STATUS_INFORMASI, A.STATUS_INFORMASI) STATUS_INFORMASI
			, CASE WHEN COALESCE(NULLIF(B.INFORMASI_TABLE, ''), NULL) IS NULL THEN A.INFORMASI_TABLE ELSE B.INFORMASI_TABLE END INFORMASI_TABLE
			, CASE WHEN COALESCE(NULLIF(B.INFORMASI_FIELD, ''), NULL) IS NULL THEN A.INFORMASI_FIELD ELSE B.INFORMASI_FIELD END INFORMASI_FIELD
			, B.INFO_CHECKED
			, JP.NAMA JENIS_PELAYANAN_NAMA
			, SP.PEGAWAI_ID, SP.PANGKAT_RIWAYAT_ID, SP.PENDIDIKAN_RIWAYAT_ID
			, SP.JENIS_ID, SP.SURAT_MASUK_BKD_ID, SP.SURAT_MASUK_UPT_ID
			, COALESCE(B.KATEGORI_FILE_ID, A.KATEGORI_FILE_ID) KATEGORI_FILE_ID
		FROM persuratan.JENIS_PELAYANAN_CHECK A
		INNER JOIN persuratan.JENIS_PELAYANAN JP ON A.JENIS_PELAYANAN_ID = JP.JENIS_PELAYANAN_ID
		LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID = ".$idrow.$statementdetil."
		,
		(
			SELECT A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID
			, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID
			FROM persuratan.SURAT_MASUK_PEGAWAI A
			WHERE SURAT_MASUK_PEGAWAI_ID = ".$idrow."
		) SP
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsMonitoringUsulanCheck($paramsArray=array(),$limit=-1,$from=-1, $statement='', $idrow='', $statementdetil="", $order=' ORDER BY A.JENIS_PELAYANAN_ID, A.NOMOR')
	{
		//, $id=''
		//LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID = ".$idrow." AND B.PEGAWAI_ID = ".$id."
		$str = "
		SELECT
			COALESCE(B.NOMOR, A.NOMOR) NOMOR, COALESCE(B.JENIS_PELAYANAN_ID, A.JENIS_PELAYANAN_ID) JENIS_PELAYANAN_ID
			, B.SURAT_MASUK_PEGAWAI_CHECK_ID
			, COALESCE(B.TIPE, A.TIPE) TIPE, COALESCE(B.NAMA, A.NAMA) NAMA, A.NAMA_FILE, A.NAMA_FILE2
			, COALESCE(B.LINK_FILE, A.LINK_FILE) LINK_FILE, COALESCE(B.STATUS_INFORMASI, A.STATUS_INFORMASI) STATUS_INFORMASI
			, COALESCE(B.INFORMASI_TABLE, A.INFORMASI_TABLE) INFORMASI_TABLE, COALESCE(B.INFORMASI_FIELD, A.INFORMASI_FIELD) INFORMASI_FIELD
			, B.INFO_CHECKED
			, JP.NAMA JENIS_PELAYANAN_NAMA
			, SP.PEGAWAI_ID, SP.PANGKAT_RIWAYAT_ID, SP.PENDIDIKAN_RIWAYAT_ID
			, SP.JENIS_ID, SP.SURAT_MASUK_BKD_ID, SP.SURAT_MASUK_UPT_ID
			, COALESCE(B.KATEGORI_FILE_ID, A.KATEGORI_FILE_ID) KATEGORI_FILE_ID
		FROM persuratan.JENIS_PELAYANAN_CHECK A
		INNER JOIN persuratan.JENIS_PELAYANAN JP ON A.JENIS_PELAYANAN_ID = JP.JENIS_PELAYANAN_ID
		LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID IN ".$idrow.$statementdetil."
		,
		(
			SELECT A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID
			, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID
			FROM persuratan.SURAT_MASUK_PEGAWAI A
			WHERE SURAT_MASUK_PEGAWAI_ID IN ".$idrow."
		) SP
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

	function selectByParamsFile($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE DESC')
	{
		$str = "
				SELECT A.PEGAWAI_FILE_ID, A.PEGAWAI_ID, A.RIWAYAT_TABLE, A.RIWAYAT_FIELD, A.RIWAYAT_ID, A.FILE_KUALITAS_ID, B.NAMA FILE_KUALITAS_NAMA, A.PATH, 
				A.STATUS_VERIFIKASI, A.KETERANGAN, A.STATUS,
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				, CASE RIWAYAT_TABLE WHEN 'SK_CPNS' THEN 'SK CPNS' WHEN 'SK_PNS' THEN 'SK PNS' WHEN 'JABATAN_RIWAYAT' THEN 'Jabatan' WHEN 'PANGKAT_RIWAYAT' THEN 'Kenaikan Pangkat' WHEN 'PENDIDIKAN_RIWAYAT' THEN 'Ijazah' END INFO_GROUP_DATA
				FROM PEGAWAI_FILE A
				LEFT JOIN KUALITAS_FILE B ON A.FILE_KUALITAS_ID = B.KUALITAS_FILE_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsDownload($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE DESC')
	{
		$str = "
		SELECT 
			A.PATH, RF.FORMAT_BKN
		FROM PEGAWAI_FILE A
		INNER JOIN RIWAYAT_FILE RF ON A.KATEGORI_FILE_ID = RF.NO_URUT AND A.PEGAWAI_ID = RF.PEGAWAI_ID AND A.RIWAYAT_TABLE = RF.RIWAYAT_TABLE 
		AND CASE WHEN COALESCE(NULLIF(A.RIWAYAT_FIELD, ''), NULL) IS NULL THEN '' ELSE A.RIWAYAT_FIELD END =
		CASE WHEN COALESCE(NULLIF(RF.RIWAYAT_FIELD, ''), NULL) IS NULL THEN '' ELSE RF.RIWAYAT_FIELD END
		AND COALESCE(NULLIF(CAST(A.RIWAYAT_ID AS TEXT), ''), NULL) = COALESCE(NULLIF(RF.RIWAYAT_ID, ''), NULL)
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PANGKAT_RIWAYAT_ID ASC')
	{
		$str = "
				SELECT 	
					A.PANGKAT_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.STLUD, A.NO_STLUD, A.TANGGAL_STLUD, A.NO_NOTA
					, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK, A.TMT_PANGKAT, A.KREDIT, A.JENIS_RIWAYAT, A.KETERANGAN, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
					, A.GAJI_POKOK, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
					, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
					, CASE A.JENIS_RIWAYAT WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
					WHEN 4 THEN 'Reguler'
					WHEN 5 THEN 'Pilihan Struktural'
					WHEN 6 THEN 'Pilihan JFT'
					WHEN 7 THEN 'Pilihan PI/UD'
					WHEN 8 THEN 'Hukuman disiplin'
					WHEN 9 THEN 'Pemulihan hukuman disiplin'
					ELSE '-' END JENIS_RIWAYAT_NAMA
					, PP.NAMA PEJABAT_PENETAP_NAMA
					, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
					, A.LAST_USER, A.LAST_DATE, A.NO_URUT_CETAK
				FROM PANGKAT_RIWAYAT A
				LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsKgb($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY TMT_SK DESC')
	{
		$str = "
		SELECT
			CASE WHEN PR.PANGKAT_RIWAYAT_ID IS NULL THEN 'GAJI_RIWAYAT' ELSE 'PANGKAT_RIWAYAT' END RIWAYAT_TABLE,
			CASE WHEN PR.PANGKAT_RIWAYAT_ID IS NULL THEN 'GAJI_RIWAYAT_ID' ELSE 'PANGKAT_RIWAYAT_ID' END RIWAYAT_FIELD,
			CASE WHEN PR.PANGKAT_RIWAYAT_ID IS NULL THEN GR.GAJI_RIWAYAT_ID ELSE PR.PANGKAT_RIWAYAT_ID END RIWAYAT_ID,
			GR.GAJI_RIWAYAT_ID, PR.PANGKAT_RIWAYAT_ID, GR.PEGAWAI_ID, GR.TMT_SK, PR.TMT_PANGKAT
		FROM GAJI_RIWAYAT GR
		LEFT JOIN
		(
			SELECT PR.PANGKAT_RIWAYAT_ID, PR.PEGAWAI_ID, PR.TMT_PANGKAT
			FROM PANGKAT_RIWAYAT PR
			WHERE 1=1
			AND (COALESCE(NULLIF(PR.STATUS, ''), NULL) IS NULL OR PR.STATUS = '2')
			ORDER BY PR.TMT_PANGKAT DESC
		) PR ON GR.PEGAWAI_ID = PR.PEGAWAI_ID AND GR.TMT_SK = PR.TMT_PANGKAT
		WHERE 1=1
		AND (COALESCE(NULLIF(GR.STATUS, ''), NULL) IS NULL OR GR.STATUS = '2')
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

	function selectByParamsPendidikan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENDIDIKAN_RIWAYAT_ID ASC')
	{
		$str = "
				SELECT 	
					A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.PENDIDIKAN_JURUSAN_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.NAMA, A.TEMPAT, A.KEPALA, 
					A.NO_STTB, A.TANGGAL_STTB, A.JURUSAN, A.NO_SURAT_IJIN, A.TANGGAL_SURAT_IJIN, A.STATUS_PENDIDIKAN, A.GELAR_TIPE, 
					A.GELAR_DEPAN, A.GELAR_NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS, C.NAMA PENDIDIKAN_NAMA,
					CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
					, CASE A.STATUS_PENDIDIKAN
					WHEN '1' THEN 'Pendidikan CPNS'
					WHEN '2' THEN 'Diakui'
					WHEN '3' THEN 'Belum Diakui'
					WHEN '4' THEN 'Riwayat'
					WHEN '5' THEN 'Ijin belajar'
					WHEN '6' THEN 'Tugas Belajar'
					ELSE '-' END STATUS_PENDIDIKAN_NAMA
				FROM PENDIDIKAN_RIWAYAT A
				LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
				LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsPenilaianSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENILAIAN_SKP_ID ASC')
	{
		$str = "
				SELECT 	
					A.PENILAIAN_SKP_ID, A.PEGAWAI_ID, CAST(A.TAHUN AS TEXT) TAHUN
				FROM PENILAIAN_SKP A
				WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPensiunSuamiIstri($paramsArray=array(),$limit=-1,$from=-1, $statement='', $kategori= "", $order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT
			DISTINCT
			A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.NAMA, A.TANGGAL_KAWIN, A.STATUS_S_I,
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '3' THEN 'Meninggal' ELSE 'Belum di set' END STATUS_S_I_NAMA,
			CASE A.STATUS_S_I WHEN '1' THEN 'aktanikah' WHEN '2' THEN 'aktacerai' ELSE '' END STATUS_S_I_RIWAYAT_FIELD
			, A.CERAI_TMT, A.KEMATIAN_TMT
		FROM SUAMI_ISTRI A
		LEFT JOIN
		(
			SELECT A.SUAMI_ISTRI_ID
			FROM 
			(
				SELECT P.PEGAWAI_ID, TMT, JENIS, ANAK_ID
				FROM PENSIUN P
				INNER JOIN
				(
					SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, KATEGORI, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, ANAK_ID
					FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK
				) P1 ON P.JENIS = P1.KATEGORI AND P.PEGAWAI_ID = P1.PEGAWAI_ID
				WHERE P.JENIS = '".$kategori."'
			) P
			INNER JOIN ANAK A ON P.ANAK_ID = A.ANAK_ID
		) B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsSuamiIstri($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT 	
			A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.TANGGAL_KAWIN, A.KARTU, 
			A.STATUS_PNS, A.NIP_PNS, A.PEKERJAAN, A.STATUS_TUNJANGAN, A.STATUS_BAYAR, A.BULAN_BAYAR, A.STATUS, A.STATUS_S_I, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '3' THEN 'Meninggal' ELSE 'Belum di set' END STATUS_S_I_NAMA,
			CASE A.STATUS_S_I WHEN '1' THEN 'aktanikah' WHEN '2' THEN 'aktacerai' ELSE '' END STATUS_S_I_RIWAYAT_FIELD
			, A.SURAT_NIKAH, A.NIK, A.CERAI_SURAT, A.CERAI_TANGGAL, A.CERAI_TMT, A.KEMATIAN_SURAT, A.KEMATIAN_TANGGAL, A.KEMATIAN_TMT
		FROM SUAMI_ISTRI A
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsMonitoringCheckBak($paramsArray=array(),$limit=-1,$from=-1, $statement='', $idrow='',$order=' ORDER BY JENIS_PELAYANAN_ID, NOMOR')
	{
		$str = "
		SELECT
			COALESCE(B.NOMOR, A.NOMOR) NOMOR, COALESCE(B.JENIS_PELAYANAN_ID, A.JENIS_PELAYANAN_ID) JENIS_PELAYANAN_ID
			, COALESCE(B.TIPE, A.TIPE) TIPE, COALESCE(B.NAMA, A.NAMA) NAMA, A.NAMA_FILE, A.NAMA_FILE2
			, COALESCE(B.LINK_FILE, A.LINK_FILE) LINK_FILE
			, B.SURAT_MASUK_PEGAWAI_CHECK_ID, B.INFO_CHECKED
			, JP.NAMA JENIS_PELAYANAN_NAMA
		FROM persuratan.JENIS_PELAYANAN_CHECK A
		INNER JOIN persuratan.JENIS_PELAYANAN JP ON A.JENIS_PELAYANAN_ID = JP.JENIS_PELAYANAN_ID
		LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID = ".$idrow."
		,
		(
			SELECT PANGKAT_RIWAYAT_AKHIR_ID
			FROM persuratan.SURAT_MASUK_PEGAWAI
			WHERE SURAT_MASUK_PEGAWAI_ID = ".$idrow."
		) SP
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
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
				SELECT COUNT(A.SURAT_MASUK_PEGAWAI_CHECK_ID) AS ROWCOUNT 
				FROM SURAT_MASUK_PEGAWAI_CHECK A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
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