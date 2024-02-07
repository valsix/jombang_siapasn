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
  
  class KenaikanPangkatHitung extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function KenaikanPangkatHitung()
	{
      $this->Entity(); 
    }
	
	/** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
	function proseshitung()
	{
        $str = "
        SELECT PROSESKENAIKANPANGKAT('".$this->getField("PERIODE")."', NULL, ".$this->getField("USER_LOGIN_ID").")
		"; 
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }
	
	function selectByParamsInfoTotal($statement='')
	{
		$str = "
		SELECT
		(
			SELECT COUNT(1) 
			FROM 
			(
				SELECT 
				A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
				, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
				, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
				FROM KENAIKAN_PANGKAT_HITUNG A
				INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
			) KP
			INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
			WHERE 1=1 ".$statement."
		) JUMLAH_DATA
		, 
		(
			SELECT COUNT(1) 
			FROM 
			(
				SELECT 
				A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
				, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
				, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
				FROM KENAIKAN_PANGKAT_HITUNG A
				INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
			) KP
			INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
			WHERE 1=1 AND KP.KP_JENIS_RIWAYAT = 72 ".$statement." 
		) JUMLAH_DATA_UD
		, 
		(
			SELECT COUNT(1) 
			FROM 
			(
				SELECT 
				A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
				, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
				, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
				FROM KENAIKAN_PANGKAT_HITUNG A
				INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
			) KP
			INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
			WHERE 1=1 AND KP.KP_JENIS_RIWAYAT = 71 ".$statement." 
		) JUMLAH_DATA_PI
		, 
		(
			SELECT COUNT(1) 
			FROM 
			(
				SELECT 
				A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
				, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
				, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
				FROM KENAIKAN_PANGKAT_HITUNG A
				INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
			) KP
			INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
			WHERE 1=1 AND KP.KP_STATUS IS NULL ".$statement." 
		) JUMLAH_DATA_TIDAK
		"; 
		
		// $str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT
		A.NIP_BARU, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
		, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
		, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
		, KP.KP_PANGKAT_KODE, KP.KP_TMT KP_TMT_PANGKAT, KP_STATUS_NAMA, KP_JENIS_RIWAYAT_NAMA
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
			, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
			, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
			FROM KENAIKAN_PANGKAT_HITUNG A
			INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
		) KP
		INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JAB_RIW ON KP.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
			WHERE 1=1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		) PANG_RIW ON KP.TMT_PANGKAT = PANG_RIW.TMT_PANGKAT AND KP.PEGAWAI_ID = PANG_RIW.PEGAWAI_ID
		WHERE 1=1
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.TIPE_PEGAWAI_ID, A.TMT_PANGKAT, A.PENDIDIKAN_ID, A.KP_TMT, A.KP_PANGKAT_ID, B.KODE KP_PANGKAT_KODE
			, A.KP_STATUS, CASE A.KP_STATUS WHEN 1 THEN 'Memenuhi Syarat' WHEN 2 THEN 'Pangkat Maksimal' WHEN 3 THEN 'Belum UD' END KP_STATUS_NAMA
			, A.KP_JENIS_RIWAYAT, CASE A.KP_JENIS_RIWAYAT WHEN 4 THEN 'Reguler' WHEN 72 THEN 'Pilihan Ud' END KP_JENIS_RIWAYAT_NAMA
			FROM KENAIKAN_PANGKAT_HITUNG A
			INNER JOIN PANGKAT B ON A.KP_PANGKAT_ID = B.PANGKAT_ID
		) KP
		INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = KP.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JAB_RIW ON KP.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
			WHERE 1=1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		) PANG_RIW ON KP.TMT_PANGKAT = PANG_RIW.TMT_PANGKAT AND KP.PEGAWAI_ID = PANG_RIW.PEGAWAI_ID
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