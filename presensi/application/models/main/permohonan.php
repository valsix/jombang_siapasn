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

class Permohonan extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function Permohonan()
	{
	  $this->Entity(); 
	}

	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP, A.JABATAN_RIWAYAT_NAMA
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
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

	function getCountByParamsPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
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

	function selectByParamsPermohonan($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		, P.NAMA_PERMOHONAN, P.PERMOHONAN_CUTI_ID, P.REVISI, P.NOMOR, P.NAMA_IJIN_KOREKSI, P.TANGGAL
		, TO_DATE(TO_CHAR(P.TANGGAL_AWAL, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS') TANGGAL_AWAL
		, TO_DATE(TO_CHAR(P.TANGGAL_AKHIR, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS') TANGGAL_AKHIR
		, P.JUMLAH_HARI, P.STATUS, P.POSTING, P.LINK_FILE, P.LINK_FILE_CETAK
		, P.PEGAWAI_ID_APPROVAL0, P.PEGAWAI_ID_APPROVAL1, P.PEGAWAI_ID_APPROVAL2
		, P.APPROVAL0 , P.APPROVAL1, P.APPROVAL2, P.STATUS_MASUK, P.STATUS_PULANG, P.KETERANGAN
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
		INNER JOIN presensi.PERMOHONAN_ALL P ON CAST(P.PEGAWAI_ID AS NUMERIC) = A.PEGAWAI_ID
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

	function getCountByParamsPermohonan($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
		INNER JOIN presensi.PERMOHONAN_ALL P ON CAST(P.PEGAWAI_ID AS NUMERIC) = A.PEGAWAI_ID
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

	function selectByParamsPermohonanLupaAbsen($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
			, P.TANGGAL, P.TANGGAL_IJIN, P.KETERANGAN
			, CASE 
			WHEN COALESCE(NULLIF(P.STATUS_MASUK, ''), '0') = '1' AND COALESCE(NULLIF(P.STATUS_PULANG, ''), '0') = '1' THEN 'MASUK & PULANG'
			WHEN COALESCE(NULLIF(P.STATUS_MASUK, ''), '0') = '1' AND COALESCE(NULLIF(P.STATUS_PULANG, ''), '0') = '0' THEN 'MASUK'
			WHEN COALESCE(NULLIF(P.STATUS_MASUK, ''), '0') = '0' AND COALESCE(NULLIF(P.STATUS_PULANG, ''), '0') = '1' THEN 'PULANG'
			END LUPA_ABSEN_JENIS
			, CASE
			WHEN P.APPROVAL1 = 'Y' AND P.APPROVAL2 = 'Y' THEN 'Disetujui'
			WHEN P.APPROVAL1 = 'T' OR P.APPROVAL2 = 'T' THEN 'Ditolak'
			WHEN COALESCE(NULLIF(P.APPROVAL1, ''), 'X') = 'X' THEN 'Menunggu Persetujuan ' || D.NAMA || ' (' || P.PEGAWAI_ID_APPROVAL1 || ')'
			WHEN COALESCE(NULLIF(P.APPROVAL2, ''), 'X') = 'X' THEN 'Menunggu Persetujuan ' || E.NAMA || ' (' || P.PEGAWAI_ID_APPROVAL2 || ')'
			ELSE 'Proses' END STATUS
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
		INNER JOIN presensi.PERMOHONAN_LUPA_ABSEN P ON CAST(P.PEGAWAI_ID AS NUMERIC) = A.PEGAWAI_ID
		LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = CAST(P.PEGAWAI_ID_APPROVAL1 AS NUMERIC)
		LEFT JOIN PEGAWAI E ON E.PEGAWAI_ID = CAST(P.PEGAWAI_ID_APPROVAL2 AS NUMERIC)
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

	function getCountByParamsPermohonanLupaAbsen($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM 
		(
			SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			FROM PEGAWAI A
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
			WHERE 1 = 1
		) A 
		INNER JOIN presensi.PERMOHONAN_LUPA_ABSEN P ON CAST(P.PEGAWAI_ID AS NUMERIC) = A.PEGAWAI_ID
		LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = CAST(P.PEGAWAI_ID_APPROVAL1 AS NUMERIC)
		LEFT JOIN PEGAWAI E ON E.PEGAWAI_ID = CAST(P.PEGAWAI_ID_APPROVAL2 AS NUMERIC)
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

} 
?>