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
  
  class Pegawai  extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Pegawai()
    {
    	$this->Entity(); 
    }
	
    function insert()
    {


    	$str = "INSERT INTO BKN.PEGAWAI (NIP_BARU, ID, IDPNS, NIPBARU, NIPLAMA, JENISJABATAN, INSTANSIKERJAID, INSTANSIKERJANAMA, SATUANKERJAID, SATUANKERJANAMA, UNORID, UNORNAMA, UNORINDUKID, UNORINDUKNAMA, ESELON, ESELONID, JABATANFUNGSIONALID, JABATANFUNGSIONALNAMA, JABATANFUNGSIONALUMUMID, JABATANFUNGSIONALUMUMNAMA, TMTJABATAN, NOMORSK, TANGGALSK, NAMAUNOR, NAMAJABATAN, TMTPELANTIKAN)VALUES (
    	'".$this->getField("NIP_BARU")."',
    	'".$this->getField("ID")."',
    	'".$this->getField("IDPNS")."',
    	'".$this->getField("NIPBARU")."',
    	'".$this->getField("NIPLAMA")."',
    	'".$this->getField("JENISJABATAN")."',
    	'".$this->getField("INSTANSIKERJAID")."',
    	'".$this->getField("INSTANSIKERJANAMA")."',
    	'".$this->getField("SATUANKERJAID")."',
    	'".$this->getField("SATUANKERJANAMA")."',
    	'".$this->getField("UNORID")."',
    	'".$this->getField("UNORNAMA")."',
    	'".$this->getField("UNORINDUKID")."',
    	'".$this->getField("UNORINDUKNAMA")."',
    	'".$this->getField("ESELON")."',
    	'".$this->getField("ESELONID")."',
    	'".$this->getField("JABATANFUNGSIONALID")."',
    	'".$this->getField("JABATANFUNGSIONALNAMA")."',
    	'".$this->getField("JABATANFUNGSIONALUMUMID")."',
    	'".$this->getField("JABATANFUNGSIONALUMUMNAMA")."',
    	".$this->getField("TMTJABATAN").",
    	'".$this->getField("NOMORSK")."',
    	'".$this->getField("TANGGALSK")."',
    	'".$this->getField("NAMAUNOR")."',
    	'".$this->getField("NAMAJABATAN")."',
    	".$this->getField("TMTPELANTIKAN")."
    	
    )";

  
    $this->query= $str;
		// echo $str;exit();
    return $this->execQuery($str);
  }


    
  } 
?>