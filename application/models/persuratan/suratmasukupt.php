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
  
  class SuratMasukUpt extends Entity{ 

  	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function __construct() {
    	parent::__construct();
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_UPT_ID", $this->getNextId("SURAT_MASUK_UPT_ID","PERSURATAN.SURAT_MASUK_UPT")); 

     	$str = "
     	INSERT INTO PERSURATAN.SURAT_MASUK_UPT 
     	(
	     	SURAT_MASUK_UPT_ID, JENIS_ID, NOMOR, NO_AGENDA, TANGGAL, KEPADA, PERIHAL
	     	, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, KATEGORI, PENGATURAN_KENAIKAN_PANGKAT_ID
	     	, JKM_NOMOR, JKM_TANGGAL
     	)
     	VALUES
     	(
	     	".$this->getField("SURAT_MASUK_UPT_ID")."
	     	, ".$this->getField("JENIS_ID")."
	     	, '".$this->getField("NOMOR")."'
	     	, '".$this->getField("NO_AGENDA")."'
	     	, ".$this->getField("TANGGAL")."
	     	, '".$this->getField("KEPADA")."'
	     	, '".$this->getField("PERIHAL")."'
	     	, ".$this->getField("SATUAN_KERJA_TUJUAN_ID")."
	     	, ".$this->getField("SATUAN_KERJA_ASAL_ID")."
	     	, '".$this->getField("KATEGORI")."'
	     	, ".$this->getField("PENGATURAN_KENAIKAN_PANGKAT_ID")."
	     	, '".$this->getField("JKM_NOMOR")."'
	     	, ".$this->getField("JKM_TANGGAL")."
     	)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_UPT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE PERSURATAN.SURAT_MASUK_UPT
		SET
			JENIS_ID= ".$this->getField("JENIS_ID")."
			, NOMOR= '".$this->getField("NOMOR")."'
			, NO_AGENDA= '".$this->getField("NO_AGENDA")."'
			, TANGGAL= ".$this->getField("TANGGAL")."
			, KEPADA= '".$this->getField("KEPADA")."'
			, PERIHAL= '".$this->getField("PERIHAL")."'
			, SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID")."
			, SATUAN_KERJA_ASAL_ID= ".$this->getField("SATUAN_KERJA_ASAL_ID")."
			, JKM_NOMOR= '".$this->getField("JKM_NOMOR")."'
	     	, JKM_TANGGAL= ".$this->getField("JKM_TANGGAL")."
		WHERE  SURAT_MASUK_UPT_ID = ".$this->getField("SURAT_MASUK_UPT_ID")."
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_UPT
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM")."
				WHERE  SURAT_MASUK_UPT_ID = ".$this->getField("SURAT_MASUK_UPT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updatePerihalKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_UPT
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM").",
					PERIHAL= '".$this->getField("PERIHAL")."'
				WHERE  SURAT_MASUK_UPT_ID = ".$this->getField("SURAT_MASUK_UPT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateBaca()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_UPT
				SET
					TERBACA= ".$this->getField("TERBACA")."
				WHERE  SURAT_MASUK_UPT_ID = ".$this->getField("SURAT_MASUK_UPT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
		
	function delete()
	{
        $str = "
				DELETE FROM PERSURATAN.SURAT_MASUK_UPT
				WHERE SURAT_MASUK_UPT_ID = ".$this->getField("SURAT_MASUK_UPT_ID")."
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

    function selectByParamsSimple($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_UPT_ID ASC')
	{
		$str = "
		SELECT
			SURAT_MASUK_UPT_ID, JENIS_ID, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, 
			TANGGAL_BATAS, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, 
			STATUS_KIRIM, TERBACA, KATEGORI
		FROM PERSURATAN.SURAT_MASUK_UPT A
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
    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_UPT_ID ASC')
	{
		$str = "
		SELECT
			B.NAMA SATUAN_KERJA_ASAL_NAMA, C.NAMA SATUAN_KERJA_TUJUAN_NAMA
			, COALESCE(TPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI, COALESCE(TPPU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_PROGRES
			, COALESCE(TPKU.JUMLAH_PEGAWAI,0) TOTAL_PEGAWAI_KEMBALI
			, CASE A.TERBACA WHEN 1 THEN C.NAMA ELSE '' END POSISI_TERAKHIR
			, CASE WHEN A.KATEGORI = 'bup' AND A.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND A.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' WHEN A.KATEGORI = 'dini' AND A.JENIS_ID = 7 THEN 'Pensiun APS(Dini)' WHEN A.KATEGORI = 'udzur' AND A.JENIS_ID = 7 THEN 'Pensiun Udzur' END KATEGORI_NAMA
			, PKP.TANGGAL_PERIODE PENGATURAN_KENAIKAN_PANGKAT_TANGGAL_PERIODE
			, A.*
		FROM persuratan.SURAT_MASUK_UPT A
		LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
		LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_TUJUAN_ID = C.SATUAN_KERJA_ID
		LEFT JOIN persuratan.TOTAL_PEGAWAI_UPT TPU ON A.SURAT_MASUK_UPT_ID = TPU.SURAT_MASUK_UPT_ID
		LEFT JOIN persuratan.TOTAL_PEGAWAI_PROGRES_UPT TPPU ON A.SURAT_MASUK_UPT_ID = TPPU.SURAT_MASUK_UPT_ID
		LEFT JOIN persuratan.TOTAL_PEGAWAI_KEMBALI_UPT TPKU ON A.SURAT_MASUK_UPT_ID = TPKU.SURAT_MASUK_UPT_ID
		LEFT JOIN pengaturan_kenaikan_pangkat PKP ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = PKP.PENGATURAN_KENAIKAN_PANGKAT_ID
		WHERE 1 = 1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsCetakPengantarSatuOrang($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT 
			A.NAMA, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, A.NIP_BARU, A.JENIS_KELAMIN, A.TANGGAL_LAHIR, A.TEMPAT_LAHIR
			, AMBIL_ALAMAT_PEGAWAI(A.PEGAWAI_ID) ALAMAT, COALESCE(SMP.TMT_PENSIUN, P.TMT) PENSIUN_TMT
			, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Karis' ELSE 'Karsu' END JENIS_KARIS_KARSU
			, CASE WHEN JENIS_KELAMIN = 'L' THEN 'Istri' ELSE 'Suami' END JENIS_NAMA_KARIS_KARSU
			, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA
			, SMU.SATUAN_KERJA_ASAL_ID
			, SMU.NOMOR, SMU.TANGGAL, SMU.KEPADA, AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM
			, AMBIL_SATKER_JABATAN(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM_KEPALA, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_DETIL, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_DETIL1
			, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
			, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA, PEN_RIW.JURUSAN JURUSAN
			, PEN_US_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.TEMPAT TEMPAT_US
			, PUS.JUMLAH_USULAN_PEGAWAI
			, KPG_RIW.JENIS_KARPEG, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.KETERANGAN
			, KARS_RIW.JENIS_KARSU
			, KARS_RIW.SURAT_MASUK_KARSU_ID, KARS_RIW.NO_SURAT_KEHILANGAN_KARSU, KARS_RIW.TANGGAL_SURAT_KEHILANGAN_KARSU
			, KARS_RIW.KETERANGAN_KARSU, KARS_RIW.JENIS_KESALAHAN, KARS_RIW.TERTULIS, KARS_RIW.SEHARUSNYA, SMU.KATEGORI KATEGORI
			,
			CASE SMU.KATEGORI
			WHEN 'bup' THEN 'BUP' WHEN 'meninggal' THEN 'Janda / Duda'
			WHEN 'dini' THEN 'Atas Permintaan Sendiri' WHEN 'udzur' THEN 'Tidak Cakap Jasmani/Rohani'
			ELSE ''
			END KATEGORI_INFO
			, CASE SMP.KP_JENIS
			WHEN 'kpreguler' THEN 'KP Reguler'
			WHEN 'kpstruktural' THEN 'KP Pilihan (Jabatan Struktural)'
			WHEN 'kpjft' THEN 'KP Pilihan (Jabatan Fungsional Tertentu)'
			WHEN 'kppi' THEN 'KP Pilihan (Penyesuian Ijazah)'
			WHEN 'kpbtugas' THEN 'KP Pilihan (Sedang Melaksanakan Tugas)'
			WHEN 'kpstugas' THEN 'KP Pilihan (Setelah Selesai Tugas Belajar)'
			END KP_JENIS_NAMA
			, PS.NAMA USULAN_PANGKAT_RIWAYAT_NAMA, PS.KODE USULAN_PANGKAT_RIWAYAT_KODE, SMU.TANGGAL_PERIODE_KP USULAN_PANGKAT_RIWAYAT_TMT
		FROM PEGAWAI A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
		INNER JOIN 
		(
			SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_UPT A
			LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
		) SMU ON SMU.SURAT_MASUK_UPT_ID = SMP.SURAT_MASUK_UPT_ID
		LEFT JOIN PANGKAT PS ON PS.PANGKAT_ID = SMP.KP_PANGKAT_ID
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT SURAT_MASUK_KARPEG_ID, JENIS_ID, JENIS_KARPEG, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
				PEGAWAI_ID, NO_SURAT_KEHILANGAN, TANGGAL_SURAT_KEHILANGAN, KETERANGAN
			FROM persuratan.SURAT_MASUK_KARPEG A
		) KPG_RIW ON SMP.SURAT_MASUK_KARPEG_ID = KPG_RIW.SURAT_MASUK_KARPEG_ID
		LEFT JOIN
		(
			SELECT SURAT_MASUK_KARSU_ID, NO_SURAT_KEHILANGAN NO_SURAT_KEHILANGAN_KARSU, TANGGAL_SURAT_KEHILANGAN TANGGAL_SURAT_KEHILANGAN_KARSU, KETERANGAN KETERANGAN_KARSU
			, JENIS_KESALAHAN, TERTULIS, SEHARUSNYA, JENIS_KARSU
			FROM persuratan.SURAT_MASUK_KARSU A
		) KARS_RIW ON SMP.SURAT_MASUK_KARSU_ID = KARS_RIW.SURAT_MASUK_KARSU_ID
		LEFT JOIN
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
				, CASE A.STATUS_PENDIDIKAN
				WHEN '1' THEN 'Pendidikan CPNS'
				WHEN '2' THEN 'Diakui'
				WHEN '3' THEN 'Belum Diakui'
				WHEN '4' THEN 'Riwayat'
				WHEN '5' THEN 'Ijin belajar'
				WHEN '6' THEN 'Tugas Belajar'
				ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
		) PEN_RIW ON SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID = PEN_RIW.PENDIDIKAN_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
				, CASE A.STATUS_PENDIDIKAN
				WHEN '1' THEN 'Pendidikan CPNS'
				WHEN '2' THEN 'Diakui'
				WHEN '3' THEN 'Belum Diakui'
				WHEN '4' THEN 'Riwayat'
				WHEN '5' THEN 'Ijin belajar'
				WHEN '6' THEN 'Tugas Belajar'
				ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH, A.NAMA_FAKULTAS, A.TEMPAT
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
		) PEN_US_RIW ON SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID = PEN_US_RIW.PENDIDIKAN_RIWAYAT_ID
		LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT SMP.SURAT_MASUK_UPT_ID, SMP.JENIS_ID, COUNT(SMP.PEGAWAI_ID) JUMLAH_USULAN_PEGAWAI
			FROM persuratan.SURAT_MASUK_PEGAWAI SMP
			WHERE 1 = 1
			GROUP BY SMP.SURAT_MASUK_UPT_ID, SMP.JENIS_ID
		) PUS ON SMP.SURAT_MASUK_UPT_ID = PUS.SURAT_MASUK_UPT_ID
		LEFT JOIN PENSIUN P ON A.PEGAWAI_ID = P.PEGAWAI_ID AND SMP.KATEGORI = P.JENIS
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
	
	function selectByParamsSurat($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_ID ASC')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
			SELECT 
				A.SURAT_MASUK_UPT_ID, A.JENIS_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, 
				A.TANGGAL_BATAS, A.KEPADA, A.PERIHAL
				, A.SATUAN_KERJA_ASAL_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_ASAL_NAMA
				, A.SATUAN_KERJA_TUJUAN_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_NAMA
				, '' POSISI_SURAT
				, '' POSISI_ID_SURAT
				, A.TERBACA, CASE A.TERBACA WHEN 1 THEN 'Di terima' ELSE 'Belum di terima' END TERBACA_NAMA
			FROM persuratan.SURAT_MASUK_UPT A
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

    function getCountByParamsSurat($paramsArray=array(), $satuankerjaid="", $jenisid="", $statement='')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( SURAT_MASUK_BKD_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_MASUK_UPT A
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

    function getCountByParamsCetakPengantarSatuOrang($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN persuratan.SURAT_MASUK_UPT SMU ON SMU.SURAT_MASUK_UPT_ID = SMP.SURAT_MASUK_UPT_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT SURAT_MASUK_KARPEG_ID, JENIS_ID, JENIS_KARPEG, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
						PEGAWAI_ID, NO_SURAT_KEHILANGAN, TANGGAL_SURAT_KEHILANGAN, KETERANGAN
					FROM persuratan.SURAT_MASUK_KARPEG A
				) KPG_RIW ON SMP.SURAT_MASUK_KARPEG_ID = KPG_RIW.SURAT_MASUK_KARPEG_ID
				LEFT JOIN
				(
					SELECT 	
						A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
						, CASE A.STATUS_PENDIDIKAN
						WHEN '1' THEN 'Pendidikan CPNS'
						WHEN '2' THEN 'Diakui'
						WHEN '3' THEN 'Belum Diakui'
						WHEN '4' THEN 'Riwayat'
						WHEN '5' THEN 'Ijin belajar'
						WHEN '6' THEN 'Tugas Belajar'
						ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_RIW ON SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID = PEN_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT 	
						A.PENDIDIKAN_RIWAYAT_ID, A.PENDIDIKAN_ID, C.NAMA PENDIDIKAN_NAMA
						, CASE A.STATUS_PENDIDIKAN
						WHEN '1' THEN 'Pendidikan CPNS'
						WHEN '2' THEN 'Diakui'
						WHEN '3' THEN 'Belum Diakui'
						WHEN '4' THEN 'Riwayat'
						WHEN '5' THEN 'Ijin belajar'
						WHEN '6' THEN 'Tugas Belajar'
						ELSE '-' END STATUS_PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN_ID, A.JURUSAN, A.NAMA NAMA_SEKOLAH, A.NAMA_FAKULTAS, A.TEMPAT
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_US_RIW ON SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID = PEN_US_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN
				(
					SELECT SMP.SURAT_MASUK_UPT_ID, SMP.JENIS_ID, COUNT(SMP.PEGAWAI_ID) JUMLAH_USULAN_PEGAWAI
					FROM persuratan.SURAT_MASUK_PEGAWAI SMP
					WHERE 1 = 1
					GROUP BY SMP.SURAT_MASUK_UPT_ID, SMP.JENIS_ID
				) PUS ON SMP.SURAT_MASUK_UPT_ID = PUS.SURAT_MASUK_UPT_ID
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

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_MASUK_UPT A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_TUJUAN_ID = C.SATUAN_KERJA_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_UPT TPU ON A.SURAT_MASUK_UPT_ID = TPU.SURAT_MASUK_UPT_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_PROGRES_UPT TPPU ON A.SURAT_MASUK_UPT_ID = TPPU.SURAT_MASUK_UPT_ID
				LEFT JOIN persuratan.TOTAL_PEGAWAI_KEMBALI_UPT TPKU ON A.SURAT_MASUK_UPT_ID = TPKU.SURAT_MASUK_UPT_ID
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