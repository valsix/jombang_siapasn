<? 
  require_once('nu/nusoap.php');
  class SoapData extends CI_Model{ 

	var $currentRow;
	var $errorMsg;
    var $rowCount;
    var $rowResult=array();
	var $currentRowIndex;
    /**
    * Class constructor.
    **/
    function SoapData()
	{
      // $this->load->database(); 
    }
	
	public function getField($fieldName){
		$fieldName = strtolower($fieldName);
		
		return $this->currentRow[$fieldName];
	}
	
	function selectLimit($file, $method, $call, $detilinfo=""){

		$_rowResult = array();
		$this->rowResult = array();
		$this->rowCount = 0;


		$client = new nusoap_client("http://103.215.24.91/ws/".$file.".php?id=bZeWaw%3D%3D&method=".$method.$detilinfo);
		$result= $client->call($call);

		$i=0;
        if (is_array($result))
        {
            foreach($result as $data)
            {
            	foreach($data as $key=>$val)
				{
					$arr[strtolower($key)] = $val;
				}

				$_rowResult[$i]= $arr;
				$i = $i+1;
            }
        }

		$this->rowResult = $_rowResult;
		$this->rowCount = $i;
		
		$this->currentRowIndex = -1;
		$this->currentRow = array();
    }

    function jumlahRow(){
		return $this->rowCount;
	}

	function nextRow(){
		if($this->currentRowIndex < $this->rowCount-1){
			$this->currentRowIndex++;
			$this->setRowValue();
			return true;
		} else {
			return false;
    	}
	}
	
	function firstRow(){
		// print_r($this->rowCount);exit();
      	if($this->rowCount>0){
			$this->currentRowIndex=0;
			$this->setRowValue();
			return true;
      	} else {
        	return false;
    	}
	}
	
	function setRowValue(){
		$this->currentRow = $this->rowResult[$this->currentRowIndex];
	}	
	
  } 
?>