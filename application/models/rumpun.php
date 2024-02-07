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
  
  class Rumpun extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Rumpun()
	{
      $this->Entity(); 
    }

	function insert()
	{
		$this->setField("RUMPUN_ID", $this->getNextId("RUMPUN_ID","talent.rumpun")); 
      
		$str = "
		INSERT INTO talent.rumpun 
		(
			RUMPUN_ID, PERMEN_ID, KODE, NAMA, KETERANGAN
		) 
		VALUES 
		(
			".$this->getField("RUMPUN_ID")."
			, ".$this->getField("PERMEN_ID")."
			, '".$this->getField("KODE")."'
			, '".$this->getField("NAMA")."'
			, '".$this->getField("KETERANGAN")."'
		)
		"; 

		/*, '".$this->getField("LAST_USER")."'
		, ".$this->getField("LAST_DATE")."
		, ".$this->getField("USER_LOGIN_ID")."
		, ".$this->getField("USER_LOGIN_PEGAWAI_ID")."*/

		$this->id = $this->getField("RUMPUN_ID");
		$this->query = $str;
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "		
		UPDATE talent.rumpun
		SET    
		KODE= ".$this->getField("KODE")."
		, NAMA= '".$this->getField("NAMA")."'
		, KETERANGAN= '".$this->getField("KETERANGAN")."'
		WHERE RUMPUN_ID = ".$this->getField("RUMPUN_ID")."
		";

		/*, LAST_USER= '".$this->getField("LAST_USER")."'
		, LAST_DATE= ".$this->getField("LAST_DATE")."
		, USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID")."
		, USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."*/
		$this->query = $str;
		return $this->execQuery($str);
    }

    function delete()
    {
    	$str = "
    	UPDATE talent.rumpun SET
    	KETERANGAN = '1',
    	LAST_USER= '".$this->getField("LAST_USER")."',
    	LAST_DATE= ".$this->getField("LAST_DATE").",
    	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
    	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
    	WHERE RUMPUN_ID = ".$this->getField("RUMPUN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.RUMPUN_ID ASC ')
	{
		$str = "
				SELECT
				A.*
				FROM talent.rumpun A
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
				SELECT COUNT(A.RUMPUN_ID) AS ROWCOUNT 
				FROM talent.rumpun A
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

    function selectrumpunnilai($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT A.*
		FROM talent.rumpun_nilai A
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectskdasarjabatan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.SK_DASAR_JABATAN_ID')
	{
		$str = "
		SELECT A.*
		FROM sk_dasar_jabatan A
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectskdasarpengakuan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.SK_DASAR_PENGAKUAN_ID')
	{
		$str = "
		SELECT A.*
		FROM sk_dasar_pengakuan A
		WHERE 1 = 1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectbidangterkait($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.NAMA')
	{
		$str = "
		SELECT
			A1.KETERANGAN RUMPUN_NAMA
			, A.*
		FROM talent.bidang_terkait A
		INNER JOIN talent.rumpun A1 ON A.RUMPUN_ID = A1.RUMPUN_ID
		WHERE 1=1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectbidangjabatanterkait($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='ORDER BY A.NAMA')
	{
		$str = "
		SELECT
			A1.KETERANGAN RUMPUN_NAMA
			, A.*
		FROM talent.bidang_jabatan_terkait A
		INNER JOIN talent.rumpun A1 ON A.RUMPUN_ID = A1.RUMPUN_ID
		WHERE 1=1
		";
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		// echo $str; exit;
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

  } 
?>