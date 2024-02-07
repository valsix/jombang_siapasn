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
  
  class SuamiIstri extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function SuamiIstri()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.TANGGAL_KAWIN ASC')
	{
		$str = "
		SELECT 	
		A.SUAMI_ISTRI_ID, A.PEGAWAI_ID, A.PENDIDIKAN_ID, A.NAMA, A.TEMPAT_LAHIR, A.TANGGAL_LAHIR, A.TANGGAL_KAWIN, A.KARTU, 
		A.STATUS_PNS, A.NIP_PNS, A.PEKERJAAN, A.STATUS_TUNJANGAN, A.STATUS_BAYAR, A.BULAN_BAYAR, A.STATUS, A.STATUS_S_I, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,
		CASE A.STATUS_S_I WHEN '1' THEN 'Nikah' WHEN '2' THEN 'Cerai Hidup' WHEN '3' THEN 'Cerai Mati' ELSE 'Belum di set' END STATUS_S_I_NAMA
		, A.SURAT_NIKAH, A.NIK, A.CERAI_SURAT, A.CERAI_TANGGAL, A.CERAI_TMT, A.KEMATIAN_SURAT, A.KEMATIAN_TANGGAL, A.KEMATIAN_TMT,B.NAMA PENDIDIKAN_NAMA
		FROM SUAMI_ISTRI A
		LEFT JOIN PENDIDIKAN B ON B.PENDIDIKAN_ID = A.PENDIDIKAN_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
		$str .= $statement."  ".$order;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.SUAMI_ISTRI_ID) AS ROWCOUNT 
				FROM SUAMI_ISTRI A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }

  } 
?>