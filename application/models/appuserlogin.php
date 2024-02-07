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
  // include_once('Entity.php');
  include_once(APPPATH.'/models/Entity.php');

  class AppUserLogin extends Entity{ 

  	var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function AppUserLogin()
    {
    	$this->Entity(); 
    }

    function insert()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$this->setField("USER_LOGIN_ID", $this->getNextId("USER_LOGIN_ID","app.user_login"));

    	$str = "
        INSERT INTO app.user_login (
        USER_LOGIN_ID, LOGIN_USER, LOGIN_PASS, PEGAWAI_ID, SATUAN_KERJA_ID, STATUS) 
        VALUES 
        (
            '".$this->getField("USER_LOGIN_ID")."'
            ,  '".$this->getField("LOGIN_USER")."'
            --, crypt('".$this->getField("LOGIN_PASS")."',gen_salt('bf'))
            , crypt('".$this->getField("LOGIN_PASS")."', gen_salt('md5'))
            ,  ".$this->getField("PEGAWAI_ID")."
            , ".$this->getField("SATUAN_KERJA_ID")."
            , ".$this->getField("STATUS")."
        )
      "; 
    	$this->id = $this->getField("USER_LOGIN_ID");
    	$this->query = $str;
		// echo $str;exit();
    	return $this->execQuery($str);
    }

    function update()
    {
    	$str = "
    	UPDATE app.user_login
    	SET   
    	LOGIN_USER		= '".$this->getField("LOGIN_USER")."',
    	PEGAWAI_ID		= ".$this->getField("PEGAWAI_ID").",
    	SATUAN_KERJA_ID		= ".$this->getField("SATUAN_KERJA_ID")."
    	WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'
    	"; 
    	$this->query = $str;
		//echo $str;
    	return $this->execQuery($str);
    }

    function resetPassword()
    {
    	$str = "
    	UPDATE user_login
    	SET    LOGIN_PASS       = '".$this->getField("LOGIN_PASS")."',
    	LAST_USER 		= '".$this->getField("LAST_USER")."',
    	LAST_DATE 		= ".$this->getField("LAST_DATE")."
    	WHERE  USER_LOGIN_ID    = '".$this->getField("USER_LOGIN_ID")."'

    	"; 
    	$this->query = $str;
		//echo $str;
    	return $this->execQuery($str);
    }

    function updateStatus()
    {
    	/*Auto-generate primary key(s) by next max value (integer) */
    	$str = "		
    	UPDATE user_login
    	SET    
    	STATUS   		= ".$this->getField("STATUS").",
    	LAST_USER		= '".$this->getField("LAST_USER")."',
    	LAST_DATE		= ".$this->getField("LAST_DATE")."
    	WHERE  USER_LOGIN_ID    = ".$this->getField("USER_LOGIN_ID")."
    	"; 
    	$this->query = $str;
    	return $this->execQuery($str);
    }

    function delete()
    {
    	$str = "DELETE FROM user_login
    	WHERE 
    	USER_LOGIN_ID = ".$this->getField("USER_LOGIN_ID").""; 

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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY t1.USER_LOGIN_ID ASC ')
    {
    	$str = "	
    	SELECT t1.user_login_id , t1.login_user ,t2.nama ,t3.nama as nama_satuan ,t2.pegawai_id,t3.satuan_kerja_id
					from  app.user_login t1
					left join pegawai t2 on t1.pegawai_id = t2.pegawai_id
					left join  satuan_kerja t3 on t1.satuan_kerja_id = t3.satuan_kerja_id
    				WHERE 1 = 1 "; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	$this->query = $str;

    	$str .= $statement." ".$order;
    	return $this->selectLimit($str,$limit,$from); 

    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=20000,$from=0, $statement='',$order=' ORDER BY t1.USER_LOGIN_ID ASC ')
    {
    	$str = "
				SELECT t1.user_login_id , t1.login_user ,t2.nama , CASE t1.satuan_kerja_id WHEN -1 THEN 'Semua Satuan Kerja' ELSE t3.nama END nama_satuan ,t2.pegawai_id,t3.satuan_kerja_id,t2.nip_baru
					from  app.user_login t1
					left join pegawai t2 on t1.pegawai_id = t2.pegawai_id
					left join  satuan_kerja t3 on t1.satuan_kerja_id = t3.satuan_kerja_id
		    	WHERE 1 = 1  "; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$str .= $statement." ".$order;
    	$this->query = $str;
    //	echo $str;exit();

    	return $this->selectLimit($str,$limit,$from); 

    }

    function selectByParamsLogin($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order='')
    {
    	$str = 
    	"	
    		SELECT t1.user_login_id , t1.login_user ,t2.nama ,t3.nama ,t2.pegawai_id,t3.satuan_kerja_id
					from  app.user_login t1
					left join pegawai t2 on t1.pegawai_id = t2.pegawai_id
					left join  satuan_kerja t3 on t1.satuan_kerja_id = t3.satuan_kerja_id
    	WHERE 1=1
    	"; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}
    	$this->query = $str;
		//echo $str;exit;
    	$str .= $statement." ".$order;
    	return $this->selectLimit($str,$limit,$from); 

    }
    
    /** 
    * Hitung jumlah record berdasarkan parameter (array). 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","IJIN_USAHA_ID"=>"yyy") 
    * @return long Jumlah record yang sesuai kriteria 
    **/ 
    function getCountByParams($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(USER_LOGIN_ID) AS ROWCOUNT FROM 
					from  app.user_login t1
					left join pegawai t2 on t1.pegawai_id = t2.pegawai_id
					left join  satuan_kerja t3 on t1.satuan_kerja_id = t3.satuan_kerja_id
		
    	WHERE 1 = 1 ".$statement; 

    	foreach ($paramsArray as $key => $val)
    	{
    		$str .= " AND $key = '$val' ";
    	}

    	$this->select($str); 
    	if($this->firstRow()) 
    		return $this->getField("ROWCOUNT"); 
    	else 
    		return 0; 
    }

    function getCountByParamsMonitoring($paramsArray=array(), $statement="")
    {
    	$str = "SELECT COUNT(1) AS ROWCOUNT
    	FROM user_login A
    	LEFT JOIN user_group C ON A.USER_GROUP_ID = C.USER_GROUP_ID
    	LEFT JOIN satuan_kerja D ON A.SATUAN_KERJA_ID = D.SATUAN_KERJA_ID
    	WHERE 1 = 1 ".$statement; 

    	foreach ($paramsArray as $key => $val)
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