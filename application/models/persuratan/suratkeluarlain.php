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
  
  class SuratKeluarLain extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratKeluarLain()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_KELUAR_LAIN_ID", $this->getNextId("SURAT_KELUAR_LAIN_ID","PERSURATAN.SURAT_KELUAR_LAIN")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_KELUAR_LAIN (
				SURAT_KELUAR_LAIN_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID
				, NOMOR, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_NAMA
			) 
			VALUES (
				 ".$this->getField("SURAT_KELUAR_LAIN_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 '".$this->getField("NOMOR")."',
				 ".$this->getField("TANGGAL").",
				 '".$this->getField("PERIHAL")."',
				 '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."'
			)
		"; 	
		$this->id = $this->getField("SURAT_KELUAR_LAIN_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_LAIN
				SET
				 	JENIS_ID				= ".$this->getField("JENIS_ID").",
				 	SURAT_MASUK_BKD_ID		= ".$this->getField("SURAT_MASUK_BKD_ID").",
				 	SURAT_MASUK_UPT_ID		= ".$this->getField("SURAT_MASUK_UPT_ID").",
					NOMOR					= '".$this->getField("NOMOR")."',
					TANGGAL					= ".$this->getField("TANGGAL").",
					PERIHAL					= '".$this->getField("PERIHAL")."',
					SATUAN_KERJA_TUJUAN_NAMA= '".$this->getField("SATUAN_KERJA_TUJUAN_NAMA")."'
				WHERE SURAT_KELUAR_LAIN_ID	= ".$this->getField("SURAT_KELUAR_LAIN_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				DELETE FROM persuratan.SURAT_KELUAR_LAIN
				WHERE SURAT_KELUAR_LAIN_ID = ".$this->getField("SURAT_KELUAR_LAIN_ID")."
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
    function selectByParamsTahun($statement='',$order="ORDER BY TO_CHAR(TANGGAL, 'YYYY')")
	{
		$str = "
		SELECT TO_CHAR(TANGGAL, 'YYYY') TAHUN 
		FROM persuratan.SURAT_KELUAR_LAIN A 
		WHERE 1=1 
		"; 
		
		$str .= $statement." GROUP BY TO_CHAR(TANGGAL, 'YYYY') ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_LAIN_ID ASC')
	{
		$str = "
			SELECT
				SURAT_KELUAR_LAIN_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
				NOMOR, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_NAMA
			FROM persuratan.SURAT_KELUAR_LAIN A
			WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_LAIN_ID ASC')
	{
		$str = "
			SELECT
				SURAT_KELUAR_LAIN_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
				NOMOR, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_NAMA
			FROM persuratan.SURAT_KELUAR_LAIN A
			WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_KELUAR_LAIN_ID ASC')
	{
		$str = "
			SELECT
				SURAT_KELUAR_LAIN_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, 
				NOMOR, TANGGAL, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_NAMA
			FROM persuratan.SURAT_KELUAR_LAIN A
			WHERE 1=1
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from);
    }

	function getCountByParamsMonitoring($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_KELUAR_LAIN A
				WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsData($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM persuratan.SURAT_KELUAR_LAIN A
		WHERE 1=1 ".$statement; 
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
	
	function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM persuratan.SURAT_KELUAR_LAIN A
		WHERE 1=1 ".$statement; 
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
	
	function getCountByParamsTanggalPerTahun($statement='')
	{
		$str = "
			SELECT
				MAX(TANGGAL) AS ROWCOUNT 
			FROM persuratan.SURAT_KELUAR_LAIN A 
			WHERE 1=1
			".$statement; 
			
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
    }
	
  } 
?>