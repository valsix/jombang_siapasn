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
include_once(APPPATH.'/models/Entity.php');

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

	function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.KODE')
	{
		$str = "
		SELECT 
			A.SATUAN_KERJA_ID, A.SATUAN_KERJA_PARENT_ID, A.NAMA, A.NAMA_SINGKAT, A.KODE, A.TIPE_ID
			, A.NAMA_JABATAN, A.TIPE_JABATAN_ID
			, A.ESELON_ID, ES.NAMA ESELON_NAMA, A.SATUAN_KERJA_INDUK, A.SATUAN_KERJA_URUTAN_SURAT
			, A.MASA_BERLAKU_AWAL, A.MASA_BERLAKU_AKHIR, A.KONVERSI, A.ID_SAPK
			, AMBIL_SATKER_NAMA_DYNAMIC(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_DETIL
			, AMBIL_SATKER_INDUK(A.SATUAN_KERJA_ID) SATUAN_KERJA_NAMA_INDUK
		FROM SATUAN_KERJA A
		LEFT JOIN ESELON ES ON A.ESELON_ID = ES.ESELON_ID
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
		SELECT COUNT(A.SATUAN_KERJA_ID) AS ROWCOUNT 
		FROM SATUAN_KERJA A
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