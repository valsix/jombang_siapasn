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
  
  class Anak extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Anak()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ANAK_ID", $this->getNextId("ANAK_ID","ANAK"));
		$str = "
		INSERT INTO ANAK 
		(
			ANAK_ID, PEGAWAI_ID, SUAMI_ISTRI_ID, PENDIDIKAN_ID, NAMA, NOMOR_INDUK, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN
			, STATUS_KELUARGA, STATUS_TUNJANGAN, PEKERJAAN, AWAL_BAYAR, AKHIR_BAYAR, STATUS_NIKAH, STATUS_AKTIF
			, TANGGAL_MENINGGAL, STATUS_BEKERJA, GELAR_DEPAN, GELAR_BELAKANG, AKTA_KELAHIRAN, JENIS_ID_DOKUMEN, AGAMA_ID
			, EMAIL, HP, TELEPON, ALAMAT, BPJS_NO, BPJS_TANGGAL, NPWP_NO, NPWP_TANGGAL, STATUS_PNS, NIP_PNS, STATUS_LULUS
			, KEMATIAN_NO, KEMATIAN_TANGGAL, JENIS_KAWIN_ID, AKTA_NIKAH_NO, AKTA_NIKAH_TANGGAL, NIKAH_TANGGAL
			, AKTA_CERAI_NO, AKTA_CERAI_TANGGAL, CERAI_TANGGAL
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES
		(
			".$this->getField("ANAK_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("SUAMI_ISTRI_ID")."
			, ".$this->getField("PENDIDIKAN_ID")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("NOMOR_INDUK")."'
			, '".$this->getField("TEMPAT_LAHIR")."'
			, ".$this->getField("TANGGAL_LAHIR")."
			, '".$this->getField("JENIS_KELAMIN")."'
			, ".$this->getField("STATUS_KELUARGA")."
			, ".$this->getField("STATUS_TUNJANGAN")."
			, '".$this->getField("PEKERJAAN")."'
			, ".$this->getField("AWAL_BAYAR")."
			, ".$this->getField("AKHIR_BAYAR")."
			, ".$this->getField("STATUS_NIKAH")."
			, ".$this->getField("STATUS_AKTIF")."
			, ".$this->getField("TANGGAL_MENINGGAL")."
			, ".$this->getField("STATUS_BEKERJA")."
			, '".$this->getField("GELAR_DEPAN")."'
			, '".$this->getField("GELAR_BELAKANG")."'
			, '".$this->getField("AKTA_KELAHIRAN")."'
			, ".$this->getField("JENIS_ID_DOKUMEN")."
			, ".$this->getField("AGAMA_ID")."
			, '".$this->getField("EMAIL")."'
			, '".$this->getField("HP")."'
			, '".$this->getField("TELEPON")."'
			, '".$this->getField("ALAMAT")."'
			, '".$this->getField("BPJS_NO")."'
			, ".$this->getField("BPJS_TANGGAL")."
			, '".$this->getField("NPWP_NO")."'
			, ".$this->getField("NPWP_TANGGAL")."
			, ".$this->getField("STATUS_PNS")."
			, '".$this->getField("NIP_PNS")."'
			, ".$this->getField("STATUS_LULUS")."
			, '".$this->getField("KEMATIAN_NO")."'
			, ".$this->getField("KEMATIAN_TANGGAL")."
			, ".$this->getField("JENIS_KAWIN_ID")."
			, '".$this->getField("AKTA_NIKAH_NO")."'
			, ".$this->getField("AKTA_NIKAH_TANGGAL")."
			, ".$this->getField("NIKAH_TANGGAL")."
			, '".$this->getField("AKTA_CERAI_NO")."'
			, ".$this->getField("AKTA_CERAI_TANGGAL")."
			, ".$this->getField("CERAI_TANGGAL")."
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("LAST_LEVEL")."
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->id = $this->getField("ANAK_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE ANAK 
		SET
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID")."
			, SUAMI_ISTRI_ID= ".$this->getField("SUAMI_ISTRI_ID")."
			, PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
			, NAMA= '".$this->getField("NAMA")."'
			, NOMOR_INDUK= '".$this->getField("NOMOR_INDUK")."'
			, TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."'
			, TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR")."
			, JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."'
			, STATUS_KELUARGA= ".$this->getField("STATUS_KELUARGA")."
			, STATUS_TUNJANGAN= ".$this->getField("STATUS_TUNJANGAN")."
			, PEKERJAAN= '".$this->getField("PEKERJAAN")."'
			, AWAL_BAYAR= ".$this->getField("AWAL_BAYAR")."
			, AKHIR_BAYAR= ".$this->getField("AKHIR_BAYAR")."
			, STATUS_NIKAH= ".$this->getField("STATUS_NIKAH")."
			, STATUS_AKTIF= ".$this->getField("STATUS_AKTIF")."
			, TANGGAL_MENINGGAL= ".$this->getField("TANGGAL_MENINGGAL")."
			, STATUS_BEKERJA= ".$this->getField("STATUS_BEKERJA")."
			, GELAR_DEPAN= '".$this->getField("GELAR_DEPAN")."'
			, GELAR_BELAKANG= '".$this->getField("GELAR_BELAKANG")."'
			, AKTA_KELAHIRAN= '".$this->getField("AKTA_KELAHIRAN")."'
			, JENIS_ID_DOKUMEN= ".$this->getField("JENIS_ID_DOKUMEN")."
			, AGAMA_ID= ".$this->getField("AGAMA_ID")."
			, EMAIL= '".$this->getField("EMAIL")."'
			, HP= '".$this->getField("HP")."'
			, TELEPON= '".$this->getField("TELEPON")."'
			, ALAMAT= '".$this->getField("ALAMAT")."'
			, BPJS_NO= '".$this->getField("BPJS_NO")."'
			, BPJS_TANGGAL= ".$this->getField("BPJS_TANGGAL")."
			, NPWP_NO= '".$this->getField("NPWP_NO")."'
			, NPWP_TANGGAL= ".$this->getField("NPWP_TANGGAL")."
			, STATUS_PNS= ".$this->getField("STATUS_PNS")."
			, NIP_PNS= '".$this->getField("NIP_PNS")."'
			, STATUS_LULUS= ".$this->getField("STATUS_LULUS")."
			, KEMATIAN_NO= '".$this->getField("KEMATIAN_NO")."'
			, KEMATIAN_TANGGAL= ".$this->getField("KEMATIAN_TANGGAL")."
			, JENIS_KAWIN_ID= ".$this->getField("JENIS_KAWIN_ID")."
			, AKTA_NIKAH_NO= '".$this->getField("AKTA_NIKAH_NO")."'
			, AKTA_NIKAH_TANGGAL= ".$this->getField("AKTA_NIKAH_TANGGAL")."
			, NIKAH_TANGGAL= ".$this->getField("NIKAH_TANGGAL")."
			, AKTA_CERAI_NO= '".$this->getField("AKTA_CERAI_NO")."'
			, AKTA_CERAI_TANGGAL= ".$this->getField("AKTA_CERAI_TANGGAL")."
			, CERAI_TANGGAL= ".$this->getField("CERAI_TANGGAL")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE  ANAK_ID= ".$this->getField("ANAK_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertDataBkn()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("ANAK_ID", $this->getNextId("ANAK_ID","ANAK"));
		$str = "
		INSERT INTO ANAK 
		(
			ANAK_ID, PEGAWAI_ID, NAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,JENIS_KELAMIN,STATUS_KELUARGA
		) 
		VALUES
		(
		".$this->getField("ANAK_ID")."
		, ".$this->getField("PEGAWAI_ID")."
		, '".$this->getField("NAMA")."'
		, '".$this->getField("TEMPAT_LAHIR")."'
		, ".$this->getField("TANGGAL_LAHIR")."
		, '".$this->getField("JENIS_KELAMIN")."'
		, ".$this->getField("STATUS_KELUARGA")."
			
		)
		"; 	
		$this->id = $this->getField("ANAK_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function updateBknData()
	{
		$str = "		
		UPDATE ANAK 
		SET
		PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		NAMA= '".$this->getField("NAMA")."',
		TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
		TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
		JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
		STATUS_KELUARGA= ".$this->getField("STATUS_KELUARGA")."

			
		WHERE  ANAK_ID= ".$this->getField("ANAK_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateIdSapk()
    {
		$str = "		
		UPDATE ANAK
		SET    
		
		 	 ID_SAPK= '".$this->getField("ID_SAPK")."'
	
		WHERE  ANAK_ID = ".$this->getField("ANAK_ID")."
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function updatepensiun()
	{
		// , KEMATIAN_NO= '".$this->getField("KEMATIAN_NO")."'
		// , KEMATIAN_TANGGAL= ".$this->getField("KEMATIAN_TANGGAL")."
		$str = "		
		UPDATE ANAK 
		SET
			SUAMI_ISTRI_ID= ".$this->getField("SUAMI_ISTRI_ID")."
			, PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
			, STATUS_LULUS= ".$this->getField("STATUS_LULUS")."
			, STATUS_BEKERJA= ".$this->getField("STATUS_BEKERJA")."
			, STATUS_AKTIF= ".$this->getField("STATUS_AKTIF")."
			, JENIS_KAWIN_ID= ".$this->getField("JENIS_KAWIN_ID")."
			, AKTA_NIKAH_NO= '".$this->getField("AKTA_NIKAH_NO")."'
			, AKTA_NIKAH_TANGGAL= ".$this->getField("AKTA_NIKAH_TANGGAL")."
			, NIKAH_TANGGAL= ".$this->getField("NIKAH_TANGGAL")."
			, AKTA_CERAI_NO= '".$this->getField("AKTA_CERAI_NO")."'
			, AKTA_CERAI_TANGGAL= ".$this->getField("AKTA_CERAI_TANGGAL")."
			, CERAI_TANGGAL= ".$this->getField("CERAI_TANGGAL")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE= ".$this->getField("LAST_DATE")."
			, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE ANAK_ID= ".$this->getField("ANAK_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		$str = "		
		UPDATE ANAK
		SET    
		STATUS= ".$this->getField("STATUS").",
		LAST_USER= '".$this->getField("LAST_USER")."',
		LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
		LAST_DATE= ".$this->getField("LAST_DATE").",
		USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
		USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE ANAK_ID= ".$this->getField("ANAK_ID")."
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE ANAK SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE ANAK_ID = ".$this->getField("ANAK_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.ANAK_ID ASC')
	{
		$str = "
		SELECT 
			B.NAMA SUAMI_ISTRI_NAMA
			, CASE A.STATUS_KELUARGA WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' WHEN '3' THEN 'Angkat' ELSE 'Belum diisi' END STATUS_KELUARGA_NAMA
			, CASE A.STATUS_AKTIF WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' ELSE '' END STATUS_NAMA
			, PA.ANAK_ID PENSIUN_ANAK_ID
			, datediff('year', A.TANGGAL_LAHIR, current_date) USIA
			, A.*
		FROM ANAK A
		LEFT JOIN SUAMI_ISTRI B ON A.SUAMI_ISTRI_ID = B.SUAMI_ISTRI_ID
		LEFT JOIN
		(
			SELECT PEGAWAI_ID, ANAK_ID
			FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK
			GROUP BY PEGAWAI_ID, ANAK_ID
		) PA ON A.PEGAWAI_ID = PA.PEGAWAI_ID AND A.ANAK_ID = PA.ANAK_ID
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
	
	function getCountByParamsSuratMasukPensiunAnak($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.ANAK_ID) AS ROWCOUNT 
				FROM PERSURATAN.SURAT_MASUK_PENSIUN_ANAK A
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
				SELECT COUNT(A.ANAK_ID) AS ROWCOUNT 
				FROM ANAK A
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