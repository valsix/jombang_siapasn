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
  
  class JenisPelayanan extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function JenisPelayanan()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JENIS_PELAYANAN_ID", $this->getNextId("JENIS_PELAYANAN_ID","PERSURATAN.JENIS_PELAYANAN")); 

     	$str = "
			INSERT INTO PERSURATAN.JENIS_PELAYANAN (
				JENIS_PELAYANAN_ID, JUDUL_CHEKLIST, NAMA
				)
			VALUES (
				 ".$this->getField("JENIS_PELAYANAN_ID").",
				 ".$this->getField("JUDUL_CHEKLIST").",
				 '".$this->getField("NAMA")."'
			)
		"; 	
		$this->id = $this->getField("JENIS_PELAYANAN_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.JENIS_PELAYANAN
				SET
					JUDUL_CHEKLIST= '".$this->getField("JUDUL_CHEKLIST")."',
					NAMA= '".$this->getField("NAMA")."'
				WHERE JENIS_PELAYANAN_ID = ".$this->getField("JENIS_PELAYANAN_ID")."
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
				WHERE JENIS_PELAYANAN_ID = ".$this->getField("JENIS_PELAYANAN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JENIS_PELAYANAN_ID ASC')
	{
		$str = "
		SELECT
			A.JENIS_PELAYANAN_ID, A.JUDUL_CHEKLIST, A.NAMA, A.SATUAN_KERJA_TUJUAN_NAMA, A.KEPADA
		FROM PERSURATAN.JENIS_PELAYANAN A
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
				FROM PERSURATAN.JENIS_PELAYANAN A
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