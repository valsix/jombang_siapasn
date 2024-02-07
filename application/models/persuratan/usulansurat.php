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
  
  class UsulanSurat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function UsulanSurat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USULAN_SURAT_ID", $this->getNextId("USULAN_SURAT_ID","PERSURATAN.USULAN_SURAT")); 
		// '".$this->getField("NOMOR")."',
     	$str = "
			INSERT INTO PERSURATAN.USULAN_SURAT (
				USULAN_SURAT_ID, JENIS_ID, ID_SEMENTARA, NOMOR, NO_AGENDA, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, SATUAN_KERJA_TUJUAN_NAMA, KATEGORI
				, TANGGAL_DIBUAT) 
			VALUES (
				 ".$this->getField("USULAN_SURAT_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("ID_SEMENTARA")."',
				 (SELECT persuratan.AMBIL_JENIS_KATEGORI_KLASIFIKASI_KODE_USULAN_BARU(".$this->getField("JENIS_ID").", '".$this->getField("KATEGORI")."')),
				 '".$this->getField("NO_AGENDA")."',
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("KEPADA")."',
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 ".$this->getField("SATUAN_KERJA_ASAL_ID").",
				 '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."',
				 '".$this->getField("KATEGORI")."'
				 , NOW()
			)
		"; 	
		$this->id = $this->getField("USULAN_SURAT_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function insertSurat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("USULAN_SURAT_ID", $this->getNextId("USULAN_SURAT_ID","PERSURATAN.USULAN_SURAT")); 

     	$str = "
			INSERT INTO PERSURATAN.USULAN_SURAT (
				USULAN_SURAT_ID, JENIS_ID, NOMOR, NO_AGENDA, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, STATUS_KIRIM
				, IS_MANUAL, SATUAN_KERJA_ASAL_NAMA) 
			VALUES (
				 ".$this->getField("USULAN_SURAT_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("NOMOR")."',
				 '".$this->getField("NO_AGENDA")."',
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("KEPADA")."',
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 ".$this->getField("SATUAN_KERJA_ASAL_ID")."
				 , 1
				 , ".$this->getField("IS_MANUAL")."
				 , ".$this->getField("SATUAN_KERJA_ASAL_NAMA")."
			)
		"; 	
		$this->id = $this->getField("USULAN_SURAT_ID");
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		$str1= "		
				UPDATE PERSURATAN.USULAN_SURAT_DISPOSISI
				SET
					SURAT_AWAL= 1, STATUS_POSISI_SURAT = 1
				WHERE  USULAN_SURAT_ID = ".$this->id." AND USULAN_SURAT_DISPOSISI_PARENT_ID != 0
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		$this->execQuery($str1);
		
		$str1= "		
				UPDATE PERSURATAN.USULAN_SURAT_DISPOSISI
				SET
					TERBACA= 1
				WHERE  USULAN_SURAT_ID = ".$this->id."
				"; 
		$this->query = $str1;
		//echo $str1;exit;
		return $this->execQuery($str1);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// '".$this->getField("NOMOR")."'
		$str = "		
				UPDATE PERSURATAN.USULAN_SURAT
				SET
					JENIS_ID= ".$this->getField("JENIS_ID").",
					ID_SEMENTARA= '".$this->getField("ID_SEMENTARA")."',
				 	NOMOR= (SELECT persuratan.AMBIL_JENIS_KATEGORI_KLASIFIKASI_KODE_USULAN(USULAN_SURAT_ID, JENIS_ID, KATEGORI)),
				 	NO_AGENDA= '".$this->getField("NO_AGENDA")."',
				 	TANGGAL= ".$this->getField("TANGGAL").",
				 	KEPADA= '".$this->getField("KEPADA")."',
				 	PERIHAL= '".$this->getField("PERIHAL")."',
				 	SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
					SATUAN_KERJA_TUJUAN_NAMA= '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."',
				 	SATUAN_KERJA_ASAL_ID= ".$this->getField("SATUAN_KERJA_ASAL_ID").",
				 	KATEGORI= '".$this->getField("KATEGORI")."'
				WHERE  USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.USULAN_SURAT
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM")."
				WHERE  USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updatePerihalKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.USULAN_SURAT
				SET
					STATUS_KIRIM= ".$this->getField("STATUS_KIRIM").",
					PERIHAL= '".$this->getField("PERIHAL")."'
				WHERE  USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateDiambil()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_SURAT_KELUAR= 88,
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str0= "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					USULAN_SURAT_ID= NULL,
					STATUS_BERKAS= ".$this->getField("STATUS_BERKAS").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				"; 
		$this->query = $str0;
		//echo $str0;exit;
		$this->execQuery($str0);
		
		$str1= "
				DELETE FROM PERSURATAN.SURAT_MASUK_PEGAWAI_LOG
				WHERE SURAT_MASUK_UPT_ID IS NULL AND USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				";
		$this->query = $str1;
		$this->execQuery($str1);
		//echo $str1;exit;
		
		$str2= "
				DELETE FROM PERSURATAN.SURAT_MASUK_PEGAWAI
				WHERE SURAT_MASUK_UPT_ID IS NULL AND USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
				";
		$this->query = $str2;
		$this->execQuery($str2);
		
        $str = "
				DELETE FROM PERSURATAN.USULAN_SURAT
				WHERE USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USULAN_SURAT_ID ASC')
	{
		$str = "
				SELECT
					A.USULAN_SURAT_ID, A.ID_SEMENTARA, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, A.TANGGAL_BATAS, 
					A.KEPADA, A.PERIHAL, COALESCE(A.SATUAN_KERJA_ASAL_NAMA, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID)) POSISI_TERAKHIR
					, A.SATUAN_KERJA_TUJUAN_NAMA, A.ID_SEMENTARA, A.STATUS_KIRIM
					, COALESCE(JUMLAH_USUL,0) TOTAL_USULAN
					, A.KATEGORI, CASE WHEN A.KATEGORI = 'bup' AND (A.JENIS_ID = 8 OR A.JENIS_ID = 12) THEN 'Pensiun BUP' WHEN A.KATEGORI = 'meninggal' AND (A.JENIS_ID = 8 OR A.JENIS_ID = 12) THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
					, A.KIRIM_KE, A.JENIS_ID
				FROM persuratan.USULAN_SURAT A
				LEFT JOIN
				(
					SELECT USULAN_SURAT_ID, COUNT(1) JUMLAH_USUL FROM persuratan.SURAT_MASUK_PEGAWAI GROUP BY USULAN_SURAT_ID
				) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
				WHERE 1 = 1
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
	
	function selectByParamsPilihUsulan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.USULAN_SURAT_ID ASC')
	{
		$str = "
		SELECT
			A.USULAN_SURAT_ID, A.ID_SEMENTARA, COALESCE(B.JUMLAH_DATA, 0) JUMLAH_DATA, TANGGAL_DIBUAT
		FROM persuratan.USULAN_SURAT A
		LEFT JOIN (SELECT USULAN_SURAT_ID, COUNT(1) JUMLAH_DATA FROM persuratan.SURAT_MASUK_PEGAWAI GROUP BY USULAN_SURAT_ID) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
		WHERE 1 = 1
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
	
	function selectByParamsCetakPengantarSatuOrang($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT 
					A.NAMA, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, A.NIP_BARU, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR
					, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA
					, SMU.SATUAN_KERJA_ASAL_ID
					, SMU.NOMOR, SMU.TANGGAL, SMU.KEPADA, AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM
					, AMBIL_SATKER_JABATAN(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_PENGIRIM_KEPALA, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_DETIL
					, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA, PEN_RIW.JURUSAN JURUSAN
					, PEN_US_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, PUS.JUMLAH_USULAN_PEGAWAI
					, CASE WHEN COALESCE(NULLIF(SKU.NOMOR,'') , NULL ) IS NULL THEN CONCAT(B.KODE,'/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/', TTD1.NO_NOMENKLATUR_KAB,'.', TTD1.NO_NOMENKLATUR_BKD,'/',TO_CHAR(CURRENT_DATE, 'YYYY')) ELSE SKU.NOMOR END NOMOR_SURAT_KELUAR
					, COALESCE(SKU.TANGGAL, CURRENT_DATE) TANGGAL_SURAT_KELUAR1
					, SKU.TANGGAL TANGGAL_SURAT_KELUAR
					, SMP.JENIS_ID, SMP.USULAN_SURAT_ID, SMP.SURAT_MASUK_UPT_ID, SMP.PEGAWAI_ID
					, AMBIL_SATKER_NAMA_DYNAMIC(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_PEGAWAI_SURAT_KELUAR
					, CONCAT((CASE WHEN COALESCE(NULLIF(COALESCE(TTD.PLT_JABATAN,TTD1.PLT_JABATAN),'') , NULL ) IS NULL THEN '' ELSE 'PLT. ' END), COALESCE(TTD.NAMA,TTD1.NAMA)) TTD_SATUAN_KERJA
					, COALESCE(TTD.NAMA_PEJABAT,TTD1.NAMA_PEJABAT) TTD_NAMA_PEJABAT, COALESCE(TTD.PANGKAT,TTD1.PANGKAT) TTD_PANGKAT, COALESCE(TTD.NIP,TTD1.NIP) TTD_NIP
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN persuratan.USULAN_SURAT SMU ON SMU.USULAN_SURAT_ID = SMP.USULAN_SURAT_ID
				LEFT JOIN persuratan.SURAT_KELUAR_BKD SKU ON SKU.SURAT_MASUK_PEGAWAI_ID = SMP.SURAT_MASUK_PEGAWAI_ID
				LEFT JOIN persuratan.KLASIFIKASI B ON SKU.KLASIFIKASI_ID = B.KLASIFIKASI_ID
				LEFT JOIN TANDA_TANGAN_BKD TTD ON SKU.TANDA_TANGAN_BKD_ID = TTD.TANDA_TANGAN_BKD_ID
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
					SELECT SMP.USULAN_SURAT_ID, SMP.JENIS_ID, COUNT(SMP.PEGAWAI_ID) JUMLAH_USULAN_PEGAWAI
					FROM persuratan.SURAT_MASUK_PEGAWAI SMP
					WHERE 1 = 1
					GROUP BY SMP.USULAN_SURAT_ID, SMP.JENIS_ID
				) PUS ON SMP.USULAN_SURAT_ID = PUS.USULAN_SURAT_ID
				, (
				SELECT 
				TANDA_TANGAN_BKD_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NO_NOMENKLATUR_KAB, 
				NO_NOMENKLATUR_BKD, NAMA, PLT_JABATAN, NAMA_PEJABAT, PANGKAT_ID, 
				KODE_PANGKAT, PANGKAT, NIP, PEJABAT_PENETAP
				FROM TANDA_TANGAN_BKD A
				WHERE 1 = 1
				AND A.TANDA_TANGAN_BKD_ID = (SELECT AMBIL_TANDA_TANGAN_BKD_TGL(CURRENT_DATE))
				) TTD1
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
	
	function selectByParamsSurat($paramsArray=array(),$limit=-1,$from=-1, $satuankerjaid="", $statementdisposisi="", $statement='',$order=' ORDER BY A.USULAN_SURAT_ID ASC')
	{
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( USULAN_SURAT_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
			SELECT 
				A.USULAN_SURAT_ID, A.JENIS_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, 
				A.TANGGAL_BATAS, A.KEPADA, A.PERIHAL
				, A.SATUAN_KERJA_ASAL_ID
				, CASE WHEN A.SATUAN_KERJA_ASAL_ID IS NULL THEN A.SATUAN_KERJA_ASAL_NAMA ELSE AMBIL_SATKER_NAMA(A.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_ASAL_NAMA
				, A.SATUAN_KERJA_TUJUAN_ID, AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID) SATUAN_KERJA_TUJUAN_NAMA
				, persuratan.AMBIL_SATKER_POSISI_SURAT(A.USULAN_SURAT_ID, A.JENIS_ID) POSISI_SURAT
				, 
				CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) IS NULL 
				THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
				ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) END POSISI_SURAT_BACA
				, persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) POSISI_SURAT_BACABAK
				, persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.USULAN_SURAT_ID, A.JENIS_ID) POSISI_ID_SURAT
				,
				CASE WHEN PD.POSISI_TEKNIS = 1 THEN
					AMBIL_SATKER_NAMA(PD.SATUAN_KERJA_ASAL_ID)
				ELSE
					CASE WHEN A.JENIS_ID IS NULL THEN 
						CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) IS NULL 
						THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
						ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) END
					ELSE PBKD.INFO_PROSES END
				END POSISI_TERAKHIR
				, 
				CASE WHEN A.JENIS_ID IS NULL THEN 
					CASE WHEN persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) IS NULL 
					THEN AMBIL_SATKER_NAMA(A.SATUAN_KERJA_TUJUAN_ID)
					ELSE persuratan.AMBIL_SATKER_POSISI_SURAT_BACA(A.USULAN_SURAT_ID, A.JENIS_ID) END
				ELSE PBKD.INFO_PROSES END POSISI_TERAKHIRBAK1
			FROM persuratan.USULAN_SURAT A
			INNER JOIN
			(
				SELECT USULAN_SURAT_ID
				FROM persuratan.USULAN_SURAT_DISPOSISI
				WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
				".$statementdisposisi."
				GROUP BY USULAN_SURAT_ID
			) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
			LEFT JOIN ( 
				SELECT USULAN_SURAT_DISPOSISI_ID, SATUAN_KERJA_ASAL_ID, POSISI_TEKNIS FROM persuratan.USULAN_SURAT_DISPOSISI
			) PD ON PD.USULAN_SURAT_DISPOSISI_ID = persuratan.AMBIL_SATKER_POSISI_ID_SURAT(A.USULAN_SURAT_ID, A.JENIS_ID)
			LEFT JOIN 
			(
				SELECT
				A.JENIS_ID, A.USULAN_SURAT_ID
				, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
				INNER JOIN
				(
				SELECT USULAN_SURAT_ID, JENIS_ID, MAX(LAST_DATE) LAST_DATE
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
				WHERE USULAN_SURAT_ID IS NOT NULL
				GROUP BY USULAN_SURAT_ID, JENIS_ID
				) B ON A.JENIS_ID = B.JENIS_ID AND A.USULAN_SURAT_ID = B.USULAN_SURAT_ID AND A.LAST_DATE = B.LAST_DATE
				GROUP BY A.JENIS_ID, A.USULAN_SURAT_ID
				, A.SATUAN_KERJA_ID, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID))
			) PBKD ON A.JENIS_ID = PBKD.JENIS_ID AND A.USULAN_SURAT_ID = PBKD.USULAN_SURAT_ID
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
		//WHERE 1=1 AND ( SATUAN_KERJA_ASAL_ID = ".$satuankerjaid." OR ( USULAN_SURAT_DISPOSISI_PARENT_ID = 0 AND SATUAN_KERJA_TUJUAN_ID = ".$satuankerjaid." ))
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.USULAN_SURAT A
				INNER JOIN
				(
					SELECT USULAN_SURAT_ID
					FROM persuratan.USULAN_SURAT_DISPOSISI
					WHERE 1=1 AND SATUAN_KERJA_ASAL_ID = ".$satuankerjaid."
					".$statementdisposisi."
					GROUP BY USULAN_SURAT_ID
				) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
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
				FROM persuratan.USULAN_SURAT A
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
	
	function getCountByParamsPilihUsulan($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.USULAN_SURAT A
				LEFT JOIN (SELECT USULAN_SURAT_ID, COUNT(1) JUMLAH_DATA FROM persuratan.SURAT_MASUK_PEGAWAI GROUP BY USULAN_SURAT_ID) B ON A.USULAN_SURAT_ID = B.USULAN_SURAT_ID
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
	
	function getCountByParamsSatuanKerjaIdSurat($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT SATUAN_KERJA_ID AS ROWCOUNT 
				FROM SATUAN_KERJA A
				WHERE 1 = 1 AND A.STATUS_KELOMPOK_PEGAWAI_USUL = 1 AND SATUAN_KERJA_PARENT_ID = 0 ".$statement; 
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