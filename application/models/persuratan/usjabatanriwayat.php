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
  
  class UsJabatanRiwayat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UsJabatanRiwayat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("US_JABATAN_RIWAYAT_ID", $this->getNextId("US_JABATAN_RIWAYAT_ID","persuratan.US_JABATAN_RIWAYAT")); 

     	$str = "
			INSERT INTO persuratan.US_JABATAN_RIWAYAT (
				US_JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID, JABATAN_FU_ID, JABATAN_FT_ID, 
				ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT, 
				IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN,
				LAST_USER, LAST_DATE, LAST_LEVEL, TMT_ESELON, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				, SATKER_ASAL_ID, JENIS_MUTASI_ID, JENIS_JABATAN_TUGAS_ID
				, LAST_USER_CREATE, LAST_DATE_CREATE, USER_LOGIN_ID_CREATE, USER_LOGIN_PEGAWAI_ID_CREATE
				, STATUS_USULAN, JABATAN_RIWAYAT_ID
			)
			VALUES (
				 	".$this->getField("US_JABATAN_RIWAYAT_ID").",
				 	".$this->getField("PEGAWAI_ID").",
					'".$this->getField("JENIS_JABATAN_ID")."',
				 	".$this->getField("PEJABAT_PENETAP_ID").",
				 	'".$this->getField("PEJABAT_PENETAP")."',
				 	".$this->getField("TIPE_PEGAWAI_ID").",
				 	".$this->getField("JABATAN_FU_ID").",
				 	".$this->getField("JABATAN_FT_ID").",
				 	".$this->getField("ESELON_ID").",
				 	'".$this->getField("NO_SK")."',
				 	".$this->getField("TANGGAL_SK").",
				 	".$this->getField("TMT_JABATAN").",
				 	'".$this->getField("NAMA")."',
				 	'".$this->getField("NO_PELANTIKAN")."',
				 	".$this->getField("TANGGAL_PELANTIKAN").",
				 	".$this->getField("TUNJANGAN").",
				 	".$this->getField("KREDIT").",
				 	".$this->getField("IS_MANUAL").",
					'".$this->getField("SATKER_NAMA")."',
					".$this->getField("SATKER_ID").",
				 	".$this->getField("BULAN_DIBAYAR").",
				 	".$this->getField("TMT_BATAS_USIA_PENSIUN").",
				 	'".$this->getField("LAST_USER")."',
				 	".$this->getField("LAST_DATE").",
				 	".$this->getField("LAST_LEVEL").",
				 	".$this->getField("TMT_ESELON").",
				 	".$this->getField("USER_LOGIN_ID").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	".$this->getField("SATKER_ASAL_ID").",
				 	".$this->getField("JENIS_MUTASI_ID").",
				 	".$this->getField("JENIS_JABATAN_TUGAS_ID").",
				 	'".$this->getField("LAST_USER_CREATE")."',
				 	".$this->getField("LAST_DATE_CREATE").",
				 	".$this->getField("USER_LOGIN_ID_CREATE").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID_CREATE").",
				 	".$this->getField("STATUS_USULAN").",
				 	".$this->getField("JABATAN_RIWAYAT_ID")."
			)
		"; 	
		$this->id = $this->getField("US_JABATAN_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		if($this->getField("JABATAN_RIWAYAT_ID") == "")
		{
			return $this->execQuery($str);
		}
		else
		{
			$this->execQuery($str);
		}

		if($this->getField("JABATAN_RIWAYAT_ID") == ""){}
		else
		{
			// pindah satuan kerja tujuan
			$str0= "		
					UPDATE PEGAWAI
					SET
						SATUAN_KERJA_ID = (SELECT SATKER_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
					WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
					"; 
			$this->query = $str0;
			return $this->execQuery($str0);
		}

    }

    function insertUtama()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_RIWAYAT_ID", $this->getNextId("JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT")); 

     	$str = "
			INSERT INTO JABATAN_RIWAYAT (
				JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID, JABATAN_FU_ID, JABATAN_FT_ID, 
				ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT, 
				IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN,
				LAST_USER, LAST_DATE, LAST_LEVEL, TMT_ESELON, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			)
			VALUES (
				 	".$this->getField("JABATAN_RIWAYAT_ID").",
				 	".$this->getField("PEGAWAI_ID").",
					'".$this->getField("JENIS_JABATAN_ID")."',
				 	".$this->getField("PEJABAT_PENETAP_ID").",
				 	'".$this->getField("PEJABAT_PENETAP")."',
				 	".$this->getField("TIPE_PEGAWAI_ID").",
				 	".$this->getField("JABATAN_FU_ID").",
				 	".$this->getField("JABATAN_FT_ID").",
				 	".$this->getField("ESELON_ID").",
				 	'".$this->getField("NO_SK")."',
				 	".$this->getField("TANGGAL_SK").",
				 	".$this->getField("TMT_JABATAN").",
				 	'".$this->getField("NAMA")."',
				 	'".$this->getField("NO_PELANTIKAN")."',
				 	".$this->getField("TANGGAL_PELANTIKAN").",
				 	".$this->getField("TUNJANGAN").",
				 	".$this->getField("KREDIT").",
				 	".$this->getField("IS_MANUAL").",
					'".$this->getField("SATKER_NAMA")."',
					".$this->getField("SATKER_ID").",
				 	".$this->getField("BULAN_DIBAYAR").",
				 	".$this->getField("TMT_BATAS_USIA_PENSIUN").",
				 	'".$this->getField("LAST_USER")."',
				 	".$this->getField("LAST_DATE").",
				 	".$this->getField("LAST_LEVEL").",
				 	".$this->getField("TMT_ESELON").",
				 	".$this->getField("USER_LOGIN_ID").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("JABATAN_RIWAYAT_ID");
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.US_JABATAN_RIWAYAT
				SET    
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					JENIS_JABATAN_ID= '".$this->getField("JENIS_JABATAN_ID")."',
				 	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				 	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				 	TIPE_PEGAWAI_ID= ".$this->getField("TIPE_PEGAWAI_ID").",
				 	JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID").",
				 	JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID").",
				 	ESELON_ID= ".$this->getField("ESELON_ID").",
				 	NO_SK= '".$this->getField("NO_SK")."',
				 	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				 	TMT_JABATAN= ".$this->getField("TMT_JABATAN").",
				 	NAMA= '".$this->getField("NAMA")."',
				 	NO_PELANTIKAN= '".$this->getField("NO_PELANTIKAN")."',
				 	TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN").",
				 	TUNJANGAN= ".$this->getField("TUNJANGAN").",
				 	KREDIT= ".$this->getField("KREDIT").",
				 	SATKER_ID= ".$this->getField("SATKER_ID").",
					SATKER_NAMA= '".$this->getField("SATKER_NAMA")."',
					IS_MANUAL= ".$this->getField("IS_MANUAL").",
				 	BULAN_DIBAYAR= ".$this->getField("BULAN_DIBAYAR").",
				 	TMT_BATAS_USIA_PENSIUN= ".$this->getField("TMT_BATAS_USIA_PENSIUN").",
				 	TMT_ESELON= ".$this->getField("TMT_ESELON").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function rejectdata()
	{
		$str= "		
		UPDATE persuratan.US_JABATAN_RIWAYAT
		SET    
		ALASAN_TOLAK= '".$this->getField("ALASAN_TOLAK")."'
		, STATUS_USULAN= ".$this->getField("STATUS")."
		, STATUS= ".$this->getField("STATUS")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_DATE= ".$this->getField("LAST_DATE")."
		, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE US_JABATAN_RIWAYAT_ID= ".$this->getField("US_JABATAN_RIWAYAT_ID")."
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
					SATUAN_KERJA_ID = (SELECT SATKER_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
				WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
				"; 
		$this->query = $str0;
		$this->execQuery($str0);

		// update data utama kl ada
		$this->setField("JABATAN_RIWAYAT_ID", $this->getNextId("JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT")); 

     	$str1= "
     		INSERT INTO JABATAN_RIWAYAT (
				JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID, JABATAN_FU_ID, JABATAN_FT_ID, 
				ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT, 
				IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN,
				LAST_USER, LAST_DATE, LAST_LEVEL, TMT_ESELON, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			)
			SELECT
			".$this->getField("JABATAN_RIWAYAT_ID")." JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID, JABATAN_FU_ID, JABATAN_FT_ID, 
				ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT, 
				IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN
				, '".$this->getField("LAST_USER")."' LAST_USER, ".$this->getField("LAST_DATE")." LAST_DATE, ".$this->getField("LAST_LEVEL")." LAST_LEVEL, TMT_ESELON, ".$this->getField("USER_LOGIN_ID")." USER_LOGIN_ID, ".$this->getField("USER_LOGIN_PEGAWAI_ID")." USER_LOGIN_PEGAWAI_ID
			FROM persuratan.US_JABATAN_RIWAYAT
			WHERE US_JABATAN_RIWAYAT_ID= ".$this->getField("US_JABATAN_RIWAYAT_ID")."
		"; 	
		// $this->id = $this->getField("JABATAN_RIWAYAT_ID");
		$this->query = $str1;
		// echo $str1;exit;
		$this->execQuery($str1);

		$str= "		
				UPDATE persuratan.US_JABATAN_RIWAYAT
				SET    
					   JABATAN_RIWAYAT_ID= ".$this->getField("JABATAN_RIWAYAT_ID").",
					   STATUS_USULAN= ".$this->getField("STATUS").",
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_RIWAYAT_ID    	= ".$this->getField("US_JABATAN_RIWAYAT_ID")."
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
					SATUAN_KERJA_ID = (SELECT SATKER_ASAL_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
				WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
				"; 
		$this->query = $str0;
		$this->execQuery($str0);

		// update data utama kl ada
		$str1= "		
				UPDATE JABATAN_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_RIWAYAT_ID = (SELECT JABATAN_RIWAYAT_ID from persuratan.US_JABATAN_RIWAYAT WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID").")
				"; 
		$this->query = $str1;
		$this->execQuery($str1);
		// echo $str1;exit();

		$str = "		
				UPDATE persuratan.US_JABATAN_RIWAYAT
				SET    
					   STATUS_USULAN= ".$this->getField("STATUS").",
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_RIWAYAT_ID    	= ".$this->getField("US_JABATAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
       $str = "
				UPDATE persuratan.US_JABATAN_RIWAYAT SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
       $str = "
				DELETE FROM persuratan.US_JABATAN_RIWAYAT
				WHERE US_JABATAN_RIWAYAT_ID = ".$this->getField("US_JABATAN_RIWAYAT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
			SELECT
				A.US_JABATAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.TIPE_PEGAWAI_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, 
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
				, A.TMT_ESELON
				, A.SATKER_ASAL_ID, AMBIL_SATKER_NAMA_DETIL(A.SATKER_ASAL_ID) SATKER_ASAL_NAMA
				, A.JENIS_MUTASI_ID, CASE WHEN A.JENIS_MUTASI_ID = 1 THEN 'Mutasi Struktural / Pelaksana' WHEN A.JENIS_MUTASI_ID = 2 THEN 'Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana' END JENIS_MUTASI_NAMA
				, A.JENIS_JABATAN_TUGAS_ID, CASE WHEN A.JENIS_JABATAN_TUGAS_ID = 11 THEN 'Jabatan Struktural' WHEN A.JENIS_JABATAN_TUGAS_ID = 12 THEN 'Pelaksana' WHEN A.JENIS_JABATAN_TUGAS_ID = 21 THEN 'JFT Pendidikan' WHEN A.JENIS_JABATAN_TUGAS_ID = 22 THEN 'JFT Kesehatan' WHEN A.JENIS_JABATAN_TUGAS_ID = 29 THEN 'Mutasi Intern Pelaksana' END JENIS_JABATAN_TUGAS_NAMA
				, A.LAST_USER_CREATE, A.LAST_DATE_CREATE, A.USER_LOGIN_ID_CREATE, A.USER_LOGIN_PEGAWAI_ID_CREATE
				, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
				, P.NIP_LAMA, P.NIP_BARU
				, A.JABATAN_RIWAYAT_ID, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_RIWAYAT A
			INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
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
				SELECT COUNT(A.US_JABATAN_RIWAYAT_ID) AS ROWCOUNT 
				FROM persuratan.US_JABATAN_RIWAYAT A
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