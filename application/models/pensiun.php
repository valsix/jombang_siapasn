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
  
  class Pensiun extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Pensiun()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_STATUS_ID", $this->getNextId("PEGAWAI_STATUS_ID","PEGAWAI_STATUS")); 

     	$str0= "
			INSERT INTO PEGAWAI_STATUS (
				PEGAWAI_STATUS_ID, PEGAWAI_ID, STATUS_PEGAWAI_ID, TMT, STATUS_PEGAWAI_KEDUDUKAN_ID, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				 ".$this->getField("PEGAWAI_STATUS_ID").",
			     ".$this->getField("PEGAWAI_ID").",
			     ".$this->getField("STATUS_PEGAWAI_ID").",
			     ".$this->getField("TMT").",
				 ".$this->getField("STATUS_PEGAWAI_KEDUDUKAN_ID").",
			     '".$this->getField("LAST_USER")."',
			     ".$this->getField("LAST_DATE").",
			     '".$this->getField("USER_LOGIN_ID")."',
			     ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEGAWAI_STATUS_ID");
		$this->query = $str0;
		// echo $str0;exit;
		return $this->execQuery($str0);
    }

    function insertPensiun()
	{
     	$str = "
     	INSERT INTO PENSIUN
		(
		JENIS, PEGAWAI_ID, PEGAWAI_STATUS_ID, JABATAN_RIWAYAT_ID, PANGKAT_RIWAYAT_ID, GAJI_RIWAYAT_ID, HUKUMAN_ID
		, SATUAN_KERJA_ID, STATUS_PENSIUN
		, TMT, NOMOR_SK, TANGGAL_KEMATIAN, TANGGAL_SK_KEMATIAN, KETERANGAN
		,LAST_USER, LAST_DATE, LAST_LEVEL
		)
		SELECT
			'".$this->getField("JENIS")."' JENIS, A.PEGAWAI_ID, A.PEGAWAI_STATUS_ID, A.JABATAN_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, HT.HUKUMAN_ID
			, A.SATUAN_KERJA_ID, NULL STATUS_PENSIUN
			, ".$this->getField("TMT")." TMT, '".$this->getField("NOMOR_SK")."' NOMOR_SK, ".$this->getField("TANGGAL_KEMATIAN")." TANGGAL_KEMATIAN, ".$this->getField("TANGGAL_SK_KEMATIAN")." TANGGAL_SK_KEMATIAN, '".$this->getField("KETERANGAN")."' KETERANGAN
			, '".$this->getField("LAST_USER")."' LAST_USER, ".$this->getField("LAST_DATE")." LAST_DATE, ".$this->getField("LAST_LEVEL")." LAST_LEVEL
		FROM PEGAWAI A
		LEFT JOIN JABATAN_RIWAYAT JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN ESELON_DETIL ED ON ED.ESELON_ID = JAB_RIW.ESELON_ID AND ED.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(ED.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		LEFT JOIN JABATAN_FU_DETIL JFU ON JFU.JABATAN_FU_ID = JAB_RIW.JABATAN_FU_ID AND JFU.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFU.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		LEFT JOIN JABATAN_FT_DETIL JFT ON JFT.JABATAN_FT_ID = JAB_RIW.JABATAN_FT_ID AND JFT.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFT.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		LEFT JOIN HUKUMAN_TERAKHIR HT ON A.PEGAWAI_ID = HT.PEGAWAI_ID
		LEFT JOIN 
		(
			SELECT A.PEGAWAI_ID, CASE WHEN A.STATUS_PENSIUN = '4' THEN '1' WHEN A.STATUS_PENSIUN IS NULL THEN '2' ELSE '3' END STATUS_PENSIUN_INFO 
			FROM PENSIUN A WHERE A.JENIS = '".$this->getField("JENIS")."'
		)
		K ON K.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		AND A.PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
		"; 	

		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str0= "
			UPDATE PEGAWAI_STATUS 
			SET
			TMT= ".$this->getField("TMT").",
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE")."
			WHERE PEGAWAI_STATUS_ID= ".$this->getField("PEGAWAI_STATUS_ID")."
		"; 	
		$this->query = $str0;
		// echo $str0;exit;
		return $this->execQuery($str0);
    }

    function updatePensiun()
	{	
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PENSIUN
				SET
			     	TMT= ".$this->getField("TMT").",
			     	NOMOR_SK= '".$this->getField("NOMOR_SK")."',
			     	TANGGAL_KEMATIAN= ".$this->getField("TANGGAL_KEMATIAN").",
					TANGGAL_SK_KEMATIAN= ".$this->getField("TANGGAL_SK_KEMATIAN").",
					KETERANGAN= '".$this->getField("KETERANGAN")."',
			     	LAST_USER= '".$this->getField("LAST_USER")."',
			     	LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND JENIS = '".$this->getField("JENIS")."'
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateBatal()
	{
		$str= "
				DELETE FROM PENSIUN
				WHERE
				PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// PEGAWAI_STATUS_ID= ".$this->getField("PEGAWAI_STATUS_ID")." AND
		// JENIS= '".$this->getField("JENIS")."'
		// echo $str;
		$this->execQuery($str);

		$str1= "
				DELETE FROM PEGAWAI_STATUS
				WHERE PEGAWAI_STATUS_ID= ".$this->getField("PEGAWAI_STATUS_ID")."
				"; 
		$this->query = $str1;
		// echo $str1;
		return $this->execQuery($str1);
    }
    
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET    
					   GAJI_RIWAYAT_ID   	= ".$this->getField("GAJI_RIWAYAT_ID").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  JENIS    = ".$this->getField("JENIS")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
				UPDATE PENSIUN SET
					GAJI_RIWAYAT_ID = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE JENIS = ".$this->getField("JENIS")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
		/*$strLog= "
				DELETE FROM PENSIUN_LOG
				WHERE JENIS = ".$this->getField("JENIS")."
				";
		$this->query = $strLog;
		$this->execQuery($strLog);*/
		
       $str = "
				DELETE FROM PENSIUN
				WHERE JENIS = ".$this->getField("JENIS")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function deletePegawai()
	{
       $str = "
				DELETE FROM PENSIUN
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
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
    function selectByParamsStatus($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_STATUS_ID ASC')
	{
		$str = "
		SELECT 
			A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, A.TMT
			, A.STATUS_PEGAWAI_KEDUDUKAN_ID, C.NAMA STATUS_PEGAWAI_KEDUDUKAN_NAMA, A.LAST_USER, A.LAST_DATE
		FROM PEGAWAI_STATUS A
		INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JENIS ASC')
	{
		$str = "
		SELECT
			A.JENIS, A.PEGAWAI_ID, A.PEGAWAI_STATUS_ID, A.JABATAN_RIWAYAT_ID, A.STATUS_HITUNG_ULANG, 
			A.PANGKAT_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.HUKUMAN_ID, A.TMT, A.TANGGAL_KEMATIAN, 
			A.NOMOR_SK, A.TANGGAL_SK_KEMATIAN, A.KETERANGAN, A.SATUAN_KERJA_ID, A.STATUS_PENSIUN, 
			A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM PENSIUN A
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
    
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.JENIS) AS ROWCOUNT 
				FROM PENSIUN A
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

    function getCountByParamsSuratMasukPegawai($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM SURAT_MASUK_PEGAWAI A
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