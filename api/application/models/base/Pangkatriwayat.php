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
  
  class PangkatRiwayat extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PangkatRiwayat()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.PANGKAT_RIWAYAT"));
     	$str = "
			INSERT INTO validasi.PANGKAT_RIWAYAT (
				PANGKAT_RIWAYAT_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, STLUD, NO_STLUD, 
				TANGGAL_STLUD, NO_NOTA, TANGGAL_NOTA, NO_SK, TANGGAL_SK, TMT_PANGKAT, KREDIT, JENIS_RIWAYAT, 
				KETERANGAN, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, NO_URUT_CETAK, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			) 
			VALUES (
				  ".$this->getField("PANGKAT_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  '".$this->getField("STLUD")."',
				  '".$this->getField("NO_STLUD")."',
				  ".$this->getField("TANGGAL_STLUD").",
				  '".$this->getField("NO_NOTA")."',
				  ".$this->getField("TANGGAL_NOTA").",
				  '".$this->getField("NO_SK")."',
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_PANGKAT").",
				  ".$this->getField("KREDIT").",
				  ".$this->getField("JENIS_RIWAYAT").",
				  '".$this->getField("KETERANGAN")."',
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  ".$this->getField("NO_URUT_CETAK").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL").",
				  ".$this->getField("USER_LOGIN_ID").",
				  ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				  ".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	
		$this->id = $this->getField("PANGKAT_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
				UPDATE validasi.PANGKAT_RIWAYAT
				SET    
				  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  	STLUD= '".$this->getField("STLUD")."',
				  	NO_STLUD= '".$this->getField("NO_STLUD")."',
				  	TANGGAL_STLUD= ".$this->getField("TANGGAL_STLUD").",
				  	NO_NOTA= '".$this->getField("NO_NOTA")."',
				  	TANGGAL_NOTA= ".$this->getField("TANGGAL_NOTA").",
				  	NO_SK= '".$this->getField("NO_SK")."',
				  	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  	TMT_PANGKAT= ".$this->getField("TMT_PANGKAT").",
				  	KREDIT= ".$this->getField("KREDIT").",
				  	JENIS_RIWAYAT= ".$this->getField("JENIS_RIWAYAT").",
				  	KETERANGAN= '".$this->getField("KETERANGAN")."',
				  	MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  	MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  	GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
					NO_URUT_CETAK=".$this->getField("NO_URUT_CETAK").",
				  	LAST_USER= '".$this->getField("LAST_USER")."',
				  	LAST_DATE= ".$this->getField("LAST_DATE").",
				  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TMT_PANGKAT DESC')
	{
		$str = "
		SELECT
			A.PANGKAT_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.STLUD, A.NO_STLUD, A.TANGGAL_STLUD, A.NO_NOTA
			, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK, A.TMT_PANGKAT, A.KREDIT, A.JENIS_RIWAYAT, A.KETERANGAN, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
			, A.GAJI_POKOK, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, CASE A.JENIS_RIWAYAT WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
			WHEN 4 THEN 'Reguler'
			WHEN 5 THEN 'Pilihan Struktural'
			WHEN 6 THEN 'Pilihan JFT'
			WHEN 7 THEN 'Pilihan PI/UD'
			WHEN 10 THEN 'Penambahan Masa Kerja'
			WHEN 8 THEN 'Hukuman disiplin'
			WHEN 9 THEN 'Pemulihan hukuman disiplin'
			ELSE '-' END JENIS_RIWAYAT_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
			, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
			, A.LAST_USER, A.LAST_DATE, A.NO_URUT_CETAK
			, COALESCE(HK.HUKUMAN_ID,HK1.HUKUMAN_ID,0) DATA_HUKUMAN
		FROM PANGKAT_RIWAYAT A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN HUKUMAN HK ON A.PANGKAT_RIWAYAT_ID = HK.PANGKAT_RIWAYAT_TURUN_ID
		LEFT JOIN HUKUMAN HK1 ON A.PANGKAT_RIWAYAT_ID = HK1.PANGKAT_RIWAYAT_KEMBALI_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }


	function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1,$pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.TMT_PANGKAT DESC')
	{
		$str = "
		SELECT
			A.PANGKAT_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PANGKAT_ID, A.STLUD, A.NO_STLUD, A.TANGGAL_STLUD, A.NO_NOTA
			, A.TANGGAL_NOTA, A.NO_SK, A.TANGGAL_SK, A.TMT_PANGKAT, A.KREDIT, A.JENIS_RIWAYAT, A.KETERANGAN, A.MASA_KERJA_TAHUN, A.MASA_KERJA_BULAN
			, A.GAJI_POKOK
			, B.KODE PANGKAT_KODE, B.NAMA PANGKAT_NAMA
			, CASE A.JENIS_RIWAYAT WHEN 1 THEN 'CPNS' WHEN 2 THEN 'PNS'
			WHEN 4 THEN 'Reguler'
			WHEN 5 THEN 'Pilihan Struktural'
			WHEN 6 THEN 'Pilihan JFT'
			WHEN 7 THEN 'Pilihan PI/UD'
			WHEN 10 THEN 'Penambahan Masa Kerja'
			WHEN 8 THEN 'Hukuman disiplin'
			WHEN 9 THEN 'Pemulihan hukuman disiplin'
			ELSE '-' END JENIS_RIWAYAT_NAMA
			, PP.NAMA PEJABAT_PENETAP_NAMA
			, A.NO_URUT_CETAK
			, COALESCE(HK.HUKUMAN_ID,HK1.HUKUMAN_ID,0) DATA_HUKUMAN
			, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA
		FROM (select * from validasi.validasi_pegawai_pangkat_riwayat('".$pegawaiid."', '".$id."', '".$rowid."')) A
		LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		LEFT JOIN PEJABAT_PENETAP PP ON A.PEJABAT_PENETAP_ID = PP.PEJABAT_PENETAP_ID
		LEFT JOIN HUKUMAN HK ON A.PANGKAT_RIWAYAT_ID = HK.PANGKAT_RIWAYAT_TURUN_ID
		LEFT JOIN HUKUMAN HK1 ON A.PANGKAT_RIWAYAT_ID = HK1.PANGKAT_RIWAYAT_KEMBALI_ID
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.PANGKAT_RIWAYAT_ID) AS ROWCOUNT 
				FROM PANGKAT_RIWAYAT A
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