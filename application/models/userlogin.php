<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class UserLogin extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UserLogin()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","user_login"));

		$str = "
		INSERT INTO user_login 
		(
			USER_LOGIN_ID, USER_GROUP_ID, STATUS, LOGIN_USER, LOGIN_PASS, LOGIN_LEVEL
			, LAST_USER, LAST_DATE, SATUAN_KERJA_ID, STATUS_MENU_KHUSUS, INFO_SIPETA
		) 
		VALUES
		(
			'".$this->getField("USER_LOGIN_ID")."'
			, ".$this->getField("USER_GROUP_ID")."
			, '".$this->getField("STATUS")."'
			, '".$this->getField("LOGIN_USER")."'
			, '".md5($this->getField("LOGIN_PASS"))."'
			, ".$this->getField("LOGIN_LEVEL")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("SATUAN_KERJA_ID")."
			, ".$this->getField("STATUS_MENU_KHUSUS")."
			, '".$this->getField("INFO_SIPETA")."'
		)
		"; 
		$this->id = $this->getField("USER_LOGIN_ID");
		$this->query = $str;
		// echo $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE user_login
		SET
			USER_GROUP_ID=  ".$this->getField("USER_GROUP_ID")."
			, LOGIN_USER= '".$this->getField("LOGIN_USER")."'
			, LOGIN_LEVEL= ".$this->getField("LOGIN_LEVEL")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID")."
			, STATUS_MENU_KHUSUS= ".$this->getField("STATUS_MENU_KHUSUS")."
			, INFO_SIPETA= '".$this->getField("INFO_SIPETA")."'
		WHERE USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function resetPassword()
	{
		$str = "
		UPDATE user_login
		SET
			LOGIN_PASS= '".$this->getField("LOGIN_PASS")."'
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
		UPDATE user_login
		SET    
			STATUS= ".$this->getField("STATUS")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
		WHERE USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM user_login
        WHERE 
        USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID")."
        ";

		$this->query = $str;
        return $this->execQuery($str);
    }

    function insertfile()
	{
		$str = "
		INSERT INTO pegawai_file_user
		(
			USER_LOGIN_ID, KATEGORI_FILE_ID, RIWAYAT_TABLE, RIWAYAT_ID, RIWAYAT_FIELD, STATUS
			, LAST_USER, LAST_DATE
		) 
		VALUES
		(
			'".$this->getField("USER_LOGIN_ID")."'
			, '".$this->getField("KATEGORI_FILE_ID")."'
			, '".$this->getField("RIWAYAT_TABLE")."'
			, '".$this->getField("RIWAYAT_ID")."'
			, '".$this->getField("RIWAYAT_FIELD")."'
			, '".$this->getField("STATUS")."'
			, '".$this->getField("LAST_USER")."'
			, NOW()
		)
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function deletefile()
	{
        $str = "
        DELETE FROM pegawai_file_user
        WHERE 
        USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID")."
        ";

		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY USER_LOGIN_ID ASC ')
	{
		$str = "
		SELECT 
		A.*
		FROM user_login A
		WHERE 1 = 1
		";

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectfileuser($paramsArray=array(),$limit=-1,$from=-1, $userid, $statement='',$order=' ORDER BY A.URUT')
	{
		$str = "
		SELECT
			B1.STATUS, B2.NAMA KATEGORI_FILE_NAMA
			, A.*
		FROM p_jenisfiledata_pegawai() A
		LEFT JOIN
		(
			SELECT
			CONCAT(CAST(A.KATEGORI_FILE_ID AS TEXT),'-',CAST(A.RIWAYAT_TABLE AS TEXT),'-',CAST(A.RIWAYAT_FIELD AS TEXT),'-',CAST(A.RIWAYAT_ID AS TEXT)) ID_ROW
			, A.*
			FROM pegawai_file_user A
			WHERE USER_LOGIN_ID = ".$userid."
		) B1 ON A.ID_ROW = B1.ID_ROW
		INNER JOIN kategori_file B2 ON A.KATEGORI_FILE_ID = B2.KATEGORI_FILE_ID
		WHERE 1=1
		";

		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=20000,$from=0, $statement='',$order=' ORDER BY A.USER_LOGIN_ID ASC ')
	{
    	/*A.USER_LOGIN_ID, A.USER_GROUP_ID, LOGIN_USER, LOGIN_PASS, LOGIN_LEVEL, 
		CASE WHEN A.STATUS = '1' THEN
			CONCAT('<a onClick=\"hapusData(''',A.USER_LOGIN_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		ELSE
			CONCAT('<a onClick=\"hapusData(''',A.USER_LOGIN_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		END LINK_URL_INFO,
    	CASE LOGIN_LEVEL WHEN 1 THEN 'UPT' WHEN 20 THEN 'Dinas / badan' WHEN 30 THEN 'Teknis - BKD' WHEN 40 THEN 'Sistem' WHEN 99 THEN 'admin - BKD' END LOGIN_LEVEL_INFO,
			A.STATUS, A.LAST_USER, A.LAST_DATE, A.SATUAN_KERJA_ID, C.NAMA USER_GROUP_NAMA, D.NAMA SATUAN_KERJA_NAMA
			, A.STATUS_MENU_KHUSUS*/
		$str = "
		SELECT
		C.NAMA USER_GROUP_NAMA, D.NAMA SATUAN_KERJA_NAMA
		,
		CASE WHEN A.STATUS = '1' THEN
		CONCAT('<a onClick=\"hapusData(''',A.USER_LOGIN_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		ELSE
		CONCAT('<a onClick=\"hapusData(''',A.USER_LOGIN_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		END LINK_URL_INFO,
		CASE A.LOGIN_LEVEL WHEN 1 THEN 'UPT' WHEN 20 THEN 'Dinas / badan' WHEN 30 THEN 'Teknis - BKD' WHEN 40 THEN 'Sistem' WHEN 99 THEN 'admin - BKD' END LOGIN_LEVEL_INFO
		, A.*
        FROM user_login A
        LEFT JOIN user_group C ON A.USER_GROUP_ID = C.USER_GROUP_ID
        LEFT JOIN satuan_kerja D ON A.SATUAN_KERJA_ID = D.SATUAN_KERJA_ID
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
	
	function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT 
		   A.USER_LOGIN_ID, A.USER_GROUP_ID, A.LOGIN_USER, A.LOGIN_LEVEL, B.NAMA USER_GROUP, B.AKSES_APP_SIMPEG_ID, B.AKSES_APP_PERSURATAN_ID
		   , A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
		   , S.SATUAN_KERJA_URUTAN_SURAT, AMBIL_SATKER_NAMA_DYNAMIC(S.SATUAN_KERJA_URUTAN_SURAT) SATUAN_KERJA_URUTAN_SURAT_NAMA
		   , AMBIL_SATKER_JABATAN(S.SATUAN_KERJA_URUTAN_SURAT) SATUAN_KERJA_URUTAN_SURAT_JABATAN
		   , AMBIL_SATKER_JABATAN(A.SATUAN_KERJA_ID) SATUAN_KERJA_LOGIN_KEPALA_JABATAN
		   , S.STATUS_KELOMPOK_PEGAWAI_USUL
		   , UL.NAMA_LENGKAP PEGAWAI_NAMA_LENGKAP, UL.PANGKAT_RIWAYAT_KODE PEGAWAI_PANGKAT_RIWAYAT_KODE
		   , UL.PANGKAT_RIWAYAT_TMT PEGAWAI_PANGKAT_RIWAYAT_TMT, UL.JABATAN_RIWAYAT_NAMA PEGAWAI_JABATAN_RIWAYAT_NAMA
		   , UL.JABATAN_RIWAYAT_ESELON PEGAWAI_JABATAN_RIWAYAT_ESELON, UL.JABATAN_RIWAYAT_TMT PEGAWAI_JABATAN_RIWAYAT_TMT
		   , XX.SATUAN_KERJA_ID SATUAN_KERJA_BKD_ID, UL.PEGAWAI_ID
		   , S.TIPE_ID SATUAN_KERJA_TIPE
		   , CASE WHEN XX.SATUAN_KERJA_ID = AMBIL_SATKER_ID_INDUK(A.SATUAN_KERJA_ID) THEN 1 END STATUS_SATUAN_KERJA_BKD
		   , A.STATUS_MENU_KHUSUS, PF.PATH
		   , B.AKSES_APP_ABSENSI_ID, S.STATUS_KHUSUS_DINAS
		   , B.TAMPIL_RESET, A.INFO_SIPETA
		FROM public.user_login A 
		LEFT JOIN public.user_group B ON A.USER_GROUP_ID = B.USER_GROUP_ID 
		LEFT JOIN SATUAN_KERJA S ON A.SATUAN_KERJA_ID = S.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT A.USER_LOGIN_ID, B.PEGAWAI_ID, B.NAMA_LENGKAP
			, B.PANGKAT_RIWAYAT_KODE, B.PANGKAT_RIWAYAT_TMT, B.JABATAN_RIWAYAT_NAMA, B.JABATAN_RIWAYAT_ESELON, B.JABATAN_RIWAYAT_TMT
			FROM USER_LOGIN_DETIL A
			LEFT JOIN
			(
				SELECT A.PEGAWAI_ID
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
				, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
			) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
			WHERE 1=1
			AND EXISTS
			(
				SELECT 1
				FROM USER_LOGIN_DETIL Y
				WHERE 
				EXISTS
				(
					SELECT 1
					FROM
					(
					SELECT USER_LOGIN_ID, MAX(COALESCE(TANGGAL_AWAL,CURRENT_DATE)) TANGGAL_AWAL, MAX(COALESCE(TANGGAL_AKHIR,CURRENT_DATE)) TANGGAL_AKHIR
					FROM USER_LOGIN_DETIL
					WHERE 1=1
					AND PEGAWAI_ID IS NOT NULL
					GROUP BY USER_LOGIN_ID
					) X
					WHERE X.USER_LOGIN_ID = Y.USER_LOGIN_ID AND X.TANGGAL_AWAL = COALESCE(Y.TANGGAL_AWAL,CURRENT_DATE) AND X.TANGGAL_AKHIR = COALESCE(Y.TANGGAL_AKHIR,CURRENT_DATE)
				)
				AND Y.PEGAWAI_ID IS NOT NULL
				AND Y.USER_LOGIN_ID = A.USER_LOGIN_ID
				AND A.PEGAWAI_ID = Y.PEGAWAI_ID
			)
		) UL ON A.USER_LOGIN_ID = UL.USER_LOGIN_ID
		LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM PEGAWAI_FILE_DATA WHERE RIWAYAT_TABLE = 'PEGAWAI' AND RIWAYAT_ID = 1) PF ON PF.PEGAWAI_ID = UL.PEGAWAI_ID
		,
		(
			SELECT SATUAN_KERJA_ID
			FROM SATUAN_KERJA
			WHERE STATUS_SATUAN_KERJA_BKPP = 1 LIMIT 1
		) XX
		WHERE 1=1 AND A.STATUS = '1'
		AND (SELECT SUM(COALESCE(STATUS_SATUAN_KERJA_BKPP,0)) FROM SATUAN_KERJA) <= 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		//echo $str;exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM user_login A
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT
				FROM user_login A
				LEFT JOIN user_group C ON A.USER_GROUP_ID = C.USER_GROUP_ID
				LEFT JOIN satuan_kerja D ON A.SATUAN_KERJA_ID = D.SATUAN_KERJA_ID
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
  } 
?>