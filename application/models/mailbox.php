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
  
  class Mailbox extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Mailbox()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("MAILBOX_ID", $this->getNextId("MAILBOX_ID","MAILBOX")); 

		$str = "
			INSERT INTO MAILBOX (
				MAILBOX_ID, MAILBOX_KATEGORI_ID, USER_LOGIN_ID, SATUAN_KERJA_TUJUAN_ID, SATUAN_KERJA_ASAL_ID, TANGGAL, ISI, SUBYEK, PEGAWAI_ID
				, TIPE
				, LAST_USER, LAST_DATE, USER_LOGIN_PEGAWAI_ID
			) 
			VALUES (
				".$this->getField("MAILBOX_ID").",
				".$this->getField("MAILBOX_KATEGORI_ID").",
				".$this->getField("USER_LOGIN_ID").",
				".$this->getField("SATUAN_KERJA_TUJUAN_ID").",
				".$this->getField("SATUAN_KERJA_ASAL_ID").",
				".$this->getField("TANGGAL").",
				'".$this->getField("ISI")."',
				'".$this->getField("SUBYEK")."',
				".$this->getField("PEGAWAI_ID").",
				".$this->getField("TIPE").",
				'".$this->getField("LAST_USER")."',
				".$this->getField("LAST_DATE").",
				".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("MAILBOX_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
    function updateByField()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE MAILBOX A SET
				".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE MAILBOX_ID	  = ".$this->getField("MAILBOX_ID")."
				"; 
		$this->query = $str;
		return  $this->execQuery($str);
    }

    function updateByFieldIsNull()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE MAILBOX A SET
				".$this->getField("FIELD")." = '".$this->getField("FIELD_VALUE")."'
				WHERE MAILBOX_ID = ".$this->getField("MAILBOX_ID")." AND STATUS IS NULL
				"; 
		$this->query = $str;
		return  $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE MAILBOX SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					USER_LOGIN_ID= '".$this->getField("USER_LOGIN_ID")."',
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID").",
					LAST_DATE= ".$this->getField("LAST_DATE")."
				WHERE MAILBOX_ID = ".$this->getField("MAILBOX_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY A.TANGGAL DESC")
	{
		$str = "SELECT 
				A.MAILBOX_ID, A.PEGAWAI_ID, A.MAILBOX_KATEGORI_ID, A.TANGGAL, A.SUBYEK, COALESCE(C.ISI, A.ISI) ISI, A.STATUS, A.TANGGAL TANGGAL_UPDATE,
				CASE 
				WHEN A.STATUS = 0 OR A.STATUS IS NULL THEN 'baru(belum dibuka)'
				WHEN A.STATUS = 1 AND C.STATUS IS NULL THEN 'sudah terbaca(belum balas)'
				WHEN A.STATUS = 1 AND C.STATUS = 1 THEN 'dibalas(dibalas admin)'
				WHEN C.STATUS = 2 THEN 'user respon(user membalas)'
				ELSE ''
				END STATUS_NAMA, 
				CASE 
				WHEN A.STATUS = 0 OR A.STATUS IS NULL THEN '1'
				WHEN A.STATUS = 1 AND C.STATUS IS NULL THEN '2'
				WHEN A.STATUS = 1 AND C.STATUS = 1 THEN '3'
				WHEN C.STATUS = 2 THEN '4'
				ELSE '5'
				END STATUS_INFO_ID, 
				B.NAMA PEGAWAI_NAMA, D.NAMA MAILBOX_KATEGORI_NAMA
				, CASE WHEN D.JENIS_PELAYANAN_ID IS NULL THEN 'Usul Umum' ELSE E.NAMA END JENIS_PELAYANAN_NAMA
				FROM MAILBOX A 
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				LEFT JOIN
				(
				  SELECT A.MAILBOX_DETIL_ID, A.MAILBOX_ID, A.STATUS, A.TANGGAL, ISI
				  FROM MAILBOX_DETIL A 
				  INNER JOIN 
				  ( 
					SELECT MAX(A.MAILBOX_DETIL_ID) MAILBOX_DETIL_ID, A.MAILBOX_ID 
					FROM MAILBOX_DETIL A WHERE 1=1 GROUP BY A.MAILBOX_ID 
				  ) B ON A.MAILBOX_DETIL_ID = B.MAILBOX_DETIL_ID WHERE 1=1 
				) C ON C.MAILBOX_ID = A.MAILBOX_ID
				LEFT JOIN MAILBOX_KATEGORI D ON A.MAILBOX_KATEGORI_ID = D.MAILBOX_KATEGORI_ID
				LEFT JOIN persuratan.JENIS_PELAYANAN E ON E.JENIS_PELAYANAN_ID = D.JENIS_PELAYANAN_ID
				WHERE 1=1  "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function selectByParamsDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY B.MAILBOX_DETIL_ID DESC")
	{
		$str = "SELECT A.SUBYEK, A.ISI, B.ISI ISI_DETIL, B.MAILBOX_DETIL_ID, B.USER_LOGIN_ID,
				A.TANGGAL, B.TANGGAL TANGGAL_DETIL
				, P1.NAMA PEGAWAI_NAMA1, COALESCE(P2.NAMA, UL.LOGIN_USER) PEGAWAI_NAMA2, B.SATUAN_KERJA_JAWAB_ID SATUAN_KERJA_ID, B.TIPE
				, A.SATUAN_KERJA_ASAL_ID, A.SATUAN_KERJA_TUJUAN_ID
				FROM MAILBOX A
				LEFT JOIN MAILBOX_DETIL B ON A.MAILBOX_ID = B.MAILBOX_ID
				LEFT JOIN PEGAWAI P1 ON A.PEGAWAI_ID = P1.PEGAWAI_ID
				LEFT JOIN PEGAWAI P2 ON B.PEGAWAI_ID = P2.PEGAWAI_ID
				LEFT JOIN USER_LOGIN UL ON B.USER_LOGIN_ID = UL.USER_LOGIN_ID
				WHERE 1=1
				"; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsTerakhirDetil($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY B.MAILBOX_DETIL_ID DESC")
	{
		$str = "
		SELECT
			A.MAILBOX_ID, A.SUBYEK, A.ISI, B.ISI ISI_DETIL, B.MAILBOX_DETIL_ID, B.USER_LOGIN_ID,
			A.TANGGAL, B.TANGGAL TANGGAL_DETIL
			, P1.NAMA PEGAWAI_NAMA1, COALESCE(P2.NAMA, UL.LOGIN_USER) PEGAWAI_NAMA2, B.SATUAN_KERJA_JAWAB_ID SATUAN_KERJA_ID, B.TIPE
			, A.SATUAN_KERJA_ASAL_ID, A.SATUAN_KERJA_TUJUAN_ID
			,
			CASE 
			WHEN A.STATUS = 0 OR A.STATUS IS NULL THEN 'baru(belum dibuka)'
			WHEN A.STATUS = 1 AND B.STATUS IS NULL THEN 'sudah terbaca(belum balas)'
			WHEN A.STATUS = 1 AND B.STATUS = 1 THEN 'dibalas(dibalas admin)'
			WHEN B.STATUS = 2 THEN 'user respon(user membalas)'
			ELSE ''
			END STATUS_NAMA
		FROM MAILBOX A
		LEFT JOIN
		(
			SELECT B.*
			FROM MAILBOX_DETIL B
			INNER JOIN
			(
				SELECT MAILBOX_ID, MAX(MAILBOX_DETIL_ID) MAILBOX_DETIL_ID
				FROM MAILBOX_DETIL
				GROUP BY MAILBOX_ID
			) B1 ON B.MAILBOX_DETIL_ID = B1.MAILBOX_DETIL_ID
		) B ON A.MAILBOX_ID = B.MAILBOX_ID
		LEFT JOIN PEGAWAI P1 ON A.PEGAWAI_ID = P1.PEGAWAI_ID
		LEFT JOIN PEGAWAI P2 ON B.PEGAWAI_ID = P2.PEGAWAI_ID
		LEFT JOIN USER_LOGIN UL ON B.USER_LOGIN_ID = UL.USER_LOGIN_ID
		WHERE 1=1 "; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
	function getCountByParamsMaxDetil($statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT FROM 
		( 
		  SELECT A.MAILBOX_DETIL_ID 
		  FROM MAILBOX_DETIL A 
		  INNER JOIN 
		  ( 
			SELECT MAX(A.MAILBOX_DETIL_ID) MAILBOX_DETIL_ID, A.MAILBOX_ID 
			FROM MAILBOX_DETIL A WHERE 1=1 GROUP BY A.MAILBOX_ID 
		  ) B ON A.MAILBOX_DETIL_ID = B.MAILBOX_DETIL_ID 
		  WHERE 1=1 ".$statement."
		) A "; 
		
		$this->select($str); 
		$this->query = $str;		
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(1) AS ROWCOUNT 
				FROM MAILBOX A
				LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				LEFT JOIN
				(
				  SELECT A.MAILBOX_DETIL_ID, A.MAILBOX_ID, A.STATUS, A.TANGGAL, ISI
				  FROM MAILBOX_DETIL A 
				  INNER JOIN 
				  ( 
					SELECT MAX(A.MAILBOX_DETIL_ID) MAILBOX_DETIL_ID, A.MAILBOX_ID 
					FROM MAILBOX_DETIL A WHERE 1=1 GROUP BY A.MAILBOX_ID 
				  ) B ON A.MAILBOX_DETIL_ID = B.MAILBOX_DETIL_ID WHERE 1=1 
				) C ON C.MAILBOX_ID = A.MAILBOX_ID
				LEFT JOIN MAILBOX_KATEGORI D ON A.MAILBOX_KATEGORI_ID = D.MAILBOX_KATEGORI_ID
				LEFT JOIN persuratan.JENIS_PELAYANAN E ON E.JENIS_PELAYANAN_ID = D.JENIS_PELAYANAN_ID
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