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

class PresensiRekap extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function PresensiRekap()
	{
	  $this->Entity(); 
	}

	function setpinfoakhir()
	{
		$str= "
		SELECT 
		A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
		, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
		, A.PANGKAT_RIWAYAT_ID
		, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
		, A.JABATAN_RIWAYAT_ID
		, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
		, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, A.SATUAN_KERJA_ID, A.STATUS_PEGAWAI_ID, A.PEGAWAI_STATUS_ID, PS.PEGAWAI_STATUS_TMT
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
		LEFT JOIN
		(
		SELECT PEGAWAI_STATUS_ID, TMT PEGAWAI_STATUS_TMT FROM PEGAWAI_STATUS
		) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
		WHERE 1 = 1
		";

		return $str;
	}

	function selectpermohonanlog($periode, $pegawaiid)
	{
		$str = "
		select
			pegawai_id, tanggal_info, jam, to_char(tanggal_info, 'ddmmyyyy') infohari
		from presensi.permohonan_log
		where to_char(tanggal_info, 'mmyyyy') = '".$periode."' and pegawai_id::numeric in (".$pegawaiid.")
		group by pegawai_id, tanggal_info, jam order by tanggal_info, jam
		";

		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function setperiodepartisi()
	{
		$str = "SELECT presensi.PARTISITABLE('".$this->getField("PERIODE")."') AS ROWCOUNT ";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }

    function setabsenharilibur()
	{
		$str = "select presensi.pabsenharilibur('".$this->getField("PERIODE")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("JAMKERJAJENIS")."') AS ROWCOUNT ";
		$this->query = $str;
		// echo $str;exit;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }

    function selectByParamsRekapAwalUnitKerja($paramsArray=array(),$limit=-1,$from=-1, $periode, $statementdetil="", $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.SATUAN_KERJA_INFO, P.JABATAN_RIWAYAT_NAMA
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.N_JAM_MASUK_".$i.", K.N_MASUK_".$i.", R.N_JAM_PULANG_".$i.", K.N_PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			";
			// , presensi.ambilpresensiinfo(R.N_JAM_MASUK_".$i.", K.N_MASUK_".$i.", R.N_JAM_PULANG_".$i.", K.N_PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i.") HARIINFO".$i."
		}

		$str .= "
		FROM
		(
			select 
			AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, * from pinfoakhir() P
			where 1=1 ".$statementdetil."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

    function getCountByParamsRekapAwalUnitKerja($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pinfoakhir() P
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

	function selectByParamsNewRekapAwalUnitKerja($paramsArray=array(),$limit=-1,$from=-1, $infotanggaldetil, $periode, $statementdetil="", $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.SATUAN_KERJA_INFO, P.JABATAN_RIWAYAT_NAMA
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.N_JAM_MASUK_".$i.", K.N_MASUK_".$i.", R.N_JAM_PULANG_".$i.", K.N_PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			";
		}

		$str .= "
		FROM
		(
			select 
			AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, * 
			from presensi.pinfoperiode('".$infotanggaldetil."') P
			where 1=1 ".$statementdetil."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

	function getCountByParamsNewRekapAwalUnitKerja($paramsArray=array(), $infotanggaldetil, $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		from presensi.pinfoperiode('".$infotanggaldetil."') P
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

	function selectByParamsRekapAwalPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.JABATAN_RIWAYAT_NAMA
		, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
		FROM pinfoakhir() P
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

    function getCountByParamsRekapAwalPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM pinfoakhir() P
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

	function selectByParamsRekapAwalPegawaiDetil($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
		, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			, R.LEMBUR_JAM_MASUK_".$i.", R.LEMBUR_JAM_PULANG_".$i."
			";
		}
		// , presensi.infologjam(generatezero('".$i."', 1) || '".$periode."', P.PEGAWAI_ID) INFO_LOG_".$i."

/*


		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			, R.LEMBUR_JAM_MASUK_".$i.", K.LEMBUR_MASUK_".$i."
			, presensi.infologjam(generatezero('".$i."', 1) || '".$periode."', P.PEGAWAI_ID) INFO_LOG_".$i."
			";
		}

*/


		$str .= "
		FROM
		(
		".$this->setpinfoakhir()."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

	function selectByParamsHasilKlarifikasi($paramsArray=array(),$limit=-1,$from=-1, $periode, $statementdetil="", $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.SATUAN_KERJA_INFO, P.JABATAN_RIWAYAT_NAMA
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			";
			// , presensi.ambilpresensiinfo(R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i.") HARIINFO".$i."
		}

		$str .= "
		FROM
		(
			select 
			AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, *
			from
			(
			".$this->setpinfoakhir()."
			) P
			where 1=1 ".$statementdetil."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

    function getCountByParamsHasilKlarifikasi($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
		".$this->setpinfoakhir()."
		) P
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

	function selectByParamsNewHasilKlarifikasi($paramsArray=array(),$limit=-1,$from=-1, $infotanggaldetil, $periode, $statementdetil="", $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.SATUAN_KERJA_INFO, P.JABATAN_RIWAYAT_NAMA
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			";
		}

		$str .= "
		FROM
		(
			select 
			AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, * 
			from presensi.pinfoperiode('".$infotanggaldetil."') P
			where 1=1 ".$statementdetil."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

    function getCountByParamsNewHasilKlarifikasi($paramsArray=array(), $infotanggaldetil, $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		from presensi.pinfoperiode('".$infotanggaldetil."') P
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

	function selectByParamsNewTppHasilKlarifikasi($paramsArray=array(),$limit=-1,$from=-1, $infotanggaldetil, $reqStatusTpp, $periode, $statementdetil="", $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP, P.SATUAN_KERJA_INFO, P.JABATAN_RIWAYAT_NAMA
		";

		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, R.JAM_MASUK_".$i.", K.MASUK_".$i.", R.JAM_PULANG_".$i.", K.PULANG_".$i.", R.EX_JAM_MASUK_".$i.", K.EX_MASUK_".$i."
			";
		}

		$str .= "
		FROM
		(
			select 
			AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
			, * 
			from presensi.pinfoperiode('".$infotanggaldetil."', '".$reqStatusTpp."') P
			where 1=1 ".$statementdetil."
		) P
		LEFT JOIN partisi.absensi_koreksi_".$periode." K ON P.PEGAWAI_ID = CAST(K.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN partisi.absensi_rekap_".$periode." R ON P.PEGAWAI_ID = CAST(R.PEGAWAI_ID AS NUMERIC)
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

	function getCountByParamsNewTppHasilKlarifikasi($paramsArray=array(), $infotanggaldetil, $reqStatusTpp, $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		from presensi.pinfoperiode('".$infotanggaldetil."', '".$reqStatusTpp."') P
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

	function selectByParamsMPresensiAbsen($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		// (CASE WHEN COALESCE(NULLIF(P.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE P.GELAR_DEPAN || ' ' END) || P.NAMA || (CASE WHEN COALESCE(NULLIF(P.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || P.GELAR_BELAKANG END)
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU,  P.NAMA_LENGKAP
		, TO_CHAR(PA.JAM, 'YYYY-MM-DD') TANGGAL
		, TO_CHAR(PA.JAM, 'HH24:MI:SS') WAKTU
		, CASE PA.TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Siang' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END TIPE_PRESENSI
		, CASE PA.VERIFY_TYPE WHEN 1 THEN 'Jari' WHEN 15 THEN 'Wajah' ELSE '-' END TIPE_LOG
		, PA.TERMINAL_ALIAS MESIN_PRESENSI
		, TO_CHAR(PA.UPLOAD_TIME, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_DATA_MASUK
		FROM 
		(
			".$this->setpinfoakhir()."
		) P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'MMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
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

	function selectByParamsMPresensiAbsenBak($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
		, TO_CHAR(PA.JAM, 'YYYY-MM-DD') TANGGAL
		, TO_CHAR(PA.JAM, 'HH24:MI:SS') WAKTU
		, CASE PA.TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Siang' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END TIPE_PRESENSI
		, CASE PA.VERIFY_TYPE WHEN 1 THEN 'Jari' WHEN 15 THEN 'Wajah' ELSE '-' END TIPE_LOG
		, PA.TERMINAL_ALIAS MESIN_PRESENSI
		, TO_CHAR(PA.UPLOAD_TIME, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_DATA_MASUK
		FROM
		(
		".$this->setpinfoakhir()."
		) P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'MMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
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

	function selectByParamsPresensiAbsen($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID, P.NIP_BARU, P.NAMA_LENGKAP
		, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_INFO
		, TO_CHAR(PA.JAM, 'YYYY-MM-DD') TANGGAL, TO_CHAR(PA.JAM, 'HH24:MI:SS') WAKTU
		, CASE PA.TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'A/S/K' WHEN '3' THEN 'Cek Siang' WHEN '4' THEN 'Masuk Lembur' WHEN '5' THEN 'Pulang Lembur' ELSE '-' END TIPE_PRESENSI
		, CASE PA.VERIFY_TYPE WHEN 1 THEN 'Jari' WHEN 15 THEN 'Wajah' ELSE '-' END TIPE_LOG
		, PA.TERMINAL_ALIAS MESIN_PRESENSI, TO_CHAR(PA.UPLOAD_TIME, 'YYYY-MM-DD HH24:MI:SS') TANGGAL_DATA_MASUK
		FROM
		(
		".$this->setpinfoakhir()."
		) P
		INNER JOIN (SELECT * FROM presensi.absensi WHERE TO_CHAR(JAM, 'MMYYYY') = '".$periode."') PA ON P.PEGAWAI_ID = CAST(PA.PEGAWAI_ID AS NUMERIC)
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

	function getCountByParamsPresensiAbsen($paramsArray=array(), $periode, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
		".$this->setpinfoakhir()."
		) P
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

	function selectByParamsPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT
		P.PEGAWAI_ID
		FROM
		(
		".$this->setpinfoakhir()."
		) P
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

} 
?>