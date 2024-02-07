<? 
/* *******************************************************************************************************
MODUL NAME 			: E LEARNING
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KontakPegawai.
  * 
  ***/
  include_once(APPPATH.'/models/Entity.php');
  
  class PendidikanRiwayat extends Entity{ 

	var $query;
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
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PENDIDIKAN_RIWAYAT")); 
     	$str = "
     	INSERT INTO validasi.PENDIDIKAN_RIWAYAT 
     	(
	     	PENDIDIKAN_RIWAYAT_ID, PEGAWAI_ID, PENDIDIKAN_ID, PENDIDIKAN_JURUSAN_ID, NAMA, TEMPAT
	     	, KEPALA, NO_STTB, TANGGAL_STTB, JURUSAN, NO_SURAT_IJIN, TANGGAL_SURAT_IJIN, STATUS_PENDIDIKAN, GELAR_TIPE
	     	, GELAR_DEPAN, GELAR_NAMA, STATUS_TUGAS_IJIN_BELAJAR, PPPK_STATUS
	     	, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
	     	, TEMP_VALIDASI_ID
     	) 
     	VALUES 
     	(
	     	".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
	     	".$this->getField("PEGAWAI_ID").",
	     	".$this->getField("PENDIDIKAN_ID").",
	     	".$this->getField("PENDIDIKAN_JURUSAN_ID").",
	     	'".$this->getField("NAMA")."',
	     	'".$this->getField("TEMPAT")."',
	     	'".$this->getField("KEPALA")."',
	     	'".$this->getField("NO_STTB")."',
	     	".$this->getField("TANGGAL_STTB").",
	     	'".$this->getField("JURUSAN")."',
	     	'".$this->getField("NO_SURAT_IJIN")."',
	     	".$this->getField("TANGGAL_SURAT_IJIN").",
	     	'".$this->getField("STATUS_PENDIDIKAN")."',
	     	'".$this->getField("GELAR_TIPE")."',
	     	'".$this->getField("GELAR_DEPAN")."',
	     	'".$this->getField("GELAR_NAMA")."',
	     	".$this->getField("STATUS_TUGAS_IJIN_BELAJAR").",
	     	".$this->getField("PPPK_STATUS").",
	     	'".$this->getField("LAST_USER")."',
	     	".$this->getField("LAST_DATE").",
	     	".$this->getField("LAST_LEVEL").",
	     	".$this->getField("USER_LOGIN_ID").",
	     	".$this->getField("USER_LOGIN_PEGAWAI_ID").",
	     	".$this->getField("TEMP_VALIDASI_ID")."
     	)
		"; 	
		$this->id = $this->getField("PENDIDIKAN_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		$str = "		
		UPDATE validasi.PENDIDIKAN_RIWAYAT
		SET  
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
			PENDIDIKAN_JURUSAN_ID= ".$this->getField("PENDIDIKAN_JURUSAN_ID").",
			NAMA= '".$this->getField("NAMA")."',
			TEMPAT= '".$this->getField("TEMPAT")."',
			KEPALA= '".$this->getField("KEPALA")."',
			NO_STTB= '".$this->getField("NO_STTB")."',
			TANGGAL_STTB= ".$this->getField("TANGGAL_STTB").",
			JURUSAN= '".$this->getField("JURUSAN")."',
			NO_SURAT_IJIN= '".$this->getField("NO_SURAT_IJIN")."',
			TANGGAL_SURAT_IJIN= ".$this->getField("TANGGAL_SURAT_IJIN").",
			STATUS_PENDIDIKAN= '".$this->getField("STATUS_PENDIDIKAN")."',
			STATUS_TUGAS_IJIN_BELAJAR= ".$this->getField("STATUS_TUGAS_IJIN_BELAJAR").",
			PPPK_STATUS= ".$this->getField("PPPK_STATUS").",
			GELAR_TIPE= '".$this->getField("GELAR_TIPE")."',
			GELAR_DEPAN= '".$this->getField("GELAR_DEPAN")."',
			GELAR_NAMA= '".$this->getField("GELAR_NAMA")."',
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TANGGAL_STTB DESC')
	{
		$str = "
		SELECT 	
			A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.PENDIDIKAN_JURUSAN_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.NAMA, A.TEMPAT, A.KEPALA, 
			A.NO_STTB, A.TANGGAL_STTB, A.JURUSAN, A.NO_SURAT_IJIN, A.TANGGAL_SURAT_IJIN, A.STATUS_PENDIDIKAN
			, A.GELAR_TIPE
			, CASE A.GELAR_TIPE
			WHEN '1' THEN 'Gelar Depan'
			WHEN '2' THEN 'Gelar Belakang'
			WHEN '3' THEN 'Gelar Depan Belakang'
			ELSE '-' END GELAR_TIPE_NAMA
			, A.GELAR_DEPAN, A.GELAR_NAMA, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS, C.NAMA PENDIDIKAN_NAMA,
			CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
			, CASE A.STATUS_PENDIDIKAN
			WHEN '1' THEN 'Pendidikan CPNS'
			WHEN '2' THEN 'Diakui'
			WHEN '3' THEN 'Belum Diakui'
			WHEN '4' THEN 'Riwayat'
			WHEN '5' THEN 'Ijin belajar'
			WHEN '6' THEN 'Tugas Belajar'
			ELSE '-' END STATUS_PENDIDIKAN_NAMA
			, A.STATUS_TUGAS_IJIN_BELAJAR
			, CASE A.STATUS_TUGAS_IJIN_BELAJAR WHEN 1 THEN 'Ijin Belajar' WHEN 2 THEN 'Tugas Belajar' END STATUS_TUGAS_IJIN_BELAJAR_NAMA, A.STATUS_VALIDASI_TUGAS_IJIN_BELAJAR
		FROM PENDIDIKAN_RIWAYAT A
		LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
		LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TANGGAL_STTB DESC')
	{
		$str = "
		SELECT 	
			A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.PENDIDIKAN_JURUSAN_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.NAMA, A.TEMPAT, A.KEPALA, 
			A.NO_STTB, A.TANGGAL_STTB, A.JURUSAN, A.NO_SURAT_IJIN, A.TANGGAL_SURAT_IJIN, A.STATUS_PENDIDIKAN
			, A.GELAR_TIPE
			, CASE A.GELAR_TIPE
			WHEN '1' THEN 'Gelar Depan'
			WHEN '2' THEN 'Gelar Belakang'
			WHEN '3' THEN 'Gelar Depan Belakang'
			ELSE '-' END GELAR_TIPE_NAMA
			, A.GELAR_DEPAN, A.GELAR_NAMA, C.NAMA PENDIDIKAN_NAMA,
			CASE A.STATUS_PENDIDIKAN
			WHEN '1' THEN 'Pendidikan CPNS'
			WHEN '2' THEN 'Diakui'
			WHEN '3' THEN 'Belum Diakui'
			WHEN '4' THEN 'Riwayat'
			WHEN '5' THEN 'Ijin belajar'
			WHEN '6' THEN 'Tugas Belajar'
			ELSE '-' END STATUS_PENDIDIKAN_NAMA
			, A.STATUS_TUGAS_IJIN_BELAJAR
			, CASE A.STATUS_TUGAS_IJIN_BELAJAR WHEN 1 THEN 'Ijin Belajar' WHEN 2 THEN 'Tugas Belajar' END STATUS_TUGAS_IJIN_BELAJAR_NAMA
			, A.STATUS
			, A.PPPK_STATUS
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_pendidikan_riwayat('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
		LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PENDIDIKAN_RIWAYAT_ID) AS ROWCOUNT 
				FROM PENDIDIKAN_RIWAYAT A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
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