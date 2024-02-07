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
  
  class PenilaianSkpDetil  extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function PenilaianSkpDetil()
		{
      $this->Entity(); 
    }
	
	
		    function insert()
		    {
		    	$this->setField("PENILAIAN_SKP_DETIL_ID", $this->getNextId("PENILAIAN_SKP_DETIL_ID","PENILAIAN_SKP_DETIL")); 

		    	$str = "INSERT INTO PENILAIAN_SKP_DETIL (PENILAIAN_SKP_DETIL_ID, TAHUN, TRIWULAN, PEGAWAI_ID, PEGAWAI_UNOR_ID,        PEGAWAI_UNOR_NAMA, JENIS_JABATAN_DINILAI, PEGAWAI_PEJABAT_PENILAI_ID,        PEGAWAI_PEJABAT_PENILAI_NIP, PEGAWAI_PEJABAT_PENILAI_NAMA, PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,        PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA, PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID,        PEGAWAI_ATASAN_PEJABAT_ID, PEGAWAI_ATASAN_PEJABAT_NIP, PEGAWAI_ATASAN_PEJABAT_NAMA,        PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA, PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,        PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID, NILAI_HASIL_KERJA, NILAI_HASIL_PERILAKU,        NILAI_QUADRAN, NILAI_CAPAIAN_ORGANISASI, LAST_USER, LAST_DATE,        LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID)VALUES (
		    	".$this->getField("PENILAIAN_SKP_DETIL_ID").",
		    	".$this->getField("TAHUN").",
		    	".$this->getField("TRIWULAN").",
		    	".$this->getField("PEGAWAI_ID").",
		    	".$this->getField("PEGAWAI_UNOR_ID").",
		    	'".$this->getField("PEGAWAI_UNOR_NAMA")."',
		    	'".$this->getField("JENIS_JABATAN_DINILAI")."',
		    	".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
		    	'".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP")."',
		    	'".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA")."',
		    	'".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA")."',
		    	'".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA")."',
		    	".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID").",
		    	".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
		    	'".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP")."',
		    	'".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA")."',
		    	'".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA")."',
		    	'".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA")."',
		    	".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
		    	".$this->getField("NILAI_HASIL_KERJA").",
		    	".$this->getField("NILAI_HASIL_PERILAKU").",
		    	".$this->getField("NILAI_QUADRAN").",
		    	".$this->getField("NILAI_CAPAIAN_ORGANISASI").",
		    	'".$this->getField("LAST_USER")."',
		    	".$this->getField("LAST_DATE").",
		    	'".$this->getField("LAST_LEVEL")."',
		    	'".$this->getField("USER_LOGIN_ID")."',
		    	".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		    
		    
		    )";

		    $this->id = $this->getField("PENILAIAN_SKP_DETIL_ID");
		    $this->query= $str;
				// echo $str;exit();
		    return $this->execQuery($str);
		  }

		  function update()
		  {
		  	$str = "
		  	UPDATE PENILAIAN_SKP_DETIL
		  	SET    
		  	PENILAIAN_SKP_DETIL_ID =".$this->getField("PENILAIAN_SKP_DETIL_ID").",
		  	TAHUN =".$this->getField("TAHUN").",
		  	TRIWULAN =".$this->getField("TRIWULAN").",
		  	PEGAWAI_ID =".$this->getField("PEGAWAI_ID").",
		  	PEGAWAI_UNOR_ID =".$this->getField("PEGAWAI_UNOR_ID").",
		  	PEGAWAI_UNOR_NAMA ='".$this->getField("PEGAWAI_UNOR_NAMA")."',
		  	JENIS_JABATAN_DINILAI ='".$this->getField("JENIS_JABATAN_DINILAI")."',
		  	PEGAWAI_PEJABAT_PENILAI_ID =".$this->getField("PEGAWAI_PEJABAT_PENILAI_ID").",
		  	PEGAWAI_PEJABAT_PENILAI_NIP ='".$this->getField("PEGAWAI_PEJABAT_PENILAI_NIP")."',
		  	PEGAWAI_PEJABAT_PENILAI_NAMA ='".$this->getField("PEGAWAI_PEJABAT_PENILAI_NAMA")."',
		  	PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA ='".$this->getField("PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA")."',
		  	PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA ='".$this->getField("PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA")."',
		  	PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID =".$this->getField("PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID").",
		  	PEGAWAI_ATASAN_PEJABAT_ID =".$this->getField("PEGAWAI_ATASAN_PEJABAT_ID").",
		  	PEGAWAI_ATASAN_PEJABAT_NIP ='".$this->getField("PEGAWAI_ATASAN_PEJABAT_NIP")."',
		  	PEGAWAI_ATASAN_PEJABAT_NAMA ='".$this->getField("PEGAWAI_ATASAN_PEJABAT_NAMA")."',
		  	PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA ='".$this->getField("PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA")."',
		  	PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA ='".$this->getField("PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA")."',
		  	PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID =".$this->getField("PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID").",
		  	NILAI_HASIL_KERJA =".$this->getField("NILAI_HASIL_KERJA").",
		  	NILAI_HASIL_PERILAKU =".$this->getField("NILAI_HASIL_PERILAKU").",
		  	NILAI_QUADRAN =".$this->getField("NILAI_QUADRAN").",
		  	NILAI_CAPAIAN_ORGANISASI =".$this->getField("NILAI_CAPAIAN_ORGANISASI").",
		  	LAST_USER ='".$this->getField("LAST_USER")."',
		  	LAST_DATE =".$this->getField("LAST_DATE").",
		  	LAST_LEVEL ='".$this->getField("LAST_LEVEL")."',
		  	USER_LOGIN_ID ='".$this->getField("USER_LOGIN_ID")."',
		  	USER_LOGIN_PEGAWAI_ID =".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		  
		  	
		  	WHERE PENILAIAN_SKP_DETIL_ID= '".$this->getField("PENILAIAN_SKP_DETIL_ID")."'";
		  	$this->query = $str;
				  // echo  $str;exit;
		  	return $this->execQuery($str);
		  }
		  function deleteBak($statement= "")
		  {
		  	$str = "DELETE FROM PENILAIAN_SKP_DETIL
		  	WHERE PENILAIAN_SKP_DETIL_ID= ".$this->getField("PENILAIAN_SKP_DETIL_ID").""; 
		  	$this->query = $str;
				  // echo $str;exit();
		  	return $this->execQuery($str);
		  }
			  function updateStatus()
			  {
			  	$str = "		
			  	UPDATE PENILAIAN_SKP_DETIL
			  	SET    
			  	STATUS= ".$this->getField("STATUS")."
			  	, LAST_USER= '".$this->getField("LAST_USER")."'
			  	, LAST_LEVEL= ".$this->getField("LAST_LEVEL")."
			  	, LAST_DATE= ".$this->getField("LAST_DATE")."
			  	, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			  	 	, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
			  	WHERE PENILAIAN_SKP_DETIL_ID= ".$this->getField("PENILAIAN_SKP_DETIL_ID")."
			  	"; 
			  	$this->query = $str;
			  	// echo  $str;exit;
			  	return $this->execQuery($str);
    }
		  function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.PENILAIAN_SKP_DETIL_ID ASC")
		  {
		  	$str = "
		  	SELECT A.PENILAIAN_SKP_DETIL_ID,A.TAHUN,A.TRIWULAN,A.PEGAWAI_ID,A.PEGAWAI_UNOR_ID,A.PEGAWAI_UNOR_NAMA,A.JENIS_JABATAN_DINILAI,A.PEGAWAI_PEJABAT_PENILAI_ID,A.PEGAWAI_PEJABAT_PENILAI_NIP,A.PEGAWAI_PEJABAT_PENILAI_NAMA,A.PEGAWAI_PEJABAT_PENILAI_JABATAN_NAMA,A.PEGAWAI_PEJABAT_PENILAI_UNOR_NAMA,A.PEGAWAI_PEJABAT_PENILAI_PANGKAT_ID,A.PEGAWAI_ATASAN_PEJABAT_ID,A.PEGAWAI_ATASAN_PEJABAT_NIP,A.PEGAWAI_ATASAN_PEJABAT_NAMA,A.PEGAWAI_ATASAN_PEJABAT_JABATAN_NAMA,A.PEGAWAI_ATASAN_PEJABAT_UNOR_NAMA,A.PEGAWAI_ATASAN_PEJABAT_PANGKAT_ID,A.NILAI_HASIL_KERJA,A.NILAI_HASIL_PERILAKU,A.NILAI_QUADRAN,A.NILAI_CAPAIAN_ORGANISASI,A.LAST_USER,A.LAST_DATE,A.LAST_LEVEL,A.USER_LOGIN_ID,A.USER_LOGIN_PEGAWAI_ID,A.LAST_CREATE_USER,A.LAST_CREATE_DATE,A.STATUS
		  	, C.PEGAWAI_ID_SAPK,
		  	CASE 
		  	WHEN A.TRIWULAN='1' THEN 'I'
		  	WHEN A.TRIWULAN='2' THEN 'II'
		  	WHEN A.TRIWULAN='3' THEN 'III'
		  	WHEN A.TRIWULAN='4' THEN 'IV'
		  	WHEN A.TRIWULAN='99' THEN 'Final'
		  	ELSE ''
		  	END  NAMA_TRIWULAN
		  	FROM PENILAIAN_SKP_DETIL A
		  			LEFT JOIN PEGAWAI C ON C.PEGAWAI_ID = A.PEGAWAI_ID
		  	WHERE 1=1 ";
		  	while(list($key,$val) = each($paramsArray))
		  	{
		  		$str .= " AND $key = '$val'";
		  	}

		  	$str .= $statement." ".$order;
		  	$this->query = $str;
		  	return $this->selectLimit($str,$limit,$from); 
		  }

		  function getCountByParams($paramsArray=array(), $statement="")
		  {
		  	$str = "SELECT COUNT(1) AS ROWCOUNT FROM PENILAIAN_SKP_DETIL A WHERE 1=1 ".$statement;
		  	while(list($key,$val)=each($paramsArray))
		  	{
		  		$str .= " AND $key = 	'$val' ";
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