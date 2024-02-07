<? 
include_once(APPPATH.'/models/Entity.php');

class InfoData extends Entity{ 

	var $query;
	var $id;

    function InfoData()
	{
      $this->Entity(); 
    }
	
    function selectdata($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT A.*
		FROM AGAMA A
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

    function getnip($id)
	{
		$str = "
		SELECT A.NIP_BARU AS ROWCOUNT 
		FROM PEGAWAI A
		WHERE 1 = 1 AND A.PEGAWAI_ID = ".$id;

		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return "none";
    }

  } 
?>