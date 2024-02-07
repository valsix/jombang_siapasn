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
  
  class Cuti extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function Cuti()
	{
      $this->Entity(); 
    }

    function insert()
	{
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.CUTI")); 

		$str = "
		INSERT INTO validasi.CUTI 
		(
			CUTI_ID, PEGAWAI_ID, JENIS_CUTI
			, NO_SURAT, TANGGAL_PERMOHONAN, TANGGAL_SURAT, LAMA, TANGGAL_MULAI,TANGGAL_SELESAI, KETERANGAN, CUTI_KETERANGAN
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
		) 
		VALUES (
				".$this->getField("CUTI_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("JENIS_CUTI").",
				'".$this->getField("NO_SURAT")."',
				".$this->getField("TANGGAL_PERMOHONAN").",
				".$this->getField("TANGGAL_SURAT").",
				".$this->getField("LAMA").",
				".$this->getField("TANGGAL_MULAI").",
				".$this->getField("TANGGAL_SELESAI").",
				'".$this->getField("KETERANGAN")."',
				'".$this->getField("CUTI_KETERANGAN")."',
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("LAST_LEVEL").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID").",
				".$this->getField("TEMP_VALIDASI_ID")."
		)
		"; 	
		$this->id = $this->getField("TEMP_VALIDASI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}


	function update()
	{
		$str = "		
		UPDATE validasi.CUTI
		SET  
			PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
			JENIS_CUTI= ".$this->getField("JENIS_CUTI").",
			NO_SURAT= '".$this->getField("NO_SURAT")."',
			TANGGAL_PERMOHONAN= ".$this->getField("TANGGAL_PERMOHONAN").",
			TANGGAL_SURAT= ".$this->getField("TANGGAL_SURAT").",
			LAMA= ".$this->getField("LAMA").",
			TANGGAL_MULAI= ".$this->getField("TANGGAL_MULAI").",
			TANGGAL_SELESAI= ".$this->getField("TANGGAL_SELESAI").",
			KETERANGAN= '".$this->getField("KETERANGAN")."',
			CUTI_KETERANGAN= '".$this->getField("CUTI_KETERANGAN")."',
			LAST_USER= '".$this->getField("LAST_USER")."',
			LAST_DATE= ".$this->getField("LAST_DATE").",
			LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
			USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
			USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
		WHERE  TEMP_VALIDASI_ID     	= '".$this->getField("TEMP_VALIDASI_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
	}
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='')
	{
		$str = "
		SELECT A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT, A.TANGGAL_PERMOHONAN, A.TANGGAL_SURAT, A.LAMA, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
		A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL,B.NIP_BARU,B.NAMA
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
		LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
	
			
		$str .= $statement."  ".$order;
			$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='')
	{
		$str = "
		SELECT A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT, A.TANGGAL_PERMOHONAN, A.TANGGAL_SURAT, A.LAMA, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
		A.KETERANGAN, A.CUTI_KETERANGAN
		, CASE A.JENIS_CUTI 
		WHEN 1 THEN 'Cuti Tahunan'
		WHEN 2 THEN 'Cuti Besar'
		WHEN 3 THEN 'Cuti Sakit'
		WHEN 4 THEN 'Cuti Bersalin'
		WHEN 5 THEN 'Cuti Alasan Penting'
		WHEN 6 THEN 'Cuti Bersama'
		WHEN 7 THEN 'CLTN'
		ELSE '-' END JENIS_CUTI_NAMA
		, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
		FROM (select * from validasi.validasi_pegawai_cuti('".$pegawaiid."', '".$id."', '".$rowid."')) A
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
		$str = "SELECT COUNT(A.CUTI_ID) AS ROWCOUNT 
				FROM CUTI A
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