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
include_once(APPPATH.'/models/Entity.php');

class PermohonanLupaAbsen extends Entity{ 

	var $query;
	var $id;

	/**
	* Class constructor.
	**/
	function PermohonanLupaAbsen()
	{
	  $this->Entity(); 
	}

	function insert()
	{
		$this->setField("PERMOHONAN_LUPA_ABSEN_ID", $this->getNextId("PERMOHONAN_LUPA_ABSEN_ID","presensi.PERMOHONAN_LUPA_ABSEN"));

		$str = "
		INSERT INTO presensi.PERMOHONAN_LUPA_ABSEN
		(
			PERMOHONAN_LUPA_ABSEN_ID, PEGAWAI_ID, NOMOR, TANGGAL, JABATAN, 
			CABANG, DEPARTEMEN, SUB_DEPARTEMEN, JENIS_LUPA_ABSEN, 
			TANGGAL_IJIN, KETERANGAN, LAST_CREATE_USER, 
			LAST_CREATE_DATE, STATUS_MASUK, 
			STATUS_PULANG
		) 
		VALUES 
		( 
			'".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NOMOR")."', 
			".$this->getField("TANGGAL").", '".$this->getField("JABATAN")."', '".$this->getField("CABANG")."',
			'".$this->getField("DEPARTEMEN")."', '".$this->getField("SUB_DEPARTEMEN")."', '".$this->getField("JENIS_LUPA_ABSEN")."',
			".$this->getField("TANGGAL_IJIN").", '".$this->getField("KETERANGAN")."', '".$this->getField("LAST_CREATE_USER")."',
			".$this->getField("LAST_CREATE_DATE").", '".$this->getField("STATUS_MASUK")."', 
			'".$this->getField("STATUS_PULANG")."'
		)
		"; 
		$this->id = $this->getField("PERMOHONAN_LUPA_ABSEN_ID");
		$this->query = $str;
		// echo $str;exit();
		
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE presensi.PERMOHONAN_LUPA_ABSEN
		SET    
		PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."', 
		NOMOR= '".$this->getField("NOMOR")."', 
		TANGGAL= ".$this->getField("TANGGAL").",
		JABATAN= '".$this->getField("JABATAN")."', 
		CABANG= '".$this->getField("CABANG")."',
		DEPARTEMEN= '".$this->getField("DEPARTEMEN")."', 
		SUB_DEPARTEMEN= '".$this->getField("SUB_DEPARTEMEN")."', 
		JENIS_LUPA_ABSEN= '".$this->getField("JENIS_LUPA_ABSEN")."',
		TANGGAL_IJIN= ".$this->getField("TANGGAL_IJIN").",
		KETERANGAN= '".$this->getField("KETERANGAN")."', 
		LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
		LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").", 
		STATUS_MASUK= '".$this->getField("STATUS_MASUK")."', 
		STATUS_PULANG= '".$this->getField("STATUS_PULANG")."',
		APPROVAL1= '".$this->getField("APPROVAL1")."',
		ALASAN_TOLAK1= '".$this->getField("ALASAN_TOLAK1")."'
		WHERE PERMOHONAN_LUPA_ABSEN_ID= '".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."'
		"; 
		$this->query = $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function approval1()
	{
		$str = "
		UPDATE PERMOHONAN_LUPA_ABSEN
		SET    APPROVAL1          			= '".$this->getField("APPROVAL")."',
		ALASAN_TOLAK1				= '".$this->getField("ALASAN_TOLAK")."',
		APPROVAL_TANGGAL1			= ".$this->getField("APPROVAL_TANGGAL1").",
		LAST_UPDATE_DATE     		= ".$this->getField("LAST_UPDATE_DATE").",
		LAST_UPDATE_USER       		= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE  PERMOHONAN_LUPA_ABSEN_ID   = '".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }
	
	function approval2()
	{
		$str = "
		UPDATE PERMOHONAN_LUPA_ABSEN
		SET    APPROVAL2          			= '".$this->getField("APPROVAL")."',
		ALASAN_TOLAK2				= '".$this->getField("ALASAN_TOLAK")."',
		APPROVAL_TANGGAL2			= ".$this->getField("APPROVAL_TANGGAL2").",
		LAST_UPDATE_DATE     		= ".$this->getField("LAST_UPDATE_DATE").",
		LAST_UPDATE_USER       		= '".$this->getField("LAST_UPDATE_USER")."'
		WHERE  PERMOHONAN_LUPA_ABSEN_ID   = '".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."'
		"; 
		$this->query = $str;
		return $this->execQuery($str);
    }

	function delete()
	{
        $str = "
        DELETE FROM PERMOHONAN_LUPA_ABSEN
        WHERE 
        PERMOHONAN_LUPA_ABSEN_ID = ".$this->getField("PERMOHONAN_LUPA_ABSEN_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_LUPA_ABSEN_ID DESC")
	{
		$str = "
		SELECT 
		PERMOHONAN_LUPA_ABSEN_ID, A.PEGAWAI_ID, B.NIP_BARU
		, (CASE WHEN COALESCE(NULLIF(B.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE B.GELAR_DEPAN || ' ' END) || B.NAMA || (CASE WHEN COALESCE(NULLIF(B.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || B.GELAR_BELAKANG END) NAMA_LENGKAP
		, NOMOR, TANGGAL, A.JABATAN, 
		CABANG, DEPARTEMEN, SUB_DEPARTEMEN, JENIS_LUPA_ABSEN
		, TO_DATE(TO_CHAR(TANGGAL_IJIN, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS') TANGGAL_IJIN
		, TO_CHAR(TANGGAL_IJIN, 'HH24:MI:SS') TANGGAL_IJIN_JAM
		, A.KETERANGAN, PEGAWAI_ID_APPROVAL1, PEGAWAI_ID_APPROVAL2, 
		APPROVAL1, APPROVAL2, ALASAN_TOLAK1, ALASAN_TOLAK2, APPROVAL_TANGGAL1, 
		APPROVAL_TANGGAL2, A.LAST_CREATE_USER, A.LAST_CREATE_DATE, A.LAST_UPDATE_USER, 
		A.LAST_UPDATE_DATE, D.NAMA NAMA_APPROVAL1,
		CASE WHEN APPROVAL1 = 'Y' THEN 'Disetujui' WHEN APPROVAL1 = 'T' THEN 'Ditolak' ELSE 'Verifikasi' END STATUS_APPROVAL1, 
		CASE WHEN APPROVAL2 = 'Y' THEN 'Disetujui' WHEN APPROVAL2 = 'T' THEN 'Ditolak' ELSE 'Verifikasi' END STATUS_APPROVAL2,
		CASE
		WHEN A.APPROVAL1 = 'Y' THEN 'Disetujui'
		WHEN A.APPROVAL1 = 'T' THEN 'Ditolak'
		WHEN COALESCE(NULLIF(A.APPROVAL1, ''), 'X') = 'X' THEN 'Menunggu Persetujuan'
		ELSE 'Proses' END STATUS
		, STATUS_MASUK, STATUS_PULANG,
		CASE 
		WHEN COALESCE(NULLIF(A.STATUS_MASUK, ''), '0') = '1' AND COALESCE(NULLIF(A.STATUS_PULANG, ''), '0') = '1' THEN 'MASUK & PULANG'
		WHEN COALESCE(NULLIF(A.STATUS_MASUK, ''), '0') = '1' AND COALESCE(NULLIF(A.STATUS_PULANG, ''), '0') = '0' THEN 'MASUK'
		WHEN COALESCE(NULLIF(A.STATUS_MASUK, ''), '0') = '0' AND COALESCE(NULLIF(A.STATUS_PULANG, ''), '0') = '1' THEN 'PULANG'
		WHEN COALESCE(NULLIF(A.JENIS_LUPA_ABSEN, ''), '0') = '2' THEN 'Keg/Senam/Apel'
		END LUPA_ABSEN_JENIS
		, COALESCE(D.NAMA) NAMA_APPROVAL
		FROM presensi.PERMOHONAN_LUPA_ABSEN A
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = CAST(A.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = CAST(A.PEGAWAI_ID_APPROVAL1 AS NUMERIC)
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

    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str = "
		SELECT COUNT(1) AS ROWCOUNT 
		FROM presensi.PERMOHONAN_LUPA_ABSEN A
		WHERE 1=1 ".$statement; 
		
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
	
	function getTotalBulanan($pegawaiId, $tglIjin)
	{
		$str = " SELECT ambil_total_permohonan_lupa_absen('".$pegawaiId."', ".$tglIjin.") ROWCOUNT "; 
		$this->select($str); 
		//echo $str;
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
	
    

} 
?>