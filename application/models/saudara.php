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
  
  class Saudara extends Entity{ 

	 var $query;
  	var $id;
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
		$this->setField("SAUDARA_ID", $this->getNextId("SAUDARA_ID","SAUDARA"));
     	$str = "
			INSERT INTO SAUDARA (
			SAUDARA_ID, PEGAWAI_ID, NAMA, TEMPAT_LAHIR, TANGGAL_LAHIR, JENIS_KELAMIN, PEKERJAAN, ALAMAT, KODEPOS, TELEPON, PROPINSI_ID, 
			KABUPATEN_ID, KECAMATAN_ID, KELURAHAN_ID, STATUS_HIDUP, STATUS_SDR, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
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
			)
		"; 	
		$this->id = $this->getField("SAUDARA_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

 	function update() 
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SAUDARA
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
				WHERE  SAUDARA_ID = ".$this->getField("SAUDARA_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SAUDARA
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  SAUDARA_ID    	= ".$this->getField("SAUDARA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SAUDARA SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE SAUDARA_ID = ".$this->getField("SAUDARA_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SAUDARA_ID ASC')
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

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.SAUDARA_ID) AS ROWCOUNT 
				FROM SAUDARA A
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