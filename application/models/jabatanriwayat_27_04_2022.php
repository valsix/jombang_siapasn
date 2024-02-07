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
  include_once('Entity.php');
  
  class JabatanRiwayat extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function JabatanRiwayat()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("JABATAN_RIWAYAT_ID", $this->getNextId("JABATAN_RIWAYAT_ID","JABATAN_RIWAYAT")); 

     	$str = "
			INSERT INTO JABATAN_RIWAYAT (
				JABATAN_RIWAYAT_ID, PEGAWAI_ID, JENIS_JABATAN_ID, PEJABAT_PENETAP_ID, PEJABAT_PENETAP, TIPE_PEGAWAI_ID, JABATAN_FU_ID, JABATAN_FT_ID, 
				ESELON_ID, NO_SK, TANGGAL_SK, TMT_JABATAN, NAMA, NO_PELANTIKAN, TANGGAL_PELANTIKAN, TUNJANGAN, KREDIT, 
				IS_MANUAL, SATKER_NAMA, SATKER_ID, BULAN_DIBAYAR, TMT_BATAS_USIA_PENSIUN,
				LAST_USER, LAST_DATE, LAST_LEVEL, TMT_ESELON, USER_LOGIN_ID, USER_LOGIN_PEGAWAI_ID
			)
			VALUES (
				 	".$this->getField("JABATAN_RIWAYAT_ID").",
				 	".$this->getField("PEGAWAI_ID").",
					'".$this->getField("JENIS_JABATAN_ID")."',
				 	".$this->getField("PEJABAT_PENETAP_ID").",
				 	'".$this->getField("PEJABAT_PENETAP")."',
				 	".$this->getField("TIPE_PEGAWAI_ID").",
				 	".$this->getField("JABATAN_FU_ID").",
				 	".$this->getField("JABATAN_FT_ID").",
				 	".$this->getField("ESELON_ID").",
				 	'".$this->getField("NO_SK")."',
				 	".$this->getField("TANGGAL_SK").",
				 	".$this->getField("TMT_JABATAN").",
				 	'".$this->getField("NAMA")."',
				 	'".$this->getField("NO_PELANTIKAN")."',
				 	".$this->getField("TANGGAL_PELANTIKAN").",
				 	".$this->getField("TUNJANGAN").",
				 	".$this->getField("KREDIT").",
				 	".$this->getField("IS_MANUAL").",
					'".$this->getField("SATKER_NAMA")."',
					".$this->getField("SATKER_ID").",
				 	".$this->getField("BULAN_DIBAYAR").",
				 	".$this->getField("TMT_BATAS_USIA_PENSIUN").",
				 	'".$this->getField("LAST_USER")."',
				 	".$this->getField("LAST_DATE").",
				 	".$this->getField("LAST_LEVEL").",
				 	".$this->getField("TMT_ESELON").",
				 	".$this->getField("USER_LOGIN_ID").",
				 	".$this->getField("USER_LOGIN_PEGAWAI_ID")."
			)
		"; 	
		$this->id = $this->getField("JABATAN_RIWAYAT_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE JABATAN_RIWAYAT
				SET    
					PEGAWAI_ID= ".$this->getField("PEGAWAI_ID").",
					JENIS_JABATAN_ID= '".$this->getField("JENIS_JABATAN_ID")."',
				 	PEJABAT_PENETAP_ID= ".$this->getField("PEJABAT_PENETAP_ID").",
				 	PEJABAT_PENETAP= '".$this->getField("PEJABAT_PENETAP")."',
				 	TIPE_PEGAWAI_ID= ".$this->getField("TIPE_PEGAWAI_ID").",
				 	JABATAN_FU_ID= ".$this->getField("JABATAN_FU_ID").",
				 	JABATAN_FT_ID= ".$this->getField("JABATAN_FT_ID").",
				 	ESELON_ID= ".$this->getField("ESELON_ID").",
				 	NO_SK= '".$this->getField("NO_SK")."',
				 	TANGGAL_SK= ".$this->getField("TANGGAL_SK").",
				 	TMT_JABATAN= ".$this->getField("TMT_JABATAN").",
				 	NAMA= '".$this->getField("NAMA")."',
				 	NO_PELANTIKAN= '".$this->getField("NO_PELANTIKAN")."',
				 	TANGGAL_PELANTIKAN= ".$this->getField("TANGGAL_PELANTIKAN").",
				 	TUNJANGAN= ".$this->getField("TUNJANGAN").",
				 	KREDIT= ".$this->getField("KREDIT").",
				 	SATKER_ID= ".$this->getField("SATKER_ID").",
					SATKER_NAMA= '".$this->getField("SATKER_NAMA")."',
					IS_MANUAL= ".$this->getField("IS_MANUAL").",
				 	BULAN_DIBAYAR= ".$this->getField("BULAN_DIBAYAR").",
				 	TMT_BATAS_USIA_PENSIUN= ".$this->getField("TMT_BATAS_USIA_PENSIUN").",
				 	TMT_ESELON= ".$this->getField("TMT_ESELON").",
				 	LAST_USER= '".$this->getField("LAST_USER")."',
				 	LAST_DATE= ".$this->getField("LAST_DATE").",
				 	LAST_LEVEL= ".$this->getField("LAST_LEVEL").",
				 	USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
				 	USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatus()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE JABATAN_RIWAYAT
				SET    
					   STATUS   	= ".$this->getField("STATUS").",
					   LAST_USER	= '".$this->getField("LAST_USER")."',
					   LAST_DATE	= ".$this->getField("LAST_DATE").",
					   USER_LOGIN_ID	= ".$this->getField("USER_LOGIN_ID").",
					   USER_LOGIN_PEGAWAI_ID	= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE  JABATAN_RIWAYAT_ID    	= ".$this->getField("JABATAN_RIWAYAT_ID")."
				"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function delete()
	{
       $str = "
				UPDATE JABATAN_RIWAYAT SET
					STATUS = '1',
					LAST_USER= '".$this->getField("LAST_USER")."',
					LAST_DATE= ".$this->getField("LAST_DATE").",
					USER_LOGIN_ID= ".$this->getField("USER_LOGIN_ID").",
					USER_LOGIN_PEGAWAI_ID= ".$this->getField("USER_LOGIN_PEGAWAI_ID")."
				WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
				";
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function deleteData()
	{
       $str = "
				DELETE FROM JABATAN_RIWAYAT
				WHERE JABATAN_RIWAYAT_ID = ".$this->getField("JABATAN_RIWAYAT_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.TMT_JABATAN DESC')
	{
		$str = "
			SELECT
				A.JABATAN_RIWAYAT_ID, A.PEGAWAI_ID, A.PEJABAT_PENETAP_ID, A.PEJABAT_PENETAP, A.TIPE_PEGAWAI_ID, A.JABATAN_FU_ID, A.JABATAN_FT_ID, 
				A.ESELON_ID, B.NAMA ESELON_NAMA, A.NO_SK, A.TANGGAL_SK, A.TMT_JABATAN, A.NAMA, A.NO_PELANTIKAN, A.TANGGAL_PELANTIKAN, A.TUNJANGAN, A.KREDIT, A.SATKER_ID
				, 
				CASE 
				WHEN A.SATKER_ID IS NULL THEN
				A.SATKER_NAMA 
				ELSE AMBIL_SATKER_NAMA_DYNAMIC(A.SATKER_ID) 
				END SATUAN_KERJA_NAMA_DETILbak
				, 
				CASE 
				WHEN A.SATKER_ID IS NULL THEN
				A.SATKER_NAMA 
				ELSE AMBIL_SATKER_NAMA_DETIL(A.SATKER_ID) 
				END SATUAN_KERJA_NAMA_DETIL
				, A.JENIS_JABATAN_ID, CASE A.JENIS_JABATAN_ID WHEN '1' THEN 'Jabatan Struktural' WHEN '2' THEN 'Jabatan Fungsional Umum' WHEN '3' THEN 'Jabatan Fungsional Tertentu' END JENIS_JABATAN_NAMA
				, A.IS_MANUAL, A.BULAN_DIBAYAR, A.TMT_BATAS_USIA_PENSIUN, A.STATUS, A.LAST_USER, A.LAST_DATE, A.LAST_LEVEL
				, COALESCE(HK.HUKUMAN_ID,0) DATA_HUKUMAN
				, A.TMT_ESELON
			FROM JABATAN_RIWAYAT A
			LEFT JOIN ESELON B ON A.ESELON_ID = B.ESELON_ID
			LEFT JOIN HUKUMAN HK ON A.JABATAN_RIWAYAT_ID = HK.JABATAN_RIWAYAT_ID
			WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		//echo $str;exit;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
    function getCountByParams($paramsArray=array(), $statement='')
	{
		$str = "
				SELECT COUNT(A.JABATAN_RIWAYAT_ID) AS ROWCOUNT 
				FROM JABATAN_RIWAYAT A
				WHERE 1 = 1 ".$statement; 
		while(list($key,$val)=each($paramsArray))
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