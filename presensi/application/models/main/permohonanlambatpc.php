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
  include_once(APPPATH.'/models/Entity.php');

  class PermohonanLambatPc extends Entity{ 

	var $query;
    /**
    * Class constructor.
    **/
    function PermohonanLambatPc()
	{
      $this->Entity(); 
    }
	
	function insert()
	{
		$this->setField("PERMOHONAN_LAMBAT_PC_ID", $this->getNextId("PERMOHONAN_LAMBAT_PC_ID","presensi.PERMOHONAN_LAMBAT_PC"));

		$str= "
		INSERT INTO presensi.PERMOHONAN_LAMBAT_PC 
		(
			PERMOHONAN_LAMBAT_PC_ID, PEGAWAI_ID, NOMOR, TANGGAL, 
			JABATAN, CABANG, DEPARTEMEN, 
			SUB_DEPARTEMEN, TANGGAL_IJIN, JAM_DATANG, 
			JAM_PULANG, KEPERLUAN, KETERANGAN, 
			LAST_CREATE_USER, LAST_CREATE_DATE, CABANG_ID,
			TANGGAL_AWAL, TANGGAL_AKHIR, LAMPIRAN,
			LOKASI
		) 
		VALUES 
		( 
		'".$this->getField("PERMOHONAN_LAMBAT_PC_ID")."', '".$this->getField("PEGAWAI_ID")."', '".$this->getField("NOMOR")."'
		, ".$this->getField("TANGGAL")."
		, '".$this->getField("JABATAN")."', '".$this->getField("CABANG")."', '".$this->getField("DEPARTEMEN")."',
		'".$this->getField("SUB_DEPARTEMEN")."', ".$this->getField("TANGGAL_IJIN").", '".$this->getField("JAM_DATANG")."',
		'".$this->getField("JAM_PULANG")."', '".$this->getField("KEPERLUAN")."', '".$this->getField("KETERANGAN")."', 
		'".$this->getField("LAST_CREATE_USER")."', ".$this->getField("LAST_CREATE_DATE").", '".$this->getField("CABANG_ID")."',
		".$this->getField("TANGGAL_AWAL").", ".$this->getField("TANGGAL_AKHIR").", '".$this->getField("LAMPIRAN")."', 
		'".$this->getField("LOKASI")."')
		"; 
		$this->id= $this->getField("PERMOHONAN_LAMBAT_PC_ID");
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }

    function update()
	{
		$str = "
		UPDATE presensi.PERMOHONAN_LAMBAT_PC
		SET    
			PEGAWAI_ID= '".$this->getField("PEGAWAI_ID")."',
			TANGGAL_AWAL= ".$this->getField("TANGGAL_AWAL").",
			TANGGAL_AKHIR= ".$this->getField("TANGGAL_AKHIR").",
			NOMOR= '".$this->getField("NOMOR")."', 
			TANGGAL= ".$this->getField("TANGGAL").",
			JABATAN= '".$this->getField("JABATAN")."', 
			CABANG= '".$this->getField("CABANG")."',
			DEPARTEMEN= '".$this->getField("DEPARTEMEN")."', 
			SUB_DEPARTEMEN= '".$this->getField("SUB_DEPARTEMEN")."', 
			KETERANGAN= '".$this->getField("KETERANGAN")."', 
			LAST_UPDATE_USER= '".$this->getField("LAST_UPDATE_USER")."',
			LAST_UPDATE_DATE= ".$this->getField("LAST_UPDATE_DATE").", 
			JAM_DATANG= '".$this->getField("JAM_DATANG")."',
			JAM_PULANG= '".$this->getField("JAM_PULANG")."',
			APPROVAL1= '".$this->getField("APPROVAL1")."',
			ALASAN_TOLAK1= '".$this->getField("ALASAN_TOLAK1")."'
		WHERE PERMOHONAN_LAMBAT_PC_ID= '".$this->getField("PERMOHONAN_LAMBAT_PC_ID")."'
		";
		$this->query= $str;
		// echo $str;exit();
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str= "
        DELETE FROM presensi.PERMOHONAN_LAMBAT_PC
        WHERE 
        PERMOHONAN_LAMBAT_PC_ID= ".$this->getField("PERMOHONAN_LAMBAT_PC_ID").""; 
				  
		$this->query= $str;
        return $this->execQuery($str);
    }

    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement="", $order="ORDER BY PERMOHONAN_LAMBAT_PC_ID ASC")
	{
		$str= "
		SELECT
			PERMOHONAN_LAMBAT_PC_ID, A.PEGAWAI_ID, B.NIP_BARU
			, (CASE WHEN COALESCE(NULLIF(B.GELAR_DEPAN,'') , NULL ) IS NULL THEN '' ELSE B.GELAR_DEPAN || ' ' END) || B.NAMA || (CASE WHEN COALESCE(NULLIF(B.GELAR_BELAKANG,'') , NULL ) IS NULL THEN '' ELSE '' || B.GELAR_BELAKANG END) NAMA_LENGKAP
			, NOMOR, TANGGAL, A.JABATAN JABATAN, CABANG,
			DEPARTEMEN, SUB_DEPARTEMEN,
			COALESCE(A.TANGGAL_IJIN, TANGGAL) TANGGAL_IJIN, 
			COALESCE(A.JAM_DATANG, TO_CHAR(A.TANGGAL_AWAL, 'DD-MM-YYYY')) JAM_DATANG, 
			COALESCE(A.JAM_PULANG, TO_CHAR(A.TANGGAL_AKHIR, 'DD-MM-YYYY')) JAM_PULANG,
			KEPERLUAN, A.KETERANGAN KETERANGAN, A.LAST_CREATE_USER, A.LAST_CREATE_DATE,
			A.LAST_UPDATE_USER, A.LAST_UPDATE_DATE, A.CABANG_ID, B.NAMA NAMA_PEGAWAI, '' NAMA_CABANG,
			D.NAMA NAMA_APPROVAL1,
			CASE WHEN APPROVAL1 = 'Y' THEN 'Disetujui' WHEN APPROVAL1 = 'T' THEN 'Ditolak' ELSE 'Verifikasi' END STATUS_APPROVAL1, 
			CASE WHEN APPROVAL2 = 'Y' THEN 'Disetujui' WHEN APPROVAL2 = 'T' THEN 'Ditolak' ELSE 'Verifikasi' END STATUS_APPROVAL2,
			APPROVAL_TANGGAL1, APPROVAL_TANGGAL2,
			CASE
			WHEN A.TANGGAL_AWAL IS NULL
			THEN 'TL/PC'
			ELSE 
			'Non SPPD'
			END JENIS,
			A.APPROVAL1, A.APPROVAL2,
			CASE
			WHEN A.APPROVAL1 = 'Y' THEN 'Disetujui'
			WHEN A.APPROVAL1 = 'T' THEN 'Ditolak'
			WHEN COALESCE(NULLIF(A.APPROVAL1, ''), 'X') = 'X' THEN 'Menunggu Persetujuan'
			ELSE 'Proses' END STATUS
			, TANGGAL_AWAL, TANGGAL_AKHIR, A.LOKASI
			, COALESCE(D.NAMA) NAMA_APPROVAL
		FROM presensi.PERMOHONAN_LAMBAT_PC A 
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = CAST(A.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = CAST(A.PEGAWAI_ID_APPROVAL1 AS NUMERIC)
		WHERE 1=1
		"; 
	
		while(list($key,$val)= each($paramsArray))
		{
			$str .= " AND $key= '$val' ";
		}
		
		$str .= $statement." ".$order;
		$this->query= $str;
		return $this->selectLimit($str,$limit,$from); 
    }
	
    function getCountByParams($paramsArray=array(), $statement="")
	{
		$str= "
		SELECT COUNT(1) AS ROWCOUNT
		FROM presensi.PERMOHONAN_LAMBAT_PC A 
		INNER JOIN PEGAWAI B ON B.PEGAWAI_ID = CAST(A.PEGAWAI_ID AS NUMERIC)
		LEFT JOIN PEGAWAI D ON D.PEGAWAI_ID = CAST(A.PEGAWAI_ID_APPROVAL1 AS NUMERIC)
		WHERE 1=1 ".$statement; 
		
		foreach ($paramsArray as $key => $val)
		{
			$str .= " AND $key= '$val' ";
		}
		
		$this->query= $str;
		$this->select($str); 
		if($this->firstRow()) 
			return $this->getField("ROWCOUNT"); 
		else 
			return 0; 
    }
  } 
?>