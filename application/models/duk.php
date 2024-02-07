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
  
  class Duk extends Entity{ 

	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Duk()
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
	function prosesDuk()
	{
        $str = "
		SELECT PROSESDUK('".$this->getField("PERIODE")."', ".$this->getField("SATKERID").", ".$this->getField("KONDISISATKERID").", '".$this->getField("TIPEPEGAWAI")."', '".$this->getField("LAST_USER")."', ".$this->getField("LAST_LEVEL").")
		"; 
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DUK_ID ASC')
	{
		$str = "
		SELECT
			A.DUK, A.PERIODE, A.SATUAN_KERJA_PROSES_ID, A.PEGAWAI_ID, A.TIPE_PEGAWAI_ID,
			A.SATUAN_KERJA_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.TEMPAT_LAHIR, A.JENIS_KELAMIN,
			A.STATUS_PEGAWAI_NAMA, A.USIA, A.TANGGAL_LAHIR, A.AGAMA, A.PANGKAT_RIWAYAT_ID,
			A.PANGKAT_ID, A.GOL_RUANG, A.TMT_PANGKAT, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN,
			A.JABATAN_RIWAYAT_ID, A.JABATAN, A.TMT_JABATAN, A.ESELON_ID, A.ESELON,
			A.TMT_ESELON, A.DIKLAT_STRUKTURAL, A.TAHUN_DIKLAT, A.JUMLAH_DIKLAT_STRUKTURAL,
			A.JUMLAH_DIKLAT_NONSTRUKTURAL, A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN,
			A.NAMA_SEKOLAH, A.TAHUN_LULUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM DUK A
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

    function selectByParamsCetak($statement='',$order=' ORDER BY A.DUK ASC')
	{
		$str = "
		SELECT
			A.SATUAN_KERJA_ID,
			DUK, A.PEGAWAI_ID, NIP_LAMA, NIP_BARU NIP_BARU, A.NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN,
			STATUS_PEGAWAI_NAMA STATUS_PEGAWAI, GOL_RUANG, TMT_PANGKAT,
			JABATAN, A.TMT_JABATAN, 
			ESELON, TMT_ESELON, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, DIKLAT_STRUKTURAL,
			TAHUN_DIKLAT, JUMLAH_DIKLAT_STRUKTURAL || '/' || JUMLAH_DIKLAT_NONSTRUKTURAL JUMLAH_DIKLAT, PENDIDIKAN TINGKAT_PENDIDIKAN, TAHUN_LULUS,
			NAMA_SEKOLAH, PENDIDIKAN  || ' - ' || NAMA_SEKOLAH PENDIDIKAN_NAMA,
			USIA, B.NAMA SATKER_NAMA
		FROM DUK A
		LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		WHERE 1 = 1
		"; 

		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1); 
    }

    function getCountByParamsCetak($statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM DUK A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				WHERE 1 = 1 ".$statement; 
		$str .= $statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.DUK_ID ASC')
	{
		$str = "
		SELECT
			DUK, A.PEGAWAI_ID, A.NAMA, A.NIP_BARU NIP_BARU, A.GOL_RUANG, A.TMT_PANGKAT, A.JABATAN, A.TMT_JABATAN,  MASA_KERJA_TAHUN, MASA_KERJA_BULAN,  
			C.NAMA DIKLAT_STRUKTURAL, C.TANGGAL_MULAI TANGGAL_MULAI_STRUKTURAL, C.TANGGAL_SELESAI TANGGAL_SELESAI_STRUKTURAL, C.JUMLAH_JAM JUMLAH_JAM_STRUKTURAL,
			NAMA_SEKOLAH, PENDIDIKAN, TAHUN_LULUS,  
			A.SATUAN_KERJA_ID, NIP_LAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_PEGAWAI_NAMA, ESELON, TMT_ESELON, TAHUN_DIKLAT, 
			JUMLAH_DIKLAT_STRUKTURAL || '/' || JUMLAH_DIKLAT_NONSTRUKTURAL JUMLAH_DIKLAT, USIA, B.NAMA SATKER_NAMA
		FROM DUK A
		LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
		LEFT JOIN DIKLAT_STRUKTURAL_TERAKHIR C ON C.PEGAWAI_ID = A.PEGAWAI_ID
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM DUK A
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
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM DUK A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ID = B.SATUAN_KERJA_ID
				LEFT JOIN DIKLAT_STRUKTURAL_TERAKHIR C ON C.PEGAWAI_ID = A.PEGAWAI_ID
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