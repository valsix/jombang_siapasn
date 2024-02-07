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
  
class Saudara extends Entity
{ 

	var $query;
  /**
  * Class constructor.
  **/
  function Saudara()
	{
    $this->Entity(); 
  }

  function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		// $this->setField("SAUDARA_ID", $this->getNextId("SAUDARA_ID","SAUDARA"));
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.SAUDARA")); 

   	$str = "
			INSERT INTO validasi.SAUDARA 
			(
				SAUDARA_ID, PEGAWAI_ID, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, PEKERJAAN, ALAMAT, KODEPOS, TELEPON, PROPINSI_ID, 
				KABUPATEN_ID, KECAMATAN_ID, KELURAHAN_ID, STATUS_HIDUP, STATUS_SDR, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
				, LAST_CREATE_USER, LAST_CREATE_DATE
				, TEMP_VALIDASI_ID
			) 
			VALUES 
			(
			  ".$this->getField("SAUDARA_ID").",
			  ".$this->getField("PEGAWAI_ID").",
			  '".$this->getField("NAMA")."',
			  '".$this->getField("TEMPAT_LAHIR")."',
			  ".$this->getField("TANGGAL_LAHIR").",
			  '".$this->getField("JENIS_KELAMIN")."',
			  '".$this->getField("PEKERJAAN")."',
			  '".$this->getField("ALAMAT")."',
			  '".$this->getField("KODEPOS")."',
			  '".$this->getField("TELEPON")."',
			  ".$this->getField("PROPINSI_ID").",
			  ".$this->getField("KABUPATEN_ID").",
			  ".$this->getField("KECAMATAN_ID").",
			  ".$this->getField("KELURAHAN_ID").",
			  '".$this->getField("STATUS_HIDUP")."',
			  '".$this->getField("STATUS_SDR")."',
			  '".$this->getField("LAST_USER")."',
			  ".$this->getField("LAST_DATE").",
			  ".$this->getField("LAST_LEVEL").",
			  ".$this->getField("USER_LOGIN_ID").",
			  ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			  , '".$this->getField("LAST_USER")."'
			  , NOW()
			  , ".$this->getField("TEMP_VALIDASI_ID")."
			)
		"; 	

		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
  }

  function update() 
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
			UPDATE validasi.SAUDARA
			SET    
		  	PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
		  	NAMA= '".$this->getField("NAMA")."',
		  	TEMPAT_LAHIR= '".$this->getField("TEMPAT_LAHIR")."',
		  	TANGGAL_LAHIR= ".$this->getField("TANGGAL_LAHIR").",
		  	JENIS_KELAMIN= '".$this->getField("JENIS_KELAMIN")."',
		  	PEKERJAAN= '".$this->getField("PEKERJAAN")."',
		  	ALAMAT= '".$this->getField("ALAMAT")."',
		  	KODEPOS= '".$this->getField("KODEPOS")."',
		  	TELEPON= '".$this->getField("TELEPON")."',
		  	PROPINSI_ID= ".$this->getField("PROPINSI_ID").",
		  	KABUPATEN_ID= ".$this->getField("KABUPATEN_ID").",
		  	KECAMATAN_ID= ".$this->getField("KECAMATAN_ID").",
		  	KELURAHAN_ID= ".$this->getField("KELURAHAN_ID").",
		  	STATUS_HIDUP= ".$this->getField("STATUS_HIDUP").",
		  	STATUS_SDR= '".$this->getField("STATUS_SDR")."',
		  	LAST_USER= '".$this->getField("LAST_USER")."',
		  	LAST_DATE= ".$this->getField("LAST_DATE").",
		  	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
		  	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
		  	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
		  	, LAST_USER= '".$this->getField("LAST_USER")."'
	 		, LAST_DATE= NOW()
			TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
		"; 

		$this->query = $str;
		// echo $str;exit;

		return $this->execQuery($str);
  }
	
  function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.SAUDARA_ID ASC')
	{
		$str = "
		SELECT
		A.SAUDARA_ID, A.PEGAWAI_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.JENIS_KELAMIN, A.PEKERJAAN, 
		A.ALAMAT, A.KODEPOS, A.TELEPON, A.PROPINSI_ID, A.KABUPATEN_ID, A.KECAMATAN_ID, A.KELURAHAN_ID,
		A.STATUS_HIDUP, A.STATUS_SDR,  A.STATUS,
		CASE A.STATUS_HIDUP WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' END STATUS_HIDUP_NAMA,
		CASE A.STATUS_SDR WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' WHEN '3' THEN 'Angkat' END STATUS_SDR_NAMA,
		A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		FROM SAUDARA A
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from);	
  }
	 
  function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
			SELECT 
				COUNT(A.SAUDARA_ID) AS ROWCOUNT 
			FROM SAUDARA A
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

  	function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.SAUDARA_ID ASC')
	{
		$str = "
		SELECT
		A.*,
		CASE A.STATUS_HIDUP WHEN '1' THEN 'Aktif' WHEN '2' THEN 'Meninggal' END STATUS_HIDUP_NAMA,
		CASE A.STATUS_SDR WHEN '1' THEN 'Kandung' WHEN '2' THEN 'Tiri' WHEN '3' THEN 'Angkat' END STATUS_SDR_NAMA
		FROM (select * from validasi.validasi_pegawai_saudara('".$pegawaiid."', '".$id."', '".$rowid."')) A
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from);	
  	}

} 
?>