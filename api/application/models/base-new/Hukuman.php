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
  
  class Hukuman extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Hukuman()
	{
      $this->Entity(); 
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.HUKUMAN_ID ASC')
	{
		$str = "
		SELECT 	
					A.HUKUMAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.PERATURAN_ID
					, A.TINGKAT_HUKUMAN_ID, B.NAMA TINGKAT_HUKUMAN_NAMA
					, A.JENIS_HUKUMAN_ID, C.NAMA JENIS_HUKUMAN_NAMA
					, A.NO_SK, A.TANGGAL_SK, A.TMT_SK, A.KETERANGAN, A.BERLAKU, A.TANGGAL_MULAI, A.TANGGAL_AKHIR, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL, A.STATUS,
					B.NAMA TINGKAT_HUKUMAN_NAMA, C.NAMA JENIS_HUKUMAN_NAMA, D.NAMA PEJABAT_PENETAP_NAMA,
					CASE A.BERLAKU WHEN '1' THEN 'Berlaku' ELSE 'Tidak Berlaku' END BERLAKU_NAMABAK,
					CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 'Berlaku' ELSE 'Tidak Berlaku' END BERLAKU_NAMA
					, A.TANGGAL_PEMULIHAN, A.TMT_BERIKUT_PANGKAT, A.TMT_BERIKUT_GAJI
					, CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 1 ELSE 0 END STATUS_BERLAKU
					, CASE WHEN CURRENT_DATE <= COALESCE(A.TANGGAL_AKHIR,CURRENT_DATE) AND CURRENT_DATE >= A.TANGGAL_MULAI THEN 'Ya' ELSE 'Tidak' END STATUS_BERLAKU_INFO
					, A.PANGKAT_RIWAYAT_TERAKHIR_ID, A.GAJI_RIWAYAT_TERAKHIR_ID
					, A.PANGKAT_RIWAYAT_TURUN_ID, A.GAJI_RIWAYAT_TURUN_ID, A.PANGKAT_RIWAYAT_KEMBALI_ID, A.GAJI_RIWAYAT_KEMBALI_ID, A.JABATAN_RIWAYAT_ID, A.PEGAWAI_STATUS_ID
				FROM HUKUMAN A
				LEFT JOIN TINGKAT_HUKUMAN B ON B.TINGKAT_HUKUMAN_ID = A.TINGKAT_HUKUMAN_ID
				LEFT JOIN JENIS_HUKUMAN C ON C.JENIS_HUKUMAN_ID = A.JENIS_HUKUMAN_ID
				LEFT JOIN PEJABAT_PENETAP D ON D.PEJABAT_PENETAP_ID = A.PEJABAT_PENETAP_ID
				WHERE 1 = 1
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	 
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "SELECT COUNT(A.HUKUMAN_ID) AS ROWCOUNT 
				FROM HUKUMAN A
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