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
  
  class PendidikanRiwayat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PendidikanRiwayat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENDIDIKAN_RIWAYAT_ID", $this->getNextId("PENDIDIKAN_RIWAYAT_ID","PENDIDIKAN_RIWAYAT"));
     	$str = "
     	INSERT INTO PENDIDIKAN_RIWAYAT 
     	(
	     	PENDIDIKAN_RIWAYAT_ID, PEGAWAI_ID, PENDIDIKAN_ID, PENDIDIKAN_JURUSAN_ID, NAMA, TEMPAT
	     	, KEPALA, NO_STTB, TANGGAL_STTB, JURUSAN, NO_SURAT_IJIN, TANGGAL_SURAT_IJIN, STATUS_PENDIDIKAN, GELAR_TIPE
	     	, GELAR_DEPAN, GELAR_NAMA, STATUS_TUGAS_IJIN_BELAJAR, PPPK_STATUS
	     	, STATUS_SK_DASAR_PENGAKUAN, CANTUM_GELAR_TANGGAL, CANTUM_GELAR_NO_SK
	     	, DASAR_PANGKAT_RIWAYAT_ID, NILAI_REKAM_JEJAK, RUMPUN_ID
	     	, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
     	) 
     	VALUES 
     	(
	     	".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
	     	, ".$this->getField("PEGAWAI_ID")."
	     	, ".$this->getField("PENDIDIKAN_ID")."
	     	, ".$this->getField("PENDIDIKAN_JURUSAN_ID")."
	     	, '".$this->getField("NAMA")."'
	     	, '".$this->getField("TEMPAT")."'
	     	, '".$this->getField("KEPALA")."'
	     	, '".$this->getField("NO_STTB")."'
	     	, ".$this->getField("TANGGAL_STTB")."
	     	, '".$this->getField("JURUSAN")."'
	     	, '".$this->getField("NO_SURAT_IJIN")."'
	     	, ".$this->getField("TANGGAL_SURAT_IJIN")."
	     	, '".$this->getField("STATUS_PENDIDIKAN")."'
	     	, '".$this->getField("GELAR_TIPE")."'
	     	, '".$this->getField("GELAR_DEPAN")."'
	     	, '".$this->getField("GELAR_NAMA")."'
	     	, ".$this->getField("STATUS_TUGAS_IJIN_BELAJAR")."
	     	, ".$this->getField("PPPK_STATUS")."
	     	, ".$this->getField("STATUS_SK_DASAR_PENGAKUAN")."
	     	, ".$this->getField("CANTUM_GELAR_TANGGAL")."
	     	, '".$this->getField("CANTUM_GELAR_NO_SK")."'
	     	, ".$this->getField("DASAR_PANGKAT_RIWAYAT_ID")."
	     	, ".$this->getField("NILAI_REKAM_JEJAK")."
	     	, ".$this->getField("RUMPUN_ID")."
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, ".$this->getField("LAST_LEVEL")."
	     	, ".$this->getField("USER_LOGIN_ID")."
	     	, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
     	)
		"; 	
		$this->id = $this->getField("PENDIDIKAN_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE PENDIDIKAN_RIWAYAT
		SET  
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
			, PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
			, PENDIDIKAN_JURUSAN_ID= ".$this->getField("PENDIDIKAN_JURUSAN_ID")."
			, NAMA= '".$this->getField("NAMA")."'
			, TEMPAT= '".$this->getField("TEMPAT")."'
			, KEPALA= '".$this->getField("KEPALA")."'
			, NO_STTB= '".$this->getField("NO_STTB")."'
			, TANGGAL_STTB= ".$this->getField("TANGGAL_STTB")."
			, JURUSAN= '".$this->getField("JURUSAN")."'
			, NO_SURAT_IJIN= '".$this->getField("NO_SURAT_IJIN")."'
			, TANGGAL_SURAT_IJIN= ".$this->getField("TANGGAL_SURAT_IJIN")."
			, STATUS_PENDIDIKAN= '".$this->getField("STATUS_PENDIDIKAN")."'
			, STATUS_TUGAS_IJIN_BELAJAR= ".$this->getField("STATUS_TUGAS_IJIN_BELAJAR")."
			, PPPK_STATUS= ".$this->getField("PPPK_STATUS")."
			, STATUS_SK_DASAR_PENGAKUAN= ".$this->getField("STATUS_SK_DASAR_PENGAKUAN")."
	     	, CANTUM_GELAR_TANGGAL= ".$this->getField("CANTUM_GELAR_TANGGAL")."
	     	, CANTUM_GELAR_NO_SK= '".$this->getField("CANTUM_GELAR_NO_SK")."'
	     	, DASAR_PANGKAT_RIWAYAT_ID= ".$this->getField("DASAR_PANGKAT_RIWAYAT_ID")."
	     	, NILAI_REKAM_JEJAK= ".$this->getField("NILAI_REKAM_JEJAK")."
	     	, RUMPUN_ID= '".$this->getField("RUMPUN_ID")."'
			, GELAR_TIPE= '".$this->getField("GELAR_TIPE")."'
			, GELAR_DEPAN= '".$this->getField("GELAR_DEPAN")."'
			, GELAR_NAMA= '".$this->getField("GELAR_NAMA")."'
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE  PENDIDIKAN_RIWAYAT_ID = ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function insertDataBkn()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENDIDIKAN_RIWAYAT_ID", $this->getNextId("PENDIDIKAN_RIWAYAT_ID","PENDIDIKAN_RIWAYAT"));
     	$str = "
     	INSERT INTO PENDIDIKAN_RIWAYAT 
     	(
	     	PENDIDIKAN_RIWAYAT_ID, PEGAWAI_ID,PENDIDIKAN_ID,PENDIDIKAN_JURUSAN_ID,NAMA,NO_STTB,TANGGAL_STTB,JURUSAN,GELAR_DEPAN,GELAR_NAMA,STATUS_PENDIDIKAN
     	) 
     	VALUES 
     	(
     	".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
     	, ".$this->getField("PEGAWAI_ID")."
     	, ".$this->getField("PENDIDIKAN_ID")."
     	, ".$this->getField("PENDIDIKAN_JURUSAN_ID")."
     	, '".$this->getField("NAMA")."'
     	, '".$this->getField("NO_STTB")."'
     	, ".$this->getField("TANGGAL_STTB")."
     	, '".$this->getField("JURUSAN")."'
     	, '".$this->getField("GELAR_DEPAN")."'
     	, '".$this->getField("GELAR_NAMA")."'
     	, '".$this->getField("STATUS_PENDIDIKAN")."'
	     	
     	)
		"; 	
		$this->id = $this->getField("PENDIDIKAN_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateBknData()
	{
		$str = "		
		UPDATE PENDIDIKAN_RIWAYAT
		SET  
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
			, PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
			, PENDIDIKAN_JURUSAN_ID= ".$this->getField("PENDIDIKAN_JURUSAN_ID")."
			, NAMA= '".$this->getField("NAMA")."'
			, NO_STTB= '".$this->getField("NO_STTB")."'
			, TANGGAL_STTB= ".$this->getField("TANGGAL_STTB")."
			, JURUSAN= '".$this->getField("JURUSAN")."'
			, GELAR_DEPAN= '".$this->getField("GELAR_DEPAN")."'
			, GELAR_NAMA= '".$this->getField("GELAR_NAMA")."'
			, STATUS_PENDIDIKAN= '".$this->getField("STATUS_PENDIDIKAN")."'
			
		WHERE  PENDIDIKAN_RIWAYAT_ID = ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function updateIdSapk()
    {
		$str = "		
		UPDATE PENDIDIKAN_RIWAYAT
		SET    
		
		 	 ID_SAPK= '".$this->getField("ID_SAPK")."'
	
		WHERE  PENDIDIKAN_RIWAYAT_ID = ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }
	
    function updateStatus()
	{
		$str = "		
		UPDATE PENDIDIKAN_RIWAYAT
		SET    
			STATUS= ".$this->getField("STATUS")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			, LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE PENDIDIKAN_RIWAYAT_ID= ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
        UPDATE PENDIDIKAN_RIWAYAT SET
	        STATUS = '1'
	        , LAST_USER= '".$this->getField("LAST_USER")."'
	        , USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."'
	        , USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
	        , LAST_DATE= ".$this->getField("LAST_DATE")."
        WHERE PENDIDIKAN_RIWAYAT_ID = ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
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
    function selectByParamsPegawaiCariKenaikanPangkatReguler($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, PR.PANGKAT_ID, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT, PR.MASA_KERJA_TAHUN PANGKAT_TH, PR.MASA_KERJA_BULAN PANGKAT_BL
			, A.JABATAN_RIWAYAT_ID, JR.ESELON_NAMA JABATAN_ESELON, JR.NAMA JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PENR.PENDIDIKAN_JURUSAN_NAMA, PENR.TANGGAL_STTB, PENR.STATUS_PENDIDIKAN_NAMA, PENR.PENDIDIKAN_ID
			, CASE WHEN JR.JENIS_JABATAN_ID = '3' THEN COALESCE(A.JABATAN_TAMBAHAN_ID,-1) ELSE NULL END JABATAN_TAMBAHAN_ID
		FROM PEGAWAI A
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID
		INNER JOIN
		(
			SELECT A.*, B.NAMA ESELON_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JR ON JR.JABATAN_RIWAYAT_ID = A.JABATAN_RIWAYAT_ID
		INNER JOIN 
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, C.NAMA || ' - ' || B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.TANGGAL_STTB, A.PENDIDIKAN_ID
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
		) PENR ON PENR.PENDIDIKAN_RIWAYAT_ID = A.PENDIDIKAN_RIWAYAT_ID
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANGGAL_STTB ASC')
	{
		$str = "
		SELECT
			B.NAMA PENDIDIKAN_JURUSAN_NAMA, C.NAMA PENDIDIKAN_NAMA
			, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
			, CASE A.STATUS_PENDIDIKAN
			WHEN '1' THEN 'Pendidikan CPNS'
			WHEN '2' THEN 'Diakui'
			WHEN '3' THEN 'Belum Diakui'
			WHEN '4' THEN 'Riwayat'
			WHEN '5' THEN 'Ijin belajar'
			WHEN '6' THEN 'Tugas Belajar'
			ELSE '-' END STATUS_PENDIDIKAN_NAMA
			, CASE A.STATUS_TUGAS_IJIN_BELAJAR WHEN 1 THEN 'Ijin Belajar' WHEN 2 THEN 'Tugas Belajar' END STATUS_TUGAS_IJIN_BELAJAR_NAMA
			, A.*
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
	
	function selectByParamsPegawaiCari($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.JABATAN_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
			, A.SATUAN_KERJA_ID
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, UST.PENDIDIKAN_ID, UST.PENDIDIKAN_NAMA, UST.STATUS_PENDIDIKAN_NAMA, UST.JURUSAN, UST.NAMA_SEKOLAH
		FROM PEGAWAI A
		LEFT JOIN 
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA, A.STATUS_PENDIDIKAN
				, CASE A.STATUS_PENDIDIKAN
				WHEN '1' THEN 'Pendidikan CPNS'
				WHEN '2' THEN 'Diakui'
				WHEN '3' THEN 'Belum Diakui'
				WHEN '4' THEN 'Riwayat'
				WHEN '5' THEN 'Ijin belajar'
				WHEN '6' THEN 'Tugas Belajar'
				ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.JURUSAN, A.NAMA NAMA_SEKOLAH
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
			AND (A.STATUS IS NULL OR A.STATUS = '2')
		) UST ON A.PENDIDIKAN_RIWAYAT_ID = UST.PENDIDIKAN_RIWAYAT_ID
		INNER JOIN PANGKAT_RIWAYAT PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID AND (COALESCE(NULLIF(PR.STATUS, ''), NULL) IS NULL OR PR.STATUS = '2')
		LEFT JOIN PEGAWAI_FILE_SCAN_ASLI_PANGKAT_RIWAYAT_ID PSCANPANG ON PSCANPANG.PEGAWAI_ID = A.PEGAWAI_ID AND PSCANPANG.RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID
		LEFT JOIN PEGAWAI_FILE_SCAN_ASLI_PENDIDIKAN_RIWAYAT PSCANPEND ON PSCANPEND.PEGAWAI_ID = A.PEGAWAI_ID
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
	
	function selectByParamsPegawaiCariKarpeg($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		 // AND (COALESCE(NULLIF(PR.STATUS, ''), NULL) IS NULL OR PR.STATUS = '2')
		 // AND (COALESCE(NULLIF(JR.STATUS, ''), NULL) IS NULL OR JR.STATUS = '2')
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, A.JABATAN_RIWAYAT_ID, JR.NAMA JABATAN_NAMA
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PR.PANGKAT_KODE, A.KARTU_PEGAWAI
		FROM PEGAWAI A
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID
		INNER JOIN JABATAN_RIWAYAT JR ON JR.JABATAN_RIWAYAT_ID = A.JABATAN_RIWAYAT_ID
		WHERE 1 = 1
		AND A.STATUS_PEGAWAI_ID IN (1,2,6)
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

    function selectByParamsPegawaiCariKaris($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.*
		, SI.SUAMI_ISTRI_ID, SI.SUAMI_ISTRI_NAMA, SI.SUAMI_ISTRI_TANGGAL_LAHIR, SI.SUAMI_ISTRI_TANGGAL_KAWIN
		, SI.SUAMI_ISTRI_PERTAMA_PNS_STATUS, SI.SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA, SI.SUAMI_ISTRI_PERTAMA_PNS_TANGGAL
		, SI.SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I, SI.SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA
		, SI.SUAMI_ISTRI_PISAH_ID
		FROM
		(
			SELECT
				A.PEGAWAI_ID, A.NIP_BARU, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
				, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
				, A.JABATAN_RIWAYAT_ID, JR.ESELON_NAMA JABATAN_ESELON, JR.JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT
			FROM PEGAWAI A
			INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_ID
			INNER JOIN
			(
				SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
				FROM JABATAN_RIWAYAT A
				LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				WHERE (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			) JR ON JR.JABATAN_RIWAYAT_ID = A.JABATAN_RIWAYAT_ID
			WHERE 1 = 1
			AND A.STATUS_PEGAWAI_ID IN (1,2,6)
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order."
		) A
		INNER JOIN
		(
			SELECT A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.NAMA SUAMI_ISTRI_NAMA, A.TANGGAL_LAHIR SUAMI_ISTRI_TANGGAL_LAHIR, A.TANGGAL_KAWIN SUAMI_ISTRI_TANGGAL_KAWIN
			, CASE WHEN COALESCE(SUAMI_ISTRI_PERTAMA_PNS_JUMLAH,0) > 0 THEN 1 ELSE 0 END SUAMI_ISTRI_PERTAMA_PNS_STATUS
			, CASE WHEN COALESCE(SUAMI_ISTRI_PERTAMA_PNS_JUMLAH,0) > 0 THEN 'Tidak' ELSE 'Ya' END SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA
			, SUAMI_ISTRI_PERTAMA_PNS_TANGGAL, SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I, SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA
			, SUAMI_ISTRI_PISAH_ID
			FROM SUAMI_ISTRI A
			LEFT JOIN
			(
				SELECT
					A.SUAMI_ISTRI_ID SUAMI_ISTRI_PISAH_ID, A.PEGAWAI_ID, 1 SUAMI_ISTRI_PERTAMA_PNS_JUMLAH, A.STATUS_S_I SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I
					, CASE A.STATUS_S_I WHEN '2' THEN A.CERAI_TMT WHEN '3' THEN A.KEMATIAN_TMT END SUAMI_ISTRI_PERTAMA_PNS_TANGGAL
					, CASE A.STATUS_S_I WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' END SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA
				FROM SUAMI_ISTRI A
				INNER JOIN
				(
					SELECT
					A.PEGAWAI_ID, MAX(A.TANGGAL_KAWIN) TANGGAL_KAWIN
					FROM SUAMI_ISTRI A
					WHERE 1=1
					AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
					AND STATUS_S_I NOT IN ('1')
					GROUP BY A.PEGAWAI_ID
				) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.TANGGAL_KAWIN = B.TANGGAL_KAWIN
				WHERE 1=1
				AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
				AND STATUS_S_I NOT IN ('1')
			) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
			WHERE 1=1
			AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			AND STATUS_S_I = '1' AND STATUS_AKTIF = '1'
		) SI ON A.PEGAWAI_ID = SI.PEGAWAI_ID
		WHERE 1=1
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsPegawaiCariPensiun($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY P.PEGAWAI_ID ASC')
	{
		// , SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 1) PENSIUN_THBAK
		// , SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 2) PENSIUN_BLBAK
		$str = "
		SELECT
			P.PEGAWAI_ID, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' 'END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE ' '|| A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT, PR.MASA_KERJA_TAHUN PANGKAT_TH, PR.MASA_KERJA_BULAN PANGKAT_BL
			, P.TMT PENSIUN_TMT
			, CASE WHEN (COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) % 12 = 0
			THEN
			(COALESCE(CAST(PR.MASA_KERJA_TAHUN AS NUMERIC),0) + FLOOR((COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) / 12))
			ELSE
			(COALESCE(CAST(PR.MASA_KERJA_TAHUN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 1) AS NUMERIC),0))
			END
			PENSIUN_TH
			, CASE WHEN (COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) % 12 = 0 
			THEN 0
			ELSE
			(COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0))
			END PENSIUN_BL
			, P.TANGGAL_KEMATIAN PENSIUN_TANGGAL_KEMATIAN, P.NOMOR_SK PENSIUN_NOMOR_SK, P.TANGGAL_SK_KEMATIAN PENSIUN_TANGGAL_SK_KEMATIAN, P.KETERANGAN PENSIUN_KETERANGAN
			, JR.ESELON_NAMA JABATAN_ESELON, JR.JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
			, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, A.GAJI_RIWAYAT_ID, PR.PANGKAT_RIWAYAT_ID, P.SATUAN_KERJA_ID
		FROM PENSIUN P
		INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = P.PEGAWAI_ID
		--INNER JOIN PANGKAT_RIWAYAT_PENSIUN_DATA PR ON PR.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = A.PANGKAT_RIWAYAT_PENSIUN_ID
		INNER JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JR ON JR.JABATAN_RIWAYAT_ID = P.JABATAN_RIWAYAT_ID
		WHERE 1 = 1
		"; 
		// INNER JOIN SK_CPNS SKC ON SKC.PEGAWAI_ID = P.PEGAWAI_ID
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PENDIDIKAN_RIWAYAT_ID) AS ROWCOUNT 
				FROM PENDIDIKAN_RIWAYAT A
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