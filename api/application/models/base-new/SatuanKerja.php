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
  
  class SatuanKerja extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SatuanKerja()
	{
      $this->Entity(); 
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