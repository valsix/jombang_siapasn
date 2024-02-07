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
  
  class CetakIjinBelajar extends Entity{ 

	 var $query;
  	var $id;
    /**
    * Class constructor.
    **/
    function CetakIjinBelajar()
	{
      $this->Entity(); 
    }

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$this->setField("CETAK_IJIN_BELAJAR_ID", $this->getNextId("CETAK_IJIN_BELAJAR_ID","PERSURATAN.CETAK_IJIN_BELAJAR")); 

     	$str = "
			INSERT INTO PERSURATAN.CETAK_IJIN_BELAJAR (
				CETAK_IJIN_BELAJAR_ID, SURAT_MASUK_PEGAWAI_ID, JENIS_ID, SURAT_MASUK_BKD_ID, SURAT_MASUK_UPT_ID, PEGAWAI_ID, NOMOR_SURAT_KELUAR
				, TANGGAL_SURAT_KELUAR, NOMOR_SURAT_DINAS, TANGGAL_SURAT_DINAS, NAMA_JABATAN_TTD_SURAT_KELUAR, NAMA_PEJABAT_TTD_SURAT_KELUAR
				, PANGKAT_PEJABAT_TTD_SURAT_KELUAR, NIP_PEJABAT_TTD_SURAT_KELUAR, PEGAWAI_NAMA, PEGAWAI_NIP_BARU, PEGAWAI_PANGKAT, PEGAWAI_TTL
				, PEGAWAI_PENDIDIKAN, PEGAWAI_JABATAN, PEGAWAI_SATUAN_KERJA, PENDIDIKAN_NAMA, PENDIDIKAN_JURUSAN, PENDIDIKAN_SEKOLAH
				, LAST_USER, LAST_DATE, LAST_LEVEL
				)
			VALUES (
				 ".$this->getField("CETAK_IJIN_BELAJAR_ID").",
				 ".$this->getField("SURAT_MASUK_PEGAWAI_ID").",
				 ".$this->getField("JENIS_ID").",
				 ".$this->getField("SURAT_MASUK_BKD_ID").",
				 ".$this->getField("SURAT_MASUK_UPT_ID").",
				 ".$this->getField("PEGAWAI_ID").",
				 '".$this->getField("NOMOR_SURAT_KELUAR")."',
				 ".$this->getField("TANGGAL_SURAT_KELUAR").",
				 '".$this->getField("NOMOR_SURAT_DINAS")."',
				 ".$this->getField("TANGGAL_SURAT_DINAS").",
				 '".$this->getField("NAMA_JABATAN_TTD_SURAT_KELUAR")."',
				 '".$this->getField("NAMA_PEJABAT_TTD_SURAT_KELUAR")."',
				 '".$this->getField("PANGKAT_PEJABAT_TTD_SURAT_KELUAR")."',
				 '".$this->getField("NIP_PEJABAT_TTD_SURAT_KELUAR")."',
				 '".$this->getField("PEGAWAI_NAMA")."',
				 '".$this->getField("PEGAWAI_NIP_BARU")."',
				 '".$this->getField("PEGAWAI_PANGKAT")."',
				 '".$this->getField("PEGAWAI_TTL")."',
				 '".$this->getField("PEGAWAI_PENDIDIKAN")."',
				 '".$this->getField("PEGAWAI_JABATAN")."',
				 '".$this->getField("PEGAWAI_SATUAN_KERJA")."',
				 '".$this->getField("PENDIDIKAN_NAMA")."',
				 '".$this->getField("PENDIDIKAN_JURUSAN")."',
				 '".$this->getField("PENDIDIKAN_SEKOLAH")."',
				 '".$this->getField("LAST_USER")."',
				 ".$this->getField("LAST_DATE").",
				 ".$this->getField("LAST_LEVEL")."
			)
		"; 	
		$this->id = $this->getField("CETAK_IJIN_BELAJAR_ID");
		$this->query = $str;
		//echo $str;exit;
		return $this->execQuery($str);
    }

    function updateStatusKirim()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_KELUAR_BKD
				SET
					STATUS_KIRIM_TEKNIS= 2
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		$this->execQuery($str);
		
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.SURAT_MASUK_PEGAWAI
				SET
					STATUS_SURAT_KELUAR= 3
				WHERE SURAT_MASUK_PEGAWAI_ID = ".$this->getField("SURAT_MASUK_PEGAWAI_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "		
				UPDATE PERSURATAN.CETAK_IJIN_BELAJAR
				SET
					NOMOR_SURAT_KELUAR= '".$this->getField("NOMOR_SURAT_KELUAR")."',
					TANGGAL_SURAT_KELUAR= ".$this->getField("TANGGAL_SURAT_KELUAR").",
					NOMOR_SURAT_DINAS= '".$this->getField("NOMOR_SURAT_DINAS")."',
					TANGGAL_SURAT_DINAS= ".$this->getField("TANGGAL_SURAT_DINAS").",
					NAMA_JABATAN_TTD_SURAT_KELUAR= '".$this->getField("NAMA_JABATAN_TTD_SURAT_KELUAR")."',
					NAMA_PEJABAT_TTD_SURAT_KELUAR= '".$this->getField("NAMA_PEJABAT_TTD_SURAT_KELUAR")."',
					PANGKAT_PEJABAT_TTD_SURAT_KELUAR= '".$this->getField("PANGKAT_PEJABAT_TTD_SURAT_KELUAR")."',
					NIP_PEJABAT_TTD_SURAT_KELUAR= '".$this->getField("NIP_PEJABAT_TTD_SURAT_KELUAR")."',
					PEGAWAI_NAMA= '".$this->getField("PEGAWAI_NAMA")."',
					PEGAWAI_NIP_BARU= '".$this->getField("PEGAWAI_NIP_BARU")."',
					PEGAWAI_PANGKAT= '".$this->getField("PEGAWAI_PANGKAT")."',
					PEGAWAI_TTL= '".$this->getField("PEGAWAI_TTL")."',
					PEGAWAI_PENDIDIKAN= '".$this->getField("PEGAWAI_PENDIDIKAN")."',
					PEGAWAI_JABATAN= '".$this->getField("PEGAWAI_JABATAN")."',
					PEGAWAI_SATUAN_KERJA= '".$this->getField("PEGAWAI_SATUAN_KERJA")."',
					PENDIDIKAN_NAMA= '".$this->getField("PENDIDIKAN_NAMA")."',
					PENDIDIKAN_JURUSAN= '".$this->getField("PENDIDIKAN_JURUSAN")."',
					PENDIDIKAN_SEKOLAH= '".$this->getField("PENDIDIKAN_SEKOLAH")."'
				WHERE CETAK_IJIN_BELAJAR_ID = ".$this->getField("CETAK_IJIN_BELAJAR_ID")."
				"; 
		$this->query = $str;
		// echo $str;exit;
		return $this->execQuery($str);
    }
	
	function delete()
	{
        $str = "
				UPDATE PERSURATAN.URAT_MASUK SET
					STATUS = '1',
					LAST_USER = '".$this->getField("LAST_USER")."',
					LAST_DATE = ".$this->getField("LAST_DATE")."
				WHERE CETAK_IJIN_BELAJAR_ID = ".$this->getField("CETAK_IJIN_BELAJAR_ID")."
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
    function selectByParams($paramsArray=array(),$limit=-1,$from=-1, $statement='',$order=' ORDER BY A.CETAK_IJIN_BELAJAR_ID ASC')
	{
		$str = "
		SELECT
			A.CETAK_IJIN_BELAJAR_ID, A.SURAT_MASUK_PEGAWAI_ID, A.JENIS_ID, A.SURAT_MASUK_BKD_ID, A.SURAT_MASUK_UPT_ID, A.PEGAWAI_ID, A.NOMOR_SURAT_KELUAR
			, A.TANGGAL_SURAT_KELUAR, A.NOMOR_SURAT_DINAS, A.TANGGAL_SURAT_DINAS, A.NAMA_JABATAN_TTD_SURAT_KELUAR, A.NAMA_PEJABAT_TTD_SURAT_KELUAR
			, A.PANGKAT_PEJABAT_TTD_SURAT_KELUAR, A.NIP_PEJABAT_TTD_SURAT_KELUAR, A.PEGAWAI_NAMA, A.PEGAWAI_NIP_BARU, A.PEGAWAI_PANGKAT, A.PEGAWAI_TTL
			, A.PEGAWAI_PENDIDIKAN, A.PEGAWAI_JABATAN, A.PEGAWAI_SATUAN_KERJA, A.PENDIDIKAN_NAMA, A.PENDIDIKAN_JURUSAN, A.PENDIDIKAN_SEKOLAH
			, A.LAST_USER, A.LAST_DATE
		FROM PERSURATAN.CETAK_IJIN_BELAJAR A
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
				SELECT COUNT(1) AS ROWCOUNT 
				FROM PERSURATAN.CETAK_IJIN_BELAJAR A
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