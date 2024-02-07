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
  
  class Klasifikasi extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function Klasifikasi()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("KLASIFIKASI_ID", $this->getNextId("KLASIFIKASI_ID","PERSURATAN.KLASIFIKASI")); 

     	$str = "
			INSERT INTO PERSURATAN.KLASIFIKASI (
				KLASIFIKASI_ID, KLASIFIKASI_PARENT_ID, KODE, NAMA
				)
			VALUES (
				 ".$this->getField("KLASIFIKASI_ID").",
				 ".$this->getField("KLASIFIKASI_PARENT_ID").",
				 ".$this->getField("KODE").",
				 '".$this->getField("NAMA")."'
			)
		"; 	
		$this->id = $this->getField("KLASIFIKASI_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.KLASIFIKASI
				SET
					KODE= '".$this->getField("KODE")."',
					NAMA= '".$this->getField("NAMA")."'
				WHERE KLASIFIKASI_ID = ".$this->getField("KLASIFIKASI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PERSURATAN.URAT_MASUK SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE KLASIFIKASI_ID = ".$this->getField("KLASIFIKASI_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.KLASIFIKASI_ID ASC')
	{
		$str = "
		SELECT
			A.KLASIFIKASI_ID, A.KLASIFIKASI_PARENT_ID, A.KODE, A.NAMA
			, PERSURATAN.AMBIL_KLAFIKASI_DETIL(A.KLASIFIKASI_ID) KLASIFIKASI_DETIL
		FROM PERSURATAN.KLASIFIKASI A
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
				SELECT COUNT(1) AS ROWCOUNT 
				FROM PERSURATAN.KLASIFIKASI A
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