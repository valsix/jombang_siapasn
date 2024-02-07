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
  
  class Hukuman extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Hukuman()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("HUKUMAN_ID", $this->getNextId("HUKUMAN_ID","HUKUMAN"));

     	$str = "
			INSERT INTO HUKUMAN (
				HUKUMAN_ID, PEGAWAI_ID, PANGKAT_RIWAYAT_TERAKHIR_ID, GAJI_RIWAYAT_TERAKHIR_ID, PANGKAT_RIWAYAT_TURUN_ID, GAJI_RIWAYAT_TURUN_ID, PANGKAT_RIWAYAT_KEMBALI_ID, JABATAN_RIWAYAT_ID, PEGAWAI_STATUS_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PERATURAN_ID, TINGKAT_HUKUMAN_ID, JENIS_HUKUMAN_ID, NO_SK, TANGGAL_SK, TMT_SK, KETERANGAN, BERLAKU, TANGGAL_MULAI, TANGGAL_AKHIR, TANGGAL_PEMULIHAN, TMT_BERIKUT_PANGKAT, TMT_BERIKUT_GAJI, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				  ".$this->getField("HUKUMAN_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PANGKAT_RIWAYAT_TERAKHIR_ID").",
				  ".$this->getField("GAJI_RIWAYAT_TERAKHIR_ID").",
				  ".$this->getField("PANGKAT_RIWAYAT_TURUN_ID").",
				  ".$this->getField("GAJI_RIWAYAT_TURUN_ID").",
				  ".$this->getField("PANGKAT_RIWAYAT_KEMBALI_ID").",
				  ".$this->getField("JABATAN_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_STATUS_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PERATURAN_ID").",
				  ".$this->getField("TINGKAT_HUKUMAN_ID").",
				  ".$this->getField("JENIS_HUKUMAN_ID").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_SK").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("BERLAKU").",
				  ".$this->getField("TANGGAL_MULAI").",
				  ".$this->getField("TANGGAL_AKHIR").",
				  ".$this->getField("TANGGAL_PEMULIHAN").",
				  ".$this->getField("TMT_BERIKUT_PANGKAT").",
				  ".$this->getField("TMT_BERIKUT_GAJI").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("HUKUMAN_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE HUKUMAN
				SET   
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	PERATURAN_ID= ".$this->getField("PERATURAN_ID").",
				  	TINGKAT_HUKUMAN_ID= ".$this->getField("TINGKAT_HUKUMAN_ID").",
				  	JENIS_HUKUMAN_ID= ".$this->getField("JENIS_HUKUMAN_ID").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_SK= ".$this->getField("TMT_SK").",
				  	KETERANGAN= '".$this->getField("KETERANGAN")."',
				  	TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
				  	TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR").",
					TANGGAL_PEMULIHAN= ".$this->getField("TANGGAL_PEMULIHAN").",
					TMT_BERIKUT_PANGKAT= ".$this->getField("TMT_BERIKUT_PANGKAT").",
					TMT_BERIKUT_GAJI= ".$this->getField("TMT_BERIKUT_GAJI").",
					GAJI_RIWAYAT_TERAKHIR_ID= ".$this->getField("GAJI_RIWAYAT_TERAKHIR_ID").",
					PANGKAT_RIWAYAT_TERAKHIR_ID= ".$this->getField("PANGKAT_RIWAYAT_TERAKHIR_ID").",
					PANGKAT_RIWAYAT_TURUN_ID= ".$this->getField("PANGKAT_RIWAYAT_TURUN_ID").",
					GAJI_RIWAYAT_TURUN_ID= ".$this->getField("GAJI_RIWAYAT_TURUN_ID").",
					PANGKAT_RIWAYAT_KEMBALI_ID= ".$this->getField("PANGKAT_RIWAYAT_KEMBALI_ID").",
					GAJI_RIWAYAT_KEMBALI_ID= ".$this->getField("GAJI_RIWAYAT_KEMBALI_ID").",
					JABATAN_RIWAYAT_ID= ".$this->getField("JABATAN_RIWAYAT_ID").",
					PEGAWAI_STATUS_ID= ".$this->getField("PEGAWAI_STATUS_ID").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  HUKUMAN_ID = ".$this->getField("HUKUMAN_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE HUKUMAN
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  HUKUMAN_ID    	= ".$this->getField("HUKUMAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE HUKUMAN SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE HUKUMAN_ID = ".$this->getField("HUKUMAN_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
       $str = "
				DELETE FROM HUKUMAN
				WHERE HUKUMAN_ID = ".$this->getField("HUKUMAN_ID")."
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
	function selectByParamsPangkatDataRiwayat($paramsArray=array(),$limit=-1, $from=-1, $statement='', $order=' ')
	{
		//AND TMT_PANGKAT <= TO_DATE('2017-10-02', 'YYYY-MM-DD')
		$str = "
		SELECT
			A.PANGKAT_RIWAYAT_ID, B.KODE PANGKAT_KODE, A.PANGKAT_ID, B.NAMA PANGKAT_NAMA, A.TMT_PANGKAT PANGKAT_TMT, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK
		FROM PANGKAT_RIWAYAT A
		INNER JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsPangkatRiwayat($paramsArray=array(),$limit=-1, $from=-1, $tanggal="", $statement='', $order=' ')
	{
		//AND TMT_PANGKAT <= TO_DATE('2017-10-02', 'YYYY-MM-DD')
		$str = "
		SELECT
			A.PANGKAT_RIWAYAT_ID, B.KODE PANGKAT_KODE, A.PANGKAT_ID, B.NAMA PANGKAT_NAMA, A.TMT_PANGKAT PANGKAT_TMT, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK
		FROM PANGKAT_RIWAYAT A
		INNER JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		INNER JOIN
		(
			SELECT MAX(A.TMT_PANGKAT) TMT_PANGKAT, A.PEGAWAI_ID
			FROM PANGKAT_RIWAYAT A
			WHERE 1=1
			AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			AND TMT_PANGKAT <= TO_DATE('".$tanggal."', 'YYYY-MM-DD')
			GROUP BY A.PEGAWAI_ID
		) C ON A.PEGAWAI_ID = C.PEGAWAI_ID AND A.TMT_PANGKAT = C.TMT_PANGKAT
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsGajiDataRiwayat($paramsArray=array(),$limit=-1, $from=-1, $statement='', $order=' ')
	{
		$str = "
		SELECT
			A.GAJI_RIWAYAT_ID, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA, A.TMT_SK, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK
		FROM GAJI_RIWAYAT A
		INNER JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsGajiRiwayat($paramsArray=array(),$limit=-1, $from=-1, $tanggal="", $statement='', $order=' ')
	{
		$str = "
		SELECT
			A.GAJI_RIWAYAT_ID, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA, A.TMT_SK, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN, A.GAJI_POKOK
		FROM GAJI_RIWAYAT A
		INNER JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		INNER JOIN
		(
			SELECT MAX(A.TMT_SK) TMT, A.PEGAWAI_ID
			FROM GAJI_RIWAYAT A
			WHERE 1=1
			AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			AND A.JENIS_KENAIKAN = 3
			AND TMT_SK <= TO_DATE('".$tanggal."', 'YYYY-MM-DD')
			GROUP BY A.PEGAWAI_ID
		) C ON A.PEGAWAI_ID = C.PEGAWAI_ID AND A.TMT_SK = C.TMT
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.HUKUMAN_ID ASC')
	{
		$str = "
				SELECT 	
					A.HUKUMAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PERATURAN_ID
					, A.TINGKAT_HUKUMAN_ID, B.NAMA TINGKAT_HUKUMAN_NAMA
					, A.JENIS_HUKUMAN_ID, C.NAMA JENIS_HUKUMAN_NAMA
					, A.NO_SK, A.TANGGAL_SK, A.TMT_SK, A.KETERANGAN, A.BERLAKU, A.TANGGAL_MULAI, A.TANGGAL_AKHIR, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS,
					B.NAMA TINGKAT_HUKUMAN_NAMA, C.NAMA JENIS_HUKUMAN_NAMA, D.NAMA PEJABAT_PENETAP_NAMA,
					CASE A.BERLAKU WHEN '1' THEN 'Berlaku' ELSE 'Tidak Berlaku' END BERLAKU_NAMABAK,
					CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 'Berlaku' ELSE 'Tidak Berlaku' END BERLAKU_NAMA
					, A.TANGGAL_PEMULIHAN, A.TMT_BERIKUT_PANGKAT, A.TMT_BERIKUT_GAJI
					, CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 1 ELSE 0 END STATUS_BERLAKU
					, CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 'Ya' ELSE 'Tidak' END STATUS_BERLAKU_INFO
					, A.PANGKAT_RIWAYAT_TERAKHIR_ID, A.GAJI_RIWAYAT_TERAKHIR_ID
					, A.PANGKAT_RIWAYAT_TURUN_ID, A.GAJI_RIWAYAT_TURUN_ID, A.PANGKAT_RIWAYAT_KEMBALI_ID, A.GAJI_RIWAYAT_KEMBALI_ID, A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID
				FROM HUKUMAN A
				LEFT JOIN TINGKAT_HUKUMAN B ON B.TINGKAT_HUKUMAN_ID = A.TINGKAT_HUKUMAN_ID
				LEFT JOIN JENIS_HUKUMAN C ON C.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID
				LEFT JOIN PEJABAT_PENETAP D ON D.PEJABAT_PENETAP_ID = A.PEJABAT_PENETAP_ID
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
	
	function selectByParamsPangkatTurunInfo($pangkatid="", $tanggalawal="", $tanggalakhir="")
	{
		$str= "
		SELECT 
		GETPANGKATTURUN('".$pangkatid."') PANGKAT_ID,
		AMBIL_UMUR_DUK(".$tanggalawal.", ".$tanggalakhir.") SELISIH
		"; 
		//echo $str;exit;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
		
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.HUKUMAN_ID) AS ROWCOUNT 
				FROM HUKUMAN A
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