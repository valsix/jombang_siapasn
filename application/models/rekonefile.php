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
  
  class RekonFile extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function RekonMasuk()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SURAT_MASUK_ID", $this->getNextId("SURAT_MASUK_ID","PERSURATAN.SURAT_MASUK")); 

     	$str = "
			INSERT INTO PERSURATAN.SURAT_MASUK (
				SURAT_MASUK_ID, NOMOR, NO_AGENDA, TANGGAL, TANGGAL_DITERUSKAN, TANGGAL_BATAS, KEPADA, PERIHAL, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID				) 

			VALUES (
				 ".$this->getField("SURAT_MASUK_ID").",
				 '".$this->getField("NOMOR")."',
				 '".$this->getField("NO_AGENDA")."',
				 ".$this->getField("TANGGAL").",
				 ".$this->getField("TANGGAL_DITERUSKAN").",
				 ".$this->getField("TANGGAL_BATAS").",
				 '".$this->getField("KEPADA")."',
				 '".$this->getField("PERIHAL")."',
				 ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 ".$this->getField("SATUAN_KERJA_ASAL_ID")."
				
			)
		"; 	
		$this->id = $this->getField("SURAT_MASUK_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK
				SET    
				 	NOMOR= '".$this->getField("NOMOR")."',
				 	NO_AGENDA= '".$this->getField("NO_AGENDA")."',
				 	TANGGAL= ".$this->getField("TANGGAL").",
				 	TANGGAL_DITERUSKAN= ".$this->getField("TANGGAL_DITERUSKAN").",
				 	TANGGAL_BATAS= ".$this->getField("TANGGAL_BATAS").",
				 	KEPADA= '".$this->getField("KEPADA")."',
				 	PERIHAL= '".$this->getField("PERIHAL")."',
				 	SATUAN_KERJA_TUJUAN_ID= ".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				 	SATUAN_KERJA_ASAL_ID= ".$this->getField("SATUAN_KERJA_ASAL_ID")."
				WHERE  SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
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
				WHERE SURAT_MASUK_ID = ".$this->getField("SURAT_MASUK_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SURAT_MASUK_ID ASC')
	{
		$str = "
				SELECT
					A.SURAT_MASUK_ID, A.NOMOR, A.NO_AGENDA, A.TANGGAL, A.TANGGAL_DITERUSKAN, A.TANGGAL_BATAS, 
					A.KEPADA, A.PERIHAL, A.SATUAN_KERJA_TUJUAN_ID, A.SATUAN_KERJA_ASAL_ID, B.NAMA SATUAN_KERJA_ASAL_NAMA, C.NAMA SATUAN_KERJA_TUJUAN_NAMA
				FROM PERSURATAN.SURAT_MASUK A
				LEFT JOIN SATUAN_KERJA B ON A.SATUAN_KERJA_ASAL_ID = B.SATUAN_KERJA_ID
				LEFT JOIN SATUAN_KERJA C ON A.SATUAN_KERJA_TUJUAN_ID = C.SATUAN_KERJA_ID
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
				SELECT COUNT(A.SURAT_MASUK_ID) AS ROWCOUNT 
				FROM SURAT_MASUK A
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



    function selectByParamsRekoneFileSKCPNS($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY pegawai_id_file ASC')
	{
		$str = "
				SELECT
				"public".pegawai.pegawai_id,
				"public".pegawai.status_pegawai_id,
				"public".pegawai.nip_baru,
				"public".pegawai.nama,
				"public".pegawai_file.pegawai_id AS pegawai_id_file,
				"public".pegawai_file.riwayat_table,
				"public".pegawai_file.riwayat_field,
				"public".pegawai_file.file_kualitas_id,
				"public".pegawai_file.status,
				"public".pegawai_file."path"
				FROM
				"public".pegawai
				INNER JOIN "public".pegawai_file ON "public".pegawai_file.pegawai_id = "public".pegawai.pegawai_id
				WHERE
				"public".pegawai.status_pegawai_id IN (1, 2) AND
				("public".pegawai_file.riwayat_field LIKE '%skcpns%')
		"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
  } 
?>