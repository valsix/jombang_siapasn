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
  
  class Pegawai extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function pegawai()
	{
      $this->Entity(); 
    }

	function insert()
	{
		
      
		$str = "
		INSERT INTO bkn.pegawai 
		(
			NIP_BARU, ID, IDPNS
		) 
		VALUES 
		(
			'".$this->getField("NIP_BARU")."',
    	'".$this->getField("ID")."',
    	'".$this->getField("IDPNS")."'
		)
		"; 

		
		$this->query = $str;
		return $this->execQuery($str);
    }

   

  } 
?>