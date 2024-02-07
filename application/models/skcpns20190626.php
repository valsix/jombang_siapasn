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
  
  class Skcpns extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Skcpns()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SK_CPNS_ID", $this->getNextId("SK_CPNS_ID","SK_CPNS"));
     	$str = "
			INSERT INTO SK_CPNS (
			SK_CPNS_ID, TRIGER_PANGKAT, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, NO_NOTA, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_CPNS, TANGGAL_TUGAS, 
			NO_STTPP, TANGGAL_STTPP, NAMA_PENETAP, NIP_PENETAP, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, NO_PERSETUJUAN_NIP, 
			TANGGAL_PERSETUJUAN_NIP, FORMASI_CPNS_ID, JABATAN_TUGAS, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("SK_CPNS_ID").",
				  NULL,
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  '".$this->getField("NO_NOTA")."',
				  ".$this->getField("TANGGAL_NOTA").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_CPNS").",
				  ".$this->getField("TANGGAL_TUGAS").",
				  '".$this->getField("NO_STTPP")."',
				  ".$this->getField("TANGGAL_STTPP").",
				  '".$this->getField("NAMA_PENETAP")."',
				  '".$this->getField("NIP_PENETAP")."',
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  '".$this->getField("NO_PERSETUJUAN_NIP")."',
				  ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
				  ".$this->getField("FORMASI_CPNS_ID").",
				  '".$this->getField("JABATAN_TUGAS")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SK_CPNS_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

 	function update() 
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_CPNS
				SET   
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					TRIGER_PANGKAT = NULL,
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
					PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  	NO_NOTA= '".$this->getField("NO_NOTA")."',
				  	TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
					NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
					TMT_CPNS= ".$this->getField("TMT_CPNS").",
				  	TANGGAL_TUGAS= ".$this->getField("TANGGAL_TUGAS").",
				  	TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
				  	NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
				  	NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
					MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  	NO_PERSETUJUAN_NIP= '".$this->getField("NO_PERSETUJUAN_NIP")."',
				  	TANGGAL_PERSETUJUAN_NIP= ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
					FORMASI_CPNS_ID= ".$this->getField("FORMASI_CPNS_ID").",
					JABATAN_TUGAS= '".$this->getField("JABATAN_TUGAS")."',
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  SK_CPNS_ID = ".$this->getField("SK_CPNS_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_CPNS
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  	NO_NOTA= '".$this->getField("NO_NOTA")."',
				  	TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_CPNS= ".$this->getField("TMT_CPNS").",
				  	TANGGAL_TUGAS= ".$this->getField("TANGGAL_TUGAS").",
				  	NO_STTPP= '".$this->getField("NO_STTPP")."',
				  	TANGGAL_STTPP= ".$this->getField("TANGGAL_STTPP").",
				  	NAMA_PENETAP= '".$this->getField("NAMA_PENETAP")."',
				  	NIP_PENETAP= '".$this->getField("NIP_PENETAP")."',
				  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  	NO_PERSETUJUAN_NIP= '".$this->getField("NO_PERSETUJUAN_NIP")."',
				  	TANGGAL_PERSETUJUAN_NIP= ".$this->getField("TANGGAL_PERSETUJUAN_NIP").",
				  	PENDIDIKAN_RIWAYAT_ID= ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  	LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  SK_CPNS_ID = ".$this->getField("SK_CPNS_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SK_CPNS
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  SK_CPNS_ID    	= ".$this->getField("SK_CPNS_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SK_CPNS SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SK_CPNS_ID = ".$this->getField("SK_CPNS_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SK_CPNS_ID ASC')
	{
		$str = "
				SELECT
					A.SK_CPNS_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.NO_NOTA, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK,
					A.TMT_CPNS, A.TANGGAL_TUGAS, A.NO_STTPP, A.TANGGAL_STTPP, A.NAMA_PENETAP, A.NIP_PENETAP, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN,
					A.GAJI_POKOK, A.NO_PERSETUJUAN_NIP, A.TANGGAL_PERSETUJUAN_NIP, A.PENDIDIKAN_RIWAYAT_ID
					, PEND.PENDIDIKAN_NAMA, PEND.PENDIDIKAN_JURUSAN_NAMA
					, COALESCE(B.KODE, 'Belum di entri') PANGKAT_KODE, B.NAMA PANGKAT_NAMA
					, PP.NAMA PEJABAT_PENETAP_NAMA
					, A.FORMASI_CPNS_ID, A.JABATAN_TUGAS
				FROM SK_CPNS A
				LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
				LEFT JOIN
				(
					SELECT A.PENDIDIKAN_RIWAYAT_ID, B.NAMA PENDIDIKAN_NAMA, C.NAMA PENDIDIKAN_JURUSAN_NAMA
					FROM PENDIDIKAN_RIWAYAT A
					INNER JOIN PENDIDIKAN B ON A.PENDIDIKAN_ID = B.PENDIDIKAN_ID
					LEFT JOIN PENDIDIKAN_JURUSAN C ON A.PENDIDIKAN_JURUSAN_ID = C.PENDIDIKAN_JURUSAN_ID
					WHERE 1=1
				) PEND ON A.PENDIDIKAN_RIWAYAT_ID = PEND.PENDIDIKAN_RIWAYAT_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
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
				SELECT COUNT(A.SK_CPNS_ID) AS ROWCOUNT 
				FROM SK_CPNS A
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

  } 
?>