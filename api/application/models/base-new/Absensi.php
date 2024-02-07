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
  
  class Absensi extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Absensi()
	{
      $this->Entity(); 
    }

    function nextpermohonanfileid()
	{
		return $this->getNextId("PERMOHONAN_FILE_ID","presensi.PERMOHONAN_FILE");
	}

    function insertPermohonanFile()
	{
		$this->setField("PERMOHONAN_FILE_ID", $this->getNextId("PERMOHONAN_FILE_ID","presensi.PERMOHONAN_FILE"));

		$str= "
		INSERT INTO presensi.PERMOHONAN_FILE
		(
			PERMOHONAN_FILE_ID, PEGAWAI_ID, PERMOHONAN_TABLE_NAMA, PERMOHONAN_TABLE_ID, 
			NAMA, TIPE, LINK_FILE, KETERANGAN, LAST_USER, LAST_DATE, 
			USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, LAST_CREATE_DATE, USER_LOGIN_CREATE_ID
		) 
		VALUES 
		( 
			".$this->getField("PERMOHONAN_FILE_ID").",
			'".$this->getField("PEGAWAI_ID")."',
			'".$this->getField("PERMOHONAN_TABLE_NAMA")."',
			".$this->getField("PERMOHONAN_TABLE_ID").",
			'".$this->getField("NAMA")."',
			'".$this->getField("TIPE")."',
			'".$this->getField("LINK_FILE")."',
			'".$this->getField("KETERANGAN")."',
			'".$this->getField("LAST_USER")."',
			NOW(),
			".$this->getField("USER_LOGIN_ID").",
			".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			NOW(),
			".$this->getField("USER_LOGIN_CREATE_ID")."
		)
		"; 
		$this->id= $this->getField("PERMOHONAN_FILE_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertPermohonanDinas()
	{
		$this->setField("PERMOHONAN_LAMBAT_PC_ID", $this->getNextId("PERMOHONAN_LAMBAT_PC_ID","presensi.PERMOHONAN_LAMBAT_PC"));

		$str= "
		INSERT INTO presensi.PERMOHONAN_LAMBAT_PC 
		(
			PERMOHONAN_LAMBAT_PC_ID, PEGAWAI_ID, NOMOR, TANGGAL, 
			JABATAN, CABANG, DEPARTEMEN, 
			SUB_DEPARTEMEN, TANGGAL_IJIN, JAM_DATANG, 
			JAM_PULANG, KEPERLUAN, KETERANGAN, 
			LAST_CREATE_USER, LAST_CREATE_DATE, CABANG_ID,
			TANGGAL_AWAL, TANGGAL_AKHIR, LAMPIRAN,
			LOKASI
		) 
		VALUES 
		( 
		'".$this->getField("PERMOHONAN_LAMBAT_PC_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NOMOR")."'
		, ".$this->getField("TANGGAL")."
		, '".$this->getField("JABATAN")."', '".$this->getField("CABANG")."', '".$this->getField("DEPARTEMEN")."',
		'".$this->getField("SUB_DEPARTEMEN")."', ".$this->getField("TANGGAL_IJIN").", '".$this->getField("JAM_DATANG")."',
		'".$this->getField("JAM_PULANG")."', '".$this->getField("KEPERLUAN")."', '".$this->getField("KETERANGAN")."', 
		'".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE").", '".$this->getField("CABANG_ID")."',
		".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("LAMPIRAN")."', 
		'".$this->getField("LOKASI")."')
		"; 
		$this->id= $this->getField("PERMOHONAN_LAMBAT_PC_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function insertPermohonanLupaAbsen()
	{
		$this->setField("PERMOHONAN_LUPA_ABSEN_ID", $this->getNextId("PERMOHONAN_LUPA_ABSEN_ID","presensi.PERMOHONAN_LUPA_ABSEN"));

		$str = "
		INSERT INTO presensi.PERMOHONAN_LUPA_ABSEN
		(
			PERMOHONAN_LUPA_ABSEN_ID, PEGAWAI_ID, NOMOR, TANGGAL, JABATAN, 
			CABANG, DEPARTEMEN, SUB_DEPARTEMEN, JENIS_LUPA_ABSEN, 
			TANGGAL_IJIN, KETERANGAN, LAST_CREATE_USER, 
			LAST_CREATE_DATE, STATUS_MASUK, 
			STATUS_PULANG
		) 
		VALUES 
		( 
			'".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NOMOR")."', 
			".$this->getField("TANGGAL").", '".$this->getField("JABATAN")."', '".$this->getField("CABANG")."',
			'".$this->getField("DEPARTEMEN")."', '".$this->getField("SUB_DEPARTEMEN")."', '".$this->getField("JENIS_LUPA_ABSEN")."',
			".$this->getField("TANGGAL_IJIN").", '".$this->getField("KETERANGAN")."', '".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE").", '".$this->getField("STATUS_MASUK")."', 
			'".$this->getField("STATUS_PULANG")."'
		)
		"; 
		$this->id = $this->getField("PERMOHONAN_LUPA_ABSEN_ID");
		$this->query = $str;
		// echo $str;exit();
		
		return $this->execQuery($str);
    }

    function insertPermohonanTipeLupaAbsen()
	{
		$this->setField("PERMOHONAN_LUPA_ABSEN_ID", $this->getNextId("PERMOHONAN_LUPA_ABSEN_ID","presensi.PERMOHONAN_LUPA_ABSEN"));

		$str = "
		INSERT INTO presensi.PERMOHONAN_LUPA_ABSEN
		(
			PERMOHONAN_LUPA_ABSEN_ID, PEGAWAI_ID, TANGGAL, JENIS_LUPA_ABSEN, 
			TANGGAL_IJIN, KETERANGAN, LAST_CREATE_USER, 
			LAST_CREATE_DATE
		) 
		VALUES 
		( 
			'".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."', '".$this->getField("PEGAWAI_ID")."', 
			".$this->getField("TANGGAL").", '".$this->getField("JENIS_LUPA_ABSEN")."',
			".$this->getField("TANGGAL_IJIN").", '".$this->getField("KETERANGAN")."', '".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE")."
		)
		"; 
		$this->id = $this->getField("PERMOHONAN_LUPA_ABSEN_ID");
		$this->query = $str;
		// echo $str;exit();
		
		return $this->execQuery($str);
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
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

    function selectByParamsJamKerja($paramsArray=array(),$limit=-1,$from=-1,$statement="", $order="")
    {
    	$str= "	
    	SELECT 
    	JAM_KERJA_ID, A.NAMA, B.NAMA JENIS, JAM_AWAL, 
    	JAM_AKHIR, TERLAMBAT_AWAL, TERLAMBAT_AKHIR, 
    	STATUS, B.JAM_KERJA_JENIS_ID JAM_KERJA_JENIS_ID,
    	JAM_ISTIRAHAT
    	FROM presensi.JAM_KERJA A
    	LEFT JOIN presensi.JAM_KERJA_JENIS B ON A.JAM_KERJA_JENIS_ID= B.JAM_KERJA_JENIS_ID
    	WHERE 1= 1
    	"; 

    	while(list($key,$val)= each($paramsArray))
    	{
    		$str .= " AND $key= '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query= $str;
		// echo $str;exit;
    	return $this->selectLimit($str,$limit,$from); 
    }

    function setPartisiTablePeriode($periode)
	{
		$str = "SELECT presensi.PARTISITABLE('".$periode."') AS ROWCOUNT "; 
		// $str = "DROP TABLE partisi.absensi_koreksi_".$periode.";";
		// echo $str."<br/>";
		$this->query = $str;
		$this->select($str);
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }
	
	function selectByParamsAbensiInfo($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $periode, $statement='', $order='ORDER BY A.HARI ASC')
	{
		$str = "
		SELECT
		A.PEGAWAI_ID, A.PERIODE, A.MASUK, A.MASUK_KETERANGAN, A.JAM_MASUK
		, A.JAM_PULANG, SPLIT_PART(A.AUTH_MASUK, '#', 1) AUTH_MASUK, SPLIT_PART(A.AUTH_PULANG, '#', 1) AUTH_PULANG
		, NULL LOKASI_AUTH_MASUK, NULL LOKASI_AUTH_PULANG
		, A.HARI, A.TANGGAL, A.NAMA_HARI, A.TERLAMBAT, A.PULANG_CEPAT
		, 
		CASE 
			WHEN A.JAM_MASUK IS NULL THEN NULL
			WHEN (A.JAM_MASUK::INTERVAL - '07:30'::INTERVAL) < '00:00:00' THEN NULL
			WHEN (A.JAM_MASUK::INTERVAL - '07:30'::INTERVAL) < '00:15:00' THEN TO_NUMBER(TO_CHAR((A.JAM_MASUK::INTERVAL - '07:30'::INTERVAL), 'MI'), '99') ||' mnt'
			ELSE 15||' mnt' 
		END TIDAK_DIPOTONG
		,
		CASE 
			WHEN MASUK like 'efektif-LA%' THEN 'Lupa Check Log'
			WHEN MASUK not like 'efektif%' THEN MASUK_KETERANGAN
			ELSE NULL
		END KETERANGAN
		, NULL JAM_MASUK_LEMBUR, NULL JAM_PULANG_LEMBUR, NULL KETERANGAN_LEMBUR
		, JAM_SHIFT, NULL JUMLAH_TERLAMBAT, A.STATUS_MASUK
		, A.EX_JAM_MASUK
		, A.K_MASUK, A.K_MASUK K_MASUK_KETERANGAN, A.T_MASUK, A.S_MASUK
		, A.K_PULANG, A.K_PULANG K_PULANG_KETERANGAN, A.T_PULANG, A.S_PULANG
		, CASE STATUS_KOREKSI WHEN 2 THEN 1 ELSE 0 END STATUS_KOREKSI
		FROM 
		(
			SELECT 
			B1.PEGAWAI_ID, B1.PERIODE, A.MASUK, A.MASUK_KETERANGAN,
			A.JAM_MASUK, A.JAM_PULANG, B1.HARI, A.TANGGAL, A.NAMA_HARI,
			B.TERLAMBAT, B.PULANG_CEPAT, B.AUTH_MASUK, B.AUTH_PULANG, JAM_SHIFT,
			B.MASUK STATUS_MASUK, B.EX_JAM_MASUK
			, K_MASUK, T_MASUK, S_MASUK, K_PULANG, T_PULANG, S_PULANG
			, COALESCE(S_MASUK,0) + COALESCE(S_PULANG,0) STATUS_KOREKSI
			FROM (SELECT * FROM presensi.P_REKAP_ABSENSI_KOREKSI('".$periode."', ' AND B.PEGAWAI_ID = ''".$pegawaiid."'' ')) B1
			LEFT JOIN (SELECT * FROM presensi.P_REKAP_KEHADIRAN_V2('".$periode."', ' AND A.PEGAWAI_ID = ''".$pegawaiid."'' ')) B ON B1.HARI = B.HARI
			LEFT JOIN (SELECT * FROM presensi.P_REKAP_KEHADIRAN_TANGGAL('".$periode."', ' AND A.PEGAWAI_ID = ''''".$pegawaiid."'''' ')) A ON A.HARI = B1.HARI
		) A
		WHERE 1=1
		"; 
		// , B1.KOREKSI_KETERANGAN K_MASUK_KETERANGAN1
		// , B2.KOREKSI_KETERANGAN K_PULANG_KETERANGAN1
		// LEFT JOIN (SELECT KODE KOREKSI_KODE, KETERANGAN KOREKSI_KETERANGAN FROM presensi.IJIN_KOREKSI) B1 ON B1.KOREKSI_KODE = A.K_MASUK
		// LEFT JOIN (SELECT KODE KOREKSI_KODE, KETERANGAN KOREKSI_KETERANGAN FROM presensi.IJIN_KOREKSI) B2 ON B2.KOREKSI_KODE = A.K_PULANG

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsAbensiRekap($paramsArray=array(),$limit=-1,$from=-1, $periode, $statement='', $order='ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
		*
		FROM partisi.ABSENSI_REKAP_".$periode." A
		WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;

		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.BAHASA_ID) AS ROWCOUNT 
				FROM BAHASA A
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

    function selectByParamsAbensiLog($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY JAM ASC')
	{
		$str = "
		SELECT A.*
		FROM
		(
			SELECT
			DISTINCT PEGAWAI_ID, JAM, TO_CHAR(JAM, 'HH24:MI:SS') JAM_INFO, TIPE_ABSEN
			, CASE TIPE_ABSEN WHEN '0' THEN 'Masuk' WHEN '1' THEN 'Pulang' WHEN '2' THEN 'Keg/Senam/Apel' ELSE 'Info belum di tentukan' END TIPE_ABSEN_INFO
			FROM presensi.ABSENSI
			WHERE VALIDASI = 1
		) A
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;

		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsLog($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.LAST_DATE DESC')
	{
		$str = "
		SELECT 
			A.PEGAWAI_ID, A.TANGGAL_INFO, A.KODE, A.INFO_LOG, A.LAST_USER, A.LAST_DATE, 
			A.STATUS, A.USER_LOGIN_ID, A.USER_LOGIN_PEGAWAI_ID
		FROM presensi.PERMOHONAN_LOG A
		WHERE 1=1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    function selectByDataKoreksiPegawai($paramsArray=array(),$limit=-1,$from=-1, $periode, $pegawaiid, $statement='', $order='')
	{
		$str = "
		SELECT
		K.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, k.masuk_".$i.", k.pulang_".$i."
			, k.ex_masuk_".$i.", k.ex_pulang_".$i."
			";
		}

		$str .= "
		FROM pinfopegawaiperiodekoreksi('".$periode."', ".$pegawaiid.") K
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}
	
		function selectByDataRekapPegawai($paramsArray=array(),$limit=-1,$from=-1, $periode, $pegawaiid, $statement='', $order='')
	{
		$str = "
		SELECT
		R.PEGAWAI_ID
		";
		for($i=1; $i <= 31; $i++)
		{
			$str .= "
			, r.jam_masuk_".$i.", r.jam_pulang_".$i."
			, r.ex_jam_masuk_".$i.", r.ex_jam_pulang_".$i."
			, r.terlambat_".$i.", r.pulang_cepat_".$i."
			";
		}

		$str .= "
		FROM pinfopegawaiperioderekap('".$periode."', ".$pegawaiid.") R
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}


    function selectByDataPegawai($paramsArray=array(),$limit=-1,$from=-1, $infotanggaldetil, $statement='', $order='')
    {
    	$str = "
    	SELECT
    	A.PEGAWAI_ID, A.NIP_BARU, A.NAMA_LENGKAP
    	FROM pinfoberjalan('".$infotanggaldetil."') A
    	WHERE 1=1
    	"; 

    	while(list($key,$val) = each($paramsArray))
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
		// echo $str;exit();
    	return $this->selectLimit($str,$limit,$from);
    }

    function selectByParamsSettingKlarifikasi($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT 
    	SETTING_KLARIFIKASI_ID, MASA_BERLAKU_AWAL, MASA_BERLAKU_AKHIR
    	, TO_CHAR(A.MASA_BERLAKU_AWAL, 'YYYY-MM-DD') INFO_MASA_BERLAKU_AWAL
    	, TO_CHAR(A.MASA_BERLAKU_AKHIR, 'YYYY-MM-DD') INFO_MASA_BERLAKU_AKHIR
    	FROM presensi.SETTING_KLARIFIKASI A
    	WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsSettingKlarifikasiPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT 
    	PEGAWAI_ID
    	FROM presensi.SETTING_KLARIFIKASI_PEGAWAI A
    	WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
			
		$str .= $statement."  ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>