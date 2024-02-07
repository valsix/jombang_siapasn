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
  
  class SatuanKerja extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function SatuanKerja()
	{
      $this->Entity(); 
    }
	
	function insertbak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("SATUAN_KERJA_ID", $this->getNextId("SATUAN_KERJA_ID","SATUAN_KERJA")); 

		$str = "
			INSERT INTO SATUAN_KERJA (
				SATUAN_KERJA_ID, SATUAN_KERJA_PARENT_ID, NAMA, NO_URUT, NAMA_SINGKAT, NAMA_PENANDATANGAN, 
				TIPE_ID, NAMA_JABATAN, TIPE_JABATAN_ID, ESELON_ID, SATUAN_KERJA_INDUK, SATUAN_KERJA_URUTAN_SURAT, 
				MASA_BERLAKU_AWAL, MASA_BERLAKU_AKHIR, KONVERSI, ID_SAPK, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("SATUAN_KERJA_ID").",
				".$this->getField("SATUAN_KERJA_PARENT_ID").",
				'".$this->getField("NAMA")."',
				".$this->getField("NO_URUT").",
				'".$this->getField("NAMA_SINGKAT")."',
				'".$this->getField("NAMA_PENANDATANGAN")."',
				".$this->getField("TIPE_ID").",
				'".$this->getField("NAMA_JABATAN")."',
				".$this->getField("TIPE_JABATAN_ID").",
				".$this->getField("ESELON_ID").",
				".$this->getField("SATUAN_KERJA_INDUK").",
				".$this->getField("SATUAN_KERJA_URUTAN_SURAT").",
				".$this->getField("MASA_BERLAKU_AWAL").",
				".$this->getField("MASA_BERLAKU_AKHIR").",
				'".$this->getField("KONVERSI")."',
				'".$this->getField("ID_SAPK")."',
				'".$this->getField("USER_LOGIN_ID")."',
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("SATUAN_KERJA_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SATUAN_KERJA
				SET    
					NAMA= '".$this->getField("NAMA")."',
					NAMA_SINGKAT= '".$this->getField("NAMA_SINGKAT")."',
					TIPE_ID= ".$this->getField("TIPE_ID").",
					TIPE_JABATAN_ID= ".$this->getField("TIPE_JABATAN_ID").",
					JENIS_JABATAN_ID= ".$this->getField("JENIS_JABATAN_ID").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					SATUAN_KERJA_URUTAN_SURAT= ".$this->getField("SATUAN_KERJA_URUTAN_SURAT").",
					KODE= ".$this->getField("KODE").",
					ESELON_ID= ".$this->getField("ESELON_ID").",
					SATUAN_KERJA_MUTASI_STATUS= ".$this->getField("SATUAN_KERJA_MUTASI_STATUS").",
					SATUAN_KERJA_INDUK_ID= ".$this->getField("SATUAN_KERJA_INDUK_ID").",
					NAMA_PENANDATANGAN= ".$this->getField("NAMA_PENANDATANGAN").",
					NAMA_JABATAN= ".$this->getField("NAMA_JABATAN").",
					MASA_BERLAKU_AWAL= ".$this->getField("MASA_BERLAKU_AWAL").",
					MASA_BERLAKU_AKHIR= ".$this->getField("MASA_BERLAKU_AKHIR")."
				WHERE SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function updatebak()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SATUAN_KERJA
				SET    
					SATUAN_KERJA_ID= ".$this->getField("SATUAN_KERJA_ID").",
					SATUAN_KERJA_PARENT_ID= ".$this->getField("SATUAN_KERJA_PARENT_ID").",
					NAMA= '".$this->getField("NAMA")."',
					NO_URUT= ".$this->getField("NO_URUT").",
					NAMA_SINGKAT= '".$this->getField("NAMA_SINGKAT")."',
					NAMA_PENANDATANGAN= '".$this->getField("NAMA_PENANDATANGAN")."',
					TIPE_ID= ".$this->getField("TIPE_ID").",
					NAMA_JABATAN= '".$this->getField("NAMA_JABATAN")."',
					TIPE_JABATAN_ID= ".$this->getField("TIPE_JABATAN_ID").",
					ESELON_ID= ".$this->getField("ESELON_ID").",
					SATUAN_KERJA_INDUK= ".$this->getField("SATUAN_KERJA_INDUK").",
					SATUAN_KERJA_URUTAN_SURAT= ".$this->getField("SATUAN_KERJA_URUTAN_SURAT").",
					MASA_BERLAKU_AWAL= ".$this->getField("MASA_BERLAKU_AWAL").",
					MASA_BERLAKU_AKHIR= ".$this->getField("MASA_BERLAKU_AKHIR").",
					KONVERSI= '".$this->getField("KONVERSI")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					ID_SAPK= '".$this->getField("ID_SAPK")."'
				WHERE  SATUAN_KERJA_ID    	= ".$this->getField("SATUAN_KERJA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE SATUAN_KERJA
				SET    
					   STATUS   	= ".$this->getField("STATUS")."
				WHERE  SATUAN_KERJA_ID    	= ".$this->getField("SATUAN_KERJA_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE SATUAN_KERJA SET
					STATUS = '1'
				WHERE SATUAN_KERJA_ID = ".$this->getField("SATUAN_KERJA_ID")."
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
	function selectByParamsTree($statement="", $parentId="0", $sOrder= "ORDER BY SATUAN_KERJA_PARENT_ID, NO_URUT")
	{
		$str = "		
		WITH RECURSIVE nodes(SATUAN_KERJA_ID, SATUAN_KERJA_PARENT_ID, STATUS, NAMA, NO_URUT, NAMA_SINGKAT, PATH) AS (
			SELECT SATUAN_KERJA_ID, SATUAN_KERJA_PARENT_ID, STATUS, NAMA, NO_URUT, NAMA_SINGKAT
			, A.SATUAN_KERJA_ID AS PATH
			FROM SATUAN_KERJA A WHERE A.SATUAN_KERJA_PARENT_ID = ".$parentId."
			UNION
			SELECT B.SATUAN_KERJA_ID, B.SATUAN_KERJA_PARENT_ID, B.STATUS, B.NAMA, B.NO_URUT, B.NAMA_SINGKAT
			, A.SATUAN_KERJA_ID AS PATH
			FROM SATUAN_KERJA B, nodes A WHERE B.SATUAN_KERJA_PARENT_ID = A.SATUAN_KERJA_ID
		)
		SELECT SATUAN_KERJA_ID, SATUAN_KERJA_PARENT_ID, STATUS, NAMA, NO_URUT, NAMA_SINGKAT FROM nodes
		"; 
		$str .= $sOrder;
		//echo $str;exit;
		$this->query = $str;
				
		return $this->selectLimit($str,-1,-1);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SATUAN_KERJA_ID ASC')
	{
		$str = "
				SELECT A.SATUAN_KERJA_ID, A.SATUAN_KERJA_PARENT_ID, A.NAMA, A.NAMA_SINGKAT, A.TIPE_ID, A.NAMA_JABATAN, A.TIPE_JABATAN_ID, 
					   A.ESELON_ID, ES.NAMA ESELON_NAMA, A.SATUAN_KERJA_INDUK, A.SATUAN_KERJA_URUTAN_SURAT, A.MASA_BERLAKU_AWAL, 
					   A.MASA_BERLAKU_AKHIR, A.KONVERSI, A.ID_SAPK
					   , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
					   , AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_INDUK
				FROM SATUAN_KERJA A
				LEFT JOIN ESELON ES ON A.ESELON_ID = ES.ESELON_ID
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
	
	function selectByParamsData($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SATUAN_KERJA_ID ASC')
	{
		$str = "
				SELECT A.SATUAN_KERJA_ID, A.SATUAN_KERJA_PARENT_ID, A.NAMA, A.NAMA_SINGKAT, A.TIPE_ID, A.NAMA_JABATAN, A.TIPE_JABATAN_ID, 
					   A.ESELON_ID, ES.NAMA ESELON_NAMA, A.SATUAN_KERJA_INDUK, A.SATUAN_KERJA_URUTAN_SURAT, A.MASA_BERLAKU_AWAL, 
					   A.MASA_BERLAKU_AKHIR, A.KONVERSI, A.ID_SAPK
					   , AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
					   , AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_INDUK
					   , AMBIL_SATKER_NAMA(A.SATUAN_KERJA_URUTAN_SURAT) SATUAN_KERJA_URUTAN_SURAT_NAMA
					   , A.JENIS_JABATAN_ID, A.NAMA_PENANDATANGAN, A.KODE
					   , AMBIL_SATKER_NAMA(A.SATUAN_KERJA_INDUK_ID) SATUAN_KERJA_INDUK_NAMA, A.SATUAN_KERJA_INDUK_ID
					   , A.SATUAN_KERJA_MUTASI_STATUS
				FROM SATUAN_KERJA A
				LEFT JOIN ESELON ES ON A.ESELON_ID = ES.ESELON_ID
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
	
	function selectByParamsTreeMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.SATUAN_KERJA_ID ASC ')
	{
		$str = "
				SELECT 
					SATUAN_KERJA_ID, SATUAN_KERJA_PARENT_ID, NAMA, NAMA_SINGKAT, TIPE_ID, NAMA_JABATAN, TIPE_JABATAN_ID, 
					ESELON_ID, SATUAN_KERJA_INDUK, SATUAN_KERJA_URUTAN_SURAT, MASA_BERLAKU_AWAL
					, MASA_BERLAKU_AKHIR, KONVERSI, ID_SAPK
					, AMBIL_SATKER_NAMA_DYNAMIC(SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
					,
					CASE WHEN A.STATUS = '1' THEN 
					CONCAT('<a onClick=\"adddata(''',A.SATUAN_KERJA_ID,''',''insert'')\" style=\"cursor:pointer\" title=\"Tambah\"><img src=\"images/icon-tambah.png\" width=\"15px\" heigth=\"15px\"></a>-<a onClick=\"adddata(''',A.SATUAN_KERJA_ID,''',''update'')\", ''Aplikasi Data'', ''500'', ''200'')\" style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icon-edit.png\" width=\"15px\" heigth=\"15px\"></a>- <a onClick=\"hapusData(''',A.SATUAN_KERJA_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>') 
					ELSE
					CONCAT('<a onClick=\"adddata(''',A.SATUAN_KERJA_ID,''',''insert'')\" style=\"cursor:pointer\" title=\"Tambah\"><img src=\"images/icon-tambah.png\" width=\"15px\" heigth=\"15px\"></a>-<a onClick=\"adddata(''',A.SATUAN_KERJA_ID,''',''update'')\", ''Aplikasi Data'', ''500'', ''200'')\" style=\"cursor:pointer\" title=\"Ubah\"><img src=\"images/icon-edit.png\" width=\"15px\" heigth=\"15px\"></a>- <a onClick=\"hapusData(''',A.SATUAN_KERJA_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>') 
					END LINK_URL_INFO
				FROM SATUAN_KERJA A
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.SATUAN_KERJA_ID) AS ROWCOUNT 
				FROM SATUAN_KERJA A
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
	
	function getSatuanKerja($id='')
	{
		$str = "SELECT REPLACE(REPLACE(CAST(AMBIL_ID_SATUAN_KERJA_TREE_ARRAY(".$id.") AS TEXT), '{',''), '}','') ROWCOUNT";
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow())
		{
			if($this->getField("ROWCOUNT") == "")
			return $id;
			else
			return $id.",".$this->getField("ROWCOUNT"); 
		}
		else 
			return $id;  
    }

    function getSatuanKerjaTipe($id='', $tipeid="")
	{
		$str = "SELECT REPLACE(REPLACE(CAST(AMBIL_ID_SATUAN_KERJA_TREE_ARRAY_TIPE(".$id.", ".$tipeid.") AS TEXT), '{',''), '}','') ROWCOUNT";
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow())
		{
			if($this->getField("ROWCOUNT") == "")
			return $id;
			else
			return $id.",".$this->getField("ROWCOUNT"); 
		}
		else 
			return $id;  
    }

  } 
?>