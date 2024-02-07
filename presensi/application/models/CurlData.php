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

  class CurlData extends CI_Model{ 

	var $currentRow;
	var $errorMsg;
    var $rowCount;
    var $rowResult=array();
	var $currentRowIndex;
	var $urlcurl= "http://118.97.241.182:8180/";
    /**
    * Class constructor.
    **/
    function CurlData()
	{
      // $this->load->database(); 
    }
	
	public function getField($fieldName){
		$fieldName = strtolower($fieldName);
		
		return $this->currentRow[$fieldName];
	}

	function execQuery($url, $fields, $token){
		// $url= "departments/";
		$headers = array
		(
			'Authorization: ' . $token,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $this->urlcurl."personnel/api/".$url);
		// curl_setopt( $ch,CURLOPT_POST, true );
		// curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch);

		$res = json_decode($result);
		$result = json_decode(json_encode($res), true);

		curl_close( $ch );

		return $result;
    }
	
	function selectLimit($url, $token){

		// echo $url." ".$token;exit();
		$_rowResult = array();
		$this->rowResult = array();
		$this->rowCount = 0;
		
		$headers = array
		(
			'Authorization: ' . $token,
			'Content-Type: application/json'
		);

		$ch = curl_init();
		// $url = 'http://118.97.241.182:8180/'.$url;
		$url = $this->urlcurl.$url;
		// echo $url;exit();
		curl_setopt_array($ch, array(
		  CURLOPT_URL => $url,
		  CURLOPT_HTTPHEADER=> $headers,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_TIMEOUT => 500,
		));

		$response = curl_exec($ch);
		$rs = json_decode($response);
		curl_close($ch);
		// print_r($rs);exit();

		$rsdata= $rs->data;

		$i=0;
		if ($rs) {
			$i=0;
			foreach ($rsdata as $row) 
			{
				foreach($row as $key=>$val)
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