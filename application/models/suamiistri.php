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
  
  class SuamiIstri extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuamiIstri()
	{
      $this->Entity(); 
    }

	function insert()
	{
		$this->setField("SUAMI_ISTRI_ID", $this->getNextId("SUAMI_ISTRI_ID","SUAMI_ISTRI"));
 
     	$str = "
		INSERT INTO SUAMI_ISTRI 
		(
			SUAMI_ISTRI_ID, PEGAWAI_ID, PENDIDIKAN_ID, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, TANGGAL_KAWIN, KARTU
			, PEKERJAAN, STATUS_TUNJANGAN, STATUS_BAYAR, BULAN_BAYAR, STATUS_S_I
			, SURAT_NIKAH, NIK, CERAI_SURAT, CERAI_TMT, KEMATIAN_SURAT, KEMATIAN_TMT, STATUS_AKTIF
			, TANGGAL_MENINGGAL, STATUS_BEKERJA, GELAR_DEPAN, GELAR_BELAKANG, AKTA_KELAHIRAN, JENIS_ID_DOKUMEN, AGAMA_ID
			, EMAIL, HP, TELEPON, ALAMAT, BPJS_NO, BPJS_TANGGAL, NPWP_NO, NPWP_TANGGAL, STATUS_PNS, NIP_PNS, STATUS_LULUS
			, KEMATIAN_NO, KEMATIAN_TANGGAL, JENIS_KAWIN_ID, AKTA_NIKAH_NO, AKTA_NIKAH_TANGGAL, NIKAH_TANGGAL
			, AKTA_CERAI_NO, AKTA_CERAI_TANGGAL, CERAI_TANGGAL, JENIS_KELAMIN
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
		) 
		VALUES (
			".$this->getField("SUAMI_ISTRI_ID")."
			, ".$this->getField("PEGAWAI_ID")."
			, ".$this->getField("PENDIDIKAN_ID")."
			, '".$this->getField("NAMA")."'
			, '".$this->getField("TEMPAT_LAHIR")."'
			, ".$this->getField("TANGGAL_LAHIR")."
			, ".$this->getField("TANGGAL_KAWIN")."
			, '".$this->getField("KARTU")."'
			, '".$this->getField("PEKERJAAN")."'
			, ".$this->getField("STATUS_TUNJANGAN")."
			, ".$this->getField("STATUS_BAYAR")."
			, ".$this->getField("BULAN_BAYAR")."
			, '".$this->getField("STATUS_S_I")."'
			, '".$this->getField("SURAT_NIKAH")."'
			, '".$this->getField("NIK")."'
			, '".$this->getField("CERAI_SURAT")."'
			, ".$this->getField("CERAI_TMT")."
			, '".$this->getField("KEMATIAN_SURAT")."'
			, ".$this->getField("KEMATIAN_TMT")."
			, '".$this->getField("STATUS_AKTIF")."'
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
			, '".$this->getField("JENIS_KELAMIN")."'
			, '".$this->getField("LAST_USER")."'
			, ".$this->getField("LAST_DATE")."
			, ".$this->getField("LAST_LEVEL")."
			, ".$this->getField("USER_LOGIN_ID")."
			, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
		"; 	
		$this->id = $this->getField("SUAMI_ISTRI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE SUAMI_ISTRI
		SET    
			PENDIDIKAN_ID= ".$this->getField("PENDIDIKAN_ID")."
			, NAMA= '".$this->getField("NAMA")."'
			, TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."'
			, TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR")."
			, TANGGAL_KAWIN= ".$this->getField("TANGGAL_KAWIN")."
			, KARTU= '".$this->getField("KARTU")."'
			, PEKERJAAN= '".$this->getField("PEKERJAAN")."'
			, STATUS_TUNJANGAN= ".$this->getField("STATUS_TUNJANGAN")."
			, STATUS_BAYAR= ".$this->getField("STATUS_BAYAR")."
			, BULAN_BAYAR= ".$this->getField("BULAN_BAYAR")."
			, STATUS_S_I= '".$this->getField("STATUS_S_I")."'
			, SURAT_NIKAH= '".$this->getField("SURAT_NIKAH")."'
			, NIK= '".$this->getField("NIK")."'
			, CERAI_SURAT= '".$this->getField("CERAI_SURAT")."'
			, CERAI_TMT= ".$this->getField("CERAI_TMT")."
			, KEMATIAN_SURAT= '".$this->getField("KEMATIAN_SURAT")."'
			, KEMATIAN_TMT= ".$this->getField("KEMATIAN_TMT")."
			, STATUS_AKTIF= '".$this->getField("STATUS_AKTIF")."'
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
			, JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."'
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE=  ".$this->getField("LAST_DATE")."
			, USER_LOGIN_ID=  ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID=  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, LAST_LEVEL=  ".$this->getField("LAST_LEVEL")."  
		WHERE  SUAMI_ISTRI_ID = ".$this->getField("SUAMI_ISTRI_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function insertDataBkn()
	{
		$this->setField("SUAMI_ISTRI_ID", $this->getNextId("SUAMI_ISTRI_ID","SUAMI_ISTRI"));
 
     	$str = "
		INSERT INTO SUAMI_ISTRI 
		(
			SUAMI_ISTRI_ID, PEGAWAI_ID,NAMA,TEMPAT_LAHIR,TANGGAL_LAHIR,TANGGAL_KAWIN,SURAT_NIKAH,AKTA_NIKAH_NO,JENIS_KELAMIN,GELAR_DEPAN,GELAR_BELAKANG,CERAI_SURAT,CERAI_TANGGAL
			,CERAI_TMT,TANGGAL_MENINGGAL,KEMATIAN_SURAT,KEMATIAN_TMT,AKTA_CERAI_NO,AKTA_CERAI_TANGGAL,STATUS_PNS
		) 
		VALUES (
		".$this->getField("SUAMI_ISTRI_ID")."
		, ".$this->getField("PEGAWAI_ID")."
		, '".$this->getField("NAMA")."'
		, '".$this->getField("TEMPAT_LAHIR")."'
		, ".$this->getField("TANGGAL_LAHIR")."
		, ".$this->getField("TANGGAL_KAWIN")."
		, '".$this->getField("SURAT_NIKAH")."'
		, '".$this->getField("AKTA_NIKAH_NO")."'
		, '".$this->getField("JENIS_KELAMIN")."'
		, '".$this->getField("GELAR_DEPAN")."'
		, '".$this->getField("GELAR_BELAKANG")."'
		, '".$this->getField("CERAI_SURAT")."'
		, ".$this->getField("CERAI_TANGGAL")."
		, ".$this->getField("CERAI_TMT")."
		, ".$this->getField("TANGGAL_MENINGGAL")."
		, '".$this->getField("KEMATIAN_SURAT")."'
		, ".$this->getField("KEMATIAN_TMT")."
		, '".$this->getField("AKTA_CERAI_NO")."'
		, ".$this->getField("AKTA_CERAI_TANGGAL")."
		, ".$this->getField("STATUS_PNS")."
		)
		"; 	
		$this->id = $this->getField("SUAMI_ISTRI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function updateBknData()
    {
		$str = "		
		UPDATE SUAMI_ISTRI
		SET    
		
		 	 
		 	 PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		 	 NAMA= '".$this->getField("NAMA")."',
		 	 TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
		 	 TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
		 	 TANGGAL_KAWIN= ".$this->getField("TANGGAL_KAWIN").",
		 	 SURAT_NIKAH= '".$this->getField("SURAT_NIKAH")."',
		 	 JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
		 	 GELAR_DEPAN= '".$this->getField("GELAR_DEPAN")."',
		 	 GELAR_BELAKANG= '".$this->getField("GELAR_BELAKANG")."',
		 	 CERAI_SURAT= '".$this->getField("CERAI_SURAT")."',
		 	 CERAI_TANGGAL= ".$this->getField("CERAI_TANGGAL").",
		 	 CERAI_TMT= ".$this->getField("CERAI_TMT").",
		 	 TANGGAL_MENINGGAL= ".$this->getField("TANGGAL_MENINGGAL").",
		 	 KEMATIAN_SURAT= '".$this->getField("KEMATIAN_SURAT")."',
		 	 KEMATIAN_TMT= ".$this->getField("KEMATIAN_TMT").",
		 	 AKTA_CERAI_NO= '".$this->getField("AKTA_CERAI_NO")."',
		 	 AKTA_CERAI_TANGGAL= ".$this->getField("AKTA_CERAI_TANGGAL").",
		 	 STATUS_PNS= ".$this->getField("STATUS_PNS").",
		 	 AKTA_NIKAH_NO= '".$this->getField("AKTA_NIKAH_NO")."'
	
		WHERE  SUAMI_ISTRI_ID = ".$this->getField("SUAMI_ISTRI_ID")."
		"; 
		$this->query = $str;
	 //	 echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }


    function updateIdSapk()
    {
		$str = "		
		UPDATE SUAMI_ISTRI
		SET    
		
		 	 ID_SAPK= '".$this->getField("ID_SAPK")."'
	
		WHERE  SUAMI_ISTRI_ID = ".$this->getField("SUAMI_ISTRI_ID")."
		"; 
		$this->query = $str;
	 	// echo "xxx-".$str;exit;
		return $this->execQuery($str);
    }

    function updatepensiun()
	{
		$str = "		
		UPDATE SUAMI_ISTRI
		SET
			SURAT_NIKAH= '".$this->getField("SURAT_NIKAH")."'
			, TANGGAL_KAWIN= ".$this->getField("TANGGAL_KAWIN")."
			, AKTA_NIKAH_TANGGAL= ".$this->getField("AKTA_NIKAH_TANGGAL")."
			, STATUS_AKTIF= '".$this->getField("STATUS_AKTIF")."'
			, KEMATIAN_NO= '".$this->getField("KEMATIAN_NO")."'
			, KEMATIAN_TANGGAL= ".$this->getField("KEMATIAN_TANGGAL")."
			, TANGGAL_MENINGGAL= ".$this->getField("TANGGAL_MENINGGAL")."
			, STATUS_S_I= '".$this->getField("STATUS_S_I")."'
			, AKTA_NIKAH_NO= '".$this->getField("AKTA_NIKAH_NO")."'
			, NIKAH_TANGGAL= ".$this->getField("NIKAH_TANGGAL")."
			, AKTA_CERAI_NO= '".$this->getField("AKTA_CERAI_NO")."'
			, AKTA_CERAI_TANGGAL= ".$this->getField("AKTA_CERAI_TANGGAL")."
			, CERAI_TANGGAL= ".$this->getField("CERAI_TANGGAL")."
			, LAST_USER= '".$this->getField("LAST_USER")."'
			, LAST_DATE=  ".$this->getField("LAST_DATE")."
			, USER_LOGIN_ID=  ".$this->getField("USER_LOGIN_ID")."
			, USER_LOGIN_PEGAWAI_ID=  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			, LAST_LEVEL=  ".$this->getField("LAST_LEVEL")."  
		WHERE SUAMI_ISTRI_ID = ".$this->getField("SUAMI_ISTRI_ID")."
		";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SUAMI_ISTRI
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   USER_LOGIN_ID	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  SUAMI_ISTRI_ID    	= ".$this->getField("SUAMI_ISTRI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SUAMI_ISTRI SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SUAMI_ISTRI_ID = ".$this->getField("SUAMI_ISTRI_ID")."
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
    
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SUAMI_ISTRI_ID ASC')
	{
		// CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' ELSE 'Belum di set' END STATUS_S_I_NAMA
		$str = "
		SELECT 	
			A.*
			, CASE A.STATUS_PNS WHEN 1 THEN 'PNS' ELSE 'Non PNS' END STATUS_PNS_NAMA
		FROM SUAMI_ISTRI A
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

    function selectByParamsTanggalKawinAkhir($statement='', $sOrder="")
	{
		$str = "
		SELECT A.SUAMI_ISTRI_ID, A.TANGGAL_KAWIN, A.PEGAWAI_ID
		FROM
		(
			SELECT A.SUAMI_ISTRI_ID, A.TANGGAL_KAWIN, A.PEGAWAI_ID
			FROM SUAMI_ISTRI A
			WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		) A
		INNER JOIN
		(
			SELECT A.PEGAWAI_ID, MAX(A.TANGGAL_KAWIN) TANGGAL_KAWIN
			FROM SUAMI_ISTRI A
			WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
			GROUP BY A.PEGAWAI_ID
		) B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.TANGGAL_KAWIN = B.TANGGAL_KAWIN
		WHERE 1=1
		".$statement." ".$sOrder;
		$this->query = $str;
		return $this->selectLimit($str,-1,-1); 
    }

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.SUAMI_ISTRI_ID) AS ROWCOUNT 
				FROM SUAMI_ISTRI A
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