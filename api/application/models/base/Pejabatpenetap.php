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
  
  class PejabatPenetap extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function PejabatPenetap()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PEJABAT_PENETAP_ID", $this->getNextId("PEJABAT_PENETAP_ID","PEJABAT_PENETAP")); 

     	$str = "
			INSERT INTO PEJABAT_PENETAP (
				PEJABAT_PENETAP_ID, NAMA, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				   ".$this->getField("PEJABAT_PENETAP_ID").",
			      '".$this->getField("NAMA")."',
			      '".$this->getField("LAST_USER")."',
			       ".$this->getField("LAST_DATE")." ,
			      '".$this->getField("USER_LOGIN_ID")."',
			      ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PEJABAT_PENETAP_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEJABAT_PENETAP
				SET    
			      	NAMA= '".$this->getField("NAMA")."',
			      	LAST_USER= '".$this->getField("LAST_USER")."',
			      	USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
			      	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			       	LAST_DATE= ".$this->getField("LAST_DATE")." 
				WHERE  PEJABAT_PENETAP_ID = ".$this->getField("PEJABAT_PENETAP_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PEJABAT_PENETAP
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   USER_LOGIN_ID	= '".$this->getField("USER_LOGIN_ID")."',
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PEJABAT_PENETAP_ID    = ".$this->getField("PEJABAT_PENETAP_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
				DELETE FROM PEJABAT_PENETAP
            	WHERE PEJABAT_PENETAP_ID = ".$this->getField("PEJABAT_PENETAP_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PEJABAT_PENETAP_ID ASC')
	{
		$str = "
				SELECT A.PEJABAT_PENETAP_ID, A.NAMA, A.LAST_USER, A.LAST_DATE,
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.PEJABAT_PENETAP_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.PEJABAT_PENETAP_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				FROM PEJABAT_PENETAP A
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
				SELECT COUNT(A.PEJABAT_PENETAP_ID) AS ROWCOUNT 
				FROM PEJABAT_PENETAP A
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