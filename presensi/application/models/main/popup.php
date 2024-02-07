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

class Popup extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function Popup()
	{
	  $this->Entity(); 
	}

	function selectByParamsPopupPegawai($paramsArray=array(),$limit=-1,$from=-1, $satuankerjakondisi, $statement='', $order='')
	{
		$str = "
		SELECT *
			FROM PINFOAKHIR() XXX
			WHERE 1=1
			".$satuankerjakondisi.$statement
		; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from);
	}

	function getCountByParamsPopupPegawai($paramsArray=array(), $satuankerjakondisi, $statement='')
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
			FROM PINFOAKHIR() XXX
			WHERE 1=1
			".$satuankerjakondisi.$statement;
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		// echo $str;exit();
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0;  
	}

} 
?>