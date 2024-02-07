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
  
  class JabatanTambahan extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function JabatanTambahan()
	{
      $this->Entity(); 
    }

    function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("TEMP_VALIDASI_ID", $this->getNextId("TEMP_VALIDASI_ID","validasi.JABATAN_TAMBAHAN")); 

     	$str = "
			INSERT INTO validasi.JABATAN_TAMBAHAN (
			JABATAN_TAMBAHAN_ID, PEGAWAI_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, NO_SK, TANGGAL_SK
			, TMT_JABATAN, TMT_JABATAN_AKHIR, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, BULAN_DIBAYAR
			, NAMA, TUGAS_TAMBAHAN_ID, IS_MANUAL, SATKER_NAMA, SATKER_ID, STATUS_PLT
			, LAST_USER, LAST_DATE, LAST_LEVEL, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID, TEMP_VALIDASI_ID
			)
			VALUES (
				".$this->getField("JABATAN_TAMBAHAN_ID").",
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("PEJABAT_PENETAP_ID").",
				'".$this->getField("PEJABAT_PENETAP")."',
				'".$this->getField("NO_SK")."',
				".$this->getField("TANGGAL_SK").",
				".$this->getField("TMT_JABATAN").",
				".$this->getField("TMT_JABATAN_AKHIR").",
				'".$this->getField("NO_PELANTIKAN")."',
				".$this->getField("TANGGAL_PELANTIKAN").",
				".$this->getField("TUNJANGAN").",
				".$this->getField("BULAN_DIBAYAR").",
				'".$this->getField("NAMA")."',
				".$this->getField("TUGAS_TAMBAHAN_ID").",
				".$this->getField("IS_MANUAL").",
				'".$this->getField("SATKER_NAMA")."',
				".$this->getField("SATKER_ID").",
				'".$this->getField("STATUS_PLT")."',
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
		//echo $str;exit;
		return $this->execQuery($str);
    }


    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE validasi.JABATAN_TAMBAHAN
				SET    
					PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
					PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
					NO_SK= '".$this->getField("NO_SK")."',
					TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
					TMT_JABATAN= ".$this->getField("TMT_JABATAN").",
					TMT_JABATAN_AKHIR= ".$this->getField("TMT_JABATAN_AKHIR").",
					NO_PELANTIKAN= '".$this->getField("NO_PELANTIKAN")."',
					TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN").",
					TUNJANGAN= ".$this->getField("TUNJANGAN").",
					BULAN_DIBAYAR= ".$this->getField("BULAN_DIBAYAR").",
					NAMA= '".$this->getField("NAMA")."',
					TUGAS_TAMBAHAN_ID= ".$this->getField("TUGAS_TAMBAHAN_ID").",
					IS_MANUAL= ".$this->getField("IS_MANUAL").",
					SATKER_NAMA= '".$this->getField("SATKER_NAMA")."',
					SATKER_ID= ".$this->getField("SATKER_ID").",
					STATUS_PLT= '".$this->getField("STATUS_PLT")."',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  TEMP_VALIDASI_ID = ".$this->getField("TEMP_VALIDASI_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='', $order='ORDER BY A.JABATAN_TAMBAHAN_ID ASC')
	{
		$str = "
		SELECT
				A.JABATAN_TAMBAHAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NO_SK, A.TANGGAL_SK
				, A.TMT_JABATAN, A.TMT_JABATAN_AKHIR, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.BULAN_DIBAYAR
				, A.NAMA, A.TUGAS_TAMBAHAN_ID, A.IS_MANUAL
				, CASE WHEN COALESCE(NULLIF(A.SATKER_NAMA, ''), NULL) IS NULL THEN AMBIL_SATKER_NAMA(A.SATKER_ID) ELSE A.SATKER_NAMA END SATKER_NAMA, A.SATKER_ID, A.STATUS_PLT, A.STATUS,
				A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
			FROM JABATAN_TAMBAHAN A
			WHERE 1 = 1 AND (COALESCE(NULLIF(A.STATUS, ''), NULL) IS NULL OR A.STATUS = '2')
		"; 

		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$this->query = $str;
			
		$str .= $statement."  ".$order;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByPersonal($paramsArray=array(),$limit=-1,$from=-1, $pegawaiid, $id="", $rowid="", $statement='', $order='ORDER BY A.JABATAN_TAMBAHAN_ID ASC')
	{
		$str = "
		SELECT
				A.JABATAN_TAMBAHAN_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.NO_SK, A.TANGGAL_SK
				, A.TMT_JABATAN, A.TMT_JABATAN_AKHIR, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.BULAN_DIBAYAR
				, A.NAMA, A.TUGAS_TAMBAHAN_ID, A.IS_MANUAL
				, CASE WHEN COALESCE(NULLIF(A.SATKER_NAMA, ''), NULL) IS NULL THEN AMBIL_SATKER_NAMA(A.SATKER_ID) ELSE A.SATKER_NAMA END SATKER_NAMA, A.SATKER_ID, A.STATUS_PLT, TEMP_VALIDASI_ID, TEMP_VALIDASI_HAPUS_ID, VALIDASI, VALIDATOR, PERUBAHAN_DATA, TIPE_PERUBAHAN_DATA, TANGGAL_VALIDASI
			FROM (select * from validasi.validasi_pegawai_jabatan_tambahan('".$pegawaiid."', '".$id."', '".$rowid."')) A
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
		$str = "SELECT COUNT(A.JABATAN_TAMBAHAN_ID) AS ROWCOUNT 
				FROM JABATAN_TAMBAHAN A
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