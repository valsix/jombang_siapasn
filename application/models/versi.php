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
  
  class Versi extends Entity{ 

	var $query;
	var $id;
    /**
    * Class constructor.
    **/
    function Versi()
	{
      $this->Entity(); 
    }
	
	function insert()
	{

    }


    function update()
	{

    }

    function updateStatus()
	{

    }
	
	function delete()
	{

    }

    /** 
    * Cari record berdasarkan array parameter dan limit tampilan 
    * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","MASTER_KATEGORI_METODE_EVALUASI_ID"=>"yyy") 
    * @param int limit Jumlah maksimal record yang akan diambil 
    * @param int from Awal record yang diambil 
    * @return boolean True jika sukses, false jika tidak 
    **/ 
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1)
	{
		$str = "
				SELECT version() AS version
		"; 
		
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }
	
	function selectByParamsJenisPelayan($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.JENIS_PELAYANAN_ID ASC')
	{
		$str = "
		SELECT
			A.JENIS_PELAYANAN_ID, A.JUDUL_CHEKLIST, A.NAMA, A.SATUAN_KERJA_TUJUAN_NAMA, A.KEPADA
		FROM PERSURATAN.JENIS_PELAYANAN A
		WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
		{
			$str .= " AND $key = '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query = $str;
		return $this->selectLimit($str,$limit,$from); 
		
    }

    function selectByParamsMonitoring($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.MAILBOX_KATEGORI_ID ASC')
	{
		$str = "
				SELECT A.MAILBOX_KATEGORI_ID, A.NAMA, CASE A.STATUS WHEN '1' THEN 'Tidak Aktif' ELSE 'Aktif' END STATUS_NAMA, 
				CASE WHEN A.STATUS = '1' THEN
					CONCAT('<a onClick=\"hapusData(''',A.MAILBOX_KATEGORI_ID,''',''1'')\" style=\"cursor:pointer\" title=\"Klik untuk mengkatifkan data\"><img src=\"images/icon-nonaktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				ELSE
					CONCAT('<a onClick=\"hapusData(''',A.MAILBOX_KATEGORI_ID,''','''')\" style=\"cursor:pointer\" title=\"Klik untuk menonatifkan data\"><img src=\"images/icon-aktip.png\" width=\"15px\" heigth=\"15px\"></a>')
				END LINK_URL_INFO
				, A.JENIS_PELAYANAN_ID, CASE WHEN A.JENIS_PELAYANAN_ID IS NULL THEN 'Usul Umum' ELSE B.NAMA END JENIS_PELAYANAN_NAMA
				, A.SATUAN_KERJA_ID
				, CASE WHEN A.SATUAN_KERJA_ID IS NULL THEN 'Semua Satuan Kerja' ELSE AMBIL_SATKER_NAMA_DETIL(A.SATUAN_KERJA_ID) END SATUAN_KERJA_NAMA
				FROM MAILBOX_KATEGORI A
				LEFT JOIN persuratan.JENIS_PELAYANAN B ON A.JENIS_PELAYANAN_ID = B.JENIS_PELAYANAN_ID
				WHERE 1 = 1
		"; 
		
		while(list($key,$val) = each($paramsArray))
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
				SELECT COUNT(A.MAILBOX_KATEGORI_ID) AS ROWCOUNT 
				FROM MAILBOX_KATEGORI A
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