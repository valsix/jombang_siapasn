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
  
  class SuratMasukPegawaiTurunStatus extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratMasukPegawaiTurunStatus()
	{
      $this->Entity(); 
    }

	function insert()
	{
		$this->setField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID","PERSURATAN.SURAT_MASUK_PEGAWAI_TURUN_STATUS")); 

     	$str = "
     	INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI_TURUN_STATUS
     	(
     		SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID, SURAT_MASUK_PEGAWAI_ID, KETERANGAN
     		, JENIS
     		, LAST_USER, LAST_DATE, LAST_LEVEL
     	)
     	VALUES
     	(
	     	".$this->getField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID")."
	     	, ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
	     	, '".$this->getField("KETERANGAN")."'
	     	, '".$this->getField("JENIS")."'
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, ".$this->getField("LAST_LEVEL")."
     	)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID");
		$this->query = $str;
		//echo $str;exit;
		$this->execQuery($str);
		
		//set status kembali
		$strKembali= "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_KEMBALI= 1, STATUS_PERNAH_TURUN = 1
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $strKembali;
		// echo $strKembali;exit;
		return $this->execQuery($strKembali);
		
    }

    function insertdata()
	{
		$this->setField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID", $this->getNextId("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID","PERSURATAN.SURAT_MASUK_PEGAWAI_TURUN_STATUS")); 

     	$str = "
     	INSERT INTO PERSURATAN.SURAT_MASUK_PEGAWAI_TURUN_STATUS
     	(
     		SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID, SURAT_MASUK_PEGAWAI_ID, KETERANGAN
     		, JENIS
     		, LAST_USER, LAST_DATE, LAST_LEVEL
     	)
     	VALUES
     	(
	     	".$this->getField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID")."
	     	, ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
	     	, '".$this->getField("KETERANGAN")."'
	     	, '".$this->getField("JENIS")."'
	     	, '".$this->getField("LAST_USER")."'
	     	, ".$this->getField("LAST_DATE")."
	     	, ".$this->getField("LAST_LEVEL")."
     	)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function kirimUlang()
	{
        $str = "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_KEMBALI= NULL,
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }

    function statusberkas()
	{
        $str = "
        UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
        SET
	        STATUS_BERKAS= ".$this->getField("STATUS_BERKAS")."
	        , LAST_USER= '".$this->getField("LAST_USER")."'
	        , LAST_DATE= ".$this->getField("LAST_DATE")."
	        , LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
	        , USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
	        , USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
        WHERE SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
        ";
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function statusPegawaiTms()
	{
        $str = "
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
				 	STATUS_TMS= ".$this->getField("STATUS_TMS").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
				WHERE SURAT_MASUK_PEGAWAI_ID= ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				";
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.LAST_DATE ASC')
	{
		$str = "
		SELECT
			A.*
		FROM PERSURATAN.SURAT_MASUK_PEGAWAI_TURUN_STATUS A
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
				SELECT COUNT(A.SURAT_MASUK_PEGAWAI_TURUN_STATUS_ID) AS ROWCOUNT 
				FROM SURAT_MASUK_PEGAWAI_TURUN_STATUS A
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