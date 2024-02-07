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
  
  class SuratKeluarBkd extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratKeluarBkd()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_BKD_ID", $this->getNextId("SURAT_KELUAR_BKD_ID","PERSURATAN.SURAT_KELUAR_BKD")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_KELUAR_BKD (
				SURAT_KELUAR_BKD_ID, JENIS_ID, NOMOR, NOMOR_AWAL, NOMOR_URUT, KLASIFIKASI_ID, TANGGAL, PERIHAL, IS_MANUAL
				, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_TUJUAN_NAMA
				, SATUAN_KERJA_TERIMA_SURAT_ID, STATUS_TERIMA, PERIODE
			) 
			VALUES (
				 ".$this->getField("SURAT_KELUAR_BKD_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("NOMOR")."',
				 ".$this->getField("NOMOR_AWAL").",
				 ".$this->getField("NOMOR_URUT").",
				 ".$this->getField("KLASIFIKASI_ID").",
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("IS_MANUAL").",
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."',
				 ".$this->getField("SATUAN_KERJA_TERIMA_SURAT_ID").",
				 ".$this->getField("STATUS_TERIMA").",
				 ".$this->getField("PERIODE")."
			)
		"; 	
		$this->id = $this->getField("SURAT_KELUAR_BKD_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function insertSurat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_BKD_ID", $this->getNextId("SURAT_KELUAR_BKD_ID","PERSURATAN.SURAT_KELUAR_BKD")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_KELUAR_BKD (
				SURAT_KELUAR_BKD_ID, JENIS_ID, NOMOR, NO_AGENDA, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, STATUS_KIRIM
				, IS_MANUAL, SATUAN_KERJA_ASAL_NAMA) 
			VALUES (
				 ".$this->getField("SURAT_KELUAR_BKD_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("NOMOR")."',
				 '".$this->getField("NO_AGENDA")."',
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("KEPADA")."',
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 ".$this->getField("SATUAN_KERJA_ASAL_ID")."
				 , 1
				 , ".$this->getField("IS_MANUAL")."
				 , ".$this->getField("SATUAN_KERJA_ASAL_NAMA")."
			)
		"; 	
		$this->id = $this->getField("SURAT_KELUAR_BKD_ID");
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		$str1= "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD_DISPOSISI
				SET
					SURAT_AWAL= 1, STATUS_POSISI_SURAT = 1
				WHERE  SURAT_KELUAR_BKD_ID = ".$this->id." AND SURAT_KELUAR_BKD_DISPOSISI_PARENT_ID != 0
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		$this->execQuery($str1);
		
		$str1= "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD_DISPOSISI
				SET
					TERBACA= 1
				WHERE  SURAT_KELUAR_BKD_ID = ".$this->id."
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		return $this->execQuery($str1);
    }

    function updateNomorSurat()
	{
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_SURAT_KELUAR= 2
				WHERE SURAT_MASUK_PEGAWAI_ID = (SELECT SURAT_MASUK_PEGAWAI_ID FROM PERSURATAN.SURAT_KELUAR_BKD WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID").")
				"; 
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					NOMOR= '".$this->getField("NOMOR")."',
				 	NOMOR_AWAL= ".$this->getField("NOMOR_AWAL").",
					NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
					TANDA_TANGAN_BKD_ID= ".$this->getField("TANDA_TANGAN_BKD_ID").",
				 	TANGGAL= ".$this->getField("TANGGAL").",
					STATUS_KIRIM_TEKNIS = 1
				WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateNomorDataSurat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					NOMOR= '".$this->getField("NOMOR")."',
				 	NOMOR_AWAL= ".$this->getField("NOMOR_AWAL").",
					NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
					TANDA_TANGAN_BKD_ID= ".$this->getField("TANDA_TANGAN_BKD_ID").",
				 	TANGGAL= ".$this->getField("TANGGAL")."
				WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function updateNomorDataSurat2()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					NOMOR= '".$this->getField("NOMOR")."',
				 	NOMOR_AWAL= ".$this->getField("NOMOR_AWAL").",
					NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
					TANDA_TANGAN_BKD_ID= ".$this->getField("TANDA_TANGAN_BKD_ID").",
				 	TANGGAL= ".$this->getField("TANGGAL").",
				 	STATUS_KIRIM_TEKNIS= '2'
				WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateBatal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.USULAN_SURAT
				SET STATUS_KIRIM = NULL
				WHERE USULAN_SURAT_ID IN (SELECT USULAN_SURAT_ID FROM PERSURATAN.SURAT_KELUAR_BKD WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID").")
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					STATUS_TERIMA= ".$this->getField("STATUS_TERIMA").",
					NOMOR= '".$this->getField("NOMOR")."',
					NOMOR_AWAL= ".$this->getField("NOMOR_AWAL").",
					NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
					KLASIFIKASI_ID= ".$this->getField("KLASIFIKASI_ID").",
					TANGGAL= ".$this->getField("TANGGAL").",
					PERIHAL= '".$this->getField("PERIHAL")."',
					IS_MANUAL= ".$this->getField("IS_MANUAL").",
					SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
					SATUAN_KERJA_TUJUAN_NAMA= '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."'
				WHERE SURAT_KELUAR_BKD_ID= ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM")."
				WHERE  SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusTerima()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					STATUS_TERIMA= ".$this->getField("STATUS_TERIMA")."
				WHERE  SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updatePerihalKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM").",
					PERIHAL= '".$this->getField("PERIHAL")."'
				WHERE  SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM persuratan.SURAT_KELUAR_BKD
				WHERE SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."
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
    function selectByParamsTahun($statement='',$order="ORDER BY TO_CHAR(TANGGAL, 'YYYY')")
	{
		$str = "
		SELECT TO_CHAR(TANGGAL, 'YYYY') TAHUN 
		FROM persuratan.SURAT_KELUAR_BKD A 
		WHERE 1=1 
		"; 
		
		$str .= $statement." GROUP BY TO_CHAR(TANGGAL, 'YYYY') ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsUptDinasTahun($statement='',$order="ORDER BY CAST(TO_CHAR(COALESCE(SMU.TANGGAL, SMB.TANGGAL), 'YYYY') AS NUMERIC)")
	{
		$str = "
		SELECT TO_CHAR(COALESCE(SMU.TANGGAL, SMB.TANGGAL), 'YYYY') TAHUN
		FROM persuratan.SURAT_MASUK_PEGAWAI SMP
		LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		WHERE 1=1
		"; 
		
		$str .= $statement." GROUP BY TO_CHAR(COALESCE(SMU.TANGGAL, SMB.TANGGAL), 'YYYY') ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from);
    }
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_BKD_ID ASC')
	{
		$str = "
		SELECT
			A.SURAT_KELUAR_BKD_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID
			, A.SURAT_MASUK_PEGAWAI_ID, A.NOMOR
			, A.NOMOR_AWAL, A.NOMOR_URUT
			, A.TANGGAL, A.KEPADA, A.PERIHAL, A.IS_MANUAL, A.SATUAN_KERJA_TUJUAN_ID
			, A.SATUAN_KERJA_TUJUAN_NAMA, A.SATUAN_KERJA_TEKNIS_ID, A.SATUAN_KERJA_TERIMA_SURAT_ID
			, A.STATUS_TERIMA, A.STATUS_KIRIM_TEKNIS, A.TANDA_TANGAN_BKD_ID, A.PERIODE
			, CASE A.STATUS_TERIMA WHEN '1' THEN 'Di setujui' ELSE 'Belum di setujui' END STATUS_TERIMA_NAMA
		FROM persuratan.SURAT_KELUAR_BKD A
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_BKD_ID ASC')
	{
		$str = "
		SELECT
			A.SURAT_KELUAR_BKD_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, 
			A.SURAT_MASUK_PEGAWAI_ID, A.PERIODE, SUBSTR(A.PERIODE,1,2) PERIODE_BULAN, SUBSTR(A.PERIODE,3,4) PERIODE_TAHUN
			, CASE WHEN COALESCE(NULLIF(A.NOMOR,'') , NULL ) IS NULL THEN CONCAT(B.KODE,'/        /', TTD.NO_NOMENKLATUR_KAB,'.', TTD.NO_NOMENKLATUR_BKD,'/',TO_CHAR(CURRENT_DATE, 'YYYY')) ELSE A.NOMOR END NOMOR
			, A.NOMOR_AWAL, A.NOMOR_URUT, A.KLASIFIKASI_ID, B.KODE KLASIFIKASI_KODE, B.NAMA KLASIFIKASI_NAMA,
			A.TANGGAL, A.KEPADA, A.PERIHAL, A.IS_MANUAL, A.SATUAN_KERJA_TUJUAN_ID, 
			A.SATUAN_KERJA_TUJUAN_NAMA, A.SATUAN_KERJA_TEKNIS_ID, A.SATUAN_KERJA_TERIMA_SURAT_ID, 
			A.STATUS_TERIMA, A.STATUS_KIRIM_TEKNIS, A.TANDA_TANGAN_BKD_ID
			, SMP.NIP_BARU, SMP.NAMA_LENGKAP, SMP.SATUAN_KERJA_LENGKAP
		FROM persuratan.SURAT_KELUAR_BKD A
		LEFT JOIN persuratan.KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
		LEFT JOIN
		(
			SELECT
			SMP.SURAT_MASUK_PEGAWAI_ID,
			(CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, A.NIP_BARU, AMBIL_SATKER_NAMA_DYNAMIC(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_LENGKAP
			FROM PEGAWAI A
			INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
			WHERE 1=1
		) SMP ON A.SURAT_MASUK_PEGAWAI_ID = SMP.SURAT_MASUK_PEGAWAI_ID
		, (
		SELECT 
		TANDA_TANGAN_BKD_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NO_NOMENKLATUR_KAB, 
		NO_NOMENKLATUR_BKD, NAMA, PLT_JABATAN, NAMA_PEJABAT, PANGKAT_ID, 
		KODE_PANGKAT, PANGKAT, NIP, PEJABAT_PENETAP
		FROM TANDA_TANGAN_BKD A
		WHERE 1 = 1
		AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(CURRENT_DATE))
		) TTD
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_BKD_ID ASC')
	{
		$str = "
		SELECT
			A.SURAT_KELUAR_BKD_ID, A.TANGGAL, B.KODE KLASIFIKASI_KODE, A.NOMOR_AWAL, A.NOMOR_URUT, A.PERIHAL
			, COALESCE(A.SATUAN_KERJA_TUJUAN_NAMA, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TEKNIS_ID)) SATUAN_KERJA_TUJUAN_NAMA
			, A.NOMOR
		FROM persuratan.SURAT_KELUAR_BKD A
		LEFT JOIN persuratan.KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
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
	
	function selectByParamsCetakPengantarSatuOrang($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT 
					A.NAMA, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, A.NIP_BARU
					, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA
					, SMU.SATUAN_KERJA_ASAL_ID
					, SMU.NOMOR, SMU.TANGGAL, SMU.KEPADA, AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM
					, AMBIL_SATKER_JABATAN(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM_KEPALA, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_DETIL
					, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA, PEN_RIW.JURUSAN JURUSAN
					, PEN_US_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, PUS.JUMLAH_USULAN_PEGAWAI
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_KELUAR_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN persuratan.SURAT_KELUAR_BKD SMU ON SMU.SURAT_KELUAR_BKD_ID = SMP.SURAT_KELUAR_BKD_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT 	
						A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
						, CASE A.STATUS_PENDIDIKAN
						WHEN '1' THEN 'Pendidikan CPNS'
						WHEN '2' THEN 'Diakui'
						WHEN '3' THEN 'Belum Diakui'
						WHEN '4' THEN 'Riwayat'
						WHEN '5' THEN 'Ijin belajar'
						WHEN '6' THEN 'Tugas Belajar'
						ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_RIW ON SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID = PEN_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT 	
						A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
						, CASE A.STATUS_PENDIDIKAN
						WHEN '1' THEN 'Pendidikan CPNS'
						WHEN '2' THEN 'Diakui'
						WHEN '3' THEN 'Belum Diakui'
						WHEN '4' THEN 'Riwayat'
						WHEN '5' THEN 'Ijin belajar'
						WHEN '6' THEN 'Tugas Belajar'
						ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH, A.NAMA_FAKULTAS, A.TEMPAT
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_US_RIW ON SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID = PEN_US_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN
				(
					SELECT SMP.SURAT_KELUAR_BKD_ID, SMP.JENIS_ID, COUNT(SMP.PEGAWAI_ID) JUMLAH_USULAN_PEGAWAI
					FROM persuratan.SURAT_KELUAR_PEGAWAI SMP
					WHERE 1 = 1
					GROUP BY SMP.SURAT_KELUAR_BKD_ID, SMP.JENIS_ID
				) PUS ON SMP.SURAT_KELUAR_BKD_ID = PUS.SURAT_KELUAR_BKD_ID
				WHERE 1 = 1
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
	
	function selectByParamsSurat($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.SURAT_KELUAR_BKD_ID ASC')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_KELUAR_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
			SELECT 
				A.SURAT_KELUAR_BKD_ID, A.JENIS_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, 
				A.TANGGAL_BATAS, A.KEPADA, A.PERIHAL
				, A.SATUAN_KERJA_ASAL_ID
				, CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_ASAL_NAMA
				, A.SATUAN_KERJA_TUJUAN_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_NAMA
				, persuratan.AMBIL_SATKER_POSISI_SURAT(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) POSISI_SURAT
				, 
				CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) IS NULL 
				THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
				ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) END POSISI_SURAT_BACA
				, persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) POSISI_SURAT_BACABAK
				, persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) POSISI_ID_SURAT
				,
				CASE WHEN PD.POSISI_TEKNIS = 1 THEN
					AMBIL_SATKER_NAMA(PD.SATUAN_KERJA_ASAL_ID)
				ELSE
					CASE WHEN A.JENIS_ID IS NULL THEN 
						CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) IS NULL 
						THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
						ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) END
					ELSE PBKD.INFO_PROSES END
				END POSISI_TERAKHIR
				, 
				CASE WHEN A.JENIS_ID IS NULL THEN 
					CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) IS NULL 
					THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
					ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID) END
				ELSE PBKD.INFO_PROSES END POSISI_TERAKHIRBAK1
			FROM persuratan.SURAT_KELUAR_BKD A
			INNER JOIN
			(
				SELECT SURAT_KELUAR_BKD_ID
				FROM persuratan.SURAT_KELUAR_BKD_DISPOSISI
				WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
				".$statementdisposisi."
				GROUP BY SURAT_KELUAR_BKD_ID
			) B ON A.SURAT_KELUAR_BKD_ID = B.SURAT_KELUAR_BKD_ID
			LEFT JOIN ( 
				SELECT SURAT_KELUAR_BKD_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS FROM persuratan.SURAT_KELUAR_BKD_DISPOSISI
			) PD ON PD.SURAT_KELUAR_BKD_DISPOSISI_ID = persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.SURAT_KELUAR_BKD_ID, A.JENIS_ID)
			LEFT JOIN 
			(
				SELECT
				A.JENIS_ID, A.SURAT_KELUAR_BKD_ID
				, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
				FROM persuratan.SURAT_KELUAR_PEGAWAI_LOG A
				INNER JOIN
				(
				SELECT SURAT_KELUAR_BKD_ID, JENIS_ID, MAX(LAST_DATE) LAST_DATE
				FROM persuratan.SURAT_KELUAR_PEGAWAI_LOG
				WHERE SURAT_KELUAR_BKD_ID IS NOT NULL
				GROUP BY SURAT_KELUAR_BKD_ID, JENIS_ID
				) B ON A.JENIS_ID = B.JENIS_ID AND A.SURAT_KELUAR_BKD_ID = B.SURAT_KELUAR_BKD_ID AND A.LAST_DATE = B.LAST_DATE
				GROUP BY A.JENIS_ID, A.SURAT_KELUAR_BKD_ID
				, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID))
			) PBKD ON A.JENIS_ID = PBKD.JENIS_ID AND A.SURAT_KELUAR_BKD_ID = PBKD.SURAT_KELUAR_BKD_ID
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

	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_KELUAR_BKD A
				LEFT JOIN persuratan.KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
				WHERE 1=1 ".$statement; 
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
	
    function getCountByParamsSurat($paramsArray=array(), $satuankerjaid="", $jenisid="", $statement='')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_KELUAR_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_KELUAR_BKD A
				INNER JOIN
				(
					SELECT SURAT_KELUAR_BKD_ID
					FROM persuratan.SURAT_KELUAR_BKD_DISPOSISI
					WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
					".$statementdisposisi."
					GROUP BY SURAT_KELUAR_BKD_ID
				) B ON A.SURAT_KELUAR_BKD_ID = B.SURAT_KELUAR_BKD_ID
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
	
	function getCountByParamsData($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM persuratan.SURAT_KELUAR_BKD A
		WHERE 1=1 ".$statement; 
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM persuratan.SURAT_KELUAR_BKD A
		LEFT JOIN persuratan.KLASIFIKASI B ON A.KLASIFIKASI_ID = B.KLASIFIKASI_ID
		WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsNomorAwalPerTahun($statement='')
	{
		$str = "
			SELECT
				COALESCE(MAX(NOMOR_AWAL) + 1, 1) AS ROWCOUNT 
			FROM persuratan.SURAT_KELUAR_BKD A 
			WHERE 1=1
			".$statement; 
			
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function getCountByParamsTanggalPerTahun($statement='')
	{
		$str = "
			SELECT
				MAX(TANGGAL) AS ROWCOUNT 
			FROM persuratan.SURAT_KELUAR_BKD A 
			WHERE 1=1
			".$statement; 
			
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function getCountByParamsSatuanKerjaIdSurat($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT SATUAN_KERJA_ID AS ROWCOUNT 
				FROM SATUAN_KERJA A
				WHERE 1 = 1 AND A.STATUS_KELOMPOK_PEGAWAI_USUL = 1 AND SATUAN_KERJA_PARENT_ID = 0 ".$statement; 
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
	
	function selectByParamsPangkatTurunInfo($pangkatid="", $tanggalawal="", $tanggalakhir="")
	{
		$str= "
		SELECT 
		GETPANGKATTURUN('".$pangkatid."') PANGKAT_ID,
		AMBIL_UMUR_DUK(".$tanggalawal.", ".$tanggalakhir.") SELISIH
		"; 
		//echo $str;exit;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
		
    }
	
	function getCountByParamsGajiBerlaku($periode, $masakerja, $pangkatid)
	{
		$str= "SELECT AMBIL_GAJI_BERLAKU_TGL('".substr($periode,4,4)."' || '-' || '".substr($periode,2,2)."' || '-' || '".substr($periode,0,2)."', ".$masakerja.", ".$pangkatid.") AS ROWCOUNT "; 
		$this->select($str);
		//echo $str;exit;
		$this->query = $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
  } 
?>