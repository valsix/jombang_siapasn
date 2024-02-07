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
  
  class Pegawai extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Pegawai()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); 

     	$str = "
			INSERT INTO PEGAWAI (
				PEGAWAI_ID, SATUAN_KERJA_ID, NIP_LAMA, NIP_BARU, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, 
				JENIS_KELAMIN, KEDUDUKAN, SUKU_BANGSA, GOLONGAN_DARAH, ALAMAT, RT, RW, KODEPOS, TELEPON, HP, 
				TELEPON_KANTOR, FACEBOOK, TWITTER, WHATSAPP, TELEGRAM, KARTU_PEGAWAI, ASKES, TASPEN, NPWP, NIK, NO_REKENING, 
				SK_KONVERSI_NIP, NO_URUT, NO_KK, NO_RAK_BERKAS, 
				BANK_ID, AGAMA_ID, PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, DESA_ID, KETERANGAN_1, KETERANGAN_2
				,LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				) 

			VALUES (
				 ".$this->getField("PEGAWAI_ID").",
				 ".$this->getField("SATUAN_KERJA_ID").",
				 '".$this->getField("NIP_LAMA")."',
				 '".$this->getField("NIP_BARU")."',
				 '".$this->getField("NAMA")."',
				 '".$this->getField("TEMPAT_LAHIR")."',
				 ".$this->getField("TANGGAL_LAHIR").",
				 '".$this->getField("JENIS_KELAMIN")."',
				 '".$this->getField("KEDUDUKAN")."',
				 '".$this->getField("SUKU_BANGSA")."',
				 '".$this->getField("GOLONGAN_DARAH")."',
				 '".$this->getField("ALAMAT")."',
				 '".$this->getField("RT")."',
				 '".$this->getField("RW")."',
				 '".$this->getField("KODEPOS")."',
				 '".$this->getField("TELEPON")."',
				 '".$this->getField("HP")."',
				 '".$this->getField("TELEPON_KANTOR")."',
				 '".$this->getField("FACEBOOK")."',
				 '".$this->getField("TWITTER")."',
				 '".$this->getField("WHATSAPP")."',
				 '".$this->getField("TELEGRAM")."',
				 '".$this->getField("KARTU_PEGAWAI")."',
			     '".$this->getField("ASKES")."',
			     '".$this->getField("TASPEN")."',
			     '".$this->getField("NPWP")."',
			     '".$this->getField("NIK")."',
			     '".$this->getField("NO_REKENING")."',
			     '".$this->getField("SK_KONVERSI_NIP")."',
			     '".$this->getField("NO_URUT")."',
			     '".$this->getField("NO_KK")."',
			     '".$this->getField("NO_RAK_BERKAS")."',
				 ".$this->getField("BANK_ID").",
				 ".$this->getField("AGAMA_ID").",
				 ".$this->getField("PROPINSI_ID").",
				 ".$this->getField("KABUPATEN_ID").",
				 ".$this->getField("KECAMATAN_ID").",
				 ".$this->getField("DESA_ID").",
				 '".$this->getField("KETERANGAN_1")."',
				 '".$this->getField("KETERANGAN_2")."',
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL").",
				 ".$this->getField("USER_LOGIN_ID").",
				 ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	
	function insertBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEGAWAI_ID", $this->getNextId("PEGAWAI_ID","PEGAWAI")); 

     	$str = "
			INSERT INTO PEGAWAI (
				PEGAWAI_ID, STATUS, SATUAN_KERJA_ID, JABATAN_RIWAYAT_ID, PEGAWAI_STATUS_ID, PENDIDIKAN_RIWAYAT_ID, 
				GAJI_RIWAYAT_ID, PANGKAT_RIWAYAT_ID, JENIS_PEGAWAI_ID, TIPE_PEGAWAI_ID, STATUS_PEGAWAI_ID, NIP_LAMA, NIP_BARU, 
				NAMA, GELAR_DEPAN, GELAR_BELAKANG, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, STATUS_KAWIN, SUKU_BANGSA,
				GOLONGAN_DARAH, EMAIL, ALAMAT, RT, RW, KODEPOS, TELEPON, HP, KARTU_PEGAWAI, ASKES, TASPEN, NPWP, NIK, NO_REKENING,
				SK_KONVERSI_NIP, BANK_ID, AGAMA_ID
			) 
			VALUES (
				  ".$this->getField("PEGAWAI_ID").",
			     '".$this->getField("STATUS")."',
			      ".$this->getField("SATUAN_KERJA_ID")."',
			      ".$this->getField("JABATAN_RIWAYAT_ID").",
			      ".$this->getField("PEGAWAI_STATUS_ID").",
			      ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
			      ".$this->getField("GAJI_RIWAYAT_ID").",
			      ".$this->getField("PANGKAT_RIWAYAT_ID").",
			      ".$this->getField("JENIS_PEGAWAI_ID").",
			     '".$this->getField("TIPE_PEGAWAI_ID")."',
			      ".$this->getField("STATUS_PEGAWAI_ID").",
			     '".$this->getField("NIP_LAMA")."',
			     '".$this->getField("NIP_BARU")."',
			     '".$this->getField("NAMA")."',
			     '".$this->getField("GELAR_DEPAN")."',
			     '".$this->getField("GELAR_BELAKANG")."',
			     '".$this->getField("TEMPAT_LAHIR")."',
			      ".$this->getField("TANGGAL_LAHIR").",
			     '".$this->getField("JENIS_KELAMIN")."',
			      ".$this->getField("STATUS_KAWIN").",
			     '".$this->getField("SUKU_BANGSA")."',
			     '".$this->getField("GOLONGAN_DARAH")."',
			     '".$this->getField("EMAIL")."',
			     '".$this->getField("ALAMAT")."',
			     '".$this->getField("RT")."',
			     '".$this->getField("RW")."',
			     '".$this->getField("KODEPOS")."',
			     '".$this->getField("TELEPON")."',
			     '".$this->getField("HP")."',
			     '".$this->getField("KARTU_PEGAWAI")."',
			     '".$this->getField("ASKES")."',
			     '".$this->getField("TASPEN")."',
			     '".$this->getField("NPWP")."',
			     '".$this->getField("NIK")."',
			     '".$this->getField("NO_REKENING")."',
			     '".$this->getField("SK_KONVERSI_NIP")."',
			      ".$this->getField("BANK_ID").",
			      ".$this->getField("AGAMA_ID")."
			)
		"; 	
		$this->id = $this->getField("PEGAWAI_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEGAWAI
				SET    
				 	NIP_LAMA= '".$this->getField("NIP_LAMA")."',
				 	NIP_BARU= '".$this->getField("NIP_BARU")."',
				 	SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
				 	NAMA= '".$this->getField("NAMA")."',
				 	TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
				 	TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
				 	JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
				 	KEDUDUKAN= '".$this->getField("KEDUDUKAN")."',
				 	SUKU_BANGSA= '".$this->getField("SUKU_BANGSA")."',
				 	GOLONGAN_DARAH= '".$this->getField("GOLONGAN_DARAH")."',
				 	ALAMAT= '".$this->getField("ALAMAT")."',
				 	RT= '".$this->getField("RT")."',
				 	RW= '".$this->getField("RW")."',
				 	KODEPOS= '".$this->getField("KODEPOS")."',
				 	TELEPON= '".$this->getField("TELEPON")."',
				 	HP= '".$this->getField("HP")."',
				 	TELEPON_KANTOR= '".$this->getField("TELEPON_KANTOR")."',
				 	FACEBOOK= '".$this->getField("FACEBOOK")."',
				 	TWITTER= '".$this->getField("TWITTER")."',
				 	WHATSAPP= '".$this->getField("WHATSAPP")."',
				 	TELEGRAM= '".$this->getField("TELEGRAM")."',
					KARTU_PEGAWAI= '".$this->getField("KARTU_PEGAWAI")."',
				 	ASKES= '".$this->getField("ASKES")."',
				 	TASPEN= '".$this->getField("TASPEN")."',
				 	NPWP= '".$this->getField("NPWP")."',
				 	NIK= '".$this->getField("NIK")."',
				 	NO_REKENING= '".$this->getField("NO_REKENING")."',
				 	SK_KONVERSI_NIP= '".$this->getField("SK_KONVERSI_NIP")."',
				 	NO_URUT= '".$this->getField("NO_URUT")."',
				 	NO_KK= '".$this->getField("NO_KK")."',
				 	NO_RAK_BERKAS= '".$this->getField("NO_RAK_BERKAS")."',
				 	BANK_ID= ".$this->getField("BANK_ID").",
				 	AGAMA_ID= ".$this->getField("AGAMA_ID").",
				 	PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
				 	KABUPATEN_ID= ".$this->getField("KABUPATEN_ID").",
				 	KECAMATAN_ID= ".$this->getField("KECAMATAN_ID").",
				 	DESA_ID= ".$this->getField("DESA_ID").",
				 	KETERANGAN_1= '".$this->getField("KETERANGAN_1")."',
				 	KETERANGAN_2= '".$this->getField("KETERANGAN_2")."',
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				    LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function updateBak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEGAWAI
				SET    
				 	STATUS					='".$this->getField("STATUS")."',
			     	SATUAN_KERJA_ID			= ".$this->getField("SATUAN_KERJA_ID")."',
			     	JABATAN_RIWAYAT_ID		= ".$this->getField("JABATAN_RIWAYAT_ID").",
			     	PEGAWAI_STATUS_ID		= ".$this->getField("PEGAWAI_STATUS_ID").",
			     	PENDIDIKAN_RIWAYAT_ID	= ".$this->getField("PENDIDIKAN_RIWAYAT_ID").",
			     	GAJI_RIWAYAT_ID			= ".$this->getField("GAJI_RIWAYAT_ID").",
			     	PANGKAT_RIWAYAT_ID		= ".$this->getField("PANGKAT_RIWAYAT_ID").",
			     	JENIS_PEGAWAI_ID		= ".$this->getField("JENIS_PEGAWAI_ID").",
			     	TIPE_PEGAWAI_ID			='".$this->getField("TIPE_PEGAWAI_ID")."',
			     	STATUS_PEGAWAI_ID			= ".$this->getField("STATUS_PEGAWAI_ID").",
			     	NIP_LAMA				='".$this->getField("NIP_LAMA")."',
			     	NIP_BARU				='".$this->getField("NIP_BARU")."',
			     	NAMA					='".$this->getField("NAMA")."',
			     	GELAR_DEPAN				='".$this->getField("GELAR_DEPAN")."',
			     	GELAR_BELAKANG			='".$this->getField("GELAR_BELAKANG")."',
			     	TEMPAT_LAHIR			='".$this->getField("TEMPAT_LAHIR")."',
			     	TANGGAL_LAHIR			= ".$this->getField("TANGGAL_LAHIR").",
			     	JENIS_KELAMIN			='".$this->getField("JENIS_KELAMIN")."',
			     	STATUS_KAWIN			= ".$this->getField("STATUS_KAWIN").",
			     	SUKU_BANGSA				='".$this->getField("SUKU_BANGSA")."',
			     	GOLONGAN_DARAH			='".$this->getField("GOLONGAN_DARAH")."',
			     	EMAIL					='".$this->getField("EMAIL")."',
			     	ALAMAT					='".$this->getField("ALAMAT")."',
			     	RT						='".$this->getField("RT")."',
			     	RW						='".$this->getField("RW")."',
			     	KODEPOS					='".$this->getField("KODEPOS")."',
			     	TELEPON					='".$this->getField("TELEPON")."',
			     	HP						='".$this->getField("HP")."',
			     	KARTU_PEGAWAI			='".$this->getField("KARTU_PEGAWAI")."',
			     	ASKES					='".$this->getField("ASKES")."',
			     	TASPEN					='".$this->getField("TASPEN")."',
			     	NPWP					='".$this->getField("NPWP")."',
			     	NIK						='".$this->getField("NIK")."',
			     	NO_REKENING				='".$this->getField("NO_REKENING")."',
			     	SK_KONVERSI_NIP			='".$this->getField("SK_KONVERSI_NIP")."',
			     	BANK_ID					= ".$this->getField("BANK_ID").",
			     	AGAMA_ID				= ".$this->getField("AGAMA_ID")."
				WHERE  PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PEGAWAI SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					USER_LOGIN_PEGAWAI_ID = ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE PEGAWAI_ID = ".$this->getField("PEGAWAI_ID")."
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
    function selectByParamsSimple($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			FROM PEGAWAI A
			WHERE 1 = 1
		"; 

		// , A.PROPINSI_ID, PROP.NAMA PROPINSI_NAMA, A.KABUPATEN_ID, KAB.NAMA KABUPATEN_NAMA, A.KECAMATAN_ID, KEC.NAMA KECAMATAN_NAMA, A.DESA_ID, KEL.NAMA DESA_NAMA
		// LEFT JOIN PROPINSI PROP ON PROP.PROPINSI_ID = A.PROPINSI_ID
		// LEFT JOIN KABUPATEN KAB ON KAB.KABUPATEN_ID = A.KABUPATEN_ID
		// LEFT JOIN KECAMATAN KEC ON KEC.KECAMATAN_ID = A.KECAMATAN_ID
		// LEFT JOIN KELURAHAN KEL ON KEL.KECAMATAN_ID = A.KECAMATAN_ID AND KEL.KELURAHAN_ID = A.DESA_ID
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.PEGAWAI_ID, A.PEGAWAI_ID_SAPK, A.STATUS, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_ID, 
			A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.KEDUDUKAN, A.TIPE_PEGAWAI_ID, A.PEGAWAI_STATUS_ID, PS.PEGAWAI_STATUS_NAMA,
			PS.PEGAWAI_KEDUDUKAN_TMT, PS.PEGAWAI_KEDUDUKAN_NAMA,
			A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
			A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, A.TELEPON, A.HP, 
			A.TELEPON_KANTOR, A.FACEBOOK, A.TWITTER, A.WHATSAPP, A.TELEGRAM, A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, 
			A.SK_KONVERSI_NIP, A.NO_URUT, A.NO_KK, A.NO_RAK_BERKAS, 
			A.BANK_ID, A.AGAMA_ID
			, A.KETERANGAN_1, A.KETERANGAN_2, A.LAST_USER, A.LAST_DATE, A.JENIS_PENGADAAN_ID
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, A.PROPINSI_ID, P1.PROPINSI_NAMA, A.KABUPATEN_ID, P1.KABUPATEN_NAMA, A.KECAMATAN_ID, P1.KECAMATAN_NAMA, A.DESA_ID, P1.DESA_NAMA
			FROM PEGAWAI A
			LEFT JOIN
			(
				SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
				, A.TMT PEGAWAI_KEDUDUKAN_TMT, C.NAMA PEGAWAI_KEDUDUKAN_NAMA
				FROM PEGAWAI_STATUS A
				INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
				INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
			) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
			LEFT JOIN (
				SELECT A.PEGAWAI_ID
				, A.PROPINSI_ID, PROP.NAMA PROPINSI_NAMA, A.KABUPATEN_ID, KAB.NAMA KABUPATEN_NAMA, A.KECAMATAN_ID, KEC.NAMA KECAMATAN_NAMA, A.DESA_ID, KEL.NAMA DESA_NAMA
				FROM PEGAWAI A
				LEFT JOIN (SELECT PROPINSI_ID, NAMA FROM PROPINSI) PROP ON PROP.PROPINSI_ID = A.PROPINSI_ID
				LEFT JOIN (SELECT PROPINSI_ID, KABUPATEN_ID, NAMA FROM KABUPATEN) KAB ON KAB.PROPINSI_ID = A.PROPINSI_ID AND KAB.KABUPATEN_ID = A.KABUPATEN_ID
				LEFT JOIN (SELECT PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, NAMA FROM KECAMATAN) KEC ON KEC.PROPINSI_ID = A.PROPINSI_ID AND KEC.KABUPATEN_ID = A.KABUPATEN_ID AND KEC.KECAMATAN_ID = A.KECAMATAN_ID
				LEFT JOIN (SELECT PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, KELURAHAN_ID, NAMA FROM KELURAHAN) KEL ON KEL.PROPINSI_ID = A.PROPINSI_ID AND KEL.KABUPATEN_ID = A.KABUPATEN_ID AND KEL.KECAMATAN_ID = A.KECAMATAN_ID AND KEL.KELURAHAN_ID = A.DESA_ID
				WHERE 1 = 1
			) P1 ON P1.PEGAWAI_ID = A.PEGAWAI_ID
			WHERE 1 = 1
		"; 

		// , A.PROPINSI_ID, PROP.NAMA PROPINSI_NAMA, A.KABUPATEN_ID, KAB.NAMA KABUPATEN_NAMA, A.KECAMATAN_ID, KEC.NAMA KECAMATAN_NAMA, A.DESA_ID, KEL.NAMA DESA_NAMA
		// LEFT JOIN PROPINSI PROP ON PROP.PROPINSI_ID = A.PROPINSI_ID
		// LEFT JOIN KABUPATEN KAB ON KAB.KABUPATEN_ID = A.KABUPATEN_ID
		// LEFT JOIN KECAMATAN KEC ON KEC.KECAMATAN_ID = A.KECAMATAN_ID
		// LEFT JOIN KELURAHAN KEL ON KEL.KECAMATAN_ID = A.KECAMATAN_ID AND KEL.KELURAHAN_ID = A.DESA_ID
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsMonitoringPegawaiBak($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
			, PF.PATH
			, CASE
			WHEN CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI
			THEN 1
			WHEN G.PEGAWAI_ID IS NOT NULL THEN 2
			ELSE 0
			END STATUS_BERLAKU
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

    function selectByParamsMonitoringPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT A.*, PF.PATH
		, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
		, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
		FROM
		(
		SELECT 
			A.PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
			, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
			, JAB_RIW.ESELON_ID, PANG_RIW.PANGKAT_ID, PANG_RIW.TMT_PANGKAT, A.SATUAN_KERJA_ID
			, CASE
			WHEN CURRENT_DATE <= G.TANGGAL_AKHIR AND CURRENT_DATE >= G.TANGGAL_MULAI
			THEN 1
			WHEN G.PEGAWAI_ID IS NOT NULL THEN 2
			ELSE 0
			END STATUS_BERLAKU
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
		LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." 
		) A
		LEFT JOIN (SELECT PEGAWAI_ID, PATH FROM P_PEGAWAI_FILE_DATA('')) PF ON PF.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT A.PEGAWAI_ID, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, A.SATUAN_KERJA_ID,
					A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID, 
					A.PENDIDIKAN_RIWAYAT_ID, A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.KEDUDUKAN, A.TIPE_PEGAWAI_ID, 
					A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
					A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, 
					A.TELEPON, A.HP, A.TELEPON_KANTOR, A.FACEBOOK, A.TWITTER, A.WHATSAPP, A.TELEGRAM, 
					A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, 
					A.SK_KONVERSI_NIP, A.NO_URUT, A.NO_KK, A.NO_RAK_BERKAS, A.BANK_ID, A.KETERANGAN_1, A.KETERANGAN_2,
					A.AGAMA_ID, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA
					, PANG_RIW.KODE PANGKAT_RIWAYAT_KODE, PANG_RIW.TMT_PANGKAT PANGKAT_RIWAYAT_TMT
					, JAB_RIW.JABATAN_NAMA JABATAN_RIWAYAT_NAMA, JAB_RIW.ESELON_NAMA JABATAN_RIWAYAT_ESELON, JAB_RIW.TMT_JABATAN JABATAN_RIWAYAT_TMT
					, SK.NAMA SATUAN_KERJA_NAMA, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_INDUK
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
					, PF.PATH
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT
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
	
	function selectByParamsCetakPegawai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU,
			(CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA,
			A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, PS.PEGAWAI_STATUS_NAMA STATUS_PEGAWAI,
			PANG_RIW.KODE GOL_RUANG, PANG_RIW.TMT_PANGKAT,
			T.NAMA TIPE_PEGAWAI, JAB_RIW.ESELON_NAMA ESELON, JAB_RIW.JABATAN_NAMA JABATAN, JAB_RIW.TMT_JABATAN, D.NAMA AGAMA, A.TELEPON, A.ALAMAT,
			AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATKER_INDUK,
			AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) NAMA_SATKER,
			CASE WHEN A.TANGGAL_LAHIR IS NULL THEN NULL
			ELSE
				CASE WHEN TO_CHAR(A.TANGGAL_LAHIR, 'MM') = '12' THEN
				TO_DATE(CONCAT('01-01-', CAST(CAST(TO_CHAR(A.TANGGAL_LAHIR, 'YYYY') AS NUMERIC) + 1 + COALESCE(JAB_RIW.USIA_BUP, 0) AS TEXT)), 'DD-MM-YYYY')
				ELSE
				TO_DATE(CONCAT('01-', CAST(TO_CHAR(A.TANGGAL_LAHIR, 'MM') AS NUMERIC) + 1, '-', CAST(CAST(TO_CHAR(A.TANGGAL_LAHIR, 'YYYY') AS NUMERIC) + COALESCE(JAB_RIW.USIA_BUP, 0) AS TEXT)), 'DD-MM-YYYY')
				END
			END TMT_PENSIUN,
			(CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN '' ELSE F.PENDIDIKAN_NAMA END) || (CASE WHEN COALESCE(NULLIF(F.PENDIDIKAN_JURUSAN_NAMA,'') , NULL ) IS NULL THEN '' ELSE ' - ' || F.PENDIDIKAN_JURUSAN_NAMA END) PENDIDIKAN,
			CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN NULL ELSE TO_CHAR(F.TANGGAL_STTB, 'YYYY') END LULUS,
			'' KETERANGAN
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
			, A.TMT PEGAWAI_KEDUDUKAN_TMT, C.NAMA PEGAWAI_KEDUDUKAN_NAMA
			FROM PEGAWAI_STATUS A
			INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
			INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
		) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			, A.JABATAN_FU_ID, A.JABATAN_FT_ID
			, COALESCE(ED.USIA_BUP, JFU.USIA_BUP, JFT.USIA_BUP) USIA_BUP
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			LEFT JOIN ESELON_DETIL ED ON ED.ESELON_ID = A.ESELON_ID AND ED.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(ED.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FU_DETIL JFU ON JFU.JABATAN_FU_ID = A.JABATAN_FU_ID AND JFU.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFU.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FT_DETIL JFT ON JFT.JABATAN_FT_ID = A.JABATAN_FT_ID AND JFT.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFT.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN TIPE_PEGAWAI T ON CAST(A.TIPE_PEGAWAI_ID AS NUMERIC) = T.TIPE_PEGAWAI_ID
		LEFT JOIN AGAMA D ON A.AGAMA_ID = D.AGAMA_ID
		LEFT JOIN PENDIDIKAN_RIWAYAT_DATA F ON A.PENDIDIKAN_RIWAYAT_ID = F.PENDIDIKAN_RIWAYAT_ID
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

//copy dari selectByParamsCetakPegawai modifikasi buat tambah jenjang pendidikan di excel nya // 20200127_1

	function selectByParamsCetakPegawaiRendy20200127_1($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
		SELECT
			A.NIP_LAMA, AMBIL_FORMAT_NIP_BARU(A.NIP_BARU) NIP_BARU,
			(CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA,
			A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, PS.PEGAWAI_STATUS_NAMA STATUS_PEGAWAI,
			PANG_RIW.KODE GOL_RUANG, PANG_RIW.TMT_PANGKAT,
			T.NAMA TIPE_PEGAWAI, JAB_RIW.ESELON_NAMA ESELON, JAB_RIW.JABATAN_NAMA JABATAN, JAB_RIW.TMT_JABATAN, D.NAMA AGAMA, A.TELEPON, A.ALAMAT,
			AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATKER_INDUK,
			AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) NAMA_SATKER,
			CASE WHEN A.TANGGAL_LAHIR IS NULL THEN NULL
			ELSE
				CASE WHEN TO_CHAR(A.TANGGAL_LAHIR, 'MM') = '12' THEN
				TO_DATE(CONCAT('01-01-', CAST(CAST(TO_CHAR(A.TANGGAL_LAHIR, 'YYYY') AS NUMERIC) + 1 + COALESCE(JAB_RIW.USIA_BUP, 0) AS TEXT)), 'DD-MM-YYYY')
				ELSE
				TO_DATE(CONCAT('01-', CAST(TO_CHAR(A.TANGGAL_LAHIR, 'MM') AS NUMERIC) + 1, '-', CAST(CAST(TO_CHAR(A.TANGGAL_LAHIR, 'YYYY') AS NUMERIC) + COALESCE(JAB_RIW.USIA_BUP, 0) AS TEXT)), 'DD-MM-YYYY')
				END
			END TMT_PENSIUN,
			(CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN '' ELSE F.PENDIDIKAN_NAMA END) || (CASE WHEN COALESCE(NULLIF(F.PENDIDIKAN_JURUSAN_NAMA,'') , NULL ) IS NULL THEN '' ELSE ' - ' || F.PENDIDIKAN_JURUSAN_NAMA END) PENDIDIKAN,
			(CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN '' ELSE F.PENDIDIKAN_NAMA END) TK_PENDIDIKAN,
			(CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN '' ELSE F.PENDIDIKAN_JURUSAN_NAMA END) JUR_PENDIDIKAN,
			CASE WHEN A.PENDIDIKAN_RIWAYAT_ID IS NULL THEN NULL ELSE TO_CHAR(F.TANGGAL_STTB, 'YYYY') END LULUS,
			'' KETERANGAN
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
			, A.TMT PEGAWAI_KEDUDUKAN_TMT, C.NAMA PEGAWAI_KEDUDUKAN_NAMA
			FROM PEGAWAI_STATUS A
			INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
			INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
		) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			, A.JABATAN_FU_ID, A.JABATAN_FT_ID
			, COALESCE(ED.USIA_BUP, JFU.USIA_BUP, JFT.USIA_BUP) USIA_BUP
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			LEFT JOIN ESELON_DETIL ED ON ED.ESELON_ID = A.ESELON_ID AND ED.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(ED.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FU_DETIL JFU ON JFU.JABATAN_FU_ID = A.JABATAN_FU_ID AND JFU.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFU.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FT_DETIL JFT ON JFT.JABATAN_FT_ID = A.JABATAN_FT_ID AND JFT.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFT.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN TIPE_PEGAWAI T ON CAST(A.TIPE_PEGAWAI_ID AS NUMERIC) = T.TIPE_PEGAWAI_ID
		LEFT JOIN AGAMA D ON A.AGAMA_ID = D.AGAMA_ID
		LEFT JOIN PENDIDIKAN_RIWAYAT_DATA F ON A.PENDIDIKAN_RIWAYAT_ID = F.PENDIDIKAN_RIWAYAT_ID
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


    function getCountByParamsCetakPegawai($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
		FROM PEGAWAI A
		LEFT JOIN
		(
			SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
			, A.TMT PEGAWAI_KEDUDUKAN_TMT, C.NAMA PEGAWAI_KEDUDUKAN_NAMA
			FROM PEGAWAI_STATUS A
			INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
			INNER JOIN STATUS_PEGAWAI_KEDUDUKAN C ON A.STATUS_PEGAWAI_KEDUDUKAN_ID = C.STATUS_PEGAWAI_KEDUDUKAN_ID
		) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
		LEFT JOIN
		(
			SELECT A.PANGKAT_RIWAYAT_ID, B.KODE, A.TMT_PANGKAT, A.PANGKAT_ID
			FROM PANGKAT_RIWAYAT A
			LEFT JOIN PANGKAT B ON A.PANGKAT_ID = B.PANGKAT_ID
		) PANG_RIW ON A.PANGKAT_RIWAYAT_ID = PANG_RIW.PANGKAT_RIWAYAT_ID
		LEFT JOIN
		(
			SELECT A.JABATAN_RIWAYAT_ID, COALESCE(A.ESELON_ID,99) ESELON_ID, B.NAMA ESELON_NAMA, A.TMT_JABATAN, A.NAMA JABATAN_NAMA
			, A.JABATAN_FU_ID, A.JABATAN_FT_ID
			, COALESCE(ED.USIA_BUP, JFU.USIA_BUP, JFT.USIA_BUP) USIA_BUP
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			LEFT JOIN ESELON_DETIL ED ON ED.ESELON_ID = A.ESELON_ID AND ED.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(ED.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FU_DETIL JFU ON JFU.JABATAN_FU_ID = A.JABATAN_FU_ID AND JFU.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFU.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
			LEFT JOIN JABATAN_FT_DETIL JFT ON JFT.JABATAN_FT_ID = A.JABATAN_FT_ID AND JFT.TANGGAL_AWAL <= CURRENT_DATE AND COALESCE(JFT.TANGGAL_AKHIR, CURRENT_DATE) >= CURRENT_DATE
		) JAB_RIW ON A.JABATAN_RIWAYAT_ID = JAB_RIW.JABATAN_RIWAYAT_ID
		LEFT JOIN TIPE_PEGAWAI T ON CAST(A.TIPE_PEGAWAI_ID AS NUMERIC) = T.TIPE_PEGAWAI_ID
		LEFT JOIN AGAMA D ON A.AGAMA_ID = D.AGAMA_ID
		LEFT JOIN PENDIDIKAN_RIWAYAT_DATA F ON A.PENDIDIKAN_RIWAYAT_ID = F.PENDIDIKAN_RIWAYAT_ID
		WHERE 1=1 ".$statement; 
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
				SELECT COUNT(A.PEGAWAI_ID) AS ROWCOUNT 
				FROM PEGAWAI A
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
				LEFT JOIN (SELECT PEGAWAI_ID, TANGGAL_MULAI, TANGGAL_AKHIR FROM HUKUMAN_TERAKHIR X) G ON A.PEGAWAI_ID = G.PEGAWAI_ID
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

    function checkpegawaisatuankerja($id,$satuankerja)
	{
		$str = "
		SELECT AMBIL_SATKER_ID_BATAS(SATUAN_KERJA_ID, ".$satuankerja.") ROWCOUNT
		FROM PEGAWAI
		WHERE PEGAWAI_ID = ".$id;
		// echo $str;exit();
		$this->query = $str;
		$this->select($str);
		$this->firstRow();
		$tempData= $this->getField("ROWCOUNT"); 

		$arrData= explode(",", $tempData);
		return $arrData;
    }

  } 
?>