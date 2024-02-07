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
  
  class Cuti extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Cuti()
	{
      $this->Entity(); 
    }
	


    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TANGGAL_SURAT ASC')
	{
		$str = "
		SELECT
		A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT, A.TANGGAL_PERMOHONAN, A.TANGGAL_SURAT, A.TANGGAL_MULAI
		, A.TANGGAL_SELESAI, A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
		, TO_CHAR(A.TANGGAL_MULAI,'YYYY') TAHUN_MULAI
		, CASE WHEN LAMA IS NULL THEN NULLIF(REGEXP_REPLACE(A.CUTI_KETERANGAN, '[^0-9.]*','','g'), '')::NUMERIC ELSE A.LAMA END LAMA
		, CASE A.JENIS_CUTI 
		WHEN 1 THEN 'Cuti Tahunan'
		WHEN 2 THEN 'Cuti Besar'
		WHEN 3 THEN 'Cuti Sakit'
		WHEN 4 THEN 'Cuti Bersalin'
		WHEN 5 THEN 'Cuti Alasan Penting'
		WHEN 6 THEN 'Cuti Bersama'
		WHEN 7 THEN 'CLTN'
		ELSE '-' END JENIS_CUTI_NAMA
		FROM CUTI A
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
				SELECT COUNT(A.CUTI_ID) AS ROWCOUNT 
				FROM CUTI A
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