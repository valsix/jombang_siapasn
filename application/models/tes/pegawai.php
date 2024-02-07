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
				JENIS_KELAMIN, SUKU_BANGSA, GOLONGAN_DARAH, ALAMAT, RT, RW, KODEPOS, TELEPON, HP,
				KARTU_PEGAWAI, ASKES, TASPEN, NPWP, NIK, NO_REKENING, SK_KONVERSI_NIP,
				BANK_ID, AGAMA_ID, PROPINSI_ID, KABUPATEN_ID, KECAMATAN_ID, DESA_ID, LAST_USER, LAST_DATE, LAST_LEVEL
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
				 '".$this->getField("SUKU_BANGSA")."',
				 '".$this->getField("GOLONGAN_DARAH")."',
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
				 ".$this->getField("AGAMA_ID").",
				 ".$this->getField("PROPINSI_ID").",
				 ".$this->getField("KABUPATEN_ID").",
				 ".$this->getField("KECAMATAN_ID").",
				 ".$this->getField("DESA_ID").",
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL")."
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
				 	NAMA= '".$this->getField("NAMA")."',
				 	TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
				 	TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
				 	JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
				 	SUKU_BANGSA= '".$this->getField("SUKU_BANGSA")."',
				 	GOLONGAN_DARAH= '".$this->getField("GOLONGAN_DARAH")."',
				 	ALAMAT= '".$this->getField("ALAMAT")."',
				 	RT= '".$this->getField("RT")."',
				 	RW= '".$this->getField("RW")."',
				 	KODEPOS= '".$this->getField("KODEPOS")."',
				 	TELEPON= '".$this->getField("TELEPON")."',
				 	HP= '".$this->getField("HP")."',
					KARTU_PEGAWAI= '".$this->getField("KARTU_PEGAWAI")."',
				 	ASKES= '".$this->getField("ASKES")."',
				 	TASPEN= '".$this->getField("TASPEN")."',
				 	NPWP= '".$this->getField("NPWP")."',
				 	NIK= '".$this->getField("NIK")."',
				 	NO_REKENING= '".$this->getField("NO_REKENING")."',
				 	SK_KONVERSI_NIP= '".$this->getField("SK_KONVERSI_NIP")."',
				 	BANK_ID= ".$this->getField("BANK_ID").",
				 	AGAMA_ID= ".$this->getField("AGAMA_ID").",
				 	PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
				 	KABUPATEN_ID= ".$this->getField("KABUPATEN_ID").",
				 	KECAMATAN_ID= ".$this->getField("KECAMATAN_ID").",
				 	DESA_ID= ".$this->getField("DESA_ID").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
	{
		$str = "
				SELECT
				A.PEGAWAI_ID, A.PEGAWAI_ID_SAPK, A.STATUS, A.SATUAN_KERJA_ID, A.JABATAN_RIWAYAT_ID, A.PENDIDIKAN_RIWAYAT_ID, 
				A.GAJI_RIWAYAT_ID, A.PANGKAT_RIWAYAT_ID, A.JENIS_PEGAWAI_ID, A.TIPE_PEGAWAI_ID, A.PEGAWAI_STATUS_ID, PS.PEGAWAI_STATUS_NAMA,
				A.STATUS_PEGAWAI_ID, A.NIP_LAMA, A.NIP_BARU, A.NAMA, A.GELAR_DEPAN, A.GELAR_BELAKANG, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, 
				A.JENIS_KELAMIN, A.STATUS_KAWIN, A.SUKU_BANGSA, A.GOLONGAN_DARAH, A.EMAIL, A.ALAMAT, A.RT, A.RW, A.KODEPOS, A.TELEPON, A.HP, 
				A.KARTU_PEGAWAI, A.ASKES, A.TASPEN, A.NPWP, A.NIK, A.NO_REKENING, A.SK_KONVERSI_NIP, A.BANK_ID, A.AGAMA_ID, A.PROPINSI_ID, 
				A.KABUPATEN_ID, A.KECAMATAN_ID, A.DESA_ID, A.LAST_USER, A.LAST_DATE, A.JENIS_PENGADAAN_ID
				, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
				FROM PEGAWAI A
				LEFT JOIN
				(
					SELECT A.PEGAWAI_STATUS_ID, A.PEGAWAI_ID, A.STATUS_PEGAWAI_ID, B.NAMA PEGAWAI_STATUS_NAMA
					FROM PEGAWAI_STATUS A
					INNER JOIN STATUS_PEGAWAI B ON A.STATUS_PEGAWAI_ID = B.STATUS_PEGAWAI_ID
				) PS ON A.PEGAWAI_STATUS_ID = PS.PEGAWAI_STATUS_ID
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
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEGAWAI_ID ASC')
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
					, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE ' ' || A.GELAR_BELAKANG END) NAMA_LENGKAP
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
		return $this->selectLimit($str,$limit,$from); 
		
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

  } 
?>