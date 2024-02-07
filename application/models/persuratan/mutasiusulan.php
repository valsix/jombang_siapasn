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
  
  class MutasiUsulan extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function MutasiUsulan()
	{
      $this->Entity(); 
    }

    function insertPejabat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEJABAT_PENETAP_ID", $this->getNextId("PEJABAT_PENETAP_ID","PEJABAT_PENETAP")); 

     	$str = "
			INSERT INTO PEJABAT_PENETAP (
				PEJABAT_PENETAP_ID, NAMA, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				   ".$this->getField("PEJABAT_PENETAP_ID").",
			      '".$this->getField("NAMA")."',
			      '".$this->getField("LAST_USER")."',
			       ".$this->getField("LAST_DATE")." ,
			      '".$this->getField("USER_LOGIN_ID")."',
			      ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEJABAT_PENETAP_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function selectByParamsEselon($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ESELON_ID ASC ')
	{
		$str = "
				SELECT A.ESELON_ID, A.NAMA, A.PANGKAT_MINIMAL, A.PANGKAT_MAKSIMAL, 
				CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
				FROM ESELON A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT
		A.ID_VAL, A.ROW_ID_VAL, A.PEGAWAI_ID, A.SATKER_ID, AMBIL_SATKER_NAMA_DETIL(A.SATKER_ID) SATKER_NAMA, A.SATKER_ASAL_ID, AMBIL_SATKER_NAMA_DETIL(A.SATKER_ASAL_ID) SATKER_ASAL_NAMA, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, A.TMT
		, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
		, P.NIP_LAMA, P.NIP_BARU
		, CASE WHEN A.JENIS_MUTASI_ID = 1 THEN 'Mutasi Struktural / Pelaksana<br/>' WHEN A.JENIS_MUTASI_ID = 2 THEN 'Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana<br/>' END ||
		CASE WHEN A.JENIS_JABATAN_TUGAS_ID = 11 THEN 'Jabatan Struktural' WHEN A.JENIS_JABATAN_TUGAS_ID = 12 THEN 'Pelaksana' WHEN A.JENIS_JABATAN_TUGAS_ID = 21 THEN 'JFT Pendidikan' WHEN A.JENIS_JABATAN_TUGAS_ID = 22 THEN 'JFT Kesehatan' WHEN A.JENIS_JABATAN_TUGAS_ID = 29 THEN 'Mutasi Intern Pelaksana' END
		JENIS_MUTASI_JABATAN
		, CASE WHEN COALESCE(NULLIF(A.STATUS_USULAN, ''), NULL) IS NULL THEN 'Proses Usul' WHEN A.STATUS_USULAN = '1' THEN 'Di tolak' WHEN A.STATUS_USULAN = '2' THEN 'Selesai' END STATUS_INFO
		, CASE WHEN A.JENIS_JABATAN_TUGAS_ID = 11 THEN 'mutasi_usulan_add_struktural' WHEN A.JENIS_JABATAN_TUGAS_ID = 12 THEN 'mutasi_usulan_add_fungsional' WHEN A.JENIS_JABATAN_TUGAS_ID = 21 THEN 'mutasi_usulan_add_jft_pendidikan' WHEN A.JENIS_JABATAN_TUGAS_ID = 22 THEN 'mutasi_usulan_add_jft_kesehatan' WHEN A.JENIS_JABATAN_TUGAS_ID = 29 THEN 'mutasi_usulan_add_intern_pelaksana' END INFO_URL
		FROM
		(
			SELECT 
			A.US_JABATAN_RIWAYAT_ID ID_VAL, A.JABATAN_RIWAYAT_ID ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, A.TMT_JABATAN TMT
			FROM persuratan.US_JABATAN_RIWAYAT A
			UNION ALL
			SELECT 
			A.US_JABATAN_TAMBAHAN_ID ID_VAL, A.JABATAN_TAMBAHAN_ID ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, A.TMT_JABATAN TMT
			FROM persuratan.US_JABATAN_TAMBAHAN A
			UNION ALL
			SELECT 
			A.US_JABATAN_MUTASI_INTERN_ID ID_VAL, NULL ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, NULL TMT
			FROM persuratan.US_JABATAN_MUTASI_INTERN A
		) A
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
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

    function selectByParamsPegawaiCari($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
		FROM PEGAWAI A
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
	
    function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT 
			A.US_JABATAN_RIWAYAT_ID ID_VAL, A.JABATAN_RIWAYAT_ID ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, A.TMT_JABATAN TMT
			FROM persuratan.US_JABATAN_RIWAYAT A
			UNION ALL
			SELECT 
			A.US_JABATAN_TAMBAHAN_ID ID_VAL, A.JABATAN_TAMBAHAN_ID ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, A.TMT_JABATAN TMT
			FROM persuratan.US_JABATAN_TAMBAHAN A
			UNION ALL
			SELECT 
			A.US_JABATAN_MUTASI_INTERN_ID ID_VAL, NULL ROW_ID_VAL
			, A.PEGAWAI_ID, A.SATKER_ID, A.SATKER_ASAL_ID, A.JENIS_MUTASI_ID, A.JENIS_JABATAN_TUGAS_ID, A.STATUS_USULAN, NULL TMT
			FROM persuratan.US_JABATAN_MUTASI_INTERN A
		) A
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
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

    function getSatuanKerja($id='')
	{
		$str = "SELECT REPLACE(REPLACE(CAST(AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$id.") AS TEXT), '{',''), '}','') ROWCOUNT";
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow())
		{
			if($this->getField("ROWCOUNT") == "")
			return $id;
			else
			return $id.",".$this->getField("ROWCOUNT"); 
		}
		else 
			return $id;  
    }
	
  } 
?>