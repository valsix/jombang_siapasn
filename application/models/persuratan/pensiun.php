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
		$str = "
			INSERT INTO PENSIUN
			(
				PERIODE, PEGAWAI_ID, PEGAWAI_STATUS_ID, JABATAN_RIWAYAT_ID,
				GAJI_RIWAYAT_LAMA_ID, HUKUMAN_RIWAYAT_ID, TANGGAL_BARU, TMT_BARU, MASA_KERJA_TAHUN_BARU, 
				MASA_KERJA_BULAN_BARU, GAJI_BARU, SATUAN_KERJA_ID, SURAT_KELUAR_BKD_ID, STATUS_KGB, LAST_USER, LAST_DATE, LAST_LEVEL
			)
			VALUES (
				'".$this->getField("PERIODE")."',
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("PEGAWAI_STATUS_ID").",
				".$this->getField("JABATAN_RIWAYAT_ID").",
				".$this->getField("GAJI_RIWAYAT_LAMA_ID").",
				".$this->getField("HUKUMAN_RIWAYAT_ID").",
				".$this->getField("TANGGAL_BARU").",
				".$this->getField("TMT_BARU").",
				".$this->getField("MASA_KERJA_TAHUN_BARU").",
				".$this->getField("MASA_KERJA_BULAN_BARU").",
				".$this->getField("GAJI_BARU").",
				".$this->getField("SATUAN_KERJA_ID").",
				".$this->getField("SURAT_KELUAR_BKD_ID").",
				".$this->getField("STATUS_KGB").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL")."
			)
		"; 	
		//$this->id = $this->getField("AGAMA_ID");
		//echo $str;exit;
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
	function prosesPensiun()
	{
        $str = "
		SELECT PROSESPENSIUN('".$this->getField("JENIS")."', ".$this->getField("SATKERID").", '".$this->getField("LAST_USER")."', ".$this->getField("LAST_LEVEL").")
		"; 
		$this->query = $str;
		// echo $str;exit();
        return $this->execQuery($str);
    }
	
	function prosesPersonalKgb()
	{
        $str = "
		SELECT PROSESPENSIUNPEGAWAI('".$this->getField("JENIS")."', ".$this->getField("PEGAWAI_ID").", '".$this->getField("LAST_USER")."', ".$this->getField("LAST_LEVEL").")
		"; 
		$this->query = $str;
		//echo $str;
        return $this->execQuery($str);
    }
	
	function updateStatusHitung()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET 	
				   STATUS_HITUNG_ULANG= 1,
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateResetStatusHitung()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET 	
				   STATUS_HITUNG_ULANG= NULL,
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET 	
				   GAJI_RIWAYAT_LAMA_ID= ".$this->getField("GAJI_RIWAYAT_LAMA_ID").",
				   SURAT_KELUAR_BKD_ID= ".$this->getField("SURAT_KELUAR_BKD_ID").",
				   NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
				   KETERANGAN_TEKNIS= '".$this->getField("KETERANGAN_TEKNIS")."',
				   TANGGAL_BARU= ".$this->getField("TANGGAL_BARU").",
				   TMT_BARU= ".$this->getField("TMT_BARU").",
				   MASA_KERJA_TAHUN_BARU= ".$this->getField("MASA_KERJA_TAHUN_BARU").",
				   MASA_KERJA_BULAN_BARU= ".$this->getField("MASA_KERJA_BULAN_BARU").",
				   GAJI_BARU= ".$this->getField("GAJI_BARU").",
				   SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
			       STATUS_KGB= ".$this->getField("STATUS_KGB").",
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateSetNomor()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET 	
				   GAJI_RIWAYAT_LAMA_ID= ".$this->getField("GAJI_RIWAYAT_LAMA_ID").",
				   SURAT_KELUAR_BKD_ID= ".$this->getField("SURAT_KELUAR_BKD_ID").",
				   NOMOR_URUT= (SELECT COALESCE((SELECT MAX(COALESCE(NOMOR_URUT,0)) + 1 DATA_NOMOR FROM kenaikan_gaji_berkala A WHERE 1=1 AND A.SURAT_KELUAR_BKD_ID = ".$this->getField("SURAT_KELUAR_BKD_ID")."),1)),
				   KETERANGAN_TEKNIS= '".$this->getField("KETERANGAN_TEKNIS")."',
				   TANGGAL_BARU= ".$this->getField("TANGGAL_BARU").",
				   TMT_BARU= ".$this->getField("TMT_BARU").",
				   MASA_KERJA_TAHUN_BARU= ".$this->getField("MASA_KERJA_TAHUN_BARU").",
				   MASA_KERJA_BULAN_BARU= ".$this->getField("MASA_KERJA_BULAN_BARU").",
				   GAJI_BARU= ".$this->getField("GAJI_BARU").",
				   SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
			       STATUS_KGB= ".$this->getField("STATUS_KGB").",
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateSelesai()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PENSIUN
				SET 	
				   GAJI_RIWAYAT_LAMA_ID= ".$this->getField("GAJI_RIWAYAT_LAMA_ID").",
				   GAJI_RIWAYAT_BARU_ID= (SELECT MAX(COALESCE(GAJI_RIWAYAT_ID,0)) + 1 FROM GAJI_RIWAYAT A WHERE 1=1),
				   SURAT_KELUAR_BKD_ID= ".$this->getField("SURAT_KELUAR_BKD_ID").",
				   NOMOR_URUT= ".$this->getField("NOMOR_URUT").",
				   KETERANGAN_TEKNIS= '".$this->getField("KETERANGAN_TEKNIS")."',
				   TANGGAL_BARU= ".$this->getField("TANGGAL_BARU").",
				   TMT_BARU= ".$this->getField("TMT_BARU").",
				   MASA_KERJA_TAHUN_BARU= ".$this->getField("MASA_KERJA_TAHUN_BARU").",
				   MASA_KERJA_BULAN_BARU= ".$this->getField("MASA_KERJA_BULAN_BARU").",
				   GAJI_BARU= ".$this->getField("GAJI_BARU").",
				   SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
			       STATUS_KGB= ".$this->getField("STATUS_KGB").",
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENSIUN
				SET 	
				   STATUS		= ".$this->getField("STATUS").",
			       STATUS_KGB	= ".$this->getField("STATUS_KGB").",
			       LAST_USER	= '".$this->getField("LAST_USER")."',
			       LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE PERIODE	= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateBatal()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str1= "
				UPDATE PENSIUN
				SET 	
				   STATUS_KGB= NULL
				WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		$this->execQuery($str1);
		
		$str2= "		
				UPDATE GAJI_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  GAJI_RIWAYAT_ID= (SELECT GAJI_RIWAYAT_BARU_ID FROM PENSIUN WHERE PERIODE= '".$this->getField("PERIODE")."' AND PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").")
				"; 
		$this->query = $str2;
		//echo $str2;exit;
		$this->execQuery($str2);
		
		$str3= "
		SELECT PROSESPENSIUNPEGAWAI('".$this->getField("PERIODE")."', ".$this->getField("PEGAWAI_ID").", '".$this->getField("LAST_USER")."', ".$this->getField("LAST_LEVEL").")
		"; 
		$this->query = $str3;
		//echo $str3;exit;
        return $this->execQuery($str3);
		//echo $str;exit;
    }
	
	function insertGajiRiwayat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("GAJI_RIWAYAT_ID", $this->getNextId("GAJI_RIWAYAT_ID","GAJI_RIWAYAT"));
     	$str = "
			INSERT INTO GAJI_RIWAYAT (
				GAJI_RIWAYAT_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, PANGKAT_ID, NO_SK,
				TANGGAL_SK, TMT_SK, MASA_KERJA_TAHUN, MASA_KERJA_BULAN, GAJI_POKOK, JENIS_KENAIKAN, LAST_USER, LAST_DATE, LAST_LEVEL
			) 
			VALUES (
				  (
				  SELECT 
				  A.GAJI_RIWAYAT_BARU_ID
				  FROM PENSIUN A
				  WHERE A.STATUS_KGB = '3' AND A.PERIODE= '".$this->getField("PERIODE")."' AND A.PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				  ),
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PEJABAT_PENETAP_ID").",
				  '".$this->getField("PEJABAT_PENETAP")."',
				  ".$this->getField("PANGKAT_ID").",
				  (
				  SELECT 
				  CONCAT(SPLIT_PART(B.NOMOR, '/', 1), '/', SPLIT_PART(B.NOMOR, '/', 2), '.', CAST(A.NOMOR_URUT AS TEXT), '/', SPLIT_PART(B.NOMOR, '/', 3), '/', SPLIT_PART(B.NOMOR, '/', 4))
				  FROM PENSIUN A
				  INNER JOIN PERSURATAN.SURAT_KELUAR_BKD B ON A.SURAT_KELUAR_BKD_ID = B.SURAT_KELUAR_BKD_ID
				  WHERE A.STATUS_KGB = '3' AND A.PERIODE= '".$this->getField("PERIODE")."' AND A.PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				  ),
				  ".$this->getField("TANGGAL_SK").",
				  ".$this->getField("TMT_SK").",
				  ".$this->getField("MASA_KERJA_TAHUN").",
				  ".$this->getField("MASA_KERJA_BULAN").",
				  ".$this->getField("GAJI_POKOK").",
				  ".$this->getField("JENIS_KENAIKAN").",
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL")."
			)
		";
		
		$this->id = $this->getField("GAJI_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateGajiRiwayat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_RIWAYAT
				SET    
				  PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				  PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				  PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				  PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
				  NO_SK= (
				  SELECT 
				  CONCAT(SPLIT_PART(B.NOMOR, '/', 1), '/', SPLIT_PART(B.NOMOR, '/', 2), '.', CAST(A.NOMOR_URUT AS TEXT), '/', SPLIT_PART(B.NOMOR, '/', 3), '/', SPLIT_PART(B.NOMOR, '/', 4))
				  FROM PENSIUN A
				  INNER JOIN PERSURATAN.SURAT_KELUAR_BKD B ON A.SURAT_KELUAR_BKD_ID = B.SURAT_KELUAR_BKD_ID
				  WHERE A.STATUS_KGB = '3' AND A.PERIODE= '".$this->getField("PERIODE")."' AND A.PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
				  ),
				  TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				  TMT_SK= ".$this->getField("TMT_SK").",
				  MASA_KERJA_TAHUN= ".$this->getField("MASA_KERJA_TAHUN").",
				  MASA_KERJA_BULAN= ".$this->getField("MASA_KERJA_BULAN").",
				  GAJI_POKOK= ".$this->getField("GAJI_POKOK").",
				  JENIS_KENAIKAN= ".$this->getField("JENIS_KENAIKAN").",
				  LAST_USER= '".$this->getField("LAST_USER")."',
				  LAST_DATE= ".$this->getField("LAST_DATE").",
				  LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  GAJI_RIWAYAT_ID = ".$this->getField("GAJI_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatusGajiRiwayat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE GAJI_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  GAJI_RIWAYAT_ID    	= ".$this->getField("GAJI_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT 
					A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.JENIS
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, PF.PATH, A.TMT
					, COALESCE(SP.KONDISI_PILIH,0) KONDISI_PILIH, COALESCE(SP.KONDISI_NAMA, 'Belum di usulkan') KONDISI_NAMA
				FROM 
				(
					SELECT P.JENIS, P.PEGAWAI_ID, P.PEGAWAI_STATUS_ID, P.JABATAN_RIWAYAT_ID, P.PANGKAT_RIWAYAT_ID, P.GAJI_RIWAYAT_ID, P.TMT, A.SATUAN_KERJA_ID
					, P.STATUS_PENSIUN, A.NIP_LAMA, A.NIP_BARU, A.GELAR_DEPAN, A.NAMA, A.GELAR_BELAKANG
					FROM PENSIUN P
					INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = P.PEGAWAI_ID
				) A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM PEGAWAI_FILE_DATA WHERE RIWAYAT_TABLE = 'PEGAWAI' AND RIWAYAT_ID = 1) PF ON PF.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID, STATUS_BERKAS, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID,
					CASE
					WHEN SURAT_MASUK_UPT_ID IS NOT NULL AND STATUS_BERKAS = 1 THEN 'Usulan'
					WHEN SURAT_MASUK_UPT_ID IS NOT NULL AND STATUS_BERKAS > 1 THEN 'Proses'
					WHEN SURAT_MASUK_BKD_ID IS NOT NULL AND SURAT_MASUK_UPT_ID IS NULL AND STATUS_BERKAS = 4 THEN 'Usulan'
					WHEN SURAT_MASUK_BKD_ID IS NOT NULL AND SURAT_MASUK_UPT_ID IS NULL AND STATUS_BERKAS > 4 THEN 'Proses'
					ELSE 'Belum Proses' END KONDISI_NAMA,
					CASE
					WHEN SURAT_MASUK_UPT_ID IS NOT NULL AND STATUS_BERKAS > 1 THEN 1
					WHEN SURAT_MASUK_BKD_ID IS NOT NULL AND SURAT_MASUK_UPT_ID IS NULL AND STATUS_BERKAS > 4 THEN 1
					ELSE 0 END KONDISI_PILIH
					FROM persuratan.SURAT_MASUK_PEGAWAI WHERE JENIS_ID = 7
				) SP ON SP.PEGAWAI_ID = A.PEGAWAI_ID
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT
			A.PERIODE, A.PEGAWAI_ID, A.PEGAWAI_STATUS_ID, A.JABATAN_RIWAYAT_ID, A.PANGKAT_RIWAYAT_LAMA_ID, 
			A.GAJI_RIWAYAT_LAMA_ID, A.PANGKAT_RIWAYAT_BARU_ID, A.GAJI_RIWAYAT_BARU_ID, 
			A.HUKUMAN_RIWAYAT_ID, A.SURAT_KELUAR_BKD_ID, A.NOMOR_URUT, A.KETERANGAN_TEKNIS, A.TANGGAL_BARU, A.TMT_BARU, A.MASA_KERJA_TAHUN_BARU, 
			A.MASA_KERJA_BULAN_BARU, A.GAJI_BARU, A.SATUAN_KERJA_ID, A.STATUS_KGB, 
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
	
	function selectByParamsInfoTotal($periode="", $statement='')
	{
		$str = "
		SELECT
		(SELECT COUNT(1) FROM PENSIUN A WHERE 1=1 AND A.PERIODE = '".$periode."' ".$statement." ) JUMLAH_DATA_KGB
		, (SELECT COUNT(1) FROM PENSIUN A WHERE 1=1 AND A.STATUS_KGB = '2' AND A.PERIODE = '".$periode."' ".$statement." ) JUMLAH_DATA_KGB_PROSES
		, (SELECT COUNT(1) FROM PENSIUN A WHERE 1=1 AND A.STATUS_KGB = '3' AND A.PERIODE = '".$periode."' ".$statement." ) JUMLAH_DATA_KGB_SELESAI
		, (SELECT COUNT(1) FROM PENSIUN A WHERE 1=1 AND A.HUKUMAN_RIWAYAT_ID IS NOT NULL AND A.PERIODE = '".$periode."' ".$statement." ) JUMLAH_DATA_KGB_HUKUMAN
		"; 
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }
	
	function selectByParamsSuratKeluarData($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_BKD_ID ASC')
	{
		$str = "
		SELECT
			A.SURAT_KELUAR_BKD_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID
			, A.SURAT_MASUK_PEGAWAI_ID, A.NOMOR
			, A.NOMOR_AWAL, A.NOMOR_URUT
			, A.TANGGAL, A.KEPADA, A.PERIHAL, A.IS_MANUAL, A.SATUAN_KERJA_TUJUAN_ID
			, A.SATUAN_KERJA_TUJUAN_NAMA, A.SATUAN_KERJA_TEKNIS_ID, A.SATUAN_KERJA_TERIMA_SURAT_ID
			, A.STATUS_TERIMA, A.STATUS_KIRIM_TEKNIS, A.TANDA_TANGAN_BKD_ID, A.PERIODE
			, CASE A.STATUS_TERIMA WHEN '1' THEN 'Di setujui' ELSE 'Belum di setujui' END STATUS_TERIMA_NAMA
		FROM persuratan.SURAT_KELUAR_BKD A
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
	
	function selectByParamsHitungGajiRiwayat($statement='')
	{
		$str = "
		SELECT
			GAJI_RIWAYAT_ID, PEGAWAI_ID, TMT_PERIODE_KGB, MASA_KERJA_TAHUN_BARU, MASA_KERJA_BULAN_BARU
			, TAHUN_KGB, BULAN_KGB, JENIS_KENAIKAN, GAJI_BARU, CONCAT(BULAN_KGB, TAHUN_KGB) PERIODE
		FROM GAJI_TERAKHIR_ID_PERIODE A
		WHERE 1=1
		";
		$str .= $statement;
		//echo $str;exit;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
	}
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.PANGKAT_RIWAYAT_LAMA_ID, A.GAJI_RIWAYAT_LAMA_ID, A.PANGKAT_RIWAYAT_BARU_ID, A.GAJI_RIWAYAT_BARU_ID, A.JABATAN_RIWAYAT_ID
			, A.HUKUMAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, A.PERIODE, P.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, PS.PEGAWAI_STATUS_NAMA, PS.PEGAWAI_KEDUDUKAN_NAMA
			, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) PEGAWAI_SATUAN_KERJA_NAMA_DETIL
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
			, PR1.NO_SK NO_SK_LAMA, PR1.TANGGAL_SK, PR1.TMT_SK TMT_LAMA, PR1.JENIS_KENAIKAN_NAMA, PR1.PANGKAT_ID, PR1.PANGKAT_KODE
			, PR1.MASA_KERJA_TAHUN MASA_KERJA_TAHUN_LAMA, PR1.MASA_KERJA_BULAN MASA_KERJA_BULAN_LAMA, PR1.GAJI_POKOK GAJI_LAMA
			, PR1.PEJABAT_PENETAP_ID PEJABAT_PENETAP_ID_LAMA, PR1.PEJABAT_PENETAP_NAMA PEJABAT_PENETAP_LAMA
			, A.TMT_BARU, A.MASA_KERJA_TAHUN_BARU, A.MASA_KERJA_BULAN_BARU, A.GAJI_BARU
			, A.TANGGAL_BARU, SK.NOMOR_AWAL
			, A.SURAT_KELUAR_BKD_ID, A.NOMOR_URUT, A.KETERANGAN_TEKNIS, SK.PERIODE
			, HK.KETERANGAN_HUKUMAN_INFO, HK.TMT_BERIKUT_GAJI, HK.TANGGAL_SK HUKUMAN_TANGGAL_SK, HK.NO_SK HUKUMAN_NO_SK
			, '' JENIS_KGB, A.STATUS_KGB, CASE A.STATUS_KGB WHEN '2' THEN 'Dalam Proses' WHEN '3' THEN 'Selesai' WHEN '4' THEN 'Batal' ELSE '' END STATUS_KGB_NAMA
			, A.STATUS_HITUNG_ULANG
		FROM PENSIUN A
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN GAJI_RIWAYAT_DATA PR1 ON A.GAJI_RIWAYAT_LAMA_ID = PR1.GAJI_RIWAYAT_ID
		LEFT JOIN 
		(
			SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
			, A.TMT PEGAWAI_KEDUDUKAN_TMT, C.NAMA PEGAWAI_KEDUDUKAN_NAMA
			FROM PEGAWAI_STATUS A
			INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
			INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
		) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
		LEFT JOIN PERSURATAN.SURAT_KELUAR_BKD SK ON A.SURAT_KELUAR_BKD_ID = SK.SURAT_KELUAR_BKD_ID
		LEFT JOIN
		(
			SELECT 	
				A.HUKUMAN_ID, A.PEGAWAI_ID, B.NAMA TINGKAT_HUKUMAN_NAMA, C.NAMA JENIS_HUKUMAN_NAMA
				, A.NO_SK, A.TANGGAL_SK, A.TMT_SK, A.TMT_BERIKUT_GAJI
				, CONCAT(B.NAMA, ' : ', C.NAMA) KETERANGAN_HUKUMAN_INFO
			FROM HUKUMAN A
			LEFT JOIN TINGKAT_HUKUMAN B ON B.TINGKAT_HUKUMAN_ID = A.TINGKAT_HUKUMAN_ID
			LEFT JOIN JENIS_HUKUMAN C ON C.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID
		) HK ON A.HUKUMAN_RIWAYAT_ID = HK.HUKUMAN_ID
		WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		//echo $str;exit;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.PERIODE, A.SURAT_KELUAR_BKD_ID,
			(CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END) NAMA_LENGKAP
			, P.NIP_BARU, PR1.PANGKAT_KODE, PR1.TMT_SK TMT_LAMA
			, PR1.MASA_KERJA_TAHUN MASA_KERJA_TAHUN_LAMA, PR1.MASA_KERJA_BULAN MASA_KERJA_BULAN_LAMA, PR1.GAJI_POKOK GAJI_LAMA
			, A.TMT_BARU, A.MASA_KERJA_TAHUN_BARU, A.MASA_KERJA_BULAN_BARU, A.GAJI_BARU
			, CASE WHEN PR1.JENIS_KENAIKAN = 1 AND A.HUKUMAN_RIWAYAT_ID IS NULL THEN 'CPNS' WHEN HK.JENIS_HUKUMAN_ID = 4 THEN 'Penundaan' WHEN A.HUKUMAN_RIWAYAT_ID IS NOT NULL THEN 'Mundur' ELSE 'Normal' END
			JENIS_KGB, A.STATUS_KGB, CASE A.STATUS_KGB WHEN '2' THEN 'Dalam Proses' WHEN '3' THEN 'Selesai' WHEN '4' THEN 'Batal' ELSE '' END STATUS_KGB_NAMA
		FROM PENSIUN A
		INNER JOIN PEGAWAI P ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN GAJI_RIWAYAT_DATA PR1 ON A.GAJI_RIWAYAT_LAMA_ID = PR1.GAJI_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID, A.HUKUMAN_ID, A.JENIS_HUKUMAN_ID FROM HUKUMAN A
		) HK ON HK.HUKUMAN_ID = A.HUKUMAN_RIWAYAT_ID
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
	
	function selectByParamsSuamiIstri($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '3' THEN 'Meninggal' ELSE 'Belum di set' END STATUS_S_I_NAMA
			, A.*
		FROM SUAMI_ISTRI A
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

    function selectByParamsSuamiIstriAnak($paramsArray=array(),$limit=-1,$from=-1, $kategori= '', $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT DISTINCT
			A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.TANGGAL_KAWIN, A.KARTU, 
			A.STATUS_PNS, A.NIP_PNS, A.PEKERJAAN, A.STATUS_TUNJANGAN, A.STATUS_BAYAR, A.BULAN_BAYAR, A.STATUS, A.STATUS_S_I, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '3' THEN 'Meninggal' ELSE 'Belum di set' END STATUS_S_I_NAMA
			, A.SURAT_NIKAH, A.NIK, A.CERAI_SURAT, A.CERAI_TANGGAL, A.CERAI_TMT, A.KEMATIAN_SURAT, A.KEMATIAN_TANGGAL, A.KEMATIAN_TMT
		FROM SUAMI_ISTRI A
		INNER JOIN 
		(
			SELECT P.PEGAWAI_ID, TMT, JENIS FROM PENSIUN P WHERE REPLACE(JENIS, 'pensiun', '') = '".$kategori."'
		) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
		INNER JOIN ANAK B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		WHERE 1 = 1 AND B.STATUS_AKTIF = '1' AND CAST(AMBIL_UMUR_TAHUN(B.TANGGAL_LAHIR, P.TMT) AS NUMERIC) <= 25
		"; 
		// JENIS = '".$kategori."'
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }

	function selectByParamsAnak($pegawaiid="", $kategori="", $statement='',$order=' ORDER BY CAST(A.ANAK_USIA AS NUMERIC) DESC')
	{
		$str = "
		SELECT 
			A.*
		FROM
		(
			SELECT 
				ROW_NUMBER () OVER (ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC) NOMOR
				, AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) ANAK_USIA
				, P.TMT, B.NAMA SUAMI_ISTRI_NAMA
				, CASE B.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '2' THEN 'Meninggal' ELSE 'Belum di set' END SUAMI_ISTRI_STATUS_NAMA
				, CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' ELSE 'Angkat' END ANAK_STATUS_NAMA
				, A.*
			FROM 
			(
				SELECT P.PEGAWAI_ID, TMT, JENIS FROM PENSIUN P WHERE REPLACE(JENIS, 'pensiun', '') = '".$kategori."'
			) P
			INNER JOIN ANAK A ON P.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
			WHERE 1=1 --AND A.STATUS_AKTIF = '1' AND A.STATUS_KELUARGA = '1'
			AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			AND CAST(AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) AS NUMERIC) < 25
			AND P.PEGAWAI_ID = ".$pegawaiid."
			ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC
		) A
		WHERE 1=1
		"; 
		// <= 25
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsTambahanMasaKerja($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sOrder= "ORDER BY TANGGAL_SK ASC")
	{
		$str = "
		SELECT
			TAMBAHAN_MASA_KERJA_ID, PEGAWAI_ID, NO_SK, TANGGAL_SK, TMT_SK, TAHUN_TAMBAHAN, 
			BULAN_TAMBAHAN, TAHUN_BARU, BULAN_BARU, STATUS
		FROM TAMBAHAN_MASA_KERJA A
		WHERE 1=1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$str .= $statement." ".$sOrder;
				
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
				FROM 
				(
				SELECT P.JENIS, P.PEGAWAI_ID, P.PEGAWAI_STATUS_ID, P.JABATAN_RIWAYAT_ID, P.PANGKAT_RIWAYAT_ID, P.GAJI_RIWAYAT_ID, P.TMT, A.SATUAN_KERJA_ID
				, P.STATUS_PENSIUN, A.NIP_LAMA, A.NIP_BARU, A.GELAR_DEPAN, A.NAMA, A.GELAR_BELAKANG
				FROM PENSIUN P
				INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = P.PEGAWAI_ID
				) A
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