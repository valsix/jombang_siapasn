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
  
  class UsJabatanMutasiIntern extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UsJabatanMutasiIntern()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("US_JABATAN_MUTASI_INTERN_ID", $this->getNextId("US_JABATAN_MUTASI_INTERN_ID","persuratan.US_JABATAN_MUTASI_INTERN")); 

     	$str = "
			INSERT INTO persuratan.US_JABATAN_MUTASI_INTERN (
				US_JABATAN_MUTASI_INTERN_ID, PEGAWAI_ID, SATKER_NAMA, SATKER_ID,
				LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				, SATKER_ASAL_ID, JENIS_MUTASI_ID, JENIS_JABATAN_TUGAS_ID
				, LAST_USER_CREATE, LAST_DATE_CREATE, USER_LOGIN_ID_CREATE, USER_LOGIN_PEGAWAI_ID_CREATE
				, STATUS_USULAN
			)
			VALUES (
				 	".$this->getField("US_JABATAN_MUTASI_INTERN_ID").",
				 	".$this->getField("PEGAWAI_ID").",
					'".$this->getField("SATKER_NAMA")."',
					".$this->getField("SATKER_ID").",
				 	'".$this->getField("LAST_USER")."',
				 	".$this->getField("LAST_DATE").",
				 	".$this->getField("LAST_LEVEL").",
				 	".$this->getField("USER_LOGIN_ID").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				 	".$this->getField("SATKER_ASAL_ID").",
				 	".$this->getField("JENIS_MUTASI_ID").",
				 	".$this->getField("JENIS_JABATAN_TUGAS_ID").",
				 	'".$this->getField("LAST_USER_CREATE")."',
				 	".$this->getField("LAST_DATE_CREATE").",
				 	".$this->getField("USER_LOGIN_ID_CREATE").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID_CREATE").",
				 	".$this->getField("STATUS_USULAN")."
			)
		"; 	
		$this->id = $this->getField("US_JABATAN_MUTASI_INTERN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertUtama()
	{
		// pindah satuan kerja tujuan
		$str= "		
				UPDATE PEGAWAI
				SET
					SATUAN_KERJA_ID = ".$this->getField("SATKER_ID")."
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.US_JABATAN_MUTASI_INTERN
				SET    
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					SATKER_ID= ".$this->getField("SATKER_ID").",
					SATKER_NAMA= '".$this->getField("SATKER_NAMA")."',
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_MUTASI_INTERN_ID = ".$this->getField("US_JABATAN_MUTASI_INTERN_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// kembalikan satuan kerja lama
		$str0= "		
				UPDATE PEGAWAI
				SET
					SATUAN_KERJA_ID = (SELECT SATKER_ASAL_ID from persuratan.US_JABATAN_MUTASI_INTERN WHERE US_JABATAN_MUTASI_INTERN_ID = ".$this->getField("US_JABATAN_MUTASI_INTERN_ID").")
				WHERE PEGAWAI_ID = (SELECT PEGAWAI_ID from persuratan.US_JABATAN_MUTASI_INTERN WHERE US_JABATAN_MUTASI_INTERN_ID = ".$this->getField("US_JABATAN_MUTASI_INTERN_ID").")
				"; 
		$this->query = $str0;
		$this->execQuery($str0);

		$str = "		
				UPDATE persuratan.US_JABATAN_MUTASI_INTERN
				SET    
					   STATUS_USULAN= ".$this->getField("STATUS").",
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  US_JABATAN_MUTASI_INTERN_ID    	= ".$this->getField("US_JABATAN_MUTASI_INTERN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
       $str = "
				UPDATE persuratan.US_JABATAN_MUTASI_INTERN SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE US_JABATAN_MUTASI_INTERN_ID = ".$this->getField("US_JABATAN_MUTASI_INTERN_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
       $str = "
				DELETE FROM persuratan.US_JABATAN_MUTASI_INTERN
				WHERE US_JABATAN_MUTASI_INTERN_ID = ".$this->getField("US_JABATAN_MUTASI_INTERN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
			SELECT
				A.US_JABATAN_MUTASI_INTERN_ID, A.PEGAWAI_ID, A.SATKER_ID
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
				, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				, A.SATKER_ASAL_ID, AMBIL_SATKER_NAMA_DETIL(A.SATKER_ASAL_ID) SATKER_ASAL_NAMA
				, A.JENIS_MUTASI_ID, CASE WHEN A.JENIS_MUTASI_ID = 1 THEN 'Mutasi Struktural / Pelaksana' WHEN A.JENIS_MUTASI_ID = 2 THEN 'Tugas JFT / T. Tambahan / Mutasi Intern Pelaksana' END JENIS_MUTASI_NAMA
				, A.JENIS_JABATAN_TUGAS_ID, CASE WHEN A.JENIS_JABATAN_TUGAS_ID = 11 THEN 'Jabatan Struktural' WHEN A.JENIS_JABATAN_TUGAS_ID = 12 THEN 'Pelaksana' WHEN A.JENIS_JABATAN_TUGAS_ID = 21 THEN 'JFT Pendidikan' WHEN A.JENIS_JABATAN_TUGAS_ID = 22 THEN 'JFT Kesehatan' WHEN A.JENIS_JABATAN_TUGAS_ID = 29 THEN 'Mutasi Intern Pelaksana' END JENIS_JABATAN_TUGAS_NAMA
				, A.LAST_USER_CREATE, A.LAST_DATE_CREATE, A.USER_LOGIN_ID_CREATE, A.USER_LOGIN_PEGAWAI_ID_CREATE
				, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
				, P.NIP_LAMA, P.NIP_BARU
				, A.STATUS_USULAN
			FROM persuratan.US_JABATAN_MUTASI_INTERN A
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
				SELECT COUNT(A.US_JABATAN_MUTASI_INTERN_ID) AS ROWCOUNT 
				FROM persuratan.US_JABATAN_MUTASI_INTERN A
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