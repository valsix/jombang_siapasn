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
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CUTI_ID", $this->getNextId("CUTI_ID","CUTI")); 

		$str = "
			INSERT INTO CUTI (
				CUTI_ID, PEGAWAI_ID, JENIS_CUTI, NO_SURAT, TANGGAL_PERMOHONAN, TANGGAL_SURAT, LAMA, TANGGAL_MULAI,TANGGAL_SELESAI, KETERANGAN, CUTI_KETERANGAN, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
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
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("CUTI_ID");
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE CUTI
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
				WHERE  CUTI_ID     	= '".$this->getField("CUTI_ID")."'
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE CUTI
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_LEVEL	= ".$this->getField("LAST_LEVEL").",
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  CUTI_ID    	= ".$this->getField("CUTI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE CUTI SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",	
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE CUTI_ID = ".$this->getField("CUTI_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
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
				SELECT A.CUTI_ID, A.PEGAWAI_ID, A.JENIS_CUTI, A.NO_SURAT, A.TANGGAL_PERMOHONAN, A.TANGGAL_SURAT, A.LAMA, A.TANGGAL_MULAI, A.TANGGAL_SELESAI, 
				A.KETERANGAN, A.CUTI_KETERANGAN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
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