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
  
  class TandaTanganBkd extends Entity{ 

  	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function TandaTanganBkd()
    {
    	$this->Entity(); 
    }

    function insert()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$this->setField("TANDA_TANGAN_BKD_ID", $this->getNextId("TANDA_TANGAN_BKD_ID","TANDA_TANGAN_BKD")); 

    	$str = "
    	INSERT INTO TANDA_TANGAN_BKD (
    	TANDA_TANGAN_BKD_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NO_NOMENKLATUR_KAB, NO_NOMENKLATUR_BKD, NAMA, PLT_JABATAN, NAMA_PEJABAT, PANGKAT_ID, KODE_PANGKAT, PANGKAT, NIP, PEJABAT_PENETAP, PEJABAT_PENETAP_LENGKAP, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
    	) 
    	VALUES (
    	".$this->getField("TANDA_TANGAN_BKD_ID").",
    	".$this->getField("MULAI_BERLAKU").",
    	".$this->getField("AKHIR_BERLAKU").",
    	'".$this->getField("NO_NOMENKLATUR_KAB")."',
    	'".$this->getField("NO_NOMENKLATUR_BKD")."',
    	'".$this->getField("NAMA")."',
    	'".$this->getField("PLT_JABATAN")."',
    	'".$this->getField("NAMA_PEJABAT")."',
    	".$this->getField("PANGKAT_ID").",
    	'".$this->getField("KODE_PANGKAT")."',
    	'".$this->getField("PANGKAT")."',
    	'".$this->getField("NIP")."',
        '".$this->getField("PEJABAT_PENETAP")."',
    	'".$this->getField("PEJABAT_PENETAP_LENGKAP")."',
        '".$this->getField("USER_LOGIN_ID")."',
        ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		)
    	"; 	
    	$this->id = $this->getField("TANDA_TANGAN_BKD_ID");
    	$this->query = $str;
		//echo $str;exit;
    	return $this->execQuery($str);
    }


    function update()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$str = "		
    	UPDATE TANDA_TANGAN_BKD
    	SET    
    	MULAI_BERLAKU= ".$this->getField("MULAI_BERLAKU").",
    	AKHIR_BERLAKU= ".$this->getField("AKHIR_BERLAKU").",
    	NO_NOMENKLATUR_KAB= '".$this->getField("NO_NOMENKLATUR_KAB")."',
    	NO_NOMENKLATUR_BKD= '".$this->getField("NO_NOMENKLATUR_BKD")."',
    	NAMA= '".$this->getField("NAMA")."',
    	PLT_JABATAN= '".$this->getField("PLT_JABATAN")."',
    	NAMA_PEJABAT= '".$this->getField("NAMA_PEJABAT")."',
    	PANGKAT_ID= ".$this->getField("PANGKAT_ID").",
    	KODE_PANGKAT= '".$this->getField("KODE_PANGKAT")."',
    	PANGKAT= '".$this->getField("PANGKAT")."',
    	NIP= '".$this->getField("NIP")."',
    	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
        USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
		PEJABAT_PENETAP_LENGKAP= '".$this->getField("PEJABAT_PENETAP_LENGKAP")."'
    	WHERE  TANDA_TANGAN_BKD_ID = ".$this->getField("TANDA_TANGAN_BKD_ID")."
    	"; 
    	$this->query = $str;
        // echo $str;exit();
    	return $this->execQuery($str);
    }

    function updateStatus()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$str = "		
    	UPDATE TANDA_TANGAN_BKD
    	SET    
    	STATUS   	= ".$this->getField("STATUS").",
    	LAST_USER	= '".$this->getField("LAST_USER")."',
        USER_LOGIN_ID'".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID".$this->getField("USER_LOGIN_PEGAWAI_ID").",
    	LAST_DATE	= ".$this->getField("LAST_DATE")."
    	WHERE  TANDA_TANGAN_BKD_ID    	= ".$this->getField("TANDA_TANGAN_BKD_ID")."
    	"; 
    	$this->query = $str;
    	return $this->execQuery($str);
    }

    function delete()
    {
        /*$str = "
				DELETE FROM TANDA_TANGAN_BKD
            	WHERE TANDA_TANGAN_BKD_ID = ".$this->getField("TANDA_TANGAN_BKD_ID")."
            	";*/ 
            	$str = "
            	UPDATE TANDA_TANGAN_BKD SET
            	STATUS = '1',
            	LAST_USER= '".$this->getField("LAST_USER")."',
                USER_LOGIN_ID'".$this->getField("USER_LOGIN_ID")."',
        USER_LOGIN_PEGAWAI_ID".$this->getField("USER_LOGIN_PEGAWAI_ID").",
            	LAST_DATE= ".$this->getField("LAST_DATE")."
            	WHERE TANDA_TANGAN_BKD_ID = ".$this->getField("TANDA_TANGAN_BKD_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANDA_TANGAN_BKD_ID ASC')
    {
    	$str = "
    	SELECT 
    	A.TANDA_TANGAN_BKD_ID, A.MULAI_BERLAKU, A.AKHIR_BERLAKU, A.NO_NOMENKLATUR_KAB, A.NO_NOMENKLATUR_BKD, A.NAMA, A.PLT_JABATAN, A.NAMA_PEJABAT, A.PANGKAT_ID, A.KODE_PANGKAT, A.PANGKAT, A.NIP, A.PEJABAT_PENETAP
		, A.PEJABAT_PENETAP_LENGKAP
    	FROM TANDA_TANGAN_BKD A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANDA_TANGAN_BKD_ID ASC')
    {
    	$str = "
    	SELECT A.TANDA_TANGAN_BKD_ID, A.MULAI_BERLAKU, A.AKHIR_BERLAKU, A.NO_NOMENKLATUR_KAB, A.NO_NOMENKLATUR_BKD, A.NAMA, A.PLT_JABATAN, A.NAMA_PEJABAT, A.PANGKAT_ID, A.KODE_PANGKAT, A.PANGKAT, A.NIP, A.PEJABAT_PENETAP
    	-- CASE WHEN A.STATUS = '1' THEN
    	-- CONCAT('<a onClick=\"hapusData(''',A.TANDA_TANGAN_BKD_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
    	-- ELSE
    	-- CONCAT('<a onClick=\"hapusData(''',A.TANDA_TANGAN_BKD_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
    	-- END LINK_URL_INFO
    	FROM TANDA_TANGAN_BKD A
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
    	SELECT COUNT(A.TANDA_TANGAN_BKD_ID) AS ROWCOUNT 
    	FROM TANDA_TANGAN_BKD A
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