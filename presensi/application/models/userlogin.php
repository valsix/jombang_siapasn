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
  include_once("Entity.php");

  class UserLogin extends Entity{ 

	var $query;
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
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","USER_LOGIN"));

		$str = "
				INSERT INTO USER_LOGIN (
				   USER_LOGIN_ID, USER_GROUP_ID, PEGAWAI_ID, 
				   NAMA, JABATAN, EMAIL, 
				   TELEPON, STATUS, USER_LOGIN, 
				   USER_PASS, LAST_CREATE_USER, LAST_CREATE_DATE) 
				VALUES ( '".$this->getField("USER_LOGIN_ID")."', '".$this->getField("USER_GROUP_ID")."', '".$this->getField("PEGAWAI_ID")."',
					'".$this->getField("NAMA")."', '".$this->getField("JABATAN")."', '".$this->getField("EMAIL")."',
					'".$this->getField("TELEPON")."', '".$this->getField("STATUS")."', '".$this->getField("USER_LOGIN")."',
					'".$this->getField("USER_PASS")."', '".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE")." )
				"; 
		$this->id = $this->getField("USER_LOGIN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
				UPDATE USER_LOGIN
				SET    USER_GROUP_ID    = '".$this->getField("USER_GROUP_ID")."',
					   PEGAWAI_ID       = '".$this->getField("PEGAWAI_ID")."',
					   NAMA             = '".$this->getField("NAMA")."',
					   JABATAN          = '".$this->getField("JABATAN")."',
					   EMAIL            = '".$this->getField("EMAIL")."',
					   TELEPON          = '".$this->getField("TELEPON")."',
					   STATUS           = '".$this->getField("STATUS")."',
					   USER_LOGIN       = '".$this->getField("USER_LOGIN")."',
					   USER_PASS        = '".$this->getField("USER_PASS")."',
					   LAST_UPDATE_USER = '".$this->getField("LAST_UPDATE_USER")."',
					   LAST_UPDATE_DATE = ".$this->getField("LAST_UPDATE_DATE")."
				WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'

			 "; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "DELETE FROM USER_LOGIN
                WHERE 
                  USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").""; 
				  
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY USER_LOGIN_ID ASC")
	{
		$str = "
				SELECT 
				USER_LOGIN_ID, A.USER_GROUP_ID, A.PEGAWAI_ID, 
				   B.NAMA, B.JABATAN, A.EMAIL, 
				   A.TELEPON, STATUS, USER_LOGIN, 
				   USER_PASS, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, 
				   A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, C.NAMA NAMA_CABANG, 
                   D.NAMA NAMA_GROUP
				FROM USER_LOGIN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
                LEFT JOIN CABANG C ON C.CABANG_ID = B.CABANG_ID
                LEFT JOIN USER_GROUP D ON D.USER_GROUP_ID = A.USER_GROUP_ID
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

    /*function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT 
			A.USER_LOGIN_ID, A.USER_GROUP_ID, A.LOGIN_USER USER_LOGIN, NULL PEGAWAI_ID
			, B.AKSES_APP_ABSENSI_ID
		FROM user_login A
		LEFT JOIN user_group B ON A.USER_GROUP_ID = B.USER_GROUP_ID 
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
		
    }*/

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
		$this->query = $str;
		//echo $str;exit;
		$str .= $statement." ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    
	function selectByParamsLike($paramsArray=array(),$limit=-1,$from=-1, $statement="")
	{
		$str = "	
				  SELECT USER_LOGIN_ID, NAMA
				  FROM USER_LOGIN                  
				  WHERE 0=0
			    "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->query = $str;
		$str .= $statement." ORDER BY USER_LOGIN_ID ASC";
		return $this->selectLimit($str,$limit,$from); 
    }	
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(A.USER_LOGIN_ID) AS ROWCOUNT 
				FROM USER_LOGIN A
                LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
                LEFT JOIN CABANG C ON C.CABANG_ID = B.CABANG_ID
                LEFT JOIN USER_GROUP D ON D.USER_GROUP_ID = A.USER_GROUP_ID 
		        WHERE 0=0 ".$statement; 
		
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

    function getCountByParamsLike($paramsArray=array(), $statement="")
	{
		$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM USER_LOGIN 
		        WHERE 0=0 ".$statement; 
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key LIKE '%$val%' ";
		}
		
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }	
  } 
?>