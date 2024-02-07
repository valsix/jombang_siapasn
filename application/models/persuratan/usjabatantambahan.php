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
  
  class UsJabatanTambahan extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UsJabatanTambahan()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("US_JABATAN_TAMBAHAN_ID", $this->getNextId("US_JABATAN_TAMBAHAN_ID","persuratan.US_JABATAN_TAMBAHAN")); 
     	$str = "
			INSERT INTO persuratan.US_JABATAN_TAMBAHAN (
			US_JABATAN_TAMBAHAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_JABATAN, TMT_JABATAN_AKHIR, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, BULAN_DIBAYAR
			, NAMA, TUGAS_TAMBAHAN_ID, IS_MANUAL, SATKER_NAMA, SATKER_ID, STATUS_PLT
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			, IS_DPK_MANUAL, SATKER_DPK_NAMA
			, SATKER_ASAL_ID, JENIS_MUTASI_ID, JENIS_JABATAN_TUGAS_ID
			, LAST_USER_CREATE, LAST_DATE_CREATE, USER_LOGIN_ID_CREATE, USER_LOGIN_PEGAWAI_ID_CREATE
			, STATUS_USULAN, JABATAN_TAMBAHAN_ID
			)
			VALUES (
				".$this->getField("US_JABATAN_TAMBAHAN_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("PEJABAT_PENETAP_ID").",
				'".$this->getField("PEJABAT_PENETAP")."',
				'".$this->getField("NO_SK")."',
				".$this->getField("TANGGAL_SK").",
				".$this->getField("TMT_JABATAN").",
				".$this->getField("TMT_JABATAN_AKHIR").",
				'".$this->getField("NO_PELANTIKAN")."',
				".$this->getField("TANGGAL_PELANTIKAN").",
				".$this->getField("TUNJANGAN").",
				".$this->getField("BULAN_DIBAYAR").",
				'".$this->getField("NAMA")."',
				".$this->getField("TUGAS_TAMBAHAN_ID").",
				".$this->getField("IS_MANUAL").",
				'".$this->getField("SATKER_NAMA")."',
				".$this->getField("SATKER_ID").",
				'".$this->getField("STATUS_PLT")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				".$this->getField("IS_DPK_MANUAL").",
				'".$this->getField("SATKER_DPK_NAMA")."',
			 	".$this->getField("SATKER_ASAL_ID").",
			 	".$this->getField("JENIS_MUTASI_ID").",
			 	".$this->getField("JENIS_JABATAN_TUGAS_ID").",
			 	'".$this->getField("LAST_USER_CREATE")."',
			 	".$this->getField("LAST_DATE_CREATE").",
			 	".$this->getField("USER_LOGIN_ID_CREATE").",
			 	".$this->getField("USER_LOGIN_PEGAWAI_ID_CREATE").",
				".$this->getField("STATUS_USULAN").",
				".$this->getField("JABATAN_TAMBAHAN_ID")."
			)
		"; 	
		$this->id = $this->getField("US_JABATAN_TAMBAHAN_ID");
		$this->query = $str;
		// echo $str;exit;

		if($this->getField("JABATAN_TAMBAHAN_ID") == "")
		{
			return $this->execQuery($str);
		}
		else
		{
			$this->execQuery($str);
		}

		if($this->getField("JABATAN_TAMBAHAN_ID") == ""){}
		else
		{
			// pindah satuan kerja tujuan
			$str0= "		
					UPDATE PEGAWAI
					SET
						SATUAN_KERJA_ID = (SELECT SATKER_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
					WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
					"; 
			return $this->query = $str0;
		}

    }

    function insertUtama()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_TAMBAHAN_ID", $this->getNextId("JABATAN_TAMBAHAN_ID","JABATAN_TAMBAHAN")); 

     	$str = "
			INSERT INTO JABATAN_TAMBAHAN (
			JABATAN_TAMBAHAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_JABATAN, TMT_JABATAN_AKHIR, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, BULAN_DIBAYAR
			, NAMA, TUGAS_TAMBAHAN_ID, IS_MANUAL, SATKER_NAMA, SATKER_ID, STATUS_PLT
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			)
			VALUES (
				".$this->getField("JABATAN_TAMBAHAN_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("PEJABAT_PENETAP_ID").",
				'".$this->getField("PEJABAT_PENETAP")."',
				'".$this->getField("NO_SK")."',
				".$this->getField("TANGGAL_SK").",
				".$this->getField("TMT_JABATAN").",
				".$this->getField("TMT_JABATAN_AKHIR").",
				'".$this->getField("NO_PELANTIKAN")."',
				".$this->getField("TANGGAL_PELANTIKAN").",
				".$this->getField("TUNJANGAN").",
				".$this->getField("BULAN_DIBAYAR").",
				'".$this->getField("NAMA")."',
				".$this->getField("TUGAS_TAMBAHAN_ID").",
				".$this->getField("IS_MANUAL").",
				'".$this->getField("SATKER_NAMA")."',
				".$this->getField("SATKER_ID").",
				'".$this->getField("STATUS_PLT")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("JABATAN_TAMBAHAN_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.US_JABATAN_TAMBAHAN
				SET    
					PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
					PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
					NO_SK= '".$this->getField("NO_SK")."',
					TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
					TMT_JABATAN= ".$this->getField("TMT_JABATAN").",
					TMT_JABATAN_AKHIR= ".$this->getField("TMT_JABATAN_AKHIR").",
					NO_PELANTIKAN= '".$this->getField("NO_PELANTIKAN")."',
					TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN").",
					TUNJANGAN= ".$this->getField("TUNJANGAN").",
					BULAN_DIBAYAR= ".$this->getField("BULAN_DIBAYAR").",
					NAMA= '".$this->getField("NAMA")."',
					TUGAS_TAMBAHAN_ID= ".$this->getField("TUGAS_TAMBAHAN_ID").",
					IS_MANUAL= ".$this->getField("IS_MANUAL").",
					SATKER_NAMA= '".$this->getField("SATKER_NAMA")."',
					SATKER_ID= ".$this->getField("SATKER_ID").",
					STATUS_PLT= '".$this->getField("STATUS_PLT")."',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					IS_DPK_MANUAL= ".$this->getField("IS_DPK_MANUAL").",
					SATKER_DPK_NAMA= '".$this->getField("SATKER_DPK_NAMA")."'
				WHERE  US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function rejectdata()
	{
		$str= "		
		UPDATE persuratan.US_JABATAN_TAMBAHAN
		SET    
			ALASAN_TOLAK= '".$this->getField("ALASAN_TOLAK")."'
			, STATUS_USULAN= ".$this->getField("STATUS")."
			, STATUS= ".$this->getField("STATUS")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE US_JABATAN_TAMBAHAN_ID= ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function validData()
	{
		// pindah satuan kerja tujuan
		$str0= "		
				UPDATE PEGAWAI
				SET
					SATUAN_KERJA_ID = (SELECT SATKER_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
				WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
				"; 
		$this->query = $str0;
		$this->execQuery($str0);

		// update data utama kl ada
		$this->setField("JABATAN_TAMBAHAN_ID", $this->getNextId("JABATAN_TAMBAHAN_ID","JABATAN_TAMBAHAN")); 

     	$str1= "
			INSERT INTO JABATAN_TAMBAHAN (
			JABATAN_TAMBAHAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_JABATAN, TMT_JABATAN_AKHIR, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, BULAN_DIBAYAR
			, NAMA, TUGAS_TAMBAHAN_ID, IS_MANUAL, SATKER_NAMA, SATKER_ID, STATUS_PLT
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			)
			SELECT
			".$this->getField("JABATAN_TAMBAHAN_ID")." JABATAN_TAMBAHAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_JABATAN, TMT_JABATAN_AKHIR, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, BULAN_DIBAYAR
			, NAMA, TUGAS_TAMBAHAN_ID, IS_MANUAL, SATKER_NAMA, SATKER_ID, STATUS_PLT
			, '".$this->getField("LAST_USER")."' LAST_USER, ".$this->getField("LAST_DATE")." LAST_DATE, ".$this->getField("LAST_LEVEL")." LAST_LEVEL, ".$this->getField("USER_LOGIN_ID")." USER_LOGIN_ID, ".$this->getField("USER_LOGIN_PEGAWAI_ID")." USER_LOGIN_PEGAWAI_ID
			FROM persuratan.US_JABATAN_TAMBAHAN
			WHERE US_JABATAN_TAMBAHAN_ID= ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
		"; 	
		// $this->id = $this->getField("JABATAN_TAMBAHAN_ID");
		$this->query = $str1;
		// echo $str1;exit;
		$this->execQuery($str1);

		$str= "		
				UPDATE persuratan.US_JABATAN_TAMBAHAN
				SET    
					   JABATAN_TAMBAHAN_ID= ".$this->getField("JABATAN_TAMBAHAN_ID").",
					   STATUS_USULAN= ".$this->getField("STATUS").",
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_TAMBAHAN_ID= ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// kembalikan satuan kerja lama
		$str0= "		
				UPDATE PEGAWAI
				SET
					SATUAN_KERJA_ID = (SELECT SATKER_ASAL_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
				WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
				"; 
		$this->query = $str0;
		$this->execQuery($str0);

		// update data utama kl ada
		$str1= "		
				UPDATE JABATAN_TAMBAHAN
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_TAMBAHAN_ID = (SELECT JABATAN_TAMBAHAN_ID from persuratan.US_JABATAN_TAMBAHAN WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID").")
				"; 
		$this->query = $str1;
		$this->execQuery($str1);
		// echo $str1;exit();

		$str= "		
				UPDATE persuratan.US_JABATAN_TAMBAHAN
				SET    
					   STATUS_USULAN= ".$this->getField("STATUS").",
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_TAMBAHAN_ID    	= ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
       $str = "
				UPDATE persuratan.US_JABATAN_TAMBAHAN SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE US_JABATAN_TAMBAHAN_ID = ".$this->getField("US_JABATAN_TAMBAHAN_ID")."
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
	function selectByParamsPlt($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
		FROM SATUAN_KERJA A
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

    function selectByParamsPlh($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
		FROM SATUAN_KERJA A
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
	
    function selectByParamsTugasTambahan($paramsArray=array(),$limit=-1,$from=-1, $tipepegawaiid="", $statement='',$order=' ')
	{
		$str = "
		SELECT
		NAMA, TUGAS_TAMBAHAN_ID, SATKER_NAMA, SATKER_ID
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) SATUAN_KERJA_NAMA_DETIL
		FROM
		(
			SELECT
				NAMA, TUGAS_JABATAN_ID TUGAS_TAMBAHAN_ID, NULL SATKER_NAMA, NULL SATKER_ID
			FROM TUGAS_JABATAN
			WHERE TIPE_PEGAWAI_ID IS NOT NULL AND TIPE_PEGAWAI_ID = '".$tipepegawaiid."'
			UNION ALL
			SELECT
				NAMA_JABATAN NAMA, NULL TUGAS_TAMBAHAN_ID, NAMA SATKER_NAMA, SATUAN_KERJA_ID SATKER_ID
			FROM SATUAN_KERJA WHERE TIPE_JABATAN_ID = 2 AND JENIS_JABATAN_ID = ".substr($tipepegawaiid,1,1)."
		) A
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.US_JABATAN_TAMBAHAN_ID ASC')
	{
		$str = "
			SELECT
				A.US_JABATAN_TAMBAHAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NO_SK, A.TANGGAL_SK
				, A.TMT_JABATAN, A.TMT_JABATAN_AKHIR, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.BULAN_DIBAYAR
				, A.NAMA, A.TUGAS_TAMBAHAN_ID, A.IS_MANUAL
				, CASE WHEN COALESCE(NULLIF(A.SATKER_NAMA, ''), NULL) IS NULL THEN AMBIL_SATKER_NAMA(A.SATKER_ID) ELSE A.SATKER_NAMA END SATKER_NAMA, A.SATKER_ID, A.STATUS_PLT, A.STATUS,
				A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				, A.IS_DPK_MANUAL, A.SATKER_DPK_NAMA
				, A.SATKER_ASAL_ID, AMBIL_SATKER_NAMA_DETIL(A.SATKER_ASAL_ID) SATKER_ASAL_NAMA
				, A.JENIS_MUTASI_ID, CASE WHEN A.JENIS_MUTASI_ID = 1 THEN 'Mutasi Struktural / Pelaksana' WHEN A.JENIS_MUTASI_ID = 2 THEN 'Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana' END JENIS_MUTASI_NAMA
				, A.JENIS_JABATAN_TUGAS_ID, CASE WHEN A.JENIS_JABATAN_TUGAS_ID = 11 THEN 'Jabatan Struktural' WHEN A.JENIS_JABATAN_TUGAS_ID = 12 THEN 'Pelaksana' WHEN A.JENIS_JABATAN_TUGAS_ID = 21 THEN 'JFT Pendidikan' WHEN A.JENIS_JABATAN_TUGAS_ID = 22 THEN 'JFT Kesehatan' WHEN A.JENIS_JABATAN_TUGAS_ID = 29 THEN 'Mutasi Intern Pelaksana' END JENIS_JABATAN_TUGAS_NAMA
				, A.LAST_USER_CREATE, A.LAST_DATE_CREATE, A.USER_LOGIN_ID_CREATE, A.USER_LOGIN_PEGAWAI_ID_CREATE
				, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
				, P.NIP_LAMA, P.NIP_BARU
				, A.JABATAN_TAMBAHAN_ID, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_TAMBAHAN A
			INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
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
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.US_JABATAN_TAMBAHAN_ID) AS ROWCOUNT 
				FROM persuratan.US_JABATAN_TAMBAHAN A
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