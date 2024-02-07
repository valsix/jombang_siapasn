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
  
  class JabatanRiwayat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function JabatanRiwayat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_RIWAYAT_ID", $this->getNextId("JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT")); 

     	$str = "
		INSERT INTO JABATAN_RIWAYAT 
		(
			JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID
			, JABATAN_FU_ID, JABATAN_FT_ID
			, ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT
			, IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN, TMT_ESELON
			, TMT_SPMT, STATUS_SK_DASAR_JABATAN, TMT_SELESAI_JABATAN, LAMA_JABATAN, NILAI_REKAM_JEJAK, RUMPUN_ID
			, SERTIFIKASI_NO_SK, SERTIFIKASI_TGL_SK, SERTIFIKASI_TGL_BERLAKU, SERTIFIKASI_TGL_EXPIRED
			, EOFFICE_JABATAN_ID, EOFFICE_JABATAN_NAMA, EOFFICE_SATUAN_KERJA_ID, EOFFICE_SATUAN_KERJA_NAMA
			, BIDANG_JABATAN_TERKAIT_ID
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		)
		VALUES
		(
			".$this->getField("JABATAN_RIWAYAT_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("JENIS_JABATAN_ID")."'
			, ".$this->getField("PEJABAT_PENETAP_ID")."
			, '".$this->getField("PEJABAT_PENETAP")."'
			, ".$this->getField("TIPE_PEGAWAI_ID")."
			, ".$this->getField("JABATAN_FU_ID")."
			, ".$this->getField("JABATAN_FT_ID")."
			, ".$this->getField("ESELON_ID")."
			, '".$this->getField("NO_SK")."'
			, ".$this->getField("TANGGAL_SK")."
			, ".$this->getField("TMT_JABATAN")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("NO_PELANTIKAN")."'
			, ".$this->getField("TANGGAL_PELANTIKAN")."
			, ".$this->getField("TUNJANGAN")."
			, ".$this->getField("KREDIT")."
			, ".$this->getField("IS_MANUAL")."
			, '".$this->getField("SATKER_NAMA")."'
			, ".$this->getField("SATKER_ID")."
			, ".$this->getField("BULAN_DIBAYAR")."
			, ".$this->getField("TMT_BATAS_USIA_PENSIUN")."
			, ".$this->getField("TMT_ESELON")."
			, ".$this->getField("TMT_SPMT")."
			, ".$this->getField("STATUS_SK_DASAR_JABATAN")."
			, ".$this->getField("TMT_SELESAI_JABATAN")."
			, ".$this->getField("LAMA_JABATAN")."
			, ".$this->getField("NILAI_REKAM_JEJAK")."
			, ".$this->getField("RUMPUN_ID")."
			, '".$this->getField("SERTIFIKASI_NO_SK")."'
			, ".$this->getField("SERTIFIKASI_TGL_SK")."
			, ".$this->getField("SERTIFIKASI_TGL_BERLAKU")."
			, ".$this->getField("SERTIFIKASI_TGL_EXPIRED")."
			, ".$this->getField("EOFFICE_JABATAN_ID")."
			, '".$this->getField("EOFFICE_JABATAN_NAMA")."'
			, ".$this->getField("EOFFICE_SATUAN_KERJA_ID")."
			, '".$this->getField("EOFFICE_SATUAN_KERJA_NAMA")."'
			, ".$this->getField("BIDANG_JABATAN_TERKAIT_ID")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("LAST_LEVEL")."
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->id = $this->getField("JABATAN_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE JABATAN_RIWAYAT
		SET
		PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
		, JENIS_JABATAN_ID= '".$this->getField("JENIS_JABATAN_ID")."'
		, PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID")."
		, PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."'
		, TIPE_PEGAWAI_ID= ".$this->getField("TIPE_PEGAWAI_ID")."
		, JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID")."
		, JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID")."
		, ESELON_ID= ".$this->getField("ESELON_ID")."
		, NO_SK= '".$this->getField("NO_SK")."'
		, TANGGAL_SK= ".$this->getField("TANGGAL_SK")."
		, TMT_JABATAN= ".$this->getField("TMT_JABATAN")."
		, NAMA= '".$this->getField("NAMA")."'
		, NO_PELANTIKAN= '".$this->getField("NO_PELANTIKAN")."'
		, TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN")."
		, TUNJANGAN= ".$this->getField("TUNJANGAN")."
		, KREDIT= ".$this->getField("KREDIT")."
		, IS_MANUAL= ".$this->getField("IS_MANUAL")."
		, SATKER_NAMA= '".$this->getField("SATKER_NAMA")."'
		, SATKER_ID= ".$this->getField("SATKER_ID")."
		, BULAN_DIBAYAR= ".$this->getField("BULAN_DIBAYAR")."
		, TMT_BATAS_USIA_PENSIUN= ".$this->getField("TMT_BATAS_USIA_PENSIUN")."
		, TMT_ESELON= ".$this->getField("TMT_ESELON")."
		, TMT_SPMT= ".$this->getField("TMT_SPMT")."
		, STATUS_SK_DASAR_JABATAN= ".$this->getField("STATUS_SK_DASAR_JABATAN")."
		, TMT_SELESAI_JABATAN= ".$this->getField("TMT_SELESAI_JABATAN")."
		, LAMA_JABATAN= ".$this->getField("LAMA_JABATAN")."
		, NILAI_REKAM_JEJAK= ".$this->getField("NILAI_REKAM_JEJAK")."
		, RUMPUN_ID= ".$this->getField("RUMPUN_ID")."
		, SERTIFIKASI_NO_SK= '".$this->getField("SERTIFIKASI_NO_SK")."'
		, SERTIFIKASI_TGL_SK= ".$this->getField("SERTIFIKASI_TGL_SK")."
		, SERTIFIKASI_TGL_BERLAKU= ".$this->getField("SERTIFIKASI_TGL_BERLAKU")."
		, SERTIFIKASI_TGL_EXPIRED= ".$this->getField("SERTIFIKASI_TGL_EXPIRED")."
		, EOFFICE_JABATAN_ID= ".$this->getField("EOFFICE_JABATAN_ID")."
		, EOFFICE_JABATAN_NAMA= '".$this->getField("EOFFICE_JABATAN_NAMA")."'
		, EOFFICE_SATUAN_KERJA_ID= ".$this->getField("EOFFICE_SATUAN_KERJA_ID")."
		, EOFFICE_SATUAN_KERJA_NAMA= '".$this->getField("EOFFICE_SATUAN_KERJA_NAMA")."'
		, BIDANG_JABATAN_TERKAIT_ID= ".$this->getField("BIDANG_JABATAN_TERKAIT_ID")."
		, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_DATE= ".$this->getField("LAST_DATE")."
		, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertDataBkn()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_RIWAYAT_ID", $this->getNextId("JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT")); 

     	$str = "
		INSERT INTO JABATAN_RIWAYAT 
		(
			JABATAN_RIWAYAT_ID,ESELON_ID, PEGAWAI_ID, JENIS_JABATAN_ID, TIPE_PEGAWAI_ID
			, JABATAN_FU_ID, JABATAN_FT_ID, NO_SK,SATKER_NAMA, NAMA, SATKER_ID, TANGGAL_SK
			, TMT_JABATAN, TMT_ESELON, TANGGAL_PELANTIKAN,IS_MANUAL
		)
		VALUES
		(
			".$this->getField("JABATAN_RIWAYAT_ID")."
			, ".$this->getField("ESELON_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, '".$this->getField("JENIS_JABATAN_ID")."'
			, ".$this->getField("TIPE_PEGAWAI_ID")."
			, ".$this->getField("JABATAN_FU_ID")."
			, ".$this->getField("JABATAN_FT_ID")."
			, '".$this->getField("NO_SK")."'
				, '".$this->getField("SATKER_NAMA")."'
			, '".$this->getField("NAMA")."'
			, ".$this->getField("SATKER_ID")."
			, ".$this->getField("TANGGAL_SK")."
			, ".$this->getField("TMT_JABATAN")."
			, ".$this->getField("TMT_ESELON")."
			, ".$this->getField("TANGGAL_PELANTIKAN")."
			, ".$this->getField("IS_MANUAL")."
		)
		"; 	
		$this->id = $this->getField("JABATAN_RIWAYAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateBknData()
	{
		$str = "		
		UPDATE JABATAN_RIWAYAT
		SET
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
			, ESELON_ID= ".$this->getField("ESELON_ID")."
			, JENIS_JABATAN_ID= '".$this->getField("JENIS_JABATAN_ID")."'
			, TIPE_PEGAWAI_ID= ".$this->getField("TIPE_PEGAWAI_ID")."
			, JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID")."
			, JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID")."
			, NO_SK= '".$this->getField("NO_SK")."'
			, SATKER_NAMA= '".$this->getField("SATKER_NAMA")."'
			, NAMA= '".$this->getField("NAMA")."'
			, SATKER_ID= ".$this->getField("SATKER_ID")."
			, TANGGAL_SK= ".$this->getField("TANGGAL_SK")."
			, TMT_JABATAN= ".$this->getField("TMT_JABATAN")."
			, TMT_ESELON= ".$this->getField("TMT_ESELON")."
			, TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN")."
			, IS_MANUAL= ".$this->getField("IS_MANUAL")."
		WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateIdSapk()
    {
		$str = "		
		UPDATE JABATAN_RIWAYAT
		SET    
		
		 	 ID_SAPK= '".$this->getField("ID_SAPK")."'
	
		WHERE  JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
		"; 
		$this->query = $str;
		// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
				UPDATE JABATAN_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_RIWAYAT_ID    	= ".$this->getField("JABATAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
       $str = "
				UPDATE JABATAN_RIWAYAT SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
       $str = "
				DELETE FROM JABATAN_RIWAYAT
				WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
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

    function selectdata($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
		SELECT
			B.NAMA ESELON_NAMA, R.KETERANGAN RUMPUN_NAMA
			, A.*
		FROM jabatan_riwayat A
		INNER JOIN eselon B ON A.ESELON_ID = B.ESELON_ID
		LEFT JOIN talent.rumpun R ON A.RUMPUN_ID = R.RUMPUN_ID
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

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
		SELECT
		A.*
		, CASE WHEN LAMA_JABATAN_HITUNG >= 5 THEN 100
		WHEN LAMA_JABATAN_HITUNG >= 4 THEN 80
		WHEN LAMA_JABATAN_HITUNG >= 3 THEN 60
		WHEN LAMA_JABATAN_HITUNG >= 2 THEN 40
		WHEN LAMA_JABATAN_HITUNG >= 1 THEN 20
		ELSE 0 END NILAI_REKAM_JEJAK_HITUNG
		, B.NAMA ESELON_NAMA, COALESCE(HK.HUKUMAN_ID,0) DATA_HUKUMAN
		, CASE A.JENIS_JABATAN_ID WHEN '1' THEN 'Jabatan Struktural' WHEN '2' THEN 'Jabatan Fungsional Umum' WHEN '3' THEN 'Jabatan Fungsional Tertentu' END JENIS_JABATAN_NAMA
		--, F.PATH FILE_UPLOAD
		, CASE 
		WHEN A.SATKER_ID IS NULL OR A.SATKER_ID = -1 THEN
		A.SATKER_NAMA 
		ELSE AMBIL_SATKER_NAMA_DETIL(A.SATKER_ID) 
		END SATUAN_KERJA_NAMA_DETIL
		, R.KETERANGAN RUMPUN_NAMA,
			CASE 
			WHEN A.SATKER_ID IS NULL THEN
			A.SATKER_NAMA 
			ELSE ambil_satker_nama(A.SATKER_ID) 
			END SATUAN_KERJA_NAMA,C.PEGAWAI_ID_SAPK
		FROM
		(
			SELECT A.*
			, CASE WHEN A.TMT_SELESAI_JABATAN IS NULL THEN
				/*
				 * CASE WHEN
				(datediff('month', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD')) / 12) > 2
				THEN 1 ELSE 0
				END
				+
				datediff('year', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD'))
				*/
				datediff('year', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD'))
				ELSE A.LAMA_JABATAN
			END LAMA_JABATAN_HITUNG
			,
			CASE WHEN A.TMT_SELESAI_JABATAN IS NULL THEN
				(
					datediff('month', TO_DATE(TO_CHAR(A.TMT_JABATAN, 'YYYY-MM-DD'), 'YYYY-MM-DD'), TO_DATE(TO_CHAR(NOW(), 'YYYY-MM-DD'), 'YYYY-MM-DD'))
				)::integer % 12::integer
				ELSE A.LAMA_JABATAN_BULAN
			END LAMA_JABATAN_BULAN_HITUNG
			FROM JABATAN_RIWAYAT A
		) A
		LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		LEFT JOIN HUKUMAN HK ON A.JABATAN_RIWAYAT_ID = HK.JABATAN_RIWAYAT_ID
		LEFT JOIN talent.rumpun R ON A.RUMPUN_ID = R.RUMPUN_ID
		LEFT JOIN PEGAWAI C ON C.PEGAWAI_ID = A.PEGAWAI_ID
		--LEFT JOIN pegawai_file F ON A.JABATAN_RIWAYAT_ID = F.RIWAYAT_ID and a.pegawai_Id=f.pegawai_id AND COALESCE(NULLIF(f.STATUS, ''), NULL) IS NULL
		WHERE 1 = 1
		";

		/*A.JABATAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.TIPE_PEGAWAI_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, 
		A.ESELON_ID, A.NO_SK, A.TANGGAL_SK, A.TMT_JABATAN, A.NAMA, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.KREDIT, A.SATKER_ID
		, 
		CASE 
		WHEN A.SATKER_ID IS NULL THEN
		A.SATKER_NAMA 
		ELSE AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) 
		END SATUAN_KERJA_NAMA_DETILbak
		, A.JENIS_JABATAN_ID
		, A.IS_MANUAL, A.BULAN_DIBAYAR, A.TMT_BATAS_USIA_PENSIUN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		, A.TMT_ESELON
		, A.STATUS_SK_DASAR_JABATAN
		, A.TMT_SELESAI_JABATAN
		, A.LAMA_JABATAN
		, A.NILAI_REKAM_JEJAK
		, A.RUMPUN_ID
		, A.SERTIFIKASI_NO_SK
		, A.SERTIFIKASI_TGL_SK
		, A.SERTIFIKASI_TGL_BERLAKU
		, A.SERTIFIKASI_TGL_EXPIRED
		, A.EOFFICE_JABATAN_ID
		, A.EOFFICE_JABATAN_NAMA
		, A.EOFFICE_SATUAN_KERJA_ID
		, A.EOFFICE_SATUAN_KERJA_NAMA
		, A.TMT_SPMT*/
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.JABATAN_RIWAYAT_ID) AS ROWCOUNT 
				FROM JABATAN_RIWAYAT A
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