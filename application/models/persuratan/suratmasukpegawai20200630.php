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
  include_once("functions/date.func.php");
  
  class SuratMasukPegawai extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratMasukPegawai()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, PENDIDIKAN_RIWAYAT_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertPensiun()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, PENDIDIKAN_RIWAYAT_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID
				, KATEGORI, KETERANGAN_PENSIUN
				, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 '".$this->getField("KATEGORI")."',
				 '".$this->getField("KETERANGAN_PENSIUN")."',
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertKenaikanPangkatReguler()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI"));

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, JABATAN_TAMBAHAN_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_AKHIR_ID, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID
				, KP_PANGKAT_ID, KP_JENIS, KP_STATUS_SURAT_TANDA_LULUS, KP_SURAT_TANDA_LULUS_ID
				, KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI, KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
				, KP_PEGAWAI_FILE_ID, KP_DIKLAT_ID, KP_DIKLAT_STRUKTURAL_ID
				, KP_PAK_LAMA_ID, KP_PAK_BARU_ID
				, KP_STATUS_SERTIFIKAT_KEASLIAN, KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
				, KP_STATUS_SERTIFIKAT_PENDIDIK, KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
				, KP_PAK_LAMA_STATUS
				, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("JABATAN_TAMBAHAN_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 ".$this->getField("KP_PANGKAT_ID").",
				 '".$this->getField("KP_JENIS")."',
				 ".$this->getField("KP_STATUS_SURAT_TANDA_LULUS").",
				 ".$this->getField("KP_SURAT_TANDA_LULUS_ID").",
				 ".$this->getField("KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI").",
				 ".$this->getField("KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID").",
				 ".$this->getField("KP_PEGAWAI_FILE_ID").",
				 ".$this->getField("KP_DIKLAT_ID").",
				 ".$this->getField("KP_DIKLAT_STRUKTURAL_ID").",
				 ".$this->getField("KP_PAK_LAMA_ID").",
				 ".$this->getField("KP_PAK_BARU_ID").",
				 ".$this->getField("KP_STATUS_SERTIFIKAT_KEASLIAN").",
				 ".$this->getField("KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID").",
				 ".$this->getField("KP_STATUS_SERTIFIKAT_PENDIDIK").",
				 ".$this->getField("KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID").",
				 ".$this->getField("KP_PAK_LAMA_STATUS").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertPensiunAnak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PENSIUN_ANAK_ID", $this->getNextId("SURAT_MASUK_PENSIUN_ANAK_ID","PERSURATAN.SURAT_MASUK_PENSIUN_ANAK")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PENSIUN_ANAK (
				SURAT_MASUK_PENSIUN_ANAK_ID, SURAT_MASUK_PEGAWAI_ID, JENIS_ID, KATEGORI, SURAT_MASUK_BKD_ID,
				SURAT_MASUK_UPT_ID, PEGAWAI_ID, ANAK_ID
				)
			VALUES (
				 ".$this->getField("SURAT_MASUK_PENSIUN_ANAK_ID").",
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 '".$this->getField("KATEGORI")."',
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("ANAK_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PENSIUN_ANAK_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function insertKarpeg()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, PENDIDIKAN_RIWAYAT_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID
				, SURAT_MASUK_KARPEG_ID, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 ".$this->getField("SURAT_MASUK_KARPEG_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function insertKarsu()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, PENDIDIKAN_RIWAYAT_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID
				, SURAT_MASUK_KARSU_ID, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 ".$this->getField("SURAT_MASUK_KARSU_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function insertUsulanPegawaiInformasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_PEGAWAI_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_ID","PERSURATAN.SURAT_MASUK_PEGAWAI")); 

     	$str1= "
			INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI (
				SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, STATUS_BERKAS
				, JABATAN_RIWAYAT_AKHIR_ID, JABATAN_RIWAYAT_SEKARANG_ID, PENDIDIKAN_RIWAYAT_AKHIR_ID
				, PENDIDIKAN_RIWAYAT_SEKARANG_ID, GAJI_RIWAYAT_AKHIR_ID, GAJI_RIWAYAT_SEKARANG_ID
				, PANGKAT_RIWAYAT_AKHIR_ID, PANGKAT_RIWAYAT_SEKARANG_ID, SATUAN_KERJA_PEGAWAI_USULAN_ID
				, USULAN_PEGAWAI_INFORMASI_ID
				, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 
			VALUES (
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("STATUS_BERKAS").",
				 ".$this->getField("JABATAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("JABATAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PENDIDIKAN_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("GAJI_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("GAJI_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_AKHIR_ID").",
				 ".$this->getField("PANGKAT_RIWAYAT_SEKARANG_ID").",
				 ".$this->getField("SATUAN_KERJA_PEGAWAI_USULAN_ID").",
				 ".$this->getField("USULAN_PEGAWAI_INFORMASI_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_ID");
		$this->query = $str1;
		//echo $str;exit;
		$this->execQuery($str1);
		
		//update data detil usulan
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.USULAN_PEGAWAI_INFORMASI
				SET
					SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				WHERE USULAN_PEGAWAI_INFORMASI_ID= ".$this->getField("USULAN_PEGAWAI_INFORMASI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					JENIS_ID= ".$this->getField("JENIS_ID").",
				 	SURAT_MASUK_BKD_ID= '".$this->getField("SURAT_MASUK_BKD_ID")."',
				 	SURAT_MASUK_UPT_ID= '".$this->getField("SURAT_MASUK_UPT_ID")."',
				 	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
				 	KEPADA= '".$this->getField("KEPADA")."',
				 	STATUS_PILIH= '".$this->getField("STATUS_PILIH")."',
				 	STATUS_KEMBALI= ".$this->getField("STATUS_KEMBALI").",
				 	STATUS_PROGRES= ".$this->getField("STATUS_PROGRES")."
				WHERE  SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusBerkasSebelumTeknis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_BERKAS= 
					(
						SELECT STATUS_BERKAS + 1 FROM persuratan.SURAT_MASUK_PEGAWAI WHERE 1=1 AND SURAT_MASUK_BKD_ID = 
						(
							SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_BKD_DISPOSISI WHERE 1=1 AND SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")." GROUP BY SURAT_MASUK_BKD_ID
						)
						GROUP BY STATUS_BERKAS
					)
					
					".$this->getField("STATUS_BERKAS").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_BKD_ID= 
				(
					SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_BKD_DISPOSISI WHERE 1=1 AND SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")." GROUP BY SURAT_MASUK_BKD_ID
				)
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusBerkasUbahSebelumTeknis()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_BERKAS= 
					(
						SELECT STATUS_BERKAS FROM persuratan.SURAT_MASUK_PEGAWAI WHERE 1=1 AND SURAT_MASUK_BKD_ID = 
						(
							SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_BKD_DISPOSISI WHERE 1=1 AND SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")." GROUP BY SURAT_MASUK_BKD_ID
						)
						GROUP BY STATUS_BERKAS
					)
					
					".$this->getField("STATUS_BERKAS").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_BKD_ID= 
				(
					SELECT SURAT_MASUK_BKD_ID FROM persuratan.SURAT_MASUK_BKD_DISPOSISI WHERE 1=1 AND SURAT_MASUK_BKD_DISPOSISI_ID = ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID")." GROUP BY SURAT_MASUK_BKD_ID
				)
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusBerkasUpt()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_BERKAS= ".$this->getField("STATUS_BERKAS").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_UPT_ID= ".$this->getField("SURAT_MASUK_UPT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusBerkasBkd()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_BERKAS= ".$this->getField("STATUS_BERKAS").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_BKD_ID= ".$this->getField("SURAT_MASUK_BKD_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateStatusVerifikasi()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_VERIFIKASI= ".$this->getField("STATUS_VERIFIKASI").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function insertKarpegUsulan()
	{
		//".str_replace("0000", generateZeroDate($this->getField("PENDIDIKAN_RIWAYAT_ID"),4), $this->getField("TANGGAL_STTB")).",
		//TO_DATE(CONCAT((SELECT GENERATEZERO(CAST((SELECT COUNT(1) FROM PENDIDIKAN_RIWAYAT WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND STATUS = '3') AS TEXT),3)), '-00-00', 'YYYY-MM-DD')
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_KARPEG_ID", $this->getNextId("SURAT_MASUK_KARPEG_ID","persuratan.SURAT_MASUK_KARPEG"));
     	$str = "
			INSERT INTO persuratan.SURAT_MASUK_KARPEG (
				SURAT_MASUK_KARPEG_ID, JENIS_ID, JENIS_KARPEG, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
       			PEGAWAI_ID, NO_SURAT_KEHILANGAN, TANGGAL_SURAT_KEHILANGAN, KETERANGAN, 
				LAST_USER, LAST_DATE, LAST_LEVEL
			) 
			VALUES (
				  ".$this->getField("SURAT_MASUK_KARPEG_ID").",
				  ".$this->getField("JENIS_ID").",
				  ".$this->getField("JENIS_KARPEG").",
				  ".$this->getField("SURAT_MASUK_BKD_ID").",
				  ".$this->getField("SURAT_MASUK_UPT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  '".$this->getField("NO_SURAT_KEHILANGAN")."',
				  ".$this->getField("TANGGAL_SURAT_KEHILANGAN").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_KARPEG_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKarpegUsulan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.SURAT_MASUK_KARPEG
				SET  
				  JENIS_ID				= ".$this->getField("JENIS_ID").",
				  JENIS_KARPEG			= ".$this->getField("JENIS_KARPEG").",
				  SURAT_MASUK_BKD_ID	= ".$this->getField("SURAT_MASUK_BKD_ID").",
				  SURAT_MASUK_UPT_ID	= ".$this->getField("SURAT_MASUK_UPT_ID").",
				  PEGAWAI_ID			= ".$this->getField("PEGAWAI_ID").",
				  NO_SURAT_KEHILANGAN	= '".$this->getField("NO_SURAT_KEHILANGAN")."',
				  TANGGAL_SURAT_KEHILANGAN	= ".$this->getField("TANGGAL_SURAT_KEHILANGAN").",
				  KETERANGAN			= '".$this->getField("KETERANGAN")."',
				  LAST_USER				= '".$this->getField("LAST_USER")."',
				  LAST_DATE				= ".$this->getField("LAST_DATE").",
				  LAST_LEVEL			= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_KARPEG_ID = ".$this->getField("SURAT_MASUK_KARPEG_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }
	
	function insertKarsuUsulan()
	{
		$this->setField("SURAT_MASUK_KARSU_ID", $this->getNextId("SURAT_MASUK_KARSU_ID","persuratan.SURAT_MASUK_KARSU"));
     	$str = "
			INSERT INTO persuratan.SURAT_MASUK_KARSU (
				SURAT_MASUK_KARSU_ID, JENIS_ID, JENIS_KARSU, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
       			PEGAWAI_ID, SUAMI_ISTRI_ID, SUAMI_ISTRI_PISAH_ID, NIKAH_PERTAMA_PNS_STATUS, NIKAH_PERTAMA_PNS_STATUS_SI, NIKAH_PERTAMA_PNS_TANGGAL
       			, NIKAH_PERTAMA_PASANGAN_STATUS, NIKAH_PERTAMA_PASANGAN_STATUS_SI, NIKAH_PERTAMA_PASANGAN_TANGGAL
       			, JENIS_KESALAHAN, TERTULIS, SEHARUSNYA, NO_SURAT_KEHILANGAN, TANGGAL_SURAT_KEHILANGAN, KETERANGAN, 
				LAST_USER, LAST_DATE, LAST_LEVEL
			) 
			VALUES (
				  ".$this->getField("SURAT_MASUK_KARSU_ID").",
				  ".$this->getField("JENIS_ID").",
				  ".$this->getField("JENIS_KARSU").",
				  ".$this->getField("SURAT_MASUK_BKD_ID").",
				  ".$this->getField("SURAT_MASUK_UPT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("SUAMI_ISTRI_ID").",
				  ".$this->getField("SUAMI_ISTRI_PISAH_ID").",
				  ".$this->getField("NIKAH_PERTAMA_PNS_STATUS").",
				  ".$this->getField("NIKAH_PERTAMA_PNS_STATUS_SI").",
				  ".$this->getField("NIKAH_PERTAMA_PNS_TANGGAL").",
				  ".$this->getField("NIKAH_PERTAMA_PASANGAN_STATUS").",
				  ".$this->getField("NIKAH_PERTAMA_PASANGAN_STATUS_SI").",
				  ".$this->getField("NIKAH_PERTAMA_PASANGAN_TANGGAL").",
				  '".$this->getField("JENIS_KESALAHAN")."',
				  '".$this->getField("TERTULIS")."',
				  '".$this->getField("SEHARUSNYA")."',
				  '".$this->getField("NO_SURAT_KEHILANGAN")."',
				  ".$this->getField("TANGGAL_SURAT_KEHILANGAN").",
				  '".$this->getField("KETERANGAN")."',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL")."
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_KARSU_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateKarsuUsulan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE persuratan.SURAT_MASUK_KARSU
				SET  
				  JENIS_ID				= ".$this->getField("JENIS_ID").",
				  JENIS_KARSU			= ".$this->getField("JENIS_KARSU").",
				  SURAT_MASUK_BKD_ID	= ".$this->getField("SURAT_MASUK_BKD_ID").",
				  SURAT_MASUK_UPT_ID	= ".$this->getField("SURAT_MASUK_UPT_ID").",
				  PEGAWAI_ID			= ".$this->getField("PEGAWAI_ID").",
				  SUAMI_ISTRI_ID= ".$this->getField("SUAMI_ISTRI_ID").",
				  SUAMI_ISTRI_PISAH_ID= ".$this->getField("SUAMI_ISTRI_PISAH_ID").",
				  NIKAH_PERTAMA_PNS_STATUS= ".$this->getField("NIKAH_PERTAMA_PNS_STATUS").",
				  NIKAH_PERTAMA_PNS_STATUS_SI= ".$this->getField("NIKAH_PERTAMA_PNS_STATUS_SI").",
				  NIKAH_PERTAMA_PNS_TANGGAL= ".$this->getField("NIKAH_PERTAMA_PNS_TANGGAL").",
				  NIKAH_PERTAMA_PASANGAN_STATUS= ".$this->getField("NIKAH_PERTAMA_PASANGAN_STATUS").",
				  NIKAH_PERTAMA_PASANGAN_STATUS_SI= ".$this->getField("NIKAH_PERTAMA_PASANGAN_STATUS_SI").",
				  NIKAH_PERTAMA_PASANGAN_TANGGAL= ".$this->getField("NIKAH_PERTAMA_PASANGAN_TANGGAL").",
				  JENIS_KESALAHAN= '".$this->getField("JENIS_KESALAHAN")."',
				  TERTULIS= '".$this->getField("TERTULIS")."',
				  SEHARUSNYA= '".$this->getField("SEHARUSNYA")."',
				  NO_SURAT_KEHILANGAN	= '".$this->getField("NO_SURAT_KEHILANGAN")."',
				  TANGGAL_SURAT_KEHILANGAN	= ".$this->getField("TANGGAL_SURAT_KEHILANGAN").",
				  KETERANGAN			= '".$this->getField("KETERANGAN")."',
				  LAST_USER				= '".$this->getField("LAST_USER")."',
				  LAST_DATE				= ".$this->getField("LAST_DATE").",
				  LAST_LEVEL			= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_KARSU_ID = ".$this->getField("SURAT_MASUK_KARSU_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }

	function insertPendidikanUsulan()
	{
		//".str_replace("0000", generateZeroDate($this->getField("PENDIDIKAN_RIWAYAT_ID"),4), $this->getField("TANGGAL_STTB")).",
		//TO_DATE(CONCAT((SELECT GENERATEZERO(CAST((SELECT COUNT(1) FROM PENDIDIKAN_RIWAYAT WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND STATUS = '3') AS TEXT),3)), '-00-00', 'YYYY-MM-DD')
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENDIDIKAN_RIWAYAT_ID", $this->getNextId("PENDIDIKAN_RIWAYAT_ID","PENDIDIKAN_RIWAYAT"));
     	$str = "
			INSERT INTO PENDIDIKAN_RIWAYAT (
				PENDIDIKAN_RIWAYAT_ID, PEGAWAI_ID, PENDIDIKAN_ID, PENDIDIKAN_JURUSAN_ID, NAMA, NAMA_FAKULTAS, TEMPAT,
				TANGGAL_STTB, JURUSAN, STATUS_PENDIDIKAN, STATUS_TUGAS_IJIN_BELAJAR, STATUS, LAST_USER, LAST_DATE, LAST_LEVEL
			) 
			VALUES (
				  ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
				  ".$this->getField("PEGAWAI_ID").",
				  ".$this->getField("PENDIDIKAN_ID").",
				  ".$this->getField("PENDIDIKAN_JURUSAN_ID").",
				  '".$this->getField("NAMA")."',
				  '".$this->getField("NAMA_FAKULTAS")."',
				  '".$this->getField("TEMPAT")."',
				  (SELECT TO_DATE(CONCAT((SELECT GENERATEZERO(CAST((SELECT COUNT(1) FROM PENDIDIKAN_RIWAYAT WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")." AND STATUS = '3') + 1 AS TEXT),3)), '-00-00'), 'YYYY-MM-DD')),
				  '".$this->getField("JURUSAN")."',
				  '3',
				  ".$this->getField("STATUS_TUGAS_IJIN_BELAJAR").",
				  '3',
				  '".$this->getField("LAST_USER")."',
				  ".$this->getField("LAST_DATE").",
				  ".$this->getField("LAST_LEVEL")."
			)
		"; 	
		$this->id = $this->getField("PENDIDIKAN_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function updatePendidikanUsulan()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENDIDIKAN_RIWAYAT
				SET  
				  PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID").",
				  PENDIDIKAN_JURUSAN_ID= ".$this->getField("PENDIDIKAN_JURUSAN_ID").",
				  NAMA= '".$this->getField("NAMA")."',
				  NAMA_FAKULTAS= '".$this->getField("NAMA_FAKULTAS")."',
				  TEMPAT= '".$this->getField("TEMPAT")."',
				  JURUSAN= '".$this->getField("JURUSAN")."',
				  STATUS_PENDIDIKAN= '3',
				  STATUS_TUGAS_IJIN_BELAJAR= '".$this->getField("STATUS_TUGAS_IJIN_BELAJAR")."',
				  LAST_USER= '".$this->getField("LAST_USER")."',
				  LAST_DATE= ".$this->getField("LAST_DATE").",
				  LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE PENDIDIKAN_RIWAYAT_ID = ".$this->getField("PENDIDIKAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		//echo $this->errorMsg;exit;
		return $this->execQuery($str);
    }
	
	function updateUpt()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					SURAT_MASUK_BKD_ID= ".$this->getField("SURAT_MASUK_BKD_ID").",
					STATUS_BERKAS= ".$this->getField("STATUS_BERKAS").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateUsulanSurat()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					USULAN_SURAT_URUT= (SELECT COALESCE(MAX(USULAN_SURAT_URUT),0) + 1 FROM persuratan.SURAT_MASUK_PEGAWAI WHERE USULAN_SURAT_ID = ".$this->getField("USULAN_SURAT_ID")."),
					USULAN_SURAT_ID= ".$this->getField("USULAN_SURAT_ID").",
					STATUS_BERKAS = 10,
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function resetbidang()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					SURAT_MASUK_BKD_ID= NULL,
					STATUS_BERKAS= ".$this->getField("STATUS_BERKAS").",
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
		$str1= "
				DELETE FROM PERSURATAN.SURAT_MASUK_PEGAWAI_LOG
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				";
		$this->query = $str1;
		$this->execQuery($str1);

        $str = "
				DELETE FROM PERSURATAN.SURAT_MASUK_PEGAWAI
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
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

    function selectByParamsSuamiIstri($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		$str = "
		SELECT 	
			A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.TANGGAL_KAWIN, A.KARTU, 
			A.STATUS_PNS, A.NIP_PNS, A.PEKERJAAN, A.STATUS_TUNJANGAN, A.STATUS_BAYAR, A.BULAN_BAYAR, A.STATUS, A.STATUS_S_I, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,
			CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '3' THEN 'Meninggal' ELSE 'Belum di set' END STATUS_S_I_NAMA
			, A.SURAT_NIKAH, A.NIK, A.CERAI_SURAT, A.CERAI_TANGGAL, A.CERAI_TMT, A.KEMATIAN_SURAT, A.KEMATIAN_TANGGAL, A.KEMATIAN_TMT
		FROM SUAMI_ISTRI A
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

    function selectByParamsAmbilAnak($pegawaiid="", $kategori="", $statement='',$order=' ORDER BY A.ANAK_USIA DESC')
	{
		$str = "
		SELECT 
			A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, NOMOR
			, A.ANAK_NAMA, A.ANAK_USIA, A.ANAK_TANGGAL_LAHIR, A.ANAK_STATUS_NAMA
			, A.SUAMI_ISTRI_NAMA, A.SUAMI_ISTRI_STATUS_NAMA, A.TMT
		FROM
		(
			SELECT 
				ROW_NUMBER () OVER (ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC) NOMOR,
				P.TMT,
				A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, B.NAMA SUAMI_ISTRI_NAMA
				, CASE B.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '2' THEN 'Meninggal' ELSE 'Belum di set' END SUAMI_ISTRI_STATUS_NAMA
				, A.NAMA ANAK_NAMA, A.TANGGAL_LAHIR ANAK_TANGGAL_LAHIR
				, AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) ANAK_USIA
				, CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' ELSE 'Angkat' END ANAK_STATUS_NAMA
			FROM 
			(
				SELECT P.PEGAWAI_ID, TMT, JENIS FROM PENSIUN P WHERE REPLACE(JENIS, 'pensiun', '') = '".$kategori."'
			) P
			INNER JOIN ANAK A ON P.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
			WHERE A.STATUS_AKTIF = '1'
			AND CAST(AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) AS NUMERIC) <= 25
			AND P.PEGAWAI_ID = ".$pegawaiid."
			ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC
		) A
		WHERE 1=1
		"; 
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function selectByParamsAnak($pegawaiid="", $kategori="", $statement='',$order=' ORDER BY CAST(A.ANAK_USIA AS NUMERIC) DESC')
	{
		// GROUP BY SURAT_MASUK_PEGAWAI_ID, JENIS_ID, KATEGORI, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID
		$str = "
		SELECT 
			A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, NOMOR
			, A.ANAK_NAMA, A.ANAK_USIA, A.ANAK_TANGGAL_LAHIR, A.ANAK_STATUS_NAMA
			, A.SUAMI_ISTRI_NAMA, A.SUAMI_ISTRI_STATUS_NAMA, A.TMT
		FROM
		(
			SELECT 
				ROW_NUMBER () OVER (ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC) NOMOR,
				P.TMT,
				A.ANAK_ID, A.PEGAWAI_ID, A.SUAMI_ISTRI_ID, B.NAMA SUAMI_ISTRI_NAMA
				, CASE B.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai' WHEN '2' THEN 'Meninggal' ELSE 'Belum di set' END SUAMI_ISTRI_STATUS_NAMA
				, A.NAMA ANAK_NAMA, A.TANGGAL_LAHIR ANAK_TANGGAL_LAHIR
				, AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) ANAK_USIA
				, CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' ELSE 'Angkat' END ANAK_STATUS_NAMA
			FROM 
			(
				SELECT P.PEGAWAI_ID, TMT, JENIS, ANAK_ID
				FROM PENSIUN P
				INNER JOIN
				(
					SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, KATEGORI, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, ANAK_ID
					FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK
				) P1 ON P.JENIS = P1.KATEGORI AND P.PEGAWAI_ID = P1.PEGAWAI_ID
				WHERE REPLACE(P.JENIS, 'pensiun', '') = '".$kategori."' AND P.PEGAWAI_ID = ".$pegawaiid."
			) P
			INNER JOIN ANAK A ON P.ANAK_ID = A.ANAK_ID
			LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
			WHERE A.STATUS_AKTIF = '1'
			AND A.STATUS_KELUARGA = '1' AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			AND CAST(AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) AS NUMERIC) < 25
			AND P.PEGAWAI_ID = ".$pegawaiid."
			ORDER BY AMBIL_UMUR_TAHUN(A.TANGGAL_LAHIR, P.TMT) DESC
		) A
		WHERE 1=1
		"; 
		// <= 25
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

	function selectByParamsCombo($paramsArray=array(),$limit=-1,$from=-1, $statement='', $sTable= "", $sField="", $sJoin="")
	{
		$str = "SELECT 
					   ".$sField." NAMA
				FROM ".$sTable." A
				".$sJoin."
				WHERE 1=1"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." GROUP BY ".$sField." ORDER BY ".$sField;
		$this->query = $str;
				
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsKepala($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
					, AMBIL_SATKER_JABATAN(A.SATUAN_KERJA_ID) SATUAN_KERJA_KEPALA
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA, A.PANGKAT_ID
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA, A.TIPE_PEGAWAI_ID
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_PEGAWAI_ID ASC')
	{
		$str = "
				SELECT
					A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
					, A.STATUS_PILIH, A.STATUS_KEMBALI, A.STATUS_PROGRES
				FROM persuratan.SURAT_MASUK_PEGAWAI A
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
	
	function selectByParamsUsulanPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY SMP.SURAT_MASUK_PEGAWAI_ID ASC')
	{
		$str = "
			SELECT A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.NIP_BARU
				, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID
				, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
				, AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) SATUAN_KERJA_NAMA_UPT
				, CASE WHEN SMP.SURAT_MASUK_UPT_ID IS NOT NULL THEN AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID) ELSE AMBIL_SATKER_NAMA(SMB.SATUAN_KERJA_ASAL_ID) END SATUAN_KERJA_NAMA_BKD
				, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_LENGKAP
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, A.NAMA NAMA_SAJA
				, SMB.NOMOR NOMOR_USUL_BKDPP, L.LAST_DATE PROSES_TANGGAL, L.INFO_PROSES PROSES_STATUS
				, SMP.STATUS_KEMBALI, SMP.STATUS_PERNAH_TURUN
				, SKR.NOMOR NOMOR_SURAT_KELUAR, SMP.STATUS_SURAT_KELUAR
				, SMP.USULAN_SURAT_URUT
			FROM PEGAWAI A
			INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
			LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
			LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
			LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
			LEFT JOIN
			(
				SELECT A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
				, A.SATUAN_KERJA_ID, A.INFO_LOG, A.LAST_USER, A.LAST_DATE
				, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
				INNER JOIN
				(
				SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID, MAX(LAST_DATE) LAST_DATE
				FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
				GROUP BY SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID
				) B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID AND A.JENIS_ID = B.JENIS_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID AND A.LAST_DATE = B.LAST_DATE
			) L ON SMP.SURAT_MASUK_PEGAWAI_ID = L.SURAT_MASUK_PEGAWAI_ID AND SMP.JENIS_ID = L.JENIS_ID AND SMP.PEGAWAI_ID = L.PEGAWAI_ID
			LEFT JOIN persuratan.SURAT_KELUAR_BKD SKR ON SMP.USULAN_SURAT_ID = SKR.USULAN_SURAT_ID
			WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }

    function getCountByParamsUsulanPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT
		FROM PEGAWAI A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
			, A.SATUAN_KERJA_ID, A.INFO_LOG, A.LAST_USER, A.LAST_DATE
			, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
			FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
			INNER JOIN
			(
			SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID, MAX(LAST_DATE) LAST_DATE
			FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
			GROUP BY SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID
			) B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID AND A.JENIS_ID = B.JENIS_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID AND A.LAST_DATE = B.LAST_DATE
		) L ON SMP.SURAT_MASUK_PEGAWAI_ID = L.SURAT_MASUK_PEGAWAI_ID AND SMP.JENIS_ID = L.JENIS_ID AND SMP.PEGAWAI_ID = L.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SKR ON SMP.USULAN_SURAT_ID = SKR.USULAN_SURAT_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA1
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, PANG_US.KODE US_PANGKAT_RIWAYAT_KODE
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES, SMP.STATUS_TMS, SMP.STATUS_PERNAH_TURUN
					, SMP.STATUS_VERIFIKASI, SMP.STATUS_SURAT_KELUAR
					, SMP.JABATAN_RIWAYAT_AKHIR_ID, SMP.JABATAN_RIWAYAT_SEKARANG_ID, SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID
					, SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID, SMP.GAJI_RIWAYAT_AKHIR_ID, SMP.GAJI_RIWAYAT_SEKARANG_ID
					, SMP.PANGKAT_RIWAYAT_AKHIR_ID, SMP.PANGKAT_RIWAYAT_SEKARANG_ID
					, PEN_RIW.PENDIDIKAN_ID, PEN_RIW.PENDIDIKAN_NAMA, PEN_RIW.STATUS_PENDIDIKAN_NAMA, PEN_RIW.JURUSAN, PEN_RIW.NAMA_SEKOLAH
					, PEN_US_RIW.STATUS_TUGAS_IJIN_BELAJAR
					, PEN_US_RIW.PENDIDIKAN_ID PENDIDIKAN_ID_US, PEN_US_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.PENDIDIKAN_JURUSAN_ID PENDIDIKAN_JURUSAN_ID_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, SMP.KETERANGAN_TEKNIS
					, TO_CHAR(SMB.TANGGAL, 'YYYY') TAHUN_SURATBAK
					, CASE
					WHEN SMP.JENIS_ID = 7 THEN
						CASE
						WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(PENSIUN_TMT, 'MM') = '01'
						THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 1 AS TEXT)
						WHEN (SMB.TANGGAL >= PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT)
						THEN CAST(CAST(TO_CHAR(PENSIUN_TMT, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						WHEN (SMB.TANGGAL < PENSIUN_TMT OR SMU.TANGGAL >= PENSIUN_TMT) AND TO_CHAR(SMB.TANGGAL, 'YYYY') = TO_CHAR(PENSIUN_TMT, 'YYYY')
						THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
						ELSE 
							CASE
							WHEN TO_CHAR(SMB.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
							WHEN TO_CHAR(SMU.TANGGAL, 'MM') = '01' THEN CAST(CAST(TO_CHAR(SMU.TANGGAL, 'YYYY') AS NUMERIC) - 1 AS TEXT)
							ELSE
								CASE WHEN SMP.SURAT_MASUK_BKD_ID IS NOT NULL THEN 
									CAST(CAST(TO_CHAR(SMB.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
								ELSE
									CAST(CAST(TO_CHAR(SMu.TANGGAL, 'YYYY') AS NUMERIC) - 0 AS TEXT)
								END
							END
						END
					WHEN SMP.JENIS_ID = 10 THEN
						CASE WHEN SMP.SURAT_MASUK_BKD_ID IS NOT NULL THEN CAST(CAST(TO_CHAR(SMB.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 1 AS TEXT)
						ELSE CAST(CAST(TO_CHAR(SMU.TANGGAL_PERIODE_KP, 'YYYY') AS NUMERIC) - 1 AS TEXT)
						END
					WHEN SMP.SURAT_MASUK_BKD_ID IS NOT NULL THEN TO_CHAR(SMB.TANGGAL, 'YYYY')
					ELSE TO_CHAR(SMU.TANGGAL, 'YYYY')
					END TAHUN_SURAT
					, SMP.SURAT_MASUK_KARPEG_ID, KPG_RIW.JENIS_KARPEG, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.KETERANGAN
					, AMBIL_SATKER_NAMA_DETIL(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_PEGAWAI_USULAN_NAMA
					, CASE KPG_RIW.JENIS_KARPEG WHEN 1 THEN 'Baru' WHEN 2 THEN 'Revisi' WHEN 3 THEN 'Kehilangan' END JENIS_KARPEG_NAMA
					, SMP.USULAN_SURAT_ID, US.STATUS_KIRIM STATUS_KIRIM_USULAN
					, SMP.KATEGORI, CASE WHEN SMP.KATEGORI = 'bup' AND SMP.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN SMP.KATEGORI = 'meninggal' AND SMP.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
					, CASE SMP.KP_JENIS
					WHEN 'kpreguler' THEN 'KP Reguler'
					WHEN 'kpstruktural' THEN 'KP Pilihan (Jabatan Struktural)'
					WHEN 'kpjft' THEN 'KP Pilihan (Jabatan Fungsional Tertentu)'
					WHEN 'kppi' THEN 'KP Pilihan (Penyesuian Ijazah)'
					WHEN 'kpbtugas' THEN 'KP Pilihan (Sedang Melaksanakan Tugas)'
					WHEN 'kpstugas' THEN 'KP Pilihan (Setelah Selesai Tugas Belajar)'
					END KP_JENIS_NAMA
					, SMP.KETERANGAN_PENSIUN, SMP.KP_PANGKAT_ID, SMP.KP_JENIS, SMP.KP_STATUS_SURAT_TANDA_LULUS, SMP.KP_SURAT_TANDA_LULUS_ID
					, SMP.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI, SMP.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				LEFT JOIN 
				(
					SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_BKD A
					LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
				) SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
				LEFT JOIN 
				(
					SELECT A.*, TANGGAL_PERIODE TANGGAL_PERIODE_KP FROM PERSURATAN.SURAT_MASUK_UPT A
					LEFT JOIN PENGATURAN_KENAIKAN_PANGKAT B ON A.PENGATURAN_KENAIKAN_PANGKAT_ID = B.PENGATURAN_KENAIKAN_PANGKAT_ID
				) SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
				LEFT JOIN
				(
					SELECT PEGAWAI_ID PENSIUN_PEGAWAI_ID
					, CASE WHEN CAST(TO_CHAR(TMT, 'MM') AS INTEGER) >= 4 THEN CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) ELSE CAST(TO_CHAR(TMT, 'YYYY') AS NUMERIC) - 1 END JENIS_PENSIUN_TAHUN
					, TMT PENSIUN_TMT
					FROM PENSIUN
				) DP ON A.PEGAWAI_ID = DP.PENSIUN_PEGAWAI_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN PANGKAT PANG_US ON SMP.KP_PANGKAT_ID = PANG_US.PANGKAT_ID
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
						, A.STATUS_TUGAS_IJIN_BELAJAR
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_US_RIW ON SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID = PEN_US_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN persuratan.USULAN_SURAT US ON SMP.USULAN_SURAT_ID = US.USULAN_SURAT_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoringKpUpt($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA1
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, PANG_US.KODE US_PANGKAT_RIWAYAT_KODE
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES, SMP.STATUS_TMS, SMP.STATUS_PERNAH_TURUN
					, SMP.STATUS_VERIFIKASI, SMP.STATUS_SURAT_KELUAR
					, SMP.JABATAN_RIWAYAT_AKHIR_ID, SMP.JABATAN_RIWAYAT_SEKARANG_ID, SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID
					, SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID, SMP.GAJI_RIWAYAT_AKHIR_ID, SMP.GAJI_RIWAYAT_SEKARANG_ID
					, SMP.PANGKAT_RIWAYAT_AKHIR_ID, SMP.PANGKAT_RIWAYAT_SEKARANG_ID
					, PEN_RIW.PENDIDIKAN_ID, PEN_RIW.PENDIDIKAN_NAMA, PEN_RIW.STATUS_PENDIDIKAN_NAMA, PEN_RIW.JURUSAN, PEN_RIW.NAMA_SEKOLAH
					, PEN_US_RIW.STATUS_TUGAS_IJIN_BELAJAR
					, PEN_US_RIW.PENDIDIKAN_ID PENDIDIKAN_ID_US, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.PENDIDIKAN_JURUSAN_ID PENDIDIKAN_JURUSAN_ID_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, SMP.KETERANGAN_TEKNIS
					, TO_CHAR(SMB.TANGGAL, 'YYYY') TAHUN_SURAT
					, SMP.SURAT_MASUK_KARPEG_ID, KPG_RIW.JENIS_KARPEG, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.KETERANGAN
					, AMBIL_SATKER_NAMA_DETIL(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_PEGAWAI_USULAN_NAMA
					, CASE KPG_RIW.JENIS_KARPEG WHEN 1 THEN 'Baru' WHEN 2 THEN 'Revisi' WHEN 3 THEN 'Kehilangan' END JENIS_KARPEG_NAMA
					, SMP.USULAN_SURAT_ID, US.STATUS_KIRIM STATUS_KIRIM_USULAN
					, SMP.KATEGORI, CASE WHEN SMP.KATEGORI = 'bup' AND SMP.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN SMP.KATEGORI = 'meninggal' AND SMP.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
					, CASE SMP.KP_JENIS
					WHEN 'kpreguler' THEN 'KP Reguler'
					WHEN 'kpstruktural' THEN 'KP Pilihan (Jabatan Struktural)'
					WHEN 'kpjft' THEN 'KP Pilihan (Jabatan Fungsional Tertentu)'
					WHEN 'kppi' THEN 'KP Pilihan (Penyesuian Ijazah)'
					WHEN 'kpbtugas' THEN 'KP Pilihan (Sedang Melaksanakan Tugas)'
					WHEN 'kpstugas' THEN 'KP Pilihan (Setelah Selesai Tugas Belajar)'
					END KP_JENIS_NAMA
					, SMP.KETERANGAN_PENSIUN, SMP.KP_PANGKAT_ID, SMP.KP_JENIS, SMP.KP_STATUS_SURAT_TANDA_LULUS, SMP.KP_SURAT_TANDA_LULUS_ID
					, SMP.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI, SMP.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				LEFT JOIN persuratan.SURAT_MASUK_UPT SMB ON SMP.SURAT_MASUK_UPT_ID = SMB.SURAT_MASUK_UPT_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN PANGKAT PANG_US ON SMP.KP_PANGKAT_ID = PANG_US.PANGKAT_ID
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
						, A.STATUS_TUGAS_IJIN_BELAJAR
					FROM PENDIDIKAN_RIWAYAT A
					LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
					LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
					WHERE 1 = 1
				) PEN_US_RIW ON SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID = PEN_US_RIW.PENDIDIKAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN persuratan.USULAN_SURAT US ON SMP.USULAN_SURAT_ID = US.USULAN_SURAT_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoringKaris($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA1
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES, SMP.STATUS_TMS, SMP.STATUS_PERNAH_TURUN
					, SMP.STATUS_VERIFIKASI, SMP.STATUS_SURAT_KELUAR
					, SMP.JABATAN_RIWAYAT_AKHIR_ID, SMP.JABATAN_RIWAYAT_SEKARANG_ID, SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID
					, SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID, SMP.GAJI_RIWAYAT_AKHIR_ID, SMP.GAJI_RIWAYAT_SEKARANG_ID
					, SMP.PANGKAT_RIWAYAT_AKHIR_ID, SMP.PANGKAT_RIWAYAT_SEKARANG_ID
					, PEN_RIW.PENDIDIKAN_ID, PEN_RIW.PENDIDIKAN_NAMA, PEN_RIW.STATUS_PENDIDIKAN_NAMA, PEN_RIW.JURUSAN, PEN_RIW.NAMA_SEKOLAH
					, PEN_US_RIW.PENDIDIKAN_ID PENDIDIKAN_ID_US, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.PENDIDIKAN_JURUSAN_ID PENDIDIKAN_JURUSAN_ID_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, SMP.KETERANGAN_TEKNIS, TO_CHAR(SMB.TANGGAL, 'YYYY') TAHUN_SURAT
					, SMP.SURAT_MASUK_KARSU_ID, KPG_RIW.JENIS_KARSU, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.KETERANGAN
					, KPG_RIW.JENIS_KESALAHAN, KPG_RIW.TERTULIS, KPG_RIW.SEHARUSNYA
					, KPG_RIW.SUAMI_ISTRI_ID, KPG_RIW.SUAMI_ISTRI_PISAH_ID
					, KPG_RIW.SUAMI_ISTRI_NAMA, KPG_RIW.SUAMI_ISTRI_TANGGAL_LAHIR, KPG_RIW.SUAMI_ISTRI_TANGGAL_KAWIN
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PNS_STATUS, KPG_RIW.SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I, KPG_RIW.SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PNS_TANGGAL
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS, KPG_RIW.SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_NAMA
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I, KPG_RIW.SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I_NAMA
					, KPG_RIW.SUAMI_ISTRI_PERTAMA_PASANGAN_TANGGAL
					, AMBIL_SATKER_NAMA_DETIL(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_PEGAWAI_USULAN_NAMA
					, CASE KPG_RIW.JENIS_KARSU WHEN 1 THEN 'Baru' WHEN 2 THEN 'Revisi' WHEN 3 THEN 'Kehilangan' END JENIS_KARSU_NAMA
					, SMP.USULAN_SURAT_ID, US.STATUS_KIRIM STATUS_KIRIM_USULAN
					, SMP.KATEGORI, CASE WHEN SMP.KATEGORI = 'bup' AND SMP.JENIS_ID = 7 THEN 'Pensiun BUP' WHEN SMP.KATEGORI = 'meninggal' AND SMP.JENIS_ID = 7 THEN 'Pensiun Janda/Duda' END KATEGORI_NAMA
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
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
					SURAT_MASUK_KARSU_ID, JENIS_ID, JENIS_KARSU, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
					A.PEGAWAI_ID, NO_SURAT_KEHILANGAN, TANGGAL_SURAT_KEHILANGAN, KETERANGAN
					, JENIS_KESALAHAN, TERTULIS, SEHARUSNYA
					, A.SUAMI_ISTRI_ID, A.SUAMI_ISTRI_PISAH_ID, B.NAMA SUAMI_ISTRI_NAMA, B.TANGGAL_LAHIR SUAMI_ISTRI_TANGGAL_LAHIR, B.TANGGAL_KAWIN SUAMI_ISTRI_TANGGAL_KAWIN
					, NIKAH_PERTAMA_PNS_STATUS SUAMI_ISTRI_PERTAMA_PNS_STATUS, CASE WHEN COALESCE(NIKAH_PERTAMA_PNS_STATUS, 0) > 0 THEN 'Tidak' ELSE 'Ya' END SUAMI_ISTRI_PERTAMA_PNS_STATUS_NAMA
					, NIKAH_PERTAMA_PNS_STATUS_SI SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I, CASE NIKAH_PERTAMA_PNS_STATUS_SI WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' END SUAMI_ISTRI_PERTAMA_PNS_STATUS_S_I_NAMA
					, NIKAH_PERTAMA_PNS_TANGGAL SUAMI_ISTRI_PERTAMA_PNS_TANGGAL
					, NIKAH_PERTAMA_PASANGAN_STATUS SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS, CASE WHEN COALESCE(NIKAH_PERTAMA_PASANGAN_STATUS, 0) <= 0 THEN 'Tidak' ELSE 'Ya' END SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_NAMA
					, NIKAH_PERTAMA_PASANGAN_STATUS_SI SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I, CASE NIKAH_PERTAMA_PASANGAN_STATUS_SI WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' END SUAMI_ISTRI_PERTAMA_PASANGAN_STATUS_S_I_NAMA
					, NIKAH_PERTAMA_PASANGAN_TANGGAL SUAMI_ISTRI_PERTAMA_PASANGAN_TANGGAL
					FROM persuratan.SURAT_MASUK_KARSU A
					INNER JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
					WHERE 1=1
				) KPG_RIW ON SMP.SURAT_MASUK_KARSU_ID = KPG_RIW.SURAT_MASUK_KARSU_ID
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
				LEFT JOIN persuratan.USULAN_SURAT US ON SMP.USULAN_SURAT_ID = US.USULAN_SURAT_ID
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
	
	function selectByParamsMonitoringPensiun($paramsArray=array(),$limit=-1,$from=-1, $kategori="", $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		// , SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 1) PENSIUN_THBAK
		// , SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 2) PENSIUN_BLBAK
		// INNER JOIN SK_CPNS SKC ON SKC.PEGAWAI_ID = P.PEGAWAI_ID
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA1
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES, SMP.STATUS_TMS, SMP.STATUS_PERNAH_TURUN
					, SMP.STATUS_VERIFIKASI, SMP.STATUS_SURAT_KELUAR
					, SMP.JABATAN_RIWAYAT_AKHIR_ID, SMP.JABATAN_RIWAYAT_SEKARANG_ID, SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID
					, SMP.PENDIDIKAN_RIWAYAT_SEKARANG_ID, SMP.GAJI_RIWAYAT_AKHIR_ID, SMP.GAJI_RIWAYAT_SEKARANG_ID
					, SMP.PANGKAT_RIWAYAT_AKHIR_ID, SMP.PANGKAT_RIWAYAT_SEKARANG_ID
					, PEN_RIW.PENDIDIKAN_ID, PEN_RIW.PENDIDIKAN_NAMA, PEN_RIW.STATUS_PENDIDIKAN_NAMA, PEN_RIW.JURUSAN, PEN_RIW.NAMA_SEKOLAH
					, PEN_US_RIW.PENDIDIKAN_ID PENDIDIKAN_ID_US, PEN_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.PENDIDIKAN_JURUSAN_ID PENDIDIKAN_JURUSAN_ID_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.TEMPAT TEMPAT_US
					, SMP.KETERANGAN_TEKNIS, TO_CHAR(SMB.TANGGAL, 'YYYY') TAHUN_SURAT
					, SMP.SURAT_MASUK_KARPEG_ID, KPG_RIW.JENIS_KARPEG, KPG_RIW.NO_SURAT_KEHILANGAN, KPG_RIW.TANGGAL_SURAT_KEHILANGAN, KPG_RIW.KETERANGAN
					, AMBIL_SATKER_NAMA_DETIL(SMP.SATUAN_KERJA_PEGAWAI_USULAN_ID) SATUAN_KERJA_PEGAWAI_USULAN_NAMA
					, CASE KPG_RIW.JENIS_KARPEG WHEN 1 THEN 'Baru' WHEN 2 THEN 'Revisi' WHEN 3 THEN 'Kehilangan' END JENIS_KARPEG_NAMA
					, SMP.USULAN_SURAT_ID, US.STATUS_KIRIM STATUS_KIRIM_USULAN
					, P.PANGKAT_KODE, P.PANGKAT_TMT, P.PANGKAT_TH, P.PANGKAT_BL, P.PENSIUN_TMT
					, P.PENSIUN_TH, P.PENSIUN_BL
					, P.PENSIUN_TANGGAL_KEMATIAN, P.PENSIUN_NOMOR_SK, P.PENSIUN_TANGGAL_SK_KEMATIAN, P.PENSIUN_KETERANGAN
					, P.JABATAN_ESELON, P.JABATAN_NAMA, P.JABATAN_TMT
					, SMP.KETERANGAN_PENSIUN
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN 
				(
					SELECT
						P.PEGAWAI_ID
						, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT, PR.MASA_KERJA_TAHUN PANGKAT_TH, PR.MASA_KERJA_BULAN PANGKAT_BL
						, P.TMT PENSIUN_TMT
						, CASE WHEN (COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) % 12 = 0
						THEN
						(COALESCE(CAST(PR.MASA_KERJA_TAHUN AS NUMERIC),0) + FLOOR((COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) / 12))
						ELSE
						(COALESCE(CAST(PR.MASA_KERJA_TAHUN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 1) AS NUMERIC),0))
						END
						PENSIUN_TH
						, CASE WHEN (COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0)) % 12 = 0 
						THEN 0
						ELSE
						(COALESCE(CAST(PR.MASA_KERJA_BULAN AS NUMERIC),0) + COALESCE(CAST(SPLIT_PART(AMBIL_UMUR_DUK(PR.TMT_PANGKAT, P.TMT), ' - ', 2) AS NUMERIC),0))
						END PENSIUN_BL
						, P.TANGGAL_KEMATIAN PENSIUN_TANGGAL_KEMATIAN, P.NOMOR_SK PENSIUN_NOMOR_SK, P.TANGGAL_SK_KEMATIAN PENSIUN_TANGGAL_SK_KEMATIAN, P.KETERANGAN PENSIUN_KETERANGAN
						, JR.ESELON_NAMA JABATAN_ESELON, JR.JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
						, P.GAJI_RIWAYAT_ID, P.PANGKAT_RIWAYAT_ID, P.SATUAN_KERJA_ID
					FROM PENSIUN P
					INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = P.PEGAWAI_ID
					INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = P.PANGKAT_RIWAYAT_ID
					INNER JOIN
					(
						SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
						FROM JABATAN_RIWAYAT A
						LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
					) JR ON JR.JABATAN_RIWAYAT_ID = P.JABATAN_RIWAYAT_ID
					WHERE 1 = 1 AND REPLACE(P.JENIS, 'pensiun', '') = '".$kategori."'
				) P ON A.PEGAWAI_ID = P.PEGAWAI_ID
				LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
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
				LEFT JOIN persuratan.USULAN_SURAT US ON SMP.USULAN_SURAT_ID = US.USULAN_SURAT_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoringKenaikanPangkat($paramsArray=array(),$limit=-1,$from=-1, $kategori="", $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		// , CASE
		// WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_STATUS_SURAT_TANDA_LULUS = 1 THEN 'Ada STLUD'
		// WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_STATUS_SURAT_TANDA_LULUS = 3 THEN 'Tidak Ada STLUD'
		// WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_DIKLAT_ID = 1 THEN 'Ada Diklatpim'
		// WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_DIKLAT_ID = 4 THEN 'Tidak Ada Diklatpim'
		// ELSE '' END KP_STATUS_STRUKTURAL_NAMA
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU, A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID
			, A.SATUAN_KERJA_ID, AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, PR.PANGKAT_ID, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT, PR.MASA_KERJA_TAHUN PANGKAT_TH, PR.MASA_KERJA_BULAN PANGKAT_BL
			, A.JABATAN_RIWAYAT_ID, JR.ESELON_NAMA JABATAN_ESELON, JR.NAMA JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PENR.PENDIDIKAN_JURUSAN_NAMA, PENR.TANGGAL_STTB, PENR.STATUS_PENDIDIKAN_NAMA
			, CASE
			WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_STATUS_SURAT_TANDA_LULUS = 1 THEN 'STLUD'
			WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_DIKLAT_ID = 1 THEN 'Diklatpim'
			WHEN SMP.KP_DIKLAT_ID = 4 OR SMP.KP_STATUS_SURAT_TANDA_LULUS = 3 THEN 'Tidak Ada'
			ELSE '' END KP_STATUS_STRUKTURAL_NAMA
			, CASE
			WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_STATUS_SURAT_TANDA_LULUS = 1 THEN '1'
			WHEN SMP.KP_PANGKAT_ID = 41 AND SMP.KP_DIKLAT_ID = 1 THEN '2'
			ELSE '' END KP_STATUS_STRUKTURAL_ID
			, SMP.KP_DIKLAT_ID, SMP.KP_DIKLAT_STRUKTURAL_ID, DS.NO_STTPP KP_DIKLAT_STRUKTURAL_NAMA
			, CASE SMP.KP_JENIS
			WHEN 'kpreguler' THEN 'KP Reguler'
			WHEN 'kpstruktural' THEN 'KP Pilihan (Jabatan Struktural)'
			WHEN 'kpjft' THEN 'KP Pilihan (Jabatan Fungsional Tertentu)'
			WHEN 'kppi' THEN 'KP Pilihan (Penyesuian Ijazah)'
			WHEN 'kpbtugas' THEN 'KP Pilihan (Sedang Melaksanakan Tugas)'
			WHEN 'kpstugas' THEN 'KP Pilihan (Setelah Selesai Tugas Belajar)'
			END KP_JENIS_NAMA
			, SMP.KETERANGAN_PENSIUN, SMP.KP_PANGKAT_ID, SMP.KP_JENIS, SMP.KP_STATUS_SURAT_TANDA_LULUS, SMP.KP_SURAT_TANDA_LULUS_ID
			, SMP.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI, SMP.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
			, CASE SMP.KP_STATUS_SURAT_TANDA_LULUS WHEN 1 THEN 'Ya' ELSE 'Tidak' END KP_STATUS_SURAT_TANDA_LULUS_NAMA
			, CASE SMP.KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI WHEN 1 THEN 'Ya' ELSE 'Tidak' END KP_STATUS_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_NAMA,
			STL.NO_STLUD KP_NO_STLUD, PENR1.PENDIDIKAN_JURUSAN_NAMA KP_PENDIDIKAN_JURUSAN_NAMA, SPLIT_PART(PF.PATH, '/', 3) KP_PATH_NAMA
			, PANG.KODE KP_PANGKAT_NAMA
			, SMP.KP_PAK_LAMA_ID, P_LAMA.NO_SK KP_PAK_LAMA_NAMA, SMP.KP_PAK_BARU_ID, P_BARU.NO_SK KP_PAK_BARU_NAMA
			, SMP.KP_STATUS_SERTIFIKAT_KEASLIAN
			, CASE SMP.KP_STATUS_SERTIFIKAT_KEASLIAN WHEN 1 THEN 'Ya' ELSE 'Tidak' END KP_STATUS_SERTIFIKAT_KEASLIAN_NAMA
			, SPLIT_PART(PFSA.PATH, '/', 3) KP_SERTIFIKASI_ASLI_PATH_NAMA
			, SMP.KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
			, SMP.KP_STATUS_SERTIFIKAT_PENDIDIK
			, CASE SMP.KP_STATUS_SERTIFIKAT_PENDIDIK WHEN 1 THEN 'Ya' ELSE 'Tidak' END KP_STATUS_SERTIFIKAT_PENDIDIK_NAMA
			, SPLIT_PART(PFSP.PATH, '/', 3) KP_SERTIFIKASI_PENDIDIK_PATH_NAMA
			, SMP.KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
			, SMP.KP_PAK_LAMA_STATUS
			, CASE WHEN SMP.KP_PAK_LAMA_STATUS = 1 THEN 'Ya' ELSE 'Tidak' END KP_PAK_LAMA_STATUS_NAMA
		FROM PEGAWAI A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMP.SURAT_MASUK_BKD_ID = SMB.SURAT_MASUK_BKD_ID
		LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
		LEFT JOIN
		(
			SELECT A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID
			, A.SATUAN_KERJA_ID, A.INFO_LOG, A.LAST_USER, A.LAST_DATE
			, CONCAT(A.INFO_LOG, ' ', ambil_satker_nama(A.SATUAN_KERJA_ID)) INFO_PROSES
			FROM persuratan.SURAT_MASUK_PEGAWAI_LOG A
			INNER JOIN
			(
			SELECT SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID, MAX(LAST_DATE) LAST_DATE
			FROM persuratan.SURAT_MASUK_PEGAWAI_LOG
			GROUP BY SURAT_MASUK_PEGAWAI_ID, JENIS_ID, PEGAWAI_ID
			) B ON A.SURAT_MASUK_PEGAWAI_ID = B.SURAT_MASUK_PEGAWAI_ID AND A.JENIS_ID = B.JENIS_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID AND A.LAST_DATE = B.LAST_DATE
		) L ON SMP.SURAT_MASUK_PEGAWAI_ID = L.SURAT_MASUK_PEGAWAI_ID AND SMP.JENIS_ID = L.JENIS_ID AND SMP.PEGAWAI_ID = L.PEGAWAI_ID
		LEFT JOIN persuratan.SURAT_KELUAR_BKD SKR ON SMP.USULAN_SURAT_ID = SKR.USULAN_SURAT_ID
		LEFT JOIN persuratan.USULAN_SURAT US ON SMP.USULAN_SURAT_ID = US.USULAN_SURAT_ID
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = SMP.PANGKAT_RIWAYAT_AKHIR_ID
		INNER JOIN
		(
			SELECT A.*, B.NAMA ESELON_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JR ON JR.JABATAN_RIWAYAT_ID = SMP.JABATAN_RIWAYAT_AKHIR_ID
		INNER JOIN 
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.TANGGAL_STTB
				, CASE A.STATUS_PENDIDIKAN
				WHEN '1' THEN 'Pendidikan CPNS'
				WHEN '2' THEN 'Diakui'
				WHEN '3' THEN 'Belum Diakui'
				WHEN '4' THEN 'Riwayat'
				WHEN '5' THEN 'Ijin belajar'
				WHEN '6' THEN 'Tugas Belajar'
				ELSE '-' END STATUS_PENDIDIKAN_NAMA
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
		) PENR ON PENR.PENDIDIKAN_RIWAYAT_ID = SMP.PENDIDIKAN_RIWAYAT_AKHIR_ID
		LEFT JOIN SURAT_TANDA_LULUS STL ON STL.SURAT_TANDA_LULUS_ID = SMP.KP_SURAT_TANDA_LULUS_ID
		LEFT JOIN 
		(
			SELECT 	
				A.PENDIDIKAN_RIWAYAT_ID, A.PEGAWAI_ID, B.NAMA PENDIDIKAN_JURUSAN_NAMA, A.TANGGAL_STTB
				, CASE A.STATUS_PENDIDIKAN
				WHEN '1' THEN 'Pendidikan CPNS'
				WHEN '2' THEN 'Diakui'
				WHEN '3' THEN 'Belum Diakui'
				WHEN '4' THEN 'Riwayat'
				WHEN '5' THEN 'Ijin belajar'
				WHEN '6' THEN 'Tugas Belajar'
				ELSE '-' END STATUS_PENDIDIKAN_NAMA
			FROM PENDIDIKAN_RIWAYAT A
			LEFT JOIN PENDIDIKAN_JURUSAN B ON A.PENDIDIKAN_JURUSAN_ID = B.PENDIDIKAN_JURUSAN_ID
			LEFT JOIN PENDIDIKAN C ON A.PENDIDIKAN_ID = C.PENDIDIKAN_ID
			WHERE 1 = 1
		) PENR1 ON PENR1.PENDIDIKAN_RIWAYAT_ID = SMP.KP_PENDIDIKAN_RIWAYAT_BELUM_DIAKUI_ID
		LEFT JOIN PEGAWAI_FILE PF ON PF.PEGAWAI_FILE_ID = SMP.KP_PEGAWAI_FILE_ID
		LEFT JOIN PANGKAT PANG ON PANG.PANGKAT_ID = SMP.KP_PANGKAT_ID
		LEFT JOIN DIKLAT_STRUKTURAL DS ON DS.DIKLAT_STRUKTURAL_ID = SMP.KP_DIKLAT_STRUKTURAL_ID
		LEFT JOIN PAK P_LAMA ON P_LAMA.PAK_ID = SMP.KP_PAK_LAMA_ID
		LEFT JOIN PAK P_BARU ON P_BARU.PAK_ID = SMP.KP_PAK_BARU_ID
		LEFT JOIN PEGAWAI_FILE PFSA ON PFSA.PEGAWAI_FILE_ID = SMP.KP_PEGAWAI_FILE_SERTIFIKAT_KEASLIAN_ID
		LEFT JOIN PEGAWAI_FILE PFSP ON PFSP.PEGAWAI_FILE_ID = SMP.KP_PEGAWAI_FILE_SERTIFIKAT_PENDIDIK_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

	function selectByParamsCetakRekomendasi($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT 
					A.NAMA, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, A.NIP_BARU
					, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA
					, PEN_US_RIW.PENDIDIKAN_NAMA PENDIDIKAN_NAMA_US, PEN_US_RIW.NAMA_FAKULTAS NAMA_FAKULTAS_US, PEN_US_RIW.JURUSAN JURUSAN_US, PEN_US_RIW.NAMA_SEKOLAH NAMA_SEKOLAH_US
					, SMU.SATUAN_KERJA_ASAL_ID, SMU.SATUAN_KERJA_TUJUAN_ID
					, CASE WHEN SMP.SURAT_MASUK_UPT_ID IS NOT NULL THEN SMU.SATUAN_KERJA_TUJUAN_ID ELSE SMB.SATUAN_KERJA_ASAL_ID END SATUAN_KERJA_TTD_ID
				FROM PEGAWAI A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				LEFT JOIN persuratan.SURAT_MASUK_UPT SMU ON SMU.SURAT_MASUK_UPT_ID = SMP.SURAT_MASUK_UPT_ID
				LEFT JOIN persuratan.SURAT_MASUK_BKD SMB ON SMB.SURAT_MASUK_BKD_ID = SMP.SURAT_MASUK_BKD_ID
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
	
	function selectByParamsMonitoringBkd($paramsArray=array(),$limit=-1,$from=-1, $statementsatuankerja='', $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		// , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
		$str = "
		SELECT 
			CONCAT(AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID), ' - ', SMU.NOMOR ) GROUP_INFO, A.*
			, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES
			, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		FROM
		(
				SELECT
					A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, A.AGAMA_ID
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT, SK.NAMA SATUAN_KERJA_NAMA
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				WHERE 1 = 1 ".$statementsatuankerja."
		) A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
		INNER JOIN persuratan.SURAT_MASUK_BKD SMU ON SMP.SURAT_MASUK_BKD_ID = SMU.SURAT_MASUK_BKD_ID
		WHERE 1=1
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
	
	function selectByParamsMonitoringUpt($paramsArray=array(),$limit=-1,$from=-1, $statementsatuankerja='', $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT 
			CONCAT(AMBIL_SATKER_NAMA(SMU.SATUAN_KERJA_ASAL_ID), ' - ', SMU.NOMOR ) GROUP_INFO, A.*
			, SMP.SURAT_MASUK_PEGAWAI_ID, SMP.JENIS_ID, SMP.SURAT_MASUK_BKD_ID, SMP.SURAT_MASUK_UPT_ID, SMP.STATUS_PILIH, SMP.STATUS_KEMBALI, SMP.STATUS_PROGRES
		FROM
		(
				SELECT
					A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				WHERE 1 = 1 ".$statementsatuankerja."
		) A
		INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
		INNER JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
		WHERE 1=1
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
	
	function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, 
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
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
	
	function selectByParamsMonitoringUsulanCariKepala($paramsArray=array(),$limit=-1,$from=-1, $statementsatuankerja="", $statementPlt='', $statementJson='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.*
		FROM
		(
			SELECT A.*
			FROM
			(
				SELECT 
					ROW_NUMBER () OVER (ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC) NOMOR,
					CAST('' AS TEXT) TIPE_ID,
					A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, PF.PATH
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA, A.PANGKAT_ID
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
				LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
				WHERE 1 = 1
				AND A.STATUS_PEGAWAI_ID IN (1,2)
				".$statementsatuankerja."
				ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC
			) A
			WHERE 1=1 AND NOMOR <= 2
			UNION ALL
			SELECT 
				ROW_NUMBER () OVER (ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC) NOMOR,
				CAST('3' AS TEXT) TIPE_ID,
				A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
				, PLT.NAMA_JABATAN JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
				, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
				, PF.PATH
			FROM PEGAWAI A
			LEFT JOIN
			(
				SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA, A.PANGKAT_ID
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
			LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
			INNER JOIN (
				SELECT A.PEGAWAI_ID, CONCAT('Plt. ', A.NAMA) NAMA_JABATAN
				FROM
				(
				SELECT A.SATKER_ID SATUAN_KERJA_ID, A.* FROM JABATAN_TAMBAHAN A WHERE 1=1 AND A.STATUS_PLT = 'plt'
				".$statementPlt."
				) A
				WHERE 1=1
				".$statementsatuankerja."
			) PLT ON A.PEGAWAI_ID = PLT.PEGAWAI_ID
			WHERE 1 = 1
		) A
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statementJson." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	

	function getCountByParamsMonitoringUsulanCariKepala($paramsArray=array(), $statementsatuankerja="", $statementPlt='', $statementJson='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM
		(
			SELECT A.*
			FROM
			(
				SELECT 
					ROW_NUMBER () OVER (ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC) NOMOR,
					A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, PF.PATH
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
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM PEGAWAI_FILE_DATA WHERE RIWAYAT_TABLE = 'PEGAWAI' AND RIWAYAT_ID = 1) PF ON PF.PEGAWAI_ID = A.PEGAWAI_ID
				LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
				WHERE 1 = 1
				AND A.STATUS_PEGAWAI_ID IN (1,2)
				".$statementsatuankerja."
			) A
			WHERE 1=1 AND NOMOR <= 2
			UNION ALL
			SELECT 
				ROW_NUMBER () OVER (ORDER BY JAB_RIW.ESELON_ID ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT ASC) NOMOR,
				A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
				, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
				, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
				, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
				, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
				, PF.PATH
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
			LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
			LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM PEGAWAI_FILE_DATA WHERE RIWAYAT_TABLE = 'PEGAWAI' AND RIWAYAT_ID = 1) PF ON PF.PEGAWAI_ID = A.PEGAWAI_ID
			LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
			INNER JOIN (
				SELECT A.PEGAWAI_ID, CONCAT('Plt. ', A.NAMA) NAMA_JABATAN
				FROM
				(
				SELECT A.SATKER_ID SATUAN_KERJA_ID, A.* FROM JABATAN_TAMBAHAN A WHERE 1=1 AND A.STATUS_PLT = 'plt'
				".$statementPlt."
				) A
				WHERE 1=1
				".$statementsatuankerja."
			) PLT ON A.PEGAWAI_ID = PLT.PEGAWAI_ID
			WHERE 1 = 1
		) A
		WHERE 1=1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
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

    function selectByParamsCariPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.PEGAWAI_ID, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP

			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.NAMA PANGKAT_RIWAYAT_NAMA, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
			, A.SATUAN_KERJA_ID
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, B.NAMA, A.PANGKAT_ID
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

	function selectByParamsMonitoringCariKepala($paramsArray=array(),$limit=-1,$from=-1, $statementJabatan='', $statementTambahan="", $statement="", $order=' ORDER BY CASE WHEN CASE WHEN JAB_RIW.ESELON_ID IS NULL THEN 99 ELSE COALESCE(JAB_RIW.ESELON_ID,99) END = 99 THEN 99 WHEN JAB_RIW.ESELON_ID = 0 THEN 99 ELSE JAB_RIW.ESELON_ID END ASC, PANG_RIW.PANGKAT_ID DESC, PANG_RIW.TMT_PANGKAT, JAB_RIW.TMT_JABATAN')
	{
		// STATUS_PLT = 'plt'
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, JAB_RIW.JABATAN_NAMA, JAB_RIW.ESELON_NAMA
			, PANGKAT_RIWAYAT_KODE, PANGKAT_RIWAYAT_NAMA
			, A.SATUAN_KERJA_ID, JAB_RIW.TIPE_ID
		FROM 
		(
			SELECT A.PEGAWAI_ID, A.JABATAN_RIWAYAT_ID, A.ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA, 1 TIPE_ID
			FROM JABATAN_RIWAYAT A
			INNER JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			INNER JOIN
			(
				SELECT A.PEGAWAI_ID, MAX(A.TMT_JABATAN) TMT_JABATAN
				FROM JABATAN_RIWAYAT A
				WHERE (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
				GROUP BY A.PEGAWAI_ID
			) A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID AND A.TMT_JABATAN = A1.TMT_JABATAN
			WHERE 1=1
			".$statementJabatan."
			UNION ALL
			SELECT A.PEGAWAI_ID, NULL JABATAN_RIWAYAT_ID, NULL ESELON, NULL ESELON_NAMA, A.TMT_JABATAN
			, CASE WHEN STATUS_PLT = 'plt' THEN CONCAT('Plt. ', A.NAMA) WHEN STATUS_PLT = 'plh' THEN CONCAT('Plh. ', A.NAMA) ELSE A.NAMA END JABATAN_NAMA
			, 1 TIPE_ID
			FROM JABATAN_TAMBAHAN A WHERE 1=1
			AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			".$statementTambahan."
		) JAB_RIW
		INNER JOIN (SELECT * FROM PEGAWAI WHERE STATUS_PEGAWAI_ID IN (1,2) ) A ON A.PEGAWAI_ID = JAB_RIW.PEGAWAI_ID
		INNER JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, A.PANGKAT_ID, B.KODE PANGKAT_RIWAYAT_KODE, B.NAMA PANGKAT_RIWAYAT_NAMA, A.TMT_PANGKAT
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		WHERE 1 = 1
		"; 
		
		// CASE WHEN AMBIL_SATKER_ID_INDUK(A.SATKER_ID) = A.SATKER_ID THEN 1 ELSE 2 END TIPE_ID
		// CASE WHEN STATUS_PLT = 'plt' THEN 3 ELSE 1 END TIPE_ID
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function getCountByParamsMonitoringCariKepala($paramsArray=array(), $statementJabatan='', $statementTambahan="")
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM 
				(
					SELECT A.PEGAWAI_ID, A.JABATAN_RIWAYAT_ID, A.ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					, CASE WHEN AMBIL_SATKER_ID_INDUK(A.SATKER_ID) = A.SATKER_ID THEN 1 ELSE 2 END TIPE_ID
					FROM JABATAN_RIWAYAT A
					INNER JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
					INNER JOIN
					(
						SELECT A.PEGAWAI_ID, MAX(A.TMT_JABATAN) TMT_JABATAN
						FROM JABATAN_RIWAYAT A
						GROUP BY A.PEGAWAI_ID
					) A1 ON A.PEGAWAI_ID = A1.PEGAWAI_ID AND A.TMT_JABATAN = A1.TMT_JABATAN
					WHERE 1=1
					".$statementJabatan."
					UNION ALL
					SELECT A.PEGAWAI_ID, NULL JABATAN_RIWAYAT_ID, NULL ESELON, NULL ESELON_NAMA, A.TMT_JABATAN, CONCAT('Plt. ', A.NAMA) JABATAN_NAMA
					, 3 TIPE_ID
					FROM JABATAN_TAMBAHAN A WHERE 1=1
					".$statementTambahan."
				) JAB_RIW
				INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = JAB_RIW.PEGAWAI_ID
				INNER JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, A.PANGKAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
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
	
	function selectByParamsMonitoringCariKepalaBak($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.PEGAWAI_ID, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, JAB_RIW.JABATAN_NAMA, JAB_RIW.ESELON_NAMA
			, A.SATUAN_KERJA_ID
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, A.ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
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
	
	function selectByParamsPegawaiCariPensiun($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY P.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			P.PEGAWAI_ID, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' 'END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE ' '|| A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PR.PANGKAT_KODE, PR.TMT_PANGKAT PANGKAT_TMT, PR.MASA_KERJA_TAHUN PANGKAT_TH, PR.MASA_KERJA_BULAN PANGKAT_BL
			, P.TMT PENSIUN_TMT
			, SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 1) PENSIUN_TH
			, SPLIT_PART(AMBIL_UMUR_DUK(SKC.TMT_CPNS, P.TMT), ' - ', 2) PENSIUN_BL
			, P.TANGGAL_KEMATIAN PENSIUN_TANGGAL_KEMATIAN, P.NOMOR_SK PENSIUN_NOMOR_SK, P.TANGGAL_SK_KEMATIAN PENSIUN_TANGGAL_SK_KEMATIAN, P.KETERANGAN PENSIUN_KETERANGAN
			, JR.ESELON_NAMA JABATAN_ESELON, JR.JABATAN_NAMA, JR.TMT_JABATAN JABATAN_TMT
			, AMBIL_SATKER_NAMA_DETIL(P.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
			, P.GAJI_RIWAYAT_ID, P.PANGKAT_RIWAYAT_ID, P.SATUAN_KERJA_ID
		FROM PENSIUN P
		INNER JOIN PEGAWAI A ON A.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN SK_CPNS SKC ON SKC.PEGAWAI_ID = P.PEGAWAI_ID
		INNER JOIN PANGKAT_RIWAYAT_DATA PR ON PR.PANGKAT_RIWAYAT_ID = P.PANGKAT_RIWAYAT_ID
		INNER JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
		) JR ON JR.JABATAN_RIWAYAT_ID = P.JABATAN_RIWAYAT_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    
    function selectByParamsPengaturanKenaikanPangkat($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENGATURAN_KENAIKAN_PANGKAT_ID ASC')
	{
		$str = "
				SELECT A.PENGATURAN_KENAIKAN_PANGKAT_ID, A.TANGGAL_PERIODE, A.TANGGAL_BATAS_AWAL_USUL
				, A.TANGGAL_BATAS_AKHIR_USUL_STRUKTURAL, A.TANGGAL_BATAS_AKHIR_USUL_NON_TIGA_BAWAH
				, A.TANGGAL_BATAS_AKHIR_USUL_NON_EMPAT_ATAS
				, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_TANGGAL_BATAS_AWAL_USUL
				FROM PENGATURAN_KENAIKAN_PANGKAT A
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

	function getCountByParamsMonitoringCariKepalaBak($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN
				(
					SELECT A.JABATAN_RIWAYAT_ID, A.ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
					FROM JABATAN_RIWAYAT A
					LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
				) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
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
	
	function getCountByParamsMonitoringPegawai($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
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
	
	function getCountByParamsMonitoringBkd($paramsArray=array(), $statementsatuankerja='', $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM
				(
					SELECT A.PEGAWAI_ID
					FROM PEGAWAI A
					LEFT JOIN
					(
						SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
						FROM PANGKAT_RIWAYAT A
						LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
					) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
					LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
					WHERE 1 = 1 ".$statementsatuankerja."
				) A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN persuratan.SURAT_MASUK_BKD SMU ON SMP.SURAT_MASUK_BKD_ID = SMU.SURAT_MASUK_BKD_ID
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		//echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function getCountByParamsMonitoringUpt($paramsArray=array(), $statementsatuankerja='', $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM
				(
					SELECT A.PEGAWAI_ID
					FROM PEGAWAI A
					LEFT JOIN
					(
						SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
						FROM PANGKAT_RIWAYAT A
						LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
					) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
					LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
					WHERE 1 = 1 ".$statementsatuankerja."
				) A
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
				INNER JOIN persuratan.SURAT_MASUK_UPT SMU ON SMP.SURAT_MASUK_UPT_ID = SMU.SURAT_MASUK_UPT_ID
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		//echo $str;exit;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
					FROM PANGKAT_RIWAYAT A
					LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
				) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
				LEFT JOIN SATUAN_KERJA SK ON SK.SATUAN_KERJA_ID = A.SATUAN_KERJA_ID
				INNER JOIN persuratan.SURAT_MASUK_PEGAWAI SMP ON A.PEGAWAI_ID = SMP.PEGAWAI_ID
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
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_MASUK_PEGAWAI A
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
	
	function getSatuanKerja($id='')
	{
		$str = "SELECT REPLACE(REPLACE(CAST(AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$id.") AS TEXT), '{',''), '}','') ROWCOUNT";
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow())
			if($this->getField("ROWCOUNT") == "")
			return $id;
			else
			return $id.",".$this->getField("ROWCOUNT");
		else 
			return $id;  
    }

    function getSatuanKerjaId($statement="")
	{
		$str = "SELECT SATUAN_KERJA_ID ROWCOUNT FROM SATUAN_KERJA WHERE 1=1 ".$statement;
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow())
			return $this->getField("ROWCOUNT");
		else 
			return "";  
    }
	
  } 
?>