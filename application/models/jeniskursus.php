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
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');
  
  class JenisKursus extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JenisKursus()
	{
      $this->Entity(); 
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.REF_JENIS_KURSUS_ID ASC')
    {
    	$str = "
    	SELECT 
            A.*, R.KETERANGAN RUMPUN_NAMA
        FROM sapk.ref_jenis_kursus A
        LEFT JOIN talent.rumpun R ON A.RUMPUN_ID = R.RUMPUN_ID
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
    	SELECT COUNT(A.REF_JENIS_KURSUS_ID) AS ROWCOUNT 
    	FROM  sapk.ref_jenis_kursus A
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

  } 
?>