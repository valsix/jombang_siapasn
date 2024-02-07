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
				TANDA_TANGAN_BKD_ID, NAMA, LAST_USER, LAST_DATE
			) 
			VALUES (
				".$this->getField("TANDA_TANGAN_BKD_ID").",
				'".$this->getField("NAMA")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE")."
			)
		"; 	
		$this->id = $this->getField("TANDA_TANGAN_BKD_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE TANDA_TANGAN_BKD
				SET    
					NAMA= '".$this->getField("NAMA")."',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  TANDA_TANGAN_BKD_ID = ".$this->getField("TANDA_TANGAN_BKD_ID")."
				"; 
		$this->query = $str;
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
				TANDA_TANGAN_BKD_ID, MULAI_BERLAKU, AKHIR_BERLAKU, NO_NOMENKLATUR_KAB, 
				NO_NOMENKLATUR_BKD, NAMA, PLT_JABATAN, NAMA_PEJABAT, PANGKAT_ID, 
				KODE_PANGKAT, PANGKAT, NIP, PEJABAT_PENETAP, A.PEJABAT_PENETAP_LENGKAP
				FROM TANDA_TANGAN_BKD A
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

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANDA_TANGAN_BKD_ID ASC')
	{
		$str = "
				SELECT A.TANDA_TANGAN_BKD_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'AKtif' END STATUS_NAMA, 
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.TANDA_TANGAN_BKD_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.TANDA_TANGAN_BKD_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM TANDA_TANGAN_BKD A
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
				SELECT COUNT(A.TANDA_TANGAN_BKD_ID) AS ROWCOUNT 
				FROM TANDA_TANGAN_BKD A
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