<? 
/* *******************************************************************************************************
MODUL NAME 			: IMASYS
FILE NAME 			: 
AUTHOR				: 
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			: 
***************************************************************************************************** */

  /***
  * Entity-base class untuk mengimplementasikan tabel KAPAL_JENIS.
  * 
  ***/
  include_once("Entity.php");

  class UserLoginLog extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function UserLoginLog()
	{
      $this->Entity(); 
    }

    function updatepass()
	{
		$str = "
		UPDATE app.user_login AS A
		SET
		LOGIN_PASS= X.LOGIN_PASS
		FROM
		(
			SELECT
			A.USER_LOGIN_ID, CRYPT('".$this->getField("LOGIN_PASS")."', A.LOGIN_PASS) LOGIN_PASS
			FROM app.user_login A
			WHERE 1=1 AND A.STATUS = '1'
			AND A.LOGIN_USER = '".$this->getField("LOGIN_USER")."' 
		) AS X
		WHERE A.USER_LOGIN_ID = X.USER_LOGIN_ID
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function insertLogin()
	{
		$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","app.USER_LOGIN"));
		
		$str = "
		INSERT INTO app.USER_LOGIN (
			USER_LOGIN_ID, LOGIN_USER, LOGIN_PASS, PEGAWAI_ID, SATUAN_KERJA_ID, STATUS 
		) 
		VALUES (
			".$this->getField("USER_LOGIN_ID").",
			'".$this->getField("LOGIN_USER")."',
			'".$this->getField("LOGIN_USER")."',
			".$this->getField("PEGAWAI_ID").",
			-1, 1
		)
		"; 
		$this->id = $this->getField("USER_LOGIN_ID");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

	function insert()
	{
		$this->setField("USER_LOGIN_LOG_ID", $this->getNextId("USER_LOGIN_LOG_ID","app.USER_LOGIN_LOG"));
		$this->setField("TOKEN", $this->getGenerateToken($this->getField("LOGIN_USER")));
		// echo $this->getField("TOKEN");exit();
		
		$str = "
		INSERT INTO app.USER_LOGIN_LOG (
			USER_LOGIN_LOG_ID, USER_LOGIN_ID, PEGAWAI_ID, SATUAN_KERJA_ID, WAKTU_LOGIN, 
			TOKEN, STATUS, TOKEN_FIREBASE, IMEI
		) 
		SELECT
			".$this->getField("USER_LOGIN_LOG_ID")." USER_LOGIN_LOG_ID, USER_LOGIN_ID, PEGAWAI_ID, SATUAN_KERJA_ID
			, ".$this->getField("WAKTU_LOGIN")." WAKTU_LOGIN, '".$this->getField("TOKEN")."' TOKEN
			, '".$this->getField("STATUS")."' STATUS, '".$this->getField("TOKEN_FIREBASE")."' TOKEN_FIREBASE
			, '".$this->getField("IMEI")."' IMEI
		FROM app.user_login
		WHERE 1=1 AND LOGIN_USER = '".$this->getField("LOGIN_USER")."'
		"; 
		// FROM pegawai
		// AND NIP_BARU
		// AND LOGIN_PASS = crypt('".$this->getField("LOGIN_PASS")."',LOGIN_PASS)
		$this->id = $this->getField("USER_LOGIN_LOG_ID");
		$this->idToken = $this->getField("TOKEN");
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE app.USER_LOGIN_LOG
		SET
		STATUS= '".$this->getField("STATUS")."'
		WHERE TOKEN= '".$this->getField("TOKEN")."'
		"; 
		$this->query = $str;
		//echo $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM app.USER_LOGIN_LOG
        WHERE 
        USER_LOGIN_ID = '".$this->getField("USER_LOGIN_ID")."' AND 
        TOKEN = '".$this->getField("TOKEN")."' 
        ";
				  
		$this->query = $str;
        return $this->execQuery($str);
    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order=" ")
	{
		$str = "
		SELECT 
		USER_LOGIN_LOG_ID, A.USER_LOGIN_ID, A.PEGAWAI_ID, WAKTU_LOGIN, 
		TOKEN, A.STATUS, TOKEN_FIREBASE, B.NAMA NAMA_PEGAWAI,
		IMEI
		FROM app.USER_LOGIN_LOG A
		LEFT JOIN PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
		WHERE 1=1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit();
		
		return $this->selectLimit($str,$limit,$from); 
    }

    function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
	{
		$str = "
		SELECT 
			A.USER_LOGIN_ID, A.LOGIN_USER, A.PEGAWAI_ID, A.SATUAN_KERJA_ID, A.STATUS
		FROM app.user_login A
		WHERE 1=1 AND A.STATUS = '1'
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement." ".$order;
		$this->query = $str;
		// echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
    
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM USER_LOGIN_LOG A
		LEFT JOIN PEGAWAI B ON B.PEGAWAI_ID = A.PEGAWAI_ID
		WHERE 1=1 ".$statement; 
		
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

	function getTokenPegawaiId($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT PEGAWAI_ID AS ROWCOUNT 
		FROM app.USER_LOGIN_LOG A
		WHERE 1=1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }

    function getTokenSatuanKerjaId($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT SATUAN_KERJA_ID AS ROWCOUNT 
		FROM app.USER_LOGIN_LOG A
		WHERE 1=1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
	
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }

    function getToken($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT TOKEN AS ROWCOUNT 
		FROM app.USER_LOGIN_LOG A
		WHERE 1=1 ".$statement; 
		
		while(list($key,$val)=each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$this->query = $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return ""; 
    }
		
  } 
?>