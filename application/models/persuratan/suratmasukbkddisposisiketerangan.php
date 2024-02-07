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
  
  class SuratMasukBkdDisposisiKeterangan extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SuratMasukBkdDisposisiKeterangan()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID", $this->getNextId("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID","PERSURATAN.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN (
				SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID, SURAT_MASUK_BKD_DISPOSISI_ID, SURAT_MASUK_BKD_ID, JENIS_ID, PEGAWAI_ID, ISI)
			VALUES (
				 ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("ISI")."'
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN
				SET
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					ISI= '".$this->getField("ISI")."'
				WHERE SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID= ".$this->getField("SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID")."
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
	
	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID ASC')
	{
		$str = "
		SELECT 
			A.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN_ID, A.SURAT_MASUK_BKD_DISPOSISI_ID, A.SURAT_MASUK_BKD_ID, A.JENIS_ID, A.ISI
			, A.PEGAWAI_ID, NAMA_LENGKAP PEGAWAI_NAMA
		FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN A
		LEFT JOIN
		(
			SELECT A.PEGAWAI_ID
			, (CASE WHEN COALESCE(NULLIF(A.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE A.GELAR_DEPAN || ' ' END) || A.NAMA || (CASE WHEN COALESCE(NULLIF(A.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || A.GELAR_BELAKANG END) NAMA_LENGKAP
			FROM PEGAWAI A
			WHERE 1=1
		) B ON A.PEGAWAI_ID = B.PEGAWAI_ID
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

    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM persuratan.SURAT_MASUK_BKD_DISPOSISI_KETERANGAN A
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
	
  } 
?>