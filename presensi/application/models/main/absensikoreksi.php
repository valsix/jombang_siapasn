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

class AbsensiKoreksi extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function AbsensiKoreksi()
	{
	  $this->Entity(); 
	}

	function getHariLibur($bulan, $tahun, $cabangId)
	{
		if($cabangId == "")
		$str = "SELECT presensi.AMBIL_HARI_LIBUR('".$bulan."', '".$tahun."', NULL) HARI_LIBUR  "; 
		else
		$str = "SELECT presensi.AMBIL_HARI_LIBUR('".$bulan."', '".$tahun."', '".$cabangId."') HARI_LIBUR  "; 
	
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("HARI_LIBUR"); 
		else 
			return ""; 
    }

} 
?>