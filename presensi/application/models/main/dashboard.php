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
include_once(APPPATH.'/models/Entity.php');

class Dashboard extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function Dashboard()
	{
	  $this->Entity(); 
	}


	function selectByParamsPresensiAbsenHome($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
		, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
		, TO_CHAR(PA.JAM, 'YYYY-MM-DD') TANGGAL, TO_CHAR(PA.JAM, 'HH24:MI:SS') WAKTU
		, CASE PA.TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Siang' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END TIPE_PRESENSI
		, CASE PA.VERIFY_TYPE WHEN 1 THEN 'Jari' WHEN 15 THEN 'Wajah' ELSE '-' END TIPE_LOG
		, PA.TERMINAL_ALIAS MESIN_PRESENSI, TO_CHAR(PA.UPLOAD_TIME, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_DATA_MASUK
		FROM pinfoakhir() P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'DDMMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
		WHERE 1=1
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

	function selectByParamsPresensiTerlambat($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $hari='', $order='')
	{
		$str = "
		SELECT A.PEGAWAI_ID,B.NIP_BARU,B.NAMA, A.JAM_MASUK_".$hari.",A.TERLAMBAT_".$hari." FROM pinfoperioderekap('".$periode."') A
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 
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

	function selectByParamsPresensiTerlambatBerjalan($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $hari='',$perioderekap='', $order='')
	{
		$str = "
		SELECT A.PEGAWAI_ID,A.NIP_BARU,A.NAMA_LENGKAP, B.JAM_MASUK_".$hari.",B.TERLAMBAT_".$hari." FROM pinfoberjalan('".$periode."') A
		INNER JOIN pinfoperioderekap ('".$perioderekap."') B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
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



	function selectByParamsPresensiPulangCepat($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $hari='', $order='')
	{
		$str = "
		SELECT A.PEGAWAI_ID,B.NIP_BARU,B.NAMA, A.JAM_MASUK_".$hari.",A.PULANG_CEPAT_".$hari." FROM pinfoperioderekap('".$periode."') A
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 
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

	function selectByParamsPresensiPulangCepatBerjalan($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $hari='',$perioderekap='', $order='')
	{
		$str = "
		SELECT A.PEGAWAI_ID,A.NIP_BARU,A.NAMA_LENGKAP, B.JAM_PULANG_".$hari.",B.PULANG_CEPAT_".$hari." FROM pinfoberjalan('".$periode."') A
		INNER JOIN pinfoperioderekap ('".$perioderekap."') B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
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

	function getCountByParamsCuti($paramsArray=array(), $satuankerjakondisi, $statement='',$statementnew='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT
			, TO_CHAR(A.TANGGAL_PERMOHONAN, 'YYYY-MM-DD') TANGGAL_PERMOHONAN
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI
			, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.LAMA, A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			, CASE A.JENIS_CUTI 
			WHEN 1 THEN 'Cuti Tahunan'
			WHEN 2 THEN 'Cuti Besar'
			WHEN 3 THEN 'Cuti Sakit'
			WHEN 4 THEN 'Cuti Bersalin'
			WHEN 5 THEN 'Cuti Alasan Penting'
			WHEN 6 THEN 'Cuti Bersama'
			WHEN 7 THEN 'CLTN'
			ELSE '-' END JENIS_CUTI_NAMA
			FROM CUTI A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 ".$statementnew."
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiAbsen($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pinfoakhir() P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'DDMMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
		WHERE 1=1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiAbsenBulan($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pinfoakhir() P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'MMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
		WHERE 1=1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsDinasLuar($paramsArray=array(), $satuankerjakondisi, $statement='',$statementbulan)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT
			A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD') TANGGAL_SURAT
			, TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD') TANGGAL_MULAI, TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD') TANGGAL_SELESAI
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
			FROM presensi.KLARIFIKASI_DETIL A
			INNER JOIN
			(
				SELECT XXX.PEGAWAI_ID
				FROM PINFOAKHIR() XXX
				WHERE 1=1
				".$satuankerjakondisi."
			) P ON P.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 ".$statementbulan."
			GROUP BY A.KLARIFIKASI_ID, A.JENIS_KLARIFIKASI, A.NOMOR_SURAT
			, TO_CHAR(A.TANGGAL_SURAT, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_MULAI, 'YYYY-MM-DD'), TO_CHAR(A.TANGGAL_SELESAI, 'YYYY-MM-DD')
			, A.STATUS, A.UBAH_STATUS, A.KETERANGAN, A.ALASAN_TOLAK
		) A
		WHERE 1 = 1 ".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPeriodeRekap($paramsArray=array(),  $periode='',$statement)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			select * from pinfoperioderekap('".$periode."')
		)
		A
		LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID 
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPeriodeBerjalan($paramsArray=array(),  $periode='',$statement)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			select * from pinfoberjalan('".$periode."')
		)
		A
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPeriodeBerjalanRekap($paramsArray=array(),  $periode='', $perioderekap='',$statement)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT * FROM pinfoberjalan('".$periode."') A
			INNER JOIN pinfoperioderekap ('".$perioderekap."') B ON B.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1=1 ".$statement."
		)
		A
		WHERE 1=1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiTerlambat($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pinfoperioderekap('".$periode."') A
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiTerlambatBerjalan($paramsArray=array(), $periode, $statement='',$perioderekap)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pinfoberjalan('".$periode."') A
		INNER JOIN pinfoperioderekap ('".$perioderekap."') B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiPulangCepat($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pinfoperioderekap('".$periode."') A
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getCountByParamsPresensiPulangCepatBerjalan($paramsArray=array(), $periode, $statement='',$perioderekap)
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM pinfoberjalan('".$periode."') A
		INNER JOIN pinfoperioderekap ('".$perioderekap."') B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1
		".$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

	function getSatuanKerja($id='')
	{
		$str = "SELECT REPLACE(REPLACE(CAST(AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$id.") AS TEXT), '{',''), '}','') ROWCOUNT";
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow())
		{
			if($this->getField("ROWCOUNT") == "")
			return $id;
			else
			return $id.",".$this->getField("ROWCOUNT"); 
		}
		else 
			return $id;  
	}



} 
?>