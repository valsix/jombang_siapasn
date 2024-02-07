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
		// echo $str;exit;
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
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsUsulan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=' ORDER BY B.JENIS_PELAYANAN_ID, A.PEGAWAI_ID, B.NOMOR')
	{
		$str = "
		SELECT
			B.KATEGORI_FILE_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID,
			B.JENIS_PELAYANAN_ID, B.TIPE, B.KATEGORI, B.LINK_FILE, B.STATUS_INFORMASI
			, B.INFORMASI_TABLE, B.INFORMASI_FIELD
			, A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID
			, P.NIP_BARU, P.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN, SK.NOMOR, SK.TANGGAL
			, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, P.NAMA NAMA_PEGAWAI
			, CASE
			WHEN A.JENIS_ID = 7 THEN
				CASE
				WHEN SMB.TANGGAL >= PENSIUN_TMT AND TO_CHAR(PENSIUN_TMT, 'MM') = '01'
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				WHEN SMB.TANGGAL >= PENSIUN_TMT
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				WHEN SMB.TANGGAL < PENSIUN_TMT AND TO_CHAR(SMB.TANGGAL, 'YYYY') = TO_CHAR(PENSIUN_TMT, 'YYYY')
				THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				ELSE 
					CASE WHEN TO_CHAR(SMB.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
					ELSE
					CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
					END
				END
			WHEN A.JENIS_ID = 10 THEN CAST(CAST(TO_CHAR(SMB.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 1 AS TEXT)
			ELSE TO_CHAR(SK.TANGGAL, 'YYYY')
			END TAHUN_SURAT
			, B.NAMA NAMA_CHECK, B.NOMOR NOMOR_CHECK
			, US.ID_SEMENTARA
			, A.JABATAN_RIWAYAT_AKHIR_ID JABATAN_RIWAYAT_ID
			, A.KP_SURAT_TANDA_LULUS_ID, A.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
			, A.KP_STATUS_SURAT_TANDA_LULUS, A.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI
			, A.KP_PEGAWAI_FILE_ID, A.KP_DIKLAT_ID, A.KP_DIKLAT_STRUKTURAL_ID
			, A.KP_PAK_LAMA_ID, A.KP_PAK_BARU_ID, A.KP_STATUS_SERTIFIKAT_KEASLIAN
			, A.KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
			, A.KP_STATUS_SERTIFIKAT_PENDIDIK, A.KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
			, A.JABATAN_TAMBAHAN_AKHIR_ID JABATAN_TAMBAHAN_ID
		FROM persuratan.SURAT_MASUK_PEGAWAI A
		LEFT JOIN 
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_BKD A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMB ON A.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SK ON SK.USULAN_SURAT_ID = A.USULAN_SURAT_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID PENSIUN_PEGAWAI_ID
			, CASE WHEN CAST(TO_CHAR(TMT, 'MM') AS INTEGER) >= 4 THEN CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) ELSE CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) - 1 END JENIS_PENSIUN_TAHUN
			, TMT PENSIUN_TMT
			FROM PENSIUN
		) DP ON A.PEGAWAI_ID = DP.PENSIUN_PEGAWAI_ID
		LEFT JOIN persuratan.usulan_surat US ON A.USULAN_SURAT_ID = US.USULAN_SURAT_ID
		WHERE 1=1
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsUsulanGroupNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=' ORDER BY B.JENIS_PELAYANAN_ID, A.PEGAWAI_ID, B.NOMOR')
	{
		$str = "
		SELECT
			DISTINCT
			A.SURAT_MASUK_PEGAWAI_ID, A.PEGAWAI_ID, P.NIP_BARU, B.KATEGORI, A.KP_PAK_LAMA_ID, A.KP_PAK_BARU_ID
			, P.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN, P.NAMA NAMA_PEGAWAI
			, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, SK.NOMOR SURAT_KELUAR_NOMOR, SK.TANGGAL SURAT_KELUAR_TANGGAL
			, US.ID_SEMENTARA SEMENTARA_NOMOR, US.TANGGAL_DIBUAT SEMENTARA_TANGGAL
			, CASE
			WHEN A.JENIS_ID = 7 THEN
				CASE
				WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(PENSIUN_TMT, 'MM') = '01'
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT)
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				WHEN (SMB.TANGGAL < PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(SMB.TANGGAL, 'YYYY') = TO_CHAR(PENSIUN_TMT, 'YYYY')
				THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				ELSE 
					CASE
					WHEN TO_CHAR(SMB.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
					WHEN TO_CHAR(SMU.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
					ELSE
						CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN 
							CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						ELSE
							CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						END
					END
				END
			WHEN A.JENIS_ID = 10 THEN
				CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN CAST(CAST(TO_CHAR(SMB.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				ELSE CAST(CAST(TO_CHAR(SMU.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				END
			WHEN A.JENIS_ID = 1 THEN
				CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				ELSE CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				END
			WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN TO_CHAR(SMB.TANGGAL, 'YYYY')
			ELSE TO_CHAR(SMU.TANGGAL, 'YYYY')
			END TAHUN_SURAT
		FROM persuratan.SURAT_MASUK_PEGAWAI A
		LEFT JOIN 
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_BKD A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMB ON A.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		LEFT JOIN
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_UPT A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMU ON A.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SK ON SK.USULAN_SURAT_ID = A.USULAN_SURAT_ID
		LEFT JOIN persuratan.usulan_surat US ON A.USULAN_SURAT_ID = US.USULAN_SURAT_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID PENSIUN_PEGAWAI_ID
			, CASE WHEN CAST(TO_CHAR(TMT, 'MM') AS INTEGER) >= 4 THEN CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) ELSE CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) - 1 END JENIS_PENSIUN_TAHUN
			, TMT PENSIUN_TMT
			FROM PENSIUN
		) DP ON A.PEGAWAI_ID = DP.PENSIUN_PEGAWAI_ID
		WHERE 1=1
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
	}

	function selectByParamsUsulanNew($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order=' ORDER BY B.JENIS_PELAYANAN_ID, A.PEGAWAI_ID, B.NOMOR')
	{
		$str = "
		SELECT
			A.SURAT_MASUK_PEGAWAI_ID, A.PEGAWAI_ID, P.NIP_BARU
			, P.JENIS_KELAMIN PEGAWAI_JENIS_KELAMIN, P.NAMA NAMA_PEGAWAI
			, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, B.SURAT_MASUK_PEGAWAI_CHECK_ID, B.KATEGORI_FILE_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID, B.NOMOR
			, B.JENIS_PELAYANAN_ID, B.TIPE, B.INFORMASI_TABLE, B.INFORMASI_FIELD, B.KATEGORI
			, A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.GAJI_RIWAYAT_AKHIR_ID GAJI_RIWAYAT_ID
			, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID
			, A.JABATAN_RIWAYAT_AKHIR_ID JABATAN_RIWAYAT_ID, A.KP_PAK_LAMA_ID, A.KP_PAK_BARU_ID
			, SK.NOMOR SURAT_KELUAR_NOMOR, SK.TANGGAL SURAT_KELUAR_TANGGAL
			, US.ID_SEMENTARA SEMENTARA_NOMOR, US.TANGGAL_DIBUAT SEMENTARA_TANGGAL
			, CASE
			WHEN A.JENIS_ID = 7 THEN
				CASE
				WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(PENSIUN_TMT, 'MM') = '01'
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT)
				THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				WHEN (SMB.TANGGAL < PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(SMB.TANGGAL, 'YYYY') = TO_CHAR(PENSIUN_TMT, 'YYYY')
				THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				ELSE 
					CASE
					WHEN TO_CHAR(SMB.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
					WHEN TO_CHAR(SMU.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
					ELSE
						CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN 
							CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						ELSE
							CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						END
					END
				END
			WHEN A.JENIS_ID = 10 THEN
				CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN CAST(CAST(TO_CHAR(SMB.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				ELSE CAST(CAST(TO_CHAR(SMU.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 0 AS TEXT)
				END
			WHEN A.JENIS_ID = 1 THEN
				CASE WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				ELSE CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
				END
			WHEN A.SURAT_MASUK_BKD_ID IS NOT NULL THEN TO_CHAR(SMB.TANGGAL, 'YYYY')
			ELSE TO_CHAR(SMU.TANGGAL, 'YYYY')
			END TAHUN_SURAT
		FROM persuratan.SURAT_MASUK_PEGAWAI A
		LEFT JOIN 
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_BKD A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMB ON A.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		LEFT JOIN
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_UPT A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMU ON A.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SK ON SK.USULAN_SURAT_ID = A.USULAN_SURAT_ID
		LEFT JOIN persuratan.usulan_surat US ON A.USULAN_SURAT_ID = US.USULAN_SURAT_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID PENSIUN_PEGAWAI_ID
			, CASE WHEN CAST(TO_CHAR(TMT, 'MM') AS INTEGER) >= 4 THEN CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) ELSE CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) - 1 END JENIS_PENSIUN_TAHUN
			, TMT PENSIUN_TMT
			FROM PENSIUN
		) DP ON A.PEGAWAI_ID = DP.PENSIUN_PEGAWAI_ID
		WHERE 1=1
		"; 

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
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
			, SP.PEGAWAI_ID, SP.PANGKAT_RIWAYAT_ID, SP.JABATAN_RIWAYAT_ID, SP.JABATAN_TAMBAHAN_ID
			, SP.PENDIDIKAN_RIWAYAT_ID, SP.GAJI_RIWAYAT_ID
			, SP.JENIS_ID, SP.SURAT_MASUK_BKD_ID, SP.SURAT_MASUK_UPT_ID
			, COALESCE(B.KATEGORI_FILE_ID, A.KATEGORI_FILE_ID) KATEGORI_FILE_ID
			, SP.KP_SURAT_TANDA_LULUS_ID, SP.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
			, SP.KP_STATUS_SURAT_TANDA_LULUS, SP.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI
			, SP.KP_PEGAWAI_FILE_ID, SP.KP_DIKLAT_ID, SP.KP_DIKLAT_STRUKTURAL_ID
			, SP.KP_STATUS_STRUKTURAL_ID, SP.KP_STATUS_STRUKTURAL_NAMA
			, SP.KP_PAK_LAMA_ID, SP.KP_PAK_BARU_ID, SP.KP_STATUS_SERTIFIKAT_KEASLIAN
			, SP.KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
			, SP.KP_STATUS_SERTIFIKAT_PENDIDIK, SP.KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
		FROM persuratan.JENIS_PELAYANAN_CHECK A
		INNER JOIN persuratan.JENIS_PELAYANAN JP ON A.JENIS_PELAYANAN_ID = JP.JENIS_PELAYANAN_ID
		LEFT JOIN persuratan.SURAT_MASUK_PEGAWAI_CHECK B ON A.NOMOR = B.NOMOR AND A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID AND A.TIPE = B.TIPE AND B.SURAT_MASUK_PEGAWAI_ID = ".$idrow.$statementdetil."
		,
		(
			SELECT
			A.PANGKAT_RIWAYAT_AKHIR_ID PANGKAT_RIWAYAT_ID, A.JABATAN_RIWAYAT_AKHIR_ID JABATAN_RIWAYAT_ID
			, A.JABATAN_TAMBAHAN_AKHIR_ID JABATAN_TAMBAHAN_ID, A.GAJI_RIWAYAT_AKHIR_ID GAJI_RIWAYAT_ID
			, A.PENDIDIKAN_RIWAYAT_AKHIR_ID PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID
			, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID
			, A.KP_SURAT_TANDA_LULUS_ID, A.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
			, A.KP_STATUS_SURAT_TANDA_LULUS, A.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI
			, A.KP_PEGAWAI_FILE_ID, A.KP_DIKLAT_ID, A.KP_DIKLAT_STRUKTURAL_ID
			, CASE
			WHEN A.KP_PANGKAT_ID = 41 AND A.KP_STATUS_SURAT_TANDA_LULUS = 1 THEN 'STLUD'
			WHEN A.KP_PANGKAT_ID = 41 AND A.KP_DIKLAT_ID = 1 THEN 'Diklatpim'
			ELSE '' END KP_STATUS_STRUKTURAL_NAMA
			, CASE
			WHEN A.KP_PANGKAT_ID = 41 AND A.KP_STATUS_SURAT_TANDA_LULUS = 1 THEN '1'
			WHEN A.KP_PANGKAT_ID = 41 AND A.KP_DIKLAT_ID = 1 THEN '2'
			ELSE '' END KP_STATUS_STRUKTURAL_ID
			, KP_PAK_LAMA_ID, KP_PAK_BARU_ID, KP_STATUS_SERTIFIKAT_KEASLIAN, KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
			, KP_STATUS_SERTIFIKAT_PENDIDIK, KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
			FROM persuratan.SURAT_MASUK_PEGAWAI A
			WHERE SURAT_MASUK_PEGAWAI_ID = ".$idrow."
		) SP
		WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
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
		
		foreach ($paramsArray as $key => $val)
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
				, SPLIT_PART(A.PATH, '/', 3) PATH_NAMA
				FROM PEGAWAI_FILE A
				LEFT JOIN KUALITAS_FILE B ON A.FILE_KUALITAS_ID = B.KUALITAS_FILE_ID
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

    function selectByParamsDownload($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE DESC')
	{
		$str = "
		SELECT 
			A.PATH
			, CASE WHEN RF.FORMAT_BKN = '_' || P_NIP THEN REPLACE(RF.FORMAT_BKN, '_' || P_NIP, '')
			ELSE RF.FORMAT_BKN END FORMAT_BKN
		FROM PEGAWAI_FILE A
		INNER JOIN RIWAYAT_FILE RF ON A.KATEGORI_FILE_ID = RF.NO_URUT AND A.PEGAWAI_ID = RF.PEGAWAI_ID AND A.RIWAYAT_TABLE = RF.RIWAYAT_TABLE 
		AND CASE WHEN COALESCE(NULLIF(A.RIWAYAT_FIELD, ''), NULL) IS NULL THEN '' ELSE A.RIWAYAT_FIELD END =
		CASE WHEN COALESCE(NULLIF(RF.RIWAYAT_FIELD, ''), NULL) IS NULL THEN '' ELSE RF.RIWAYAT_FIELD END
		AND COALESCE(NULLIF(CAST(A.RIWAYAT_ID AS TEXT), ''), NULL) = COALESCE(NULLIF(RF.RIWAYAT_ID, ''), NULL)
		INNER JOIN (SELECT PEGAWAI_ID P_ID, NIP_BARU P_NIP FROM PEGAWAI) P ON A.PEGAWAI_ID = P_ID
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

    function selectByParamsPak($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PAK_ID ASC')
	{
		$str = "
				SELECT 
					A.PAK_ID, A.PEGAWAI_ID, A.NO_SK, A.TANGGAL_SK, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
				   	A.PERIODE_AWAL, A.PERIODE_AKHIR, A.PAK_AWAL, A.JABATAN_FT_ID, A.KREDIT_UTAMA, 
				   	A.KREDIT_PENUNJANG, A.TOTAL_KREDIT, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS,
				   	B.NAMA JABATAN
				FROM PAK A
				LEFT JOIN JABATAN_FT B ON A.JABATAN_FT_ID = B.JABATAN_FT_ID
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
	
	function selectByParamsPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PANGKAT_RIWAYAT_ID ASC')
	{
		$str = "
				SELECT 	
					A.PANGKAT_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.STLUD, A.NO_STLUD, A.TANGGAL_STLUD, A.NO_NOTA
					, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK, A.TMT_PANGKAT, A.KREDIT, A.JENIS_RIWAYAT, A.KETERANGAN, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
					, A.GAJI_POKOK, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
					, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
					, CASE A.JENIS_RIWAYAT WHEN 1 THEN 'skcpns' WHEN 2 THEN 'skpns' ELSE '' END JENIS_FIELD
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
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsJabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
			SELECT
				A.JABATAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.TIPE_PEGAWAI_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, 
				CASE WHEN AMBIL_SATKER_INDUK(A.SATKER_ID) IS NULL THEN A.NAMA ELSE A.NAMA || ' - ' || AMBIL_SATKER_INDUK(A.SATKER_ID) END JABATAN_INFO,
				A.ESELON_ID, B.NAMA ESELON_NAMA, A.NO_SK, A.TANGGAL_SK, A.TMT_JABATAN, A.NAMA, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.KREDIT, A.SATKER_ID
				, 
				CASE 
				WHEN A.SATKER_ID IS NULL THEN
				A.SATKER_NAMA 
				ELSE AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) 
				END SATUAN_KERJA_NAMA_DETILbak
				, 
				CASE 
				WHEN A.SATKER_ID IS NULL THEN
				A.SATKER_NAMA 
				ELSE AMBIL_SATKER_NAMA_DETIL(A.SATKER_ID) 
				END SATUAN_KERJA_NAMA_DETIL
				, A.JENIS_JABATAN_ID, CASE A.JENIS_JABATAN_ID WHEN '1' THEN 'Jabatan Struktural' WHEN '2' THEN 'Jabatan Fungsional Umum' WHEN '3' THEN 'Jabatan Fungsional Tertentu' END JENIS_JABATAN_NAMA
				, A.IS_MANUAL, A.BULAN_DIBAYAR, A.TMT_BATAS_USIA_PENSIUN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				, COALESCE(HK.HUKUMAN_ID,0) DATA_HUKUMAN
				, A.TMT_ESELON
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			LEFT JOIN HUKUMAN HK ON A.JABATAN_RIWAYAT_ID = HK.JABATAN_RIWAYAT_ID
			WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsTugas($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
			SELECT
				A.JABATAN_TAMBAHAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NO_SK, A.TANGGAL_SK
				, A.TMT_JABATAN, A.TMT_JABATAN_AKHIR, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.BULAN_DIBAYAR
				, A.NAMA, A.TUGAS_TAMBAHAN_ID, A.IS_MANUAL
				, CASE WHEN COALESCE(NULLIF(A.SATKER_NAMA, ''), NULL) IS NULL THEN AMBIL_SATKER_NAMA(A.SATKER_ID) ELSE A.SATKER_NAMA END SATKER_NAMA, A.SATKER_ID, A.STATUS_PLT, A.STATUS,
				A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			FROM JABATAN_TAMBAHAN A
			WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
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
		
		foreach ($paramsArray as $key => $val)
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
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsSuratTandaLulus($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_TANDA_LULUS_ID ASC')
	{
		$str = "
		SELECT 	
			A.SURAT_TANDA_LULUS_ID, A.PEGAWAI_ID, A.JENIS_ID, A.NO_STLUD, A.TANGGAL_STLUD
			, A.TANGGAL_MULAI, A.TANGGAL_AKHIR, A.NILAI_NPR, A.NILAI_NT, A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID
			, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, CASE A.JENIS_ID 
			WHEN 1 THEN 'STL Ujian Dinas I' 
			WHEN 2 THEN 'STL Ujian Dinas II' 
			WHEN 3 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah SMA'
			WHEN 4 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah D-4/S-1'
			WHEN 5 THEN 'STL Ujian Kenaikan Pangkat Penyesuaian Ijazah S-2'
			ELSE '' END JENIS_NAMA
			, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
			, A.LAST_USER, A.LAST_DATE
		FROM SURAT_TANDA_LULUS A
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
	
	function selectByParamsDiklatStruktural($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DIKLAT_STRUKTURAL_ID ASC')
	{
		$str = "
		SELECT 
			A.DIKLAT_STRUKTURAL_ID, A.DIKLAT_ID, A.PEGAWAI_ID, A.TEMPAT, A.PENYELENGGARA, A.ANGKATAN, A.TAHUN, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
			A.NO_STTPP, A.TANGGAL_STTPP, A.JUMLAH_JAM, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS, B.NAMA
		FROM DIKLAT_STRUKTURAL A
		JOIN DIKLAT B ON A.DIKLAT_ID = B.DIKLAT_ID
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

	function selectByParamsPenilaianSkp($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENILAIAN_SKP_ID ASC')
	{
		$str = "
				SELECT 	
					A.PENILAIAN_SKP_ID, A.PEGAWAI_ID, CAST(A.TAHUN AS TEXT) TAHUN
					, A.PRESTASI_HASIL
				FROM PENILAIAN_SKP A
				WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
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
				WHERE REPLACE(P.JENIS, 'pensiun', '') = '".$kategori."'
			) P
			INNER JOIN ANAK A ON P.ANAK_ID = A.ANAK_ID
		) B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
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
		
		foreach ($paramsArray as $key => $val)
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
				SELECT COUNT(A.SURAT_MASUK_PEGAWAI_CHECK_ID) AS ROWCOUNT 
				FROM SURAT_MASUK_PEGAWAI_CHECK A
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