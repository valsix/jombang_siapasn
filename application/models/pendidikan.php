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
  
  class Pendidikan extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Pendidikan()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("PENDIDIKAN_ID", $this->getNextId("PENDIDIKAN_ID","PENDIDIKAN")); 

     	$str = "
			INSERT INTO PENDIDIKAN (
				PENDIDIKAN_ID, NAMA, PANGKAT_MINIMAL, PANGKAT_MAKSIMAL, NO_URUT, LAST_USER, LAST_DATE, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				   ".$this->getField("PENDIDIKAN_ID").",
			      '".$this->getField("NAMA")."',
			       ".$this->getField("PANGKAT_MINIMAL").",
			       ".$this->getField("PANGKAT_MAKSIMAL").",
			       ".$this->getField("NO_URUT").",
			       '".$this->getField("LAST_USER")."',
			       ".$this->getField("LAST_DATE").",
			       '".$this->getField("USER_LOGIN_ID")."',
			       ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("PENDIDIKAN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENDIDIKAN
				SET 	
				   NAMA= '".$this->getField("NAMA")."',
			       PANGKAT_MINIMAL= ".$this->getField("PANGKAT_MINIMAL").",
			       PANGKAT_MAKSIMAL= ".$this->getField("PANGKAT_MAKSIMAL").",
			       NO_URUT= ".$this->getField("NO_URUT").",
			       LAST_USER= '".$this->getField("LAST_USER")."',
			       USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
			       USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
			       LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE  PENDIDIKAN_ID = ".$this->getField("PENDIDIKAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PENDIDIKAN
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE")."
				WHERE  PENDIDIKAN_ID    = ".$this->getField("PENDIDIKAN_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM PENDIDIKAN
            	WHERE PENDIDIKAN_ID = ".$this->getField("PENDIDIKAN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.PENDIDIKAN_ID ASC')
	{
		$str = "
		SELECT
		CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA
		, COALESCE(RN.NILAI,0) NILAI_RUMPUN
		, CASE WHEN A.STATUS = '1' THEN
		CONCAT('<a onClick=\"hapusData(''',A.PENDIDIKAN_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		ELSE
		CONCAT('<a onClick=\"hapusData(''',A.PENDIDIKAN_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
		END LINK_URL_INFO
		, A.*
		FROM pendidikan A
		LEFT JOIN
		(
			SELECT * FROM talent.rumpun_nilai WHERE INFOMODE = 'pendidikan' AND PERMEN_ID = 1
		) RN ON INFOID = A.PENDIDIKAN_ID
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
				SELECT COUNT(A.PENDIDIKAN_ID) AS ROWCOUNT 
				FROM PENDIDIKAN A
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